<?php

namespace App\Covoiturage\Modele\Repository;

use App\Covoiturage\Modele\DataObject\Utilisateur;
use PDO;
use PDOException;

class UtilisateurRepository
{
    public static function recupererUtilisateurs() : array
    {
        $pdoStatement = ConnexionBaseDeDonnees::getPdo()->query("SELECT * FROM utilisateur");

        $tableauUtilisateurs = [];
        foreach ($pdoStatement as $utilisateurFormatTableau) {
            $tableauUtilisateurs[] = self::construireDepuisTableauSQL($utilisateurFormatTableau);
        }
        return $tableauUtilisateurs;
    }

    public static function recupererUtilisateurParLogin(string $login) : ?Utilisateur {
        $sql = "SELECT * from utilisateur WHERE login = :loginTag";
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
        $sql = 'INSERT INTO utilisateur (login, nom, prenom) VALUES (:loginTag, :nomTag, :prenomTag);';
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

    /**
     * @return Trajet[]
     */
    public static function recupererTrajetsCommePassager(Utilisateur $user) : array {
        $sql = 'SELECT p.trajetId
                FROM passager p
                WHERE p.passagerLogin = :loginTag;';

        $pdoStatement = ConnexionBaseDeDonnees::getPdo() -> prepare($sql);

        $values = array(
            'loginTag' => $user->getLogin()
        );

        $pdoStatement -> execute($values);

        $listeTrajet = $pdoStatement -> fetchAll(PDO::FETCH_ASSOC);

        $Trajets = array();
        foreach($listeTrajet as $trajetFormatted) {
            $Trajets[] = Trajet::recupererTrajetParId($trajetFormatted['trajetId']);
        }

        return $Trajets;
    }

    public static function supprimerParLogin(string $login) : bool {
        $sql = "DELETE FROM utilisateur WHERE login = :loginTag;";
        $pdoStatement = ConnexionBaseDeDonnees::getPdo()->prepare($sql);

        $values = array(
            "loginTag" => $login
        );

        return $pdoStatement->execute($values);
    }

    public static function mettreAJour(Utilisateur $utilisateur) : void {
        $sql = "UPDATE utilisateur SET nom = :nomTag, prenom = :prenomTag WHERE login = :loginTag;";
        $pdoStatement = ConnexionBaseDeDonnees::getPdo()-> prepare($sql);

        $values = array(
            "loginTag" => $utilisateur->getLogin(),
            "nomTag" => $utilisateur->getNom(),
            "prenomTag" => $utilisateur->getPrenom()
        );

        $pdoStatement->execute($values);
    }
}