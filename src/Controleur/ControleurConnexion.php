<?php
namespace App\GenerateurAvis\Controleur;

class ControleurConnexion {
    public static function afficherAdministrateur() {
        include __DIR__ . '/../../web/views/connexion.php';
    }

    public static function afficherPreference() {
        include __DIR__ . '/../../web/views/preference.php';
    }

    public static function afficherErreur($message) {
        echo "Erreur: " . $message;
    }

    public function connecter() {
        // TO DO
    }
}

