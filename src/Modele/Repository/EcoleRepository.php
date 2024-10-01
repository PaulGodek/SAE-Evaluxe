<?php

namespace App\GenerateurAvis\Modele\Repository;

use App\GenerateurAvis\Modele\DataObject\Ecole;

class EcoleRepository
{
    private static string $tableEcole= "EcoleTest";

    public static function recupererEcole() : array
    {
        $pdoStatement = ConnexionBaseDeDonnees::getPdo()->query("SELECT * FROM ".self::$tableEcole);

        $tableauEcole = [];
        foreach ($pdoStatement as $EcoleFormatTableau) {
            $tableauEcole[] = self::construireDepuisTableauSQL($EcoleFormatTableau);
        }
        return $tableauEcole;
    }

    public static function recupererEcoleParLogin(string $login) : ?Utilisateur {
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

        return self::construireDepuisTableauSQL($ecoleFormatTableau);
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

    public static function construireDepuisTableauSQL( array $ecoleFormatTableau) : Ecole
    {
        return new Ecole($ecoleFormatTableau['login'],
            $ecoleFormatTableau['nom'],
            $ecoleFormatTableau['prenom']);
    }

    public static function supprimerParLogin(string $login) : bool {
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
            "adresseTag" => $ecole->getAddresse()
        );

        $pdoStatement->execute($values);
    }
}