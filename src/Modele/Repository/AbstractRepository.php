<?php

namespace App\GenerateurAvis\Modele\Repository;

use App\GenerateurAvis\Modele\DataObject\AbstractDataObject;

abstract class AbstractRepository
{
    public function mettreAJour(AbstractDataObject $objet): void    {

        foreach( $this->getNomsColonnes() as $attribut){
            $leSet[$attribut] =$attribut.'= :'.$attribut;

        }
        $sql = 'UPDATE '. $this->getNomTable().' SET '.join('Tag, ',$leSet) .'Tag WHERE '.$this->getNomClePrimaire().'= :'.$this->getNomClePrimaire().'Tag';

        $pdoStatement = ConnexionBaseDeDonnees::getPdo()->prepare($sql);
        $values = $this->formatTableauSQL($objet);
        $pdoStatement->execute($values);


    }

    public function ajouter(AbstractDataObject $objet): bool
    {
        $sql = 'INSERT INTO  '.$this->getNomTable() .' ('.join(',',$this->getNomsColonnes()).') VALUES (:' .join("Tag, :",$this->getNomsColonnes()).'Tag)';

        // Préparation de la requête
        $pdoStatement = ConnexionBaseDeDonnees::getPdo()->prepare($sql);

        $values = $this->formatTableauSQL($objet);

        $pdoStatement->execute($values);

        return true;
    }

    public function supprimer(string $clePrimaire): void
    {

        $sql = "DELETE from ".$this->getNomTable()." WHERE ". $this->getNomClePrimaire()." = :clePrimaireTag";
        $pdoStatement = ConnexionBaseDeDonnees::getPdo()->prepare($sql);
        $values = array();
        $values["clePrimaireTag"] = $clePrimaire;
        $pdoStatement->execute($values);


    }

    public function recupererParClePrimaire(string $clePrimaire): ?AbstractDataObject
    {
        $sql = "SELECT * from ".$this->getNomTable()." WHERE ". $this->getNomClePrimaire()." = :clePrimaireTag";
        // Préparation de la requête
        $pdoStatement = ConnexionBaseDeDonnees::getPdo()->prepare($sql);


        $values = array(
            "clePrimaireTag" => $clePrimaire,

        );
        // On donne les valeurs et on exécute la requête
        $pdoStatement->execute($values);

        // On récupère les résultats comme précédemment
        // Note: fetch() renvoie false si pas d'utilisateur correspondant
        $objetFormatTableau = $pdoStatement->fetch();

        if ($objetFormatTableau == false) {
            return null;
        }
        return ($this->construireDepuisTableauSQL($objetFormatTableau));
    }

    /**
     * @return AbstractDataObject[]
     */
    public function recuperer(): array

    {
        $objets = [];
        $pdoStatement = ConnexionBaseDeDonnees::getPdo()->query("SELECT * FROM ".$this->getNomTable());


        foreach ($pdoStatement as $objetFormatTableau) {
            $objet = $this->construireDepuisTableauSQL($objetFormatTableau);
            $objets[] = $objet;
        }
        return $objets;

    }

    protected abstract function getNomTable(): string;

    protected abstract function getNomClePrimaire(): string;
    protected abstract function construireDepuisTableauSQL(array $objetFormatTableau) : AbstractDataObject;

    /** @return string[] */
    protected abstract function getNomsColonnes(): array;
    protected abstract function formatTableauSQL(AbstractDataObject $objet): array;

}