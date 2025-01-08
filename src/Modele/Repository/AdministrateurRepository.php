<?php

namespace App\GenerateurAvis\Modele\Repository;

use App\GenerateurAvis\Modele\DataObject\AbstractDataObject;
use App\GenerateurAvis\Modele\DataObject\Administrateur;
use Exception;
use PDO;
use PDOException;
use Shuchkin\SimpleXLSX;

class AdministrateurRepository extends AbstractRepository
{
    private static string $tableAdmin = "AdminTest";
    private static string $tableSemestre = "semestres";

    public function getNomTable(): string
    {
        return self::$tableAdmin;
    }

    public function getNomTableSemestre(): string
    {
        return self::$tableSemestre;
    }

    protected function getNomClePrimaire(): string
    {
        return "login";
    }

    protected function construireDepuisTableauSQL(array $adminFormatTableau): AbstractDataObject
    {
        return new Administrateur(
            (new UtilisateurRepository())->recupererParClePrimaire($adminFormatTableau["login"]),
            $adminFormatTableau['adresseMail']
        );
    }

    protected function getNomsColonnes(): array
    {
        return ["login", "adresseMail"];
    }

    protected function formatTableauSQL(AbstractDataObject $admin): array
    {
        return array(
            "loginTag" => $admin->getAdministrateur()->getLogin(),
            "adresseMailTag" => $admin->getAdresseMail()
        );
    }


    /**
     * @throws Exception
     */
    public static function parseExcelFile(string $filePath): array
    {
        if ($xlsx = SimpleXLSX::parse($filePath)) {
            return $xlsx->rows();
        }
        throw new Exception("Échec de l'analyse du fichier Excel. Erreur : " . SimpleXLSX::parseError());
    }

    public static function extractColumns(array $row): array
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

    public static array $columnSynonyms = [
        'Groupes' => 'groupes_de_TD',
        'groupes de TD' => 'groupes_de_TD',
    ];

    public static function createDatabaseTable(string $tableName, array $columns): void
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
    public static function insertDataIntoTable(string $tableName, array $columns, array $data): void
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
            UtilisateurRepository::creerUtilisateur($row[4], $row[5]);
            EtudiantRepository::creerEtudiant($row[4], $row[5], $row[1]);
            EtudiantRepository::creerDetailEtudiant($row[1], $row[3], $row[4], $row[5]);
            $idxParcours = 0;
            foreach ($columns as $col) {
                if (strcmp($col, "Parcours") === 0) {
                    break;
                }
                $idxParcours++;
            }
            if (!is_null($row[$idxParcours])) {
                EtudiantRepository::creerParcoursEtudiant($row[1], $row[$idxParcours]);
            }
        }
        self::removeSuffixColumns($tableName);

    }

    public static function removeSuffixColumns(string $tableName): void
    {
        $pdo = ConnexionBaseDeDonnees::getPdo();

        $sql = "DESCRIBE `$tableName`";
        $stmt = $pdo->query($sql);
        $columns = $stmt->fetchAll(PDO::FETCH_ASSOC);

        //$columnsToRemove = [];
        foreach ($columns as $column) {
            $colName = $column['Field'];
            if (preg_match('/_([0-9]+)$/', $colName)) {
                $dropColumnsSql = "ALTER TABLE `$tableName` DROP COLUMN  `$colName`";
                $pdo->exec($dropColumnsSql);
            }
        }

//        if (!empty($columnsToRemove)) {
//            $dropColumnsSql = "ALTER TABLE `$tableName` DROP COLUMN " . implode(", ", $columnsToRemove);
//            try {
//                $pdo->exec($dropColumnsSql);
//            } catch (PDOException $e) {
//                throw new Exception("Erreur lors de la suppression des colonnes : " . $e->getMessage());
//            }
//        }
    }


    public static function publierSemestre(string $nomSemestre): bool
    {
        $sql = "UPDATE " . self::$tableSemestre . " SET estPublie = TRUE WHERE nomTable = :table";
        $pdoStatement = ConnexionBaseDeDonnees::getPdo()->prepare($sql);
        return $pdoStatement->execute([':table' => $nomSemestre]);
    }

    public static function supprimerSemestre(string $nomSemestre): bool
    {
        $pdo = ConnexionBaseDeDonnees::getPdo();

        $sql = "DELETE FROM " . self::$tableSemestre . " WHERE nomTable = :nom";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([':nom' => $nomSemestre]);

        $result = $pdo->exec("DROP TABLE IF EXISTS {$nomSemestre}");

        return $result !== false;
    }

    public static function afficherSemestres(): false|array
    {
        $pdo = ConnexionBaseDeDonnees::getPdo();
        $sql = "SELECT nomTable, estPublie FROM " . self::$tableSemestre . ";";
        $stmt = $pdo->prepare($sql);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function ajouterNouveauSemestre(string $tableName): bool
    {
        $sql = "INSERT INTO " . self::$tableSemestre . " (nomTable) VALUES (:name)";
        $pdoStatement = ConnexionBaseDeDonnees::getPdo()->prepare($sql);
        return $pdoStatement->execute([':name' => $tableName]);
    }
}