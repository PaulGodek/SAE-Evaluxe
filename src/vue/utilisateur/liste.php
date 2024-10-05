<?php
/** @var Utilisateur[] $utilisateurs */

use App\GenerateurAvis\Modele\DataObject\Utilisateur;

echo "<h2>Liste des utilisateurs</h2><ul>";
foreach ($utilisateurs as $utilisateur) {
    $loginHTML = htmlspecialchars($utilisateur->getLogin());
    $loginURL = rawurlencode($utilisateur->getLogin());
    echo '<li><p> Utilisateur de login <a href="controleurFrontal.php?action=afficherDetail&login=' . $loginURL . '">' . $loginHTML . '</a> (<a href="controleurFrontal.php?action=afficherFormulaireMiseAJour&login=' . $loginURL . '">Modifier ?</a>, <a href="controleurFrontal.php?action=supprimer&login=' . $loginURL . '">Supprimer ?</a>)</p></li>';
}
echo '</ul><p><a href="controleurFrontal.php?action=afficherFormulaireCreationEtudiant">Créer un etudiant</a>&emsp;<a href="controleurFrontal.php?action=afficherFormulaireCreationEcole">Créer une école</a> </p>';