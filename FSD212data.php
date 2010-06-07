<?php
/*
    $Id: FSD212data.php,v 1.5 2006-03-31 22:32:21-05 jjmcd Exp jjmcd $

    This script catures FSD-212 data from the user.  It must be
    entered with the parameters DISTRICT, COUNTY and PERIOD.  In
    general, DISTRICT and COUNTY are sent via GET and PERIOD by POST,
    but either method may be used for any of the parameters.

    DISTRICT is the numeric district key
    COUNTY is the four character county code
    PERIOD is the numeric period number

    The script first gets the county name and EC call from the
    arpsc_counties table.  It then gets the district name from the
    arpsc_districts table.  It then picks up the last date in the
    period from the periods table.  The script uses these three values
    to fill in an empty record.  It calculate a default email in the
    form EC_<county>@mi-arpsc.org.  The script then looks up the EC
    call in the calldirectory table to see if a closer email address
    is available.  Finally, the table arpsc_ecrept is queried to see
    if the specified report is already in the database.  If it is, it
    replaces the "empty" record which is used to populate the form.

    When the user clicks submit, the for data is passed to
    FSD212post.php for processing.

    Written 2006-03-29 jjmcd
    $Revision: 1.5 $ $Date: 2006-03-31 22:32:21-05 $ $Author: jjmcd $
*/
{
    include('includes/session.inc');
    $title=_('Michigan Section ARPSC');
    include('includes/miscFunctions.inc');

    // Pick up the passed in data for district, county and period,
    // no matter how it got here
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

    // Connect to the database
    $db = mysql_connect($host , $dbuser, $dbpassword);
    mysql_select_db($DatabaseName,$db);

    $_SESSION['Theme'] = 'professional';

    // Starting up and top of page stuff
    include('includes/mi-arpsc-header.inc');
    echo '<BODY ONLOAD="document.GETDATA.ARESMEM.focus()">' . "\n";

    echo "<H1><CENTER>FSD-212</CENTER></H1>\n";

    // Get the county name and EC from the selected county code
    $SQL2="SELECT district,countyname,eccall FROM arpsc_counties WHERE countycode='" . $COUNTY . "'";
    $result2=getResult($SQL2,$db);
    $myrow2=getRow($result2,$db);
    $DISTRICT=$myrow2[0];
    $COUNTYNAME=$myrow2[1];
    $ECCALL=$myrow2[2];

    // Get the district name from the district code
    $SQL1="SELECT arpsc_district FROM arpsc_districts WHERE districtkey = " . $DISTRICT ;
    $result1=getResult($SQL1,$db);

    // Get the date string for the selected period
    $SQL4='SELECT lastday FROM periods WHERE periodno=' . $PERIOD;
    $result4=getResult($SQL4,$db);

    // Fill in empty record
    $ARESMEM=0;
    $ARESCHG=0;
    $NETNAME='';
    $NETFREQ='';
    $NETLIA='';
    $NUMNET=0;
    $PHNET=0;
    $NUMPSE=0;
    $PHPSE=0;
    $NUMEOP=0;
    $PHEOP=0;
    $NUMTOT=0;
    $PHTOT=0;
    $COMMENTS='';
    $ECNAME='';
    $ECEMAIL='EC_' . $COUNTY . '@mi-arpsc.org';

    // See if the EC is listed, if so, use his data
    if ( $ECCALL != '' )
	{
	    $SQL3="SELECT name,email FROM calldirectory WHERE callsign='" . $ECCALL . "'";
	    $result3=getResult($SQL3,$db);
	    if ( $myrow3=getRow($result3,$db) )
		{
		    $ECNAME=$myrow3[0];
		    $ECEMAIL=$myrow3[1];
		}
	}

    // If the record already exists, use latest data
    $SQL5="SELECT * FROM arpsc_ecrept WHERE period='" . $PERIOD . "' AND COUNTY='" . $COUNTY . "'";
    $result5=getResult($SQL5,$db);
    if ( $myrow5=getRow($result5,$db) )
	{
	    $ARESMEM=$myrow5[2];
	    $ARESCHG=$myrow5[3];
	    $NETNAME=urldecode($myrow5[4]);
	    $NETFREQ=$myrow5[5];
	    $NETLIA=urldecode($myrow5[6]);
	    $NUMNET=$myrow5[7];
	    $PHNET=$myrow5[8];
	    $NUMPSE=$myrow5[9];
	    $PHPSE=$myrow5[10];
	    $NUMEOP=$myrow5[11];
	    $PHEOP=$myrow5[12];
	    $NUMTOT=$myrow5[13];
	    $PHTOT=$myrow5[14];
	    $COMMENTS=urldecode($myrow5[15]);
	    $ECCALL=$myrow5[16];
	    $ECNAME=urldecode($myrow5[17]);
	}

?>
<script>
function isNotEmpty(elem)
{
  var str = elem.value;
  var re = /.+/;
  if (!str.match(re)) {
   alert("Please fill in all required fields.");
   return false;
  }
  else {
   return true;
  }
}
function numeralsOnly(evt)
 {
  evt = (evt) ? evt : event;
  var charCode = (evt.charCode) ? evt.charCode : ((evt.keyCode) ? evt.keyCode : ((evt.which) ? evt.which : 0));
  if (charCode > 31 && (charCode < 48 || charCode > 57)) {
    alert("Enter numerals only in this field");
    return false;
   }
  return true;
 }
function validateForm(form) {
 if (isNotEmpty(form.call)) {
  if (isNotEmpty(form.city)) {
   if (isNotEmpty(form.msgnum)) {
    return true;
   }
  }
 }
 return false;
}
function calcTotals(ele)
{
  a9=parseInt(document.GETDATA.NUMNET.value,10);
  a10=parseInt(document.GETDATA.NUMPSE.value,10);
  a11=parseInt(document.GETDATA.NUMEOP.value,10);
  document.GETDATA.NUMTOT.value=a9+a10+a11;
  document.GETDATA.NUMTOT.write();
}
function calcHours(ele)
{
  b9=parseInt(document.GETDATA.PHNET.value,10);
  b10=parseInt(document.GETDATA.PHPSE.value,10);
  b11=parseInt(document.GETDATA.PHEOP.value,10);
  document.GETDATA.PHTOT.value=b9+b10+b11;
  document.GETDATA.valuetot.value="$"+(b9+b10+b11)*14.11;
  document.GETDATA.PHTOT.write();
}
</script>


<FORM NAME="GETDATA" METHOD="POST"
<?php
    echo ' ACTION="http:FSD212post.php?DISTRICT=' . $DISTRICT . '&COUNTY=' . $COUNTY . '&PERIOD=' . $PERIOD . '">'
?>
<TABLE WIDTH=100%>
<TR>
  <TD>&nbsp;</TD>
  <TH ALIGN=RIGHT>District</TH>
  <TD>
	<SELECT NAME=DISTRICT TABINDEX=1 DISABLED>
<?php
    $myrow1 = getRow($result1,$db);
    echo '	  <OPTION VALUE="' . $DISTRICT . '">' . $myrow1[0] . "</OPTION>\n";
?>
	</SELECT>
  </TD>
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
  <TD>
	<SELECT NAME=PERIOD TABINDEX=3 DISABLED>
<?php
    $myrow4=getRow($result4,$db);
	{
	  $usedate = date('M, Y',strtotime($myrow4[0]));
	  echo '	  <OPTION VALUE="' . $PERIOD . '">' . $usedate . "</OPTION>\n";
	}
?>
	</SELECT>
  </TD>
</TR>

<TR>
    <TD COLSPAN=4><HR></TD>
</TR>
<TR>
  <TH ALIGN=RIGHT>(4) Number ARES members</TH>
  <TD>
<?php
    echo '    <INPUT TYPE=text NAME=ARESMEM TABINDEX=4 VALUE="' . $ARESMEM . '" SIZE=12 onkeypress="return numeralsOnly(event)">' . "\n";
?>
  </TD>
  <TH ALIGN=RIGHT>(5) Change since last month</TH>
  <TD>
<?php
    echo '    <INPUT TYPE=text NAME=ARESCHG TABINDEX=5 VALUE="' . $ARESCHG . '" SIZE=12 onkeypress="return numeralsOnly(event)">' . "\n";
?>
  </TD>
</TR>

<TR>
  <TH ALIGN=RIGHT>(6) Local net name</TH>
  <TD>
<?php
    echo '    <INPUT TYPE=text NAME=NETNAME TABINDEX=6 VALUE="' . $NETNAME . '" SIZE=24>' . "\n";
?>
  </TD>
  <TH ALIGN=RIGHT>(7) Local net frequency</TH>
  <TD>
<?php
    echo '    <INPUT TYPE=text NAME=NETFREQ TABINDEX=7 VALUE="' . $NETFREQ . '" SIZE=12>' . "\n";
?>
  </TD>
</TR>

<TR>
  <TH  COLSPAN=2 ALIGN=RIGHT>(8) NTS liaisons maintained with:</TH>
  <TD COLSPAN=2 ALIGN=LEFT>
<?php
    echo '    <INPUT TYPE=text NAME=NETLIA TABINDEX=8 VALUE="' . $NETLIA . '" SIZE=40>' . "\n";
?>
  </TD>
</TR>

<TR>
  <TH ALIGN=RIGHT>(9) Number of nets, drills, tests and training sessions</TH>
  <TD>
<?php
    echo '    <INPUT TYPE=text NAME=NUMNET TABINDEX=9
        VALUE="' . $NUMNET . '" SIZE=12
        onchange="calcTotals(this)"
        onkeypress="return numeralsOnly(event)">' . "\n";
?>
  </TD>
  <TH ALIGN=RIGHT>Person hours</TH>
  <TD>
<?php
    echo '    <INPUT TYPE=text NAME=PHNET TABINDEX=10
        VALUE="' . $PHNET . '" SIZE=12
        onchange="calcHours(this)"
        onkeypress="return numeralsOnly(event)">' . "\n";
?>
  </TD>
</TR>

<TR>
  <TH ALIGN=RIGHT>(10) Number of public service events</TH>
  <TD>
<?php
    echo '    <INPUT TYPE=text NAME=NUMPSE TABINDEX=11 VALUE="' . $NUMPSE . '" SIZE=12 onchange="calcTotals(this)" onkeypress="return numeralsOnly(event)">' . "\n";
?>
  </TD>
  <TH ALIGN=RIGHT>Person hours</TH>
  <TD>
<?php
    echo '    <INPUT TYPE=text NAME=PHPSE TABINDEX=12 VALUE="' . $PHPSE . '" SIZE=12 onchange="calcHours(this)" onkeypress="return numeralsOnly(event)">' . "\n";
?>
  </TD>
</TR>

<TR>
  <TH ALIGN=RIGHT>(11) Number of Emergency Operations</TH>
  <TD>
<?php
    echo '    <INPUT TYPE=text NAME=NUMEOP TABINDEX=13 VALUE="' . $NUMEOP . '" SIZE=12 onchange="calcTotals(this)" onkeypress="return numeralsOnly(event)">' . "\n";
?>
  </TD>
  <TH ALIGN=RIGHT>Person hours</TH>
  <TD>
<?php
    echo '    <INPUT TYPE=text NAME=PHEOP TABINDEX=14 VALUE="' . $PHEOP . '" SIZE=12 onchange="calcHours(this)" onkeypress="return numeralsOnly(event)">' . "\n";
?>
  </TD>
</TR>

<TR>
  <TH ALIGN=RIGHT>(12) Number of events (total 9-11)</TH>
  <TD>
<?php
    echo '    <INPUT TYPE=text NAME=NUMTOT TABINDEX=15 VALUE="' . $NUMTOT . '" SIZE=12 readonly>' . "\n";
?>
  </TD>
  <TH ALIGN=RIGHT>Person hours</TH>
  <TD>
<?php
    echo '    <INPUT TYPE=text NAME=PHTOT TABINDEX=16 VALUE="' . $PHTOT . '" SIZE=12 readonly>' . "\n";
?>
  </TD>

</TR>

<TR>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <th align="right">Value of contributed hours:</th>
  <td>
    <input type="text" name="valuetot" size="12" tabindex=30 readonly
	  style="color:#c00000;font-weight:800">
  </td>
</tr>

<TR>
  <TH ALIGN=RIGHT>(13) Comments:</TH>
  <TD COLSPAN=3>
<?php
    echo '    <TEXTAREA NAME=COMMENTS ROWS=5 COLS=50 WRAP=virtual TABINDEX=17>';
    echo $COMMENTS;
    echo '</TEXTAREA>' . "\n";
?>
  </TD>
</TR>

<TR>
    <TD COLSPAN=4><HR></TD>
</TR>

<TR>
  <TH ALIGN=RIGHT>Your name:</TH>
  <TD>
<?php
    echo '    <INPUT TYPE=text NAME=REPORTNAME TABINDEX=18 VALUE="' . $ECNAME . '" SIZE=36>' . "\n";
?>
  </TD>
  <TH ALIGN=RIGHT>Call:</TH>
  <TD>
<?php
    echo '    <INPUT TYPE=text NAME=REPORTCALL TABINDEX=19 VALUE="' . $ECCALL . '" SIZE=12>' . "\n";
?>
  </TD>

<TR>
  <TH ALIGN=RIGHT>email:</TH>
  <TD COLSPAN=3>
<?php
    echo '    <INPUT TYPE=text NAME=EMAIL TABINDEX=20 VALUE="' . $ECEMAIL . '" SIZE=24>' . "\n";
}
?>
  </TD>
</TR>

<TR>
<TR>
  <TD COLSPAN=4>
    <INPUT TYPE="Submit" NAME="Submit" TABINDEX=21 VALUE="Submit">
    <INPUT TYPE="Reset" NAME="Reset" TABINDEX=22 VALUE="Reset">
  </TD>
</TR>



</TABLE>
</FORM>
<P CLASS=foot>Source - $Revision: 1.5 $ - $Date: 2006-03-31 22:32:21-05 $</P>
</BODY>
</HTML>
