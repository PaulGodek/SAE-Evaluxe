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

    public function ajouterAgregation(Agregation $agregation): ?int
    {
        $sql = "INSERT INTO agregations (nom_agregation, parcours, login) 
            VALUES (:nom_agregation, :parcours, :login)";

        $pdoStatement = ConnexionBaseDeDonnees::getPdo()->prepare($sql);

        $values = array(
            "nom_agregation" => $agregation->getNom(),
            "parcours" => $agregation->getParcours(),
            "login" => $agregation->getLogin()
        );

        if ($pdoStatement->execute($values)) {
            return ConnexionBaseDeDonnees::getPdo()->lastInsertId();
        }
        return null;
    }

    protected function formatTableauSQL(AbstractDataObject $agregation): array
    {
        return [
            'nom_agregation' => $agregation->getNom(),
            'parcours' => $agregation->getParcours(),
            'login' => $agregation->getLogin(),
        ];
    }
    public function getAgregationDetailsById(int $idAgregation): array
    {
        $sql = "SELECT * FROM agregations WHERE id = :id_agregation";
        $stmt = ConnexionBaseDeDonnees::getPdo()->prepare($sql);

        $values = ['id_agregation' => $idAgregation];

        $stmt->execute($values);

        $agregation = $stmt->fetch(ConnexionBaseDeDonnees::getPdo()::FETCH_ASSOC);

        if (!$agregation) {
            return [];
        }

        $matieres = $this->getMatieresForAgregation($idAgregation);

        return [
            'nom_agregation' => $agregation['nom_agregation'],
            'parcours' => $agregation['parcours'],
            'matieres' => $matieres
        ];
    }

    private function getMatieresForAgregation(int $idAgregation): array
    {
        $sql = "SELECT m.nom AS matiere, am.coefficient
                FROM agregation_matiere am
                JOIN ressources m ON am.id_ressource = m.id_ressource
                WHERE am.id_agregation = :id_agregation";
        $stmt = ConnexionBaseDeDonnees::getPdo()->prepare($sql);
        $values = ['id_agregation' => $idAgregation];
        $stmt->execute($values);

        $matieres = $stmt->fetchAll(ConnexionBaseDeDonnees::getPdo()::FETCH_ASSOC);

        return $matieres;
    }



}
