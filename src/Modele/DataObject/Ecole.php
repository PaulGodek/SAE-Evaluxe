<?php

namespace App\GenerateurAvis\Modele\DataObject;
use App\GenerateurAvis\Modele\Repository\EcoleRepository;

class Ecole extends AbstractDataObject
{

    private string $login;
    private string $nom;
    private string $adresse;
    private string $ville;
    private array $futursEtudiants;

    private bool $estValide=false;

    public function __construct(string $login, string $nom, string $adresse, string $ville)
    {
        $this->login = substr($login, 0, 64);
        $this->nom = $nom;
        $this->adresse = $adresse;
        $this->ville = $ville;
        $this->futursEtudiants = [];

    }

    public function getNom(): string
    {
        return $this->nom;
    }

    public function setNom(string $nom): void
    {
        $this->nom = $nom;
    }

    public function getLogin(): string
    {
        return $this->login;
    }

    public function setLogin(string $login): void
    {
        $this->login = substr($login, 0, 64);
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

    public function getFutursEtudiants(): array
    {
        return $this->futursEtudiants;
    }

    public function saveFutursEtudiants(): bool
    {
        return EcoleRepository::mettreAJourFutursEtudiants($this);
    }
}