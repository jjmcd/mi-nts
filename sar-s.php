<?php
//    sar.php
//    $Revision: 1.14 $ - $Date: 2007/08/31 19:15:05 $
//
//    sar displays a table of Station Activity Reports.  Beneath the
//    report it displays links to the previous and following 3 months
//    if any reports exist for that period.  Each call is a link to
//    sari.php which displays the reports available for that call.
//
//    sar.php accepts a single, named argument, period.  If the
//    argument is omitted, sar searches for the most recent period
//    for which there is data and uses that.
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
	$SQL="SELECT MAX(period) FROM sar";
	$period = singleResult($SQL,$db);;
    }
 
    echo "    <center>\n";

    // Display the month name for this report
    $SQL = 'SELECT lastday FROM periods WHERE periodno=' . $period;
    $usedate=singleResult($SQL,$db);
    echo "<p><h1>Station Activity Report for " . convertDate($usedate) . "</h1></p>\n";

    // Get the actual report data for this period
    $SQL='SELECT call,total,notes,updated FROM `sar` WHERE `period`='
        . $period . ' ORDER BY total DESC';
    $result=getResult($SQL,$db);

    echo "<table>\n";
    echo "<tr><th>Call</th><th>Total</th><th>Comments</th></tr><tr>\n";

    // We will remember rownum to alternate colors on the table
    $rownum=1;
    while ($myrow = getRow($result,$db) )
	{
	    // Keep track of the last date
	    if ( $myrow[3] > $maxdate )
		$maxdate = $myrow[3];
	    // Calculate the link to the individual's reports
	    $callrow='<a class="OsRowL" href="sari.php?call=' . $myrow[0] .'">' . $myrow[0] . "</a>";
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
	    // Now display the data
	    echo "  <tr id=" . $class1 . ">\n";
	    echo "    <td id=" . $class2 . ">" . $callrow . "</td>\n"; // Call
	    echo "    <td id=" . $class2 . " align=\"right\">" . $myrow[1] . "</td>\n"; // Total
	    echo "    <td id=" . $class2 . ">" . $myrow[2] . "</td>\n"; // Comments
	    echo "  </tr>\n";
	}
    echo "</table>\n";

    echo "<P>\n";
    dateLinks($period,"sar",$db);
    echo "</center>\n";?>
  </div>
<?php
    sectLeaders($db);
    footer($starttime,$maxdate,
      "\$Revision: 1.14 $ - \$Date: 2007/08/31 19:15:05 $");
?>
</div>
</body>
</html>
