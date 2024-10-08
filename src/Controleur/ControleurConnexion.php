<?php

namespace App\GenerateurAvis\Controleur;

use App\GenerateurAvis\Modele\Repository\UtilisateurRepository;

class ControleurConnexion
{
    public static function afficherAdministrateur()
    {
        include __DIR__ . '/../../vue/siteweb/connexion/connexionAdministrateur.php';
    }

    public static function afficherPreference()
    {
        include __DIR__ . '/../vue/siteweb/connexion/preference.php';
    }

    public static function afficherErreur($message)
    {
        echo "Erreur: " . $message;
    }

    public static function afficherConnexionEtudiant() {
        include __DIR__ . '/../vue/siteweb/connexion/connexionEtudiant.php';
    }

    public static function afficherConnexionEcole() {
        include __DIR__ . '/../vue/siteweb/connexion/connexionEcole.php';
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

                switch ($role) {
                    case 'etudiant':
                        ControleurEtudiant::afficherDetail();
                        break;
                    case 'administrateur':
                        ControleurUtilisateur::afficherListe();
                        break;
                    case 'ecole':
                        ControleurEtudiant::afficherListe();
                        break;
                    default:
                        self::afficherErreur("Rôle non supporté");
                        break;
                }
            } else {
                self::afficherErreur("Rôle incorrect");
            }

        } else {
            self::afficherErreur("Login ou mot de passe incorrect");
        }
    }


}

