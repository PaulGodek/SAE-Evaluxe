<form method="get" action="controleurFrontal.php">
    <input type="hidden" name="action" value="afficherResultatRechercheEtudiant"/>
    <input type="hidden" name="controleur" value="etudiant"/>
    <fieldset>
        <legend>Recherche :</legend>
        <p class="InputAddOn">
            <label class="InputAddOn-item" for="reponse">Login de l'étudiant (nomp)</label>
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
/** @var bool $parParcours */
/**@var Ecole $ecole*/

use App\GenerateurAvis\Lib\ConnexionUtilisateur;
use App\GenerateurAvis\Modele\DataObject\Etudiant;
use App\GenerateurAvis\Modele\DataObject\Ecole;
use App\GenerateurAvis\Modele\Repository\EtudiantRepository;

echo "<h2>Liste des étudiants</h2> 
        
    <p><a href='controleurFrontal.php?controleur=etudiant&action=afficherListeEtudiantOrdonneParNom'>Trier par nom</a>&emsp; <a href='controleurFrontal.php?controleur=etudiant&action=afficherListeEtudiantOrdonneParPrenom'>Trier par prenom</a>&emsp; <a href='controleurFrontal.php?controleur=etudiant&action=afficherListeEtudiantOrdonneParParcours'>Trier par parcours</a></p> 
    
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
    if (ConnexionUtilisateur::estEcole()) {


        $loginEcoleURL= rawurlencode($ecole->getLogin());

        if(!$etudiant->dejaDemande($ecole->getNom())){

            echo '<li><p> L\'étudiant ' . $nomHTML . '&nbsp;' . $prenomHTML . '&emsp; <a href="controleurFrontal.php?controleur=etudiant&action=demander&login=' . $loginURL . '&demandeur=' . $loginEcoleURL . '">Demander l\'accès aux informations </a> </p></li>';
        }
        else if(!in_array($etudiant->getCodeUnique(),$ecole->getFutursEtudiants())&& !$etudiant->dejaDemande($ecole->getNom())){
            echo '<li><p> L\'étudiant ' . $nomHTML . '&nbsp;' . $prenomHTML . '  &emsp; (Demande déjà evoyée et acceptée)&emsp;  </p></li>';
        }
        else if (!in_array($etudiant->getCodeUnique(),$ecole->getFutursEtudiants())&& $etudiant->dejaDemande($ecole->getNom())){
            echo '<li><p> L\'étudiant ' . $nomHTML . '&nbsp;' . $prenomHTML . '  &emsp; (Demande en attente de réponse)&emsp; <a href="controleurFrontal.php?controleur=etudiant&action=supprimerDemande&login=' . $loginURL . '&demandeur=' . $loginEcoleURL . '">Supprimer la demande  </a> </p></li>';

        }else{

        }
    } else {
        echo '<li><p>L\'étudiant <a href="controleurFrontal.php?action=afficherDetail&login=' . $loginURL . '">   ' . $nomHTML . '&nbsp;' . $prenomHTML . '</a></p></li>';
    }
}

echo "</ul>";
