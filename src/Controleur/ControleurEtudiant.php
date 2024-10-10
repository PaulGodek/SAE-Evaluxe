<?php

namespace App\GenerateurAvis\Controleur;

use App\GenerateurAvis\Modele\DataObject\Etudiant;
use App\GenerateurAvis\Modele\Repository\EtudiantRepository;
use TypeError;

class ControleurEtudiant
{
    public static function afficherListe(): void
    {
        $etudiants = EtudiantRepository::recupererEtudiants(); //appel au modèle pour gérer la BD
        self::afficherVue('vueGenerale.php', ["etudiants" => $etudiants, "titre" => "Liste des etudiants", "cheminCorpsVue" => "etudiant/listeEtudiant.php"]);  //"redirige" vers la vue
    }

    public static function afficherListeEtudiantOrdonneParNom(): void
    {
        $etudiants = EtudiantRepository::recupererEtudiantsOrdonneParNom(); //appel au modèle pour gérer la BD
        self::afficherVue('vueGenerale.php', ["etudiants" => $etudiants, "titre" => "Liste des etudiants", "cheminCorpsVue" => "etudiant/listeEtudiant.php"]);  //"redirige" vers la vue
    }

    public static function afficherListeEtudiantOrdonneParMoyenne(): void
    {
        $etudiants = EtudiantRepository::recupererEtudiantsOrdonneParMoyenne(); //appel au modèle pour gérer la BD
        self::afficherVue('vueGenerale.php', ["etudiants" => $etudiants, "titre" => "Liste des etudiants", "cheminCorpsVue" => "etudiant/listeEtudiant.php"]);  //"redirige" vers la vue
    }

    public static function afficherDetail(): void
    {
        try {
            $etudiant = EtudiantRepository::recupererEtudiantParLogin($_GET['login']);
            if ($etudiant == NULL) {
                self::afficherErreur("L'étudiant  {$_GET['login']} n'existe pas");
            } else {
                self::afficherVue('vueGeneraleEtudiantEtEcole.php', ["etudiant" => $etudiant, "titre" => "Détail de {$etudiant->getNom()}", "cheminCorpsVue" => "etudiant/detailEtudiant.php"]);
            }
        } catch (TypeError $e) {
            self::afficherErreur("Jsp ce qu'il s'est passé dsl, voilà l'erreur : {$e->getMessage()}");
        }
    }

    public static function afficherFormulaireCreation(): void
    {
        self::afficherVue('vueGenerale.php', ["titre" => "Formulaire de création de compte école", "cheminCorpsVue" => "etudiant/formulaireCreationEtudiant.php"]);
    }

    public static function creerDepuisFormulaire(): void
    {
        $etudiant = new Etudiant($_GET["login"], $_GET["nom"], $_GET["prenom"], $_GET["moyenne"]);
        EtudiantRepository::ajouter($etudiant);
        $etudiants = EtudiantRepository::recupererEtudiants();
        self::afficherVue('vueGenerale.php', ["etudiants" => $etudiants, "titre" => "Création de compte école", "cheminCorpsVue" => "etudiant/etudiantCree.php"]);
    }

    public static function afficherErreur(string $messageErreur = ""): void
    {
        self::afficherVue('vueGenerale.php', ["messageErreur" => $messageErreur, "titre" => "Erreur", "cheminCorpsVue" => "etudiant/erreurEtudiant.php"]);
    }

    public static function supprimer(): void
    {
        $login = $_GET["login"];
        EtudiantRepository::supprimerEtudiantParLogin($login);
        $etudiants = EtudiantRepository::recupererEtudiants();
        self::afficherVue('vueGenerale.php', ["etudiants" => $etudiants, "login" => $login, "titre" => "Suppression de compte école", "cheminCorpsVue" => "etudiant/etudiantSupprime.php"]);
    }

    public static function afficherFormulaireMiseAJour(): void
    {
        $etudiant = EtudiantRepository::recupererEtudiantParLogin($_GET['login']);
        self::afficherVue('vueGenerale.php', ["etudiant" => $etudiant, "titre" => "Formulaire de mise à jour de compte école", "cheminCorpsVue" => "etudiant/formulaireMiseAJourEtudiant.php"]);
    }

    public static function mettreAJour(): void
    {
        $etudiant = new Etudiant($_GET["login"], $_GET["nom"], $_GET["prenom"], $_GET["moyenne"]);
        EtudiantRepository::mettreAJourEtudiant($etudiant);
        $etudiants = EtudiantRepository::recupererEtudiants();
        self::afficherVue('vueGenerale.php', ["etudiants" => $etudiants, "login" => $etudiant->getLogin(), "titre" => "Suppression de compte école", "cheminCorpsVue" => "etudiant/etudiantMisAJour.php"]);
    }

    private static function afficherVue(string $cheminVue, array $parametres = []): void
    {
        extract($parametres); // Crée des variables à partir du tableau $parametres
        require __DIR__ . "/../vue/$cheminVue"; // Charge la vue
    }

    public static function afficherDetailEtudiantParCodeUnique(): void
    {
        try {
            $etudiant = EtudiantRepository::recupererEtudiantParCodeUnique($_GET['codeUnique']);
            if ($etudiant == NULL) {
                self::afficherErreur("L'étudiant  {$_GET['codeUnique']} n'existe pas");
            } else {
                self::afficherVue('vueEcole.php', ["etudiant" => $etudiant, "titre" => "Détail de {$etudiant->getNom()}", "cheminCorpsVue" => "etudiant/detailEtudiant.php"]);
            }
        } catch (TypeError $e) {
            self::afficherErreur("Quelque chose ne marche pas, voila l'erreur : {$e->getMessage()}");
        }
    }

}