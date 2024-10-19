<p>École connecté</p>


<?php

use App\GenerateurAvis\Controleur\ControleurEcole;
\App\GenerateurAvis\Lib\ConnexionUtilisateur::estConnecte();

ControleurEcole::afficherEcole();
?>
