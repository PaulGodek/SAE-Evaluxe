<?php
namespace App\GenerateurAvis\Controleur;

class ControleurAccueil {
    public static function afficher() {
        include __DIR__ . '/../vue/siteweb/accueil.php';
    }

    public static function afficherErreur($message) {
        echo "Erreur: " . $message;
    }
}
