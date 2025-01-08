<?php

namespace App\GenerateurAvis\Configuration;

class ConfigurationSite
{
    private static int $dureeExpirationSession = 1800;

    public static function getDureeExpirationSession(): int
    {
        return self::$dureeExpirationSession;
    }

    public static function determinerPassageNotes(float $note) : string {
        $jsonFile = file_get_contents( __DIR__ . "/configNotes.json");
        $array = json_decode($jsonFile, true);
        if ($note < $array["barrier1"]) {
            return "R";
        }
        if ($note < $array["barrier2"]) {
            return "F";
        }
        return "TF";
    }

    public static function setBarrier1(float $newBarrier) : void {
        $jsonFile = file_get_contents(__DIR__ . "/configNotes.json");
        $array = json_decode($jsonFile, true);

        $array["barrier1"] = $newBarrier;
        $jsonFile = json_encode($array);
        file_put_contents(__DIR__ . "/configNotes.json", $jsonFile);
    }

    public static function setBarrier2(int $newBarrier) : void {
        $jsonFile = file_get_contents(__DIR__ . "/configNotes.json");
        $array = json_decode($jsonFile, true);

        $array["barrier2"] = $newBarrier;
        $jsonFile = json_encode($array);
        file_put_contents(__DIR__ . "/configNotes.json", $jsonFile);
    }
}