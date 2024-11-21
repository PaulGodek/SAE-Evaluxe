<?php

namespace App\GenerateurAvis\Modele\DataObject;

class Resource extends AbstractDataObject
{
    private int $id_resource;
    private string $nom;

    public function __construct(int $id, string $nom)
    {
        $this->id = $id;
        $this->nom = $nom;
    }

    public function getNom(): string
    {
        return $this->nom;
    }

    public function getId_resource(): int
    {
        return $this->id_resource;
    }
}
