<?php
namespace App\GenerateurAvis\Controleur;

class ControleurAccueil {
    public static function afficher(): void
    {
        self::afficherVue('vueGenerale.php', [
            'titre' => 'Accueil',
            'cheminCorpsVue' => 'siteweb/accueil.php'
        ]);
    }

    public static function afficherErreur(string $messageErreur = ""): void
    {
        ControleurGenerique::afficherErreur($messageErreur, "accueil");
    }

    private static function afficherVue(string $cheminVue, array $parametres = []): void
    {
        extract($parametres); // Crée des variables à partir du tableau $parametres
        require __DIR__ . "/../vue/$cheminVue"; // Charge la vue
    }
}
