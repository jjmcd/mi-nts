<?php
//    sari.php
//    $Revision: 1.4 $ - $Date: 2007/09/01 16:30:08 $
//
//    pshri displays a table of Station Activity Reports
//    for a specific call. All reports in the databse for that
//    call are displayed.
//
//    pshri.php accepts a single, named argument, call.  If the
//    argument is omitted, or the provided call is not found in
//    the database, an empty table will be displayed.
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
    // Initialize the last date counter
    $maxdate=0;

    // Get the call from the argument list
    $call = $_GET['call'];

    echo "<center>\n";

    // Display the call for this report
    echo "<p><h1>Station Activity Report for " . $call . "</h1></p>\n";

    // Get the actual report data for this period

    $SQL="SELECT period,total,notes,updated FROM `sar` WHERE `call`='"
        . $call . "' ORDER BY period DESC";
    $result=getResult($SQL,$db);

    echo "<table>\n";
    echo "<tr><th>Month</th><th>Total</th><th>Comments</th></tr>\n";

    // Loop through the result set and get the data
    $rownum=1;
    while ($myrow = getRow($result,$db) )
	{
	    // Keep track of the most recent
	    if ( $myrow[3] > $maxdate )
		$maxdate = $myrow[3];
	    // Get the data for this period
	    $SQL2 = "SELECT lastday FROM periods WHERE periodno = " . $myrow[0];
	    $result2 = getResult($SQL2,$db);
	    $myrow2 = getRow($result2,$db);
	    $displaydate=convertDate($myrow2[0]);
	    // Alternate light and dark rows
	    if ( $rownum != 0 )
		{
		    $class1 = 'OsRow';
		    $class2 = 'OsRow3';
		    $rownum = 0;
		}
	    else
		{
		    $class1 = 'OsRow2';
		    $class2 = 'OsRow4';
		    $rownum = 1;
		}
	    echo "  <tr id=" . $class1 . ">\n";
	    echo '    <td>' . $displaydate . "</td>\n";
	    echo '    <td align="right">' . $myrow[1] . "</td>\n";
	    echo '    <td id=' . $class2 . '>' . $myrow[2] . "</td>\n";
	    echo "  </tr>\n";
	}
    echo "</table>\n";

    echo "</center>\n";?>
  </div>
<?php
    sectLeaders($db);
    footer($starttime,$maxdate,
      "\$Revision: 1.4 $ - \$Date: 2007/09/01 16:30:08 $");
?>
</div>
</body>
</html>
