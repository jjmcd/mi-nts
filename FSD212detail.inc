<?php
//    index.php
//    $Revision: 1.1 $ - $Date: 2007-11-11 20:06:00-05 $
//
//    index is the opening page of the mi-nts website.  It displays the
//    standard menu, and then only some text introducing the site.
//


//==================================================================================================
// D e t a i l   L i n e s
//==================================================================================================

$q1='SELECT `districtkey`, `district_code` from `arpsc_districts` ORDER BY `district_code`';
$r1=getResult($q1,$db);

$olddistrict=0;

$ColorBG=1;

$YPos -= 10;
$lh=11.8;

while ( $row1 = getRow($r1,$db) )
  {
    $district=$row1[1];
    $q2="SELECT `countyname`, `countycode` FROM `arpsc_counties` WHERE `district`='" .
      $district . "' ORDER BY `countyname`";
    $r2=getResult($q2,$db);
    if ( $district < ":" )
      {
	$sqlc='SELECT COUNT(*) FROM `arpsc_counties` WHERE `district`='
	  . $district;
	$lc=singleResult($sqlc,$db);

	while ( $row2 = getRow($r2,$db) )
	  {
	    $YPos -= $lh;
	    //echo $district . ',' . $row2[0] . "<br>\n";


	    $q3='SELECT `aresmem`,`drillsnum`,`drillshrs`,`psesnum`,`pseshrs`,`eopsnum`,`eopshrs`,`aresopsnum`,`aresops` ' .
	      "FROM `arpsc_ecrept` WHERE `county`='" . $row2[1] . "' AND `period`=" . $period;
	    $r3=getResult($q3,$db);
	    if ( $row3 = getRow($r3,$db) )
	      {
		$hours = $row3[2]+$row3[4]+$row3[6]+$row3[8];
		$value = $hours * 18.11;
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
			$change = " ";
		      }
		  }
		if ( $district != $olddistrict )
		  {
		    //**echo $district;
		    if ( $district == 6 )
		      {
			$PageNumber++;
			NextPage($pdf,$PageNumber,$Page_Width,$Page_Height,
				 $Top_Margin,$Bottom_Margin,
				 $Left_Margin,$Right_Margin,$Month);
			Header212($pdf,$Page_Width,$Left_Margin,
				  $Right_Margin,$Page_Height-$Top_Margin-50);
			$YPos = $YLoc - 11;
		      }
		    if ( $ColorBG == 1 )
		      {
			$pdf->SetFillColor(255,255,192);
			$YP = $Page_Height - $YPos - $lh+2;
			$pdf->Rect($Left_Margin,$YP,
				   $Page_Width-$Right_Margin-$Left_Margin,
				   $lh * $lc+1, 'F');
			$ColorBG = 0;
		      }
		    else
		      {
			$ColorBG = 1;
		      }
		    $pdf->selectFont('helvetica-Bold');
		    $pdf->addText($Left_Margin+10,$YPos-($lc*$lh)/2,20,
				  ' ' . $district);
		    $pdf->selectFont('helvetica');
		    $olddistrict = $district;
		  }
		//**echo ',' . $row2[0] . ',' . round($hours) . ',' .  round($value) . ',' . 
		//**$row3[0] . ',' . $change . ',' . $row3[1] . ',' . $row3[2] . ',' . $row3[3] . ',' .
		//**  $row3[4] . ',' . $row3[5] . ',' . $row3[6] . "\n";
		$pdf->addText($Left_Margin+50, $YPos, 10, $row2[0]);
		$pdf->addText($Left_Margin+170, $YPos, 10, round($hours));
		$pdf->addText($Left_Margin+210, $YPos, 10, round($value));
		$pdf->addText($Left_Margin+250, $YPos, 10, $row3[0]);
		$pdf->addText($Left_Margin+290, $YPos, 10, $change);
		$pdf->addText($Left_Margin+330, $YPos, 10, $row3[1]);
		$pdf->addText($Left_Margin+369, $YPos, 10, $row3[2]);
		$pdf->addText($Left_Margin+408, $YPos, 10, $row3[3]);
		$pdf->addText($Left_Margin+447, $YPos, 10, $row3[4]);
		$pdf->addText($Left_Margin+486, $YPos, 10, $row3[5]);
		$pdf->addText($Left_Margin+525, $YPos, 10, $row3[6]);
	      }
	    else
	      {
		if ( $row2[0]=='Arenac' )
		  {
		    if ( $district != $olddistrict )
		      {
			//**echo $district;
			  if ( $ColorBG == 1 )
			    {
			      $pdf->SetFillColor(255,192,192);
			      $YP = $Page_Height - $YPos - $lh+2;
			      $pdf->Rect($Left_Margin,$YP,
					 $Page_Width-$Right_Margin-$Left_Margin,
					 $lh * $lc+1, 'F');
			      $ColorBG = 0;
			    }
			  else
			    {
			      $ColorBG = 1;
			    }
			  $pdf->selectFont('helvetica-Bold');
			  $pdf->addText($Left_Margin+10,$YPos-($lc*$lh)/2,20,
					' ' . $district);
			  $pdf->selectFont('helvetica');
			  $olddistrict = $district;
		      }
		    $pdf->addText($Left_Margin+50, $YPos, 10, $row2[0]);
		    $pdf->addText($Left_Margin+190,$YPos, 10,
				  " - - - - - - - - included with Ogemaw - - - - - - - -");
		  }
		else if ( $row2[0]=='Clare' )
		  {
		    if ( $district != $olddistrict )
		      {
			//**echo $district;
			$PageNumber++;
			NextPage($pdf,$PageNumber,$Page_Width,$Page_Height,
				 $Top_Margin,$Bottom_Margin,
				 $Left_Margin,$Right_Margin,$Month);
			Header212($pdf,$Page_Width,$Left_Margin,
				  $Right_Margin,$Page_Height-$Top_Margin-50);
			$YPos = $Page_Height-$Top_Margin - 100;

			if ( $ColorBG == 1 )
			  {
			    $pdf->SetFillColor(255,255,192);
			    $YP = $Page_Height - $YPos - $lh+2;
			    $pdf->Rect($Left_Margin,$YP,
				       $Page_Width-$Right_Margin-$Left_Margin,
				       $lh * $lc+1, 'F');
			    $ColorBG = 0;
			  }
			else
			  {
			    $ColorBG = 1;
			  }
			$pdf->selectFont('helvetica-Bold');
			$pdf->addText($Left_Margin+10,$YPos-($lc*$lh)/2,20,
				      ' ' . $district);
			$pdf->selectFont('helvetica');
			$olddistrict = $district;
		      }
		    $pdf->addText($Left_Margin+50, $YPos, 10, $row2[0]);
		    $pdf->addText($Left_Margin+190,$YPos, 10,
				  "- - - - - - - - included with Isabella - - - - - - - -");
		    //**echo "," . $row2[0] . ",";
		    //**echo "w/Isabella" . "\n" ;
		  }
		else
		  {
		    if ( $district != $olddistrict )
		      {
			//**echo $district;
			if ( $district == 6 )
			  {
			    $PageNumber++;
			    NextPage($pdf,$PageNumber,$Page_Width,$Page_Height,
				     $Top_Margin,$Bottom_Margin,
				     $Left_Margin,$Right_Margin,$Month);
			    Header212($pdf,$Page_Width,$Left_Margin,
				      $Right_Margin,$Page_Height-$Top_Margin-50);
			    $YPos = $YLoc - 11;
			  }
			  if ( $ColorBG == 1 )
			    {
			      $pdf->SetFillColor(255,255,192);
			      $YP = $Page_Height - $YPos - $lh+2;
			      $pdf->Rect($Left_Margin,$YP,
					 $Page_Width-$Right_Margin-$Left_Margin,
					 $lh * $lc, 'F');
			      $ColorBG = 0;
			    }
			  else
			    {
			      $ColorBG = 1;
			    }
			  $pdf->selectFont('helvetica-Bold');
			  $pdf->addText($Left_Margin+10,$YPos-($lc*$lh)/2,20,
					' ' . $district);
			  $pdf->selectFont('helvetica');
			  $olddistrict = $district;
		      }
		    if ( $district==2 || $district==5 || $district==7 )
		      $pdf->SetFillColor(224,224,224);
		    else
		      $pdf->SetFillColor(224,224,128);
		    $YP = $Page_Height - $YPos - $lh+2;
		    $pdf->Rect($Left_Margin+40,$YP,
			       $Page_Width-$Right_Margin-$Left_Margin-40,
			       12, 'F');
		    $pdf->addText($Left_Margin+50, $YPos, 10, $row2[0]);
		    $pdf->addText($Left_Margin+200,$YPos, 10,
				  "- - - - - - - - N o    R e p o r t - - - - - - - -");
		  }
	      }
	  }
      }
  }
?>
