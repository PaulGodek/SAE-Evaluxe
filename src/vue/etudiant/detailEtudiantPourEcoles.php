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
$nomHTML = htmlspecialchars($informationsPersonelles["nom"]);
$prenomHTML = htmlspecialchars($informationsPersonelles["prenom"]);
if ($informationsPersonelles) {
    echo '<div class="etudiant-details">';
    echo "<h2>Détails de l'étudiant</h2>";
    echo "<p>Nom: {$nomHTML}</p>";
    echo "<p>Prénom: {$prenomHTML}</p>";

    foreach ($informationsParSemestre as $table => $details) {
        $moyenneHTML = htmlspecialchars($details["moyenne"]);
        $parcoursHTML = htmlspecialchars($details["parcours"]);

        preg_match('/semestre(\d+)_\d+/', $table, $matches);
        $semesterNumber = htmlspecialchars($matches[1]);
        echo '<div class="semester-details">';
        echo "<h3>Semestre: {$semesterNumber} </h3>";
        echo "<p>Absences non justifiées: " . htmlspecialchars(max(0, $details['abs'] - $details['just1'])) . "</p>";
        echo "<p>Moyenne: {$moyenneHTML}</p>";
        if ($details['parcours'] !== '-') {
            echo "<p>Parcours: {$parcoursHTML}</p>";
        }
        foreach ($details['ue_details'] as $ueDetail) {
            if ($ueDetail['moy'] !== 'N/A') {
                $ueHTML = htmlspecialchars($ueDetail["ue"]);
                $moyenneUeHTML = htmlspecialchars($ueDetail["moy"]);

                echo '<div class="ue-detail">';
                echo "<h4>{$ueHTML}</h4>";
                echo "<p>Moyenne: " . ($moyenneUeHTML) . "</p>";
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
