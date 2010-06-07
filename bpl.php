<?php
//    sar.php
//    $Revision: 1.16 $ - $Date: 2008-01-22 19:28:25-05 $
//
//    bpl displays a table of Brass Pounder League.  Beneath the
//    report it displays links to the previous and following 3 months
//    if any reports exist for that period.  Each call is a link to
//    bpli.php which displays the reports available for that call.
//
//    bpl.php accepts a single, named argument, period.  If the
//    argument is omitted, bpl searches for the most recent period
//    for which there is data and uses that.
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
	$SQL="SELECT MAX(period) FROM bpl";
	$period = singleResult($SQL,$db);;
    }
 
    echo "    <center>\n";

    // Display the month name for this report
    $SQL = 'SELECT lastday FROM periods WHERE periodno=' . $period;
    $usedate=singleResult($SQL,$db);
    echo "<p><h1>Brass Pounder's League for " . convertDate($usedate) 
     . "</h1></p>\n";

    // Get the actual report data for this period
    $SQL='SELECT `call`,`total`,`orig`,`sent`,`recd`,`deld`,`updated` FROM `bpl` WHERE `period`='
      . $period . ' ORDER BY `total` DESC';
    $result=getResult($SQL,$db);

    echo "<table width=\"80%\">\n";
    echo "<tr><th>Call</th><th>Orig</th><th>Sent</th><th>Rec'd</th><th>Del'd</th><th>Total</th></tr><tr>\n";

    // We will remember rownum to alternate colors on the table
    $rownum=1;
    while ($myrow = getRow($result,$db) )
	{
	  $Obo = 0;
	  if ( $myrow[2]>100 ) $Obo = 1;
	  elseif ( ($myrow[2]+$myrow[5])>100 && $myrow[5]<100 ) $Obo = 1;
	  $Dbo = 0;
	  if ( $myrow[5]>100 ) $Dbo = 1;
	  elseif ( ($myrow[2]+$myrow[5])>100 && $myrow[2]<100 ) $Dbo = 1;
	  $Tbo = 0;
	  if ( $myrow[1]>500 ) $Tbo = 1;
	    // Keep track of the last date
	    if ( $myrow[6] > $maxdate )
		$maxdate = $myrow[6];
	    // Calculate the link to the individual's reports
	    $callrow='<a class="OsRowL" href="bpli.php?call=' 
		. $myrow[0] .'">' . $myrow[0] . "</a>";
	    // Not ready yet
	    $callrow = "<b>" . $myrow[0] . "</b>";
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
	    if ( $Obo == 0 )
	      echo "    <td id=" . $class2 . " align=\"right\">" 
		. $myrow[2] . "</td>\n"; // orig
	    else
	      echo "    <td id=" . $class2 . " align=\"right\"><b>" 
		. $myrow[2] . "</b></td>\n"; // orig
	    echo "    <td id=" . $class2 . " align=\"right\">" 
		. $myrow[3] . "</td>\n"; // sent
	    echo "    <td id=" . $class2 . " align=\"right\">" 
		. $myrow[4] . "</td>\n"; // recd
	    if ( $Dbo == 0 )
	      echo "    <td id=" . $class2 . " align=\"right\">" 
		. $myrow[5] . "</td>\n"; // deld
	    else
	      echo "    <td id=" . $class2 . " align=\"right\"><b>" 
		. $myrow[5] . "</b></td>\n"; // deld
	    if ( $Tbo == 0 )
	      echo "    <td id=" . $class2 . " align=\"right\">" 
		. $myrow[1] . "</td>\n"; // Total
	    else
	      echo "    <td id=" . $class2 . " align=\"right\"><b>" 
		. $myrow[1] . "</b></td>\n"; // Total
	    echo "  </tr>\n";
	}
    echo "</table>\n";

    echo "<P>\n";
    dateLinks($period,"bpl",$db);
    echo "</center>\n";?>
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
