<?php
namespace App\GenerateurAvis\Controleur;

class ControleurConnexion {
    public static function afficherAdministrateur() {
        include __DIR__ . '/../../web/views/connexion/connexionAdministrateur.php';
    }

    public static function afficherPreference() {
        include __DIR__ . '/../../web/views/connexion/preference.php';
    }

    public static function afficherErreur($message) {
        echo "Erreur: " . $message;
    }

    public static function afficherEtudiant() {
        include __DIR__ . '/../../web/views/connexion/connexionEtudiant.php';
    }

    public static function afficherEcole() {
        include __DIR__ . '/../../web/views/connexion/connexionEcole.php';
    }

    public function connecter() {
        // TO DO
    }


}

