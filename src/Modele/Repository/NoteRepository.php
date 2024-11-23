<?php

namespace App\GenerateurAvis\Modele\Repository;

class NoteRepository
{
    public static function getNoteForMatiere(int $idRessource, int $idEtudiant): float
    {
        $sql = "SELECT note FROM Note WHERE id_ressource = :id_ressource AND idEtudiant = :idEtudiant";
        $stmt = ConnexionBaseDeDonnees::getPdo()->prepare($sql);
        $stmt->execute(['id_ressource' => $idRessource, 'idEtudiant' => $idEtudiant]);

        $note = $stmt->fetchColumn();
        return $note !== false ? (float)$note : 0;
    }
}