<?php

namespace App\GenerateurAvis\Modele\Repository;

use App\GenerateurAvis\Modele\DataObject\AbstractDataObject;
use App\GenerateurAvis\Modele\DataObject\Etudiant;
use PDO;
use Random\RandomException;

class EtudiantRepository extends AbstractRepository
{
    private static string $tableEtudiant = "EtudiantTest";

    /**
     * @throws RandomException
     */
    public static function recupererEtudiantsOrdonneParNom(): array
    {
        $pdoStatement = ConnexionBaseDeDonnees::getPdo()->query("SELECT * FROM " . self::$tableEtudiant . " ORDER BY nom");

        $tableauEtudiant = [];
        foreach ($pdoStatement as $EtudiantFormatTableau) {
            $tableauEtudiant[] = (new EtudiantRepository)->construireDepuisTableauSQL($EtudiantFormatTableau);
        }
        return $tableauEtudiant;
    }


    public static function recupererEtudiantsOrdonneParPrenom(): array
    {
        $pdoStatement = ConnexionBaseDeDonnees::getPdo()->query("SELECT * FROM " . self::$tableEtudiant . " ORDER BY prenom ");

        $tableauEtudiant = [];
        foreach ($pdoStatement as $EtudiantFormatTableau) {
            $tableauEtudiant[] = (new EtudiantRepository)->construireDepuisTableauSQL($EtudiantFormatTableau);
        }
        return $tableauEtudiant;
    }

    /*public static function recupererEtudiantsOrdonneParParcours(): array{
        $pdoStatement = ConnexionBaseDeDonnees::getPdo()->query("SELECT * FROM " . self::$tableEtudiant . " ORDER BY parcours ");

        $tableauEtudiant = [];
        foreach ($pdoStatement as $EtudiantFormatTableau) {
            $tableauEtudiant[] = (new EtudiantRepository)->construireDepuisTableauSQL($EtudiantFormatTableau);
        }
        return $tableauEtudiant;
    }
     */

    /**
     * @throws RandomException
     */
    protected function construireDepuisTableauSQL(array $etudiantFormatTableau): Etudiant
    {
        return new Etudiant($etudiantFormatTableau['login'],
            $etudiantFormatTableau['idEtudiant']);
    }

    /**
     * @throws RandomException
     */
    public static function recupererEtudiantParNom($nom): array
    {
        $sql = "SELECT * from " . self::$tableEtudiant . "  WHERE nom = :nomTag";
        // Préparation de la requête
        $pdoStatement = ConnexionBaseDeDonnees::getPdo()->prepare($sql);

        $values = array(
            "nomTag" => $nom,

        );
        // On donne les valeurs et on exécute la requête
        $pdoStatement->execute($values);

        $tableauEtudiant = [];
        foreach ($pdoStatement as $etudiantFormatTableau) {
            $tableauEtudiant[] = (new EtudiantRepository)->construireDepuisTableauSQL($etudiantFormatTableau);

        }

        // On récupère les résultats comme précédemment
        // Note: fetch() renvoie false si pas d'utilisateur correspondant


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

    protected function getNomTable(): string
    {
        return "EtudiantTest";
    }

    protected function getNomClePrimaire(): string
    {
        return "login";
    }

    protected function getNomsColonnes(): array
    {
        return ["login", "codeUnique", "idEtudiant"];
    }

    protected function formatTableauSQL(AbstractDataObject $etudiant): array
    {
        return array(
            "loginTag" => $etudiant->getLogin(),
            "codeUniqueTag" => $etudiant->getCodeUnique(),
            "idEtudiantTag" => $etudiant->getIdEtudiant()
        );
    }

    public static function rechercherEtudiantParLogin(string $recherche): array
    {

        $sql = "SELECT * FROM " . self::$tableEtudiant .
            " WHERE login LIKE '%" . $recherche . "' OR login LIKE '%" . $recherche . "%' OR login LIKE '" . $recherche . "%' OR login='" . $recherche . "'";
        echo $sql;
        $pdoStatement = ConnexionBaseDeDonnees::getPdo()->query($sql);

        $tableauEtudiant = [];
        foreach ($pdoStatement as $etudiantFormatTableau) {
            $tableauEtudiant[] = (new EtudiantRepository)->construireDepuisTableauSQL($etudiantFormatTableau);
        }
        return $tableauEtudiant;

    }

    public static function rechercherEtudiant(string $recherche)
    {

        $sql = "SELECT * FROM " . self::$tableEtudiant .
            " WHERE nom LIKE '%" . $recherche . "' OR nom LIKE '%" . $recherche . "%' OR nom LIKE '" . $recherche . "%'
            OR prenom LIKE '%" . $recherche . "' OR prenom LIKE '%" . $recherche . "%' OR prenom LIKE '" . $recherche . "%'
            OR prenom='" . $recherche . "' OR nom='" . $recherche . "'";
        echo $sql;
        $pdoStatement = ConnexionBaseDeDonnees::getPdo()->query($sql);

        $tableauEtudiant = [];
        foreach ($pdoStatement as $EtudiantFormatTableau) {
            $tableauEtudiant[] = (new EtudiantRepository)->construireDepuisTableauSQL($EtudiantFormatTableau);
        }
        return $tableauEtudiant;

    }

    public static function getNomPrenomParIdEtudiant($idEtudiant): ?array
    {
        $tables = ['semestre1_2024', 'semestre2_2024', 'semestre3_2024', 'semestre4_2024', 'semestre5_2024'];
        $pdo = ConnexionBaseDeDonnees::getPdo();

        foreach ($tables as $table) {
            $query = "SELECT Nom, Prénom FROM {$table} WHERE etudid = :idEtudiant";
            $stmt = $pdo->prepare($query);
            $stmt->execute([':idEtudiant' => $idEtudiant]);

            if ($result = $stmt->fetch(PDO::FETCH_ASSOC)) {
                return [
                    'Nom' => $result['Nom'],
                    'Prenom' => $result['Prénom']
                ];
            }
        }

        return null;
    }
}