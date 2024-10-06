<?php

namespace App\GenerateurAvis\Modele\Repository;

use App\GenerateurAvis\Modele\DataObject\Ecole;

class EcoleRepository
{
    private static string $tableEcole= "EcoleTest";

    public static function recupererEcoles() : array
    {
        $pdoStatement = ConnexionBaseDeDonnees::getPdo()->query("SELECT * FROM ".self::$tableEcole);

        $tableauEcole = [];
        foreach ($pdoStatement as $EcoleFormatTableau) {
            $tableauEcole[] = self::construireEcoleDepuisTableauSQL($EcoleFormatTableau);
        }
        return $tableauEcole;
    }

    public static function recupererEcolesOrdonneParNom() : array
    {
        $pdoStatement = ConnexionBaseDeDonnees::getPdo()->query("SELECT * FROM ".self::$tableEcole." ORDER BY nom");

        $tableauEcole = [];
        foreach ($pdoStatement as $EcoleFormatTableau) {
            $tableauEcole[] = self::construireEcoleDepuisTableauSQL($EcoleFormatTableau);
        }
        return $tableauEcole;
    }




    public static function recupererEcolesOrdonneParAdresse() : array
    {
        $pdoStatement = ConnexionBaseDeDonnees::getPdo()->query("SELECT * FROM ".self::$tableEcole." ORDER BY adresse");

        $tableauEcole = [];
        foreach ($pdoStatement as $EcoleFormatTableau) {
            $tableauEcole[] = self::construireEcoleDepuisTableauSQL($EcoleFormatTableau);
        }
        return $tableauEcole;
    }




    public static function recupererEcoleParLogin(string $login) : ?Ecole {
        $sql = "SELECT * from ".self::$tableEcole." WHERE login = :loginTag";
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
        $ecoleFormatTableau = $pdoStatement->fetch();
        if (!$ecoleFormatTableau)
            return null;

        return self::construireEcoleDepuisTableauSQL($ecoleFormatTableau);
    }

    public static function ajouter(Ecole $ecole) : bool {
        $sql = "INSERT INTO ".self::$tableEcole." (login, nom, adresse) VALUES (:loginTag, :nomTag, :adresseTag);";
        $pdoStatement = ConnexionBaseDeDonnees::getPdo()-> prepare($sql);

        $values = array(
            "loginTag" => $ecole->getLogin(),
            "nomTag" => $ecole->getNom(),
            "adresseTag" => $ecole->getAdresse()
        );

        return $pdoStatement->execute($values);
    }

    public static function construireEcoleDepuisTableauSQL( array $ecoleFormatTableau) : Ecole
    {
        return new Ecole($ecoleFormatTableau['login'],
            $ecoleFormatTableau['nom'],
            $ecoleFormatTableau['adresse']);
    }

    public static function supprimerEcoleParLogin(string $login) : bool {
        $sql = "DELETE FROM ".self::$tableEcole." WHERE login = :loginTag;";
        $pdoStatement = ConnexionBaseDeDonnees::getPdo()->prepare($sql);

        $values = array(
            "loginTag" => $login
        );

        return $pdoStatement->execute($values);
    }

    public static function mettreAJour(Ecole $ecole) : void {
        $sql = "UPDATE ".self::$tableEcole." SET nom = :nomTag, adresse = :adresseTag WHERE login = :loginTag;";
        $pdoStatement = ConnexionBaseDeDonnees::getPdo()-> prepare($sql);

        $values = array(
            "loginTag" => $ecole->getLogin(),
            "nomTag" => $ecole->getNom(),
            "adresseTag" => $ecole->getAdresse()
        );

        $pdoStatement->execute($values);
    }


    public static function recupererEcoleParNom($nom){
        $sql = "SELECT * from ".self::$tableEcole."  WHERE nom = :nomTag";
        // Préparation de la requête
        $pdoStatement = ConnexionBaseDeDonnees::getPdo()->prepare($sql);

        $values = array(
            "nomTag" => $nom,

        );
        // On donne les valeurs et on exécute la requête
        $pdoStatement->execute($values);

        $tableauEcole = [];
        foreach ($pdoStatement as $ecoleFormatTableau) {
            $tableauEcole[] = self::construireEcoleDepuisTableauSQL($ecoleFormatTableau);

        }

        // On récupère les résultats comme précédemment
        // Note: fetch() renvoie false si pas d'utilisateur correspondant




        return $tableauEcole;
    }



}