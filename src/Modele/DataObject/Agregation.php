<?php

namespace App\Modele\DataObject;

class Agregation
{
    private ?int $id;
    private string $nom;
    private string $semestre;
    private string $expression;

    public function __construct(?int $id, string $nom, string $semestre, string $expression)
    {
        $this->id = $id;
        $this->nom = $nom;
        $this->semestre = $semestre;
        $this->expression = $expression;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNom(): string
    {
        return $this->nom;
    }

    public function getSemestre(): string
    {
        return $this->semestre;
    }

    public function getExpression(): string
    {
        return $this->expression;
    }
}
