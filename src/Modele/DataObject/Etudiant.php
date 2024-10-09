<?php

namespace App\GenerateurAvis\Modele\DataObject;

use Random\RandomException;
use function Sodium\add;

class Etudiant
{

    private string $login;
    private string $nom;
    private string $prenom;
    private float $moyenne;
    private string $codeUnique;
    private static array $codesUniquesUtilisees = [];

    /**
     * @throws RandomException
     */
    public function __construct(string $login, string $nom, string $prenom, float $moyenne)
    {
        $this->login = substr($login, 0, 64);
        $this->nom = $nom;
        $this->prenom = $prenom;
        $this->moyenne = $moyenne;
        $this->codeUnique = $this->genererCodeUnique();
        self::$codesUniquesUtilisees[] = $this->codeUnique;
    }

    public function getNom(): string
    {
        return $this->nom;
    }

    public function setNom(string $nom): void
    {
        $this->nom = $nom;
    }

    public function getLogin(): string
    {
        return $this->login;
    }

    public function setLogin(string $login): void
    {
        $this->login = substr($login, 0, 64);
    }

    public function getPrenom(): string
    {
        return $this->prenom;
    }

    public function setPrenom(string $prenom): void
    {
        $this->prenom = $prenom;
    }

    public function getMoyenne(): float
    {
        return $this->moyenne;
    }

    public function setMoyenne(float $moyenne): void
    {
        $this->moyenne = $moyenne;
    }

    /**
     * @throws RandomException
     */
    private function genererCodeUnique(): string
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


    // Pour pouvoir convertir un objet en chaîne de caractères
    /*public function __toString() : string
    {
        return "<p>Utilisateur $this->prenom $this->nom de login $this->login.</p>";
    }*/
}