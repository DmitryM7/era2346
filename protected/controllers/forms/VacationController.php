<?php

class VacationController extends Controller
{
	public function actionIndex()
	{
		$pdf = Yii::createComponent('application.extensions.tcpdf.ETcPdf','P', 'cm', 'A4', true, 'UTF-8');

		$pdf->SetCreator(PDF_CREATOR);
		$pdf->SetAuthor("Nicola Asuni");
		$pdf->SetTitle("TCPDF Example 002");
		$pdf->SetSubject("TCPDF Tutorial");
		$pdf->SetKeywords("TCPDF, PDF, example, test, guide");
		$pdf->setPrintHeader(false);
		$pdf->setPrintFooter(false);
		$pdf->AliasNbPages();
		$pdf->AddPage();
		$pdf->SetFont("dejavusanscondensed", "", 10);

		$aaa = file_get_contents(dirname(__FILE__)."/Otpusk.htm");

		$pdf->writeHTML($aaa,true,false,true,false,'');		
		$pdf->Output("example_002.pdf", "I");


	}

	// Uncomment the following methods and override them if needed
	/*
	public function filters()
	{
		// return the filter configuration for this controller, e.g.:
		return array(
			'inlineFilterName',
			array(
				'class'=>'path.to.FilterClass',
				'propertyName'=>'propertyValue',
			),
		);
	}

	public function actions()
	{
		// return external action classes, e.g.:
		return array(
			'action1'=>'path.to.ActionClass',
			'action2'=>array(
				'class'=>'path.to.AnotherActionClass',
				'propertyName'=>'propertyValue',
			),
		);
	}
	*/
       public function actionIndex2() {
	require_once('/var/www/localwww2/protected/extensions/MPDF/mpdf.php');

	$html=file_get_contents(dirname(__FILE__)."/index3.tpl");

	$html=str_replace("#dd#",date("d"),$html);
	$html=str_replace("#mm#",date("m"),$html);
	$html=str_replace("#yyyy#",date("Y"),$html);



	$css=file_get_contents(dirname(__FILE__)."/index2.css");

	$mpdf = new mPDF('utf-8','A4','8','',20,1,10,10,1,1);
	$mpdf->charset_in = 'utf-8';

	header('Content-type: application/pdf');
	$mpdf->WriteHTML($css,1);
	$mpdf->WriteHTML($html,2);
	$mpdf->Output('mpdf.pdf','I');


       }
     public function actionTest() {

		$pdf = Yii::createComponent('application.extensions.tcpdf.ETcPdf','P', 'cm', 'A4', true, 'UTF-8');

// set document information
$pdf->SetCreator(PDF_CREATOR);
$pdf->SetAuthor('Nicola Asuni');
$pdf->SetTitle('TCPDF Example 058');
$pdf->SetSubject('TCPDF Tutorial');
$pdf->SetKeywords('TCPDF, PDF, example, test, guide');

// set default header data
$pdf->SetHeaderData(PDF_HEADER_LOGO, PDF_HEADER_LOGO_WIDTH, PDF_HEADER_TITLE.' 058', PDF_HEADER_STRING);

// set header and footer fonts
$pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
$pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));

// set default monospaced font
$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

//set margins
$pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
$pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
$pdf->SetFooterMargin(PDF_MARGIN_FOOTER);

//set auto page breaks
$pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);

//set image scale factor
$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

//set some language-dependent strings
//$pdf->setLanguageArray($l);

// ---------------------------------------------------------

// set font
$pdf->SetFont('helvetica', '', 10);

// add a page
$pdf->AddPage();

// NOTE: Uncomment the following line to rasterize SVG image using the ImageMagick library.
//$pdf->setRasterizeVectorImages(true);

$pdf->ImageSVG($file=dirname(__FILE__).'/BNK-CL(2145)(8741109).svg', $x=15, $y=30, $w='', $h='', $link='http://www.tcpdf.org', $align='', $palign='', $border=1, $fitonpage=false);

/*$pdf->ImageSVG($file='../images/tux.svg', $x=30, $y=100, $w='', $h=100, $link='', $align='', $palign='', $border=0, $fitonpage=false);*/

$pdf->SetFont('helvetica', '', 8);
/*$pdf->SetY(195);*/
$txt = '© The copyright holder of the above Tux image is Larry Ewing, allows anyone to use it for any purpose, provided that the copyright holder is properly attributed. Redistribution, derivative work, commercial use, and all other use is permitted.';
$pdf->Write(0, $txt, '', 0, 'L', true, 0, false, false, 0);

// ---------------------------------------------------------

//Close and output PDF document
$pdf->Output('example_058.pdf', 'I');


	
      }

public function actionIndex3() {
 	require_once('/var/www/localwww2/protected/extensions/tcpdf/tcpdf.php');
	require_once('/var/www/localwww2/protected/extensions/fpdi/fpdi.php');

     $pdf = &new FPDI();
     $pdf->AddPage();
     $pdf->setSourceFile(dirname(__FILE__).'/1.pdf'); 
     $tplIdx = $pdf->importPage(1);   
      $pdf->useTemplate($tplIdx);   

// now write some text above the imported page   
/*$pdf->SetFont('Arial');   */
$pdf->SetTextColor(255,0,0);   
$pdf->SetXY(25, 75);   
$pdf->Write(0, "This is just a simple text");   

$pdf->Output('newpdf.pdf', 'D'); 


}
}