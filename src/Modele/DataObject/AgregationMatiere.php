<?php

namespace App\GenerateurAvis\Modele\DataObject;

class AgregationMatiere extends AbstractDataObject
{
    private int $id_agregation;
    private int $id_ressource;
    private float $coefficient;

    public function __construct(int $id_agregation, int $id_ressource, float $coefficient)
    {
        $this->id_agregation = $id_agregation;
        $this->id_ressource = $id_ressource;
        $this->coefficient = $coefficient;
    }

    public function getIdAgregation(): int
    {
        return $this->id_agregation;
    }

    public function getIdRessource(): int
    {
        return $this->id_ressource;
    }

    public function getCoefficient(): float
    {
        return $this->coefficient;
    }
}
