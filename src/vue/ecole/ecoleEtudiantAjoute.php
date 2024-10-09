<?php
/** @var string $codeUnique */

/** @var string $titre */

use App\GenerateurAvis\Modele\Repository\UtilisateurRepository;

$login = $_GET["login"] ?? "";
$password = UtilisateurRepository::recupererUtilisateurParLogin($login)->getPasswordHash();

echo "<h1>$titre</h1>";
echo "<p>Le futur étudiant avec le code unique <strong>" . htmlspecialchars($codeUnique) . "</strong> a bien été ajouté à l'école.</p>";

echo '<p><a href="controleurFrontal.php?login=' . urlencode($login) . '&password=' . urlencode($password) . '&action=connecter&controleur=Connexion&type=ecole">Retourner à la page de connexion école</a></p>';
?>
