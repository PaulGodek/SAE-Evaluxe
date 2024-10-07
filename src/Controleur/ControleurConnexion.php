<?php
namespace App\GenerateurAvis\Controleur;

class ControleurConnexion {
    public static function afficherAdministrateur() {
        include __DIR__ . '/../../web/views/connexionAdministrateur.php';
    }

    public static function afficherPreference() {
        include __DIR__ . '/../../web/views/preference.php';
    }

    public static function afficherErreur($message) {
        echo "Erreur: " . $message;
    }

    public static function afficherEtudiant() {
        include __DIR__ . '/../../web/views/connexionEtudiant.php';
    }

    public static function afficherEcole() {
        include __DIR__ . '/../../web/views/connexionEcole.php';
    }

    public function connecter() {
        // TO DO
    }


}

