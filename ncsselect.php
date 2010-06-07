<?php
//    ncsselect.php
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

    echo "    <p>&nbsp;</p>\n";
    echo "    <center>\n";
    echo "      <h1>Net Controls</h1>\n";
    echo "      <p>&nbsp;</p>\n";
    echo "      <p>Clicking on a net below will launch a page detailing\n";
    echo "        net control assignments for that net. Only those nets\n";
    echo "        which have supplied data are listed.</p>\n";
    echo "      <p>&nbsp;</p>\n";
    echo "      <table>\n";

    $SQL1 = "SELECT DISTINCT `netid` FROM `net_controls` "
      . "WHERE netid < 1000 ORDER BY `netid`";
    $res1 = getResult( $SQL1, $db );

    while ( $row1 = getRow( $res1, $db ) )
      {
	$SQL2 = "SELECT `netfullname`, `updated` FROM `nets` " .
	  "WHERE `netid`=" . $row1[0];
	$res2 = getResult( $SQL2, $db );
	if ( $row2 = getRow( $res2, $db ) )
	  {
	    echo "        <tr><th style=\"padding: 1em; \">";
	    echo "<a href=\"netcontrols.php?netid=" . $row1[0] . "\">";
	    echo $row2[0];
	    echo "</a></th></tr>\n";
	    if ( $row2[1] > $maxdate )
	      $maxdate = $row2[1];
	  }
      }
    echo "      </table>\n";
    echo "    </center>\n";
    echo "    <p>&nbsp;</p>\n";
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
