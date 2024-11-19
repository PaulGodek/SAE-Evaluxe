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

}
