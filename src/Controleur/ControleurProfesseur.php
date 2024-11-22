<?php

namespace App\GenerateurAvis\Controleur;

use App\GenerateurAvis\Lib\ConnexionUtilisateur;
use App\GenerateurAvis\Lib\MessageFlash;
use App\GenerateurAvis\Modele\DataObject\Professeur;
use App\GenerateurAvis\Modele\Repository\ProfesseurRepository;
use App\GenerateurAvis\Modele\Repository\UtilisateurRepository;
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
            self::redirectionVersURL("error", "Vous n'avez pas de droit d'accès pour cette page", "afficherAccueil&controleur=Accueil");
            return;
        }
        $professeurs = (new ProfesseurRepository())->recuperer(); //appel au modèle pour gérer la BD
        self::afficherVue('vueGenerale.php', ["professeurs" => $professeurs, "titre" => "Liste des professeurs", "cheminCorpsVue" => "professeur/listeProfesseur.php"]);  //"redirige" vers la vue
    }

    public static function afficherListeProfesseurOrdonneParNom(): void
    {
        if (!ConnexionUtilisateur::estAdministrateur()) {
            self::redirectionVersURL("error", "Vous n'avez pas de droit d'accès pour cette page", "afficherAccueil&controleur=Accueil");
            return;
        }
        $professeurs = ProfesseurRepository::recupererProfesseursOrdonneParNom(); //appel au modèle pour gérer la BD
        self::afficherVue('vueGenerale.php', ["professeurs" => $professeurs, "titre" => "Liste des professeurs", "cheminCorpsVue" => "professeur/listeProfesseur.php"]);  //"redirige" vers la vue
    }

    public static function afficherListeProfesseurOrdonneParPrenom(): void
    {
        if (!ConnexionUtilisateur::estAdministrateur()) {
            self::redirectionVersURL("error", "Vous n'avez pas de droit d'accès pour cette page", "afficherAccueil&controleur=Accueil");
            return;
        }
        $professeurs = ProfesseurRepository::recupererProfesseursOrdonneParPrenom(); //appel au modèle pour gérer la BD
        self::afficherVue('vueGenerale.php', ["professeurs" => $professeurs, "titre" => "Liste des professeurs", "cheminCorpsVue" => "professeur/listeProfesseur.php"]);  //"redirige" vers la vue
    }

    public static function afficherDetail(): void
    {
        if (!ConnexionUtilisateur::estConnecte()) {
            //self::afficherErreur("Veuillez vous connecter d'abord.");
            self::redirectionVersURL("warning", "Veuillez vous connecter d'abord", "afficherPreference&controleur=Connexion");
            return;
        }

        if (!ConnexionUtilisateur::estAdministrateur()) {
            if (!ConnexionUtilisateur::estProfesseur()) {
//                self::afficherErreur("Vous n'avez pas de droit d'accès pour cette page.");
                self::redirectionVersURL("error", "Vous n'avez pas de droit d'accès pour cette page", "afficherAccueil&controleur=Accueil");
                return;
            }
        }
        try {
            $professeur = (new ProfesseurRepository)->recupererParClePrimaire($_GET['login']);
            if ($professeur == NULL) {
                MessageFlash::ajouter("error", "Le professeur {$_GET['login']} n'existe pas");
                self::afficherErreurProfesseur(" ");
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
            self::redirectionVersURL("error", "Vous n'avez pas de droit d'accès pour cette page", "afficherAccueil&controleur=Accueil");
            return;
        }
        self::afficherVue('vueGenerale.php', ["titre" => "Formulaire de création de compte professeur", "cheminCorpsVue" => "professeur/formulaireCreationProfesseur.php"]);

    }

    public static function creerDepuisFormulaire(): void
    {
        if (!ConnexionUtilisateur::estAdministrateur()) {
            self::redirectionVersURL("error", "Vous n'avez pas de droit d'accès pour cette page", "afficherAccueil&controleur=Accueil");
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
        MessageFlash::ajouter("success", "Le compte professeur a bien été créé !");
        $professeurs = (new ProfesseurRepository)->recuperer();
        self::afficherVue('vueGenerale.php', ["professeurs" => $professeurs, "titre" => "Création de compte professeur", "cheminCorpsVue" => "professeur/detailProfesseur.php"]);
    }

    public static function supprimer(): void
    {
        if (!ConnexionUtilisateur::estAdministrateur()) {
            self::redirectionVersURL("error", "Vous n'avez pas de droit d'accès pour cette page", "afficherAccueil&controleur=Accueil");
            return;
        }
        if (!isset($_GET["login"])) {
            self::redirectionVersURL("error", "Vous n'avez pas de droit d'accès pour cette page", "afficherAccueil&controleur=Accueil");
            return;
        }
        $login = $_GET["login"];
        (new ProfesseurRepository)->supprimer($login);
        MessageFlash::ajouter("success", "Le compte de login " . htmlspecialchars($login) . " a bien été supprimé");
        $professeurs = (new ProfesseurRepository)->recuperer();
        self::afficherVue('vueGenerale.php', ["professeurs" => $professeurs, "login" => $login, "titre" => "Suppression de compte professeur", "cheminCorpsVue" => "professeur/professeurSupprime.php"]);
    }

    public static function afficherFormulaireMiseAJour(): void
    {
        if (!ConnexionUtilisateur::estAdministrateur()) {
            self::redirectionVersURL("error", "Vous n'avez pas de droit d'accès pour cette page", "afficherAccueil&controleur=Accueil");
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
            self::redirectionVersURL("error", "Vous n'avez pas de droit d'accès pour cette page", "afficherAccueil&controleur=Accueil");
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
        MessageFlash::ajouter("success", "Le compte de login " . htmlspecialchars($professeur->getUtilisateur()->getLogin()) . " a bien été mis à jour");
        $professeurs = (new ProfesseurRepository)->recuperer();
        self::afficherVue('vueGenerale.php', ["professeurs" => $professeurs, "login" => $professeur->getUtilisateur()->getLogin(), "titre" => "Suppression de compte professeur", "cheminCorpsVue" => "professeur/professeurMisAJour.php"]);
    }

    public static function creerProfesseurDepuisFormulaire(): void
    {
        if (!ConnexionUtilisateur::estAdministrateur()) {
            self::redirectionVersURL("error", "Vous n'avez pas de droit d'accès pour cette page", "afficherAccueil&controleur=Accueil");
            return;
        }

        $mdp = $_GET['mdp'] ?? '';
        $mdp2 = $_GET['mdp2'] ?? '';

        if ($mdp !== $mdp2) {
            MessageFlash::ajouter("warning", "Les mots de passes ne correspondent pas");
            self::afficherErreurProfesseur(" ");
            return;
        }
        $utilisateur = self::construireDepuisFormulaire($_GET);
        (new UtilisateurRepository)->ajouter($utilisateur);


        $professeur = new Professeur($_GET["login"], $_GET["nom"], $_GET["prenom"]);
        (new ProfesseurRepository)->ajouter($professeur);
        MessageFlash::ajouter("success", "Le compte professeur a bien été créé !");
        $professeurs = (new ProfesseurRepository)->recuperer();
        self::afficherVue('vueGenerale.php', ["professeurs" => $professeurs, "titre" => "Création du professeur", "cheminCorpsVue" => "professeur/listeProfesseur.php"]);
    }

    public static function afficherResultatRechercheProfesseur(): void
    {
        if (!ConnexionUtilisateur::estAdministrateur()) {
            self::redirectionVersURL("error", "Vous n'avez pas de droit d'accès pour cette page", "afficherAccueil&controleur=Accueil");
            return;
        }
        $professeurs = ProfesseurRepository::rechercherProfesseur($_GET['reponse']);
        self::afficherVue("vueGenerale.php", ["professeurs" => $professeurs, "titre" => "Résultat recherche professeur", "cheminCorpsVue" => "professeur/listeProfesseur.php"]);
    }
}