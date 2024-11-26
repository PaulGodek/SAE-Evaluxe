<?php
/** @var string|null $codeUnique */
/** @var array $informationsPersonelles */
/** @var array $informationsParSemestre */
/** @var string $code_nip */

/** @var string $loginEtudiant */
/** @var array $agregations */

use App\GenerateurAvis\Lib\ConnexionUtilisateur;

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



if (ConnexionUtilisateur::estAdministrateur())
    echo '<p><a class="button" href="controleurFrontal.php?controleur=Professeur&action=afficherAvisProfesseurs&login=' . rawurlencode($loginEtudiant) . '">Voir avis des professeurs</a></p>';

if (!empty($agregations)) {
    echo "<h2>Agregations et notes finales</h2>";
    foreach ($agregations as $agregation) {
        echo "<h3>Nom agrégation: " . htmlspecialchars($agregation['nom_agregation']) . "</h3>";
        echo "<p>Note finale: " . number_format($agregation['note_finale'], 2) . "</p>";
    }
} else {
    echo "<p>Aucune agregation disponible.</p>";
}

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

    foreach ($informationsParSemestre as $table => $details) {
        preg_match('/semestre(\d+)_\d+/', $table, $matches);
        $semesterNumber = htmlspecialchars($matches[1] ?? 'Inconnu');
        echo '<div class="semester-details">';
        echo "<h3>Semestre: {$semesterNumber}</h3>";

        foreach ($details as $column => $value) {
            if ($column == 'abs' && isset($details['just1'])) {
                echo "<p>Absences non justifiées: " . htmlspecialchars(max(0, $value - $details['just1'])) . "</p>";
            } elseif ($column == 'just1') {
                continue;
            } elseif ($value !== '') {
                $valueHTML = htmlspecialchars($value);
                echo "<p>" . htmlspecialchars(ucfirst(str_replace('_', ' ', $column))) . ": $valueHTML</p>";
            }
        }
        echo '</div>';
    }
    echo '</div>';
} else {
    echo '<p>Aucun détail n\'a été trouvé pour l\'étudiant avec code NIP ' . htmlspecialchars($code_nip) . '.</p>';
}
