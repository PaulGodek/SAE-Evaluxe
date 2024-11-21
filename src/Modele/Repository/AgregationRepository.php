<?php

namespace App\GenerateurAvis\Modele\Repository;

use App\GenerateurAvis\Modele\DataObject\AbstractDataObject;
use App\GenerateurAvis\Modele\Repository\ConnexionBaseDeDonnees;
use App\GenerateurAvis\Modele\DataObject\Agregation;
use App\GenerateurAvis\Modele\Repository\AbstractRepository;

class AgregationRepository extends AbstractRepository
{
    protected function getNomTable(): string
    {
        return 'agregations';
    }


    protected function getNomClePrimaire(): string
    {
        return 'id';
    }

    protected function construireDepuisTableauSQL(array $row): Agregation
    {
        $agregation = new Agregation(
            $row['nom_agregation'],
            $row['parcours'] ?? '',
            $row['login'],
            $row['id'] ?? null
        );

        return $agregation;
    }

    protected function getNomsColonnes(): array
    {
        return ['nom_agregation', 'parcours', 'login'];
    }





    public function recuperer(): array
    {
        $objets = [];
        $pdoStatement = ConnexionBaseDeDonnees::getPdo()->query("SELECT * FROM " . $this->getNomTable());


        foreach ($pdoStatement as $objetFormatTableau) {
            $objet = $this->construireDepuisTableauSQL($objetFormatTableau);
            $objets[] = $objet;
        }
        return $objets;
    }


    public function ajouterAgregation(Agregation $agregation): bool
    {
        $sql = "CALL insert_agregations(:nomTag, :parcoursTag, :loginTag)";
        $pdoStatement = ConnexionBaseDeDonnees::getPdo()->prepare($sql);
        try {
            $success = $pdoStatement->execute([
                ":nomTag" => $agregation->getNom(),
                ":parcoursTag" => $agregation->getParcours(),
                ":loginTag" => $agregation->getLogin()
            ]);
            if (!$success) {
                error_log("Failed to execute stored procedure: " . implode(", ", $pdoStatement->errorInfo()));
            }
            return $success;
        } catch (\PDOException $e) {
            error_log("PDOException: " . $e->getMessage());
            return false;
        }
    }

    protected function formatTableauSQL(AbstractDataObject $agregation): array
    {
        return [
            'nom_agregation' => $agregation->getNom(),
            'parcours' => $agregation->getParcours(),
            'login' => $agregation->getLogin(),
        ];

    }

}
