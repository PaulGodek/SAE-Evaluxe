<?php
/** @var ?array $avis
 * @var ?array $listeNomPrenom
 * */

if (is_null($avis))
    echo "<h3>Il n'y a aucun avis émis par des professeurs pour cet étudiant</h3>";
else {
    echo "<h2>Avis des Professeurs</h2>";
    echo "<ul>";
    foreach ($avis as $avisParProfesseur) {
        $nom = $listeNomPrenom[$avisParProfesseur["loginProfesseur"]]["nom"];
        $prenom = $listeNomPrenom[$avisParProfesseur["loginProfesseur"]]["prenom"];
        $avisEffectif = $avisParProfesseur["avis"];
        echo "<li><p>Avis du professeur <b>" . $nom . " " . $prenom . "</b> : " . $avisEffectif . "</p></li>";
    }
    echo "</ul>";
}