<?php

namespace App\GenerateurAvis\Controleur;

use App\GenerateurAvis\Lib\ConnexionUtilisateur;
use App\GenerateurAvis\Modele\Repository\NoteRepository;

class ControleurNote extends ControleurGenerique
{
    public static function afficherChartParcour() {
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


}