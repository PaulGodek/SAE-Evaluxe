<?php

namespace App\GenerateurAvis\Lib;

use App\GenerateurAvis\Modele\HTTP\Session;
use App\GenerateurAvis\Modele\Repository\UtilisateurRepository;

class ConnexionUtilisateur
{
    private static string $cleConnexion = "_utilisateurConnecte";

    public static function connecter(string $loginUtilisateur): void
    {
        $session = Session::getInstance();
        $session->enregistrer(self::$cleConnexion, $loginUtilisateur);
    }

    public static function estConnecte(): bool
    {
        $session = Session::getInstance();
        return $session->contient(self::$cleConnexion);
    }

    public static function deconnecter(): void
    {
        $session = Session::getInstance();
        $session->supprimer(self::$cleConnexion);
    }

    public static function getLoginUtilisateurConnecte(): ?string
    {
        $session = Session::getInstance();
        return $session->lire(self::$cleConnexion) ?? null;
    }

    public static function estUtilisateur($login): bool
    {
        $session = Session::getInstance();
        $loginUtilisateurConnecte = $session->lire(self::$cleConnexion) ?? null;

        return $loginUtilisateurConnecte === $login;
    }

    public static function estAdministrateur(): bool
    {
        if (!self::estConnecte()) {
            return false;
        }
        $login = self::getLoginUtilisateurConnecte();
        $utilisateur = (new UtilisateurRepository())->recupererParClePrimaire($login);
        return $utilisateur !== null && ($utilisateur->getType()==='administrateur');
    }
}