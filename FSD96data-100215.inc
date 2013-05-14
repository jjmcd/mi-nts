<?php
//    index.php
//    $Revision: 1.1 $ - $Date: 2007-11-07 21:11:38-05 $
//
//    index is the opening page of the mi-nts website.  It displays the
//    standard menu, and then only some text introducing the site.
//

  // Page heading
$pdf->SetDrawColor(192,192,192);
$pdf->SetLineWidth( 2 );
$YPos = $Page_Height-$Top_Margin-70;
$pdf->Line( $Left_Margin, $YPos, $Print_Width,$YPos);

$YPos -= 30;
$msg = "ARRL Section: Michigan        Month: " . $Month . "                  ";
centerText($pdf,$Left_Margin,$Page_Width-$Right_Margin,
	   $YPos,12,$msg);
$YPos -= 25;
$pdf->Line( $Left_Margin, $YPos, $Print_Width,$YPos);

$YPos -= 30;
$msg = "AMATEUR RADIO EMERGENCY SERVICE";
$pdf->SelectFont("helvetica-Bold");
centerText($pdf,$Left_Margin,$Page_Width-$Right_Margin,
	   $YPos,12,$msg);
$YPos -= 25;
$pdf->Line( $Left_Margin, $YPos, $Print_Width,$YPos);


// Loop through districts
$q1='SELECT `districtkey`, `district_code` from `arpsc_districts` ORDER BY `district_code`';
$r1=getResult($q1,$db);

$garesmem=0;
$gareschg=0;
$gnetsess=0;
$gnethrs =0;
$gpsnum  =0;
$gpshrs  =0;
$gemnum  =0;
$gemhrs  =0;
$gmanhrs =0;
$gvalue  =0;
$numecs = 0;
$numnet = 0;

while ( $row1 = getRow($r1,$db) )
{
    $district=$row1[1];
    $manhrs=0;
    $aresmem=0;
    $areschg=0;
    $netsess=0;
    $nethrs=0;
    $psnum = 0;
    $pshrs = 0;
    $emnum = 0;
    $emhrs = 0;
    $adhrs = 0;

    // Get data for district staff
    $key1 = "D" . $district;
    $q5='SELECT `aresmem`,`drillsnum`,`drillshrs`,`psesnum`,`pseshrs`,`eopsnum`,`eopshrs`,`aresopsnum`,`aresops`, `updated` ' .
		"FROM `arpsc_ecrept` WHERE `county`='" . $key1 . "' AND `period`=" . $period;
    $r5=getResult($q5,$db);

    if ( $row5 = getRow($r5,$db) )
	{
		    $hours = $row5[2]+$row5[4]+$row5[6]+$row5[8];
		    $value = $hours * $Volunteer_Rate;
		    $aresmem=$row5[0];
		    $netsess=$row5[1];
		    $nethrs= $row5[2];
		    $psnum = $row5[3];
		    $pshrs = $row5[4];
		    $emnum = $row5[5];
		    $emhrs = $row5[6];
		    $adhrs = $row5[8];
	}

    $q2="SELECT `countyname`, `countycode` FROM `arpsc_counties` WHERE `district`='" .
	$row1[0] . "' ORDER BY `countyname`";

    $r2=getResult($q2,$db);
    while ( $row2 = getRow($r2,$db) )
	{
	    $q3='SELECT `aresmem`,`drillsnum`,`drillshrs`,`psesnum`,`pseshrs`,`eopsnum`,`eopshrs`,`aresopsnum`,`aresops`, `updated` ' .
		"FROM `arpsc_ecrept` WHERE `county`='" . $row2[1] . "' AND `period`=" . $period;
	    $r3=getResult($q3,$db);

	    if ( $row3 = getRow($r3,$db) )
		{
		    $hours = $row3[2]+$row3[4]+$row3[6]+$row3[8];
		    $value = $hours * $Volunteer_Rate;
		    $lastperiod = $period-1;
		    $q4="SELECT `aresmem` FROM `arpsc_ecrept` WHERE `county`='" . $row2[1] . 
			"' AND `period`=" . $lastperiod;
		    $r4=getResult($q4,$db);
		    if ( $row4 = getRow($r4,$db) )
			{
			    $change = $row3[0]-$row4[0];
			}
		    else
			{
			    $q4="SELECT `aresmem` FROM `arpsc_ecrept` WHERE `county`='" . $row2[1] . 
				"' AND `period`=0";
			    $r4=getResult($q4,$db);
			    if ( $row4 = getRow($r4,$db) )
				{
				    $change = $row3[0]-$row4[0];
				}
			    else
				{
				    $change = 0;
				}
			}
		    $aresmem=$aresmem+$row3[0];
		    $areschg=$areschg+$change;
		    $netsess=$netsess+$row3[1];
		    $nethrs=$nethrs+$row3[2];
		    $psnum = $psnum+$row3[3];
		    $pshrs = $pshrs+$row3[4];
		    $emnum = $emnum+$row3[5];
		    $emhrs = $emhrs+$row3[6];
		    $adhrs = $adhrs+$row3[8];
		    $numecs = $numecs +1;
		    if ( $netsess > 1 )
			$numnets = $numnets + 1;

		    if ( $row3[9] > $maxdate )
		    {
			$maxdate = $row3[9];
		    }
		}
	    else
		{
		    $q3='SELECT `aresmem` ' .
			"FROM `arpsc_ecrept` WHERE `county`='" . $row2[1] . "' AND `period`=0";
		    $r3=getResult($q3,$db);
		    if ( $row3 = getRow($r3,$db) )
			{
			    $aresmem=$aresmem+$row3[0];
			}
		}
	}
    $manhrs = $nethrs + $pshrs + $emhrs + $adhrs;
    $value = $manhrs * $Volunteer_Rate;

    $garesmem=$garesmem+$aresmem;
    $gareschg=$gareschg+$areschg;
    $gnetsess=$gnetsess+$netsess;
    $gnethrs =$gnethrs +$nethrs ;
    $gpsnum  =$gpsnum  +$psnum  ;
    $gpshrs  =$gpshrs  +$pshrs  ;
    $gemnum  =$gemnum  +$emnum  ;
    $gemhrs  =$gemhrs  +$emhrs  ;
    $gmanhrs =$gmanhrs +$manhrs ;
    $gvalue  =$gvalue  +$value  ;
}
// Display the data
$StartY = $YPos - 40;
$pdf->SelectFont("helvetica");
$FontSize = 10;

$XPos = $Left_Margin + 10;
$X1 = $Page_Width/2 -60;
$YPos = $StartY;
$pdf->addText($XPos,$YPos,$FontSize,"Total number of ARES members");
$pdf->addTextWrap($X1,$YPos,40,$FontSize,$garesmem,'right');
$YPos -= 14;
$pdf->addText($XPos,$YPos,$FontSize,"# ECs reporting");
$pdf->addTextWrap($X1,$YPos,40,$FontSize,$numecs,'right');
$YPos -= 14;
$pdf->addText($XPos,$YPos,$FontSize,"Drills, tests and training sessions");
$pdf->addTextWrap($X1,$YPos,40,$FontSize,$gnetsess,'right');
$YPos -= 14;
$pdf->addText($XPos,$YPos,$FontSize,"Public Service Events");
$pdf->addTextWrap($X1,$YPos,40,$FontSize,$gpsnum,'right');
$YPos -= 14;
$pdf->addText($XPos,$YPos,$FontSize,"Emergency Operations");
$pdf->addTextWrap($X1,$YPos,40,$FontSize,$gemnum,'right');
$YPos -= 14;
$pdf->addText($XPos,$YPos,$FontSize,"Total number of ARES ops");
$pdf->addTextWrap($X1,$YPos,40,$FontSize,round($gnetsess+$gpsnum+$gemnum),'right');

$XPos = $Page_Width/2;
$X1 = $Page_Width - $Right_Margin - 80;
$YPos = $StartY;
$pdf->addText($XPos,$YPos,$FontSize,"Change since last month");
$pdf->addTextWrap($X1,$YPos,40,$FontSize,$gareschg,'right');
$YPos -= 14;
$pdf->addText($XPos,$YPos,$FontSize,"# ARES nets");
$pdf->addTextWrap($X1,$YPos,40,$FontSize,$numnets,'right');
$YPos -= 14;
$pdf->addText($XPos,$YPos,$FontSize,"Person hours");
$pdf->addTextWrap($X1,$YPos,40,$FontSize,round($gnethrs),'right');
$YPos -= 14;
$pdf->addText($XPos,$YPos,$FontSize,"Person hours");
$pdf->addTextWrap($X1,$YPos,40,$FontSize,round($gpshrs),'right');
$YPos -= 14;
$pdf->addText($XPos,$YPos,$FontSize,"Person hours");
$pdf->addTextWrap($X1,$YPos,40,$FontSize,round($gemhrs),'right');
$YPos -= 14;
$pdf->addText($XPos,$YPos,$FontSize,"Person hours");
$pdf->addTextWrap($X1,$YPos,40,$FontSize,round($gmanhrs),'right');


?>