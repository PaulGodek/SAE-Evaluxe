<?php

namespace App\GenerateurAvis\Controleur;

use App\GenerateurAvis\Lib\ConnexionUtilisateur;
use App\GenerateurAvis\Modele\Repository\NoteRepository;

class ControleurNote extends ControleurGenerique
{
    public static function afficherChartParcour() {
        if (!ConnexionUtilisateur::estConnecte()) {
            self::redirectionVersURL("warning", "Veuillez vous connecter d'abord", "afficherPreference&controleur=Connexion");
            return;
        }

        if (!ConnexionUtilisateur::estAdministrateur()) {
            self::redirectionVersURL("error", "Vous n'avez pas de droit d'accès pour cette page", "afficherPreference&controleur=Connexion");
            return;
        }
        $parcourA = (new NoteRepository())->getNbEtudiantParcour('A');
        $parcourB = (new NoteRepository())->getNbEtudiantParcour('B');
        $parcourD = (new NoteRepository())->getNbEtudiantParcour('D');

        $dataPoints = [
            'Parcours A' => $parcourA,
            'Parcours B' => $parcourB,
            'Parcours D' => $parcourD,
        ];
        self::afficherVue('vueGenerale.php', ['dataPoints' => $dataPoints, 'titre' => 'Chart par parcours', "cheminCorpsVue" => "chart/chartParcour.php"]);
    }

    public static function afficherChartMoyenneUEParSemestre()
    {
        if (!ConnexionUtilisateur::estConnecte()) {
            self::redirectionVersURL("warning", "Veuillez vous connecter d'abord", "afficherPreference&controleur=Connexion");
            return;
        }

        if (!ConnexionUtilisateur::estAdministrateur()) {
            self::redirectionVersURL("error", "Vous n'avez pas de droit d'accès pour cette page", "afficherPreference&controleur=Connexion");
            return;
        }

        $semestres = [1, 2, 3, 4, 5];
        $noteRepository = new NoteRepository();

        $dataPoints = [];
        $UEs = ['UE1', 'UE2', 'UE3', 'UE4', 'UE5', 'UE6'];

        foreach ($UEs as $UE) {
            $data = [];
            foreach ($semestres as $semestre) {
                $moyenne = $noteRepository->getMoyenneUEParSemestre($UE, $semestre);
                $data[] = ['label' => "Semestre $semestre", 'y' => $moyenne];
            }
            $dataPoints[] = [
                'type' => 'column',
                'name' => $UE,
                'showInLegend' => true,
                'dataPoints' => $data
            ];
        }

        self::afficherVue('vueGenerale.php', [
            'dataPoints' => $dataPoints,
            'titre' => 'Moyenne des UEs pour tous les semestres',
            'cheminCorpsVue' => 'chart/chartMoyenneUEParSemestre.php',
        ]);
    }

    public static function afficherChartUEPourEtudiant()
    {
        if (!ConnexionUtilisateur::estConnecte()) {
            self::redirectionVersURL("warning", "Veuillez vous connecter d'abord", "afficherPreference&controleur=Connexion");
            return;
        }

        if (ConnexionUtilisateur::estEcole()) {
            self::redirectionVersURL("error", "Vous n'avez pas de droit d'accès pour cette page", "afficherPreference&controleur=Connexion");
            return;
        }
        if (!isset($_GET["code"])) {
            self::redirectionVersURL("error", "Le code unique n'est pas valide", "afficherPreference&controleur=Connexion");
            return;
        }
        $codeNip = $_GET['code'];
        $UEs = ['UE1', 'UE2', 'UE3', 'UE4', 'UE5', 'UE6'];
        $noteRepository = new NoteRepository();
        if (ConnexionUtilisateur::estAdministrateur()) {
            $semestres = $noteRepository->getAllSemestres();
        }
        else $semestres = $noteRepository->getSemestresPublic();
        $dataPoints = [];
        foreach ($UEs as $UE) {
            $data = [];
            foreach ($semestres as $semestre) {
                $moyenne = $noteRepository->getMoyenneUEParEtudiantParSemestre($codeNip, $UE, $semestre);
                $data[] = ['label' => "Semestre $semestre", 'y' => $moyenne];
            }
            $dataPoints[$UE] = $data;
        }
        self::afficherVue('vueGenerale.php', ['dataPoints' => $dataPoints, 'titre' => 'Progression des UEs pour l\'étudiant ' . $codeNip, 'cheminCorpsVue' => 'chart/chartUEPourEtudiant.php',]);
    }
}