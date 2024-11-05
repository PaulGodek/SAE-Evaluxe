<?php

use App\GenerateurAvis\Modele\DataObject\Ecole;

echo "<h2>Liste des écoles</h2> 
    <p><a href='controleurFrontal.php?controleur=ecole&action=afficherListe'>  Trier par validation  </a>&emsp; 
       <a href='controleurFrontal.php?controleur=ecole&action=afficherListeEcoleOrdonneParNom'>  Trier par nom  </a>&emsp; 
       <a href='controleurFrontal.php?controleur=ecole&action=afficherListeEcoleOrdonneParVille'>  Trier par ville  </a></p> 
<ul>";

/** @var Ecole[] $ecoles */
foreach ($ecoles as $ecole) {
    $nomHTML = htmlspecialchars($ecole->getNom());
    $loginURL = rawurlencode($ecole->getLogin());

    if (!$ecole->isEstValide()) {
        echo '<li><p>L\'école <a href="controleurFrontal.php?action=afficherDetail&login=' . $loginURL . '">' . $nomHTML . '</a> 
                  &ensp; <a href="controleurFrontal.php?controleur=ecole&action=valider&login=' . $loginURL . '">Valider</a> </p></li>';
    } else {
        echo '<li><p>L\'école <a href="controleurFrontal.php?action=afficherDetail&login=' . $loginURL . '">' . $nomHTML . '</a></p></li>';
    }
}

echo "</ul>";
