<?php
//    netinfo.php
//    $Revision: 1.5 $ - $Date: 2008-01-22 13:35:03-05 $
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
<p>The following nets have reported their activity during the previous year.</p>
<p>HF nets are listed in order of meeting time.  All others are listed aphabetically.</p>
<?php
    // Initialize the latest data counter
    $maxdate=0;

    echo '<h1>Affiliated Nets</h1>' . "\n";

    // Each call to ShowNets() executes the provided SQL depending on
    // getting the correct number of columns, then displays a table
    // of the information about the net

    echo	'<h3>HF Nets</h3>' . "\n";
    $SQL = 'SELECT `netacro`,`netfullname`,`netmanager`,`days1`,`time1`,`freq1`,' .
	'`days2`,`time2`,`freq2`,`comments`,`updated`' .
	' FROM nets' .
	' WHERE nethf=1 AND netaffil=1' .
	' ORDER BY time1, freq1';
    Show_Nets($db,$SQL);

    echo	'<h3>VHF Nets</h3>' . "\n";
    $SQL = 'SELECT `netacro`,`netfullname`,`netmanager`,`days1`,`time1`,`freq1`,' .
	'`days2`,`time2`,`freq2`,`comments`,`updated`' .
	' FROM nets' .
	' WHERE nethf=0 AND netaffil=1' .
	' ORDER BY netfullname';
    Show_Nets($db,$SQL);

    //echo '<h3>Probationary Nets</h3>' . "\n";
    //$SQL = 'SELECT `netacro`,`netfullname`,`netmanager`,`days1`,`time1`,`freq1`,' .
    //	'`days2`,`time2`,`freq2`,`comments`,`updated`' .
    //	' FROM `nets`' .
    //	' WHERE `netaffil`=2' .
    //	' ORDER BY `netfullname`';
    //Show_Nets($db,$SQL);

    echo '<h1>Other Reporting Nets</h1>' . "\n";
    $SQL = 'SELECT `netacro`,`netfullname`,`netmanager`,`days1`,`time1`,`freq1`,' .
	'`days2`,`time2`,`freq2`,`comments`,`updated`' .
	' FROM `nets`' .
	' WHERE `netaffil`=0' .
	' ORDER BY `netfullname`';
    Show_Nets($db,$SQL);
    echo "  </div>\n";

    sectLeaders($db);

    footer($starttime,$maxdate,
	   "\$Revision: 1.5 $ - \$Date: 2008-01-22 13:35:03-05 $");
}
?>
</div>
</body>
</html>
