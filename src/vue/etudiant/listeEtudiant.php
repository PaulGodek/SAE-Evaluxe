<link rel="stylesheet" href="../ressources/css/connect.css">
<div class="container">
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
</div>
<?php

/** @var Etudiant[] $etudiants */
/** @var bool $parParcours */
/**@var Ecole $ecole */
/** @var ?bool $avis */

/**@var array $listeNomPrenom */


use App\GenerateurAvis\Lib\ConnexionUtilisateur;
use App\GenerateurAvis\Modele\DataObject\Etudiant;
use App\GenerateurAvis\Modele\DataObject\Ecole;

echo "<div class='container'><h2>Liste des étudiants</h2> 
        
    <p><a href='controleurFrontal.php?controleur=etudiant&action=afficherListeEtudiantOrdonneParNom'>Trier par nom</a>&emsp; <a href='controleurFrontal.php?controleur=etudiant&action=afficherListeEtudiantOrdonneParPrenom'>Trier par prenom</a>&emsp; <a href='controleurFrontal.php?controleur=etudiant&action=afficherListeEtudiantOrdonneParParcours'>Trier par parcours</a></p> 
    
<ul>";
$i = 0;
foreach ($etudiants as $etudiant) {

    $code_nip = $etudiant->getCodeNip();

    if ($listeNomPrenom[$i]) {
        $nomHTML = htmlspecialchars($listeNomPrenom[$i]['Nom']);
        $prenomHTML = htmlspecialchars($listeNomPrenom[$i]['Prenom']);
    } else {
        $nomHTML = $prenomHTML = 'Nom inconnu';
    }

    $loginURL = rawurlencode($etudiant->getUtilisateur()->getLogin());
    if (ConnexionUtilisateur::estEcole()) {

        $loginEcoleURL = rawurlencode($ecole->getUtilisateur()->getLogin());

        if(!$etudiant->dejaDemande($ecole->getNom())){
            echo '<li><p> L\'étudiant ' . $nomHTML . '&nbsp;' . $prenomHTML . '&emsp; <a href="controleurFrontal.php?controleur=etudiant&action=demander&login=' . $loginURL . '&demandeur=' . $loginEcoleURL . '">Demander l\'accès aux informations </a> </p></li>';
        } else if (!in_array($etudiant->getCodeUnique(), $ecole->getFutursEtudiants()) && !$etudiant->dejaDemande($ecole->getNom())) {
            echo '<li><p> L\'étudiant ' . $nomHTML . '&nbsp;' . $prenomHTML . '  &emsp; (Demande déjà evoyée et acceptée)&emsp;  </p></li>';
        } else if (!in_array($etudiant->getCodeUnique(), $ecole->getFutursEtudiants()) && $etudiant->dejaDemande($ecole->getNom())) {
            echo '<li><p> L\'étudiant ' . $nomHTML . '&nbsp;' . $prenomHTML . '  &emsp; (Demande en attente de réponse)&emsp; <a href="controleurFrontal.php?controleur=etudiant&action=supprimerDemande&login=' . $loginURL . '&demandeur=' . $loginEcoleURL . '">Supprimer la demande  </a> </p></li>';

        }

    } else if (ConnexionUtilisateur::estProfesseur()) {
        $code_nipURL = rawurlencode($etudiant->getCodeNip());
        if (strcmp($etudiant->getAvisProfesseur(ConnexionUtilisateur::getLoginUtilisateurConnecte()), "") === 0) {
            echo '<li><p>L\'étudiant <a href="controleurFrontal.php?action=afficherDetail&login=' . $loginURL . '">   ' . $nomHTML . '&nbsp;' . $prenomHTML . '<a/>   <a href=controleurFrontal.php?controleur=professeur&action=afficherFormulaireAvisEtudiant&loginEtudiant=' . $loginURL . '&code_nip=' . $code_nipURL . '">(Ajouter un avis ?)<a/></p></li>';
        } else {
            echo '<li><p>L\'étudiant <a href="controleurFrontal.php?action=afficherDetail&login=' . $loginURL . '">   ' . $nomHTML . '&nbsp;' . $prenomHTML . '<a/>   <a href=controleurFrontal.php?controleur=professeur&action=afficherFormulaireAvisEtudiant&loginEtudiant=' . $loginURL . '&code_nip=' . $code_nipURL . '">(Modifier un avis ?)<a/></p></li>';
        }

    } else {
        echo '<li><p>L\'étudiant <a href="controleurFrontal.php?action=afficherDetail&login=' . $loginURL . '">   ' . $nomHTML . '&nbsp;' . $prenomHTML . '</a></p>';
        echo ' <p>(<a href="controleurFrontal.php?controleur=utilisateur&action=afficherFormulaireMiseAJour&login=' . $loginURL . '">Modifier le mot de passe </a>, <a href="controleurFrontal.php?controleur=administrateur&action=AmodifierAvis&login=' . $loginURL . '">Modifier l\'avis de poursuite</a>)</p></li>';

    }
    $i++;
}

echo "</ul></div>";
