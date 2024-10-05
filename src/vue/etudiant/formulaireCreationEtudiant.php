<form method="get" action="controleurFrontal.php">
    <input type="hidden" name="action" value="creerEtudiantDepuisFormulaire"/>
    <fieldset>
        <legend>Formulaire de création de compte étudiant:</legend>
        <p class="InputAddOn">
            <label class="InputAddOn-item" for="login_id">Login&#42;</label>
            <input class="InputAddOn-field" type="text"  name="login" id="login_id" required>
        </p>
        <p class="InputAddOn">
            <label class="InputAddOn-item" for="nom_id">Nom&#42;</label>
            <input class="InputAddOn-field" type="text"  name="nom" id="nom_id" required>
        </p>
        <p class="InputAddOn">
            <label class="InputAddOn-item" for="prenom_id">Nom&#42;</label>
            <input class="InputAddOn-field" type="text"  name="prenom" id="prenom_id" required>
        </p>
        <p class="InputAddOn">
            <label class="InputAddOn-item" for="moyenne_id">Prénom&#42;</label>
            <input class="InputAddOn-field" type="text"  name="moyenne" id="moyenne_id" required>
        </p>
        <p class="InputAddOn">
            <input class="InputAddOn-field" type="submit" value="Envoyer" />
        </p>
    </fieldset>
</form>