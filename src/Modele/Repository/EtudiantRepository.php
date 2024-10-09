<?php

namespace App\GenerateurAvis\Modele\Repository;

use App\GenerateurAvis\Modele\DataObject\Etudiant;
use Random\RandomException;

class EtudiantRepository
{
    private static string $tableEtudiant = "EtudiantTest";

    public static function recupererEtudiants(): array
    {
        $pdoStatement = ConnexionBaseDeDonnees::getPdo()->query("SELECT * FROM " . self::$tableEtudiant);

        $tableauEtudiant = [];
        foreach ($pdoStatement as $EtudiantFormatTableau) {
            $tableauEtudiant[] = self::construireEtudiantDepuisTableauSQL($EtudiantFormatTableau);
        }
        return $tableauEtudiant;
    }

    public static function recupererEtudiantsOrdonneParNom(): array
    {
        $pdoStatement = ConnexionBaseDeDonnees::getPdo()->query("SELECT * FROM " . self::$tableEtudiant . " ORDER BY nom");

        $tableauEtudiant = [];
        foreach ($pdoStatement as $EtudiantFormatTableau) {
            $tableauEtudiant[] = self::construireEtudiantDepuisTableauSQL($EtudiantFormatTableau);
        }
        return $tableauEtudiant;
    }


    public static function recupererEtudiantsOrdonneParMoyenne(): array
    {
        $pdoStatement = ConnexionBaseDeDonnees::getPdo()->query("SELECT * FROM " . self::$tableEtudiant . " ORDER BY moyenne DESC");

        $tableauEtudiant = [];
        foreach ($pdoStatement as $EtudiantFormatTableau) {
            $tableauEtudiant[] = self::construireEtudiantDepuisTableauSQL($EtudiantFormatTableau);
        }
        return $tableauEtudiant;
    }


    /**
     * @throws RandomException
     */
    public static function recupererEtudiantParLogin(string $login): ?Etudiant
    {
        $sql = "SELECT * from " . self::$tableEtudiant . " WHERE login = :loginTag";
        // Préparation de la requête
        $pdoStatement = ConnexionBaseDeDonnees::getPdo()->prepare($sql);

        $values = array(
            "loginTag" => $login,
            //nomdutag => valeur, ...
        );
        // On donne les valeurs et on exécute la requête
        $pdoStatement->execute($values);

        // On récupère les résultats comme précédemment
        // Note: fetch() renvoie false si pas d'utilisateur correspondant
        $EtudiantFormatTableau = $pdoStatement->fetch();
        if (!$EtudiantFormatTableau)
            return null;

        return self::construireEtudiantDepuisTableauSQL($EtudiantFormatTableau);
    }

    public static function ajouter(Etudiant $Etudiant): bool
    {
        $sql = "INSERT INTO " . self::$tableEtudiant . " (login, nom,prenom, moyenne, codeUnique) VALUES (:loginTag, :nomTag, :prenomTag, :moyenneTag, :codeUniqueTag);";
        $pdoStatement = ConnexionBaseDeDonnees::getPdo()->prepare($sql);

        $values = array(
            "loginTag" => $Etudiant->getLogin(),
            "nomTag" => $Etudiant->getNom(),
            "prenomTag" => $Etudiant->getPrenom(),
            "moyenneTag" => $Etudiant->getMoyenne(),
            "codeUniqueTag" => $Etudiant->getCodeUnique()
        );

        return $pdoStatement->execute($values);
    }


    /**
     * @throws RandomException
     */
    public static function construireEtudiantDepuisTableauSQL(array $EtudiantFormatTableau): Etudiant
    {
        return new Etudiant($EtudiantFormatTableau['login'],
            $EtudiantFormatTableau['nom'],
            $EtudiantFormatTableau['prenom'],
            $EtudiantFormatTableau['moyenne']);
    }

    public static function supprimerEtudiantParLogin(string $login): bool
    {
        $sql = "DELETE FROM " . self::$tableEtudiant . " WHERE login = :loginTag;";
        $pdoStatement = ConnexionBaseDeDonnees::getPdo()->prepare($sql);

        $values = array(
            "loginTag" => $login
        );

        return $pdoStatement->execute($values);
    }

    public static function mettreAJourEtudiant(Etudiant $Etudiant): void
    {
        $sql = "UPDATE " . self::$tableEtudiant . " SET nom = :nomTag, prenom= :prenomTag, moyenne = :moyenneTag WHERE login = :loginTag;";
        $pdoStatement = ConnexionBaseDeDonnees::getPdo()->prepare($sql);

        $values = array(
            "loginTag" => $Etudiant->getLogin(),
            "nomTag" => $Etudiant->getNom(),
            "prenomTag" => $Etudiant->getPrenom(),
            "moyenneTag" => $Etudiant->getMoyenne()
        );

        $pdoStatement->execute($values);
    }

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
            $tableauEtudiant[] = self::construireEtudiantDepuisTableauSQL($etudiantFormatTableau);

        }

        // On récupère les résultats comme précédemment
        // Note: fetch() renvoie false si pas d'utilisateur correspondant


        return $tableauEtudiant;
    }

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

        return self::construireEtudiantDepuisTableauSQL($etudiantFormatTableau);
    }

}