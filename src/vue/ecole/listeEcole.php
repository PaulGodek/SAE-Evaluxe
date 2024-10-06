<form method="get" action="controleurFrontal.php">
    <input type="hidden" name="action" value="afficherDetailEcole"/>
    <fieldset>
        <legend>Mon formulaire :</legend>
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


use App\GenerateurAvis\Modele\DataObject\Ecole;

echo "<h2>Liste des écoles</h2> 
        
    <p><a href='controleurFrontal.php?controleur=ecole&action=afficherListeEcoleOrdonneParNom'>  Trier par nom  </a>&emsp;<a href='controleurFrontal.php?controleur=ecole&action=afficherListeEcoleOrdonneParAdresse'>  Trier par adresse  </a></p> 
    
<ul>";
/** @var Ecole[] $ecoles */
    foreach ($ecoles as $ecole) {
    $nomHTML = htmlspecialchars($ecole->getNom());
    $loginURL = rawurlencode($ecole->getLogin());
    echo '<li><p> L\'école <a href="controleurFrontal.php?action=afficherDetail&login=' . $loginURL . '">' . $nomHTML . '</a></p></li>';
    }


