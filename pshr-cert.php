<?php
//    pshr-cert.php
//    $Revision: 1.11 $ - $Date: 2011-01-17 13:37:44-05 $
//
//    Calculate list of stations eligible for a PSHR certificate
//
{
  // Return a link to the individual reports for a call
  function callsign( $call )
  {
    $value = "<a href=\"pshri.php?call=" . $call . "\">" . $call . "</a>";
    return $value;
  }

    include('includes/session.inc');
    $title=_('Michigan Section NTS');

    include('includes/miscFunctions.inc');

    $db = openDatabase( $dppE );
    mysql_select_db($DatabaseName,$db);

    NTSheader($title,"NTS Michigan Section Traffic");

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
	$SQL="SELECT MAX(`period`) FROM `pshr`";
	$period = singleResult($SQL,$db);;
    }
 
    $startperiod = $period-23;
    $shortperiod = $period-11;
    echo "    <center>\n";

    // Display the month name for this report
    // Get names for last period, 12 and 24 months ago
    $SQL  = 'SELECT `lastday` FROM `periods` WHERE `periodno`=' . $period;
    $SQL0 = 'SELECT `lastday` FROM `periods` WHERE `periodno`=' . $startperiod;
    $SQL5 = 'SELECT `lastday` FROM `periods` WHERE `periodno`=' . $shortperiod;
    $date1 = convertDate(singleResult($SQL0,$db));
    $date2 = convertDate(singleResult($SQL5,$db));
    $date3 = convertDate(singleResult($SQL,$db));
    echo "    <p><h1>Public Service Honor Roll from<br> " . 
       $date1 . " to " . $date3 . "</h1></p>\n";
    $shortstring= $date2 . " - " . $date3;
    echo "<h2>Stations eligible for certificate</h2>\n";
    echo "    </center>\n";

    // Find out all calls that MIGHT be eligible
    $SQL1="SELECT DISTINCT `call` FROM `pshr` WHERE `total`>69 AND `period` "
      . "BETWEEN " . $startperiod . " AND " . $period .
      " ORDER BY `call`";
    $result1=getResult($SQL1,$db);
    // Now loop through the candidates
    while ( $row1=getRow($result1,$db) )
      {
	//echo "<p>" . $row1[0];
	// Count up number of 70 or higher reports in past 12 months
	$SQL2="SELECT COUNT(*) FROM `pshr` WHERE `total`>69 AND `period` "
	  . "BETWEEN " . $shortperiod . " AND " . $period .
	  " AND `call`='" . $row1[0] . "'";
	// If >11, then this station is eligible
	if ( singleResult($SQL2,$db)>11 )
	  echo "<p>" . callsign($row1[0]) . " &nbsp; eligible - >=70 " . 
	    $shortstring;
	else
	  {
	    // Count up number of 70 or higher in past 24 months
	    $SQL3="SELECT COUNT(*) FROM `pshr` WHERE `total`>69 AND `period` "
	      . "BETWEEN " . $startperiod . " AND " . $period .
	      " AND `call`='" . $row1[0] . "'";
	    $count = singleResult($SQL3,$db);
	    // If >17 then this station is eligible
	    if ( $count > 17 )
	      {
		echo "<p>" . callsign($row1[0]) . " &nbsp; eligible - >=70 " . 
		  $count . " months in past 24";
		echo "<br />\n";
		// Get the list of qualifying months
		$SQL4="SELECT `lastday` FROM `pshr` A, `periods` B " .
		  "WHERE  `total`>69 AND `period` BETWEEN " . $startperiod .
		  " AND " . $period . " AND `call`='" . $row1[0] .
		  "' AND A.`period`=B.`periodno` ORDER BY A.`period`";
		$result4=getResult($SQL4,$db);
		$dashed=0;
		while ( $row4=getRow($result4,$db) )
		  {
		    if ( $dashed )
		      echo " - ";
		    $dashed=1;
		    echo convertDateShort($row4[0]);
		  }
	      }
	  }
	echo "</p>\n";
      }

?>
  </div>
<?php
    sectLeaders($db);
    footer($starttime,$maxdate,
	   "\$Revision: 1.1 $ - \$Date: 2011-01-17 13:37:44-05 $");
}
?>
</div>
</body>
</html>
