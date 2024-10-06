<?php

namespace App\GenerateurAvis\Modele\Repository;

use App\GenerateurAvis\Modele\DataObject\Utilisateur;

class UtilisateurRepository
{
    private static string $tableUtilisateur = "Utilisateur";

    public static function recupererUtilisateurs() : array
    {
        $pdoStatement = ConnexionBaseDeDonnees::getPdo()->query("SELECT * FROM ".self::$tableUtilisateur);

        $tableauUtilisateurs = [];
        foreach ($pdoStatement as $utilisateurFormatTableau) {
            $tableauUtilisateurs[] = self::construireDepuisTableauSQL($utilisateurFormatTableau);
        }
        return $tableauUtilisateurs;
    }


    public static function recupererUtilisateurOrdonneParLogin() : array
    {
        $pdoStatement = ConnexionBaseDeDonnees::getPdo()->query("SELECT * FROM ".self::$tableUtilisateur." ORDER BY login");

        $tableauUtilisateur = [];
        foreach ($pdoStatement as $UtilisateurFormatTableau) {
            $tableauUtilisateur[] = self::construireDepuisTableauSQL($UtilisateurFormatTableau);
        }
        return $tableauUtilisateur;
    }

    public static function recupererUtilisateurOrdonneParType() : array
    {
        $pdoStatement = ConnexionBaseDeDonnees::getPdo()->query("SELECT * FROM ".self::$tableUtilisateur." ORDER BY type");

        $tableauUtilisateur = [];
        foreach ($pdoStatement as $UtilisateurFormatTableau) {
            $tableauUtilisateur[] = self::construireDepuisTableauSQL($UtilisateurFormatTableau);
        }
        return $tableauUtilisateur;
    }





    public static function recupererUtilisateurParLogin(string $login) : ?Utilisateur {
        $sql = "SELECT * from ".self::$tableUtilisateur." WHERE login = :loginTag";
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
        $utilisateurFormatTableau = $pdoStatement->fetch();
        if (!$utilisateurFormatTableau)
            return null;

        return self::construireDepuisTableauSQL($utilisateurFormatTableau);
    }

    public static function ajouter(Utilisateur $user) : bool {
        $sql = "INSERT INTO ".self::$tableUtilisateur." (login, type) VALUES (:loginTag, :typeTag);";
        $pdoStatement = ConnexionBaseDeDonnees::getPdo()-> prepare($sql);

        $values = array(
            "loginTag" => $user->getLogin(),
            "typeTag" => $user->getType()
        );

        return $pdoStatement->execute($values);
    }

    public static function construireDepuisTableauSQL( array $utilisateurFormatTableau) : Utilisateur
    {
        return new Utilisateur($utilisateurFormatTableau['login'],
            $utilisateurFormatTableau['type']);
    }

    public static function supprimerParLogin(string $login) : bool {
        $sql = "DELETE FROM ".self::$tableUtilisateur." WHERE login = :loginTag;";
        $pdoStatement = ConnexionBaseDeDonnees::getPdo()->prepare($sql);

        $values = array(
            "loginTag" => $login
        );

        return $pdoStatement->execute($values);
    }

    public static function mettreAJour(Utilisateur $utilisateur) : void {
        $sql = "UPDATE ".self::$tableUtilisateur." SET type = :typeTag WHERE login = :loginTag;";
        $pdoStatement = ConnexionBaseDeDonnees::getPdo()-> prepare($sql);

        $values = array(
            "loginTag" => $utilisateur->getLogin(),
            "typeTage" => $utilisateur->getType()
        );

        $pdoStatement->execute($values);
    }


}