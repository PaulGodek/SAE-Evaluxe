<h2>Ajouter un Étudiant</h2>
<form method="POST" action="">
    <label for="codeUnique">Code Unique de l'Étudiant:</label>
    <input type="text" id="codeUnique" name="codeUnique" required>
    <button type="submit">Ajouter Étudiant</button>
</form>

<?php if (isset($message)) echo "<p>$message</p>"; ?>