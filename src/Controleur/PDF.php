<?php

namespace App\GenerateurAvis\Controleur;
require __DIR__ . '/../../bootstrap.php';

use tFPDF;

class PDF extends tFPDF
{
    function Header(): void
    {
        $this->AddFont('DejaVu', '', 'DejaVuSansCondensed.ttf', true);
        $this->SetFont('DejaVu', '', 12);
        $this->Image(__DIR__ . '/../../ressources/images/logoIutInformatique.png', 10, 8, 25, 14);
        $this->Image(__DIR__ . '/../../ressources/images/logo_um_noir.png', 165, 8, 17, 14);
        $this->Image(__DIR__ . '/../../ressources/images/Logo_IUT.png', 185, 8, 17, 14);
        $this->Cell(0, 10, 'Fiche Avis Poursuite d’Études - Promotion 2023-2024', 0, 1, 'C');
        $this->Cell(0, 10, 'Département Informatique IUT Montpellier-Sète', 0, 1, 'C');
        $this->Ln(20);
    }

    function ChapterTitle($title): void
    {
        $this->SetFont('DejaVu', '', 14);
        $this->Cell(0, 10, $title, 0, 1, 'L');
        $this->Ln(5);
    }

    function ChapterBody($body): void
    {
        $this->SetFont('DejaVu', '', 12);
        $this->MultiCell(0, 10, $body);
        $this->Ln();
    }

    function AddStudentInfo($nom, $prenom, $parcours): void
    {
        $this->ChapterTitle('FICHE D’INFORMATION ÉTUDIANT(E)');
        $this->ChapterBody("NOM: $nom\nPrénom: $prenom\nApprentissage en BUT 3: non\nParcours BUT: $parcours");
    }

    function AddAvis($avisEcoleIngenieur, $avisMasterManagement): void
    {
        $this->ChapterTitle('Avis de l’équipe pédagogique pour la poursuite d’études après le BUT3');

        $this->SetFont('DejaVu', '', 12);
        $this->Cell(95, 10, 'En école d’ingénieur et master en informatique', 1);
        $this->Cell(95, 10, $avisEcoleIngenieur, 1);
        $this->Ln();
        $this->Cell(95, 10, 'En master en management', 1);
        $this->Cell(95, 10, $avisMasterManagement, 1);
        $this->Ln();
    }

    function AddAvisPromotion($ecoleIngenieurTF, $ecoleIngenieurF, $ecoleIngenieurR, $masterManagementTF, $masterManagementF, $masterManagementR): void
    {
        $this->Ln();
        $this->ChapterTitle('Nombre d’avis pour la promotion');

        $this->SetFont('DejaVu', '', 12);
        $this->Cell(63, 10, '', 1);
        $this->Cell(42, 10, 'Très Favorable', 1, 0, 'C');
        $this->Cell(42, 10, 'Favorable', 1, 0, 'C');
        $this->Cell(42, 10, 'Réservé', 1, 0, 'C');
        $this->Ln();

        $this->MultiCell(63, 10, 'En école d’ingénieur et master en informatique', 1);
        $this->SetXY($this->GetX() + 63, $this->GetY()-20);
        $this->Cell(42, 20, $ecoleIngenieurTF, 1, 0, 'C');
        $this->Cell(42, 20, $ecoleIngenieurF, 1, 0, 'C');
        $this->Cell(42, 20, $ecoleIngenieurR, 1, 0, 'C');
        $this->Ln();

        $this->MultiCell(63, 10, 'Master en management', 1);
        $this->SetXY($this->GetX() + 63, $this->GetY() - 10);
        $this->Cell(42, 10, $masterManagementTF, 1, 0, 'C');
        $this->Cell(42, 10, $masterManagementF, 1, 0, 'C');
        $this->Cell(42, 10, $masterManagementR, 1, 0, 'C');
        $this->Ln(20);
        $this->ChapterBody('Signature du Responsable des Poursuites d’études par délégation du chef de département');
    }
}