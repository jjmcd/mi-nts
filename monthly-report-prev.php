<?php
//    netreport.php
//    $Revision: 1.2 $ - $Date: 2006-01-16 09:37:54-05 $
//
//    netreport displays a table of net reports for a month.
//    Beneath the report it displays links to the previous and
//    following 3 months if any reports exist for that period.  
//
//    netreport.php accepts a single, named argument, period
//    If the argument is omitted, netreport searches for the 
//    most recent period for which there is data and uses that.
{
    include('includes/session.inc');
    include('includes/miscFunctions.inc');
    $title=_('Michigan Section NTS');

	echo '<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">' . "\n";
	
	
	echo '<HTML><HEAD><TITLE>' . $title . '</TITLE>' . "\n";
	echo '<link REL="shortcut icon" HREF="favicon.ico">' . "\n";
	echo '<link REL="icon" HREF="favicon.ico">' . "\n";
	echo '<meta http-equiv="Content-Type" content="text/html; charset=' . _('ISO-8859-1') . '">' . "\n";
	echo '<LINK HREF="css/'. $_SESSION['Theme'] .'/default.css" REL="stylesheet" TYPE="text/css">' . "\n";
	echo '</HEAD>' . "\n";

	echo '<BODY>' . "\n";
	echo '"Michigan Section NTS"<BR>' . "\n";

    $db = mysql_connect($host , $dbuser, passwd($dbp,'artichoke'));
    mysql_select_db($DatabaseName,$db);



    // Remember the launch time
    $starttime = strftime("%A, %B %d %Y, %H:%M");

    echo '<P></P>' . "\n";

    // Initialize the most recent data counter
    $maxdate=0;

    // Get the requested period, if blank choose the latest
    $period = $_GET['period'];
    if ( $period < 1 )
    {
	$SQL="SELECT MAX(period) FROM netreport";
	$period = singleResult($SQL,$db);
    }

    // Display the month name for this report
    $SQL = 'SELECT lastday FROM periods WHERE periodno=' . $period;
    $usedate = convertDate(singleResult($SQL,$db));
    echo '"Net reports for ' . $usedate . '"<BR><BR>' . "\n";

    // Get the actual report data for this period
    $SQL = 'SELECT B.netfullname,A.QNI,A.QTC,A.QTR,A.sessions,A.updated,A.manhours,B.nethf '
        .  'FROM netreport A, nets B '
        .  'WHERE A.period=' . $period . ' AND A.netID=B.netID '
        .  'ORDER BY QTC DESC, QNI DESC';
    $result=getResult($SQL,$db);

    echo "<TABLE class=main_page>\n";
    echo '<TD>"Net Name","QNI","QTC","QTR","Sessions","Hours"<TR>' . "\n";

    // We will use rownum to keep track of light and dark rows
    $rownum=1;
    // The following variables are used to calculate totals for the month
    $TQNI=0;
    $TQTC=0;
    $TQTR=0;
    $TSess=0;
    $TMH=0;
    $TQNIH=0;
    $TQTCH=0;
    $TQTRH=0;
    $TSessH=0;
    $TMHH=0;
    $TQNIV=0;
    $TQTCV=0;
    $TQTRV=0;
    $TSessV=0;
    $TMHV=0;
    // Loop through the rows of the result
    while ( $myrow = getRow($result,$db) )
	{
	    // Update the latest data date, if necessary
	    if ( $myrow[5] > $maxdate )
		$maxdate = $myrow[5];
		    echo "  <TR>\n";
		    $rownum = 1;
	    echo '    <TD>"' . $myrow[0] . '",' . "\n";
	    echo $myrow[1] . ",\n";
	    echo $myrow[2] . ",\n";
	    echo $myrow[3] . ",\n";
	    echo $myrow[4] . ",\n";
	    // For manhours, use reported if available
	    if ( $myrow[6] > 0 )
		{
		echo $myrow[6] . ",</TD>\n";
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
		    echo $manhours 
			. ",</TD>\n";
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
	    echo "  </TR>\n";
	}
    // Display totals
    echo '<TR><TD>"HF Total",' . $TQNIH . ',' . $TQTCH 
        . "," . $TQTRH . "," . $TSessH 
        . "," . $TMHH . ",</TR>\n";
    echo '<TR><TD>"VHF Total",' . $TQNIV . "," . $TQTCV 
        . "," . $TQTRV . "," . $TSessV 
        . "," . $TMHV . ",</TR>\n";
    echo '<TR><TD>"Total" ,'. $TQNI . "," . $TQTC 
        . "," . $TQTR . "," . $TSess 
        . "," . $TMH . "</TR>\n";
    echo "</TABLE>\n";

    echo "<P>&nbsp;</P>\n";
    //echo "<P>&nbsp;</P>\n";


// ------------------------ SAR

    echo '<P>"Station Activity Report for ' . $usedate . '"</P>' . "\n";


    // Get the actual report data for this period
    $SQL='SELECT call,total,notes,updated FROM `sar` WHERE `period`='
        . $period . ' ORDER BY total DESC';
    $result=getResult($SQL,$db);

    echo "<TABLE class=main_page>\n";
    echo '<TD>Call,Total,Comments<TR>' . "\n";

    // We will remember rownum to alternate colors on the table
    $rownum=1;
    while ($myrow = getRow($result,$db) )
	{
	    // Keep track of the last date
	    if ( $myrow[3] > $maxdate )
		$maxdate = $myrow[3];
	    // Calculate the link to the individual's reports
	    $callrow=$myrow[0];
	    // Switch between light and dark rows
	    // Now display the data
	    echo "  <TR>\n";
	    echo "    <TD>" . $callrow . ",\n"; // Call
	    echo $myrow[1] . ",\n"; // Total
	    echo '"' . $myrow[2] . '"' . ",</TD>\n"; // Comments
	    echo "  </TR>\n";
	}
    echo "</TABLE>\n";

// ------------------------ PSHR

    echo '<P>"Public Service Honor Roll for ' . $usedate . '"' . "</P>\n";

    // Get the actual report data for this period
    $SQL='SELECT call,cat1,cat2,cat3,cat4,cat5,cat6,total,comment,updated FROM `pshr` WHERE `period`='
        . $period . ' ORDER BY total DESC';
    $result=getResult($SQL,$db);

    echo "<TABLE class=main_page>\n";
    echo '<TD>"Call","1","2","3","4","5","6","Total","Comments",' . "<TR>\n";

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
	    echo "  <TR>\n";
	    echo '    <TD>' . $callrow . ",\n";
	    echo $myrow[1] . ",\n";
	    echo $myrow[2] . ",\n";
	    echo $myrow[3] . ",\n";
	    echo $myrow[4] . ",\n";
	    echo $myrow[5] . ",\n";
	    echo $myrow[6] . ",\n";
	    echo $myrow[7] . ",\n";
	    echo '"' . $myrow[8] . '"' . "</TD>\n";
	    echo "  </TR>\n";
	}
    echo "</TABLE>\n";
    echo "<P>\"Brass Pounder's League for " . $usedate . '"' . "</P>\n<P>&nbsp;</p>";


    footer($starttime,$maxdate,"\$Revision: 1.2 $ - \$Date: 2006-01-16 09:37:54-05 $");
}
?>
</BODY>
</HTML>
