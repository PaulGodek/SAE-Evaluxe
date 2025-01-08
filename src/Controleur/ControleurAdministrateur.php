<?php

namespace App\GenerateurAvis\Controleur;

require __DIR__ . '/../../bootstrap.php';

use App\GenerateurAvis\Configuration\ConfigurationSite;
use App\GenerateurAvis\Lib\ConnexionUtilisateur;
use App\GenerateurAvis\Lib\MessageFlash;
use App\GenerateurAvis\Lib\MotDePasse;
use App\GenerateurAvis\Modele\DataObject\Administrateur;
use App\GenerateurAvis\Modele\DataObject\Utilisateur;
use App\GenerateurAvis\Modele\Repository\AdministrateurRepository;
use App\GenerateurAvis\Modele\Repository\AgregationRepository;
use App\GenerateurAvis\Modele\Repository\AvisGenereRepository;
use App\GenerateurAvis\Modele\Repository\EtudiantRepository;
use App\GenerateurAvis\Modele\Repository\UtilisateurRepository;
use Exception;

class ControleurAdministrateur extends ControleurGenerique
{
    public static function afficherErreurAdministrateur(string $messageErreur = ""): void
    {
        self::afficherErreur($messageErreur, 'administrateur');
    }

    public static function afficherFormulaireImport(): void
    {
        $cheminCorpsVue = 'administrateur/importForm.php';
        ControleurUtilisateur::afficherVue('vueGenerale.php', [
            "titre" => "Importer fichiers",
            "cheminCorpsVue" => $cheminCorpsVue]);
    }

    /**
     * @throws Exception
     */
    public static function importerExcel(): void
    {
        if (!isset($_FILES['excelFile']) || $_FILES['excelFile']['error'] !== UPLOAD_ERR_OK) {
            throw new Exception("Échec du téléchargement du fichier Excel.");
        }

        $filePath = $_FILES['excelFile']['tmp_name'];
        $fileName = pathinfo($_FILES['excelFile']['name'], PATHINFO_FILENAME);
        if (preg_match('/semestre[-_](\d{1,2})[-_](\d{4})/', $fileName, $matches)) {
            $semesterYear = 'semestre' . $matches[1] . '_' . $matches[2];
        } else {
            $semesterYear = $fileName;
        }

        $tableName = preg_replace('/[^a-zA-Z0-9_]/', '_', $semesterYear);

        $repository = new AdministrateurRepository();
        $sheetData = $repository::parseExcelFile($filePath);

        if (empty($sheetData)) {
            throw new Exception("Le fichier Excel est vide.");
        }

        $columns = $repository::extractColumns(array_shift($sheetData));

        $repository::createDatabaseTable($tableName, $columns);

        $filteredData = array_filter($sheetData, function ($row) use ($columns) {
            $etudidIndex = array_search('etudid', $columns);

            if ($etudidIndex === false || !isset($row[$etudidIndex])) {
                return false;
            }

            $etudidValue = $row[$etudidIndex];

            return is_numeric($etudidValue) && $etudidValue !== 'etud_codes' && $etudidValue !== '';
        });


        if (empty($filteredData)) {
            throw new Exception("Aucune ligne valide à insérer après filtrage des valeurs nulles.");
        }

        $repository::insertDataIntoTable($tableName, $columns, $filteredData);
        $repository::ajouterNouveauSemestre($tableName);

        //MessageFlash::ajouter('success', "Fichier Excel importé avec succès dans un tableau `$tableName`.");
        self::redirectionVersURL("success", "Importation réussie", "afficherListe&controleur=utilisateur");
        exit;
    }

    public static function afficherSemestres(): void
    {
        try {
            $repository = new AdministrateurRepository();
            $semesters = $repository::afficherSemestres();

            $cheminCorpsVue = 'administrateur/semestres.php';
            self::afficherVue('vueGenerale.php', [
                "titre" => "Liste des semestres",
                "cheminCorpsVue" => $cheminCorpsVue,
                "semestres" => $semesters
            ]);
        } catch (Exception $e) {
            self::afficherErreurAdministrateur("Erreur lors de la récupération des semestres : " . $e->getMessage());
        }
    }

    public static function publierSemestre(): void
    {
        try {
            $repository = new AdministrateurRepository();
            if (isset($_POST['nomSemestre'])) {
                $nomSemestre = $_POST['nomSemestre'];
                $result = $repository::publierSemestre($nomSemestre);

                if ($result) {
                    self::redirectionVersURL("success", "Le semestre a été publié", "afficherSemestres&controleur=administrateur");
                    return;
                } else {
                    self::afficherErreurAdministrateur("Erreur lors de la publication du semestre.");
                }
            }
        } catch (Exception $e) {
            self::afficherErreurAdministrateur("Erreur lors de la publication du semestre : " . $e->getMessage());
        }
    }

    public static function supprimerSemestre(): void
    {
        try {
            $repository = new AdministrateurRepository();
            if (isset($_POST['nomSemestre'])) {
                $nomSemestre = $_POST['nomSemestre'];
                $result = $repository::supprimerSemestre($nomSemestre);

                if ($result) {
                    self::redirectionVersURL("success", "Le semestre a été supprimé", "afficherSemestres&controleur=administrateur");
                    return;
                } else {
                    self::afficherErreurAdministrateur("Erreur lors de la suppression du semestre.");
                }
            }
        } catch (Exception $e) {
            self::afficherErreurAdministrateur("Erreur lors de la suppression du semestre : " . $e->getMessage());
        }
    }


    public static function mettreAJour(): void{

        if (!ConnexionUtilisateur::estAdministrateur()) {
            self::afficherErreur("Vous n'avez pas de droit d'accès pour cette page");
            return;
        }
        $adminExistant = (new AdministrateurRepository())->recupererParClePrimaire($_GET['login']);




        $mdp = $_GET['nvmdp'] ?? '';
        $mdp2 = $_GET['nvmdp2'] ?? '';

        if ($mdp !== $mdp2) {
            MessageFlash::ajouter("warning","Les mots de passe ne correspondent pas");
            self::afficherVue('vueGenerale.php', ["admin" => $adminExistant, "titre" => "Formulaire de mise à jour d'un administrateur", "cheminCorpsVue" => "administrateur/formulaireMiseAJourAdministrateur.php"]);
            return;
        }
        $userexistant = (new UtilisateurRepository())->recupererParClePrimaire($_GET['login']);
        if($_GET["nvmdp"]==''){
            $user = new Utilisateur($userexistant->getLogin(), $userexistant->getType(), $userexistant->getPasswordHash());
        }else {
            $user = new Utilisateur($userexistant->getLogin(), $userexistant->getType(), MotDePasse::hacher($_GET["nvmdp"]));
        }        (new UtilisateurRepository)->mettreAJour($user);
        $admin = new Administrateur($user, $_GET["email"]);
        (new AdministrateurRepository)->mettreAJour($admin);


        MessageFlash::ajouter("success", "Votre compte a été mis à jour avec succès.");
        $admin=(new AdministrateurRepository())->recupererParClePrimaire($_GET['login']);
        self::afficherVue('vueGenerale.php', [
            "user" => $admin,
            "titre" => "Compte Administrateur",
            "cheminCorpsVue" => "administrateur/compteAdministrateur.php"
        ]);

    }

    public static function genererAvisAutomatique() : void {
        if (!ConnexionUtilisateur::estAdministrateur()) {
            self::afficherErreur("Vous n'avez pas de droit d'accès pour cette page");
            return;
        }

        $listeEtudiants = (new EtudiantRepository())->recuperer();

        foreach ($listeEtudiants as $etudiant) {
            $agregations1 = AgregationRepository::calculateOneAgregationNote(1, $etudiant->getCodeNip());
            $agregations2 = AgregationRepository::calculateOneAgregationNote(2, $etudiant->getCodeNip());

            $avis1 = match (ConfigurationSite::determinerPassageNotes($agregations1)) {
                "R" => "Reserve",
                "F" => "Favorable",
                default => "Tres Favorable",
            };

            $avis2 = match (ConfigurationSite::determinerPassageNotes($agregations2)) {
                "R" => "Reserve",
                "F" => "Favorable",
                default => "Tres Favorable",
            };

            AvisGenereRepository::creerAvisGenereEtudiant($etudiant->getCodeNip(), $avis1, $avis2);
        }

        MessageFlash::ajouter("success", "Les avis automatiques ont été générés avec succès.");

        $user=(new AdministrateurRepository())->recupererParClePrimaire(ConnexionUtilisateur::getLoginUtilisateurConnecte());

        self::afficherVue('vueGenerale.php', [
            "user" => $user,
            "titre" => "Compte Administrateur",
            "cheminCorpsVue" => "administrateur/compteAdministrateur.php"
        ]);
    }

    public static function parametrerAvisAutomatique() : void {
        if (!ConnexionUtilisateur::estAdministrateur()) {
            self::afficherErreur("Vous n'avez pas de droit d'accès pour cette page");
            return;
        }

        self::afficherVue('vueGenerale.php', [
            "titre" => "Formulaire de paramétrage d'avis",
            "cheminCorpsVue" => "administrateur/parametrerAvis.php"
        ]);
    }

    public static function avisParametre() : void {
        if (!ConnexionUtilisateur::estAdministrateur()) {
            self::afficherErreur("Vous n'avez pas de droit d'accès pour cette page");
            return;
        }

        if (!isset($_GET["note1"]) || !isset($_GET["note2"])) {
            self::afficherErreur("Il n'y a aucune nouvelle note de créée");
            return;
        }
        $note1 = $_GET["note1"];
        $note2 = $_GET["note2"];

        if ($note2 < $note1) {
            MessageFlash::ajouter("warning", "La note de la deuxième case doit être supérieur à la première case");
            self::afficherVue('vueGenerale.php', [
                "titre" => "Formulaire de paramétrage d'avis",
                "cheminCorpsVue" => "administrateur/parametrerAvis.php"
            ]);
            return;
        }

        ConfigurationSite::setBarrier1($note1);
        ConfigurationSite::setBarrier2($note2);
        MessageFlash::ajouter("success", "Les paramètres ont été modifié avec succès");

        $user=(new AdministrateurRepository())->recupererParClePrimaire(ConnexionUtilisateur::getLoginUtilisateurConnecte());

        self::afficherVue('vueGenerale.php', [
            "user" => $user,
            "titre" => "Compte Administrateur",
            "cheminCorpsVue" => "administrateur/compteAdministrateur.php"
        ]);
    }
}