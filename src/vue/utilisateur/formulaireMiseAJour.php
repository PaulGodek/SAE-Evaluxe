<?php
use App\GenerateurAvis\Modele\DataObject\Utilisateur;
/** @var Utilisateur $utilisateur */
?>
<form method="get" action="controleurFrontal.php">
    <input type="hidden" name="action" value="mettreAJour"/>
    <input type="hidden" name="controleur" value="utilisateur"/>
    <fieldset>
        <legend>Mon formulaire :</legend>
        <p class="InputAddOn">
            <label class="InputAddOn-item" for="login_id">Login&#42;</label>
            <input class="InputAddOn-field" type="text"  name="login" id="login_id" value="<?= htmlspecialchars($utilisateur->getLogin())?>" readonly>
        </p>
        <p class="InputAddOn">
            <label class="InputAddOn-item" for="type_id">Type&#42;</label>
            <input class="InputAddOn-field" type="text" name="type" id="type_id" value="<?= htmlspecialchars($utilisateur->getType())?>" required>
        </p>
        <p class="InputAddOn">
<!--            <input class="InputAddOn-field" type="submit" value="Envoyer" />-->
            <button class = "button-submit" type="submit">Envoyer</button>
        </p>
    </fieldset>
</form>