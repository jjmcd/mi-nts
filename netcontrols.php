<?php
//    netcontrols.php
//    $Revision: 1.16 $ - $Date: 2008-01-22 19:28:25-05 $
//
//
{
    include('includes/session.inc');
    $title=_('Michigan Section NTS');

    include('includes/miscFunctions.inc');

    $db = openDatabase( $dppE );
    mysql_select_db($DatabaseName,$db);

    NTSheader($title,"NTS Michigan Section Liaisons");

    // Remember the launch time
    $starttime = strftime("%A, %B %d %Y, %H:%M");
    $maxdate = 0;

    leftBar( $db );
?>
  <div id="main">

<?php
     // Get the requested net
     $netid = $_GET['netid'];
     if ( $netid < 1 )
       $netid = 1;

    // Initialize the latest data counter
    $maxdate=0;

    $SQLA = "SELECT netfullname FROM nets WHERE netid=" . $netid;
    $netname = singleResult( $SQLA, $db );
 
    echo "<p>&nbsp;</p>\n";
    echo "    <center>\n";
    echo "      <h1>Net Controls</h1>\n";
    echo "      <h3>" . $netname
     . "</h3>\n";
    echo "<p>&nbsp;</p>\n";

    echo "<table cellpadding=\"5px\">\n";
    echo "  <tr>\n";
    echo "    <th>Day</th>\n";

    // Get data for net
    $SQL1="SELECT `dow`, `call`,`updated` FROM `net_controls` "
      . "WHERE `netid`=" . $netid . " ORDER BY `dow`";
    $res1 = getResult( $SQL1, $db );
    $otherid = $netid + 1000;
    $SQL2="SELECT COUNT(*) FROM `net_controls` "
      . "WHERE `netid`=" . $otherid;
    $more = singleResult( $SQL2, $db );
    if ( $more == 0 )
      {
	echo "    <th>Net Control</th>\n";
	echo "  </tr>\n";
	while ( $row1 = getRow( $res1, $db ) )
	  {
	    // Change the coded day of week into text
	    $SQL3 = "SELECT `day_of_week` FROM `rep_dow` WHERE `id`="
	      . $row1[0];
	    $daytext = singleResult( $SQL3, $db );
	    echo "  <tr>\n";
	    echo "    <th>" . $daytext . "</th>\n";
	    
	    if ( $row1[1] )
	      {
		echo "    <td align=\"center\" style=\"background:white;\">" . $row1[1] . "</td>\n";
		if ( $maxdate < $row1[2] )
		  $maxdate = $row1[2];
	      }
	    else
	      echo "    <td>&nbsp;</td>\n";
	    echo "  </tr>\n";
	  }
      }
    else
      {
	echo "    <th>Early NCS</th>\n    <th>Late NCS</th>\n";
	echo "  </tr>\n";
	for ( $day=0; $day<7; $day ++ )
	  {
	    $SQL3 = "SELECT `day_of_week` FROM `rep_dow` WHERE `id`="
	      . $day;
	    $daytext = singleResult( $SQL3, $db );
	    echo "  <tr>\n";
	    echo "    <th>" . $daytext . "</th>\n";
	    $SQL4 = "SELECT `call`,`updated` FROM `net_controls` " .
	      "WHERE `netid`=" . $netid . " AND `dow`=" . $day;
	    $res4 = getResult( $SQL4, $db );
	    if ( $row4 = getRow($res4,$db) )
	      {
		echo "    <td align=\"center\" style=\"background:white;\">" . $row4[0] . "</td>\n";
		if ( $maxdate < $row4[1] )
		  $maxdate = $row4[1];
	      }
	    else
		echo "    <td>&nbsp;</td>\n";
	    $SQL5 = "SELECT `call`,`updated` FROM `net_controls` " .
	      "WHERE `netid`=" . $otherid . " AND `dow`=" . $day;
	    $res5 = getResult( $SQL5, $db );
	    if ( $row5 = getRow($res5,$db) )
	      {
		echo "    <td align=\"center\" style=\"background:white;\">" . $row5[0] . "</td>\n";
		if ( $maxdate < $row5[1] )
		  $maxdate = $row5[1];
	      }
	    else
		echo "    <td>&nbsp;</td>\n";
	    echo "  </tr>\n";
	  }
      }

    echo "</table>\n";
    echo "<p>&nbsp;</p>\n";
    echo "<a href=\"ncsselect.php\">Back to select net</a>\n";
    echo "<p>&nbsp;</p>\n";

    echo "</center>\n";
?>
  </div>
<?php
    sectLeaders($db);
    footer($starttime,$maxdate,
      "\$Revision: 1.1 $ - \$Date: 2010-02-20 19:28:25-05 $");
}
?>
</div>
</body>
</html>
