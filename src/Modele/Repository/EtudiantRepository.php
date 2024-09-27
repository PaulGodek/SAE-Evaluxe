<?php

namespace App\GenerateurAvis\Modele\Repository;

use App\GenerateurAvis\Modele\DataObject\Utilisateur;

class EtudiantRepository
{
    private static string $tableEtudiant = "EtudiantTest";

    public static function recupererUtilisateurs() : array
    {
        $pdoStatement = ConnexionBaseDeDonnees::getPdo()->query("SELECT * FROM ".self::$tableEtudiant);

        $tableauUtilisateurs = [];
        foreach ($pdoStatement as $utilisateurFormatTableau) {
            $tableauUtilisateurs[] = self::construireDepuisTableauSQL($utilisateurFormatTableau);
        }
        return $tableauUtilisateurs;
    }

    public static function recupererUtilisateurParLogin(string $login) : ?Utilisateur {
        $sql = "SELECT * from ".self::$tableEtudiant." WHERE login = :loginTag";
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
        $sql = "INSERT INTO ".self::$tableEtudiant." (login, nom, prenom) VALUES (:loginTag, :nomTag, :prenomTag);";
        $pdoStatement = ConnexionBaseDeDonnees::getPdo()-> prepare($sql);

        $values = array(
            "loginTag" => $user->getLogin(),
            "nomTag" => $user->getNom(),
            "prenomTag" => $user->getPrenom()
        );

        return $pdoStatement->execute($values);
    }

    public static function construireDepuisTableauSQL( array $utilisateurFormatTableau) : Utilisateur
    {
        return new Utilisateur($utilisateurFormatTableau['login'],
            $utilisateurFormatTableau['nom'],
            $utilisateurFormatTableau['prenom']);
    }

    public static function supprimerParLogin(string $login) : bool {
        $sql = "DELETE FROM ".self::$tableEtudiant." WHERE login = :loginTag;";
        $pdoStatement = ConnexionBaseDeDonnees::getPdo()->prepare($sql);

        $values = array(
            "loginTag" => $login
        );

        return $pdoStatement->execute($values);
    }

    public static function mettreAJour(Utilisateur $utilisateur) : void {
        $sql = "UPDATE ".self::$tableEtudiant." SET nom = :nomTag, prenom = :prenomTag WHERE login = :loginTag;";
        $pdoStatement = ConnexionBaseDeDonnees::getPdo()-> prepare($sql);

        $values = array(
            "loginTag" => $utilisateur->getLogin(),
            "nomTag" => $utilisateur->getNom(),
            "prenomTag" => $utilisateur->getPrenom()
        );

        $pdoStatement->execute($values);
    }
}