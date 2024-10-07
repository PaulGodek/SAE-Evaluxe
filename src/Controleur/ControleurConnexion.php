<?php
namespace App\GenerateurAvis\Controleur;

class ControleurConnexion {
    public static function afficher() {
        include __DIR__ . '/../../web/views/connexion.php';
    }

    public static function afficherErreur($message) {
        echo "Erreur: " . $message;
    }

    public function connecter() {
        // TO DO
    }
}

