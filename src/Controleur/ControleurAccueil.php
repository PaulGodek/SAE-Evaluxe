<?php
namespace App\GenerateurAvis\Controleur;
use App\GenerateurAvis\Modele\HTTP\Cookie;

class ControleurAccueil {
    public static function afficher(): void
    {
        self::afficherVue('vueGenerale.php', [
            'titre' => 'Bienvenue sur le site',
            'cheminCorpsVue' => 'siteweb/accueil.php'
        ]);
    }

    public static function afficherErreur(string $messageErreur = ""): void
    {
        self::afficherVue('vueGenerale.php', [
            'messageErreur' => $messageErreur,
            'titre' => 'Erreur',
            'cheminCorpsVue' => 'siteweb/erreur.php'
        ]);
    }

    private static function afficherVue(string $cheminVue, array $parametres = []): void
    {
        extract($parametres); // Crée des variables à partir du tableau $parametres
        require __DIR__ . "/../vue/$cheminVue"; // Charge la vue
    }
}
