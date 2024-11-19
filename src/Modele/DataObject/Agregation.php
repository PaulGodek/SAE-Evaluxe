<?php

namespace App\GenerateurAvis\Modele\DataObject;

use App\GenerateurAvis\Modele\Repository\AbstractRepository;

class Agregation extends AbstractDataObject
{
    private ?string $id;
    private string $nom;
    private string $parcours;
    private string $login;

    /**
     * @param string $nom
     * @param string $parcours
     * @param string $login
     * @param ?string $id
     */
    public function __construct(string $nom, string $parcours, string $login, string $id = null)
    {
        $this->nom = $nom;
        $this->id = $id;
        $this->parcours = $parcours;
        $this->login = $login;
    }

    public function getId(): ?string
    {
        return $this->id;
    }

    public function getNom(): string
    {
        return $this->nom;
    }

    public function getParcours(): string
    {
        return $this->parcours;
    }

    public function getLogin(): string
    {
        return $this->login;
    }
}
