<?php
/** @var Etudiant $etudiant */

use App\GenerateurAvis\Modele\DataObject\Etudiant;
use App\GenerateurAvis\Modele\Repository\EtudiantRepository;

$idEtudiant = $etudiant->getIdEtudiant();
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
