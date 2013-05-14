<?php

  // PDFmonthly.php - Creates the monthly report from the database
  // as a PDF.

include ('includes/classm.pdf.php');

function DocumentSetup($pdf,$Page_Width,$Page_Height,$Top_Margin,$Bottom_Margin,
		       $Left_Margin,$Right_Margin,$Month)
{
  $PageSize = array(0,0,$Page_Width,$Page_Height);
  $pdf = & new Cpdf($PageSize);

  $PageSize = array(0,0,$Page_Width,$Page_Height);
  $pdf = & new Cpdf($PageSize);

  $pdf->addinfo('Author','John J. McDonough ' . "WB8RCR");
  $pdf->addinfo('Creator','PDFmonthly.php $Revision: 1.4$');
  $pdf->SetKeywords('ARPSC, ARES, RACES, NTS, Michigan Section');
  $pdf->selectFont('helvetica');

  $pdf->addinfo('Title',_('Michigan ARPSC ') .  $Month  . _(' Report'));
  $pdf->addinfo('Subject',_('SEC/STM Combined Report') );

  return $pdf;

}

function NextPage($pdf,$PageNumber,$Page_Width,$Page_Height,$Top_Margin,$Bottom_Margin,
		 $Left_Margin,$Right_Margin,$Month)
{
  $PageNumber++;
  if ($PageNumber>1)
    {
      $pdf->newPage();
    }

  // Pale purple background on printable area
  //$pdf->SetFillColor(248,244,255);
  //$pdf->Rect($Left_Margin,$Top_Margin,$Page_Width-$Left_Margin-$Right_Margin,
//	     $Page_Height-$Top_Margin-$Bottom_Margin,'F');

  // Top line
  $pdf->SetTextColor(0,0,0);
  $pdf->selectFont('helvetica-Bold');
  $FontSize=18;
  $YPos = $Page_Height - $Top_Margin - 19;
  $XPos = $Page_Width/2 - 120;
  $pdf->addText($XPos,$YPos,$FontSize,_('Michigan Section ARPSC'));

  // Bottom line
  $FontSize=15;
  $YPos = $YPos - 18;
  $XPos = $Page_Width/2 - 80;
  $pdf->addText($XPos,$YPos,$FontSize,$Month);
  
  // Page Number
  $FontSize=8;
  $YPos = $Bottom_Margin + 2;
  if ( $PageNumber & 1 )
    $XPos = $Page_Width - 60;
  else
    $XPos = $Left_Margin + 10;
  $msg = 'Page ' . $PageNumber;
  $pdf->addText($XPos,$YPos,$FontSize,$msg);
  
  $FontSize=10;
  $pdf->selectFont('courier');
}

function centerText($pdf,$l,$r,$y,$fs,$text)
{
  $slen=$pdf->GetStringWidth($text);
  $XPos = round(($l+$r)/2 - $slen/2 );
  //$msg = 'Len ' . $slen . ', y=' . $y . ', text=' . $text;
  //$pdf->addText(200,300,10,$msg);
  //$msg = 'l,r=' . $l . ',' . $r . ', Pos ' . $XPos;
  //$pdf->addText(200,340,10,$msg);
  $pdf->addText($XPos,$y,$fs,$text);
}

function Header212($pdf,$Page_Width,$Left_Margin,$Right_Margin,$Yloc)
{
  $pdf->selectFont('helvetica-Bold');
  $FontSize=10;

  $YPos = $Yloc;
  $pdf->SetDrawColor(128,128,128);
  $pdf->SetLineWidth(1);
  $pdf->Line( $Left_Margin, $YPos, $Page_Width-$Right_Margin,$YPos);

  $L1 = $Yloc-12;
  $L2 = $Yloc-24;
  $L3 = $Yloc-36;

  $XPos = $Left_Margin;
  $YPos = $YLoc-18;
  centerText($pdf,$XPos,$XPos+32,$L2,$FontSize,'District');

  $XPos += 40;
  centerText($pdf,$XPos,$XPos+120,$L2,$FontSize,'Jurisdiction');

  $XPos += 120;
  centerText($pdf,$XPos,$XPos+40,$L1,$FontSize,'Monthly');
  centerText($pdf,$XPos,$XPos+40,$L2,$FontSize,'Man');
  centerText($pdf,$XPos,$XPos+40,$L3,$FontSize,'Hours');

  $XPos += 40;
  centerText($pdf,$XPos,$XPos+40,$L1,$FontSize,'Contrib');
  centerText($pdf,$XPos,$XPos+40,$L2,$FontSize,'Dollar');
  centerText($pdf,$XPos,$XPos+40,$L3,$FontSize,'Value');

  $XPos += 40;
  centerText($pdf,$XPos,$XPos+40,$L1,$FontSize,'Total #');
  centerText($pdf,$XPos,$XPos+40,$L2,$FontSize,'ARES');
  centerText($pdf,$XPos,$XPos+40,$L3,$FontSize,'mbrs');

  $XPos += 40;
  centerText($pdf,$XPos,$XPos+40,$L2,$FontSize,'Change');

  $XPos += 40;
  centerText($pdf,$XPos,$XPos+78,$L1,$FontSize,'Net');
  centerText($pdf,$XPos,$XPos+39,$L2-5,$FontSize,'Num');
  centerText($pdf,$XPos+39,$XPos+78,$L2,$FontSize,'Man');
  centerText($pdf,$XPos+39,$XPos+78,$L3,$FontSize,'Hours');

  $XPos += 78;
  centerText($pdf,$XPos,$XPos+78,$L1,$FontSize,'Public Service');
  centerText($pdf,$XPos,$XPos+39,$L2-5,$FontSize,'Num');
  centerText($pdf,$XPos+39,$XPos+78,$L2,$FontSize,'Man');
  centerText($pdf,$XPos+39,$XPos+78,$L3,$FontSize,'Hours');

  $XPos += 78;
  centerText($pdf,$XPos,$XPos+78,$L1,$FontSize,'Emergency');
  centerText($pdf,$XPos,$XPos+39,$L2-5,$FontSize,'Num');
  centerText($pdf,$XPos+39,$XPos+78,$L2,$FontSize,'Man');
  centerText($pdf,$XPos+39,$XPos+78,$L3,$FontSize,'Hours');

  $pdf->Line( $Left_Margin, $L3-3, $Page_Width-$Right_Margin, $L3-3);
  $pdf->Line( $Left_Margin, $Yloc, $Left_Margin, $L3-3 );
  $pdf->Line( $Page_Width-$Right_Margin, $Yloc, $Page_Width-$Right_Margin, $L3-3 );
  $pdf->Line( $Left_Margin+40, $Yloc, $Left_Margin+40, $L3-3 );
  $pdf->Line( $Left_Margin+160, $Yloc, $Left_Margin+160, $L3-3 );
  $pdf->Line( $Left_Margin+200, $Yloc, $Left_Margin+200, $L3-3 );
  $pdf->Line( $Left_Margin+240, $Yloc, $Left_Margin+240, $L3-3 );
  $pdf->Line( $Left_Margin+280, $Yloc, $Left_Margin+280, $L3-3 );
  $pdf->Line( $Left_Margin+320, $Yloc, $Left_Margin+320, $L3-3 );
  $pdf->Line( $Left_Margin+399, $Yloc, $Left_Margin+399, $L3-3 );
  $pdf->Line( $Left_Margin+478, $Yloc, $Left_Margin+478, $L3-3 );
  $pdf->Line( $Left_Margin+320, $L1-3, $Page_Width-$Right_Margin, $L1-3 );
  $pdf->Line( $Left_Margin+359, $L1-3, $Left_Margin+359, $L3-3 );
  $pdf->Line( $Left_Margin+438, $L1-3, $Left_Margin+438, $L3-3 );
  $pdf->Line( $Left_Margin+517, $L1-3, $Left_Margin+517, $L3-3 );

  $pdf->selectFont('helvetica');
}

include('includes/session.inc');
$title=_('Michigan Section FSD-212');

include('includes/miscFunctions.inc');
date_default_timezone_set('UTC');
// Remember the launch time
$starttime = strftime("%A, %B %d %Y, %H:%M");

// Open the database
$db = mysql_connect($host , $dbuser, $dbpassword);
mysql_select_db($DatabaseName,$db);

$period=92;

    // Get the requested period, if blank choose the latest
    $period = $_GET['period'];
    if ( $period < 1 )
    {
        $SQL="SELECT MAX(`period`) FROM `arpsc_ecrept`";
        $period = singleResult($SQL,$db);;
    }

    // Display the month name for this report
    $SQL = 'SELECT lastday FROM `periods` WHERE `periodno`=' . $period;
    $usedate=singleResult($SQL,$db);

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

//==============================================================================
//               FSD-96
//==============================================================================

$PageNumber = 0;
NextPage($pdf,$PageNumber,$Page_Width,$Page_Height,$Top_Margin,$Bottom_Margin,
	 $Left_Margin,$Right_Margin,$Month);

$pdf->SetDrawColor(192,192,192);
$pdf->SetLineWidth( 2 );
$YPos = $Page_Height-$Top_Margin-70;
$pdf->Line( $Left_Margin, $YPos, $Page_Width, $YPos);

$YPos -= 30;
/*
$msg = "ARRL Section: Michigan        Month: " . $Month . "                  ";
centerText($pdf, $Left_Margin, $Page_Width,
	   $YPos, 12, $msg);
*/
$YPos -= 25;
$pdf->Line( $Left_Margin, $YPos, $Page_Width ,$YPos);

$YPos -= 30;
/*
$msg = "AMATEUR RADIO EMERGENCY SERVICE";
$pdf->SelectFont("helvetica-Bold");
centerText($pdf,$Left_Margin,$Page_Width,
	   $YPos,12,$msg);
*/
$YPos -= 25;
$pdf->Line( $Left_Margin, $YPos, $Page_Width, $YPos);

$StartY = $YPos - 40;
$XPos = $Left_Margin + 10;
$pdf->SelectFont("helvetica");
$FontSize = 10;
/*
$YPos = $StartY;
$pdf->addText($XPos,$YPos,$FontSize,"Total number of ARES members");
$YPos -= 14;
$pdf->addText($XPos,$YPos,$FontSize,"# ECs reporting");
$YPos -= 14;
$pdf->addText($XPos,$YPos,$FontSize,"Drills, tests and training sessions");
$YPos -= 14;
$pdf->addText($XPos,$YPos,$FontSize,"Public Service Events");
$YPos -= 14;
$pdf->addText($XPos,$YPos,$FontSize,"Emergency Operations");
$YPos -= 14;
$pdf->addText($XPos,$YPos,$FontSize,"Total number of ARES ops");

$YPos = $StartY;
$XPos = $Page_Width/2;
$pdf->addText($XPos,$YPos,$FontSize,"Change since last month");
$YPos -= 14;
$pdf->addText($XPos,$YPos,$FontSize,"# ARES nets");
$YPos -= 14;
$pdf->addText($XPos,$YPos,$FontSize,"Person hours");
$YPos -= 14;
$pdf->addText($XPos,$YPos,$FontSize,"Person hours");
$YPos -= 14;
$pdf->addText($XPos,$YPos,$FontSize,"Person hours");
$YPos -= 14;
$pdf->addText($XPos,$YPos,$FontSize,"Person hours");
*/

// Go get the data
include ('FSD96data.inc');

// Display the data
/*$X1 = $Page_Width/2 -60;
$X2 = $Page_Width/2 -20;
$YPos = $StartY;
centerText($pdf,$X1,$X2,$YPos,$FontSize, $garesmem );
$YPos -= 14;
centerText($pdf,$X1,$X2,$YPos,$FontSize, $numecs );
$YPos -= 14;
centerText($pdf,$X1,$X2,$YPos,$FontSize, $gnetsess );
$YPos -= 14;
centerText($pdf,$X1,$X2,$YPos,$FontSize, $gpsnum );
$YPos -= 14;
centerText($pdf,$X1,$X2,$YPos,$FontSize, $gemnum );
$YPos -= 14;
centerText($pdf,$X1,$X2,$YPos,$FontSize, round($gnetsess+$gpsnum+$gemnum) );
*/
/*
$X1 = $Page_Width - $Right_Margin -60;
$X2 = $Page_Width - $Right_Margin -20;
$YPos = $StartY;
centerText($pdf,$X1,$X2,$YPos,$FontSize, $gareschg );
$YPos -= 14;
centerText($pdf,$X1,$X2,$YPos,$FontSize, $numnets );
$YPos -= 14;
centerText($pdf,$X1,$X2,$YPos,$FontSize, round($gnethrs) );
$YPos -= 14;
centerText($pdf,$X1,$X2,$YPos,$FontSize, round($gpshrs) );
$YPos -= 14;
centerText($pdf,$X1,$X2,$YPos,$FontSize, round($gemhrs) );
$YPos -= 14;
centerText($pdf,$X1,$X2,$YPos,$FontSize, round($gmanhrs) );
*/


//==============================================================================
//               FSD-212
//==============================================================================

$PageNumber++;
NextPage($pdf,$PageNumber,$Page_Width,$Page_Height,$Top_Margin,$Bottom_Margin,
	 $Left_Margin,$Right_Margin,$Month);

Header212($pdf,$Page_Width,$Left_Margin,$Right_Margin,$Page_Height-$Top_Margin-50);


$YLoc = $Page_Height-$Top_Margin-90;
$YPos = $YLoc;
$pdf->SelectFont("helvetica");
$FontSize = 10;

include('FSD212summ.inc');

include('FSD212detail.inc');



$PageNumber++;
NextPage($pdf,$PageNumber,$Page_Width,$Page_Height,$Top_Margin,$Bottom_Margin,
	 $Left_Margin,$Right_Margin,$Month);

include('NTSnet.inc');
include('NTSsar.inc');

$PageNumber++;
NextPage($pdf,$PageNumber,$Page_Width,$Page_Height,$Top_Margin,$Bottom_Margin,
	 $Left_Margin,$Right_Margin,$Month);

$YPos = $Page_Height-$Top_Margin-37;

include('NTSpshr.inc');
include('NTSbpl.inc');


//-- $pdf->SetFillColor(255,255,192);
//-- $pdf->Rect($Left_Margin,300,$Page_Width-$Left_Margin-$Right_Margin,25,'F');
//-- $pdf->SetFillColor(192,255,192);
//-- $pdf->Rect(300,325,100,25,'F');

//-- $FontSize = 12;
//-- $pdf->SetTextColor(0,64,128);
//-- $pdf->addTextWrap($Left_Margin+65,600,300,$FontSize,'String of Text', 'left');

//-- $pdf->SetTextColor(0,128,64);
//-- $pdf->addTextWrap($Left_Margin+65,500,150,$FontSize,'Another String of Text', 'left');

//-- $pdf->SetTextColor(64,0,128);
//-- $pdf->addTextWrap($Left_Margin+65,400,200,$FontSize,'This text on page 5', 'left');


$FontSize = 8;
$pdf->SetTextColor(128,128,128);
//$XPos = $Page_Width - $Right_Margin - 60;
$XPos = $Left_Margin + 3;
$YPos = $Bottom_Margin + 34;
$pdf->addText($XPos,$YPos,$FontSize,"Requested: " . $starttime . "Z");
$YPos -= 10;
$pdf->addText($XPos,$YPos,$FontSize,"Most recent data: " . $maxdate . "E");
$YPos -= 10;
$pdf->addText($XPos,$YPos,$FontSize,"\$Revision: 1.4 $ - \$Date: 2013-05-14 15:32:50-04 \$");
$YPos -= 10;
$pdf->addText($XPos,$YPos,$FontSize,"copyright (c) 2013, Michigan Section, American Radio Relay League");


$buf = $pdf->output();
$len += strlen($buf);

header('Content-type: application/pdf');
header('Content-Length: ' . $len);
header('Content-Disposition: inline; filename=' . $DateShort . '-ARPSC.pdf');
header('Expires: 0');
header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
header('Pragma: public');

$pdf->stream()

?>
