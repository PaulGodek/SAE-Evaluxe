<?php

namespace App\GenerateurAvis\Controleur;

use App\GenerateurAvis\Lib\ConnexionUtilisateur;
use App\GenerateurAvis\Lib\MessageFlash;
use App\GenerateurAvis\Modele\DataObject\Agregation;
use App\GenerateurAvis\Modele\Repository\AgregationRepository;

class ControleurAgregation extends ControleurGenerique
{
    public static function afficherListe(): void {
        if (!ConnexionUtilisateur::estConnecte()) {
            self::redirectionVersURL("warning", "Veuillez vous connecter d'abord", "afficherPreference&controleur=Connexion");
            return;
        }
        $agre = (new AgregationRepository())->recuperer();
        self::afficherVue('vueGenerale.php', ["titre" => "Liste des agregations","cheminCorpsVue" => "agregation/listeAgregation.php, agregations => $agre"]);
    }
    public static function afficherCreerAgregation(): void {
        if (!ConnexionUtilisateur::estConnecte()) {
            self::redirectionVersURL("warning", "Veuillez vous connecter d'abord", "afficherPreference&controleur=Connexion");
            return;
        }
        self::afficherVue('vueGenerale.php', ["cheminCorpsVue" => "agregation/creerAgregation"]);
    }

    public static function creerDepuisFormulaire(): void
    {
        if(!ConnexionUtilisateur::estConnecte()) {
            self::redirectionVersURL("warning", "Veuillez vous connecter d'abord", "afficherPreference&controleur=Connexion");
            return;
        }

        if (!isset($_GET["nom"])) {
            self::redirectionVersURL("error", "Un paramètre est manquant", "creerAgregation&controleur=agregation");
        }

        $agregation = new Agregation( $_GET["nom"], "", ConnexionUtilisateur::getLoginUtilisateurConnecte());

        $res = (new AgregationRepository())->ajouterAgregation($agregation);
        if (!$res) {
            self::redirectionVersURL("error", "L'agrégation n'a pas pu être créée","creerAgregation&controleur=agregation");
        }

        self::redirectionVersURL("success", "L'agrégation a bien été créée","afficherListe&controleur=agregation");
    }

}