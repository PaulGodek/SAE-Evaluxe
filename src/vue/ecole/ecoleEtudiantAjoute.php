<?php
/** @var string $codeUnique */

/** @var string $titre */

//use App\GenerateurAvis\Modele\Repository\UtilisateurRepository;

use App\GenerateurAvis\Controleur\ControleurEcole;

echo "<h1>$titre</h1>";
echo "<p>Le futur étudiant avec le code unique <strong>" . htmlspecialchars($codeUnique) . "</strong> a bien été ajouté à l'école.</p>";
echo "<p><a href='controleurFrontal.php?action=afficherEcole&controleur=ecole'>Retourner à la page de l'école</a></p>";
