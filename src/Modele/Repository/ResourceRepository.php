<?php

namespace App\GenerateurAvis\Modele\Repository;

use App\GenerateurAvis\Modele\DataObject\AbstractDataObject;
use App\GenerateurAvis\Modele\DataObject\Resource;

class ResourceRepository extends AbstractRepository
{
    protected function getNomTable(): string
    {
        return 'resources';
    }

    protected function getNomClePrimaire(): string
    {
        return 'id_resource';
    }

    protected function construireDepuisTableauSQL(array $row): Resource
    {
        return new Resource(
            $row['id_resource'],
            $row['nom']
        );
    }

    public function recuperer(): array
    {
        $sql = "SELECT * FROM resources";
        $pdoStatement = ConnexionBaseDeDonnees::getPdo()->query($sql);

        $resource = [];
        foreach ($pdoStatement as $row) {
            $resource[] = $this->construireDepuisTableauSQL($row);
        }
        return $resource;
    }

    protected function getNomsColonnes(): array
    {
        return ['id_resource', 'nom'];
    }

    protected function formatTableauSQL(AbstractDataObject $resource): array
    {
        return array([
            'id_resource' => $resource->getId_resource(),
            'nom' => $resource->getNom(),
        ]);

    }
}
