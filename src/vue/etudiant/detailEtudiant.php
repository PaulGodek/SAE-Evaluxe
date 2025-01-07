<?php
/** @var string|null $codeUnique */
/** @var array $informationsPersonelles */
/** @var array $informationsParSemestre */
/** @var string $code_nip */
/** @var string $loginEtudiant */
/** @var array $agregations */

use App\GenerateurAvis\Lib\ConnexionUtilisateur;
use App\GenerateurAvis\Modele\Repository\AgregationRepository;

?>

<link rel="stylesheet" type="text/css" href="../ressources/css/detailEtudiant.css">

<?php
$codeUniqueHTML = $codeUnique;
$nomHTML = $informationsPersonelles["nom"];
$prenomHTML = $informationsPersonelles["prenom"];
$etuidHTML = $informationsPersonelles["etudid"];
$codenipHTML = $informationsPersonelles["codenip"];
$civHTML = $informationsPersonelles["civ"];
$bacHTML = $informationsPersonelles["bac"];
$specialiteHTML = $informationsPersonelles["specialite"];
$typeAdmHTML = $informationsPersonelles["typeAdm"];
$rgAdmHTML = $informationsPersonelles["rgAdm"];

if (ConnexionUtilisateur::estAdministrateur()) {
    echo '<p><a class="button" href="controleurFrontal.php?controleur=Professeur&action=afficherAvisProfesseurs&login=' . rawurlencode($loginEtudiant) . '">Voir avis des professeurs</a></p>';
}

$agregations = (new AgregationRepository)->getAgregationDetailsByLogin(ConnexionUtilisateur::getLoginUtilisateurConnecte());
$agregations = AgregationRepository::calculateAgregationNotes($agregations, $code_nip);

if ($informationsPersonelles) {
    echo '<div class="etudiant-details">';
    echo "<h2>Détails de l'étudiant</h2>";
    echo "<h3>Code unique: {$codeUniqueHTML}</h3>";
    echo "<p>Nom: {$nomHTML}</p>";
    echo "<p>Prénom: {$prenomHTML}</p>";
    echo "<p>Id étudiant: {$etuidHTML}</p>";
    echo "<p>Code nip: {$codenipHTML}</p>";
    echo "<p>Civilité: {$civHTML}</p>";
    if ($informationsPersonelles['bac'] !== 'N/A') {
        echo "<p>Bac: {$bacHTML}</p>";
    }
    if ($informationsPersonelles['specialite'] !== 'N/A') {
        echo "<p class='specialite-long-text'>" . "Spécialité: {$specialiteHTML}" . "</p>";
    }
    if ($informationsPersonelles['typeAdm'] !== 'N/A') {
        echo "<p>Type d'admission: {$typeAdmHTML}</p>";
    }
    if ($informationsPersonelles['rgAdm'] !== 'N/A') {
        echo "<p>Rg. Adm.: {$rgAdmHTML}</p>";
    }

    echo "<h2>Agregations et notes finales</h2>";
    if (!empty($agregations)) {
        echo "<ul class='agregation-list'>";
        foreach ($agregations as $agregation) {
            echo "<li class='agregation-item'>";
            echo "<span class='nom-agregation'>" . htmlspecialchars($agregation['nom_agregation']) . ":</span> ";
            echo "<span class='note-finale'>" . number_format($agregation['note_finale'], 3) . "</span>";
            echo "</li>";
        }
        echo "</ul>";
    } else {
        echo "<p>Aucune agregation disponible.</p>";
    }


    foreach ($informationsParSemestre as $table => $details) {
        preg_match('/semestre(\d+)_\d+/', $table, $matches);
        $semesterNumber = htmlspecialchars($matches[1] ?? 'Inconnu');

        echo '<div class="semester-details">';
        echo "<h3>Semestre: {$semesterNumber}</h3>";

        echo '<div class="semester-table-container">';
        echo '<table class="semester-table">';
        echo '<thead>';
        echo '<tr>';

        $validColumns = [];
        foreach ($details as $column => $value) {
            if (!empty($value)) {
                $validColumns[$column] = $value;
            }
        }

        foreach ($validColumns as $column => $value) {
            echo "<th>" . htmlspecialchars(ucfirst(str_replace('_', ' ', $column))) . "</th>";
        }

        echo '</tr>';
        echo '</thead>';
        echo '<tbody>';
        echo '<tr>';

        foreach ($validColumns as $value) {
            echo "<td>" . htmlspecialchars($value) . "</td>";
        }

        echo '</tr>';
        echo '</tbody>';
        echo '</table>';
        echo '</div>';
        echo '</div>';
    }
    echo '</div>';
} else {
    echo '<p>Aucun détail n\'a été trouvé pour l\'étudiant avec code NIP ' . htmlspecialchars($code_nip) . '.</p>';
}
echo '<p><a class="button" href="controleurFrontal.php?action=afficherChartUEPourEtudiant&controleur=note&code=' . rawurlencode($codenipHTML) . '">Voir progress chart</a></p>';
?>


<style>

    .semester-table-container {
        width: 100%;
        overflow-x: auto;
        -webkit-overflow-scrolling: touch;
    }

    .semester-table {
        width: 100%;
        border-collapse: collapse;
        min-width: 1000px;
    }

    .semester-table th, .semester-table td {
        border: 1px solid #ddd;
        padding: 8px;
        text-align: center;
        word-wrap: break-word;
    }

    .semester-table th {
        background-color: #f2f2f2;
        color: #333;
        font-weight: bold;
    }

    .semester-table tr:nth-child(even) {
        background-color: #f9f9f9;
    }

    .semester-table tr:hover {
        background-color: #f1f1f1;
    }

    .semester-table td {
        font-size: 14px;
    }

    .semester-details {
        margin-bottom: 40px;
    }

    .semester-details h3 {
        margin-bottom: 10px;
        font-size: 18px;
    }

    .specialite-long-text {
        word-wrap: break-word;
    }


</style>