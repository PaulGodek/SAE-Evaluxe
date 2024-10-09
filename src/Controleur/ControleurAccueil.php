<?php
namespace App\GenerateurAvis\Controleur;

class ControleurAccueil {
    public static function afficher(): void
    {
        include __DIR__ . '/../vue/siteweb/accueil.php';
    }

    public static function afficherErreur($message): void
    {
        echo "Erreur: " . $message;
    }
}
