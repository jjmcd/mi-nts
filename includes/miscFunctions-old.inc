<?php

function leftBar($db)
{
echo "<!-- ONENTRY leftBar -->\n";
    $SQL = 'SELECT nbid,buttontext,sequence,pageid FROM navbar_heads ORDER BY sequence';
    $result=mysql_query($SQL,$db);

    if (mysql_errno($db) != 0 ){
	prnMsg($ErrorMessage.'<BR>' . mysql_error($db),'error', _('Database Error')); }

    echo '<table border="0" cellpadding="0" cellspacing="0" id="content">' .
          "\n";
    echo "  <tr>\n";

    while ($myrow = mysql_fetch_row($result))
	{
	    if (mysql_errno($db) != 0 ){
		prnMsg($ErrorMessage.'<BR>' . mysql_error($db),'error', _('Database Error'));
	    }
		$SQL2='SELECT url FROM webpages WHERE pageID=' . $myrow[3];
		$result2=mysql_query($SQL2,$db);
		if (mysql_errno($db) != 0 ){
		    prnMsg($ErrorMessage.'<BR>' . mysql_error($db),'error', _('Database Error')); }
		echo "    <td id=\"sidebar-left\">\n";
		echo "    <div class=\"block block-block\" id-\"block-block-1\">\n";
		if ( $myrow2=mysql_fetch_row($result2) )
		    echo '      <h2 class=\"title\"><a href="'. $myrow2[0] . '">' . $myrow[1] . "</a></h2>\n";
		else
		    echo '      <h2 class=\"title\">' . $myrow[1] . "</h2>\n";
//		echo "    </TH>\n";

	}
//	echo "  </TR>\n";
//	echo "  <TR>\n";

    // Need to do the original query again
    $result=mysql_query($SQL,$db);

    while ($myrow = mysql_fetch_row($result))
	{
	    if (mysql_errno($db) != 0 ){
		prnMsg($ErrorMessage.'<BR>' . mysql_error($db),'error', _('Database Error')); }

//		echo "    <TD CLASS=menu_group_item ALIGN=CENTER>\n";
		echo "    <div class=\"content\">\n";
		echo "      <ul>\n";

		// Now get the navbar_subs result
		$SQL2 = 'SELECT buttontext,pageid FROM navbar_subs WHERE nbid=' . $myrow[0] . ' ORDER BY sequence';
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
		    echo '      <li><a href="'. $myrow3[0] . '">' . $myrow2[0] . "</a></li>\n";
		else
		    echo '      <li>' . $myrow2[0] . "</li>\n";

		  }
		echo "    </ul>\n";
	}
	echo "<p><hr size=\"1\"></p>\n";
echo "<!-- ONEXIT leftBar -->\n";
}

function makeMenu($db)
{
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
		$SQL2 = 'SELECT buttontext,pageid FROM navbar_subs WHERE nbid=' . $myrow[0] . ' ORDER BY sequence';
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


function Show_Nets(&$db,&$SQL) {
    $result=mysql_query($SQL,$db);

    if (mysql_errno($db) != 0 ){
	prnMsg($ErrorMessage.'<BR>' . mysql_error($db),'error', _('Database Error'));
    }

    echo '<TABLE>' . "\n";
    echo '<TH COLSPAN=2>Net Name<TH>Net Manager<TH COLSPAN=6>Meets<TH>Comments<TR>' . "\n";

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
		    echo '  <TR CLASS=OsRow>' . "\n";
		    $rownum = 0;
		}
	    else
		{
		    echo '  <TR CLASS=OsRow2>' . "\n";
		    $rownum = 1;
		}
	    echo '    <TD>' . $myrow[0] . '</TD>' . "\n";
	    echo '    <TD>' . $myrow[1] . '</TD>' . "\n";
	    if ( $rownum != 0 )
		echo '    <TD BGCOLOR=#888888>' . $myrow[2] . '</TD>' . "\n";
	    else
		echo '    <TD BGCOLOR=#666666>' . $myrow[2] . '</TD>' . "\n";
	    echo '    <TD>' . $myrow[3] . '</TD>' . "\n";
	    echo '    <TD>' . $myrow[4] . '</TD>' . "\n";
	    echo '    <TD>' . $myrow[5] . '</TD>' . "\n";
	    echo '    <TD>' . $myrow[6] . '</TD>' . "\n";
	    echo '    <TD>' . $myrow[7] . '</TD>' . "\n";
	    echo '    <TD>' . $myrow[8] . '</TD>' . "\n";
	    if ( $rownum != 0 )
		echo '    <TD BGCOLOR=#888888>' . $myrow[9] . '</TD>' . "\n";
	    else
		echo '    <TD BGCOLOR=#666666>' . $myrow[9] . '</TD>' . "\n";
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
		    $displaydate = convertDate(singleResult($SQL,$db));
		    echo '<A HREF="' . $table . ".php?period=" . $newperiod . "\"> &nbsp;["
			. $displaydate . "]&nbsp; </A>\n";
		}
	    }
	}
}

function footer($start,$max,$revstring)
{
    echo "<HR>\n";
    echo "<P CLASS=foot>Requested: " . $start . "<BR>\n";
    echo "Most recent data: " . $max . "<BR>\n";
    echo "Source " . $revstring . "</P>\n";
}

function sectLeaders()
{
?>
<td id="sidebar-right">
<div class="block block-block" id="block-block-2">
<h2 class="title">Contact Section Leaders</h2>
<div class="content"><p><strong>Section Manager</strong><br />
Dale R. Williams, WA8EFK<br />
291 Outer Dr<br />
Dundee, MI 48131-1014<br />
734-529-3232, <a href="mailto:wa8efk@arrl.org">wa8efk@arrl.org</a></p>
<p><strong>Assistant Section Manager - Administration</strong><br />
Roger D Edwards, WB8WJV<br />
709 S Almer St<br />
Caro, MI 48723-1812<br />
989-673-5722, <a href="mailto:wb8wjv@arrl.net">wb8wjv@arrl.net</a></p>
<p><strong>Special Projects</strong><br />
John T Freeman, N8ZE<br />
333 Jefferson St<br />
Sterling, MI 48659-9547<br />
989-654-2471, <a href="mailto:n8ze@arrl.net">n8ze@arrl.net</a></p>
<p><strong>Affiliated Club Coordinator</strong><br />
Daniel M Romanchik, KB6NU<br />
1325 Orkney Dr<br />
Ann Arbor, MI 48103-2966<br />
734-930-6564, <a href="mailto:kb6nu@w8pgw.org">kb6nu@w8pgw.org</a></p>
<p><strong>Official Observer Coordinator</strong><br />
Donald G Sefcik, N8NJE<br />
4486 American Heritage Rd<br />
Grand Blanc, MI 48439-7709<br />
810-694-3823, <a href="mailto:<br />
n8nje@arrl.net">n8nje@arrl.net</a></p>
<p><strong>Public Information Coordinator</strong><br />
Bobby Corr, N8CY<br />
2205 Michele Ct.<br />
Troy, MI 48085-3826<br />
248-346-2733, <a href="mailto:<br />
n8cy@gmarc.org">n8cy@gmarc.org</a></p>
<p><strong>Section Emergency Coordinator</strong><br />
John J Mc Donough, WB8RCR<br />
2211 Laurel Ln<br />
Midland, MI 48642-3820<br />
989-631-0178, 989-430-4855, <a href="mailto:<br />
wb8rcr@arrl.net">wb8rcr@arrl.net</a></p>
<p><strong>State Government Liaison</strong><br />
Edward L Hude, WA8QJE<br />
114 S College Rd<br />
Mason, MI 48854-9786<br />
<a href="mailto:wa8qje@arrl.net">wa8qje@arrl.net</a></p>
<p><strong>Section Traffic Manager</strong><br />
John J Mc Donough, WB8RCR<br />
2211 Laurel Ln<br />
Midland, MI 48642-3820<br />
989-631-0178, 989-430-4855, <a href="mailto:<br />
wb8rcr@arrl.net">wb8rcr@arrl.net</a></p>
<p><strong>Technical Coordinator</strong><br />
W Wallace Murray, KE8HR<br />
1403 S Hill Rd<br />
Milford, MI 48381-2854<br />
<a href="mailto:ke8hr@arrl.net">ke8hr@arrl.net</a></p>
</div>
 </div>
    </td>
<?php
}
function passwd( $pass, $pass2 )
{
    return base64_decode( $pass );
}

function openDatabase( $dppE )
{
    $fh = fopen(base64_decode($dppE),'r');
    $dbpwd = fgets($fh,4096);
    $dbuser = fgets($fh,4096);
    $dbx = fgets($fh,4096);
    $dbname = fgets($fh,4096);
    $dbh = fgets($fh,4096);
    fclose($fh);

    $db = mysql_connect(base64_decode($dbh) , base64_decode($dbuser), base64_decode($dbpwd));

    return $db;
}


{
  $dbpassword=base64_decode($dbp);
}
?>
