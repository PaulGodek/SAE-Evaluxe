<?php
/** @var Etudiant $etudiant */

use App\GenerateurAvis\Lib\ConnexionUtilisateur;
use App\GenerateurAvis\Modele\DataObject\Ecole;
use App\GenerateurAvis\Modele\DataObject\Etudiant;
use App\GenerateurAvis\Modele\DataObject\Professeur;
use App\GenerateurAvis\Modele\Repository\EtudiantRepository;
use App\GenerateurAvis\Modele\Repository\UtilisateurRepository;

$idEtudiant = $etudiant->getIdEtudiant();
/*
$utilisateur = (new UtilisateurRepository())->recupererParClePrimaire(ConnexionUtilisateur::getLoginUtilisateurConnecte());
if ($utilisateur instanceof Ecole) {
    $type = "universite";
    echo "<p>Type: {$type}</p>";
} else if ($utilisateur instanceof Professeur) {
    $type = "professeur";
    echo "<p>Type: {$type}</p>";
} else if ($utilisateur instanceof Etudiant) {
    $type = "etudiant";
    echo "<p>Type: {$type}</p>";
} else {
    $type = "administrateur";
    echo "<p>Type: {$type}</p>";
}*/
$estEcole = ConnexionUtilisateur::estEcole();
if($estEcole){
    $type = "universite";
} else {
    $type = "autre";
}
/*echo "<p>Ecole: {$estEcole}</p>";

var_dump($utilisateur);

if (get_class($utilisateur) === Etudiant::class) {
    $type = "etudiant";
}
echo "<p>Type: {$type}</p>";
*/
switch ($type) {
    case "universite":
        $result = EtudiantRepository::recupererDetailsEtudiantParId($idEtudiant);

        $etudiantInfo = $result['info'];
        $etudiantDetailsPerSemester = $result['details'];

        if ($etudiantInfo) {
            echo '<div class="etudiant-details">';
            echo "<h2>Détails de l'étudiant</h2>";
            echo "<p>Nom: {$etudiantInfo['nom']}</p>";
            echo "<p>Prénom: {$etudiantInfo['prenom']}</p>";

            foreach ($etudiantDetailsPerSemester as $table => $details) {
                preg_match('/semestre(\d+)_\d+/', $table, $matches);
                $semesterNumber = $matches[1];
                echo '<div class="semester-details">';
                echo "<h3>Semestre: {$semesterNumber} </h3>";
                echo "<p>Absences non justifiées: " . max(0, $details['abs'] - $details['just1']) . "</p>";
                echo "<p>Moyenne: {$details['moyenne']}</p>";
                echo "<p>Parcours: {$details['parcours']}</p>";

                foreach ($details['ue_details'] as $ueDetail) {
                    echo '<div class="ue-detail">';
                    echo "<h4>{$ueDetail['ue']}</h4>";
                    echo "<p>Moyenne: " . ($ueDetail['moy'] !== 'N/A' ? $ueDetail['moy'] : "N/A") . "</p>";
                    echo '</div>';
                }

                echo '</div>';
            }

            echo '</div>';
        } else {
            echo '<p>Aucun détail n\'a été trouvé pour l\'étudiant avec ID ' . htmlspecialchars($idEtudiant) . '.</p>';
        }
        break;
    default:
        $result = EtudiantRepository::recupererTousLesDetailsEtudiantParId($idEtudiant);

        $etudiantInfo = $result['info'];
        $etudiantDetailsPerSemester = $result['details'];

        if ($etudiantInfo) {
            echo '<div class="etudiant-details">';
            echo "<h2>Détails de l'étudiant</h2>";
            echo "<p>Nom: {$etudiantInfo['nom']}</p>";
            echo "<p>Prénom: {$etudiantInfo['prenom']}</p>";
            echo "<p>Id étudiant: {$etudiantInfo['etudid']}</p>";
            echo "<p>Code nip: {$etudiantInfo['codenip']}</p>";
            echo "<p>Civilité: {$etudiantInfo['civ']}</p>";

            foreach ($etudiantDetailsPerSemester as $table => $details) {
                preg_match('/semestre(\d+)_\d+/', $table, $matches);
                $semesterNumber = $matches[1] ?? 'Inconnu';
                echo '<div class="semester-details">';
                echo "<h3>Semestre: {$semesterNumber}</h3>";

                foreach ($details as $column => $value) {
                    if ($column == 'abs' && isset($details['just1'])) {
                        echo "<p>Absences non justifiées: " . max(0, $value - $details['just1']) . "</p>";
                    } elseif ($column == 'just1') {
                        continue;
                    } else {
                        echo "<p>" . ucfirst(str_replace('_', ' ', $column)) . ": " . ($value !== '' ? $value : 'N/A') . "</p>";
                    }
                }

                echo '</div>';
            }

            echo '</div>';
        } else {
            echo '<p>Aucun détail n\'a été trouvé pour l\'étudiant avec ID ' . htmlspecialchars($idEtudiant) . '.</p>';
        }
        break;
}




