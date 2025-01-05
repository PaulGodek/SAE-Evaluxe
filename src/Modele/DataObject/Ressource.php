<?php

namespace App\GenerateurAvis\Modele\DataObject;
class Ressource extends AbstractDataObject
{
    private string $id_ressource;
    private string $nom;

    public function __construct(string $id_ressource, string $nom)
    {
        $this->id_ressource = $id_ressource;
        $this->nom = $nom;
    }

    public function getNom(): string
    {
        return $this->nom;
    }

    public function getId_ressource(): string
    {
        return $this->id_ressource;
    }
}
