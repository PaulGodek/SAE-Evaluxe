<?php

namespace App\GenerateurAvis\Modele\Repository;

use App\GenerateurAvis\Modele\DataObject\AbstractDataObject;
use App\GenerateurAvis\Modele\DataObject\AgregationMatiere;
use App\GenerateurAvis\Modele\DataObject\Matiere;

class AgregationMatiereRepository extends AbstractRepository
{
    public function ajouterMatierePourAgregation(int $id_agregation, Matiere $matiere): bool
    {

        $sql = "INSERT INTO agregation_matiere (id_agregation, id_ressource, coefficient) 
                VALUES (:id_agregation, :id_ressource, :coefficient)";

        $stmt = ConnexionBaseDeDonnees::getPdo()->prepare($sql);
        $stmt->bindValue(":id_agregation", $id_agregation, \PDO::PARAM_INT);
        $stmt->bindValue(":id_ressource", $matiere->getId_ressource(), \PDO::PARAM_STR);
        $stmt->bindValue(":coefficient", $matiere->getCoefficient(), \PDO::PARAM_STR);

        return $stmt->execute();
    }

    protected function getNomTable(): string
    {
        return "agregation_matiere";
    }

    protected function getNomClePrimaire(): string
    {
        return 'id_agregation, id_ressource';
    }

    protected function construireDepuisTableauSQL(array $row): AgregationMatiere
    {
        return new AgregationMatiere(
            $row["id_agregation"],
            $row["id_ressource"],
            $row["coefficient"]
        );
    }

    protected function getNomsColonnes(): array
    {
        return ['id_agregation', 'id_ressource', 'coefficient'];
    }

    protected function formatTableauSQL(AbstractDataObject $objet): array
    {
        return [
            'id_agregation' => $objet->getIdAgregation(),
            'id_ressource' => $objet->getIdRessource(),
            'coefficient' => $objet->getCoefficient()
        ];
    }

}
