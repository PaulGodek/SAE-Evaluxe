<?php

namespace App\GenerateurAvis\Controleur;

use App\GenerateurAvis\Modele\Repository\UtilisateurRepository;

class ControleurConnexion
{
    public static function afficherAdministrateur()
    {
        include __DIR__ . '/../../web/views/connexion/connexionAdministrateur.php';
    }

    public static function afficherPreference()
    {
        include __DIR__ . '/../../web/views/connexion/preference.php';
    }

    public static function afficherErreur($message)
    {
        echo "Erreur: " . $message;
    }

    public static function afficherEtudiant()
    {
        include __DIR__ . '/../../web/views/connexion/connexionEtudiant.php';
    }

    public static function afficherEcole()
    {
        include __DIR__ . '/../../web/views/connexion/connexionEcole.php';
    }

    public function connecter()
    {
        $role = $_GET['controleur'] ?? 'utilisateur';

        $username = $_GET['username'];
        $password = $_GET['password'];

        $user = UtilisateurRepository::recupererUtilisateurParLogin($username);
        if ($user && password_verify($password, $user->getPasswordHash())) {
            if ($user->getType() === $role) {
                session_start();
                $_SESSION['username'] = $username;
                $_SESSION['role'] = $role;

                if ($role == 'etudiant') {
                    ControleurEtudiant::afficherDetail();
                } else {
                    ControleurEtudiant::afficherListe();
                }
            } else {
                self::afficherErreur("Role incorrect");
            }

        } else {
            self::afficherErreur("Login ou mot de passe incorrect");
        }
    }


}

