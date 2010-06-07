<?php
{
    function addUpdate( $name, $value, $query )
	{
	    if ( $value != 'NULL' )
		{
		$query = $query . $name . '=' . $value . ", ";
		}
	    return $query;
	}

    include('includes/session.inc');
    $title=_('Michigan Section ARPSC');
    include('includes/miscFunctions.inc');

    // Pick up the passed in data for district, county and period, no matter how it got here
    if (isset($_GET['DISTRICT'])){
	$DISTRICT =$_GET['DISTRICT'];
    } elseif (isset($_POST['DISTRICT'])){
	$DISTRICT =$_POST['DISTRICT'];
    }

    if (isset($_GET['COUNTY'])){
	$COUNTY =$_GET['COUNTY'];
    } elseif (isset($_POST['COUNTY'])){
	$COUNTY =$_POST['COUNTY'];
    }

    if (isset($_GET['PERIOD'])){
	$PERIOD =$_GET['PERIOD'];
    } elseif (isset($_POST['PERIOD'])){
	$PERIOD =$_POST['PERIOD'];
    }

    if (isset($_GET['ARESMEM'])){
	$ARESMEM =$_GET['ARESMEM'];
    } elseif (isset($_POST['ARESMEM'])){
	$ARESMEM =$_POST['ARESMEM'];
    }

    if (isset($_GET['ARESCHG'])){
	$ARESCHG =$_GET['ARESCHG'];
    } elseif (isset($_POST['ARESCHG'])){
	$ARESCHG =$_POST['ARESCHG'];
    }

    if (isset($_GET['NETNAME'])){
	$NETNAME =$_GET['NETNAME'];
    } elseif (isset($_POST['NETNAME'])){
	$NETNAME =$_POST['NETNAME'];
    }

    if (isset($_GET['NETFREQ'])){
	$NETFREQ =$_GET['NETFREQ'];
    } elseif (isset($_POST['NETFREQ'])){
	$NETFREQ =$_POST['NETFREQ'];
    }

    if (isset($_GET['NETLIA'])){
	$NETLIA =$_GET['NETLIA'];
    } elseif (isset($_POST['NETLIA'])){
	$NETLIA =$_POST['NETLIA'];
    }

    if (isset($_GET['NUMNET'])){
	$NUMNET =$_GET['NUMNET'];
    } elseif (isset($_POST['NUMNET'])){
	$NUMNET =$_POST['NUMNET'];
    }

    if (isset($_GET['PHNET'])){
	$PHNET =$_GET['PHNET'];
    } elseif (isset($_POST['PHNET'])){
	$PHNET =$_POST['PHNET'];
    }

    if (isset($_GET['NUMPSE'])){
	$NUMPSE =$_GET['NUMPSE'];
    } elseif (isset($_POST['NUMPSE'])){
	$NUMPSE =$_POST['NUMPSE'];
    }

    if (isset($_GET['PHPSE'])){
	$PHPSE =$_GET['PHPSE'];
    } elseif (isset($_POST['PHPSE'])){
	$PHPSE =$_POST['PHPSE'];
    }

    if (isset($_GET['NUMEOP'])){
	$NUMEOP =$_GET['NUMEOP'];
    } elseif (isset($_POST['NUMEOP'])){
	$NUMEOP =$_POST['NUMEOP'];
    }

    if (isset($_GET['PHEOP'])){
	$PHEOP =$_GET['PHEOP'];
    } elseif (isset($_POST['PHEOP'])){
	$PHEOP =$_POST['PHEOP'];
    }

    if (isset($_GET['NUMTOT'])){
	$NUMTOT =$_GET['NUMTOT'];
    } elseif (isset($_POST['NUMTOT'])){
	$NUMTOT =$_POST['NUMTOT'];
    }

    if (isset($_GET['PHTOT'])){
	$PHTOT =$_GET['PHTOT'];
    } elseif (isset($_POST['PHTOT'])){
	$PHTOT =$_POST['PHTOT'];
    }

    if (isset($_GET['COMMENTS'])){
	$COMMENTS =$_GET['COMMENTS'];
    } elseif (isset($_POST['COMMENTS'])){
	$COMMENTS =$_POST['COMMENTS'];
    }

    if (isset($_GET['REPORTNAME'])){
	$REPORTNAME =$_GET['REPORTNAME'];
    } elseif (isset($_POST['REPORTNAME'])){
	$REPORTNAME =$_POST['REPORTNAME'];
    }

    if (isset($_GET['REPORTCALL'])){
	$REPORTCALL =$_GET['REPORTCALL'];
    } elseif (isset($_POST['REPORTCALL'])){
	$REPORTCALL =$_POST['REPORTCALL'];
    }

    if (isset($_GET['EMAIL'])){
	$REPORTEMAIL =$_GET['EMAIL'];
    } elseif (isset($_POST['EMAIL'])){
	$REPORTEMAIL =$_POST['EMAIL'];
    }

    // Connect to the database
    $db = mysql_connect($host , $dbuser, $dbpassword);
    mysql_select_db($DatabaseName,$db);

    $_SESSION['Theme'] = 'professional';

    // Starting up and top of page stuff
    include('includes/mi-arpsc-header.inc');
    echo '<BODY>' . "\n";

    echo "<H1><CENTER>FSD-212 Results</CENTER></H1>\n";
    echo "<TABLE CLASS=main_page>\n  <TD>\n";

    // Get the district name from the district code
    $SQL1="SELECT arpsc_district FROM arpsc_districts WHERE districtkey = " . $DISTRICT ;
    $result1=getResult($SQL1,$db);

    // Get the date string for the selected period
    $SQL4='SELECT lastday FROM periods WHERE periodno=' . $PERIOD;
    $result4=getResult($SQL4,$db);

    $DDISTRICT="'" . $DISTRICT . "'";

    $DCOUNTY="'" . $COUNTY . "'";

    $DPERIOD=$PERIOD;

    if ( $ARESMEM )
	$DARESMEM="'" . $ARESMEM . "'";
    else
	$DARESMEM = 'NULL';

    if ( $ARESCHG )
	$DARESCHG="'" . $ARESCHG . "'";
    else
	$DARESCHG = 'NULL';

    if ( $NETNAME )
	$DNETNAME="'" . urlencode($NETNAME) . "'";
    else
	$DNETNAME = 'NULL';

    if ( $NETFREQ )
	$DNETFREQ="'" . $NETFREQ . "'";
    else
	$DNETFREQ = 'NULL';

    if ( $NETLIA )
	$DNETLIA="'" . urlencode($NETLIA) . "'";
    else
	$DNETLIA = 'NULL';

    if ( $NUMNET!='' )
	$DNUMNET="'" . $NUMNET . "'";
    else
	$DNUMNET = 'NULL';

    if ( $PHNET!='' )
	$DPHNET="'" . $PHNET . "'";
    else
	$DPHNET = 'NULL';

    if ( $NUMPSE )
	$DNUMPSE="'" . $NUMPSE . "'";
    else
	$DNUMPSE = 'NULL';

    if ( $PHPSE )
	$DPHPSE="'" . $PHPSE . "'";
    else
	$DPHPSE = 'NULL';

    if ( $NUMEOP )
	$DNUMEOP="'" . $NUMEOP . "'";
    else
	$DNUMEOP = 'NULL';

    if ( $PHEOP )
	$DPHEOP="'" . $PHEOP . "'";
    else
	$DPHEOP = 'NULL';

    if ( $NUMTOT )
	$DNUMTOT="'" . $NUMTOT . "'";
    else
	$DNUMTOT = 'NULL';

    if ( $PHTOT )
	$DPHTOT="'" . $PHTOT . "'";
    else
	$DPHTOT = 'NULL';

    if ( $COMMENTS )
	$DCOMMENTS="'" . urlencode($COMMENTS) . "'";
    else
	$DCOMMENTS = 'NULL';

    if ( $REPORTNAME )
	$DREPORTNAME="'" . urlencode($REPORTNAME) . "'";
    else
	$DREPORTNAME = 'NULL';

    if ( $REPORTCALL )
	$DREPORTCALL="'" . urlencode($REPORTCALL) . "'";
    else
	$DREPORTCALL = 'NULL';

    $WHERECLAUSE=" WHERE county='" . $COUNTY . "' AND period='" . $PERIOD . "'";

    $SQL1 = 'SELECT COUNT(*) FROM `arpsc_ecrept`' . $WHERECLAUSE;
    $count=singleResult($SQL1,$db);

    if ( $count == 0 )
	{

	    $SQL6 = 'INSERT INTO arpsc_ecrept VALUES(' . 
		$DPERIOD . ', ' .
		$DCOUNTY . ', ' .
		$DARESMEM . ', ' .
		$DARESCHG . ', ' .
		$DNETNAME . ', ' .
		$DNETFREQ . ', ' .
		$DNETLIA  . ', ' .
		$DNUMNET  . ', ' .
		$DPHNET  . ', ' .
		$DNUMPSE  . ', ' .
		$DPHPSE  . ', ' .
		$DNUMEOP  . ', ' .
		$DPHEOP  . ', ' .
		$DNUMTOT  . ', ' .
		$DPHTOT  . ', ' .
		$DCOMMENTS  . ', ' .
		$DREPORTCALL  . ', ' .
		$DREPORTNAME  . ', ' .
		'now())';

	    // echo '<P><FONT FACE="Courier New" SIZE=3>' . $SQL6 . "</FONT></P>\n";
	    if ( getResult($SQL6,$db) )
		echo "<P>&nbsp;&nbsp;Your report has been entered in the database.</P>\n";
	}
    else
	{

	    $SQL7 = 'UPDATE `arpsc_ecrept` SET ';
	    $SQL7 = addUpdate('aresmem',$DARESMEM,$SQL7);
	    $SQL7 = addUpdate('aresmemchg',$DARESCHG,$SQL7);
	    $SQL7 = addUpdate('localnetname',$DNETNAME,$SQL7);
	    $SQL7 = addUpdate('netfrequency',$DNETFREQ,$SQL7);
	    $SQL7 = addUpdate('ntsliaisons',$DNETLIA,$SQL7);
	    $SQL7 = addUpdate('drillsnum',$DNUMNET,$SQL7);
	    $SQL7 = addUpdate('drillshrs',$DPHNET,$SQL7);
	    $SQL7 = addUpdate('psesnum',$DNUMPSE,$SQL7);
	    $SQL7 = addUpdate('pseshrs',$DPHPSE,$SQL7);
	    $SQL7 = addUpdate('eopsnum',$DNUMEOP,$SQL7);
	    $SQL7 = addUpdate('eopshrs',$DPHEOP,$SQL7);
	    $SQL7 = addUpdate('aresopsnum',$DNUMTOT,$SQL7);
	    $SQL7 = addUpdate('aresops',$DPHTOT,$SQL7);
	    $SQL7 = addUpdate('comments',$DCOMMENTS,$SQL7);
	    $SQL7 = addUpdate('reportcall',$DREPORTCALL,$SQL7);
	    $SQL7 = addUpdate('reportname',$DREPORTNAME,$SQL7);
	    $SQL7 = substr($SQL7,0,strlen($SQL7)-2);
	    $SQL7=$SQL7 . ' WHERE period=' . $DPERIOD . ' AND county = ' . $DCOUNTY;

	    // echo '<P><FONT FACE="Courier New" SIZE=3>' . $SQL7 . "</FONT></P>\n";
	    if ( getResult($SQL7,$db) )
		echo "&nbsp;&nbsp;<P>Your report has been updated in the database.</P>\n";
	}

    $SQL2="SELECT lastday FROM periods WHERE periodno=" . $PERIOD;
    $LASTDAY=singleResult($SQL2,$db);
    $SQL3="SELECT countyname FROM arpsc_counties WHERE countycode='" . $COUNTY . "'";
    $COUNTYNAME=singleResult($SQL3,$db);

    echo "  </TD>\n</TABLE>\n";
    echo "<HR>\n";
    echo "<TABLE CLASS=main_area>\n  <TD>\n";

    echo "<P CLASS=report_area><B>FSD-212 report from " . $REPORTNAME . ", " . $REPORTCALL . " &lt;" . $REPORTEMAIL . "&gt;" . ".</B></P>\n";
    echo "<P CLASS=report_area>(1) County: <B>" . $COUNTYNAME . " (" . $COUNTY . ")</B></P>\n";
    echo "<P CLASS=report_area>(2) Period: <B>" . convertDate($LASTDAY) . "</B></P>\n";
    echo "<P CLASS=report_area>(4) Number of ARES members: <B>" . $ARESMEM . "</B></P>\n";
    echo "<P CLASS=report_area>(5) Change since last month: <B>" .$ARESCHG  . "</B></P>\n";
    echo "<P CLASS=report_area>(6) Local net name: <B>" . $NETNAME . "</B></P>\n";
    echo "<P CLASS=report_area>(7) Local net frequency: <B>" . $NETFREQ . "</B></P>\n";
    echo "<P CLASS=report_area>(8) Liaisons maintained with the following NTS net(s): <B>" . $NETLIA . "</B></P>\n";
    echo "<P CLASS=report_area>(9) Number of nets, drills, tests and training sessions: <B>" . $NUMNET . "</B>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Hours: <B>" . $PHNET . "</B></P>\n";
    echo "<P CLASS=report_area>(10) Number of public service events: <B>" . $NUMPSE . "</B>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Hours: <B>" . $PHPSE . "</B></P>\n";
    echo "<P CLASS=report_area>(11) Number of emergency operations: <B>" . $NUMEOP . "</B>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Hours: <B>" . $PHEOP . "</B></P>\n";
    echo "<P CLASS=report_area>(12) Total number of ARES events: <B>" . $NUMTOT . "</B>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Hours: <B>" . $PHTOT . "</B></P>\n";
    echo "<P CLASS=report_area>(13) Comments: \n";
    echo "<TABLE WIDTH=80% CLASS=report_area><TD WIDTH=30%>&nbsp;</TD><TD><B>\n";
    echo $COMMENTS . "\n";
    echo "</B></TD></TABLE>\n";
    /*
    echo "<P CLASS=report_area>( " .  . "</B></P>\n";
    echo "<P CLASS=report_area>( " .  . "</B></P>\n";
    echo "<P CLASS=report_area>( " .  . "</B></P>\n";
    echo "<P CLASS=report_area>( " .  . "</B></P>\n";
    echo "<P CLASS=report_area>( " .  . "</B></P>\n";
    echo "<P CLASS=report_area>( " .  . "</B></P>\n";
    */
    echo "  </TD>\n</TABLE>\n";
    echo "<HR>\n";

    echo '<A HREF="http://www.mi-nts.org/FSD212data.php?DISTRICT=' . 
	$DISTRICT . '&COUNTY=' . $COUNTY . '&PERIOD=' . $PERIOD . 
	'">Correct this data</A><BR>' . "\n";
    echo '<A HREF="http://www.mi-nts.org/FSD212.php">Enter a new report</A><BR>' . "\n";

    echo "<HR>\n";
}
?>

<P CLASS=foot>Source - $Revision: 1.5 $ - $Date: 2006-03-31 16:22:12-05 $</P>
</BODY>
</HTML>
