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
        return new Professeur($professeurFormatTableau['login'],
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
            "loginTag" => $professeur->getLogin(),
            "nomTag" => $professeur->getNom(),
            "prenomTag" => $professeur->getPrenom()
        );
    }

    public static function rechercherProfesseur(string $recherche): array
    {

        $sql = "SELECT * FROM " . self::$tableProfesseur .
            " WHERE nom LIKE '%" . $recherche . "' OR nom LIKE '%" . $recherche . "%' OR nom LIKE '" . $recherche . "%'
            OR prenom LIKE '%" . $recherche . "' OR prenom LIKE '%" . $recherche . "%' OR prenom LIKE '" . $recherche . "%'
            OR prenom='" . $recherche . "' OR nom='" . $recherche . "'";

        $pdoStatement = ConnexionBaseDeDonnees::getPdo()->query($sql);

        $tableauProfesseurs = [];
        foreach ($pdoStatement as $ProfesseurFormatTableau) {
            $tableauProfesseurs[] = (new ProfesseurRepository)->construireDepuisTableauSQL($ProfesseurFormatTableau);
        }
        return $tableauProfesseurs;

    }

    public static function rechercherProfesseurParLogin(string $recherche): array
    {

        $sql = "SELECT * FROM " . self::$tableProfesseur .
            " WHERE login LIKE '%" . $recherche . "' OR login LIKE '%" . $recherche . "%' OR login LIKE '" . $recherche . "%' OR login='" . $recherche . "'";

        $pdoStatement = ConnexionBaseDeDonnees::getPdo()->query($sql);

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