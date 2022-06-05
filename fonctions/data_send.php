<?php
require('pdf/fpdf184/fpdf.php');
include('qrcode/qrlib.php');

        $dbname = 'tcce_bd';
        $DB_DSN = 'mysql:host=localhost;dbname=' . $dbname;
        $DB_USER = 'root';
        $DB_PASSWD = '';
        $options = [
            PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8',
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
            ];
 
            $PDO = new PDO($DB_DSN, $DB_USER, $DB_PASSWD, $options);

//require('image/70.jpg');
//if(isset($_GET['submit'])){    recuperer les donnees du formulaire en utilisant la valeur des attributs name comme cle

$cni_expediteur=$_POST['cni_expediteur'];
$nom_expediteur=$_POST['name_expediteur'];
$tel_expediteur=$_POST['tel_expediteur'];
$ville_expediteur=$_POST['ville_expediteur'];
$nom_colis=$_POST['name_colis'];
$poids_colis=$_POST['poids_colis'];
$valeur_colis=$_POST['valeur_colis'];
$prix_colis=$_POST['prix'];
$cni_recepteur=$_POST['cni_recepteur'];
$nom_recepteur=$_POST['nom_recepteur'];
$tel_recepteur=$_POST['tel_recepteur'];
$ville_recepteur=$_POST['ville_recepteur'];
$date=date('m-d-y H:i:s a',time());

$lettre='ABCDEFGHIJKLMNOPQRSTUVWXYZ';
$create=str_shuffle($lettre);
$create=substr($create, 1,2);
$generated = date('dmy') .'/'. $create . rand(0, 9) . rand(0, 9) . rand(0, 9);
// $generated = date('dmy') .'/'. $create . rand(0, 9) . rand(0, 9) . rand(0, 9);
//echo $generated."/ ".$date."/ ".$cni_expediteur;
   

           /* $matricules = $PDO->query("SELECT reference FROM colis")->fetchAll(PDO::FETCH_COLUMN);
        if(empty($matricules)){
             $generated = date('dmy') .'/'. $create . rand(0, 9) . rand(0, 9) . rand(0, 9);
            return $generated;
        }
        $test = true;
        while($test){
             $generated = date('dmy') .'/'. $create . rand(0, 9) . rand(0, 9) . rand(0, 9);;
            foreach($matricules as $matricule){
                if($matricule === $generated){
                    $test = true;
                break;
                }
                else{
                    $test = false;
                }
QRcode :: png($generated,"test.png");            }
        }*/



class PDF extends FPDF
{
// En-tête
function Header()
{
    // Logo
    //$this->Image('image/70.jpg',90,6,15);
    // Police Arial gras 15
     $this->Ln(5);
    $this->SetFont('Arial','B',15);
    // Décalage à droite
    $this->Cell(50);
    // Titre
    $this->Cell(80,10,'SOLO Colis et Couriel Express',1,0,'C');
    // Saut de ligne
    $this->Ln(15);
}

// Pied de page
function Footer()
{
    // Positionnement à 1,5 cm du bas
    $this->SetY(-15);
    // Police Arial italique 8
    $this->SetFont('Arial','I',8);
    // Numéro de page
    $this->Cell(0,10,'Page '.$this->PageNo().'/{nb}',0,0,'C');
}
function TitreChapitre($ville_expediteur, $ville_recepteur)
{
    // Arial 12
    $this->SetFont('Arial','',12);
    // Couleur de fond
    $this->SetFillColor(200,220,255);
    // Titre
    $this->Cell(0,6,"Chapitre $ville_recepteur: $ville_recepteur",0,1,'L',true);
    // Saut de ligne
    $this->Ln(4);
}
function LoadData($file)
{
    // Lecture des lignes du fichier
    $lines = file($file);
    $data = array();
    foreach($lines as $line)
        $data[] = explode(';',trim($line));
    return $data;
}

}

// Instanciation de la classe dérivée
$pdf = new PDF('L','mm','A5');
$pdf->AliasNbPages();
$pdf->AddPage();
$pdf->SetFont('Times','',40);
$pdf->Cell(0,10,$ville_expediteur.">>>".$ville_recepteur."\n",0,1,'C');
$pdf->Ln(10);
$pdf->SetFont('Times','B',15);

$pdf->Image('test.png',10,50,60,40,'PNG');
$pdf->Cell(60);
$pdf->MultiCell(130,10,"Reference Colis : \n".$generated."\n".$nom_recepteur ."\n Tel. : ".$tel_recepteur,1,'C');
$x = $pdf->getX();
$y = $pdf->getY();
$pdf->SetFont('Times','',12);
$pdf->MultiCell(60,10,"Expediteur/Tel",1,'C');
$pdf->setX($x);
$pdf->setY($y);
$pdf->Cell(60);
$pdf->MultiCell(130,10,$nom_expediteur."/".$tel_expediteur,1,'C');
$pdf->Ln(5);
$pdf->SetFont('Times','B',40);
$pdf->MultiCell(0,10,$ville_recepteur."/\n".$ville_recepteur,0,'C');
$pdf->Cell(0,10,$date,1,1);


//$pdf->Cell(0,10,$nom_expediteur,1,1);
//$pdf->Cell(0,10,$tel_expediteur,1,1);

//$pdf->Cell(0,10,$nom_colis,1,1);
//$pdf->Cell(0,10,$poids_colis,1,1);
//$pdf->Cell(0,10,$valeur_colis,1,1);
//$pdf->Cell(0,10,$prix_colis,1,1);
//$pdf->Cell(0,10,$cni_recepteur,1,1);
//$pdf->Cell(0,10,$nom_recepteur,1,1);
//$pdf->Cell(0,10,$tel_recepteur,1,1);

//$pdf->Cell(0,10,$date,1,1);

$pdf->Output();


?>
?>