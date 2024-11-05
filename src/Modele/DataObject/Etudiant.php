<?php

namespace App\GenerateurAvis\Modele\DataObject;

use Random\RandomException;

class Etudiant extends AbstractDataObject
{

    private string $login;
    private string $codeUnique;
    private string $idEtudiant;
    private static array $codesUniquesUtilisees = [];

    /**
     * @throws RandomException
     */
    public function __construct(string $login, string $idEtudiant)
    {
        $this->login = substr($login, 0, 64);
        $this->idEtudiant = $idEtudiant;
        $this->codeUnique = $this->genererCodeUnique();
        self::$codesUniquesUtilisees[] = $this->codeUnique;
    }

    public function getLogin(): string
    {
        return $this->login;
    }

    public function setLogin(string $login): void
    {
        $this->login = substr($login, 0, 64);
    }

    /**
     * @throws RandomException
     */
    public function genererCodeUnique(): string
    {
        do {
            $code = bin2hex(random_bytes(5));
        } while (in_array($code, self::$codesUniquesUtilisees));

        return $code;
    }

    public function getCodeUnique(): string
    {
        return $this->codeUnique;
    }

    public function getIdEtudiant(): string
    {
        return $this->idEtudiant;
    }

    public function setIdEtudiant(string $idEtudiant): void
    {
        $this->idEtudiant = $idEtudiant;
    }
}