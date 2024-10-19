<p>Administrateur connecté</p>


<?php

use App\GenerateurAvis\Controleur\ControleurUtilisateur;
use App\GenerateurAvis\Lib\ConnexionUtilisateur;

ConnexionUtilisateur::estConnecte();

ControleurUtilisateur::afficherListe();
?>
