<?php

namespace App\GenerateurAvis\Lib;

use App\GenerateurAvis\Modele\HTTP\Session;

/**
 * Classe MessageFlash gérant les messages flash enregistrés en session.
 */
class MessageFlash
{

    /**
     * @var string La clé associée aux messages flash en session.
     */
    private static string $cleFlash = "_messagesFlash";
    private static $supprimer;

    /**
     * Ajoute un message flash de type spécifié.
     *
     * @param string $type Le type du message flash ("success", "info", "warning" ou "danger").
     * @param string $message Le message à ajouter.
     */
    public static function ajouter(string $type, string $message): void
    {
        $array = array(
            $type => $message
        );
        $session = Session::getInstance();
        if ($session->contient(self::$cleFlash)) {
            $array = \App\GenerateurAvis\Lib\MessageFlash::lireMessages(self::$cleFlash);
            $array[$type] = $message;
        }
        $session->enregistrer(self::$cleFlash, $array);
    }

    /**
     * Vérifie si un message flash du type spécifié existe.
     *
     * @param string $type Le type du message flash.
     * @return bool True si un message flash du type spécifié existe, false sinon.
     */
    public static function contientMessage(string $type): bool
    {
        $session = Session::getInstance();
        if ($session->contient(self::$cleFlash)) {
            $array = $session->lire(self::$cleFlash);
            return isset($array[$type]);
        }
        return false;
    }

    /**
     * Lit les messages flash du type spécifié et les détruit.
     *
     * @param string $type Le type du message flash.
     * @return array Les messages flash du type spécifié.
     */
    public static function lireMessages(string $type): array
    {
        $session = Session::getInstance();
        $array = array();
        if ($session->contient(self::$cleFlash)) {
            $array = $session->lire(self::$cleFlash);
            $messages = array();
            foreach ($array as $typ => $message) {
                if ($typ == $type) {
                    $messages[] = $message;
                    unset($array[$typ]);
                }
            }
            $session->enregistrer(self::$cleFlash, $array);
            return $messages;
        }
        return $array;
    }

    /**
     * Lit tous les messages flash et les détruit.
     *
     * @return array Tous les messages flash enregistrés.
     */
    public static function lireTousMessages(): array
    {
        $session = Session::getInstance();
        if ($session->contient(self::$cleFlash)) {
            $array = $session->lire(self::$cleFlash);
            return $array;
        }
        return array();
    }
}