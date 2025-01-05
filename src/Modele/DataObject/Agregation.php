<?php

namespace App\GenerateurAvis\Modele\DataObject;

use App\GenerateurAvis\Modele\Repository\AbstractRepository;

class Agregation extends AbstractDataObject
{
    private ?int $id;
    private string $nom;
    private string $parcours;
    private string $login;

    /**
     * @param string $nom
     * @param string $parcours
     * @param string $login
     * @param ?int $id
     */
    public function __construct(string $nom, string $parcours, string $login, int $id = null)
    {
        $this->nom = $nom;
        $this->id = $id;
        $this->parcours = $parcours;
        $this->login = $login;
    }

    public function getId(): ?int
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
