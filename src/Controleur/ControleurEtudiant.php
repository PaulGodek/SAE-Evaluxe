<?php

namespace App\GenerateurAvis\Controleur;

use App\GenerateurAvis\Modele\DataObject\Etudiant;
use App\GenerateurAvis\Modele\Repository\EtudiantRepository;
use Random\RandomException;
use TypeError;

class ControleurEtudiant extends ControleurGenerique
{
    public static function afficherListe(): void
    {
        $etudiants = (new EtudiantRepository)->recuperer(); //appel au modèle pour gérer la BD
        self::afficherVue('vueGenerale.php', ["etudiants" => $etudiants, "titre" => "Liste des etudiants", "cheminCorpsVue" => "etudiant/listeEtudiant.php"]);  //"redirige" vers la vue
    }

    /**
     * @throws RandomException
     */
    public static function afficherListeEtudiantOrdonneParNom(): void
    {
        $etudiants = EtudiantRepository::recupererEtudiantsOrdonneParNom(); //appel au modèle pour gérer la BD
        self::afficherVue('vueGenerale.php', ["etudiants" => $etudiants, "titre" => "Liste des etudiants", "cheminCorpsVue" => "etudiant/listeEtudiant.php"]);  //"redirige" vers la vue
    }

    public static function afficherListeEtudiantOrdonneParPrenom(): void
    {
        $etudiants = EtudiantRepository::recupererEtudiantsOrdonneParPrenom(); //appel au modèle pour gérer la BD
        self::afficherVue('vueGenerale.php', ["etudiants" => $etudiants, "titre" => "Liste des etudiants", "cheminCorpsVue" => "etudiant/listeEtudiant.php"]);  //"redirige" vers la vue
    }

    /*public static function afficherListeEtudiantOrdonneParParcours(): void
    {
        $etudiants = EtudiantRepository::recupererEtudiantsOrdonneParPrenom(); //appel au modèle pour gérer la BD
        self::afficherVue('vueGenerale.php', ["etudiants" => $etudiants, "titre" => "Liste des etudiants", "cheminCorpsVue" => "etudiant/listeEtudiant.php"]);  //"redirige" vers la vue
    }*/

    public static function afficherDetail(): void
    {
        try {
            $etudiant = (new EtudiantRepository)->recupererParClePrimaire($_GET['login']);
            if ($etudiant == NULL) {
                self::afficherErreur("L'étudiant  {$_GET['login']} n'existe pas");
            } else {
                self::afficherVue('vueGenerale.php', ["etudiant" => $etudiant, "titre" => "Détail de {$etudiant->getNom()}", "cheminCorpsVue" => "etudiant/detailEtudiant.php"]);
            }
        } catch (TypeError $e) {
            self::afficherErreur("Jsp ce qu'il s'est passé dsl, voilà l'erreur : {$e->getMessage()}");
        }
    }

    public static function afficherFormulaireCreation(): void
    {
        self::afficherVue('vueGenerale.php', ["titre" => "Formulaire de création de compte école", "cheminCorpsVue" => "etudiant/formulaireCreationEtudiant.php"]);
    }

    /**
     * @throws RandomException
     */
    public static function creerDepuisFormulaire(): void
    {
        $etudiant = new Etudiant($_GET["login"], $_GET["nom"], $_GET["prenom"], $_GET["moyenne"]);
        (new EtudiantRepository)->ajouter($etudiant);
        $etudiants = (new EtudiantRepository)->recuperer();
        self::afficherVue('vueGenerale.php', ["etudiants" => $etudiants, "titre" => "Création de compte école", "cheminCorpsVue" => "etudiant/etudiantCree.php"]);
    }

    public static function afficherErreur(string $messageErreur = ""): void
    {
        self::afficherVue('vueGenerale.php', ["messageErreur" => $messageErreur, "titre" => "Erreur", "cheminCorpsVue" => "etudiant/erreurEtudiant.php"]);
    }

    public static function supprimer(): void
    {
        $login = $_GET["login"];
        (new EtudiantRepository)->supprimer($login);
        $etudiants = (new EtudiantRepository)->recuperer();
        self::afficherVue('vueGenerale.php', ["etudiants" => $etudiants, "login" => $login, "titre" => "Suppression de compte école", "cheminCorpsVue" => "etudiant/etudiantSupprime.php"]);
    }

    public static function afficherFormulaireMiseAJour(): void
    {
        $etudiant = (new EtudiantRepository)->recupererParClePrimaire($_GET['login']);
        self::afficherVue('vueGenerale.php', ["etudiant" => $etudiant, "titre" => "Formulaire de mise à jour de compte école", "cheminCorpsVue" => "etudiant/formulaireMiseAJourEtudiant.php"]);
    }

    /**
     * @throws RandomException
     */
    public static function mettreAJour(): void
    {
        $etudiant = new Etudiant($_GET["login"], $_GET["nom"], $_GET["prenom"], $_GET["moyenne"]);
        (new EtudiantRepository)->mettreAJour($etudiant);
        $etudiants = (new EtudiantRepository)->recuperer();
        self::afficherVue('vueGenerale.php', ["etudiants" => $etudiants, "login" => $etudiant->getLogin(), "titre" => "Suppression de compte école", "cheminCorpsVue" => "etudiant/etudiantMisAJour.php"]);
    }

    public static function afficherDetailEtudiantParCodeUnique(): void
    {
        try {
            $etudiant = EtudiantRepository::recupererEtudiantParCodeUnique($_GET['codeUnique']);
            if ($etudiant == NULL) {
                self::afficherErreur("L'étudiant  {$_GET['codeUnique']} n'existe pas");
            } else {
                self::afficherVue('vueGenerale.php', ["etudiant" => $etudiant, "titre" => "Détail de {$etudiant->getNom()}", "cheminCorpsVue" => "etudiant/detailEtudiant.php"]);
            }
        } catch (TypeError $e) {
            self::afficherErreur("Quelque chose ne marche pas, voila l'erreur : {$e->getMessage()}");
        }
    }



}