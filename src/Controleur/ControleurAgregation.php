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
        // Kiểm tra người dùng đã đăng nhập chưa
        if (!ConnexionUtilisateur::estConnecte()) {
            self::redirectionVersURL("warning", "Veuillez vous connecter d'abord", "afficherPreference&controleur=Connexion");
            return;
        }

        // Kiểm tra các tham số từ form
        if (!isset($_GET["nom"]) || !isset($_GET["matieres"]) || !isset($_GET["coefficients"])) {
            self::redirectionVersURL("error", "Un paramètre est manquant", "afficherCreerAgregation&controleur=agregation");
            return;
        }

        // Lấy tên của agrégation
        $nomAgregation = $_GET["nom"];
        // Lấy các môn học và hệ số từ form
        $matieres = $_GET["matieres"];
        $coefficients = $_GET["coefficients"];

        // Tạo đối tượng Agrégation
        $agregation = new Agregation($nomAgregation, "", ConnexionUtilisateur::getLoginUtilisateurConnecte());

        // Lưu agrégation vào cơ sở dữ liệu
        $agregationRepository = new AgregationRepository();
        $agregationId = $agregationRepository->ajouterAgregation($agregation);

        if (!$agregationId) {
            self::redirectionVersURL("error", "L'agrégation n'a pas pu être créée", "afficherCreerAgregation&controleur=agregation");
            return;
        }

        // Duyệt qua các môn học và hệ số để thêm vào cơ sở dữ liệu
        foreach ($matieres as $index => $matiereId) {
            $coefficient = $coefficients[$index];

            // Tạo đối tượng Matiere
            $matiere = new Matiere($matiereId, $coefficient);

            // Thêm môn học vào agrégation
            $matiereRepository = new AgregationMatiereRepository();
            $res = $matiereRepository->ajouterMatierePourAgregation($agregationId, $matiere);

            if (!$res) {
                self::redirectionVersURL("error", "Erreur lors de l'ajout des matières à l'agrégation", "afficherCreerAgregation&controleur=agregation");
                return;
            }
        }

        // Chuyển hướng sau khi thành công
        self::redirectionVersURL("success", "L'agrégation a bien été créée", "afficherListe&controleur=agregation");
    }



}