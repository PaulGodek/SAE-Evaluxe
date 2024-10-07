<?php
namespace App\GenerateurAvis\Controleur;

class ControleurConnexion {
    public static function afficherConnexionAdministrateur() {
        include __DIR__ . '/../vue/siteweb/connexion/connexionAdministrateur.php';
    }

    public static function afficherPreference() {
        include __DIR__ . '/../vue/siteweb/connexion/preference.php';
    }

    public static function afficherErreur($message) {
        echo "Erreur: " . $message;
    }

    public static function afficherConnexionEtudiant() {
        include __DIR__ . '/../vue/siteweb/connexion/connexionEtudiant.php';
    }

    public static function afficherConnexionEcole() {
        include __DIR__ . '/../vue/siteweb/connexion/connexionEcole.php';
    }

    public function connecter() {
        // TO DO
    }


}

