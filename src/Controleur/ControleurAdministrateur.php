<?php

namespace App\GenerateurAvis\Controleur;

require __DIR__ . '/../../bootstrap.php';

use App\GenerateurAvis\Lib\ConnexionUtilisateur;
use App\GenerateurAvis\Lib\MessageFlash;
use App\GenerateurAvis\Lib\MotDePasse;
use App\GenerateurAvis\Modele\DataObject\Administrateur;
use App\GenerateurAvis\Modele\DataObject\Utilisateur;
use App\GenerateurAvis\Modele\Repository\AdministrateurRepository;
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

}