<?php

namespace App\GenerateurAvis\Modele\Repository;

use App\GenerateurAvis\Lib\ConnexionUtilisateur;
use App\GenerateurAvis\Modele\DataObject\AbstractDataObject;
use App\GenerateurAvis\Modele\DataObject\Ecole;
use App\GenerateurAvis\Modele\DataObject\Etudiant;
use App\GenerateurAvis\Modele\DataObject\Professeur;
use PDO;
use PDOException;
use Random\RandomException;

class EtudiantRepository extends AbstractRepository
{
    private static string $tableEtudiant = "EtudiantImportation";

    public function getNomTable(): string
    {
        return self::$tableEtudiant;
    }

    protected function getNomClePrimaire(): string
    {
        return "login";
    }

    protected function getNomsColonnes(): array
    {
        return ["login", "codeUnique", "code_nip", "demandes"];
    }

    protected function formatTableauSQL(AbstractDataObject $etudiant): array
    {
        return array(
            "loginTag" => $etudiant->getUtilisateur()->getLogin(),
            "codeUniqueTag" => $etudiant->getCodeUnique(),
            "code_nipTag" => $etudiant->getCodeNip(),
            "demandesTag" => $etudiant->getDemandes(),
        );
    }

    /**
     * @return array
     */
    private static function getTablesSemestrePublie(): array
    {
        $pdo = ConnexionBaseDeDonnees::getPdo();
        $sql = "SELECT nomTable FROM " . (new AdministrateurRepository())->getNomTableSemestre() . " WHERE estPublie = TRUE";
        $stmt = $pdo->query($sql);
        $tables = $stmt->fetchAll(PDO::FETCH_COLUMN);
        //$tables = ['OLDsemestre1_2024', 'OLDsemestre2_2024', 'OLDsemestre3_2024', 'OLDsemestre4_2024', 'OLDsemestre5_2024'];
        return $tables;
    }

    private static function getCorpsSQLSemestre(array $tablesSemestre): string
    {
        $tempSql = "SELECT Distinct * FROM " . self::$tableEtudiant . " e ";
        foreach ($tablesSemestre as $table) {
            $tempSql = $tempSql . "JOIN $table ON $table.code_nip = e.code_nip ";
        }
        return $tempSql;
    }

    public static function getCorpsOrderBy(string $parametre, array $tablesSemestre): string
    {
        $tempSql = "ORDER BY ";
        for ($i = 0; $i < count($tablesSemestre) - 1; $i++) {
            $tempSql = $tempSql . $tablesSemestre[$i] . "." . $parametre . ", ";
        }
        $tempSql = $tempSql . $tablesSemestre[count($tablesSemestre) - 1] . "." . $parametre;
        return $tempSql;
    }

    /**
     * @throws RandomException
     */
    public static function recupererEtudiantsOrdonneParNom(): array
    {//Pire façon de faire, il va falloir changer ça pour le sprint suivant, ce n'est que temporaire
        $tables = self::getTablesSemestrePublie();
        $sql = self::getCorpsSQLSemestre($tables);
        $sql = $sql . " " . self::getCorpsOrderBy("Nom", $tables) . ";";

        $pdoStatement = ConnexionBaseDeDonnees::getPdo()->query($sql);

        $tableauEtudiant = [];
        foreach ($pdoStatement as $EtudiantFormatTableau) {
            $tableauEtudiant[] = (new EtudiantRepository)->construireDepuisTableauSQL($EtudiantFormatTableau);
        }
        return $tableauEtudiant;
    }


    public static function recupererEtudiantsOrdonneParPrenom(): array
    {//Pire façon de faire, il va falloir changer ça pour le sprint suivant, ce n'est que temporaire
        $tables = self::getTablesSemestrePublie();
        $sql = self::getCorpsSQLSemestre($tables);
        $sql = $sql . " " . self::getCorpsOrderBy("Prénom", $tables) . ";";

        $pdoStatement = ConnexionBaseDeDonnees::getPdo()->query($sql);

        $tableauEtudiant = [];
        foreach ($pdoStatement as $EtudiantFormatTableau) {
            $tableauEtudiant[] = (new EtudiantRepository)->construireDepuisTableauSQL($EtudiantFormatTableau);
        }
        return $tableauEtudiant;
    }

    public static function recupererEtudiantsOrdonneParParcours(): array
    {
        $tables = self::getTablesSemestrePublie();
        $sql = self::getCorpsSQLSemestre($tables);
        $sql = $sql . " " . self::getCorpsOrderBy("Parcours", $tables) . ";";

        $pdoStatement = ConnexionBaseDeDonnees::getPdo()->query($sql);

        $tableauEtudiant = [];
        foreach ($pdoStatement as $EtudiantFormatTableau) {
            $tableauEtudiant[] = (new EtudiantRepository)->construireDepuisTableauSQL($EtudiantFormatTableau);
        }
        return $tableauEtudiant;
    }

    /**
     * @throws RandomException
     */
    protected function construireDepuisTableauSQL(array $etudiantFormatTableau): Etudiant
    {
        return new Etudiant((new UtilisateurRepository())->recupererParClePrimaire($etudiantFormatTableau['login']),
            $etudiantFormatTableau['code_nip'],
            json_decode($etudiantFormatTableau['demandes']),
            $etudiantFormatTableau['codeUnique']);
    }

    /**
     * @throws RandomException
     */
    public static function recupererEtudiantParNom($nom): array
    {
        $sql = "SELECT * from " . self::$tableEtudiant . "  WHERE nom = :nomTag";
        $pdoStatement = ConnexionBaseDeDonnees::getPdo()->prepare($sql);

        $values = array(
            "nomTag" => $nom,
        );

        $pdoStatement->execute($values);

        $tableauEtudiant = [];
        foreach ($pdoStatement as $etudiantFormatTableau) {
            $tableauEtudiant[] = (new EtudiantRepository)->construireDepuisTableauSQL($etudiantFormatTableau);
        }

        return $tableauEtudiant;
    }

    /**
     * @throws RandomException
     */
    public static function recupererEtudiantParCodeUnique(string $codeUnique): ?Etudiant
    {

        $sql = "SELECT * FROM " . self::$tableEtudiant . " WHERE codeUnique = :codeUniqueTag";
        $pdoStatement = ConnexionBaseDeDonnees::getPdo()->prepare($sql);

        $values = array(
            "codeUniqueTag" => $codeUnique,
        );

        $pdoStatement->execute($values);
        $etudiantFormatTableau = $pdoStatement->fetch();

        if (!$etudiantFormatTableau) {
            return null;
        }

        return (new EtudiantRepository)->construireDepuisTableauSQL($etudiantFormatTableau);
    }

    public static function rechercherEtudiantParLogin(string $recherche): array
    {
        // Construire correctement la requête avec des jokers
        $sql = "SELECT * FROM " . self::$tableEtudiant . "
            WHERE login LIKE :rechercheTag1 
            OR login LIKE :rechercheTag2 
            OR login LIKE :rechercheTag3 
            OR login = :rechercheTag4";

        // Préparer la requête
        $pdoStatement = ConnexionBaseDeDonnees::getPdo()->prepare($sql);

        // Ajouter les jokers à la valeur de recherche
        $values = [
            "rechercheTag1" => '%' . $recherche,
            "rechercheTag2" => '%' . $recherche . '%',
            "rechercheTag3" => $recherche . '%',
            "rechercheTag4" => $recherche
        ];

        // Exécuter la requête
        $pdoStatement->execute($values);

        // Construire les objets étudiant à partir des résultats
        $tableauEtudiant = [];
        foreach ($pdoStatement as $etudiantFormatTableau) {
            $tableauEtudiant[] = (new EtudiantRepository)->construireDepuisTableauSQL($etudiantFormatTableau);
        }

        return $tableauEtudiant;
    }

    //Pour plus tard
    /*public static function rechercherEtudiant(string $recherche)
    {

        $sql = "SELECT * FROM " . self::$tableEtudiant .
            " WHERE nom LIKE :rechercheTag1
            OR nom LIKE :rechercheTag2
            OR nom LIKE :rechercheTag3
            OR nom = :rechercheTag4
            OR prenom LIKE :rechercheTag1
            OR prenom LIKE :rechercheTag2
            OR prenom LIKE :rechercheTag3
            OR prenom = :rechercheTag4 ";

        $pdoStatement = ConnexionBaseDeDonnees::getPdo()->prepare($sql);

        // Ajouter les jokers à la valeur de recherche
        $values = [
            "rechercheTag1" => '%' . $recherche,
            "rechercheTag2" => '%' . $recherche . '%',
            "rechercheTag3" => $recherche . '%',
            "rechercheTag4" => $recherche
        ];

        $pdoStatement->execute($values);
        $tableauEtudiant = [];
        foreach ($pdoStatement as $EtudiantFormatTableau) {
            $tableauEtudiant[] = (new EtudiantRepository)->construireDepuisTableauSQL($EtudiantFormatTableau);
        }
        return $tableauEtudiant;

    }*/

    public static function getNomPrenomParCodeNip($code_nip): ?array
    {
        $pdo = ConnexionBaseDeDonnees::getPdo();
        $tables = self::getTablesSemestrePublie();

        foreach ($tables as $table) {
            $query = "SELECT Nom, Prénom FROM {$table} WHERE code_nip = :code_nipTag";
            $stmt = $pdo->prepare($query);
            $stmt->execute([':code_nipTag' => $code_nip]);

            if ($result = $stmt->fetch(PDO::FETCH_ASSOC)) {
                return [
                    'Nom' => $result['Nom'],
                    'Prenom' => $result['Prénom']
                ];
            }
        }

        return null;
    }

    public static function recupererDetailsEtudiantParCodeNip($code_nip): array
    {
        $pdo = ConnexionBaseDeDonnees::getPdo();
        $tables = self::getTablesSemestrePublie();
        $etudiantInfo = null;
        $etudiantDetailsPerSemester = [];

        foreach ($tables as $table) {
            preg_match('/semestre(\d+)_/', $table, $matches);
            $semesterNumber = isset($matches[1]) ? (int)$matches[1] : 0;

            $ueColumns = [];
            for ($i = 1; $i <= 6; $i++) {
                $column = "UE {$semesterNumber}.{$i}";
                if (self::columnExists($pdo, $table, $column)) {
                    $ueColumns[] = "`{$column}` AS `UE_{$semesterNumber}_{$i}`";
                }
            }
            $ueColumnsString = implode(', ', $ueColumns);

            if (empty($ueColumnsString)) {
                continue;
            }

            $query = "SELECT Nom, Prénom, Abs, 'Just.', Moy, Parcours, {$ueColumnsString} FROM {$table} WHERE code_nip = :code_nipTag";
            $stmt = $pdo->prepare($query);
            $stmt->execute([':code_nipTag' => $code_nip]);

            if ($details = $stmt->fetch(PDO::FETCH_ASSOC)) {
                if (!$etudiantInfo) {
                    $etudiantInfo = [
                        'nom' => htmlspecialchars($details['Nom']),
                        'prenom' => htmlspecialchars($details['Prénom']),
                    ];
                }

                $absences = (int)htmlspecialchars($details['Abs'] ?? '0');
                $justifications = (int)htmlspecialchars($details['Just.'] ?? '0');
                $moyenne = (float)htmlspecialchars($details['Moy'] ?? '0');
                $parcours = htmlspecialchars($details['Parcours'] ?? '-');

                $ueDetails = [];
                for ($i = 1; $i <= 6; $i++) {
                    $ueKey = "UE_{$semesterNumber}_{$i}";
                    $ueDetails[] = [
                        'ue' => "UE {$semesterNumber}.{$i}",
                        'moy' => isset($details[$ueKey]) ? (float)$details[$ueKey] : 'N/A',
                    ];
                }

                $etudiantDetailsPerSemester[$table] = [
                    'abs' => $absences,
                    'just1' => $justifications,
                    'moyenne' => $moyenne,
                    'parcours' => $parcours,
                    'ue_details' => $ueDetails,
                ];
            }
        }

        return [
            'info' => $etudiantInfo,
            'details' => $etudiantDetailsPerSemester,
        ];
    }

    public static function recupererTousLesDetailsEtudiantParCodeNip($code_nip): array
    {
        $pdo = ConnexionBaseDeDonnees::getPdo();
        if (ConnexionUtilisateur::estAdministrateur() || ConnexionUtilisateur::estProfesseur()) {
            $sql = "SELECT nomTable FROM " . (new AdministrateurRepository())->getNomTableSemestre() . ";";
            $stmt = $pdo->query($sql);
            $tables = $stmt->fetchAll(PDO::FETCH_COLUMN);
        } else {
            $tables = self::getTablesSemestrePublie();
        }

        //$tables = ['semestre1_2024', 'semestre2_2024', 'semestre3_2024', 'semestre4_2024', 'semestre5_2024'];
        $etudiantInfo = null;
        $etudiantDetailsPerSemester = [];

        foreach ($tables as $table) {
            preg_match('/semestre(\d+)_/', $table, $matches);

            $query = "SELECT * FROM {$table} WHERE code_nip = :code_nipTag";
            $stmt = $pdo->prepare($query);
            $stmt->execute([':code_nipTag' => $code_nip]);

            if ($details = $stmt->fetch(PDO::FETCH_ASSOC)) {
                if (!$etudiantInfo) {
                    $etudiantInfo = [
                        'nom' => htmlspecialchars($details['Nom']),
                        'prenom' => htmlspecialchars($details['Prénom']),
                        'nomEtPrenom' => htmlspecialchars($details['Nom_1']),
                        'etudid' => htmlspecialchars($details['etudid']),
                        'codenip' => htmlspecialchars($details['code_nip']),
                        'civ' => htmlspecialchars($details['Civ.']),
                        'bac' => htmlspecialchars($details['Bac'] ?? 'N/A'),
                        'specialite' => htmlspecialchars($details['Spécialité'] ?? 'N/A'),
                        'typeAdm' => htmlspecialchars($details['Type Adm.'] ?? 'N/A'),
                        'rgAdm' => htmlspecialchars($details['Rg. Adm.'] ?? 'N/A'),
                    ];
                }

                unset($details['Nom'], $details['Prénom'], $details['Nom_1'], $details['etudid'], $details['code_nip'], $details['Civ'], $details['Bac'], $details['Spécialité'], $details['Rg. Adm.'], $details['Type Adm.']);

                $etudiantDetailsPerSemester[$table] = array_map(function ($value) {
                    return htmlspecialchars($value ?? '');
                }, $details);
            }
        }

        return [
            'info' => $etudiantInfo,
            'details' => $etudiantDetailsPerSemester,
        ];
    }


    private static function columnExists($pdo, $table, $column): bool
    {
        $query = "SELECT COUNT(*) FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_NAME = :table AND COLUMN_NAME = :column";
        $stmt = $pdo->prepare($query);
        $stmt->execute([':table' => $table, ':column' => $column]);
        return (bool)$stmt->fetchColumn();
    }

    public static function getCodeUniqueEtudiantConnecte(): string
    {
        return (new EtudiantRepository())->recupererParClePrimaire(ConnexionUtilisateur::getLoginUtilisateurConnecte())->getCodeUnique();
    }

    public static function demander($etudiant): bool
    {
        $sql = "UPDATE " . self::$tableEtudiant . " 
            SET demandes = :demandeTag 
            WHERE login = :loginTag;";

        $pdoStatement = ConnexionBaseDeDonnees::getPdo()->prepare($sql);

        $demandesSTR = json_encode($etudiant->getDemandes());

        $values = [
            "loginTag" => $etudiant->getUtilisateur()->getLogin(),
            "demandeTag" => $demandesSTR
        ];

        return $pdoStatement->execute($values);

    }


    public static function mettreAJourDemandes(Etudiant $etudiant): bool
    {
        $sql = "UPDATE " . self::$tableEtudiant . " 
            SET demandes = :demandes 
            WHERE login = :loginTag;";

        $pdoStatement = ConnexionBaseDeDonnees::getPdo()->prepare($sql);

        $demandesSTR = json_encode($etudiant->getDemandes());

        $values = [
            "loginTag" => $etudiant->getUtilisateur()->getLogin(),
            "demandes" => $demandesSTR
        ];

        return $pdoStatement->execute($values);
    }


    public static function creerEtudiant(string $nom, string $prenom, int $code_nip)
    {
        try {
            $sql = 'INSERT INTO EtudiantImportation (login,codeUnique,code_nip,demandes) Values (:loginTag, :codeUniqueTag, :code_nipTag, :demandesTag)';
            $pdoStatement = ConnexionBaseDeDonnees::getPdo()->prepare($sql);

            $login = mb_strtolower($nom .= substr($prenom, 0, 1), "UTF-8");
            $values = [
                "loginTag" => $login,
                "codeUniqueTag" => Etudiant::genererCodeUnique(),
                "code_nipTag" => $code_nip,
                "demandesTag" => '[""]'
            ];

            $pdoStatement->execute($values);
        } catch (PDOException $e) {
            if ($e->getCode() == '45000') {

            }
        }
    }
}