<?php

namespace App\GenerateurAvis\Modele\Repository;

use App\GenerateurAvis\Modele\DataObject\AbstractDataObject;
use App\GenerateurAvis\Modele\DataObject\Administrateur;
use App\GenerateurAvis\Modele\DataObject\AvisGenere;
use Exception;
use PDO;
use PDOException;
use Shuchkin\SimpleXLSX;

class AvisGenereRepository extends AbstractRepository
{
    private static string $tableAvisGeneres = "AvisGeneres";

    public function getNomTable(): string
    {
        return self::$tableAvisGeneres;
    }

    protected function getNomClePrimaire(): string
    {
        return "code_nip";
    }

    protected function construireDepuisTableauSQL(array $objetFormatTableau): AbstractDataObject
    {
        return new AvisGenere($objetFormatTableau["code_nip"], $objetFormatTableau["avisGenereIngenieur"], $objetFormatTableau["avisGenereManagement"]);
    }

    protected function getNomsColonnes(): array
    {
        return array("code_nip", "avisGenereIngenieur", "avisGenereManagement");
    }

    protected function formatTableauSQL(AbstractDataObject $objet): array
    {
        return [
            'code_nip' => $objet->getCodeNip(),
            'avisGenereIngenieur' => $objet->getAvisGenereIngenieur(),
            'avisGenereManagement' => $objet->getAvisGenereManagement()
        ];
    }

    public static function creerAvisGenereEtudiant(string $code_nip, string $avisIngenieur, string $avisManagement) : bool {
        try {
            $pdo = ConnexionBaseDeDonnees::getPdo();

            $sql = "INSERT INTO " . self::$tableAvisGeneres . " (code_nip, avisGenereIngenieur, avisGenereManagement) VALUES (:code_nipTag, :avisIngenieurTag, :avisManagementTag)";
            $stmt = $pdo->prepare($sql);

            return $stmt->execute(["code_nipTag" => $code_nip, "avisIngenieurTag" => $avisIngenieur, "avisManagementTag" => $avisManagement]);
        } catch (PDOException $e) {
            if ($e->getCode() == 45000) {
            }
        }
        return false;
    }

    public static function getAvisGenereEtudiant(string $code_nip) {
        $sql = "SELECT * FROM " . self::$tableAvisGeneres .
            " WHERE code_nip = :code_nipTag";

        // Préparer la requête
        $pdoStatement = ConnexionBaseDeDonnees::getPdo()->prepare($sql);

        // Ajouter les jokers à la valeur de recherche
        $values = [
            "code_nipTag" => $code_nip
        ];

        // Exécuter la requête
        $pdoStatement->execute($values);

        return $pdoStatement->fetch();
    }
}