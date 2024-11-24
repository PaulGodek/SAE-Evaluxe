<?php

namespace App\GenerateurAvis\Controleur;

require __DIR__ . '/../../bootstrap.php';

use App\GenerateurAvis\Lib\MessageFlash;
use App\GenerateurAvis\Modele\DataObject\Etudiant;
use App\GenerateurAvis\Modele\Repository\AdministrateurRepository;
use App\GenerateurAvis\Modele\Repository\ConnexionBaseDeDonnees;
use App\GenerateurAvis\Modele\Repository\EtudiantRepository;
use App\GenerateurAvis\Modele\Repository\UtilisateurRepository;
use Exception;
use PDOException;
use Shuchkin\SimpleXLSX;

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

}