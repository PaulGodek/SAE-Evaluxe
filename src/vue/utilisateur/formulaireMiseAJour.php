<?php
use App\Covoiturage\Modele\DataObject\Utilisateur;
/** @var Utilisateur $utilisateur */
?>
<form method="get" action="controleurFrontal.php">
    <input type="hidden" name="action" value="mettreAJour"/>
    <fieldset>
        <legend>Mon formulaire :</legend>
        <p class="InputAddOn">
            <label class="InputAddOn-item" for="login_id">Login&#42;</label>
            <input class="InputAddOn-field" type="text" placeholder="Ex : leblancj" name="login" id="login_id" value="<?= htmlspecialchars($utilisateur->getLogin())?>" readonly>
        </p>
        <p class="InputAddOn">
            <label class="InputAddOn-item" for="nom_id">Nom&#42;</label>
            <input class="InputAddOn-field" type="text" placeholder="Ex : Leblanc" name="nom" id="nom_id" value="<?= htmlspecialchars($utilisateur->getNom())?>" required>
        </p>
        <p class="InputAddOn">
            <label class="InputAddOn-item" for="prenom_id">Prénom&#42;</label>
            <input class="InputAddOn-field" type="text" placeholder="Ex : Jveux" name="prenom" id="prenom_id" value="<?= htmlspecialchars($utilisateur->getPrenom())?>" required>
        </p>
        <p class="InputAddOn">
            <input class="InputAddOn-field" type="submit" value="Envoyer" />
        </p>
    </fieldset>
</form>