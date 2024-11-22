<?php

namespace App\GenerateurAvis\Modele\Repository;

use App\GenerateurAvis\Modele\DataObject\AbstractDataObject;
use App\GenerateurAvis\Modele\DataObject\Professeur;
class ProfesseurRepository extends AbstractRepository
{
    private static string $tableProfesseur = "ProfTest";

    protected function getNomTable(): string
    {
        return "ProfTest";
    }

    protected function getNomClePrimaire(): string
    {
        return "login";
    }

    protected function construireDepuisTableauSQL(array $professeurFormatTableau): AbstractDataObject
    {
        return new Professeur((new UtilisateurRepository())->recupererParClePrimaire($professeurFormatTableau['login']),
            $professeurFormatTableau['nom'],
            $professeurFormatTableau['prenom']);
    }

    protected function getNomsColonnes(): array
    {
        return ["login", "nom", "prenom"];
    }

    protected function formatTableauSQL(AbstractDataObject $professeur): array
    {
        return array(
            "loginTag" => $professeur->getUtilisateur()->getLogin(),
            "nomTag" => $professeur->getNom(),
            "prenomTag" => $professeur->getPrenom()
        );
    }

    public static function rechercherProfesseur(string $recherche): array
    {

        $sql = "SELECT * FROM " . self::$tableProfesseur .
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

        $tableauProfesseurs = [];
        foreach ($pdoStatement as $ProfesseurFormatTableau) {
            $tableauProfesseurs[] = (new ProfesseurRepository)->construireDepuisTableauSQL($ProfesseurFormatTableau);
        }
        return $tableauProfesseurs;

    }

    public static function rechercherProfesseurParLogin(string $recherche): array
    {

        $sql = "SELECT * FROM " . self::$tableProfesseur .
            " WHERE login LIKE :rechercheTag1 
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

        $tableauProfesseurs = [];
        foreach ($pdoStatement as $ProfesseurFormatTableau) {
            $tableauProfesseurs[] = (new ProfesseurRepository)->construireDepuisTableauSQL($ProfesseurFormatTableau);
        }
        return $tableauProfesseurs;

    }

    public static function recupererProfesseurParNom($nom): array
    {
        $sql = "SELECT * from " . self::$tableProfesseur . "  WHERE nom = :nomTag";
        $pdoStatement = ConnexionBaseDeDonnees::getPdo()->prepare($sql);

        $values = array(
            "nomTag" => $nom,

        );
        $pdoStatement->execute($values);

        $tableauProfesseur = [];
        foreach ($pdoStatement as $professeurFormatTableau) {
            $tableauProfesseur[] = (new ProfesseurRepository)->construireDepuisTableauSQL($professeurFormatTableau);

        }

        return $tableauProfesseur;
    }

    public static function recupererProfesseursOrdonneParNom(): array
    {
        $pdoStatement = ConnexionBaseDeDonnees::getPdo()->query("SELECT * FROM " . self::$tableProfesseur . " ORDER BY nom");

        $tableauProfesseurs = [];
        foreach ($pdoStatement as $ProfesseurFormatTableau) {
            $tableauProfesseurs[] = (new ProfesseurRepository)->construireDepuisTableauSQL($ProfesseurFormatTableau);
        }
        return $tableauProfesseurs;
    }

    public static function recupererProfesseursOrdonneParPrenom(): array
    {
        $pdoStatement = ConnexionBaseDeDonnees::getPdo()->query("SELECT * FROM " . self::$tableProfesseur . " ORDER BY prenom ");

        $tableauProfesseurs = [];
        foreach ($pdoStatement as $ProfesseurFormatTableau) {
            $tableauProfesseurs[] = (new ProfesseurRepository)->construireDepuisTableauSQL($ProfesseurFormatTableau);
        }
        return $tableauProfesseurs;
    }
}