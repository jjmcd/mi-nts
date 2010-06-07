<?php
//    index.php
//    $Revision: 1.6 $ - $Date: 2007/08/30 23:22:13 $
//
//    index is the opening page of the mi-nts website.  It displays the
//    standard menu, and then only some text introducing the site.
//
    include('includes/session.inc');
    $title=_('Michigan Section NTS');

    include('includes/functions.inc');

    $db = mysql_connect($host , $dbuser, $dbpassword);
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
	$SQL="SELECT MAX(period) FROM netreport";
	$period = singleResult($SQL,$db);;
    }
 
    echo "    <center>\n";

    // Display the month name for this report
    $SQL = 'SELECT lastday FROM periods WHERE periodno=' . $period;
    echo "    <p><h1>Public Service Honor Roll<br>for " . convertDate(singleResult($SQL,$db)) . "</h1></p>\n";

    // Get the actual report data for this period
    $SQL='SELECT call,cat1,cat2,cat3,cat4,cat5,cat6,total,comment,updated FROM `pshr` WHERE `period`='
        . $period . ' ORDER BY total DESC';
    $result=getResult($SQL,$db);

    echo "    <table>\n";
    echo "      <th>Call<th>Nets<th>Tfc<th>Appt<th>Plan<th>Emrg<th>Digi<th>Total<th>Comments<tr>\n";

    // We will remember rownum to alternate colors in the table
    $rownum=1;
    // Now loop through the rows of the data
    while ($myrow = getRow($result,$db) )
	{
	    // Keep track of the latest data
	    if ( $myrow[9] > $maxdate )
		$maxdate = $myrow[9];

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
	    // Calculate a link to the individual's reports
	    $callrow='<a id="OsRowL" href="pshri.php?call=' . $myrow[0] .'">' . $myrow[0] . '</A>';

	    // Now display the row
	    echo "      <tr id=\"" . $class1 . "\">\n";
	    echo '        <td id="' . $class2 . '">' . $callrow . "</td>\n";
	    echo '        <td id="' . $class2 . '" align="right">' . $myrow[1] . "</td>\n";
	    echo '        <td id="' . $class2 . '" align="right">' . $myrow[2] . "</td>\n";
	    echo '        <td id="' . $class2 . '" align="right">' . $myrow[3] . "</td>\n";
	    echo '        <td id="' . $class2 . '" align="right">' . $myrow[4] . "</td>\n";
	    echo '        <td id="' . $class2 . '" align="right">' . $myrow[5] . "</td>\n";
	    echo '        <td id="' . $class2 . '" align="right">' . $myrow[6] . "</td>\n";
	    echo '        <td id="' . $class2 . '" align="right">' . $myrow[7] . "</td>\n";
	    echo '        <td id=' . $class2 . '>' . $myrow[8] . "</td>\n";
	    echo "      </tr>\n";
	}
    echo "    </table>\n";


    echo "    <p>\n";
    dateLinks($period,"pshr",$db);
    echo "    </center>\n";
?>
  </div>
<?php
    sectLeaders($db);
    footer($starttime,$maxdate,
      "\$Revision: 1.6 $ - \$Date: 2007/08/30 23:22:13 $");
?>
</div>
</body>
</html>
