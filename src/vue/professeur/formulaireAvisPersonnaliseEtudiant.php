<?php
/** @var array $nomPrenomArray
 *  @var string $loginEtudiant
 * @var array $avis
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
                echo $avis['avis'];
            }
            ?>" id="avis_id">
        </p>
        <p class="InputAddOn">
            <label class="InputAddOn-item" for="ecoleIngenieur_id">École d'ingénieur ou master en informatique:</label>
            <select name="ecoleIngenieur" id="ecoleIngenieur_id">
                <option value="Tres favorable" <?php if ($avis['ecoleIngenieur'] == 'Tres favorable') echo 'selected'; ?>>Très favorable</option>
                <option value="Favorable" <?php if ($avis['ecoleIngenieur'] == 'Favorable') echo 'selected'; ?>>Favorable</option>
                <option value="Reserve" <?php if ($avis['ecoleIngenieur'] == 'Reserve') echo 'selected'; ?>>Réservé</option>
            </select>
        </p>
        <p class="InputAddOn">
            <label class="InputAddOn-item" for="masterManagement_id">Master en management:</label>
            <select name="masterManagement" id="masterManagement_id">
                <option value="Tres favorable" <?php if ($avis['masterManagement'] == 'Tres favorable') echo 'selected'; ?>>Très favorable</option>
                <option value="Favorable" <?php if ($avis['masterManagement'] == 'Favorable') echo 'selected'; ?>>Favorable</option>
                <option value="Reserve" <?php if ($avis['masterManagement'] == 'Reserve') echo 'selected'; ?>>Réservé</option>
            </select>
        </p>
        <p class="InputAddOn">
            <button class="button-submit" type="submit">Envoyer</button>
        </p>
    </fieldset>
</form>