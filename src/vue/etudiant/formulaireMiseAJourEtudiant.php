<?php
use App\GenerateurAvis\Modele\DataObject\Etudiant;
/** @var Etudiant $etudiant */
?>
<form method="get" action="controleurFrontal.php">
    <input type="hidden" name="action" value="mettreAJour"/>
    <fieldset>
        <legend>Formulaire de mise à jour </legend>
        <p class="InputAddOn">
            <label class="InputAddOn-item" for="type_id">Type&#42;</label>
            <input class="InputAddOn-field" type="text"  name="type" id="type_id" value="etudiant" readonly>
        </p>
        <p class="InputAddOn">
            <label class="InputAddOn-item" for="login_id">Login&#42;</label>
            <input class="InputAddOn-field" type="text"  name="login" id="login_id" value="<?= htmlspecialchars($etudiant->getLogin())?>" readonly>
        </p>
        <p class="InputAddOn">
            <label class="InputAddOn-item" for="nom_id">Nom&#42;</label>
            <input class="InputAddOn-field" type="text"  name="nom" id="nom_id" value="<?= htmlspecialchars($etudiant->getNom())?>" required>
        </p>
        <p class="InputAddOn">
            <label class="InputAddOn-item" for="prenom_id">Prenom&#42;</label>
            <input class="InputAddOn-field" type="text"  name="prenom" id="prenom_id" value="<?= htmlspecialchars($etudiant->getPrenom())?>" required>
        </p>
        <p class="InputAddOn">
            <label class="InputAddOn-item" for="moyenne_id">Moyenne&#42;</label>
            <input class="InputAddOn-field" type="text"  name="moyenne" id="moyenne_id" value="<?= htmlspecialchars($etudiant->getMoyenne())?>" required>
        </p>
        <p class="InputAddOn">
<!--            <input class="InputAddOn-field" type="submit" value="Envoyer" />-->
            <button class = "button-submit" type="submit">Envoyer</button>
        </p>
    </fieldset>
</form>