<?php

namespace App\GenerateurAvis\Configuration;

class ConfigurationSite
{
    private static int $dureeExpirationSession = 1800;

    public static function getDureeExpirationSession(): int
    {
        return self::$dureeExpirationSession;
    }
}