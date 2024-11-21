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
        // Chuẩn bị câu lệnh SQL để thêm vào cơ sở dữ liệu
        $sql = "INSERT INTO agregations (nom_agregation, parcours, login) 
            VALUES (:nom_agregation, :parcours, :login)";

        // Chuẩn bị statement PDO
        $pdoStatement = ConnexionBaseDeDonnees::getPdo()->prepare($sql);

        // Tạo mảng các giá trị cần liên kết với các tham số trong câu lệnh SQL
        $values = array(
            "nom_agregation" => $agregation->getNom(),
            "parcours" => $agregation->getParcours(),
            "login" => $agregation->getLogin()
        );

        // Thực thi câu lệnh SQL với các tham số
        if ($pdoStatement->execute($values)) {
            // Nếu insert thành công, trả về ID mới được tạo
            return ConnexionBaseDeDonnees::getPdo()->lastInsertId();
        }

        return null; // Nếu không thành công, trả về null
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
