<?php
/** @var string|null $codeUnique */
/** @var array $informationsPersonelles */
/** @var array $informationsParSemestre */
/** @var string $idEtudiant */
?>

<link rel="stylesheet" type="text/css" href="../ressources/css/detailEtudiant.css">

<?php
if ($informationsPersonelles) {
    echo '<div class="etudiant-details">';
    echo "<h2>Détails de l'étudiant</h2>";
    echo "<h3>Code unique: {$codeUnique}</h3>";
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
            if ($column == 'abs' && isset($details['just1'])) {
                echo "<p>Absences non justifiées: " . max(0, $value - $details['just1']) . "</p>";
            } elseif ($column == 'just1') {
                continue;
            } elseif ($value !== '') {
                echo "<p>" . ucfirst(str_replace('_', ' ', $column)) . ": $value</p>";
            }
        }
        echo '</div>';
    }
    echo '</div>';
} else {
    echo '<p>Aucun détail n\'a été trouvé pour l\'étudiant avec ID ' . htmlspecialchars($idEtudiant) . '.</p>';
}
