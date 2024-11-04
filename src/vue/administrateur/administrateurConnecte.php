<p>Administrateur connecté</p>

<!--<a href="/sae3a-base/web/controleurFrontal.php?controleur=utilisateur&action=refaire"
   class="nav-item">Refaire les codes uniques et importer les ids</a>-->
<?php

use App\GenerateurAvis\Controleur\ControleurUtilisateur;
use App\GenerateurAvis\Lib\ConnexionUtilisateur;

ConnexionUtilisateur::estConnecte();

//"ControleurUtilisateur::afficherListe();"
?>
