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

    public function supprimerMatierePourAgregation(int $idAgregation, int $idMatiere): bool {
        $sql = "DELETE FROM agregation_matiere WHERE id_agregation = :idAgregation AND id_matiere = :idMatiere";
        $stmt = ConnexionBaseDeDonnees::getPdo()->prepare($sql);
        $stmt->bindValue(':idAgregation', $idAgregation, ConnexionBaseDeDonnees::getPdo()::PARAM_INT);
        $stmt->bindValue(':idMatiere', $idMatiere, ConnexionBaseDeDonnees::getPdo()::PARAM_INT);
        return $stmt->execute();
    }

    public function mettreAJourCoefficientPourAgregation(int $idAgregation, int $idMatiere, float $coefficient): bool {
        $sql = "UPDATE agregation_matiere SET coefficient = :coefficient WHERE id_agregation = :idAgregation AND id_matiere = :idMatiere";
        $stmt = ConnexionBaseDeDonnees::getPdo()->prepare($sql);
        $stmt->bindValue(':idAgregation', $idAgregation, ConnexionBaseDeDonnees::getPdo()::PARAM_INT);
        $stmt->bindValue(':idMatiere', $idMatiere, ConnexionBaseDeDonnees::getPdo()::PARAM_INT);
        $stmt->bindValue(':coefficient', $coefficient, ConnexionBaseDeDonnees::getPdo()::PARAM_STR);
        return $stmt->execute();
    }

    public function recupererParAgregation(int $idAgregation): array
    {
        $sql = "SELECT am.id_ressource, am.coefficient 
            FROM agregation_matiere am
            WHERE am.id_agregation = :idAgregation";

        $pdoStatement = ConnexionBaseDeDonnees::getPdo()->prepare($sql);
        $pdoStatement->bindValue(':idAgregation', $idAgregation, ConnexionBaseDeDonnees::getPdo()::PARAM_INT);
        $pdoStatement->execute();

        $matiereAgregations = [];
        while ($row = $pdoStatement->fetch()) {
            // Tạo đối tượng Matiere từ kết quả truy vấn
            $matiereAgregation = new Matiere(
                $row['id_ressource'],
                (float)$row['coefficient']
            );
            $matiereAgregations[] = $matiereAgregation;
        }

        return $matiereAgregations;
    }


}
