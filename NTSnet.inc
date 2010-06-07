<?php
//    NTSpshr.php
//    $Revision: 1.3 $ - $Date: 2008-01-22 09:37:44-05 $
{

  // ------------------------ Net Report ------------------------------------


  // Get the actual report data for this period
  $SQL = 'SELECT B.netfullname,A.QNI,A.QTC,A.QTR,A.sessions,A.updated,A.manhours,B.nethf '
    .  'FROM netreport A, nets B '
    .  'WHERE A.period=' . $period . ' AND A.netID=B.netID '
    .  'ORDER BY QTC DESC, QNI DESC';
  $result=getResult($SQL,$db);

  $YPos = $Page_Height-$Top_Margin-70;
  $pdf->selectFont('helvetica-Bold');
  centerText($pdf,$Left_Margin,$Page_Width-$Right_Margin-$Left_Margin,$YPos,
	     16,'Net Report');

  $YPos -= 30;

  $pdf->addTextWrap($Left_Margin+10,$YPos,250,10,'Net Name','left');
  $pdf->addTextWrap($Left_Margin+270,$YPos,40,10,'QNI','right');
  $pdf->addTextWrap($Left_Margin+320,$YPos,40,10,'QTC','right');
  $pdf->addTextWrap($Left_Margin+370,$YPos,40,10,'QTR','right');
  $pdf->addTextWrap($Left_Margin+420,$YPos,50,10,'Sessions','right');
  $pdf->addTextWrap($Left_Margin+470,$YPos,40,10,'Hours','right');
  $pdf->selectFont('helvetica');
  $YPos -=14;

  // We will use rownum to keep track of light and dark rows
  $rownum=1;
  // The following variables are used to calculate totals for the month
  $TQNI=0;    // Total QNI
  $TQTC=0;    // Total QTC
  $TQTR=0;    // Total QTR
  $TSess=0;   // Total sessions
  $TMH=0;     // Total manhours
  $TQNIH=0;   // Total QNI HF
  $TQTCH=0;   // Total QTC HF
  $TQTRH=0;   // Total QTR HF
  $TSessH=0;  // Total sessions HF
  $TMHH=0;    // Total manhours HF
  $TQNIV=0;   // Total QNI VHF
  $TQTCV=0;   // Total QTC VHF
  $TQTRV=0;   // Total QTR VHF
  $TSessV=0;  // Total sessions VHF
  $TMHV=0;    // Total manhours VHF
  // Loop through the rows of the result
  $Lineno = 0;
  while ( $myrow = getRow($result,$db) )
    {
      $pdf->SetFillColor(255,255,192);
      if ( !(($LineNo/3) & 1) )
	{
	  $YP = $Page_Height - $YPos - 12;
	  $pdf->Rect($Left_Margin,$YP,
		     $Page_Width-$Right_Margin-$Left_Margin,
		     12,'F');
	}
      $LineNo ++;
      // Update the latest data date, if necessary
      if ( $myrow[5] > $maxdate )
	$maxdate = $myrow[5];
      $rownum = 1;
      $pdf->addTextWrap($Left_Margin+10,$YPos,250,10,$myrow[0],'left');
      $pdf->addTextWrap($Left_Margin+270,$YPos,40,10,$myrow[1],'right');
      $pdf->addTextWrap($Left_Margin+320,$YPos,40,10,$myrow[2],'right');
      $pdf->addTextWrap($Left_Margin+370,$YPos,40,10,$myrow[3],'right');
      $pdf->addTextWrap($Left_Margin+420,$YPos,40,10,$myrow[4],'right');

      // For manhours, use reported if available
      if ( $myrow[6] > 0 )
	{
	    $pdf->addTextWrap($Left_Margin+470,$YPos,40,10,$myrow[6],'right');
	  $TMH=$TMH + $myrow[6];
	  $$manhours = $myrow[6];
	}
      // If not, estimate by calculating the product of the average QNI
      // times the QTR
      else
	if ( $myrow[4]>0 && $myrow[3]>0 )
	  {
	    $manhours=($myrow[3]/$myrow[4])*($myrow[1]);
	    $manhours=floor(100*$manhours+.005)/100;
	    $pdf->SetTextColor(128,128,128);
	    $pdf->addTextWrap($Left_Margin+470,$YPos,40,10,$manhours,'right');
	    $pdf->SetTextColor(0,0,0);
	    $TMH=$TMH + $manhours;
	  }
      // Calculate totals for the obvious ones
      $TQNI=$TQNI + $myrow[1];
      $TQTC=$TQTC + $myrow[2];
      $TQTR=$TQTR + $myrow[3];
      $TSess=$TSess + $myrow[4];
      if ( $myrow[7] == 1 )
	{
	  $TQNIH=$TQNIH + $myrow[1];
	  $TQTCH=$TQTCH + $myrow[2];
	  $TQTRH=$TQTR + $myrow[3];
	  $TSessH=$TSessH + $myrow[4];
	  $TMHH=$TMHH+$manhours;
	}
      else
	{
	  $TQNIV=$TQNIV + $myrow[1];
	  $TQTCV=$TQTCV + $myrow[2];
	  $TQTRV=$TQTRV + $myrow[3];
	  $TSessV=$TSessV + $myrow[4];
	  $TMHV=$TMHV+$manhours;
	}
      $YPos -= 12;
      //echo "  \n";
    }
  $pdf->setLineWidth(1);
  $pdf->Line($Left_Margin,$YPos+11,
	     $Page_Width-$Left_Margin-$Right_Margin,$YPos+11);
  $YPos -=1;
  $pdf->selectFont('helvetica-Bold');
  $pdf->addText($Left_Margin+10,$YPos,10,'HF Total');
  $pdf->selectFont('helvetica');
  $pdf->addTextWrap($Left_Margin+270,$YPos,40,10,$TQNIH,'right');
  $pdf->addTextWrap($Left_Margin+320,$YPos,40,10,$TQTCH,'right');
  $pdf->addTextWrap($Left_Margin+370,$YPos,40,10,$TQTRH,'right');
  $pdf->addTextWrap($Left_Margin+420,$YPos,40,10,$TSessH,'right');
  $pdf->addTextWrap($Left_Margin+470,$YPos,40,10,$TMHN,'right');
  $YPos -= 12;
  // Display totals
  $pdf->selectFont('helvetica-Bold');
  $pdf->addText($Left_Margin+10,$YPos,10,'VHF Total');
  $pdf->selectFont('helvetica');
  $pdf->addTextWrap($Left_Margin+270,$YPos,40,10,$TQNIV,'right');
  $pdf->addTextWrap($Left_Margin+320,$YPos,40,10,$TQTCV,'right');
  $pdf->addTextWrap($Left_Margin+370,$YPos,40,10,$TQTRV,'right');
  $pdf->addTextWrap($Left_Margin+420,$YPos,40,10,$TSessV,'right');
  $pdf->addTextWrap($Left_Margin+470,$YPos,40,10,$TMHV,'right');
  $YPos -= 12;

  $YPos -= 1;
  $pdf->setLineWidth(2);
  $pdf->Line($Left_Margin,$YPos+11,
	     $Page_Width-$Left_Margin-$Right_Margin,$YPos+11);
  $pdf->selectFont('helvetica-Bold');
  $pdf->addText($Left_Margin+10,$YPos,10,'Total');
  $pdf->addTextWrap($Left_Margin+270,$YPos,40,10,$TQNI,'right');
  $pdf->addTextWrap($Left_Margin+320,$YPos,40,10,$TQTC,'right');
  $pdf->addTextWrap($Left_Margin+370,$YPos,40,10,$TQTR,'right');
  $pdf->addTextWrap($Left_Margin+420,$YPos,40,10,$TSess,'right');
  $pdf->addTextWrap($Left_Margin+470,$YPos,40,10,$TMH,'right');
  $YPos -= 24;

  $pdf->SetFillColor(192,255,192);
  $YP = $Page_Height - $YPos - 12;
  $pdf->Rect($Left_Margin,$YP,
	     $Page_Width-$Right_Margin-$Left_Margin,
	     12,'F');
  $Contrib = round(18.11 * $TMH);
  $pdf->SetTextColor(0,128,64);
  $pdf->addTextWrap($Left_Margin,$YPos,
		    $Page_Width-$Left_Margin-$Right_Margin,
		    10,
		    'Value of NTS person-hour contribution $' . $Contrib,
		    'center');
  $pdf->SetTextColor(0,0,0);
  $pdf->selectFont('helvetica');
  $YPos -= 24;
}
?>