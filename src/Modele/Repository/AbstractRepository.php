<?php

namespace App\GenerateurAvis\Modele\Repository;

use App\GenerateurAvis\Lib\MotDePasse;
use App\GenerateurAvis\Modele\DataObject\AbstractDataObject;
use App\GenerateurAvis\Modele\DataObject\Ecole;
use App\GenerateurAvis\Modele\DataObject\Etudiant;
use App\GenerateurAvis\Modele\DataObject\Professeur;
use App\GenerateurAvis\Modele\DataObject\Utilisateur;

abstract class AbstractRepository
{
    public function mettreAJour(AbstractDataObject $objet): void
    {
        $leSet = [];
        foreach ($this->getNomsColonnes() as $attribut) {
            $leSet[] = $attribut . '= :' . $attribut . 'Tag';
        }

        $sql = 'UPDATE ' . $this->getNomTable() .
            ' SET ' . join(', ', $leSet) .
            ' WHERE ' . $this->getNomClePrimaire() . '= :' . $this->getNomClePrimaire() . 'Tag';

        //var_dump($sql);

        $pdoStatement = ConnexionBaseDeDonnees::getPdo()->prepare($sql);
        $values = $this->formatTableauSQL($objet);
        $pdoStatement->execute($values);
    }


    /*public function ajouter(AbstractDataObject $objet): bool
    {

        // SOLUTION TEMPORAIRE, pour pouvoir ajouter correctement il faut d'abord l'utilisateur, il faudra utiliser des Triggers
        if ($objet instanceof Ecole) {
            $type = "universite";
        } else if ($objet instanceof Professeur) {
            $type = "professeur";
        } else if ($objet instanceof Etudiant) {
            $type = "etudiant";
        } else {
            $type = "administrateur";
        }
        $listeTag = array(
            "login", "type", "password_hash"
        );
        $sql = 'INSERT INTO Utilisateur (login, type, password_hash) VALUES (:' .join("Tag, :",$listeTag).'Tag)';

        $pdoStatement = ConnexionBaseDeDonnees::getPdo()->prepare($sql);

        $values = array(
            "loginTag" => $objet->getUtilisateur()->getLogin(),
            "typeTag" => $type,
            "password_hashTag" => MotDePasse::hacher($_GET["mdp"])
        );

        $pdoStatement->execute($values);
        // FIN SOLUTION TEMPORAIRE

        $sql = 'INSERT INTO  '.$this->getNomTable() .' ('.join(',',$this->getNomsColonnes()).') VALUES (:' .join("Tag, :",$this->getNomsColonnes()).'Tag)';

        $pdoStatement = ConnexionBaseDeDonnees::getPdo()->prepare($sql);

        $values = $this->formatTableauSQL($objet);

        $pdoStatement->execute($values);

        return true;
    }*/
    public function ajouter(AbstractDataObject $objet): bool
    {
        if ($objet instanceof Utilisateur) {

            $sql = 'INSERT INTO Utilisateur (login, type, password_hash) VALUES (:loginTag, :typeTag, :password_hashTag)';
            $pdoStatement = ConnexionBaseDeDonnees::getPdo()->prepare($sql);

            $values = [
                "loginTag" => $objet->getLogin(),
                "typeTag" => $objet->getType(),
                "password_hashTag" => MotDePasse::hacher($_GET["mdp"]),
            ];

            $pdoStatement->execute($values);
        } else {
            $sql = 'INSERT INTO ' . $this->getNomTable() . ' (' . join(',', $this->getNomsColonnes()) . ') VALUES (:' . join("Tag, :", $this->getNomsColonnes()) . 'Tag)';
            $pdoStatement = ConnexionBaseDeDonnees::getPdo()->prepare($sql);

            $values = $this->formatTableauSQL($objet);

            $pdoStatement->execute($values);
        }

        return true;
    }


    public function supprimer(string $clePrimaire): void
    {

        $sql = "DELETE from " . $this->getNomTable() . " WHERE " . $this->getNomClePrimaire() . " = :clePrimaireTag";
        $pdoStatement = ConnexionBaseDeDonnees::getPdo()->prepare($sql);
        $values = array();
        $values["clePrimaireTag"] = $clePrimaire;
        $pdoStatement->execute($values);


    }

    public function recupererParClePrimaire(string $clePrimaire): ?AbstractDataObject
    {
        $sql = "SELECT * from " . $this->getNomTable() . " WHERE " . $this->getNomClePrimaire() . " = :clePrimaireTag";
        // Préparation de la requête
        $pdoStatement = ConnexionBaseDeDonnees::getPdo()->prepare($sql);


        $values = array(
            "clePrimaireTag" => $clePrimaire,

        );
        $pdoStatement->execute($values);

        $objetFormatTableau = $pdoStatement->fetch();

        if (!$objetFormatTableau) {
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
        $pdoStatement = ConnexionBaseDeDonnees::getPdo()->query("SELECT * FROM " . $this->getNomTable());


        foreach ($pdoStatement as $objetFormatTableau) {
            $objet = $this->construireDepuisTableauSQL($objetFormatTableau);
            $objets[] = $objet;
        }
        return $objets;

    }


    public function recupererOrdonneParType(): array

    {
        $objets = [];
        $pdoStatement = ConnexionBaseDeDonnees::getPdo()->query("SELECT * FROM " . $this->getNomTable() . " ORDER BY type,login");


        foreach ($pdoStatement as $objetFormatTableau) {
            $objet = $this->construireDepuisTableauSQL($objetFormatTableau);
            $objets[] = $objet;
        }
        return $objets;

    }

    protected abstract function getNomTable(): string;

    protected abstract function getNomClePrimaire(): string;

    protected abstract function construireDepuisTableauSQL(array $objetFormatTableau): AbstractDataObject;

    /** @return string[] */
    protected abstract function getNomsColonnes(): array;

    protected abstract function formatTableauSQL(AbstractDataObject $objet): array;

}