<?php

namespace App\GenerateurAvis\Modele\HTTP;

class Cookie
{
    public static function enregistrer(string $cle, mixed $valeur, ?int $dureeExpiration = null): void
    {
        $valeurS = serialize($valeur);

        if ($dureeExpiration === null) {
            setcookie($cle, $valeurS, 0, '/'); // Durée de vie par défaut
        } else {
            setcookie($cle, $valeurS, time() + $dureeExpiration, '/'); // Durée de vie spécifiée
        }
    }

    public static function lire(string $cle): mixed
    {
        if (self::contient($cle)) {
            return unserialize($_COOKIE[$cle]);
        }
        return null; // Retourne null si le cookie n'existe pas
    }

    public static function contient(string $cle): bool
    {
        return isset($_COOKIE[$cle]);
    }

    public static function supprimer(string $cle): void
    {
        if (self::contient($cle)) {
            setcookie($cle, '', time() - 3600, '/'); // Expires the cookie
            unset($_COOKIE[$cle]); // Supprime la variable de tableau
        }
    }
}