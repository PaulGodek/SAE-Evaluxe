<?php

namespace App\GenerateurAvis\Modele\Repository;

use App\GenerateurAvis\Lib\MotDePasse;
use App\GenerateurAvis\Modele\DataObject\AbstractDataObject;
use App\GenerateurAvis\Modele\DataObject\Utilisateur;
use PDOException;

class UtilisateurRepository extends AbstractRepository
{
    private static string $tableUtilisateur = "UtilisateurImportation";

    public static function recupererUtilisateurOrdonneParLogin(): array
    {
        $pdoStatement = ConnexionBaseDeDonnees::getPdo()->query("SELECT * FROM " . self::$tableUtilisateur . " ORDER BY login");

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

    public function getNomTable(): string
    {
        return self::$tableUtilisateur;
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

    public static function rechercherUtilisateurParLogin(string $recherche): array
    {
        $sql = "SELECT * FROM " . self::$tableUtilisateur .
            " WHERE login LIKE :rechercheTag1 
            OR login LIKE :rechercheTag2 
            OR login LIKE :rechercheTag3 
            OR login = :rechercheTag4
            oreder by type";

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
        $tableauUtilisateur = [];
        foreach ($pdoStatement as $utilisateurFormatTableau) {
            $tableauUtilisateur[] = (new UtilisateurRepository())->construireDepuisTableauSQL($utilisateurFormatTableau);
        }
        return $tableauUtilisateur;

    }

    public function existeUtilisateurParLogin(string $login): bool
    {
        $sql = "SELECT 1 FROM " . $this->getNomTable() . " WHERE login = :loginTag LIMIT 1";
        $pdoStatement = ConnexionBaseDeDonnees::getPdo()->prepare($sql);
        $pdoStatement->execute(['loginTag' => $login]);

        return (bool)$pdoStatement->fetch();
    }


    public  static function creerUtilisateur( string $nom,string $prenom){
        try{
        $sql='INSERT  INTO UtilisateurImportation (login,type, password_hash) VALUES (:loginTag, :typeTag, :password_hashTag)';
        $pdoStatement = ConnexionBaseDeDonnees::getPdo()->prepare($sql);

        $login =  mb_strtolower($nom.=substr($prenom, 0, 1), "UTF-8");

        $values=[
            "loginTag"=>$login,
            "typeTag"=>"etudiant",
            "password_hashTag"=>MotDePasse::hacher("123")
        ];

        $pdoStatement->execute($values);
        } catch (PDOException $e) {
            if ($e->getCode() == '45000') {

            }
        }


    }
}


