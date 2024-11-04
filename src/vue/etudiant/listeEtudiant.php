<form method="get" action="controleurFrontal.php">
    <input type="hidden" name="action" value="afficherResultatRechercheEtudiant"/>
    <fieldset>
        <legend>Recherche :</legend>
        <p class="InputAddOn">
            <label class="InputAddOn-item" for="reponse">Nom/prenom de l'étudiant</label>
            <input class="InputAddOn-field" type="text" name="reponse" placeholder="Ex : Dupont " id="reponse" required>
        </p>

        <p class="InputAddOn">
            <!--            <input class="InputAddOn-field" type="submit" value="Rechercher" />-->
            <button class="button-submit" type="submit">Rechercher</button>
        </p>
    </fieldset>
</form>
<?php

/** @var Etudiant[] $etudiants */

use App\GenerateurAvis\Modele\DataObject\Etudiant;
use App\GenerateurAvis\Modele\Repository\EtudiantRepository;

echo "<h2>Liste des étudiants</h2> 
        
    <p><a href='controleurFrontal.php?controleur=etudiant&action=afficherListeEtudiantOrdonneParNom'>  Trier par nom  </a>&emsp; <a href='controleurFrontal.php?controleur=etudiant&action=afficherListeEtudiantOrdonneParPrenom'>  Trier par prenom  </a>"/*<a href='controleurFrontal.php?controleur=etudiant&action=afficherListeEtudiantOrdonneParParcours'>  Trier par parcours  </a>*/ . "</p> 
    
<ul>";
foreach ($etudiants as $etudiant) {
    $idEtudiant = $etudiant->getIdEtudiant();
    $nomPrenom = EtudiantRepository::getNomPrenomParIdEtudiant($idEtudiant);

    if ($nomPrenom) {
        $nomHTML = htmlspecialchars($nomPrenom['Nom']);
        $prenomHTML = htmlspecialchars($nomPrenom['Prenom']);
    } else {
        $nomHTML = $prenomHTML = 'Nom inconnu';
    }

    $loginURL = rawurlencode($etudiant->getLogin());
    echo '<li><p>L\'étudiant <a href="controleurFrontal.php?action=afficherDetail&login=' . $loginURL . '">' . $nomHTML . '&nbsp;' . $prenomHTML . '</a></p></li>';
}

echo "</ul>";
