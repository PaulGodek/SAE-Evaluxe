<?php
/** @var Etudiant $etudiant */

use App\GenerateurAvis\Modele\DataObject\Etudiant;

echo "L'étudiant " . htmlspecialchars($etudiant->getNom()) .htmlspecialchars($etudiant->getPrenom()) ." dont la moyenne est  " . htmlspecialchars($etudiant->getMoyenne()) . ".";