<?php

namespace App\GenerateurAvis\Controleur;

require __DIR__ . '/../../bootstrap.php';

use App\GenerateurAvis\Lib\MessageFlash;
use App\GenerateurAvis\Modele\Repository\ConnexionBaseDeDonnees;
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

        $sheetData = self::parseExcelFile($filePath);

        if (empty($sheetData)) {
            throw new Exception("Le fichier Excel est vide.");
        }

        $columns = self::extractColumns(array_shift($sheetData));

        self::createDatabaseTable($tableName, $columns);

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

        self::insertDataIntoTable($tableName, $columns, $filteredData);

        MessageFlash::ajouter('success', "Fichier Excel importé avec succès dans un tableau `$tableName`.");
        self::redirectionVersURL("success", "Importation réussie", "afficherListe&controleur=utilisateur");
        exit;
    }


    /**
     * @throws Exception
     */
    private static function parseExcelFile(string $filePath): array
    {
        if ($xlsx = SimpleXLSX::parse($filePath)) {
            return $xlsx->rows();
        }
        throw new Exception("Échec de l'analyse du fichier Excel. Erreur : " . SimpleXLSX::parseError());
    }

    private static function extractColumns(array $row): array
    {
        $columns = [];
        $usedColumns = [];

        foreach ($row as $index => $col) {
            $col = trim($col);
            if (array_key_exists($col, self::$columnSynonyms)) {
                $col = self::$columnSynonyms[$col];
            }
            if (in_array($col, $usedColumns)) {
                $counter = 1;
                $originalCol = $col;
                while (in_array($col, $usedColumns)) {
                    $col = $originalCol . "_" . $counter;
                    $counter++;
                }
            }

            $usedColumns[] = $col;

            $columns[] = $col ?: "column_$index";
        }
        return $columns;
    }

    private static array $columnSynonyms = [
        'Groupes' => 'groupes_de_TD',
        'groupes de TD' => 'groupes_de_TD',
    ];

    private static function createDatabaseTable(string $tableName, array $columns): void
    {
        $columnTypeMapping = [
            'etudid' => 'INT(11)',
            'code_nip' => 'VARCHAR(50)',
            'Rg' => 'VARCHAR(50)',
            'Civ.' => 'VARCHAR(50)',
            'Nom' => 'VARCHAR(50)',
            'Prénom' => 'VARCHAR(50)',
            'Nom_1' => 'VARCHAR(100)',
            'Abs' => 'INT(11)',
            'Just.' => 'INT(11)',
            'UEs' => 'VARCHAR(255)',
            'groupes_de_TD' => 'VARCHAR(100)',
            'Cursus' => 'VARCHAR(100)',
            'Bac' => 'VARCHAR(100)',
            'Spécialité' => 'VARCHAR(500)',
            'Type Adm.' => 'VARCHAR(100)',
            'Rg. Adm.' => 'INT(11)',
            'Parcours' => 'VARCHAR(10)',
        ];

        $defaultType = 'FLOAT DEFAULT NULL';

        $columnDefinitions = [];
        foreach ($columns as $index => $col) {
            $sanitizedCol = "`$col`";

            $dataType = $columnTypeMapping[$col] ?? $defaultType;

            if ($index === 0 && $col === 'etudid') {
                $columnDefinitions[] = "$sanitizedCol $dataType PRIMARY KEY";
            } else {
                $columnDefinitions[] = "$sanitizedCol $dataType";
            }
        }

        $dropTableQuery = "DROP TABLE IF EXISTS `$tableName`";

        $createTableQuery = "CREATE TABLE IF NOT EXISTS `$tableName` (" . implode(',', $columnDefinitions) . ") ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 ROW_FORMAT=DYNAMIC";

        $pdo = ConnexionBaseDeDonnees::getPdo();
        $pdo->exec($dropTableQuery);
        $pdo->exec($createTableQuery);
    }

    /**
     * @throws Exception
     */
    private static function insertDataIntoTable(string $tableName, array $columns, array $data): void
    {
        $sanitizedColumns = array_map(fn($col) => "`$col`", $columns);

        $insertQuery = "INSERT INTO `$tableName` (" . implode(',', $sanitizedColumns) . ") VALUES (" . str_repeat('?,', count($columns) - 1) . "?)";
        $pdo = ConnexionBaseDeDonnees::getPdo();
        $stmt = $pdo->prepare($insertQuery);

        foreach ($data as $rowIndex => $row) {
            $row = array_slice($row, 0, count($columns));

            $row = array_map(function ($value) {
                if ($value === '' || $value === '~') {
                    return null;
                }
                return is_string($value) ? mb_convert_encoding($value, 'UTF-8', 'auto') : $value;
            }, $row);

            try {
                $stmt->execute($row);
            } catch (PDOException $e) {
                throw new Exception("Erreur d'insertion d'une ligne #$rowIndex: " . $e->getMessage());
            }
        }
    }


}