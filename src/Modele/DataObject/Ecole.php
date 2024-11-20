<?php

namespace App\GenerateurAvis\Modele\DataObject;

use App\GenerateurAvis\Modele\Repository\EcoleRepository;

class Ecole extends AbstractDataObject
{

    private Utilisateur $ecole;
    private string $nom;
    private string $adresse;
    private string $ville;
    private array $futursEtudiants;

    private bool $estValide;

    public function __construct(Utilisateur $ecole, string $nom, string $adresse, string $ville, string $estValide)
    {
        $this->ecole = $ecole;
        $this->nom = $nom;
        $this->adresse = $adresse;
        $this->ville = $ville;
        $this->futursEtudiants = [];
        $this->estValide = $estValide;

    }

    public function getNom(): string
    {
        return $this->nom;
    }

    public function setNom(string $nom): void
    {
        $this->nom = $nom;
    }

    public function getEcole(): Utilisateur
    {
        return $this->ecole;
    }

    public function setEcole(Utilisateur $ecole): void
    {
        $this->ecole = $ecole;
    }

    public function getAdresse(): string
    {
        return $this->adresse;
    }

    public function setAdresse(string $adresse): void
    {
        $this->adresse = $adresse;
    }

    public function getVille(): string
    {
        return $this->ville;
    }

    public function setVille(string $ville): void
    {
        $this->ville = $ville;
    }

    public function isEstValide(): bool
    {
        return $this->estValide;
    }

    public function setEstValide(bool $estValide): void
    {
        $this->estValide = $estValide;
    }


    public function addFuturEtudiant(string $code): void
    {
        if (!in_array($code, $this->futursEtudiants)) {
            $this->futursEtudiants[] = $code;
        }
    }

    public function removeFuturEtudiant(string $code): void
    {
        foreach ($this->futursEtudiants as $futursEtudiant) {
            if (strcmp($code, $futursEtudiant) === 0) {
                unset($this->futursEtudiants[$futursEtudiant]);
            }
        }
    }

    public function setFutursEtudiants(): void
    {
        $this->futursEtudiants = [];
    }

    public function getFutursEtudiants(): array
    {
        return $this->futursEtudiants;
    }

    public function saveFutursEtudiants(): bool
    {
        return EcoleRepository::mettreAJourFutursEtudiants($this);
    }
}