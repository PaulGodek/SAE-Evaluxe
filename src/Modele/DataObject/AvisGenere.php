<?php

namespace App\GenerateurAvis\Modele\DataObject;

class AvisGenere extends AbstractDataObject
{
    private string $code_nip;
    private string $avisGenereIngenieur;
    private string $avisGenereManagement;

    public function __construct(string $code_nip, string $avisGenereIngenieur, string $avisGenereManagement) {
        $this->code_nip = $code_nip;
        $this->avisGenereIngenieur = $avisGenereIngenieur;
        $this->avisGenereManagement = $avisGenereManagement;
    }

    public function getAvisGenereIngenieur(): string
    {
        return $this->avisGenereIngenieur;
    }

    public function getAvisGenereManagement(): string
    {
        return $this->avisGenereManagement;
    }
}