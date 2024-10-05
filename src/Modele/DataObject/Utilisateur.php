<?php
namespace App\GenerateurAvis\Modele\DataObject;
class Utilisateur
{

    private string $login;
    private string $type;

    public function __construct(string $login, string $type)
    {
        $this->login = substr($login, 0, 64);
        $this->type=$type;
    }

    public function getLogin() : string
    {
        return $this->login;
    }
    public function setLogin(string $login) : void
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



    // Pour pouvoir convertir un objet en chaîne de caractères
    /*public function __toString() : string
    {
        return "<p>Utilisateur $this->prenom $this->nom de login $this->login.</p>";
    }*/
}