<?php

namespace App\GenerateurAvis\Modele\HTTP;

class Cookie
{
    public static function enregistrer(string $cle, mixed $valeur, ?int $dureeExpiration = null): void
    {
        $valeurS = serialize($valeur);

        if ($dureeExpiration === null) {
            setcookie($cle, $valeurS, 0, '/');
        } else {
            setcookie($cle, $valeurS, time() + $dureeExpiration, '/');
        }
    }

    public static function lire(string $cle): mixed
    {
        if (self::contient($cle)) {
            return unserialize($_COOKIE[$cle]);
        }
        return null;
    }

    public static function contient(string $cle): bool
    {
        return isset($_COOKIE[$cle]);
    }

    public static function supprimer(string $cle): void
    {
        if (self::contient($cle)) {
            setcookie($cle, '', time() - 3600, '/');
            unset($_COOKIE[$cle]);
        }
    }
}