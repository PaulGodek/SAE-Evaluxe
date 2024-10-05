<?php

namespace App\GenerateurAvis\Modele\Repository;

use App\GenerateurAvis\Modele\DataObject\Etudiant;

class EtudiantRepository
{
    private static string $tableEtudiant= "EtudiantTest";

    public static function recupererEtudiants() : array
    {
        $pdoStatement = ConnexionBaseDeDonnees::getPdo()->query("SELECT * FROM ".self::$tableEtudiant);

        $tableauEtudiant = [];
        foreach ($pdoStatement as $EtudiantFormatTableau) {
            $tableauEtudiant[] = self::construireEtudiantDepuisTableauSQL($EtudiantFormatTableau);
        }
        return $tableauEtudiant;
    }

    public static function recupererEtudiantsOrdonneParNom() : array
    {
        $pdoStatement = ConnexionBaseDeDonnees::getPdo()->query("SELECT * FROM ".self::$tableEtudiant." ORDER BY nom");

        $tableauEtudiant = [];
        foreach ($pdoStatement as $EtudiantFormatTableau) {
            $tableauEtudiant[] = self::construireEtudiantDepuisTableauSQL($EtudiantFormatTableau);
        }
        return $tableauEtudiant;
    }




    public static function recupererEtudiantsOrdonneParMoyenne() : array
    {
        $pdoStatement = ConnexionBaseDeDonnees::getPdo()->query("SELECT * FROM ".self::$tableEtudiant." ORDER BY moyenne");

        $tableauEtudiant = [];
        foreach ($pdoStatement as $EtudiantFormatTableau) {
            $tableauEtudiant[] = self::construireEtudiantDepuisTableauSQL($EtudiantFormatTableau);
        }
        return $tableauEtudiant;
    }




    public static function recupererEtudiantParLogin(string $login) : ?Etudiant {
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
        $EtudiantFormatTableau = $pdoStatement->fetch();
        if (!$EtudiantFormatTableau)
            return null;

        return self::construireEtudiantDepuisTableauSQL($EtudiantFormatTableau);
    }

    public static function ajouter(Etudiant $Etudiant) : bool {
        $sql = "INSERT INTO ".self::$tableEtudiant." (login, nom,prenom, moyenne) VALUES (:loginTag, :nomTag, :prenomTag, :moyenneTag);";
        $pdoStatement = ConnexionBaseDeDonnees::getPdo()-> prepare($sql);

        $values = array(
            "loginTag" => $Etudiant->getLogin(),
            "nomTag" => $Etudiant->getNom(),
            "prenomTag" => $Etudiant->getPrenom(),
            "moyenneTag" => $Etudiant->getMoyenne()
        );

        return $pdoStatement->execute($values);
    }

    public static function construireEtudiantDepuisTableauSQL( array $EtudiantFormatTableau) : Etudiant
    {
        return new Etudiant($EtudiantFormatTableau['login'],
            $EtudiantFormatTableau['nom'],
            $EtudiantFormatTableau['prenom'],
            $EtudiantFormatTableau['moyenne']);
    }

    public static function supprimerEtudiantParLogin(string $login) : bool {
        $sql = "DELETE FROM ".self::$tableEtudiant." WHERE login = :loginTag;";
        $pdoStatement = ConnexionBaseDeDonnees::getPdo()->prepare($sql);

        $values = array(
            "loginTag" => $login
        );

        return $pdoStatement->execute($values);
    }

    public static function mettreAJour(Etudiant $Etudiant) : void {
        $sql = "UPDATE ".self::$tableEtudiant." SET nom = :nomTag, prenom= :prenomTag, moyenne = :moyenneTag WHERE login = :loginTag;";
        $pdoStatement = ConnexionBaseDeDonnees::getPdo()-> prepare($sql);

        $values = array(
            "loginTag" => $Etudiant->getLogin(),
            "nomTag" => $Etudiant->getNom(),
            "prenomTag" => $Etudiant->getPrenom(),
            "moyenneTag" => $Etudiant->getMoyenne()
        );

        $pdoStatement->execute($values);
    }
}