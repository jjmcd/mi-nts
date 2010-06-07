<?php
    include('includes/session.inc');
    $title=_('Michigan Section ARPSC');
    include('includes/miscFunctions.inc');

    $db = mysql_connect($host , $dbuser, $dbpassword);
    mysql_select_db($DatabaseName,$db);

    $_SESSION['Theme'] = 'professional';

    include('includes/mi-arpsc-header.inc');
    echo '<BODY ONLOAD="document.GETDISTRICT.DISTRICT.focus()">' . "\n";
    echo '<script>function subform() { document.GETDISTRICT.submit(); }';
    echo "</script>\n";
    echo "<H1><CENTER>FSD-212</CENTER></H1>\n";

    echo '<P></P>' . "\n";

    $SQL='SELECT districtkey,arpsc_district FROM arpsc_districts ORDER BY districtkey';
    $result=getResult($SQL,$db);
?>
<FORM NAME="GETDISTRICT" METHOD="POST" ACTION="http:FSD212county.php">
<TABLE WIDTH=100%>
<TR>
  <TD>&nbsp;</TD>
  <TH ALIGN=RIGHT>District</TH><TD>
  <SELECT NAME=DISTRICT TABINDEX=1" onchange="subform()">
    <OPTION VALUE="0">-- Select One --</OPTION>
<?php
    while ($myrow = getRow($result,$db) )
	{
	echo '    <OPTION VALUE="' . $myrow[0] . '">' . $myrow[1] . "</OPTION>\n";
	}
?>
  </SELECT>&nbsp;</TD>
  <TD>&nbsp;</TD>
</TR>

<TR>
  <TH ALIGN=RIGHT>(1) County</TH>
  <TD><SELECT NAME=COUNTY TABINDEX=2 DISABLED><OPTION VALUE=0>-- Select One --</SELECT>&nbsp;</TD>
  <TH ALIGN=RIGHT>(2) Period ending</TH>
  <TD><SELECT NAME=PERIOD TABINDEX=3 DISABLED><OPTION VALUE=0>-- Select One --</SELECT>&nbsp;</TD>
</TR>
<?php
    include('ARPSC/FSD212dummy.inc');
?>
</TABLE>
</FORM>
<P CLASS=foot>Source - $Revision: 1.2 $ - $Date: 2006-03-31 11:28:13-05 $</P>
</BODY>
</HTML>
