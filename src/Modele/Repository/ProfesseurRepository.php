<?php

namespace App\GenerateurAvis\Modele\Repository;

use App\GenerateurAvis\Modele\DataObject\AbstractDataObject;
use App\GenerateurAvis\Modele\DataObject\Professeur;

class ProfesseurRepository extends AbstractRepository
{
    private static string $tableProfesseur = "ProfTest";

    public function getNomTable(): string
    {
        return self::$tableProfesseur;
    }

    /**
     * @return array
     */
    public static function triProfesseur(string $ordre): array
    {
        $pdoStatement = ConnexionBaseDeDonnees::getPdo()->query("SELECT * FROM " . self::$tableProfesseur . " ORDER BY " . $ordre);

        $tableauProfesseurs = [];
        foreach ($pdoStatement as $ProfesseurFormatTableau) {
            $tableauProfesseurs[] = (new ProfesseurRepository)->construireDepuisTableauSQL($ProfesseurFormatTableau);
        }
        return $tableauProfesseurs;
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
        return self::triProfesseur("nom");
    }

    public static function recupererProfesseursOrdonneParPrenom(): array
    {
        return self::triProfesseur("prenom");
    }

    public static function ajouterAvis(string $loginEtudiant, string $loginProfesseur, string $avis, string $ecoleIngenieur, string $masterManagement): bool
    {
        $sql = "INSERT INTO Avis VALUES (:loginEtudiantTag, :loginProfesseurTag, :avisTag, :ecoleIngenieurTag, :masterManagementTag)";

        $pdoStatement = ConnexionBaseDeDonnees::getPdo()->prepare($sql);

        $values = array(
            "loginEtudiantTag" => $loginEtudiant,
            "loginProfesseurTag" => $loginProfesseur,
            "avisTag" => $avis,
            "ecoleIngenieurTag" => $ecoleIngenieur,
            "masterManagementTag" => $masterManagement
        );

        return $pdoStatement->execute($values);
    }

    public static function mettreAJourAvis(string $loginEtudiant, string $loginProfesseur, string $avis, string $ecoleIngenieur, string $masterManagement): bool
    {
        $sql = "UPDATE Avis SET avis = :avisTag, ecoleIngenieur = :ecoleIngenieurTag, masterManagement = :masterManagementTag WHERE loginEtudiant = :loginEtudiantTag AND loginProfesseur = :loginProfesseurTag";

        $pdoStatement = ConnexionBaseDeDonnees::getPdo()->prepare($sql);

        $values = array(
            "loginEtudiantTag" => $loginEtudiant,
            "loginProfesseurTag" => $loginProfesseur,
            "avisTag" => $avis,
            "ecoleIngenieurTag" => $ecoleIngenieur,
            "masterManagementTag" => $masterManagement
        );

        return $pdoStatement->execute($values);
    }

    public static function supprimerAvis(string $loginEtudiant, string $loginProfesseur): bool
    {
        $sql = "DELETE FROM Avis WHERE loginEtudiant = :loginEtudiantTag AND loginProfesseur = :loginProfesseurTag";

        $pdoStatement = ConnexionBaseDeDonnees::getPdo()->prepare($sql);

        $values = array(
            "loginEtudiantTag" => $loginEtudiant,
            "loginProfesseurTag" => $loginProfesseur
        );

        return $pdoStatement->execute($values);
    }

    public static function getAvis(string $loginEtudiant, string $loginProfesseur): ?array {
        $sql = "SELECT avis, ecoleIngenieur, masterManagement FROM Avis WHERE loginEtudiant = :loginEtudiantTag AND loginProfesseur = :loginProfesseurTag";

        $pdoStatement = ConnexionBaseDeDonnees::getPdo()->prepare($sql);

        $values = array(
            "loginEtudiantTag" => $loginEtudiant,
            "loginProfesseurTag" => $loginProfesseur
        );

        $pdoStatement->execute($values);
        if ($pdoStatement->rowCount() == 0) {
            return null;
        }
        return $pdoStatement->fetch(\PDO::FETCH_ASSOC);
    }

    public static function getToutAvis(string $loginEtudiant): ?array
    {
        $sql = "SELECT loginProfesseur, avis FROM Avis WHERE loginEtudiant = :loginEtudiantTag";

        $pdoStatement = ConnexionBaseDeDonnees::getPdo()->prepare($sql);

        $values = array(
            "loginEtudiantTag" => $loginEtudiant,
        );

        $pdoStatement->execute($values);
        if ($pdoStatement->rowCount() == 0) {
            return null;
        }
        return $pdoStatement->fetchAll(\PDO::FETCH_ASSOC);
    }

    public static function getNomPrenomParIdProfesseur(string $loginProfesseur): ?array
    {
        $sql = "SELECT nom, prenom FROM " . (new ProfesseurRepository)->getNomTable() . " WHERE login = :loginTag";

        $pdoStatement = ConnexionBaseDeDonnees::getPdo()->prepare($sql);

        $values = array(
            "loginTag" => $loginProfesseur,
        );

        $pdoStatement->execute($values);
        if ($pdoStatement->rowCount() == 0) {
            return null;
        }
        return $pdoStatement->fetch();
    }
}