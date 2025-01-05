<?php
/** @var string $nom */
/** @var string $login */
/** @var string $adresse */
/** @var string $ville */
/** @var string $dateCreation */
/** @var string $title */
$titleHTML = $title;
?>
<h2><?= htmlspecialchars($titleHTML) ?></h2>
<p><strong>Nom:</strong> <?= htmlspecialchars($nom) ?></p>
<p><strong>Login:</strong> <?= htmlspecialchars($login) ?></p>
<p><strong>Addresse:</strong> <?= htmlspecialchars($adresse) ?></p>
<p><strong>Ville:</strong> <?= htmlspecialchars($ville) ?></p>
<p><strong>Créé le:</strong> <?= htmlspecialchars($dateCreation) ?></p>
