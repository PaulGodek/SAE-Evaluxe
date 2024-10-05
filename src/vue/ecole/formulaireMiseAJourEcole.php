<?php
use App\GenerateurAvis\Modele\DataObject\Ecole;
/** @var Ecole $ecole */
?>
<form method="get" action="controleurFrontal.php">
    <input type="hidden" name="action" value="mettreAJourEcole"/>
    <fieldset>
        <legend>Formulaire de mise à jour </legend>
        <p class="InputAddOn">
            <label class="InputAddOn-item" for="login_id">Login&#42;</label>
            <input class="InputAddOn-field" type="text"  name="login" id="login_id" value="<?= htmlspecialchars($ecole->getLogin())?>" readonly>
        </p>
        <p class="InputAddOn">
            <label class="InputAddOn-item" for="nom_id">Nom&#42;</label>
            <input class="InputAddOn-field" type="text"  name="nom" id="nom_id" value="<?= htmlspecialchars($ecole->getNom())?>" required>
        </p>
        <p class="InputAddOn">
            <label class="InputAddOn-item" for="adresse_id">Adresse&#42;</label>
            <input class="InputAddOn-field" type="text"  name="adresse" id="adresse_id" value="<?= htmlspecialchars($ecole->getAdresse())?>" required>
        </p>
        <p class="InputAddOn">
            <input class="InputAddOn-field" type="submit" value="Envoyer" />
        </p>
    </fieldset>
</form>