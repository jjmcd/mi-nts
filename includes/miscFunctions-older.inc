<?php

function makeMenu($db)
{
    $SQL = 'SELECT nbid,buttontext,sequence,pageid FROM navbar_heads ORDER BY sequence';
    $result=mysql_query($SQL,$db);

    if (mysql_errno($db) != 0 ){
	prnMsg($ErrorMessage.'<BR>' . mysql_error($db),'error', _('Database Error')); }

    echo "<TABLE CLASS=quick_menu WIDTH=100%>\n";

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
		    echo '      <A HREF="'. $myrow2[0] . '">' . $myrow[1] . "<A>\n";
		else
		    echo '      ' . $myrow[1] . "<A>\n";

	}
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
		    echo '      <A HREF="'. $myrow3[0] . '">' . $myrow2[0] . "<A><BR>\n";
		else
		    echo '      ' . $myrow2[0] . "<BR>\n";

		  }

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
?>
