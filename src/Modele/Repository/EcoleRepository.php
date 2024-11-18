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

    public static function rechercherEcoleParLogin(string $recherche): array
    {

        $sql = "SELECT * FROM " . self::$tableEcole .
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
        $pdoStatement->execute($values);

        $tableauEcole = [];
        foreach ($pdoStatement as $ecoleFormatTableau) {
            $tableauEcole[] = (new EcoleRepository)->construireDepuisTableauSQL($ecoleFormatTableau);
        }
        return $tableauEcole;

    }

    public function recuperer(): array
    {
        $objets = [];
        $pdoStatement = ConnexionBaseDeDonnees::getPdo()->query("SELECT * FROM " . $this->getNomTable() . ' ORDER BY  valide,nom ');


        foreach ($pdoStatement as $objetFormatTableau) {
            $objet = $this->construireDepuisTableauSQL($objetFormatTableau);
            $objets[] = $objet;
        }
        return $objets;

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
            $ecoleFormatTableau['ville'],
            $ecoleFormatTableau['valide'],

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
        $pdoStatement = ConnexionBaseDeDonnees::getPdo()->prepare($sql);

        $values = array(
            "nomTag" => $nom,

        );
        $pdoStatement->execute($values);

        $tableauEcole = [];
        foreach ($pdoStatement as $ecoleFormatTableau) {
            $tableauEcole[] = (new EcoleRepository)->construireDepuisTableauSQL($ecoleFormatTableau);

        }

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
        return ["login", "nom", "adresse", "ville", "futursEtudiants", "valide"];
    }

    protected function formatTableauSQL(AbstractDataObject $ecole): array
    {
        if ($ecole->isEstValide()) {
            $valide = "1";
        } else {
            $valide = "0";
        }
        if (!empty($ecole->getFutursEtudiants())) {
            $futursEtudiantsEncode = json_encode($ecole->getFutursEtudiants());
        } else {
            $futursEtudiantsEncode = NULL;
        }
        return array(
            "loginTag" => $ecole->getLogin(),
            "nomTag" => $ecole->getNom(),
            "adresseTag" => $ecole->getAdresse(),
            "villeTag" => $ecole->getVille(),
            "futursEtudiantsTag" => $futursEtudiantsEncode,
            "valideTag" => $valide
        );
    }

    public static function valider(Ecole $ecole): bool
    {
        $sql = "UPDATE " . self::$tableEcole . " 
        SET valide = 1
        WHERE login = :loginTag;";

        $pdoStatement = ConnexionBaseDeDonnees::getPdo()->prepare($sql);

        $values = [
            "loginTag" => $ecole->getLogin(),
        ];

        return $pdoStatement->execute($values);
    }

    public static function rechercherEcole(string $recherche): array
    {

        $sql = "SELECT * FROM " . self::$tableEcole .
            " WHERE nom LIKE :rechercheTag1 
            OR nom LIKE :rechercheTag2 
            OR nom LIKE :rechercheTag3 
            OR nom = :rechercheTag4";

        // Préparer la requête
        $pdoStatement = ConnexionBaseDeDonnees::getPdo()->prepare($sql);

        // Ajouter les jokers à la valeur de recherche
        $values = [
            "rechercheTag1" => '%' . $recherche,
            "rechercheTag2" => '%' . $recherche . '%',
            "rechercheTag3" => $recherche . '%',
            "rechercheTag4" => $recherche
        ];
        $pdoStatement->execute($values);

        $tableauEcole = [];
        foreach ($pdoStatement as $ecoleFormatTableau) {
            $tableauEcole[] = (new EcoleRepository())->construireDepuisTableauSQL($ecoleFormatTableau);
        }
        return $tableauEcole;
    }

    public static function getFutursEtudiantsListe(string $loginEcole): array
    {
        $ecoleRepository = new EcoleRepository();
        $etudiantRepository = new EtudiantRepository();

        $ecole = $ecoleRepository->recupererParClePrimaire($loginEcole);

        $futursEtudiants = [];
        foreach ($ecole->getFutursEtudiants() as $code) {
            $etudiant = $etudiantRepository->recupererEtudiantParCodeUnique($code);
            if ($etudiant) {
                $nomPrenom = $etudiantRepository->getNomPrenomParIdEtudiant($etudiant->getIdEtudiant());
                $futursEtudiants[] = [
                    'codeUnique' => $code,
                    'nom' => $nomPrenom['Nom'],
                    'prenom' => $nomPrenom['Prenom'],
                ];
            }
        }
        return $futursEtudiants;
    }
}