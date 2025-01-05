<?php

namespace App\GenerateurAvis\Modele\DataObject;

class Professeur extends AbstractDataObject
{
    private Utilisateur $professeur;
    private string $nom;
    private string $prenom;

    public function __construct(Utilisateur $professeur, string $nom, string $prenom)
    {
        $this->professeur = $professeur;
        $this->nom = $nom;
        $this->prenom = $prenom;
    }

    public function getNom(): string
    {
        return $this->nom;
    }

    public function setNom(string $nom): void
    {
        $this->nom = $nom;
    }

    public function getUtilisateur(): Utilisateur
    {
        return $this->professeur;
    }

    public function setUtilisateur(Utilisateur $professeur): void
    {
        $this->professeur = $professeur;
    }

    public function getPrenom(): string
    {
        return $this->prenom;
    }

    public function setPrenom(string $prenom): void
    {
        $this->prenom = $prenom;
    }
}