<?php

namespace App\GenerateurAvis\Controleur;

use App\GenerateurAvis\Lib\ConnexionUtilisateur;
use App\GenerateurAvis\Lib\MessageFlash;

class ControleurGenerique
{
    protected static function afficherVue(string $cheminVue, array $parametres = []): void
    {
        extract($parametres); // Crée des variables à partir du tableau $parametres
        require __DIR__ . "/../vue/$cheminVue"; // Charge la vue
    }

    public static function afficherErreur(string $messageErreur = "", string $controleur = ""): void
    {
        self::afficherVue('vueGenerale.php', ['messageErreur' => $messageErreur, 'controleur' => $controleur, 'titre' => "Erreur", 'cheminCorpsVue' => 'erreur.php']);
    }

    public static function verifierAdminConnecte(): bool
    {
        if (!ConnexionUtilisateur::estConnecte()) {
//            self::afficherErreur("Veuillez vous connecter d'abord.");
            self::redirectionVersURL("error", "Veuillez vous connecter d'abord.", "afficherPreference&controleur=Connexion");
            return false;
        }

        if (!ConnexionUtilisateur::estAdministrateur()) {
//            self::afficherErreur("Vous n'avez pas de droit d'accès pour cette page");
            self::redirectionVersURL("error", "Vous n'avez pas de droit d'accès pour cette page", "home");
            return false;
        }
        return true;
    }

    public static function verifierEtudiantConnecte(): bool
    {
        if (!ConnexionUtilisateur::estConnecte()) {
//            self::afficherErreur("Veuillez vous connecter d'abord.");
            self::redirectionVersURL("error", "Veuillez vous connecter d'abord.", "afficherPreference&controleur=Connexion");
            return false;
        }

        if (!ConnexionUtilisateur::estEtudiant()) {
//            self::afficherErreur("Vous n'avez pas de droit d'accès pour cette page");
            self::redirectionVersURL("error", "Vous n'avez pas de droit d'accès pour cette page", "home");
            return false;
        }
        return true;
    }

    public static function verifierProfesseurConnecte(): bool
    {
        if (!ConnexionUtilisateur::estConnecte()) {
//            self::afficherErreur("Veuillez vous connecter d'abord.");
            self::redirectionVersURL("error", "Veuillez vous connecter d'abord.", "afficherPreference&controleur=Connexion");

            return false;
        }

        if (!ConnexionUtilisateur::estProfesseur()) {
//            self::afficherErreur("Vous n'avez pas de droit d'accès pour cette page");
            self::redirectionVersURL("error", "Vous n'avez pas de droit d'accès pour cette page", "home");
            return false;
        }
        return true;
    }

    public static function verifierEcoleConnecte(): bool
    {
        if (!ConnexionUtilisateur::estConnecte()) {
//            self::afficherErreur("Veuillez vous connecter d'abord.");
            self::redirectionVersURL("error", "Veuillez vous connecter d'abord.", "afficherPreference&controleur=Connexion");
            return false;
        }

        if (!ConnexionUtilisateur::estEcole()) {
//            self::afficherErreur("Vous n'avez pas de droit d'accès pour cette page");
            self::redirectionVersURL("error", "Vous n'avez pas de droit d'accès pour cette page", "home");
            return false;
        }
        return true;
    }

    public static function redirectionVersURL(string $type, string $message, string $url): void
    {
        MessageFlash::ajouter($type,$message);
        header("Location: controleurFrontal.php?action=$url");
        exit();
    }
}