<?php
namespace App\Covoiturage\Modele\DataObject;

use App\Covoiturage\Modele\Repository\UtilisateurRepository;
class Utilisateur
{

    private string $login;
    private string $nom;
    private string $prenom;
    /**
     * @var Trajet[]|null
     */
    private ?array $trajetsCommePassager;

    public function __construct(string $login, string $nom, string $prenom)
    {
        $this->login = substr($login, 0, 64);
        $this->nom = $nom;
        $this->prenom = $prenom;
        $this->trajetsCommePassager = null;
    }

    public function getTrajetsCommePassager(): ?array
    {
        if (is_null($this->trajetsCommePassager))
            $this->trajetsCommePassager = UtilisateurRepository::recupererTrajetsCommePassager($this);
        return $this->trajetsCommePassager;
    }

    public function setTrajetsCommePassager(?array $trajetsCommePassager): void
    {
        $this->trajetsCommePassager = $trajetsCommePassager;
    }

    public function getNom() : string
    {
        return $this->nom;
    }

    public function setNom(string $nom) : void
    {
        $this->nom = $nom;
    }

    public function getLogin() : string
    {
        return $this->login;
    }
    public function setLogin(string $login) : void
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

    // Pour pouvoir convertir un objet en chaîne de caractères
    /*public function __toString() : string
    {
        return "<p>Utilisateur $this->prenom $this->nom de login $this->login.</p>";
    }*/
}