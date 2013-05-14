<?php
//    dale.php
//    $Revision: 1.2 $ - $Date: 2011-02-13 15:14:52-05 $
//
//    dale.php displays a summary of the PSHR and SAR reports
//    for inclusion in the Section Manager's monthly newsletter.
//
//    The intent is that the SM simply cut and paste the values
//    into the newsletter, thus reducing transcription errors.
//
{
    include('includes/session.inc');
    include('includes/miscFunctions.inc');
    $title=_('Michigan Section NTS');

    $db = openDatabase( $dppE );
    mysql_select_db($DatabaseName,$db);


    // Remember the launch time
    $starttime = strftime("%A, %B %d %Y, %H:%M");

    echo '<HTML><HEAD><TITLE>' . $title . '</TITLE>' . "\n";
    echo '<link REL="shortcut icon" HREF="favicon.ico">' . "\n";
    echo '<link REL="icon" HREF="favicon.ico">' . "\n";
    echo '<meta http-equiv="Content-Type" content="text/html; charset=' 
	. _('ISO-8859-1') . '">' . "\n";
    echo '<LINK HREF="css/'. $_SESSION['Theme'] 
	.'/default.css" REL="stylesheet" TYPE="text/css">' . "\n";
    echo '</HEAD>' . "\n";
    
    echo '<BODY>' . "\n";
    echo "<center>\n";
    echo '<b>Michigan Section NTS Summary PSHR and SAR data</b><BR>' . "\n";
    echo "for Section Manager's monthly newsletter\n";
    echo "</center>\n";
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

    echo "<table class=\"main_page\">\n";
    echo "  <tr><td>&nbsp;</td></tr>\n";
    echo "  <tr><td>\n";

    // Display the month name for this report
    $SQL = 'SELECT lastday FROM periods WHERE periodno=' . $period;
    $usedate=singleResult($SQL,$db);

    // Get the actual report data for this period
    $SQL='SELECT `call`,`total`,`notes`,`updated` FROM `sar` WHERE `period`='
        . $period . ' ORDER BY `total` DESC';
    $result=getResult($SQL,$db);

    echo "    <P>SARs for " . convertDate($usedate) . ": ";

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
    echo ".    </P>\n";
    $SQL="SELECT SUM(`TOTAL`) FROM `sar`" .
      " WHERE `period`=" . $period;
    echo "<p>Total SAR reported: " . singleResult($SQL,$db) . "</p>\n";

    // Get the actual report data for this period
    $SQL='SELECT `call`,`total`,`updated` FROM `bpl` WHERE `period`='
        . $period . ' ORDER BY `total` DESC';
    $result=getResult($SQL,$db);

    echo "    <P>BPL for " . convertDate($usedate) . ": ";

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
    echo ".    </P>\n";

    // Get the actual report data for this period
    $SQL='SELECT `call`,`total`,`updated` FROM `pshr` WHERE `period`='
        . $period . ' ORDER BY `total` DESC';
    $result=getResult($SQL,$db);

    echo "    <P>PSHRs for " . convertDate($usedate) . ": ";

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
    echo ".    </P>\n";

    echo "<p><b>WARNING: </b>Net data presented two different ways,<br/>\n";
    echo "by net acronym and net full name.</p>\n";

    // Get the actual report data for this period
    $SQL='SELECT `netid`,`qtc`,`updated` FROM `netreport` WHERE `period`='
        . $period . ' ORDER BY `qtc` DESC';
    $result=getResult($SQL,$db);

    echo "    <P>Net traffic for " . convertDate($usedate) . ": ";

    $counter=0;
    while ($myrow = getRow($result,$db) )
	{
	    if ( $counter>0 )
		echo ", ";
	    if ( $myrow[2] > $maxdate )
		$maxdate = $myrow[2];
	    $SQL2="SELECT `netacro` FROM `nets` WHERE `netid`=" . $myrow[0];
	    $netacro=singleResult($SQL2,$db);
	    // Now display the data
	    echo $netacro . " " . $myrow[1];
	    $counter = $counter+1;
	}
    echo ".    </P>\n";


    // Get the actual report data for this period
    $SQL='SELECT `netid`,`qtc`,`updated` FROM `netreport` WHERE `period`='
        . $period . ' ORDER BY `qtc` DESC';
    $result=getResult($SQL,$db);

    echo "    <P>Net traffic for " . convertDate($usedate) . ": ";

    $counter=0;
    while ($myrow = getRow($result,$db) )
	{
	    if ( $counter>0 )
		echo ", ";
	    if ( $myrow[2] > $maxdate )
		$maxdate = $myrow[2];
	    $SQL2="SELECT `netfullname` FROM `nets` WHERE `netid`=" . $myrow[0];
	    $netname=singleResult($SQL2,$db);
	    // Now display the data
	    echo $netname . " " . $myrow[1];
	    $counter = $counter+1;
	}
    echo ".    </P>\n";
    $SQL="SELECT SUM(`QTC`) FROM `netreport`" .
      " WHERE `period`=" . $period;
    echo "<p>Total net traffic reported: " . singleResult($SQL,$db) . "</p>\n";


    echo "  </td></tr>\n";
    echo "  <tr><td>&nbsp;</td></tr>\n";
    echo "</table>\n";
    echo "<P>&nbsp;</p>\n";

    echo "<div id=\"footer\">\n";
    footer($starttime,$maxdate,
	   "\$Revision: 1.0 $ - \$Date: 2013-03-15 13:27:48-04 $");
    echo "</div>\n";
}
?>
</BODY>
</HTML>
