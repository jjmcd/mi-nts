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
 
    $endp= $period;
    $startp=$endp-11;

    echo "    <center>\n";
    echo "      <h2>12-month report history</h2>\n";
    echo "      <table>\n";

    $SQ1="SELECT `NETID`,count(*) AS Reports FROM `netreport` " .
        "WHERE `PERIOD` BETWEEN " . $startp . " AND " . $endp .
        " GROUP BY `NETID` " .
	"ORDER BY Reports DESC, `NETID`";
    $rs1=getResult($SQ1,$db);
    while ( $myr1 = getRow($rs1,$db) )
    {
        echo "        <tr>\n";
	$SQ2="SELECT `NETACRO`,`NETFULLNAME` FROM `nets` WHERE `NETID`=" .
	    $myr1[0];
	$rs2=getResult($SQ2,$db);
	$myr2 = getRow($rs2,$db);
	$netname=substr($myr2[1],0,25);
        echo "          <th>" . $myr2[0] . "</th>\n";
        echo "          <td>" . $netname . "</td>\n";
	echo "          <th>" . $myr1[1] . "</th>\n";
	for ( $p=0; $p<12; $p++ )
	{
	   $thisp=$startp+$p;
	   $SQ3="SELECT COUNT(*) FROM `netreport` " .
	       "WHERE `netid`=" . $myr1[0] . " AND `period`=" . $thisp;
	   $rs3=singleResult($SQ3,$db);
	   if ( $rs3==1 )
	       echo "          <td>X</td>\n";
	   else
	       echo "          <td style=\"background-color: red;\">-</td>\n";
        }
	echo "        </tr>\n";
    }
    echo "      </table>\n";


    echo "<p></p>\n";
    dateLinks($period,"netsummary",$db);
    echo "</center>\n";
?>
  </div>
<?php
    sectLeaders($db);
    footer($starttime,$maxdate,
      "\$Revision: 1.11 $ - \$Date: 2010-01-31 07:19:48-05 $");
}
?>
</div>
</body>
</html>
