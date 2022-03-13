<?php
require('../common/fpdf/fpdf.php');
class PDF extends FPDF
{
    private $propositions=array();
    private $sens=array();
    private $corrections=array();
    private $reponses=array();
    private $reussites;
    private $prenom='Anonyme';
    private $decimales;
    private $max;

    public function setBrevet($prenom, $decimales, $max, $sens, $propositions, $corrections, $reponses)
    {
        $this->propositions=$propositions;
        $this->sens=$sens;
        $this->corrections=$corrections;
        $this->reponses=$reponses;
        $this->reussites=0;
        $this->prenom=$prenom;
        $this->decimales=$decimales;
        $this->max=$max;
        foreach ($corrections as $i => $nombres) {
            if ($nombres==$reponses[$i]) {
                $this->reussites++;
            }
        }
    }
    function Header()
    {
        $this->SetFont('Arial', 'B', 20);
        $this->Cell(0, 10, utf8_decode('Ordonner les nombres'), 'LTR', 1, 'C');
        $this->SetFont('Arial', '', 14);
        $titre = ($this->decimales==0) ? 'Nombres entiers < '.$this->max : 'Nombres décimaux dont la partie décimale est composée de '.$this->decimales.' chiffres';
        $this->Cell(0, 10, utf8_decode($titre), 'LBR', 1, 'C');
        $this->SetFont('Arial', 'B', 16);
        $this->Cell(0, 8, utf8_decode('Résultats de ' . $this->prenom), 0, 1, 'C');
        $this->SetFont('Arial', '', 14);
        $this->Cell(0, 8, '(' . date('d/m/Y') . ')', 0, 1, 'C');
        $this->Ln();

        $this->SetFont('Arial', 'B', 14);
        $esse = ($this->reussites > 1) ? 's' : '';
        $nbQuestions=count($this->propositions);
        $this->SetTextColor(0, 155, 0);
        $this->Cell(0, 10, utf8_decode('Tu as trié sans erreur ' . $this->reussites . ' liste' . $esse . ' de nombres sur ' . $nbQuestions . '.'), 1, 1, 'C');
        foreach ($this->corrections as $i => $nombres) {
            $sens = ($this->sens[$i] == 'true') ? 'croissant' : utf8_decode('décroissant');
            $signe = ($this->sens[$i] == 'true') ? ' < ' : ' > ';
            if ($nombres==$this->reponses[$i]) {
                $this->Ln(1);
                $liste=explode(':', $nombres);
                foreach ($liste as $i => $nombre) {
                    $liste[$i] = number_format($nombre, $this->decimales, ',', ' ');
                }
                $this->Cell(0, 8, 'Tri ' . $sens . ' : ' . implode($signe, $liste), 0, 1, 'L');
            }
        }

        if ($this->reussites < $nbQuestions) {
            $this->Ln();
            $this->SetTextColor(255, 0, 0);
            $esse = ($this->reussites < ($nbQuestions - 2)) ? 's' : '';
            $this->Cell(0, 10, utf8_decode('Et tu as fait des erreurs sur ' . ($nbQuestions-$this->reussites) . ' liste' . $esse . ' de nombres.'), 1, 1, 'C');
            foreach ($this->corrections as $i => $nombres) {
                $sens = ($this->sens[$i] == 'true') ? 'croissant' : utf8_decode('décroissant');
                $signe = ($this->sens[$i] == 'true') ? ' < ' : ' > ';
                if ($nombres!=$this->reponses[$i]) {
                    $this->Ln(1);
                    $this->SetTextColor(255, 0, 0);
                    $liste=explode(':', $this->reponses[$i]);
                    foreach ($liste as $i => $nombre) {
                        $liste[$i] = number_format($nombre, $this->decimales, ',', ' ');
                    }
                    $this->Cell(0, 8, 'Erreur de tri ' . $sens . ' : ' . implode($signe, $liste), 0, 1, 'L');
                    $this->SetTextColor(0, 155, 0);
                    $liste=explode(':', $nombres);
                    foreach ($liste as $i => $nombre) {
                        $liste[$i]=number_format($nombre, $this->decimales, ',', ' ');
                    }
                    $this->Cell(0, 8, utf8_decode('... à la place de : ') . implode($signe, $liste), 0, 1, 'R');
                }
            }
        }
    }
    function Footer()
    {
        $url='http://maclasseweb.fr/';
        $this->SetFont('Arial', 'B', 12);
        $wUrl=$this->GetStringWidth($url);

        $this->SetXY(10, -15);
        $this->Cell(0, 5, 'http://maclasseweb.fr', 1, 0, 'C');
        $this->Link((190-$wUrl)/2, -15, $wUrl, 5, $url);
    }

}
ob_clean();
$prenom = (isset($_POST['prenom'])) ? trim($_POST['prenom']) : exit;
$decimales = (isset($_POST['decimales'])) ? trim($_POST['decimales']) : exit;
$max = (isset($_POST['max'])) ? trim($_POST['max']) : exit;
$propositions = (isset($_POST['propositions'])) ? explode('|', trim($_POST['propositions'])) : exit;
$sens = (isset($_POST['sens'])) ? explode('|', trim($_POST['sens'])) : exit;
$corrections = (isset($_POST['corrections'])) ? explode('|', trim($_POST['corrections'])) : exit;
$reponses = (isset($_POST['reponses'])) ? explode('|', trim($_POST['reponses'])) : exit;

// echo '<pre>';
// var_dump($tri);
// echo '</pre>';

$pdf=new PDF();
$pdf->setBrevet($prenom, $decimales, $max, $sens, $propositions, $corrections, $reponses);
$pdf->AddPage();
$pdf->Output('brevet.pdf', 'D');