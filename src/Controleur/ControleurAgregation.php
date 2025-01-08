<?php

namespace App\GenerateurAvis\Controleur;

use App\GenerateurAvis\Lib\ConnexionUtilisateur;
use App\GenerateurAvis\Lib\MessageFlash;
use App\GenerateurAvis\Modele\DataObject\Agregation;
use App\GenerateurAvis\Modele\DataObject\Matiere;
use App\GenerateurAvis\Modele\Repository\AgregationMatiereRepository;
use App\GenerateurAvis\Modele\Repository\AgregationRepository;
use App\GenerateurAvis\Modele\Repository\EcoleRepository;
use App\GenerateurAvis\Modele\Repository\RessourceRepository;

class ControleurAgregation extends ControleurGenerique
{
    public static function afficherListe(): void {
        if (!ConnexionUtilisateur::estConnecte()) {
            self::redirectionVersURL("warning", "Veuillez vous connecter d'abord", "afficherPreference&controleur=Connexion");
            return;
        }

        $loginActuel = ConnexionUtilisateur::getLoginUtilisateurConnecte();
        $agre = array_filter(
            (new AgregationRepository())->recuperer(),
            fn($agregation) => $agregation->getLogin() === $loginActuel
        );

        self::afficherVue('vueGenerale.php', [
            "titre" => "Liste des agregations",
            "cheminCorpsVue" => "agregation/listeAgregation.php",
            "agre" => $agre
        ]);
    }
    public static function afficherCreerAgregation(): void {
        if (!ConnexionUtilisateur::estConnecte()) {
            self::redirectionVersURL("warning", "Veuillez vous connecter d'abord", "afficherPreference&controleur=Connexion");
            return;
        }
        $ressourceRepository = new RessourceRepository();
        $ressources = $ressourceRepository->recuperer();
        self::afficherVue('vueGenerale.php', ["titre" => "Créer une agrégation", "cheminCorpsVue" => "agregation/creerAgregation.php", 'ressources' => $ressources]);
    }

    public static function creerAgregationDepuisFormulaire(): void
    {
        if (!ConnexionUtilisateur::estConnecte()) {
            self::redirectionVersURL("warning", "Veuillez vous connecter d'abord", "afficherPreference&controleur=Connexion");
            return;
        }

        if (!isset($_GET["nom"]) || !isset($_GET["matieres"]) || !isset($_GET["coefficients"])) {
            self::redirectionVersURL("error", "Un paramètre est manquant", "afficherCreerAgregation&controleur=agregation");
            return;
        }
        $nomAgregation = $_GET["nom"];
        $matieres = $_GET["matieres"];
        $coefficients = $_GET["coefficients"];

        $agregation = new Agregation($nomAgregation, "", ConnexionUtilisateur::getLoginUtilisateurConnecte());

        $agregationRepository = new AgregationRepository();
        $agregationId = $agregationRepository->ajouterAgregation($agregation);

        if (!$agregationId) {
            self::redirectionVersURL("error", "L'agrégation n'a pas pu être créée", "afficherCreerAgregation&controleur=agregation");
            return;
        }

        foreach ($matieres as $index => $matiereId) {
            $coefficient = $coefficients[$index];

            $matiere = new Matiere($matiereId, $coefficient);

            $matiereRepository = new AgregationMatiereRepository();
            $res = $matiereRepository->ajouterMatierePourAgregation($agregationId, $matiere);

            if (!$res) {
                self::redirectionVersURL("error", "Erreur lors de l'ajout des matières à l'agrégation", "afficherCreerAgregation&controleur=agregation");
                return;
            }
        }

        self::redirectionVersURL("success", "L'agrégation a bien été créée", "afficherListe&controleur=agregation");
    }

    public static function afficherDetailAgregation(): void {
        if (!ConnexionUtilisateur::estConnecte()) {
            self::redirectionVersURL("warning", "Veuillez vous connecter d'abord", "afficherPreference&controleur=Connexion");
            return;
        }

        if (!isset($_GET['id'])) {
            self::redirectionVersURL("error", "L'ID de l'agrégation est manquant", "afficherListeAgregations&controleur=agregation");
            return;
        }

        $idAgregation = (int)$_GET['id'];
        $agregationRepository = new AgregationRepository();
        $agregationDetails = $agregationRepository->getAgregationDetailsById($idAgregation);

        if (empty($agregationDetails)) {
            self::redirectionVersURL("error", "Agrégation non trouvée", "afficherListeAgregations&controleur=agregation");
            return;
        }

        self::afficherVue('vueGenerale.php', [
            "titre" => "Détails de l'agrégation",
            "cheminCorpsVue" => "agregation/detailAgregation.php",
            "agregationDetails" => $agregationDetails
        ]);
    }

    public static function supprimerAgregation(): void
    {
        if (!ConnexionUtilisateur::estConnecte()) {
            self::redirectionVersURL("warning", "Veuillez vous connecter d'abord", "afficherPreference&controleur=Connexion");
            return;
        }
        $id = $_GET["id"];
        (new AgregationRepository())->supprimer($id);
        self::redirectionVersURL("success","L'agrégation a bien été supprimée.", "afficherListe&controleur=agregation");
    }


    public static function modifierAgregationDepuisFormulaire(): void
    {
        if (!ConnexionUtilisateur::estConnecte()) {
            self::redirectionVersURL("warning", "Veuillez vous connecter d'abord", "afficherPreference&controleur=Connexion");
            return;
        }

        if (!isset($_GET["nom"]) || !isset($_GET["matieres"]) || !isset($_GET["coefficients"])) {
            self::redirectionVersURL("error", "Un paramètre est manquant", "afficherModifierAgregation&controleur=agregation");
            return;
        }

        $nomAgregation = $_GET["nom"];
        $matieres = $_GET["matieres"];
        $coefficients = $_GET["coefficients"];

        $agregation = new Agregation($nomAgregation, "", ConnexionUtilisateur::getLoginUtilisateurConnecte());

        $agregationRepository = new AgregationRepository();

        $idAgregation = $_GET['id_agregation'];
        $agregationRepository->supprimer($idAgregation);

        $agregationId = $agregationRepository->ajouterAgregation($agregation);

        if (!$agregationId) {
            self::redirectionVersURL("error", "L'agrégation n'a pas pu être créée", "afficherModifierAgregation&controleur=agregation");
            return;
        }

        $matiereRepository = new AgregationMatiereRepository();
        foreach ($matieres as $index => $matiereId) {
            $coefficient = $coefficients[$index];

            $matiere = new Matiere($matiereId, $coefficient);
            $res = $matiereRepository->ajouterMatierePourAgregation($agregationId, $matiere);

            if (!$res) {
                self::redirectionVersURL("error", "Erreur lors de l'ajout des matières à l'agrégation", "afficherModifierAgregation&controleur=agregation");
                return;
            }
        }

        self::redirectionVersURL("success", "L'agrégation a bien été modifié", "afficherListe&controleur=agregation");
    }

    public static function afficherFormulaireMiseAJour(): void
    {
        if (!ConnexionUtilisateur::estConnecte()) {
            self::redirectionVersURL("warning", "Veuillez vous connecter d'abord", "afficherPreference&controleur=Connexion");
            return;
        }

        $idAgregation = $_GET['id'];

        $agregationRepository = new AgregationRepository();
        $agregation = $agregationRepository->recupererParClePrimaire($idAgregation);

        $matiereRepository = new AgregationMatiereRepository();
        $matiereCoefficients = $matiereRepository->recupererParAgregation($idAgregation);

        $ressourceRepository = new RessourceRepository();
        $ressources = $ressourceRepository->recuperer();

        self::afficherVue('vueGenerale.php', [
            "idAgregation" => $idAgregation,
            "agregation" => $agregation,
            "matiereCoefficients" => $matiereCoefficients,
            "ressources" => $ressources,
            "titre" => "Formulaire de mise à jour d'une agrégation",
            "cheminCorpsVue" => "agregation/modifierAgregation.php"
        ]);
    }


}