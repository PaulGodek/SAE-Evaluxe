<?php
/** @var array $nomPrenomArray
 *  @var string $loginEtudiant
 * @var string $avis
 */
?>
<form method="get" action="controleurFrontal.php">
    <input type="hidden" name="action" value="publierAvisEtudiant"/>
    <input type="hidden" name="controleur" value="professeur"/>
    <input type="hidden" name="loginEtudiant" value="<?php echo $loginEtudiant?>"/>
    <input type="hidden" name="avisDejaSet" value="<?php if (!is_null($avis)) echo "1"; else echo "0";?>"/>
    <fieldset>
        <legend>Remplissage d'avis sur l'étudiant <?php echo htmlspecialchars($nomPrenomArray["Nom"]); ?> <?php echo htmlspecialchars($nomPrenomArray["Prenom"]); ?>:</legend>

        <p class="InputAddOn">
            <label class="InputAddOn-item" for="avis_id"></label>
            <input class="InputAddOn-field" type="text"  name="avis" placeholder="Entrez votre avis ici..." value="<?php
                if (!is_null($avis)) {
                    echo $avis;
                }
            ?>" id="avis_id">
        </p>
        <p class="InputAddOn">
            <!--            <input class="InputAddOn-field" type="submit" value="Envoyer" />-->
            <button class = "button-submit" type="submit">Envoyer</button>
        </p>
    </fieldset>
</form>