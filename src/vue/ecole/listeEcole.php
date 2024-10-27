<form method="get" action="controleurFrontal.php">
    <input type="hidden" name="action" value="afficherResultatRechercheEcole"/>
    <fieldset>
        <legend>Recherche :</legend>
        <p class="InputAddOn">
            <label class="InputAddOn-item" for="nom">Nom de l'école</label>
            <input class="InputAddOn-field" type="text" name ="nom" placeholder="Ex : IUT Montpellier"  id="nom" required>
        </p>

        <p class="InputAddOn">
            <input class="InputAddOn-field" type="submit" value="Rechercher" />
        </p>
    </fieldset>
</form>
<?php


use App\GenerateurAvis\Controleur\ControleurUtilisateur;
use App\GenerateurAvis\Lib\ConnexionUtilisateur;
use App\GenerateurAvis\Modele\DataObject\Ecole;
use App\GenerateurAvis\Modele\Repository\UtilisateurRepository;

echo "<h2>Liste des écoles</h2> 
        
    <p><a href='controleurFrontal.php?controleur=ecole&action=afficherListeEcoleOrdonneParNom'>  Trier par nom  </a>&emsp; <a href='controleurFrontal.php?controleur=ecole&action=afficherListeEcoleOrdonneParVille'>  Trier par ville  </a></p> 
    
<ul>";
/** @var Ecole[] $ecoles */
    $utilisateur=(new UtilisateurRepository())->recupererParClePrimaire(ConnexionUtilisateur::getLoginUtilisateurConnecte());
    $admin=$utilisateur->getType() == "administrateur";
        foreach ($ecoles as $ecole) {
            $nomHTML = htmlspecialchars($ecole->getNom());
            $loginURL = rawurlencode($ecole->getLogin());
            if (!$admin|| ($admin&&$ecole->isEstValide())) {
                echo '<li><p> L\'école <a href="controleurFrontal.php?action=afficherDetail&login=' . $loginURL . '">' . $nomHTML . '</a> </p></li>';
            } else {
                echo '<li><p> L\'école <a href="controleurFrontal.php?action=afficherDetail&login=' . $loginURL . '">' . $nomHTML . '</a> &ensp; <a href="controleurFrontal.php?controleur=ecole&action=valider&login=' . $loginURL . '">Valider</a> </p></li>';
            }
        }