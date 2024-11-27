<?php

namespace App\GenerateurAvis\Modele\DataObject;

class Note extends AbstractDataObject
{
    private string $code_nip;
    private float $note;
    private string $id_ressource;

    /**
     * @param string $code_nip
     * @param float $note
     * @param string $id_ressource
     */
    public function __construct(string $code_nip, float $note, string $id_ressource)
    {
        $this->code_nip = $code_nip;
        $this->note = $note;
        $this->id_ressource = $id_ressource;
    }

    public function getCodeNip(): string
    {
        return $this->code_nip;
    }

    public function getNote(): float
    {
        return $this->note;
    }

    public function getIdRessource(): string
    {
        return $this->id_ressource;
    }



}