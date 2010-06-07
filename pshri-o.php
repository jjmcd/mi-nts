<?php

//    pshri.php
//    $Revision: 1.6 $ - $Date: 2007/08/30 23:22:31 $
//
//    pshri displays a table of Public Service Honor Roll Reports
//    for a specific call. All reports in the databse for that
//    call are displayed.
//
//    pshr.php accepts a single, named argument, call.  If the
//    argument is omitted, or the provided call is not found in
//    the database, an empty table will be displayed.
//
{
    include('includes/session.inc');
    $title=_('Michigan Section NTS');

    include('includes/functions.inc');

    $db = mysql_connect($host , $dbuser, $dbpassword);
    mysql_select_db($DatabaseName,$db);

    NTSheader($title,"NTS Michigan Section Traffic");

    leftBar( $db );

    // Remember the launch time
    $starttime = strftime("%A, %B %d %Y, %H:%M");

    echo '  <div id="main">' . "\n";

    // Initialize the last date counter
    $maxdate=0;
   
    // Get the call from the argument list
    $call = $_GET['call'];

    echo "<center>\n";

    // Display the call for this report
    echo "<p><h1>Public Service Honor Roll for " . $call . "</h1></p>\n";


    // Get the actual report data for this call
    $SQL="SELECT period,cat1,cat2,cat3,cat4,cat5,cat6,total,comment,updated " .
	"FROM `pshr` WHERE `call`='" . $call . "' ORDER BY period DESC";
    $result=getResult($SQL,$db);

    echo "<table>\n";
    echo "<th>Period<th>Nets<th>Tfc<th>Appt<th>Pl<th>Unpl<th>BBS<th>" . 
	"Total<th>Comments<tr>\n";

    // Loop through the result set and display the data
    $rownum=1;
    while ($myrow = getRow($result,$db) )
	{
	    if ( $myrow[9] > $maxdate )
		$maxdate = $myrow[9];
	    $SQL2 = "SELECT lastday FROM periods WHERE periodno = " . $myrow[0];
	    $result2 = getResult($SQL2,$db);
	    $myrow2 = getRow($result2,$db);

	    $displaydate=convertDateShort($myrow2[0]);
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
	    echo '    <td id=' . $class2 . '>' . $displaydate . "</TD>\n";
	    echo '    <td id=' . $class2 . ' align="right">' . $myrow[1] . "</TD>\n";
	    echo '    <td id=' . $class2 . ' align="right">' . $myrow[2] . "</TD>\n";
	    echo '    <td id=' . $class2 . ' align="right">' . $myrow[3] . "</TD>\n";
	    echo '    <td id=' . $class2 . ' align="right">' . $myrow[4] . "</TD>\n";
	    echo '    <td id=' . $class2 . ' align="right">' . $myrow[5] . "</TD>\n";
	    echo '    <td id=' . $class2 . ' align="right">' . $myrow[6] . "</TD>\n";
	    echo '    <td id=' . $class2 . ' align="right">' . $myrow[7] . "</TD>\n";
	    echo '    <td id=' . $class2 . '>' . $myrow[8] . "</TD>\n";
	    echo "  </TR>\n";
	}
    echo "</table>\n";


    echo "</center>\n";
    echo "</div>\n";
    sectLeaders($db);
    footer($starttime,$maxdate,"\$Revision: 1.6 $ - \$Date: 2007/08/30 23:22:31 $");
}
?>
</body>
</html>
