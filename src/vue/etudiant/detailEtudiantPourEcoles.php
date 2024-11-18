<?php
/** @var Etudiant $etudiant */
/** @var array $informationsPersonelles */
/** @var array $informationsParSemestre */
/** @var string $idEtudiant */

/** @var string|null $codeUnique */

use App\GenerateurAvis\Modele\DataObject\Etudiant;

?>

<link rel="stylesheet" type="text/css" href="../ressources/css/detailEtudiant.css">

<?php
if ($informationsPersonelles) {
    echo '<div class="etudiant-details">';
    echo "<h2>Détails de l'étudiant</h2>";
    echo "<p>Nom: {$informationsPersonelles['nom']}</p>";
    echo "<p>Prénom: {$informationsPersonelles['prenom']}</p>";

    foreach ($informationsParSemestre as $table => $details) {
        preg_match('/semestre(\d+)_\d+/', $table, $matches);
        $semesterNumber = $matches[1];
        echo '<div class="semester-details">';
        echo "<h3>Semestre: {$semesterNumber} </h3>";
        echo "<p>Absences non justifiées: " . max(0, $details['abs'] - $details['just1']) . "</p>";
        echo "<p>Moyenne: {$details['moyenne']}</p>";
        if ($details['parcours'] !== '-') {
            echo "<p>Parcours: {$details['parcours']}</p>";
        }
        foreach ($details['ue_details'] as $ueDetail) {
            if ($ueDetail['moy'] !== 'N/A') {
                echo '<div class="ue-detail">';
                echo "<h4>{$ueDetail['ue']}</h4>";
                echo "<p>Moyenne: " . ($ueDetail['moy']) . "</p>";
                echo '</div>';
            }
        }
        echo '</div>';
    }
    echo '</div>';
} else {
    echo '<p>Aucun détail n\'a été trouvé pour l\'étudiant avec ID ' . htmlspecialchars($idEtudiant) . '.</p>';
}
?>
<a href="controleurFrontal.php?action=afficherEcole&controleur=ecole" class="return-item">Retour</a>
