<?php

namespace App\GenerateurAvis\Modele\Repository;

use App\GenerateurAvis\Modele\DataObject\AbstractDataObject;
use App\GenerateurAvis\Modele\DataObject\Ecole;

class EcoleRepository extends AbstractRepository
{
    private static string $tableEcole = "EcoleTest";

    public static function recupererEcolesOrdonneParNom(): array
    {
        $pdoStatement = ConnexionBaseDeDonnees::getPdo()->query("SELECT * FROM " . self::$tableEcole . " ORDER BY nom");

        $tableauEcole = [];
        foreach ($pdoStatement as $EcoleFormatTableau) {
            $tableauEcole[] = (new EcoleRepository)->construireDepuisTableauSQL($EcoleFormatTableau);
        }
        return $tableauEcole;
    }


    public static function recupererEcolesOrdonneParVille(): array
    {
        $pdoStatement = ConnexionBaseDeDonnees::getPdo()->query("SELECT * FROM " . self::$tableEcole . " ORDER BY ville");

        $tableauEcole = [];
        foreach ($pdoStatement as $EcoleFormatTableau) {
            $tableauEcole[] = (new EcoleRepository)->construireDepuisTableauSQL($EcoleFormatTableau);
        }
        return $tableauEcole;
    }

    protected function construireDepuisTableauSQL(array $ecoleFormatTableau): Ecole
    {
        $ecole = new Ecole(
            $ecoleFormatTableau['login'],
            $ecoleFormatTableau['nom'],
            $ecoleFormatTableau['adresse'],
            $ecoleFormatTableau['ville']
        );

        if (!empty($ecoleFormatTableau['futursEtudiants'])) {
            $futursEtudiants = json_decode($ecoleFormatTableau['futursEtudiants'], true);

            if (is_array($futursEtudiants)) {
                foreach ($futursEtudiants as $code) {
                    $ecole->addFuturEtudiant($code);
                }
            }
        }

        return $ecole;
    }

    public static function recupererEcoleParNom($nom): array
    {
        $sql = "SELECT * from " . self::$tableEcole . "  WHERE nom = :nomTag";
        // Préparation de la requête
        $pdoStatement = ConnexionBaseDeDonnees::getPdo()->prepare($sql);

        $values = array(
            "nomTag" => $nom,

        );
        // On donne les valeurs et on exécute la requête
        $pdoStatement->execute($values);

        $tableauEcole = [];
        foreach ($pdoStatement as $ecoleFormatTableau) {
            $tableauEcole[] = (new EcoleRepository)->construireDepuisTableauSQL($ecoleFormatTableau);

        }

        // On récupère les résultats comme précédemment
        // Note: fetch() renvoie false si pas d'utilisateur correspondant


        return $tableauEcole;
    }

    public static function mettreAJourFutursEtudiants(Ecole $ecole): bool
    {
        $sql = "UPDATE " . self::$tableEcole . " 
            SET futursEtudiants = :futursEtudiants 
            WHERE login = :loginTag;";

        $pdoStatement = ConnexionBaseDeDonnees::getPdo()->prepare($sql);

        $futursEtudiantsStr = json_encode($ecole->getFutursEtudiants());

        $values = [
            "loginTag" => $ecole->getLogin(),
            "futursEtudiants" => $futursEtudiantsStr
        ];

        return $pdoStatement->execute($values);
    }


    protected function getNomTable(): string
    {
        return "EcoleTest";
    }

    protected function getNomClePrimaire(): string
    {
        return "login";
    }

    protected function getNomsColonnes(): array
    {
        return ["login", "nom", "adresse","cille", "futursEtudiants"];
    }

    protected function formatTableauSQL(AbstractDataObject $ecole): array
    {
        return array(
            "loginTag" => $ecole->getLogin(),
            "nomTag" => $ecole->getNom(),
            "adresseTag" => $ecole->getAdresse(),
            "villeTag"=>$ecole->getVille(),
            "futursEtudiantsTag"=> $ecole->getFutursEtudiants()
        );
    }
}