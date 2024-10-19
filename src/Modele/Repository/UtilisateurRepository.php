<?php

namespace App\GenerateurAvis\Modele\Repository;

use App\GenerateurAvis\Modele\DataObject\AbstractDataObject;
use App\GenerateurAvis\Modele\DataObject\Utilisateur;

class UtilisateurRepository extends AbstractRepository
{
    private static string $tableUtilisateur = "Utilisateur";

    public static function recupererUtilisateurOrdonneParLogin(): array
    {
        $pdoStatement = ConnexionBaseDeDonnees::getPdo()->query("SELECT * FROM " . self::$tableUtilisateur . " ORDER BY login");

        $tableauUtilisateur = [];
        foreach ($pdoStatement as $UtilisateurFormatTableau) {
            $tableauUtilisateur[] = (new UtilisateurRepository)->construireDepuisTableauSQL($UtilisateurFormatTableau);
        }
        return $tableauUtilisateur;
    }

    public static function recupererUtilisateurOrdonneParType(): array
    {
        $pdoStatement = ConnexionBaseDeDonnees::getPdo()->query("SELECT * FROM " . self::$tableUtilisateur . " ORDER BY type");

        $tableauUtilisateur = [];
        foreach ($pdoStatement as $UtilisateurFormatTableau) {
            $tableauUtilisateur[] = (new UtilisateurRepository)->construireDepuisTableauSQL($UtilisateurFormatTableau);
        }
        return $tableauUtilisateur;
    }

    protected function construireDepuisTableauSQL(array $utilisateurFormatTableau): Utilisateur
    {
        return new Utilisateur($utilisateurFormatTableau['login'],
            $utilisateurFormatTableau['type'], $utilisateurFormatTableau['password_hash']);
    }

    protected function getNomTable(): string
    {
        return "Utilisateur";
    }

    protected function getNomClePrimaire(): string
    {
        return "login";
    }

    protected function getNomsColonnes(): array
    {
        return ["login", "type", "password_hash"];
    }

    protected function formatTableauSQL(AbstractDataObject $utilisateur): array
    {
        return array(
            "loginTag" => $utilisateur->getLogin(),
            "typeTag" => $utilisateur->getType(),
            "password_hashTag" => $utilisateur->getPasswordHash()
        );
    }
}