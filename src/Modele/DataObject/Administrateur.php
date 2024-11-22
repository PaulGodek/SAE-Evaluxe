<?php

namespace App\GenerateurAvis\Modele\DataObject;

class Administrateur extends AbstractDataObject
{
    private Utilisateur $administrateur;
    private string $adresseMail;

    /**
     * @param Utilisateur $administrateur
     * @param string $adresseMail
     */
    public function __construct(Utilisateur $administrateur, string $adresseMail)
    {
        $this->administrateur = $administrateur;
        $this->adresseMail = $adresseMail;
    }

    public function getAdministrateur(): Utilisateur
    {
        return $this->administrateur;
    }

    public function setAdministrateur(Utilisateur $administrateur): void
    {
        $this->administrateur = $administrateur;
    }

    public function getAdresseMail(): string
    {
        return $this->adresseMail;
    }

    public function setAdresseMail(string $adresseMail): void
    {
        $this->adresseMail = $adresseMail;
    }


}