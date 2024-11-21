<?php

namespace App\GenerateurAvis\Controleur;

use App\GenerateurAvis\Lib\ConnexionUtilisateur;
use App\GenerateurAvis\Lib\MessageFlash;
use App\GenerateurAvis\Modele\DataObject\Agregation;
use App\GenerateurAvis\Modele\DataObject\Matiere;
use App\GenerateurAvis\Modele\Repository\AgregationMatiereRepository;
use App\GenerateurAvis\Modele\Repository\AgregationRepository;
use App\GenerateurAvis\Modele\Repository\RessourceRepository;

class ControleurAgregation extends ControleurGenerique
{
    public static function afficherListe(): void {
        if (!ConnexionUtilisateur::estConnecte()) {
            self::redirectionVersURL("warning", "Veuillez vous connecter d'abord", "afficherPreference&controleur=Connexion");
            return;
        }
        $agre = (new AgregationRepository())->recuperer();
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
        self::afficherVue('vueGenerale.php', ["cheminCorpsVue" => "agregation/creerAgregation.php", 'ressources' => $ressources]);
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

            // Tạo đối tượng Matiere
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
        // Kiểm tra kết nối của người dùng, nếu không đăng nhập thì chuyển hướng
        if (!ConnexionUtilisateur::estConnecte()) {
            self::redirectionVersURL("warning", "Veuillez vous connecter d'abord", "afficherPreference&controleur=Connexion");
            return;
        }

        // Kiểm tra nếu tham số 'id' có trong URL
        if (!isset($_GET['id'])) {
            self::redirectionVersURL("error", "L'ID de l'agrégation est manquant", "afficherListeAgregations&controleur=agregation");
            return;
        }

        // Lấy giá trị id từ tham số GET
        $idAgregation = (int)$_GET['id'];

        // Lấy chi tiết agrégation từ repository
        $agregationRepository = new AgregationRepository();
        $agregationDetails = $agregationRepository->getAgregationDetailsById($idAgregation);

        // Kiểm tra nếu không tìm thấy agrégation
        if (empty($agregationDetails)) {
            self::redirectionVersURL("error", "Agrégation non trouvée", "afficherListeAgregations&controleur=agregation");
            return;
        }

        // Hiển thị view với dữ liệu chi tiết
        self::afficherVue('vueGenerale.php', [
            "cheminCorpsVue" => "agregation/detailAgregation.php",
            "agregationDetails" => $agregationDetails
        ]);
    }




}