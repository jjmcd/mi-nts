<?php
//    netreport.php
//    $Revision: 1.10 $ - $Date: 2008-01-22 15:25:48-05 $
//
//    netreport displays a table of net reports for a month.
//    Beneath the report it displays links to the previous and
//    following 3 months if any reports exist for that period.  
//
//    netreport.php accepts a single, named argument, period
//    If the argument is omitted, netreport searches for the 
//    most recent period for which there is data and uses that.
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

    // Get the requested period, if blank choose the latest
    $period = $_GET['period'];
    if ( $period < 1 )
    {
	$SQL="SELECT MAX(`period`) FROM `netreport`";
	$period = singleResult($SQL,$db);;
    }
 
    echo "    <center>\n";
    $lastperiod=$period;
    echo "<p><h1>Individual History</h1></p>\n";

    echo "<table>\n";
    echo "<tr><th align=\"left\">Period</th><th>SAR</th><td>Num</td><th>PSHR" .
	"</th><td>Num</td></tr>\n";

    for ( $period=$lastperiod; $period>71; $period-- )
      {

    // Display the month name for this report
    $SQL = 'SELECT `lastday` FROM `periods` WHERE `periodno`=' . $period;
    $periodname=convertDate(singleResult($SQL,$db));

    // Get the actual report data for this period
    $S01="SELECT SUM(`total`) FROM `sar` WHERE `period`=" . $period;
    $r1=singleResult($S01,$db);
    $S02="SELECT SUM(`TOTAL`) FROM `pshr` WHERE `period`=" . $period;
    $r2=singleResult($S02,$db);
    $S03="SELECT COUNT(*) FROM `sar` WHERE `period`=" . $period;
    $r3=singleResult($S03,$db);
    $S04="SELECT COUNT(*) FROM `pshr` WHERE `period`=" . $period;
    $r4=singleResult($S04,$db);

    // We will use rownum to keep track of light and dark rows
    $rownum=1;
    // The following variables are used to calculate totals for the month
    // Display totals
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
    echo "<th align=\"left\">$periodname<td $id align=\"right\">" . $r1
	. "</td><td $id align=\"right\">" . $r3 
	. "</td><td $id align=\"right\">" . $r2 
	. "</td><td $id align=\"right\">" . $r4 
      . "</td></tr>\n";
      }
    echo "</table>\n";

    echo "<p></p>\n";
    echo "</center>\n";
?>
  </div>
<?php
    sectLeaders($db);
    footer($starttime,$maxdate,
      "\$Revision: 1.1 $ - \$Date: 2012-06-15 14:13:48-05 $");
}
?>
</div>
</body>
</html>
