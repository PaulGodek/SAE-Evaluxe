<?php
/** @var Etudiant $etudiant */
/** @var array $informationsPersonelles */
/** @var array $informationsParSemestre */
/** @var string $idEtudiant */

/** @var string|null $codeUnique */

use App\GenerateurAvis\Modele\DataObject\Etudiant;

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

<!-- CSS embedded within the PHP file to apply to only this page -->
<style>
    /* Page Specific Styles */
    .etudiant-details {
        background-color: #fff;
        padding: 20px;
        border-radius: 8px;
        box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        margin-bottom: 30px;
    }

    .etudiant-details h2 {
        text-align: center;
        color: #2c3e50;
    }

    .semester-details {
        background-color: #f4f4f9;
        margin-top: 20px;
        padding: 15px;
        border-radius: 5px;
    }

    .semester-details h3 {
        color: #2980b9;
    }

    .ue-detail {
        margin-top: 10px;
    }

    .ue-detail h4 {
        color: #3498db;
    }

    .ue-detail p {
        color: #555;
    }

    .etudiant-details p {
        font-size: 16px;
        line-height: 1.5;
    }

    .etudiant-details p strong {
        color: #2980b9;
    }

    table {
        width: 100%;
        border-collapse: collapse;
        margin-top: 20px;
    }

    th, td {
        padding: 12px 15px;
        text-align: left;
        border: 1px solid #ddd;
    }

    th {
        background-color: #3498db;
        color: white;
    }

    td {
        background-color: #f9f9f9;
    }

    tr:hover {
        background-color: #f1f1f1;
    }
</style>


