<?php

namespace App\Modele\Repository;

use App\GenerateurAvis\Modele\Repository\ConnexionBaseDeDonnees;
use App\Modele\DataObject\Agregation;
use App\Modele\Repository\AbstractRepository;

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
        return new Agregation(
            $row['nom_agregation'],
            $row['parcours'],
            $row['login'],
            $row['id'] ?? null
        );
    }

    protected function getNomsColonnes(): array
    {
        return ['nom_agregation', 'parcours', 'login'];
    }

    protected function formatTableauSQL(Agregation $agregation): array
    {
        return [
            'nom_agregation' => $agregation->getNom(),
            'parcours' => $agregation->getParcours(),
            'login' => $agregation->getLogin(),
        ];
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


    public function ajouter(Agregation $agregation): bool
    {
        $sql = "CALL insert_agregation(:nomTag, :parcoursTag, :loginTag)";
        $pdoStatement = ConnexionBaseDeDonnees::getPdo()->prepare($sql);
        echo $agregation->getNom() . " " . $agregation->getParcours() . " " . $agregation->getLogin();
        try {
            return $pdoStatement->execute(
                [
                    ":nomTag" => $agregation->getNom(),
                    ":parcoursTag" => $agregation->getParcours(),
                    ":loginTag" => $agregation->getLogin()]);
        } catch (\PDOException $e) {
            return false;
        }
    }

}
