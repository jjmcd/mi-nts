<?php

// $Id: miscFunctions.inc,v 1.7 2008-01-22 15:51:31-05 jjmcd Exp jjmcd $

function leftBar($db)
{
    echo "<!-- ONENTRY leftBar -->\n";
    $SQL = 'SELECT nbid,buttontext,sequence,pageid ' .
           'FROM navbar_heads ' .
           'ORDER BY sequence';
    $result=mysql_query($SQL,$db);

    if (mysql_errno($db) != 0 )
    {
	prnMsg($ErrorMessage.'<BR>' . mysql_error($db),'error', _('Database Error')); 
    }

    echo '  <div id="navbar">' . "\n";

    while ( $myrow = mysql_fetch_row($result) )
    {
	if (mysql_errno($db) != 0 )
	{
	    prnMsg($ErrorMessage.'<BR>' . mysql_error($db),'error', _('Database Error'));
	}
	$SQL2='SELECT url FROM webpages WHERE pageID=' . $myrow[3];
	$result2=mysql_query($SQL2,$db);
	if (mysql_errno($db) != 0 )
	{
	    prnMsg($ErrorMessage.'<BR>' . mysql_error($db),'error', _('Database Error')); 
	}
	if ( $myrow2=mysql_fetch_row($result2) )
	    echo '    <h2 class="title"><a href="'. $myrow2[0] . '">' . $myrow[1] .
	    "</a></h2>\n";
	else
	    echo '    <h2 class="title">' . $myrow[1] . "</h2>\n";

	// Now get the navbar_subs result
	$SQL2 = 'SELECT buttontext,pageid' .
              ' FROM navbar_subs' .
              ' WHERE nbid=' . $myrow[0] . 
              ' ORDER BY sequence';
	$result2=mysql_query($SQL2,$db);
	if (mysql_errno($db) != 0 ){
	    prnMsg($ErrorMessage.'<BR>' . mysql_error($db),'error', _('Database Error'));
	}
        $ulprinted = 0;
	while ($myrow2 = mysql_fetch_row($result2))
	{
	    $SQL3='SELECT url FROM webpages WHERE pageID=' . $myrow2[1];
	    $result3=mysql_query($SQL3,$db);
	    if (mysql_errno($db) != 0 )
	    {
		prnMsg($ErrorMessage.'<BR>' . mysql_error($db),'error', _('Database Error')); 
	    }
            if ( !$ulprinted )
	    {
		echo "    <ul>\n";
		$ulprinted = 1;
	    }
	    if ( $myrow3=mysql_fetch_row($result3) )
		echo '      <li><a href="'. $myrow3[0] . '">' . $myrow2[0] . "</a></li>\n";
	    else
		echo '      <li>' . $myrow2[0] . "</li>\n";

	}
	if ( $ulprinted )
	    echo "    </ul>\n";
	echo "    <p><hr size=\"1\"></p>\n";
    }
    echo "  </div>\n";
    echo "<!-- ONEXIT leftBar -->\n";
}

function makeMenu($db)
{
echo "<!-- =================Onentry makeMenu============= -->\n";
    $SQL = 'SELECT nbid,buttontext,sequence,pageid FROM navbar_heads ORDER BY sequence';
    $result=mysql_query($SQL,$db);

    if (mysql_errno($db) != 0 ){
	prnMsg($ErrorMessage.'<BR>' . mysql_error($db),'error', _('Database Error')); }

    echo "<TABLE CLASS=quick_menu WIDTH=\"100%\">\n";
    echo "  <TR>\n";

    while ($myrow = mysql_fetch_row($result))
	{
	    if (mysql_errno($db) != 0 ){
		prnMsg($ErrorMessage.'<BR>' . mysql_error($db),'error', _('Database Error'));
	    }
		$SQL2='SELECT url FROM webpages WHERE pageID=' . $myrow[3];
		$result2=mysql_query($SQL2,$db);
		if (mysql_errno($db) != 0 ){
		    prnMsg($ErrorMessage.'<BR>' . mysql_error($db),'error', _('Database Error')); }
		echo "    <TH class=main_menu_headers>\n";
		if ( $myrow2=mysql_fetch_row($result2) )
		    echo '      <A HREF="'. $myrow2[0] . '">' . $myrow[1] . "</A>\n";
		else
		    echo '      ' . $myrow[1] . "\n";
		echo "    </TH>\n";

	}
	echo "  </TR>\n";
	echo "  <TR>\n";

    // Need to do the original query again
    $result=mysql_query($SQL,$db);

    while ($myrow = mysql_fetch_row($result))
	{
	    if (mysql_errno($db) != 0 ){
		prnMsg($ErrorMessage.'<BR>' . mysql_error($db),'error', _('Database Error')); }

		echo "    <TD CLASS=menu_group_item ALIGN=CENTER>\n";

		// Now get the navbar_subs result
		$SQL2 = 'SELECT buttontext,pageid' .
                    ' FROM navbar_subs' .
                    ' WHERE nbid=' . $myrow[0] . 
                    ' ORDER BY sequence';
		$result2=mysql_query($SQL2,$db);
		if (mysql_errno($db) != 0 ){
		    prnMsg($ErrorMessage.'<BR>' . mysql_error($db),'error', _('Database Error'));
		}
		while ($myrow2 = mysql_fetch_row($result2))
		  {
		$SQL3='SELECT url FROM webpages WHERE pageID=' . $myrow2[1];
		$result3=mysql_query($SQL3,$db);
		if (mysql_errno($db) != 0 ){
		    prnMsg($ErrorMessage.'<BR>' . mysql_error($db),'error', _('Database Error')); }
		//echo "    <TH class=main_menu_headers>\n";
		if ( $myrow3=mysql_fetch_row($result3) )
		    echo '      <A HREF="'. $myrow3[0] . '">' . $myrow2[0] . "</A><BR>\n";
		else
		    echo '      ' . $myrow2[0] . "<BR>\n";

		  }
		echo "    </TD>\n";
	}
	echo "</TABLE>\n";
echo "<!-- =================Onexit makeMenu============= -->\n";
}

// Convert date from database to month, year
function convertDate( $str )
{
    $mn[0] = "???";
    $mn[1] = "January";
    $mn[2] = "February";
    $mn[3] = "March";
    $mn[4] = "April";
    $mn[5] = "May";
    $mn[6] = "June";
    $mn[7] = "July";
    $mn[8] = "August";
    $mn[9] = "September";
    $mn[10] = "October";
    $mn[11] = "November";
    $mn[12] = "December";
    $year = substr($str,0,4);
    $mm = 0;
    $mm = substr($str,5,2) + $mm;

    return $mn[$mm] . ", " . $year;
}

// Convert date from database to month, year short
function convertDateShort( $str )
{
    $mn[0] = "???";
    $mn[1] = "Jan";
    $mn[2] = "Feb";
    $mn[3] = "Mar";
    $mn[4] = "Apr";
    $mn[5] = "May";
    $mn[6] = "Jun";
    $mn[7] = "Jul";
    $mn[8] = "Aug";
    $mn[9] = "Sep";
    $mn[10] = "Oct";
    $mn[11] = "Nov";
    $mn[12] = "Dec";
    $year = substr($str,2,2);
    $mm = 0;
    $mm = substr($str,5,2) + $mm;

    return $mn[$mm] . " " . $year;
}


function Show_Nets(&$db,&$SQL) {
    $result=mysql_query($SQL,$db);

    if (mysql_errno($db) != 0 ){
	prnMsg($ErrorMessage.'<BR>' . mysql_error($db),'error', _('Database Error'));
    }

    echo '<table width="100%">' . "\n";
    echo '<TH>Net Name<TH>Net Mgr<TH COLSPAN=3>Meets<TH>Comments<TR>' . "\n";

    $rownum=1;
    while ($myrow = mysql_fetch_row($result))
	{
	    if (mysql_errno($db) != 0 ){
		prnMsg($ErrorMessage.'<BR>' . mysql_error($db),'error', _('Database Error'));
	    }
	    if ( $myrow[10] > $maxdate )
		$maxdate = $myrow[10];

	    if ( $rownum != 0 )
		{
		    $id = '<td id="OsRow3">';
		    $id2 = '<td id="OsRow5">';
		    echo '  <TR CLASS=OsRow>' . "\n";
		    $rownum = 0;
		}
	    else
		{
		    $id = '<td id="OsRow4">';
		    $id2 = '<td id="OsRow6">';
		    echo '  <TR CLASS=OsRow2>' . "\n";
		    $rownum = 1;
		}
	    echo '    ' . $id2 . '<b>' . $myrow[0] . '</b><br />' .  $myrow[1] . '</TD>' . "\n";
	    echo '    ' . $id2 . $myrow[2] . '</TD>' . "\n";
	    echo '    ' . $id2 . $myrow[3] . '<br />' . $myrow[6] . '</TD>' . "\n";
	    echo '    ' . $id2 . $myrow[4] . '<br />' . $myrow[7] . '</TD>' . "\n";
	    echo '    ' . $id2 . $myrow[5] . '<br />' . $myrow[8] . '</TD>' . "\n";
	    //echo '    ' . $id2 . $myrow[6] . '</TD>' . "\n";
	    //echo '    ' . $id2 . $myrow[7] . '</TD>' . "\n";
	    //echo '    ' . $id2 . $myrow[8] . '</TD>' . "\n";
	    echo '    ' . $id2 . $myrow[9] . '</TD>' . "\n";
	    echo '  </TR>';
	}

    echo '</TABLE>' . "\n";

echo "<P>Latest data:" . $maxdate . "<HR></P>\n";
}

// Return a result set from the database
function getResult( $SQL, $db )
{
    $result = mysql_query($SQL,$db);
    if (mysql_errno($db) != 0 )
    {
	prnMsg($ErrorMessage.'<BR>' . mysql_error($db),'query error', _('Database Error'));
	return '';
    }
    return $result;
}

// Return a row from the selected result set
function getRow($result,$db)
{
    $myrow = mysql_fetch_row($result);
    if (mysql_errno($db) != 0 )
    {
	prnMsg($ErrorMessage.'<BR>' . mysql_error($db),'fetch error', _('Database Error'));
    }
    return $myrow;
}

// Return a single result from the database
function singleResult($SQL,$db)
{
    $result = getResult($SQL,$db);
    $myrow = getRow($result,$db);
    return $myrow[0];
}

function dateLinks($period,$table,$db)
{
    for ( $i=-3; $i<4; $i++ )
	{
	    if ( $i != 0 )
	    {
		// Make links for months before and after this one
		$newperiod = $period + $i;
		// If there is any report data for that period
		$SQL = "SELECT COUNT(*) FROM " . $table . " WHERE period = " . $newperiod;
		if ( singleResult($SQL,$db) )
		{
		    // Get the date from period, convert, and display as link
		    $SQL = "SELECT lastday FROM periods WHERE periodno = " . $newperiod;
		    $displaydate = convertDateShort(singleResult($SQL,$db));
		    echo '&nbsp;<a class="month" href="' . $table . ".php?period=" . $newperiod . "\">[ "
			. $displaydate . " ]</a>\n";
		}
	    }
	}
}

function footer($start,$max,$revstring)
{
    echo "  <div id=\"footer\">\n";
    echo "    <center><img src=\"http://www.mi-arpsc.org/When-All-Else-Fails-2.gif\">" .
	"</center>\n";
    echo "    <p>&nbsp;</p>\n";
    echo "    <table width=100%>\n";
    echo "      <tr>\n";
    echo "        <td width=33%>\n";
    echo "          <center>\n              Solar Flux:<br /><img src=\"http://www.n3kl.org/sun/images/status.gif?\">" .
        "\n          </center>\n";
    echo "        </td>\n";
    echo "        <td width=33%>\n";
    echo "          <center>\n              Geomagnetic Field:<br /><img src=\"http://www.n3kl.org/sun/images/kpstatus.gif?\">" .
        "\n          </center>\n";
    echo "        </td>\n";
    echo "        <td>\n";
    echo "          <center>\n              <img src=\"http://www.dhs.gov/threat_level/current_new.gif\">" .
        "\n          </center>\n";
    echo "        </td>\n";
    echo "      </tr>\n";
    echo "    </table>\n";
    if ( $start != "" )
	echo "    Requested: " . $start . "<br />\n";
    if ( $max != "" )
	echo "    Most recent data: " . $max . "<br />\n";
    echo "    Source " . $revstring . "<br />\n";
    echo "    copyright &copy; 2007, Michigan Section, American Radio Relay League\n";
    echo "  </div>\n";

}

function genInts( $string )
{
  $s1='<a href="mailto:';

  echo "\n<script type=\"text/javascript\" language=\"javascript\">\n";
  echo "<!--\n  {\n    document.write(String.fromCharCode(";
  $outstr="<a href=\"mailto:" . $string . "\">" . $string . "</a>";
  for ( $i=0,$j=strlen($outstr); $i<$j; $i++)
  {
    if ( $i != 0 )
    {
      echo ",";
    }
    echo ord($outstr[$i]);
  }
  echo "));\n";
  echo "  }\n//-->\n</script>\n";
  echo "<noscript>\n";
  for ( $i=0,$j=strlen($string); $i<$j; $i++)
  {
    echo "&#" . ord($string[$i]) . ";";
  }
  echo "\n</noscript>\n";

} 

function sectLeaders($db)
{
echo "<!-- =================Onentry sectLeaders============= -->\n";
?>
<div id="staff">
<h2 class="title">Contact Section Leaders</h2>
<?php

    $q1='SELECT A.title, B.name, B.call, B.addr1, B.addr2, B.city, B.state, B.zip, B.id' .
	  ' FROM sect_appt A, arpscc_addr B' .
        ' WHERE A.id = B.id' .
        ' ORDER BY A.seq';
    $r1=getResult($q1,$db);

    while ( $myrow = getRow($r1,$db) )
    {
	echo '<p><strong>' . $myrow[0] . '</strong><br />' . "\n";
	echo $myrow[1] . ', ' . $myrow[2] . "<br />\n";
	echo $myrow[3] . "<br />\n";
	echo $myrow[5] . ', ' . $myrow[6] . ' ' . $myrow[7] . "<br />\n";
	$q2='SELECT type, value, seq' .
          ' FROM arpsc_contact' .
          ' WHERE id=' . $myrow[8] .
	    ' ORDER BY seq';
      $r2=getResult($q2,$db);
	while ( $myrow2 = getrow($r2,$db) )
	{
	  if ( $myrow2[2] != 0 )
	  {
		if ( $myrow2[0] == 30 )
		{
		  echo "<strong>";
		  genInts($myrow2[1]);
		  echo "</strong><br />\n";
		}
		else
		{
		  echo $myrow2[1] . "<br />\n";
		}
	  }
	}
      echo "</p>\n";
    }


?>

</div>
<?php
echo "<!-- =================Onexit sectLeaders============= -->\n";
}

function NTSheader( $title, $keywords )
{

	if ( $keywords == "" )
	    $keywords="NTS Michigan 'National Traffic System' 'Michigan Section' MI";
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" lang="en" xml:lang="en">

<head>
<?php
	echo '  <title>' . $title . '</title>' . "\n";
	echo '  <meta name="KEYWORDS" content="' . $keywords . '" />' . "\n";
?>
  <style type="text/css" media="all">@import "css/arrl3c.css";</style>
  <link rel="alternate" type="application/rss+xml" title="RSS" href="http://www.arrl-mi.org/?q=rss.xml" />
  <link rel="shortcut icon" href="/misc/favicon.ico" type="image/x-icon" />
  <!--<style type="text/css" media="all">@import "css/bluemarine.css";</style>-->
  <script type="text/javascript"> </script>
</head>

<body>
<div id="container">
  <div id="masthead">
    <table border="0" cellpadding="0" cellspacing="0" id="header" width="100%">
      <tr>
        <td id="logo1"></td>
        <td id="logo2" width="200px">
          <a href="/" title="Home"><img src="images/arrlmi.gif" alt="Home" /></a>
        </td>
        <td id="logo3"><h1 class='site-name'><a href="/" title="Home">Michigan Section NTS</a></h1>
          <div class='site-slogan'>part of the National Traffic System</div><br>&nbsp;
        </td>
        <td id="logo4" width="125px">
          <img align="right" src="images/nts.gif" alt="NTS" />
        </td>
      </tr>
    </table>
  </div>
<?php
//      makeMenu($db);
echo "<!-- =================Onexit NTSheader============= -->\n";
}

function ARESheader( $title, $keywords )
{

	if ( $keywords == "" )
	    $keywords="ARPSC ARES Michigan 'Amateur Radio Public Service Corps' 'Michigan Section' MI";
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" lang="en" xml:lang="en">

<head>
<?php
	echo '  <title>' . $title . '</title>' . "\n";
	echo '  <meta name="KEYWORDS" content="' . $keywords . '" />' . "\n";
?>
  <style type="text/css" media="all">@import "css/arrl3c.css";</style>
  <link rel="alternate" type="application/rss+xml" title="RSS" href="http://www.arrl-mi.org/?q=rss.xml" />
  <link rel="shortcut icon" href="/misc/favicon.ico" type="image/x-icon" />
  <!--<style type="text/css" media="all">@import "css/bluemarine.css";</style>-->
  <script type="text/javascript"> </script>
</head>

<body>
<div id="container">
  <div id="masthead">
    <table border="0" cellpadding="0" cellspacing="0" id="header" width="100%">
      <tr>
        <td id="logo1"></td>
        <td id="logo2" width="200px">
          <a href="/" title="Home"><img src="images/arrlmi.gif" alt="Home" /></a>
        </td>
        <td id="logo3"><h1 class='site-name'><a href="/" title="Home">Michigan Section ARES</a></h1>
          <div class='site-slogan'>part of the Amateur Radio Public Service Corps</div><br>&nbsp;
        </td>
        <td id="logo4" width="125px">
          <img align="right" src="images/ares.gif" alt="NTS" />
        </td>
      </tr>
    </table>
  </div>
<?php
echo "<!-- =================Onexit ARESheader============= -->\n";
}

function passwd( $pass, $pass2 )
{
    return base64_decode( $pass );
}

function openDatabase( $dppF )
{
      $host = base64_decode($dppF[0]);
      $dbuser = base64_decode($dppF[1]);
      $dbpassword = base64_decode($dppF[2]);

//    print "<p>----&gt;<b>" . $dppF[0] . "</b>&lt;----</p>\n";
//    print "<p>----&gt;<b>" . $dppF[1] . "</b>&lt;----</p>\n";
//    print "<p>----&gt;<b>" . $dppF[2] . "</b>&lt;----</p>\n";

    $db = mysql_connect( $host, $dbuser, $dbpassword );

    return $db;
}

function myInit( $aa0, $aa1, $aa2, $aa3 )
{
    $db = mysql_connect(
	base64_decode($aa0),
	base64_decode($aa1),
	base64_decode($aa2)
				);
    mysql_select_db(base64_decode($aa3),$db);
    return $db;
}

?>
