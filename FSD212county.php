<?php
    include('includes/session.inc');
    $title=_('Michigan Section ARPSC');
    include('includes/miscFunctions.inc');

    if (isset($_GET['DISTRICT'])){
	$DISTRICT =$_GET['DISTRICT'];
    } elseif (isset($_POST['DISTRICT'])){
	$DISTRICT =$_POST['DISTRICT'];
    }

    $db = mysql_connect($host , $dbuser, $dbpassword);
    mysql_select_db($DatabaseName,$db);

    $_SESSION['Theme'] = 'professional';

    include('includes/mi-arpsc-header.inc');
    echo '<BODY ONLOAD="document.GETDISTRICT.COUNTY.focus()">' . "\n";
    echo '<script>function subform(x) { document.GETDISTRICT.submit(x); }';
    echo "</script>\n";
    echo "<H1><CENTER>FSD-212</CENTER></H1>\n";

    $SQL1="SELECT arpsc_district FROM arpsc_districts WHERE districtkey = " . $DISTRICT ;
    $result1=getResult($SQL1,$db);

    $SQL2="SELECT countycode,countyname FROM arpsc_counties WHERE district=" . $DISTRICT . " ORDER BY countyname";
    $result2=getResult($SQL2,$db);
/*
    $NOW=time();
    $THEN=$NOW-28*24*60*60; // Last month for sure
    $SDAY=date("j",$NOW);
    if ( $SDAY < 11 )
	$NOW = $THEN;
    $SMONTH=date("m",$NOW);
    $SYEAR=date("Y",$NOW);
    $SDATE = $SYEAR . '-' . $SMONTH . '-' . date(t,$NOW);
*/
?>
<FORM NAME="GETDISTRICT" METHOD="POST"
<?php
    echo ' ACTION="http:FSD212period.php?DISTRICT=' . $DISTRICT . '">'
?>
<TABLE WIDTH=100%>
<TR>
  <TD>&nbsp;</TD>
  <TH ALIGN=RIGHT>District</TH><TD>
  <SELECT ID="DISTRICT" TABINDEX=1" DISABLED>
<?php
    $myrow = getRow($result1,$db);
    echo '<OPTION VALUE="' . $DISTRICT . '">' . $myrow[0] . "</OPTION>\n";
?>
</SELECT>
&nbsp;</TD>
  <TD>&nbsp;</TD>
</TR>

<TR>
  <TH ALIGN=RIGHT>(1) County</TH>
  <TD>
	<SELECT NAME=COUNTY TABINDEX=2 onchange="subform()">
	  <OPTION VALUE="0">-- Select One --</OPTION>
<?php
	while ($myrow = getRow($result2,$db) )
	{
	echo '	  <OPTION VALUE="' . $myrow[0] . '">' . $myrow[1] . "</OPTION>\n";
	}
?>
	</SELECT>
  </TD>
  <TH ALIGN=RIGHT>(2) Period ending</TH>
  <TD><SELECT NAME=PERIOD TABINDEX=3 DISABLED><OPTION VALUE=0>-- Select One --</SELECT>&nbsp;</TD>
</TR>
<?php
    include('ARPSC/FSD212dummy.inc');
?>
</TABLE>
</FORM>
<P CLASS=foot>Source - $Revision: 1.2 $ - $Date: 2006-03-31 11:29:36-05 $</P>
</BODY>
</HTML>
