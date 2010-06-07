<?php
//    rep_edit1.php
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
    // Initialize the latest data counter
    $maxdate=0;

 
    echo "    <center>\n";
    echo "<p><h1>Eighth Region Liaisons" 
     . "</h1></p>\n";
    echo "<p>&nbsp;</p>\n";

    echo "<table>\n";
    echo "  <tr>\n";
    echo "    <th>Day</th>\n";
    // Determine nets represented
    $SQL1="SELECT DISTINCT `net` FROM `rep_liaisons` ORDER BY `net`";
    $res1 = getResult( $SQL1, $db );

    while ( $row1 = getRow( $res1, $db ) )
      {
	$SQL2 = "SELECT `net_name` FROM `rep_nets` WHERE `id`="
	  . $row1[0];
	$net = singleResult( $SQL2, $db );
	if ( $net )
	  echo "    <th>" . $net . "</th>\n";
	else
	  echo "    <th>??" . $row1[0] . "</th>\n";
      }
    echo "  </tr>\n";
    for ( $daynum=0; $daynum<7; $daynum++ )
      {
	$SQL3 = "SELECT `day_of_week` FROM `rep_dow` WHERE `id`="
	  . $daynum;
	$daytext = singleResult( $SQL3, $db );
	echo "  <tr>\n";
	echo "    <th>" . $daytext . "</th>\n";

	$res1 = getResult( $SQL1, $db );

	while ( $row1 = getRow( $res1, $db ) )
	  {
	    $SQL4="SELECT `call` FROM `rep_liaisons` WHERE `day_of_week`="
	      . $daynum . " AND `net`=" . $row1[0];
	    $call = singleResult( $SQL4, $db );
	    if ( $call )
	      {
		echo "    <td align=\"center\">" . $call . "</td>\n";
		$SQL6="SELECT `updated` FROM `rep_liaisons` WHERE `day_of_week`="
		  . $daynum . " AND `net`=" . $row1[0];
		$updated = singleResult( $SQL6, $db );
		if ( $updated > $maxdate )
		  $maxdate = $updated;
	      }
	    else
	      echo "    <td>&nbsp;</td>\n";
	  }
	echo "  </tr>\n";
      }
    echo "</table>\n";
    echo "<p>&nbsp;</p>\n";

    echo "<p>&nbsp;</p>\n";

    echo "<form name=\"enter8rn\" method=\"post\" action=\"rep_edit2.php\">\n";
    echo "Net: <select name=\"net\">\n";
    echo "<option value=\"#\">Select Net</option>\n";
    $SQL5="SELECT id, net_name FROM rep_nets ORDER BY id";
    $res5 = getResult( $SQL5, $db );
    while ( $row5 = getRow( $res5, $db ) )
      {
	echo "<option value=\"" . $row5[0] . "\">" . $row5[1] . "</option>\n";
      }
    echo "</select>\n";
    echo "<p>&nbsp;</p>\n";

    echo "Day: <select name=\"day\">\n";
    echo "<option value=\"#\">Select Day</option>\n";
    $SQL6="SELECT id, day_of_week FROM rep_dow ORDER BY id";
    $res6 = getResult( $SQL6, $db );
    while ( $row6 = getRow( $res6, $db ) )
      {
	echo "<option value=\"" . $row6[0] . "\">" . $row6[1] . "</option>\n";
      }
    echo "</select>\n";
    echo "<p>&nbsp;</p>\n";

    echo "Call: <input name=\"call\">\n";
    echo "<p>&nbsp;</p>\n";
    echo "<input type=\"submit\" name=\"Submit\" value=\"Submit\">\n";
    echo "<p>&nbsp;</p>\n";

    echo "</form>\n";
    echo "</center>\n";
?>

  </div>
<?php
    sectLeaders($db);
    footer($starttime,$maxdate,
      "\$Revision: 1.16 $ - \$Date: 2008-01-22 19:28:25-05 $");
}
?>
</div>
</body>
</html>
