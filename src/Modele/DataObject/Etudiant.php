<?php

namespace App\GenerateurAvis\Modele\DataObject;

use App\GenerateurAvis\Modele\Repository\AbstractRepository;
use App\GenerateurAvis\Modele\Repository\EtudiantRepository;
use App\GenerateurAvis\Modele\Repository\ProfesseurRepository;
use Random\RandomException;

class Etudiant extends AbstractDataObject
{

    private Utilisateur $etudiant;
    private string $codeUnique;
    private int $idEtudiant;
    private static array $codesUniquesUtilisees = [];
    private array $demandes;

    /**
     * @throws RandomException
     */
    public function __construct(Utilisateur $etudiant, int $idEtudiant, ?array $demandes, ?string $codeUnique = null)
    {
        $this->etudiant = $etudiant;
        $this->idEtudiant = $idEtudiant;

        if ($codeUnique !== null) {
            $this->codeUnique = $codeUnique;
        } else {
            $this->codeUnique = $this->genererCodeUnique();
        }

        $this->demandes = $demandes;
    }

    public function getEtudiant(): Utilisateur
    {
        return $this->etudiant;
    }

    public function setEtudiant(Utilisateur $etudiant): void
    {
        $this->etudiant = $etudiant;
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

    public function removeDemande($nom): bool
    {
            $this->demandes = array_diff($this->demandes, [$nom]);
            return (new EtudiantRepository())->mettreAJourDemandes($this);
    }

    public function getAvisProfesseur(string $loginProfesseur) : string {
        return ProfesseurRepository::getAvis($this->getEtudiant()->getLogin(), $loginProfesseur);
    }

}