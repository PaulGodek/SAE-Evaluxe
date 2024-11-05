<?php

namespace App\GenerateurAvis\Modele\HTTP;

use App\GenerateurAvis\Configuration\ConfigurationSite;
use Exception;

class Session
{
    private static ?Session $instance = null;

    /**
     * @throws Exception
     */
    private function __construct()
    {
        if (session_start() === false) {
            throw new Exception("La session n'a pas réussi à démarrer.");
        }
        $this->verifierDerniereActivite();
    }

    public static function getInstance(): Session
    {
        if (is_null(Session::$instance)) {
            Session::$instance = new Session();
        }
        return Session::$instance;
    }

    private function verifierDerniereActivite(): void
    {
        $dureeExpiration = ConfigurationSite::getDureeExpirationSession();
        if (isset($_SESSION['derniere_activite']) && (time() - $_SESSION['derniere_activite']) > $dureeExpiration) {
            $this->detruire();
            return;
        }
        $_SESSION['derniere_activite'] = time();
    }

    public function contient($nom): bool
    {
        return isset($_SESSION[$nom]);
    }

    public function enregistrer(string $nom, mixed $valeur): void
    {
        $_SESSION[$nom] = $valeur;
    }

    public function lire(string $nom): mixed
    {
        return $this->contient($nom) ? $_SESSION[$nom] : null;
    }

    public function supprimer($nom): void
    {
        if ($this->contient($nom)) {
            unset($_SESSION[$nom]);
        }
    }

    public function detruire(): void
    {
        session_unset();     // unset $_SESSION variable for the run-time
        session_destroy();   // destroy session data in storage
        Cookie::supprimer(session_name()); // deletes the session cookie
        Session::$instance = null; // Reconstruire la session au prochain appel
    }
}