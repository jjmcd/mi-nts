<?php
//    netreport.php
//    $Revision: 1.3 $ - $Date: 2008-01-22 09:37:44-05 $
{


  // ------------------------ SAR  ------------------------------------

  $YPos -= 40;
  $pdf->selectFont('helvetica-Bold');
  $pdf->addTextWrap($Left_Margin,$YPos,$Page_Width-$Left_Margin-$Right_Margin,
		    16,'Station Activity Report','center');

  $YPos -= 20;
  for ( $XSlot=0; $XSlot<3; $XSlot++ )
    {
      $pdf->addText($Left_Margin+10+170*$XSlot,$YPos,10,'Call');
      $pdf->addTextWrap($Left_Margin+40+170*$XSlot,$YPos,40,10,
			'Total','right');
      $pdf->addTextWrap($Left_Margin+90+170*$XSlot,$YPos,90,10,
			'Comments','left');
    }
  $YPos -= 2;
  $pdf->selectFont('helvetica');

  // Get the actual report data for this period
  $SQL='SELECT `call`,`total`,`notes`,`updated` FROM `sar` WHERE `period`='
    . $period . ' ORDER BY `total` DESC';
  $result=getResult($SQL,$db);

  //echo "<TABLE class=main_page>\n";
  //echo 'Call,Total,Comments' . "\n";

  $XSlot = 2;
  $LineNo = 0;

  // We will remember rownum to alternate colors on the table
  $rownum=1;
  while ($myrow = getRow($result,$db) )
    {
      $XSlot++;
      if ( $XSlot > 2 )
	{
	  $XSlot = 0;
	  $YPos -= 12;
	  $pdf->SetFillColor(255,255,192);
	  if ( !(($LineNo/3) & 1) )
	    {
	      $YP = $Page_Height - $YPos - 10;
	      $pdf->Rect($Left_Margin,$YP,
			 $Page_Width-$Right_Margin-$Left_Margin,
			 12,'F');
	    }
	  $LineNo ++;
	}
      // Keep track of the last date
      if ( $myrow[3] > $maxdate )
	$maxdate = $myrow[3];
      // Calculate the link to the individual's reports
      $callrow=$myrow[0];
      // Switch between light and dark rows
      // Now display the data
      //echo "  <TR>\n";
      //echo $callrow . ","; // Call
      //echo $myrow[1] . ","; // Total
      $pdf->addText($Left_Margin+10+170*$XSlot,$YPos,10,$callrow);
      $pdf->addTextWrap($Left_Margin+40+170*$XSlot,$YPos,40,10,
			$myrow[1],'right');
      if ( strlen($myrow[2])>0 )
	$pdf->addTextWrap($Left_Margin+80+170*$XSlot,$YPos,90,10,
			  $myrow[2],'right');

      //echo "\n";
      //echo "  </TR>\n";
    }
  //echo "\n\n";

}
?>
