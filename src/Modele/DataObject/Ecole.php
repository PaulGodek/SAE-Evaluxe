<?php
namespace App\GenerateurAvis\Modele\DataObject;
class Ecole
{

    private string $login;
    private string $nom;
    private string $adresse;
    private string $passwordHash;

    public function __construct(string $login, string $nom, string $adresse)
    {
        $this->login = substr($login, 0, 64);
        $this->nom = $nom;
        $this->adresse = $adresse;
    }

    public function getNom() : string
    {
        return $this->nom;
    }

    public function setNom(string $nom) : void
    {
        $this->nom = $nom;
    }

    public function getLogin() : string
    {
        return $this->login;
    }
    public function setLogin(string $login) : void
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

    public function getPasswordHash(): string
    {
        return $this->passwordHash;
    }


}