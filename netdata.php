<?php
//    netdata.php
//    $Revision: 1.10 $ - $Date: 2008-01-22 15:25:48-05 $
//
//
{
    include('includes/session.inc');
    $title=_('Michigan Section NTS');

    include('includes/miscFunctions.inc');

    $db = openDatabase( $dppE );
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

    $netid = $_GET['netid'];
    if ( $netid < 1 ) $netid = 1;

    // Choose the latest period and show 24 months
    $SQL="SELECT MAX(`period`) FROM `netreport`";
    $lastperiod = singleResult($SQL,$db);;
    $firstperiod = $lastperiod - 23;
 
    echo "    <center>\n";

    // Display the net name for this report
    $SQL1 = "SELECT `netfullname`, `netacro` from `nets` WHERE "
      . "`netid`=" . $netid;
    $res1 = getResult( $SQL1, $db );
    $row1 = getRow( $res1, $db );
    $netfull = $row1[0];

    echo "<h1>" . $netfull . "</h1>\n<p>&nbsp;</p>\n";

    $SQL2 = "SELECT `period`,`qni`,`qtc`,`qtr`,`sessions`,`manhours`, `updated` " .
      "FROM `netreport` WHERE `netid` = " . $netid .
      " ORDER BY `period` DESC";
      //      " AND `period` BETWEEN " . $firstperiod . " AND " .
      //      $lastperiod . " ORDER BY `period` DESC";
    $res2 = getResult( $SQL2, $db );

    echo "<table>\n";
    echo "<tr><th align=\"left\">Month</th><th>QNI</th><th>QTC" .
	"</th><th>QTR</th><th>Sessions</th><th>Hours</th></tr>\n";

    // We will use rownum to keep track of light and dark rows
    $rownum=1;
    // The following variables are used to calculate totals for the month
    $TQNI=0;
    $TQTC=0;
    $TQTR=0;
    $TSess=0;
    $TMH=0;
    // Loop through the rows of the result
    while ( $myrow = getRow($res2,$db) )
	{
	    // Update the latest data date, if necessary
	    if ( $myrow[6] > $maxdate )
		$maxdate = $myrow[6];
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
	    $SQL3 = "SELECT `lastday` FROM `periods` WHERE `periodno`="
	      . $myrow[0];
	    $longdate = singleResult( $SQL3, $db );
	    echo '    <td ' . $id . ' align="left">' . 
	      convertDateShort($longdate) . "</td>\n";
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
	    if ( $myrow[5] > 0 )
		{
		echo '    <td ' . $id . ' align="right">' . 
		    $myrow[5] . "</td>\n";
		$TMH=$TMH + $myrow[5];
		}
	    // If not, estimate by calculating the product of the average QNI
	    // times the QTR
	    else
		if ( $myrow[4]>0 && $myrow[3]>0 )
		{
		    $manhours=($myrow[3]/$myrow[4])*($myrow[1]);
		    $manhours=floor(100*$manhours+.005)/100;
		    echo '    <td ' . $id . ' align="right">'
			. '<font color=#bbbbbb>' . $manhours 
			. "</font></td>\n";
		    $TMH=$TMH + $manhours;
		}
	    echo "  </tr>\n";
	}
    // Display totals
    echo "<tr><th>Total<td align=\"right\">" . $TQNI
	. "<td align=\"right\">" . $TQTC 
        . "<td align=\"right\">" . $TQTR . "<td align=\"right\">" . $TSess 
        . "<td align=\"right\">" . $TMH . "</tr>\n";
    echo "</table>\n";

    echo "<p></p>\n";
    echo "<h2>Checkin History</h2>\n";
    echo "  <a href=\"netQNIB.php?netid=" . $netid . "\">\n";
    echo "    <img src=\"netQNI.php?netid=" . $netid . "\"  width=\"380\" height=\"220 \" alt=\"Checkins\" />\n";
    echo "  </a>\n";
    echo "<p></p>\n";
    echo "<h2>Traffic History</h2>\n";
    echo "  <a href=\"netQTCB.php?netid=" . $netid . "\">\n";
    echo "    <img src=\"netQTC.php?netid=" . $netid . "\"  width=\"380\" height=\"220 \" alt=\"Checkins\" />\n";
    echo "  </a>\n";
    echo "<p></p>\n";
    echo "<h2>Runtime History</h2>\n";
    echo "  <a href=\"netQTRB.php?netid=" . $netid . "\">\n";
    echo "    <img src=\"netQTR.php?netid=" . $netid . "\"  width=\"380\" height=\"220 \" alt=\"Checkins\" />\n";
    echo "  </a>\n";
    echo "<p></p>\n";
    echo "<h2>Traffic per Hour</h2>\n";
    echo "  <a href=\"netTPHB.php?netid=" . $netid . "\">\n";
    echo "    <img src=\"netTPH.php?netid=" . $netid . "\"  width=\"380\" height=\"220 \" alt=\"Checkins\" />\n";
    echo "  </a>\n";
    echo "</center>\n";
?>
  </div>
<?php
    sectLeaders($db);
    footer($starttime,$maxdate,
      "\$Revision: 1.00 $ - \$Date: 2010-01-31 08:25:48-05 $");
}
?>
</div>
</body>
</html>
