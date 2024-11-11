<?php

namespace App\GenerateurAvis\Controleur;

use App\GenerateurAvis\Lib\ConnexionUtilisateur;
use App\GenerateurAvis\Lib\MessageFlash;
use App\GenerateurAvis\Modele\DataObject\Professeur;
use App\GenerateurAvis\Modele\Repository\ProfesseurRepository;
use TypeError;

class ControleurProfesseur extends ControleurGenerique
{
    public static function afficherErreurProfesseur(string $messageErreur = ""): void
    {
        self::afficherErreur($messageErreur, 'professeur');
    }

    public static function afficherListe(): void
    {
        if (!ConnexionUtilisateur::estAdministrateur()) {
            self::afficherErreurProfesseur("Vous n'avez pas de droit d'accès pour cette page");
            return;
        }
        $professeurs = (new ProfesseurRepository())->recuperer(); //appel au modèle pour gérer la BD
        self::afficherVue('vueGenerale.php', ["professeurs" => $professeurs, "titre" => "Liste des professeurs", "cheminCorpsVue" => "professeur/listeProfesseur.php"]);  //"redirige" vers la vue
    }

    public static function afficherListeProfesseurOrdonneParNom(): void
    {
        if (!ConnexionUtilisateur::estAdministrateur()) {
            self::afficherErreurProfesseur("Vous n'avez pas de droit d'accès pour cette page");
            return;
        }
        $professeurs = ProfesseurRepository::recupererProfesseursOrdonneParNom(); //appel au modèle pour gérer la BD
        self::afficherVue('vueGenerale.php', ["professeurs" => $professeurs, "titre" => "Liste des professeurs", "cheminCorpsVue" => "professeur/listeProfesseur.php"]);  //"redirige" vers la vue
    }

    public static function afficherListeProfesseurOrdonneParPrenom(): void
    {
        if (!ConnexionUtilisateur::estAdministrateur()) {
            self::afficherErreurProfesseur("Vous n'avez pas de droit d'accès pour cette page");
            return;
        }
        $professeurs = ProfesseurRepository::recupererProfesseursOrdonneParPrenom(); //appel au modèle pour gérer la BD
        self::afficherVue('vueGenerale.php', ["professeurs" => $professeurs, "titre" => "Liste des professeurs", "cheminCorpsVue" => "professeur/listeProfesseur.php"]);  //"redirige" vers la vue
    }

    public static function afficherDetail(): void
    {
        try {
            $professeur = (new ProfesseurRepository)->recupererParClePrimaire($_GET['login']);
            if ($professeur == NULL) {
                self::afficherErreurProfesseur(" ");
                MessageFlash::ajouter("error", "Le professeur {$_GET['login']} n'existe pas");
            } else {
                self::afficherVue('vueGenerale.php', ["professeur" => $professeur, "titre" => "Détail de {$professeur->getNom()}", "cheminCorpsVue" => "professeur/detailProfesseur.php"]);
            }
        } catch (TypeError $e) {
            self::afficherErreurProfesseur(" ");
            MessageFlash::ajouter("warning", $e->getMessage());
        }
    }

    public static function afficherFormulaireCreation(): void
    {
        if (!ConnexionUtilisateur::estAdministrateur()) {
            self::afficherErreurProfesseur("Vous n'avez pas de droit d'accès pour cette page");
            return;
        }
        self::afficherVue('vueGenerale.php', ["titre" => "Formulaire de création de compte professeur", "cheminCorpsVue" => "professeur/formulaireCreationProfesseur.php"]);

    }

    public static function creerDepuisFormulaire(): void
    {
        if (!ConnexionUtilisateur::estAdministrateur()) {
            self::afficherErreurProfesseur("Vous n'avez pas de droit d'accès pour cette page");
            return;
        }
        if (!isset($_GET["login"])) {
            self::afficherErreurProfesseur("Le login n'est pas renseigné");
            return;
        } elseif (!isset($_GET["nom"])) {
            self::afficherErreurProfesseur("Le nom n'est pas renseigné");
            return;
        } elseif (!isset($_GET["prenom"])) {
            self::afficherErreurProfesseur("Le prénom n'est pas renseigné");
            return;
        }
        $professeur = new professeur($_GET["login"], $_GET["nom"], $_GET["prenom"]);
        (new ProfesseurRepository)->ajouter($professeur);
        MessageFlash::ajouter("success","Le compte professeur a bien été créé !");
        $professeurs = (new ProfesseurRepository)->recuperer();
        self::afficherVue('vueGenerale.php', ["professeurs" => $professeurs, "titre" => "Création de compte professeur", "cheminCorpsVue" => "professeur/detailProfesseur.php"]);
    }

    public static function supprimer(): void
    {
        if (!ConnexionUtilisateur::estAdministrateur()) {
            self::afficherErreurProfesseur("Vous n'avez pas de droit d'accès pour cette page");
            return;
        }
        if (!isset($_GET["login"])) {
            self::afficherErreurProfesseur("Le login n'est pas renseigné");
            return;
        }
        $login = $_GET["login"];
        (new ProfesseurRepository)->supprimer($login);
        MessageFlash::ajouter("success","Le compte de login ".htmlspecialchars($login)." a bien été supprimé");
        $professeurs = (new ProfesseurRepository)->recuperer();
        self::afficherVue('vueGenerale.php', ["professeurs" => $professeurs, "login" => $login, "titre" => "Suppression de compte professeur", "cheminCorpsVue" => "professeur/professeurSupprime.php"]);
    }

    public static function afficherFormulaireMiseAJour(): void
    {
        if (!ConnexionUtilisateur::estAdministrateur()) {
            self::afficherErreurProfesseur("Vous n'avez pas de droit d'accès pour cette page");
            return;
        }
        if (!isset($_GET["login"])) {
            self::afficherErreurProfesseur("Le login n'est pas renseigné");
            return;
        }
        $professeur = (new ProfesseurRepository)->recupererParClePrimaire($_GET['login']);
        self::afficherVue('vueGenerale.php', ["professeur" => $professeur, "titre" => "Formulaire de mise à jour de compte professeur", "cheminCorpsVue" => "professeur/formulaireMiseAJourProfesseur.php"]);

    }

    public static function mettreAJour(): void
    {
        if (!ConnexionUtilisateur::estAdministrateur()) {
            self::afficherErreurProfesseur("Vous n'avez pas de droit d'accès pour cette page");
            return;
        }
        if (!isset($_GET["login"])) {
            self::afficherErreurProfesseur("Le login n'est pas renseigné");
            return;
        } elseif (!isset($_GET["nom"])) {
            self::afficherErreurProfesseur("Le nom n'est pas renseigné");
            return;
        } elseif (!isset($_GET["prenom"])) {
            self::afficherErreurProfesseur("Le prénom n'est pas renseigné");
            return;
        }
        $professeur = new Professeur($_GET["login"], $_GET["nom"], $_GET["prenom"]);
        (new ProfesseurRepository)->mettreAJour($professeur);
        MessageFlash::ajouter("success","Le compte de login ".htmlspecialchars($professeur->getLogin())." a bien été mis à jour");
        $professeurs = (new ProfesseurRepository)->recuperer();
        self::afficherVue('vueGenerale.php', ["professeurs" => $professeurs, "login" => $professeur->getLogin(), "titre" => "Suppression de compte professeur", "cheminCorpsVue" => "professeur/professeurMisAJour.php"]);
    }
}