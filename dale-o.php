<?php
//    sar.php
//    $Revision: 1.1 $ - $Date: 2006-01-16 09:31:46-05 $
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
    include('includes/miscFunctions.inc');
    include('includes/session.inc');
    $title=_('Michigan Section NTS');

    $db = mysql_connect($host , $dbuser, $dbpassword);
    mysql_select_db($DatabaseName,$db);

    include('includes/mi-nts-header.inc');

    // Remember the launch time
    $starttime = strftime("%A, %B %d %Y, %H:%M");

    echo '<P></P>' . "\n";

    // Initialize the variable that will hold the last update date
    $maxdate=0;

    // Get the requested period, if blank choose the latest
    $period = $_GET['period'];
    if ( $period < 1 )
    {
	$SQL="SELECT MAX(period) FROM netreport";
	$period=singleResult($SQL,$db);
    }

    echo "<CENTER>\n";

    // Display the month name for this report
    $SQL = 'SELECT lastday FROM periods WHERE periodno=' . $period;
    $usedate=singleResult($SQL,$db);

    // Get the actual report data for this period
    $SQL='SELECT `call`,`total`,`notes`,`updated` FROM `sar` WHERE `period`='
        . $period . ' ORDER BY `total` DESC';
    $result=getResult($SQL,$db);
    echo "</CENTER>\n";

    echo "<P>SARs for " . convertDate($usedate) . ": ";

    $counter=0;
    while ($myrow = getRow($result,$db) )
	{
	    if ( $counter>0 )
		echo ", ";
	    if ( $myrow[3] > $maxdate )
		$maxdate = $myrow[3];
	    // Now display the data
	    echo $myrow[0] . " " . $myrow[1];
	    $counter = $counter+1;
	}
    echo ".</P>\n";

    // Get the actual report data for this period
    $SQL='SELECT `call`,`total`,`updated` FROM `pshr` WHERE `period`='
        . $period . ' ORDER BY `total` DESC';
    $result=getResult($SQL,$db);
    echo "</CENTER>\n";

    echo "<P>PSHRs for " . convertDate($usedate) . ": ";

    $counter=0;
    while ($myrow = getRow($result,$db) )
	{
	    if ( $counter>0 )
		echo ", ";
	    if ( $myrow[2] > $maxdate )
		$maxdate = $myrow[2];
	    // Now display the data
	    echo $myrow[0] . " " . $myrow[1];
	    $counter = $counter+1;
	}
    echo ".</P>\n";

    echo "<P>\n";
    footer($starttime,$maxdate,"\$Revision: 1.1 $ - \$Date: 2006-01-16 09:31:46-05 $");

?>
</BODY>
</HTML>
