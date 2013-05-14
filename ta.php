<?php

include ('includes/class.pdf.php');

function DocumentSetup($pdf,$Page_Width,$Page_Height,$Top_Margin,$Bottom_Margin,
		       $Left_Margin,$Right_Margin,$Month)
{
  $PageSize = array(0,0,$Page_Width,$Page_Height);
  $pdf = & new Cpdf($PageSize);

  $PageSize = array(0,0,$Page_Width,$Page_Height);
  $pdf = & new Cpdf($PageSize);


  $pdf->addinfo('Author','John J. McDonough ' . "WB8RCR");
  $pdf->addinfo('Creator','PDFmonthly.php $Revision: 1.1$');
  $pdf->SetKeywords('ARPSC, ARES, RACES, NTS, Michigan Section');
  $pdf->selectFont('helvetica');

  $pdf->addinfo('Title',_('Michigan ARPSC ') .  $Month  . _(' Report'));
  $pdf->addinfo('Subject',_('SEC/STM Combined Report') );

  return $pdf;

}

function convertDate( $str )
{
    $mn[0] = "???";
    $mn[1] = "January";
    $mn[2] = "February";
    $mn[3] = "March";
    $mn[4] = "April";
    $mn[5] = "May";
    $mn[6] = "June";
    $mn[7] = "July";
    $mn[8] = "August";
    $mn[9] = "September";
    $mn[10] = "October";
    $mn[11] = "November";
    $mn[12] = "December";
    $year = substr($str,0,4);
    $mm = 0;
    $mm = substr($str,5,2) + $mm;

    return $mn[$mm] . ", " . $year;
}


$Month = convertDate($usedate);
$yr=substr($usedate,2,2);
$mn=substr($usedate,5,2);
$DateShort=$yr . $mn;
//$DateShort=convertDateShort($usedate);

$PaperSize = 'letter';
$Page_Width=612;
$Page_Height=792;
$Top_Margin=30;
$Bottom_Margin=57;
$Left_Margin=30;
$Right_Margin=25;

$pdf = DocumentSetup($pdf,$Page_Width,$Page_Height,$Top_Margin,$Bottom_Margin,
		     $Left_Margin,$Right_Margin,$Month);




header('Content-type: application/pdf');
header('Content-Length: ' . $len);
header('Content-Disposition: inline; filename=' . $DateShort . '-ARPSC.pdf');
header('Expires: 0');
header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
header('Pragma: public');

$FontSize = 12;
$pdf->SetTextColor(128,128,128);

$XPos = $Left_Margin + 3;
$YPos = $Bottom_Margin + 34;
$pdf->addText($XPos,$YPos,$FontSize,"Requested: " . $starttime . "Z");
$YPos -= 10;

$pdf->stream()

?>
