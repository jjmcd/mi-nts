<?php
//    NTSpshr.inc
//    $Revision: 1.3 $ - $Date: 2008-01-22 09:37:44-05 $
//
{

  // ------------------------ PSHR ------------------------------------

  //**echo '"Public Service Honor Roll for ' . $usedate . '"' . "\n\n";
  $YPos -= 40;
  $pdf->selectFont('helvetica-Bold');
  $pdf->addTextWrap($Left_Margin,$YPos,$Page_Width-$Left_Margin-$Right_Margin,
		    16,'Public Service Honor Roll','center');

  $YPos -= 20;

  // Get the actual report data for this period
  $SQL='SELECT `call`,`cat1`,`cat2`,`cat3`,`cat4`,`cat5`,`cat6`,`total`,' .
    '`comment`,`updated` FROM `pshr`' .
    ' WHERE `period`=' . $period . ' ORDER BY `total` DESC';
  $result=getResult($SQL,$db);

  ////**echo "<TABLE class=main_page>\n";
  //**echo '"Call","1","2","3","4","5","6","Total","Comments",' . "\n";
      $XP[0] = $Left_Margin + 10;
      $XP[1] = $XP[0] + 40;
      $XP[2] = $XP[1] + 50;
      $XP[3] = $XP[2] + 50;
      $XP[4] = $XP[3] + 50;
      $XP[5] = $XP[4] + 50;
      $XP[6] = $XP[5] + 50;
      $XP[7] = $XP[6] + 50;
      $XP[8] = $XP[7] + 55;
      $pdf->addTextWrap($XP[0],$YPos,50,10,'Call','left');
      $pdf->addTextWrap($XP[1],$YPos,50,10,'Nets','right');
      $pdf->addTextWrap($XP[2],$YPos,50,10,'Traffic','right');
      $pdf->addTextWrap($XP[3],$YPos,50,10,'Appt','right');
      $pdf->addTextWrap($XP[4],$YPos,50,10,'Plan','right');
      $pdf->addTextWrap($XP[5],$YPos,50,10,'Emerg','right');
      $pdf->addTextWrap($XP[6],$YPos,50,10,'Digital','right');
      $pdf->addTextWrap($XP[7],$YPos,50,10,'Total','right');
      $pdf->addTextWrap($XP[8],$YPos,120,10,'Comments','left');

      $pdf->SetFillColor(255,255,192);
      $pdf->selectFont('helvetica');
      $YPos -= 14;

  // We will remember rownum to alternate colors in the table
  $rownum=1;
  $LineNo = 0;
  // Now loop through the rows of the data
  while ($myrow = getRow($result,$db) )
    {
      // Keep track of the latest data
      if ( $myrow[9] > $maxdate )
	$maxdate = $myrow[9];

      // Switch between light and dark rows
      if ( $rownum != 0 )
	{
	  $class1="OsRow";
	  $class2="OsRow3";
	  $rownum = 0;
	}
      else
	{
	  $class1="OsRow2";
	  $class2="OsRow4";
	  $rownum = 1;
	}
      // Calculate a link to the individual's reports
      $callrow= $myrow[0];

      // Now display the row
      if ( !(($LineNo/3) & 1) )
	{
	  $YP = $Page_Height - $YPos - 12;
	  $pdf->Rect($Left_Margin,$YP,
		     $Page_Width-$Right_Margin-$Left_Margin,
		     12,'F');
	}
      $LineNo++;
      $pdf->addTextWrap($XP[0],$YPos,50,10,$callrow,'left');
      $pdf->addTextWrap($XP[1],$YPos,50,10,$myrow[1],'right');
      $pdf->addTextWrap($XP[2],$YPos,50,10,$myrow[2],'right');
      $pdf->addTextWrap($XP[3],$YPos,50,10,$myrow[3],'right');
      $pdf->addTextWrap($XP[4],$YPos,50,10,$myrow[4],'right');
      $pdf->addTextWrap($XP[5],$YPos,50,10,$myrow[5],'right');
      $pdf->addTextWrap($XP[6],$YPos,50,10,$myrow[6],'right');
      $pdf->addTextWrap($XP[7],$YPos,50,10,$myrow[7],'right');
      if ( strlen($myrow[8])>0 )
	$pdf->addTextWrap($XP[8],$YPos,120,10,$myrow[8],'left');
      $YPos -= 12;

      //**echo "\n";
      ////**echo "  </TR>\n";
    }
  ////**echo "</TABLE>\n";
  //**echo "\n\n\"Brass Pounder's League for " . $usedate . '"' . "\n\n";
  //**echo "Call,Orig,Sent,Rec'd,Del'd,Total\n\n\n\n";

  //**footercsv($starttime,$maxdate,"\$Revision: 1.3 $ - \$Date: 2008-01-22 09:37:44-05 $");
  //**echo "\n</pre>\n";
}
?>
