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
    private int $code_nip;
    private static array $codesUniquesUtilisees = [];
    private array $demandes = [];

    /**
     * @throws RandomException
     */
    public function __construct(Utilisateur $etudiant, int $code_nip, ?array $demandes = null, ?string $codeUnique = null)
    {
        $this->etudiant = $etudiant;
        $this->code_nip = $code_nip;

        if ($codeUnique !== null) {
            $this->codeUnique = $codeUnique;
        } else {
            $this->codeUnique = Etudiant::genererCodeUnique();
        }

        $this->demandes = $demandes ?? [];
    }

    public function getUtilisateur(): Utilisateur
    {
        return $this->etudiant;
    }

    public function setUtilisateur(Utilisateur $etudiant): void
    {
        $this->etudiant = $etudiant;
    }


    /**
     * @throws RandomException
     */
    public static function genererCodeUnique(): string
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

    public function getCodeNip(): int
    {
        return $this->code_nip;
    }

    public function setCodeNip(int $code_nip): void
    {
        $this->code_nip = $code_nip;
    }

    public function getDemandes(): array
    {
        return $this->demandes ?? [];
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

    public function getAvisProfesseur(string $loginProfesseur): string
    {
        $avisArray = ProfesseurRepository::getAvis($this->getUtilisateur()->getLogin(), $loginProfesseur);
        return $avisArray['avis'] ?? "";
    }

}