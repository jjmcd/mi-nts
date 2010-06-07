<?php
    include('includes/session.inc');
    $title=_('Michigan Section ARPSC');
    include('includes/miscFunctions.inc');

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

    $db = mysql_connect($host , $dbuser, $dbpassword);
    mysql_select_db($DatabaseName,$db);

    $_SESSION['Theme'] = 'professional';

    include('includes/mi-arpsc-header.inc');
    echo '<BODY ONLOAD="document.GETPERIOD.PERIOD.focus()">' . "\n";
    echo '<script>function subform(x) { document.GETPERIOD.submit(x); }';
    echo "</script>\n";
    echo "<H1><CENTER>FSD-212</CENTER></H1>\n";

    $SQL2="SELECT district,countyname,eccall FROM arpsc_counties WHERE countycode='" . $COUNTY . "'";
    $result2=getResult($SQL2,$db);
    $myrow2=getRow($result2,$db);
    $DISTRICT=$myrow2[0];
    $COUNTYNAME=$myrow2[1];
    $ECCALL=$myrow2[2];

    $SQL1="SELECT arpsc_district FROM arpsc_districts WHERE districtkey = " . $DISTRICT ;
    $result1=getResult($SQL1,$db);

    $NOW=time();
    $THEN=$NOW-28*24*60*60; // Last month for sure
    $SDAY=date("j",$NOW);
    if ( $SDAY < 11 )
	$NOW = $THEN;
    $SMONTH=date("m",$NOW);
    $SYEAR=date("Y",$NOW);
    $SDATE = $SYEAR . '-' . $SMONTH . '-' . date(t,$NOW);

    $SQL3="SELECT periodno FROM periods WHERE lastday = '" . $SDATE . "'";
    $result3=getResult($SQL3,$db);
    $myrow3=getRow($result3,$db);
    $LASTPERIOD=$myrow3[0];
    $FIRSTPERIOD=$LASTPERIOD-6;

    $SQL4='SELECT periodno,lastday FROM periods WHERE periodno BETWEEN ' . $FIRSTPERIOD . ' AND ' . $LASTPERIOD . ' ORDER BY periodno DESC';
    $result4=getResult($SQL4,$db);

?>
<FORM NAME="GETPERIOD" METHOD="POST"
<?php
    echo ' ACTION="http:FSD212data.php?DISTRICT=' . $DISTRICT . '&COUNTY=' . $COUNTY . '">'
?>
<TABLE WIDTH=100%>
<TR>
  <TD>&nbsp;</TD>
  <TH ALIGN=RIGHT>District</TH><TD>
  <SELECT ID="DISTRICT" TABINDEX=1 DISABLED>
<?php
    $myrow1 = getRow($result1,$db);
    echo '	  <OPTION VALUE="' . $DISTRICT . '">' . $myrow1[0] . "</OPTION>\n";
?>
</SELECT>
&nbsp;</TD>
  <TD>&nbsp;</TD>
</TR>

<TR>
  <TH ALIGN=RIGHT>(1) County</TH>
  <TD>
	<SELECT NAME=COUNTY TABINDEX=2 DISABLED>
<?php
	echo '	  <OPTION VALUE="' . $COUNTY . '">' . $COUNTYNAME . "</OPTION>\n";
?>
	</SELECT>
  </TD>
  <TH ALIGN=RIGHT>(2) Period ending</TH>
  <TD><SELECT NAME=PERIOD TABINDEX=3 onchange="subform()">
    <OPTION VALUE=0>-- Select One--</OPTION>
<?php
    while ( $myrow4=getRow($result4,$db) )
	{
	  $usedate = date('M, Y',strtotime($myrow4[1]));
	  echo '    <OPTION VALUE=' . $myrow4[0] . '>' . $usedate . "</OPTION>\n";
	}
?>
    </SELECT>&nbsp;</TD>
</TR>
<?php
    include('ARPSC/FSD212dummy.inc');
?>
</TABLE>
</FORM>
<P CLASS=foot>Source - $Revision: 1.2 $ - $Date: 2006-03-31 11:30:13-05 $</P>
</BODY>
</HTML>
