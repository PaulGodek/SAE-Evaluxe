<?php

namespace App\GenerateurAvis\Modele\Repository;

use App\GenerateurAvis\Modele\DataObject\AbstractDataObject;
use App\GenerateurAvis\Modele\DataObject\Ressource;

class RessourceRepository extends AbstractRepository
{
    private static string $tableRessource = "ressources";

    public function getNomTable(): string
    {
        return self::$tableRessource;
    }

    protected function getNomClePrimaire(): string
    {
        return 'id_ressource';
    }

    protected function construireDepuisTableauSQL(array $row): Ressource
    {
        return new Ressource(
            $row['id_ressource'],
            $row['nom']
        );
    }

    public function recuperer(): array
    {
        $sql = "SELECT * FROM " . $this->getNomTable() . ";";
        $pdoStatement = ConnexionBaseDeDonnees::getPdo()->query($sql);

        $ressource = [];
        foreach ($pdoStatement as $row) {
            $ressource[] = $this->construireDepuisTableauSQL($row);
        }
        return $ressource;
    }

    protected function getNomsColonnes(): array
    {
        return ['id_ressource', 'nom'];
    }

    protected function formatTableauSQL(AbstractDataObject $ressource): array
    {
        return array([
            'id_ressource' => $ressource->getId_ressource(),
            'nom' => $ressource->getNom(),
        ]);

    }
}
