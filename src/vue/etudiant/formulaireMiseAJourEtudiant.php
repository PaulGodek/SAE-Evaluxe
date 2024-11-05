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
            <label class="InputAddOn-item" for="idE_id">Id étudiant&#42;</label>
            <input class="InputAddOn-field" type="text"  name="etudid" id="idE_id" value="<?= htmlspecialchars($etudiant->getIdEtudiant())?>" required>
        </p>
        <p class="InputAddOn">
            <label class="InputAddOn-item" for="codeUnique_id">Code Unique&#42;</label>
            <input class="InputAddOn-field" type="text"  name="codeU" id="codeUnique_id" value="<?= htmlspecialchars($etudiant->getCodeUnique())?>" readonly>
        </p>
        <p class="InputAddOn">
            <!--            <input class="InputAddOn-field" type="submit" value="Envoyer" />-->
            <button class = "button-submit" type="submit">Envoyer</button>
        </p>
    </fieldset>
</form>