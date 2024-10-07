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

    public static function connecter()
    {
        $role = $_GET['type'] ?? 'utilisateur';

        $login = $_GET['login'];
        $password = $_GET['password'];

        $user = UtilisateurRepository::recupererUtilisateurParLogin($login);
        if ($user && $password == $user->getPasswordHash()) {
            if ($user->getType() == $role) {
                session_start();
                $_SESSION['login'] = $login;
                $_SESSION['type'] = $role;

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

