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

    public static function afficherErreur(string $messageErreur = " "): void
    {

        echo($messageErreur);
    }
    public static function verifierAdminConnectee(): bool
    {
        if (!ConnexionUtilisateur::estConnecte()) {
            ControleurUtilisateur::afficherErreur("Veuillez vous connecter d'abord.");
            return false;
        }

        if (!ConnexionUtilisateur::estAdministrateur()) {
            self::afficherErreur("Vous n'avez pas de droit d'accès pour cette page");
            return false;
        }
        return true;
    }

    public static function home(): void
    {
        self::afficherVue('vueGenerale.php', ["titre" => "Accueil", "cheminCorpsVue" => "siteweb/accueil.php"]);
    }

    public static function redirectionVersURL(string $type, string $message, string $url): void
    {
        MessageFlash::ajouter($type,$message);
        header("Location: controleurFrontal.php?action=$url");
        exit();
    }
}