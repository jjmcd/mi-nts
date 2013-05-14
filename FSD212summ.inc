<?php
//    index.php

//==================================================================================================
// S u m m a r y   L i n e s
//==================================================================================================

// Initialize maxdate to most recent county update
$q0='SELECT MAX(`updated`) FROM `arpsc_counties`';
$r0=getResult($q0,$db);
$row0=getRow($r0,$db);
$maxdate=$row0[0];
$rowcolor=0;

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
	    $value = $hours * 18.11;
	    $lastperiod = $period-1;
		$qb="SELECT MAX(`PERIOD`) FROM `arpsc_ecrept` WHERE `county`='"
		 . $row2[1] . "' AND `PERIOD`<" . $period;
		$rb=getResult($qb,$db);
		//$lastperiod=$rb;
		$rob=getRow($rb,$db);
		$lastperiod=$rob[0];
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
    $value = $manhrs * 18.11;

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

//    echo $district . ',' . ',' . round($manhrs) . ',' . round($value) . ',' .
//      $aresmem . ',' . $areschg . ',' . $netsess . ',' . round($nethrs). ',' . 
//      $psnum . ',' . round($pshrs) . ',' . $emnum . ',' . round($emhrs) . " \n";

  $YPos = $YPos-11;

  if ( $rowcolor == 0 )
    {
      $BarPos = $Page_Height - $YPos - 9;
      $pdf->SetFillColor(255,255,192);
      $pdf->Rect($Left_Margin,$BarPos,$Page_Width-$Left_Margin-$Right_Margin,11,'F');
      $rowcolor = 1;
    }
    else
    {
      $rowcolor = 0;
    }

  $XPos = $Left_Margin;
  centerText($pdf,$XPos,$XPos+40,$YPos,$FontSize,$district);
  $XPos = $XPos+160;
  centerText($pdf,$XPos,$XPos+40,$YPos,$FontSize,round($manhrs));
  $XPos = $XPos+40;
  centerText($pdf,$XPos,$XPos+40,$YPos,$FontSize,round($value));
  $XPos = $XPos+40;
  centerText($pdf,$XPos,$XPos+40,$YPos,$FontSize,round($aresmem));
  $XPos = $XPos+40;
  centerText($pdf,$XPos,$XPos+40,$YPos,$FontSize,round($areschg));
  $XPos = $XPos+40;
  centerText($pdf,$XPos,$XPos+39,$YPos,$FontSize,round($netsess));
  $XPos = $XPos+39;
  centerText($pdf,$XPos,$XPos+39,$YPos,$FontSize,round($nethrs));
  $XPos = $XPos+39;
  centerText($pdf,$XPos,$XPos+39,$YPos,$FontSize,round($psnum));
  $XPos = $XPos+39;
  centerText($pdf,$XPos,$XPos+39,$YPos,$FontSize,round($pshrs));
  $XPos = $XPos+39;
  centerText($pdf,$XPos,$XPos+39,$YPos,$FontSize,round($emnum));
  $XPos = $XPos+39;
  centerText($pdf,$XPos,$XPos+39,$YPos,$FontSize,round($emhrs));

}

//echo "Total all districts" .  ",," . round($gmanhrs) . ',' . round($gvalue) . ',' .
//$garesmem . ',' . $gareschg . ',' . $gnetsess . ',' . round($gnethrs) .',' . 
//$gpsnum . ',' . round($gpshrs) .',' .  $gemnum . ',' . round($gemhrs) . "\n";

  $pdf->SetDrawColor(128,128,128);
  $pdf->SetLineWidth(1);
  $pdf->Line( $Left_Margin, $YPos-2, $Page_Width-$Right_Margin,$YPos-2);

  $pdf->SelectFont("helvetica-Bold");

  $YPos = $YPos-11;

  $pdf->SetDrawColor(128,128,128);
  $pdf->SetLineWidth(1);
  $pdf->Line( $Left_Margin, $YPos-3, $Page_Width-$Right_Margin, $YPos-3 );

  $XPos = $Left_Margin;
  $pdf->addText($XPos+5,$YPos,$FontSize,'Total all districts');
  $XPos = $XPos+160;
  centerText($pdf,$XPos,$XPos+40,$YPos,$FontSize,round($gmanhrs));
  $XPos = $XPos+40;
  centerText($pdf,$XPos,$XPos+40,$YPos,$FontSize,round($gvalue));
  $XPos = $XPos+40;
  centerText($pdf,$XPos,$XPos+40,$YPos,$FontSize,round($garesmem));
  $XPos = $XPos+40;
  centerText($pdf,$XPos,$XPos+40,$YPos,$FontSize,round($gareschg));
  $XPos = $XPos+40;
  centerText($pdf,$XPos,$XPos+39,$YPos,$FontSize,round($gnetsess));
  $XPos = $XPos+39;
  centerText($pdf,$XPos,$XPos+39,$YPos,$FontSize,round($gnethrs));
  $XPos = $XPos+39;
  centerText($pdf,$XPos,$XPos+39,$YPos,$FontSize,round($gpsnum));
  $XPos = $XPos+39;
  centerText($pdf,$XPos,$XPos+39,$YPos,$FontSize,round($gpshrs));
  $XPos = $XPos+39;
  centerText($pdf,$XPos,$XPos+39,$YPos,$FontSize,round($gemnum));
  $XPos = $XPos+39;
  centerText($pdf,$XPos,$XPos+39,$YPos,$FontSize,round($gemhrs));

  $pdf->SelectFont("helvetica");

?>
