<?php

namespace App\Modele\Repository;

use App\Modele\DataObject\Agregation;
use PDO;

class AgregationRepository extends AbstractRepository
{
    protected function getTableName(): string
    {
        return 'agregations';
    }

    protected function construireDepuisTableau(array $row): Agregation
    {
        return new Agregation(
            $row['id'] ?? null,
            $row['nom'],
            $row['semestre'],
            $row['expression']
        );
    }

    public function enregistrerAgregation(Agregation $agregation): void
    {
        $sql = "INSERT INTO agregations (nom, semestre, expression) VALUES (:nom, :semestre, :expression)";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([
            ':nom' => $agregation->getNom(),
            ':semestre' => $agregation->getSemestre(),
            ':expression' => $agregation->getExpression(),
        ]);
    }

    public function recupererAgregationsParSemestre(string $semestre): array
    {
        $sql = "SELECT * FROM agregations WHERE semestre = :semestre";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([':semestre' => $semestre]);
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

        return array_map([$this, 'construireDepuisTableau'], $rows);
    }
}
