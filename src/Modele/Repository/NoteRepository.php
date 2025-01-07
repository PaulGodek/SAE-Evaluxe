<?php

namespace App\GenerateurAvis\Modele\Repository;

use App\GenerateurAvis\Modele\DataObject\AbstractDataObject;
use App\GenerateurAvis\Modele\DataObject\Note;

class NoteRepository extends AbstractRepository
{
    protected function getNomTable(): string
    {
        return "Note";
    }

    protected function getNomClePrimaire(): string
    {
        return "code_nip";
    }


    protected function construireDepuisTableauSQL(array $noteFormatTableau): AbstractDataObject
    {
        return new Note((new UtilisateurRepository())->recupererParClePrimaire($noteFormatTableau['code_nip']),
            $noteFormatTableau['id_Ressource'],
            $noteFormatTableau['note']);
    }
    protected function getNomsColonnes(): array
    {
        return ["code_nip", "id_ressource", "note"];
    }

    protected function formatTableauSQL(AbstractDataObject $note): array
    {
        return array(
            "code_nipTag" => $note->getCodeNip(),
            "id_ressourceTag" => $note->getIdRessource(),
            "noteTag" => $note->getNote()
        );
    }

    public function getNbEtudiantParcour(string $parcour): int {
        $sql = "SELECT COUNT(*) AS nb_etudiants FROM ParcoursEtudiant WHERE Parcours = :parcour";
        $stmt = ConnexionBaseDeDonnees::getPdo()->prepare($sql);
        $stmt->execute(['parcour' => $parcour]);
        $result = $stmt->fetch();
        return $result['nb_etudiants'] ?? 0;
    }

    public function getMoyenneUEParSemestre(string $UE, string $semestre): float
    {
        $sql = "SELECT AVG($UE) AS moyenne FROM etudiantUE WHERE numero_semestre = :semestre AND $UE != 0";
        $pdoStatement = ConnexionBaseDeDonnees::getPdo()->prepare($sql);
        $pdoStatement->execute(['semestre' => $semestre]);
        $result = $pdoStatement->fetch();
        return $result['moyenne'] !== null ? (float)$result['moyenne'] : 0.0;
    }

    public function getMoyenneUEParEtudiantParSemestre(string $codeNip, string $UE, string $semestre): float {
        $sql = "SELECT $UE AS moyenne FROM etudiantUE WHERE code_nip = :codeNip AND numero_semestre = :semestre AND $UE IS NOT NULL";
        $pdoStatement = ConnexionBaseDeDonnees::getPdo()->prepare($sql);
        $pdoStatement->execute(['codeNip' => $codeNip, 'semestre' => $semestre]);
        $result = $pdoStatement->fetch();
        return $result !== false ? (float)$result['moyenne'] : 0.0; }
}