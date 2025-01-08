<form method="get" action="controleurFrontal.php">
    <input type="hidden" name="action" value="avisParametre"/>
    <input type="hidden" name="controleur" value="administrateur">
    <fieldset>
        <legend>Formulaire de paramétrage d'avis</legend>
        <p>En dessous de la note paramétrée dans la première case, un étudiant aura pour avis Réservé. Entre la deuxième et la deuxième case, un étudiant aura pour avis Favorable. Au dessus de la deuxième case, un étudiant aura pour avis Très Favorable. <b>Il faut garder le nombre de la première case inférieur à la deuxième case.</b></p>
        <p class="InputAddOn">
            <label class="InputAddOn-item" for="note1">Note minimale pour Favorable&#42;</label>
            <input class="InputAddOn-field" type="number" name="note1" id="note1" placeholder="Ex : 10" required>
        </p>
        <p class="InputAddOn">
            <label class="InputAddOn-item" for="note2">Note minimale pour Très Favorable&#42;</label>
            <input class="InputAddOn-field" type="number" name="note2" id="note2" placeholder="Ex : 12" required>
        </p>
        <p class="InputAddOn">
            <!--            <input class="InputAddOn-field" type="submit" value="Envoyer" />-->
            <button class = "button-submit" type="submit">Envoyer</button>
        </p>
    </fieldset>
</form>