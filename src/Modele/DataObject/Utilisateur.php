<?php

namespace App\GenerateurAvis\Modele\DataObject;
class Utilisateur extends AbstractDataObject
{

    private string $login;
    private string $type;
    private string $password_hash;

    public function __construct(string $login, string $type, string $passwordHash)
    {
        $this->login = substr($login, 0, 64);
        $this->type = $type;
        $this->password_hash = $passwordHash;
    }

    public function getLogin(): string
    {
        return $this->login;
    }
    public function setLogin(string $login): void
    {
        $this->login = substr($login, 0, 64);
    }

    public function getType(): string
    {
        return $this->type;
    }

    public function setType(string $type): void
    {
        $this->type = $type;
    }

    public function getPasswordHash(): string
    {
        return $this->password_hash;
    }



    // Pour pouvoir convertir un objet en chaîne de caractères
    /*public function __toString() : string
    {
        return "<p>Utilisateur $this->prenom $this->nom de login $this->login.</p>";
    }*/
}