<?php
//    netreport.php
//    $Revision: 1.8 $ - $Date: 2007/08/31 21:33:40 $
//
//    netreport displays a table of net reports for a month.
//    Beneath the report it displays links to the previous and
//    following 3 months if any reports exist for that period.  
//
//    netreport.php accepts a single, named argument, period
//    If the argument is omitted, netreport searches for the 
//    most recent period for which there is data and uses that.
//
    include('includes/session.inc');
    $title=_('Michigan Section NTS');

    include('includes/functions.inc');

    $db = mysql_connect($host , $dbuser, $dbpassword);
    mysql_select_db($DatabaseName,$db);

    NTSheader($title,"NTS Michigan Section Traffic");

    // Remember the launch time
    $starttime = strftime("%A, %B %d %Y, %H:%M");

    leftBar( $db );
?>
  <div id="main">

<?php
    // Initialize the latest data counter
    $maxdate=0;

    // Get the requested period, if blank choose the latest
    $period = $_GET['period'];
    if ( $period < 1 )
    {
	$SQL="SELECT MAX(period) FROM netreport";
	$period = singleResult($SQL,$db);;
    }
 
    echo "    <center>\n";

    // Display the month name for this report
    $SQL = 'SELECT lastday FROM periods WHERE periodno=' . $period;
    echo "<p><h1>Net reports for " . convertDate(singleResult($SQL,$db)) . "</h1></p>\n";

    // Get the actual report data for this period
    $SQL = 'SELECT B.netfullname,A.QNI,A.QTC,A.QTR,A.sessions,A.updated,A.manhours '
        .  'FROM netreport A, nets B '
        .  'WHERE A.period=' . $period . ' AND A.netID=B.netID '
        .  'ORDER BY QTC DESC, QNI DESC';
    $result=getResult($SQL,$db);

    echo "<table>\n";
    echo "<tr><th align=\"left\">Net Name</th><th>QNI</th><th>QTC</th><th>QTR</th><th>Sessions</th><th>Hours</th></tr>\n";

    // We will use rownum to keep track of light and dark rows
    $rownum=1;
    // The following variables are used to calculate totals for the month
    $TQNI=0;
    $TQTC=0;
    $TQTR=0;
    $TSess=0;
    $TMH=0;
    // Loop through the rows of the result
    while ( $myrow = getRow($result,$db) )
	{
	    // Update the latest data date, if necessary
	    if ( $myrow[5] > $maxdate )
		$maxdate = $myrow[5];
	    // Choose between light and dark rows
	    if ( $rownum != 0 )
		{
		    $id = 'id="OsRow3"';
		    echo "  <tr id=OsRow>\n";
		    $rownum = 0;
		}
	    else
		{
		    $id = 'id="OsRow4"';
		    echo "  <tr id=OsRow2>\n";
		    $rownum = 1;
		}
	    echo '    <td ' . $id . ' align="left">' . $myrow[0] . "</td>\n";
	    echo '    <td ' . $id . ' align="right">' . $myrow[1] . "</td>\n";
	    echo '    <td ' . $id . ' align="right">' . $myrow[2] . "</td>\n";
	    echo '    <td ' . $id . ' align="right">' . $myrow[3] . "</td>\n";
	    echo '    <td ' . $id . ' align="right">' . $myrow[4] . "</td>\n";
	    // Calculate totals for the obvious ones
	    $TQNI=$TQNI + $myrow[1];
	    $TQTC=$TQTC + $myrow[2];
	    $TQTR=$TQTR + $myrow[3];
	    $TSess=$TSess + $myrow[4];
	    // For manhours, use reported if available
	    if ( $myrow[6] > 0 )
		{
		echo '    <td ' . $id . ' align="right">' . $myrow[6] . "</td>\n";
		$TMH=$TMH + $myrow[6];
		}
	    // If not, estimate by calculating the product of the average QNI
	    // times the QTR
	    else
		if ( $myrow[4]>0 && $myrow[3]>0 )
		{
		    $manhours=($myrow[3]/$myrow[4])*($myrow[1]);
		    $manhours=floor(100*$manhours+.005)/100;
		    echo '    <td ' . $id . ' align="right"><font color=#bbbbbb>' . $manhours 
			. "</font></td>\n";
		    $TMH=$TMH + $manhours;
		}
	    echo "  </tr>\n";
	}
    // Display totals
    echo "<tr><th>Total<td align=\"right\">" . $TQNI . "<td align=\"right\">" . $TQTC 
        . "<td align=\"right\">" . $TQTR . "<td align=\"right\">" . $TSess 
        . "<td align=\"right\">" . $TMH . "</tr>\n";
    echo "</table>\n";

    echo "<p></p>\n";
    dateLinks($period,"netreport",$db);
    echo "</center>\n";
?>
  </div>
<?php
    sectLeaders($db);
    footer($starttime,$maxdate,
      "\$Revision: 1.8 $ - \$Date: 2007/08/31 21:33:40 $");
?>
</div>
</body>
</html>
