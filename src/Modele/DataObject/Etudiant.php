<?php

namespace App\GenerateurAvis\Modele\DataObject;

use App\GenerateurAvis\Modele\Repository\AbstractRepository;
use App\GenerateurAvis\Modele\Repository\EtudiantRepository;
use Random\RandomException;

class Etudiant extends AbstractDataObject
{

    private string $login;
    private string $codeUnique;
    private int $idEtudiant;
    private static array $codesUniquesUtilisees = [];
    private array $demandes;

    /**
     * @throws RandomException
     */
    public function __construct(string $login, int $idEtudiant, ?array $demandes, ?string $codeUnique = null)
    {
        $this->login = substr($login, 0, 64);
        $this->idEtudiant = $idEtudiant;

        if ($codeUnique !== null) {
            $this->codeUnique = $codeUnique;
        } else {
            $this->codeUnique = $this->genererCodeUnique();
        }

        $this->demandes = $demandes;
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

    public function getIdEtudiant(): int
    {
        return $this->idEtudiant;
    }

    public function setIdEtudiant(int $idEtudiant): void
    {
        $this->idEtudiant = $idEtudiant;
    }

    public function getDemandes(): array
    {
        return $this->demandes;
    }

    public function addDemande(string $nom): void
    {
        if (!in_array($nom, $this->demandes)) {

            $this->demandes[] = $nom;
        }
    }

    public function faireDemande(): bool
    {
        return EtudiantRepository::demander($this);
    }


    public function dejaDemande($nom): bool
    {
        return in_array($nom, $this->demandes);
    }

    public function removeDemande($nom)
    {


            $this->demandes = array_diff($this->demandes, [$nom]);
            return (new EtudiantRepository())->mettreAJourDemandes($this);


    }

}