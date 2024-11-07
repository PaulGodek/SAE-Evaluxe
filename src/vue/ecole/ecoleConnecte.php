<!--<p>École connecté</p>-->


<?php

use App\GenerateurAvis\Lib\ConnexionUtilisateur;

ConnexionUtilisateur::estConnecte();

require 'pageEcole.php';
