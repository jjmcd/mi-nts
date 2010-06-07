<?php
//    NTSbpl.inc
//    $Revision: 1.3 $ - $Date: 2008-01-22 09:37:44-05 $
//
{

  // ------------------------ BPL ------------------------------------

  //**echo '"Public Service Honor Roll for ' . $usedate . '"' . "\n\n";
  $YPos -= 40;
  $pdf->selectFont('helvetica-Bold');
  $pdf->addTextWrap($Left_Margin,$YPos,$Page_Width-$Left_Margin-$Right_Margin,
		    16,"Brass Pounder's League",'center');

  $YPos -= 20;

  // Get the actual report data for this period
  $SQL='SELECT `call`,`orig`,`sent`,`recd`,`deld`,`total`,' .
    '`updated` FROM `bpl`' .
    ' WHERE `period`=' . $period . ' ORDER BY `total` DESC';
//echo $SQL . "\n";
  $result=getResult($SQL,$db);

  ////**echo "<TABLE class=main_page>\n";
  //**echo '"Call","1","2","3","4","5","6","Total","Comments",' . "\n";
      $XP[0] = $Left_Margin + 140;
      $XP[1] = $XP[0] + 40;
      $XP[2] = $XP[1] + 50;
      $XP[3] = $XP[2] + 50;
      $XP[4] = $XP[3] + 50;
      $XP[5] = $XP[4] + 50;
      $XP[6] = $XP[5] + 50;
      $XP[7] = $XP[6] + 50;
      $XP[8] = $XP[7] + 55;
      $pdf->addTextWrap($XP[0],$YPos,50,10,'Call','left');
      $pdf->addTextWrap($XP[1],$YPos,50,10,'Orig','right');
      $pdf->addTextWrap($XP[2],$YPos,50,10,'Sent','right');
      $pdf->addTextWrap($XP[3],$YPos,50,10,"Rec'd",'right');
      $pdf->addTextWrap($XP[4],$YPos,50,10,"Del'd",'right');
      $pdf->addTextWrap($XP[5],$YPos,50,10,'Total','right');

      $pdf->selectFont('helvetica');
      $YPos -= 14;

  // We will remember rownum to alternate colors in the table
  $rownum=1;
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
      ////**echo "  <TR>\n";
      //**echo $callrow . ",";
      //**echo $myrow[1] . ",";
      //**echo $myrow[2] . ",";
      //**echo $myrow[3] . ",";
      //**echo $myrow[4] . ",";
      //**echo $myrow[5] . ",";
      //**echo $myrow[6] . ",";
      //**echo $myrow[7] . ",";
      $pdf->addTextWrap($XP[0],$YPos,50,10,$myrow[0],'left');
      $pdf->addTextWrap($XP[1],$YPos,50,10,$myrow[1],'right');
      $pdf->addTextWrap($XP[2],$YPos,50,10,$myrow[2],'right');
      $pdf->addTextWrap($XP[3],$YPos,50,10,$myrow[3],'right');
      $pdf->addTextWrap($XP[4],$YPos,50,10,$myrow[4],'right');
      $pdf->addTextWrap($XP[5],$YPos,50,10,$myrow[5],'right');
      //$pdf->addTextWrap($XP[6],$YPos,50,10,$myrow[6],'right');
      //$pdf->addTextWrap($XP[7],$YPos,50,10,$myrow[7],'right');
      //if ( strlen($myrow[8])>0 )
	//$pdf->addTextWrap($XP[8],$YPos,120,10,$myrow[8],'left');
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
