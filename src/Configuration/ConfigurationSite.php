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
        if ($note < $array[0]["barrier1"]) {
            return "R";
        }
        if ($note < $array[0]["barrier2"]) {
            return "F";
        }
        return "TF";
    }

    public static function setBarrier1(float $newBarrier) : void {
        $jsonFile = file_get_contents(__DIR__ . "/configNotes.json");
        $array = json_decode($jsonFile, true);

        $array[0]["barrier1"] = $newBarrier;
        $jsonFile = json_encode($array);
        file_put_contents(__DIR__ . "/configNotes.json", $jsonFile);
    }

    public static function setBarrier2(int $newBarrier) : void {
        $jsonFile = file_get_contents(__DIR__ . "/configNotes.json");
        $array = json_decode($jsonFile, true);

        $array[0]["barrier2"] = $newBarrier;
        $jsonFile = json_encode($array);
        file_put_contents(__DIR__ . "/configNotes.json", $jsonFile);
    }

    public static function addAvis(string $avis) : void {
        $jsonFile = file_get_contents(__DIR__ . "/configNotes.json");
        $array = json_decode($jsonFile, true);

        $array[1][$avis] = $array[1][$avis] + 1;
        $jsonFile = json_encode($array);
        file_put_contents(__DIR__ . "/configNotes.json", $jsonFile);
    }

    public static function resetAvis() : void {
        $jsonFile = file_get_contents(__DIR__ . "/configNotes.json");
        $array = json_decode($jsonFile, true);

        $array[1]["ingenieurR"] = 0;
        $array[1]["ingenieurF"] = 0;
        $array[1]["ingenieurTF"] = 0;
        $array[1]["managementR"] = 0;
        $array[1]["managementF"] = 0;
        $array[1]["managementTF"] = 0;
        $jsonFile = json_encode($array);
        file_put_contents(__DIR__ . "/configNotes.json", $jsonFile);
    }

    public static function getIngenieurR() : int {
        $jsonFile = file_get_contents(__DIR__ . "/configNotes.json");
        $array = json_decode($jsonFile, true);
        return $array[1]["ingenieurR"];
    }

    public static function getIngenieurF() : int {
        $jsonFile = file_get_contents(__DIR__ . "/configNotes.json");
        $array = json_decode($jsonFile, true);
        return $array[1]["ingenieurF"];
    }

    public static function getIngenieurTF() : int {
        $jsonFile = file_get_contents(__DIR__ . "/configNotes.json");
        $array = json_decode($jsonFile, true);
        return $array[1]["ingenieurTF"];
    }

    public static function getManagementR() : int {
        $jsonFile = file_get_contents(__DIR__ . "/configNotes.json");
        $array = json_decode($jsonFile, true);
        return $array[1]["managementR"];
    }

    public static function getManagementF() : int {
        $jsonFile = file_get_contents(__DIR__ . "/configNotes.json");
        $array = json_decode($jsonFile, true);
        return $array[1]["managementF"];
    }

    public static function getManagementTF() : int {
        $jsonFile = file_get_contents(__DIR__ . "/configNotes.json");
        $array = json_decode($jsonFile, true);
        return $array[1]["managementTF"];
    }
}