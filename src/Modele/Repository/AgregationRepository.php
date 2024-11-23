<?php

namespace App\GenerateurAvis\Modele\Repository;

use App\GenerateurAvis\Modele\DataObject\AbstractDataObject;
use App\GenerateurAvis\Modele\DataObject\Matiere;
use App\GenerateurAvis\Modele\Repository\ConnexionBaseDeDonnees;
use App\GenerateurAvis\Modele\DataObject\Agregation;
use App\GenerateurAvis\Modele\Repository\AbstractRepository;

class AgregationRepository extends AbstractRepository
{
    private static string $tableAgregation = "agregations";

    public function getNomTable(): string
    {
        return self::$tableAgregation;
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
        $sql = "INSERT INTO " . $this->getNomTable() . " (nom_agregation, parcours, login) 
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
        $sql = "SELECT * FROM " . $this->getNomTable() . " WHERE id = :id_agregation";
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

    public function getAgregationDetailsByLogin(string $login): array
    {
        $sql = "SELECT * FROM agregations WHERE login = :login";
        $stmt = ConnexionBaseDeDonnees::getPdo()->prepare($sql);
        $stmt->execute(['login' => $login]);

        return $stmt->fetchAll(ConnexionBaseDeDonnees::getPdo()::FETCH_ASSOC);
    }

    public static function calculateAgregationNotes(array $agregations, int $idEtudiant): array
    {
        $result = []; // Mảng kết quả

        foreach ($agregations as $agregation) {
            $totalNotes = 0;
            $totalCoefficients = 0;

            // Lấy danh sách môn học liên kết với agregation
            $matieres = (new AgregationRepository)->getMatieresForAgregation((int)$agregation['id']);

            foreach ($matieres as $matiere) {
                // Lấy điểm cho môn học
                $note = self::getNoteForMatiere($matiere['id_ressource'], $idEtudiant);

                // Lấy hệ số môn học
                $matiereCoefficient = (float)$matiere['coefficient'];

                // Tính toán tổng điểm và hệ số
                $totalNotes += $note * $matiereCoefficient;
                $totalCoefficients += $matiereCoefficient;
            }

            // Tính điểm trung bình cuối cùng
            $noteFinale = $totalCoefficients > 0 ? $totalNotes / $totalCoefficients : 0;

            // Chỉ lưu ID và điểm trung bình cuối cùng vào mảng kết quả
            $result[] = [
                'id' => $agregation['id'],
                'note_finale' => $noteFinale,
            ];
        }

        return $result;
    }



    public function getMatieresForAgregation(int $idAgregation): array
    {
        $sql = "SELECT am.id_ressource, m.nom AS matiere, am.coefficient
            FROM agregation_matiere am
            JOIN ressources m ON am.id_ressource = m.id_ressource
            WHERE am.id_agregation = :id_agregation";
        $sql = "SELECT m.nom AS matiere, am.coefficient
                FROM " . (new AgregationMatiereRepository())->getNomTable() . " am
                JOIN " . (new RessourceRepository())->getNomTable() . " m ON am.id_ressource = m.id_ressource
                WHERE am.id_agregation = :id_agregation";
        $stmt = ConnexionBaseDeDonnees::getPdo()->prepare($sql);
        $stmt->execute(['id_agregation' => $idAgregation]);

        return $stmt->fetchAll(ConnexionBaseDeDonnees::getPdo()::FETCH_ASSOC);
    }

    public static function getNoteForMatiere(string $idRessource, int $idEtudiant): float
    {
        $sql = "SELECT note FROM Note WHERE id_ressource = :id_ressource AND idEtudiant = :idEtudiant";
        $stmt = ConnexionBaseDeDonnees::getPdo()->prepare($sql);
        $stmt->execute(['id_ressource' => $idRessource, 'idEtudiant' => $idEtudiant]);

        $note = $stmt->fetchColumn();
        return $note !== false ? (float)$note : 0.0;
    }



}
