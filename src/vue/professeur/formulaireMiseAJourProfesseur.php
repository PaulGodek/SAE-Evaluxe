<?php
use App\GenerateurAvis\Modele\DataObject\Professeur;
/** @var Professeur $professeur */
?>
<form method="get" action="controleurFrontal.php">
    <input type="hidden" name="action" value="mettreAJour"/>
    <input type="hidden" name="controleur" value="professeur">
    <fieldset>
        <legend>Formulaire de mise à jour </legend>
        <p class="InputAddOn">
            <label class="InputAddOn-item" for="login_id">Login&#42;</label>
            <input class="InputAddOn-field" type="text" name="login" id="login_id"
                   value="<?= htmlspecialchars($professeur->getUtilisateur()->getLogin()) ?>" readonly>
        </p>
        <p class="InputAddOn">
            <label class="InputAddOn-item" for="nom_id">Nom&#42;</label>
            <input class="InputAddOn-field" type="text"  name="nom" id="nom_id" value="<?= htmlspecialchars($professeur->getNom())?>" required>
        </p>
        <p class="InputAddOn">
            <label class="InputAddOn-item" for="prenom_id">Prenom&#42;</label>
            <input class="InputAddOn-field" type="text"  name="prenom" id="prenom_id" value="<?= htmlspecialchars($professeur->getPrenom())?>" required>
        </p>

        <p class="InputAddOn">
            <label class="InputAddOn-item" for="nvmdp_id">Mot de passe&#42;</label>
            <input class="InputAddOn-field" type="password" value="" placeholder="" name="nvmdp" id="nvmdp_id" >
        </p>
        <p class="InputAddOn">
            <label class="InputAddOn-item" for="nvmdp2_id">Vérification du mot de passe&#42;</label>
            <input class="InputAddOn-field" type="password" value="" placeholder="" name="nvmdp2" id="nvmdp2_id" >
        </p>

        <p class="InputAddOn">
<!--            <input class="InputAddOn-field" type="submit" value="Envoyer" />-->
            <button class = "button-submit" type="submit">Envoyer</button>
        </p>
    </fieldset>
</form>