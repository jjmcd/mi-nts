<?php
//    rep_liaisons.php
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

    /*    // Get the actual report data for this period
    $SQL='SELECT `call`,`total`,`notes`,`updated` FROM `sar` WHERE `period`='
      . $period . ' ORDER BY `total` DESC';
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
	    $callrow='<a class="OsRowL" href="sari.php?call=' 
		. $myrow[0] .'">' . $myrow[0] . "</a>";
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
	    echo "    <td id=" . $class2 . " align=\"right\">" 
		. $myrow[1] . "</td>\n"; // Total
	    echo "    <td id=" . $class2 . ">" . $myrow[2] 
		. "</td>\n"; // Comments
	    echo "  </tr>\n";
	}
    echo "</table>\n";

    echo "<P>\n";
    dateLinks($period,"sar",$db); */
    echo "<p>Note that liaisons occasionally trade assignments when needed.</p>\n";
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
