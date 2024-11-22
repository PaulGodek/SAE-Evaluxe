<?php

namespace App\GenerateurAvis\Modele\DataObject;

class Matiere
{
    private string $id_ressource;
    private float $coefficient;

    public function __construct(string $id_ressource, float $coefficient)
    {
        $this->id_ressource = $id_ressource;
        $this->coefficient = $coefficient;
    }

    public function getId_ressource(): string
    {
        return $this->id_ressource;
    }

    public function getCoefficient(): float
    {
        return $this->coefficient;
    }
}
