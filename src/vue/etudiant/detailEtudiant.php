<?php
/** @var array $informationsPersonelles */
/** @var array $informationsParSemestre */
/** @var Etudiant $etudiant */
/** @var string|null $codeUnique */
/** @var bool $estEcole */

use App\GenerateurAvis\Lib\ConnexionUtilisateur;
use App\GenerateurAvis\Modele\DataObject\Etudiant;
use App\GenerateurAvis\Modele\Repository\EtudiantRepository;
echo "<h2>Détails de l'étudiant</h2>";

if (isset($codeUnique)) {
    echo "<h3>Code unique: {$codeUnique}</h3>";
}

echo "<p>Nom: {$informationsPersonelles['nom']}</p>";
echo "<p>Prénom: {$informationsPersonelles['prenom']}</p>";
echo "<p>Id étudiant: {$informationsPersonelles['etudid']}</p>";
echo "<p>Code nip: {$informationsPersonelles['codenip']}</p>";
echo "<p>Civilité: {$informationsPersonelles['civ']}</p>";

if ($informationsPersonelles['bac'] !== 'N/A') {
    echo "<p>Bac: {$informationsPersonelles['bac']}</p>";
}
if ($informationsPersonelles['specialite'] !== 'N/A') {
    echo "<p>Spécialité: {$informationsPersonelles['specialite']}</p>";
}
if ($informationsPersonelles['typeAdm'] !== 'N/A') {
    echo "<p>Type d'admission: {$informationsPersonelles['typeAdm']}</p>";
}
if ($informationsPersonelles['rgAdm'] !== 'N/A') {
    echo "<p>Rg. Adm.: {$informationsPersonelles['rgAdm']}</p>";
}

foreach ($informationsParSemestre as $table => $details) {
    preg_match('/semestre(\d+)_\d+/', $table, $matches);
    $semesterNumber = $matches[1] ?? 'Inconnu';
    echo '<div class="semester-details">';
    echo "<h3>Semestre: {$semesterNumber}</h3>";

    foreach ($details as $column => $value) {
        if ($column === 'abs' && isset($details['just1'])) {
            echo "<p>Absences non justifiées: " . max(0, $value - $details['just1']) . "</p>";
        } elseif ($column === 'just1') {
            continue;
        } elseif ($value !== '') {
            echo "<p>" . ucfirst(str_replace('_', ' ', $column)) . ": $value</p>";
        }
    }
    echo '</div>';
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

