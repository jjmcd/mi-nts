<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
  "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html>
<head>
  <link href="strict.css" rel="stylesheet" type="text/css" />
  <title>Reporting Result</title>
</head>
<body>
<h1>Reporting Result</h1>

<?php
   $msgstr;
   $timestring; 
   $reportingmonth; 
   $reportdate;

   /*#!/usr/bin/perl2 -w
#****************************************************************************
#
#  PSHR_result.pl - 22-Oct-2006
#                   31-Dec-2007 changed rm+1 to rm in reportingDate()
#                               line 93 (timelocal())
#
#****************************************************************************
use DBI;
use CGI qw(:standard escapeHTML);
use POSIX qw(strftime);
use Time::Local;

#============================================================================
# Message header
#============================================================================*/
function msgHeader( $msgnum, $call, $count, $city, $filedate )
{
    print $msgnum . "  R  " . $call ."  " . $count . "  " . $city . 
	" MI  " . $filedate . "\n";
    print "WB8RCR\n";
    print " \n";
}

 /*#============================================================================
# Pad line to multiple of 7 so groups align
#============================================================================*/
function padLine( &$msgstr )
{
    $target = 7;
    if ( strlen($msgstr) > 7 )
    {
	$target = 14;
    }
    if ( strlen($msgstr) > 14 )
    {
	$target = 21;
    }
    if ( strlen($msgstr) > 21 )
    {
	$target = 28;
    }
    while (strlen($msgstr)<$target)
    {
	$msgstr = $msgstr . " ";
    }
}

/*#============================================================================
# Convert null numeric field, or zero numeric fields to 'ZERO'
#============================================================================*/
function checkZero( $s )
{
    if ( $s < 1 )
    {
	return "ZERO";
    }
    else
    {
	return $s;
    }
}

//#============================================================================
//# Be sure empty numeric fields contain zero
//#============================================================================
function digZero( $s )
{
    if ( $s < 1 )
    {
	return 0;
    }
    else
    {
	return $s;
    }
}

//#============================================================================
//# Calculate reporting month and format current date
//#============================================================================
function reportingDate(&$reportingmonth,&$reportdate)
{

  date_default_timezone_set('America/Detroit');
  $now = localtime();

  //for ($i=0; $i<6; $i++)
  //    print "now[" . $i . "]=" . $now[$i] . "\n";

  // Reporting Month
  // $now[4] = current month [0..11]
  $rm = $now[4];
  if ($now[3] > 25)
    {
	$rm = $rm + 1;
    }
    if ( $rm > 11 )
    {
	$rm = 0;
    }
    // Date report submitted
    $rn = $now;
    $rn[4] = $rm;

    //print "<p>mktime(" . 12 . "," . 0 . "," . 0 .
    //  "," . $rm . "," . ($now[3]) . "," . ($now[5]+1900) . ")</p>\n";

    $timestamp = mktime(12,0,0,$rm,($now[3]),($now[5]+1900));

    //print "<p>Timestamp:" . $timestamp . "</p>\n";
    //print "<P>D=" . strftime("%Y",$timestamp) . "-" . strftime("%b",$timestamp) . "-" . strftime("%d",$timestamp) . "</p>\n";
    
    $reportingmonth = strtoupper(strftime("%b",$timestamp));
    $reportdate = strtoupper( strftime( "%b %d",(mktime()) ) );

    $period = ( $now[5]-101 ) * 12 + $now[3] - 1;
    return $period;
}

#============================================================================
# Station Activity Report or Brass Pounder's League
#============================================================================
function SAR( $so, $sr, $ss, $sd, $bpl, 
	      $msgnum, $call, $city, $filedate, $month, $period,
	      &$str1, &$str2 )
{
    # If no traffic, no report
    $str1 = "";
    $str2 = "";
    if ( ($so + $sr + $ss + $sd) > 0 )
    {
	# Be sure no empty print values
	$sop = checkZero($so);
	$ssp = checkZero($ss);
	$srp = checkZero($sr);
	$sdp = checkZero($sd);
	print "<div class=\"msg\">\n";
	print "<pre>\n";
	# Print BPL if appropriate
	if ($bpl)
	{
	    $count = 10;
	    msgHeader( $msgnum, $call, $count, $city, $filedate );
	    $msgstr = "BPL    " . $month . "    O/" . $sop;
	    padLine($msgstr);

	    $msgstr = $msgstr . "S/" . $ssp;
	    padLine($msgstr);

	    $msgstr = $msgstr . "R/" . $srp . "\n";
	    print $msgstr;
	    $msgstr = "D/" . $sdp;
	    padLine($msgstr);

	    $msgstr = $msgstr . "TOTAL  " . ($so+$ss+$sr+$sd);
	    padLine($msgstr);

	    $msgstr = $msgstr . "X      73";
	    print $msgstr . "\n&nbsp\n";
	    print $call . "\n&nbsp;\n";
	    $str1="INSERT INTO `sar` VALUES(" . $period . ",'" . $call . "'," . 
	      ($so+$ss+$sr+$sd) . ",NULL,now());";
	    $str2="INSERT INTO `bpl` VALUES(" . $period . ",'" . $call . "'," .
	      $so . "," .
	      $ss . "," .
	      $sr . "," .
	      $sd . "," .
	      ($so+$ss+$sr+$sd) . ",now());";
	}
	# Print SAR if not BPL
	else
	{
	    $count = 6;
	    msgHeader( $msgnum, $call, $count, $city, $filedate );
	    $msgstr = "SAR    " . $month . "    TOTAL  ";
	    $msgstr = $msgstr . ($so + $sr + $ss + $sd);
	    padLine($msgstr);
	    $msgstr = $msgstr . "X ";
	    print $msgstr . "\n";
	    print "73\n&nbsp;\n";
	    print $call . "\n&nbsp;\n";
	    $str1="INSERT INTO `sar` VALUES(" . $period . ",'" . $call . "'," . 
	      ($so+$ss+$sr+$sd) . ",NULL,now());";
	}
	print "</pre>\n";
	print "</div>\n";
	print "<p>&nbsp;</p>\n";
    }
}

#============================================================================
# Public Service Honor Roll
#============================================================================
function PSHR( $p1, $p2, $p3, $p4, $p5, $p6, $msgnum, $call, $city, 
	       $filedate, $month, $period, &$str3 )
{
  $str3 = "";
    # Figure group count
    $count = 6;
    if ( $p1 > 0 )
    {
	$count = $count + 1;
    }
    if ( $p2 > 0 )
    {
	$count = $count + 1;
    }
    if ( $p3 > 0 )
    {
	$count = $count + 1;
    }
    if ( $p4 > 0 )
    {
	$count = $count + 1;
    }
    if ( $p5 > 0 )
    {
	$count = $count + 1;
    }
    if ( $p6 > 0 )
    {
	$count = $count + 1;
    }
    print "<div class=\"msg\">\n";
    print "<pre>\n";
    msgHeader( ($msgnum+1), $call, $count, $city, $filedate );

    $msgstr = "PSHR   " . $month . "    ";
    if ( $p1 > 0 )
    {
	$msgstr = $msgstr . "1/" . $p1;
	padLine($msgstr);
    }
    if ( $p2 > 0 )
    {
	$msgstr = $msgstr . "2/" . $p2;
	padLine($msgstr);
    }
    if ( $p3 > 0 )
    {
	$msgstr = $msgstr . "3/" . $p3;
	padLine($msgstr);
    }
    if ( strlen($msgstr)>29 )
    {
	print $msgstr . "\n";
	$msgstr = "";
    }
    if ( $p4 > 0 )
    {
	$msgstr = $msgstr . "4/" . $p4;
	padLine($msgstr);
    }
    if ( strlen($msgstr)>29 )
    {
	print $msgstr . "\n";
	$msgstr = "";
    }
    if ( $p5 > 0 )
    {
	$msgstr = $msgstr . "5/" . $p5;
	padLine($msgstr);
    }
    if ( strlen($msgstr)>29 )
    {
	print $msgstr . "\n";
	$msgstr = "";
    }
    if ( $p6 > 0 )
    {
	$msgstr = $msgstr . "6/" . $p6;
	padLine($msgstr);
    }
    if ( strlen($msgstr)>29 )
    {
	print $msgstr . "\n";
	$msgstr = "";
    }
    $msgstr = $msgstr . "TOTAL  ";
    if ( strlen($msgstr)>29 )
    {
	print $msgstr . "\n";
	$msgstr = "";
    }
    $msgstr = $msgstr . ($p1+$p2+$p3+$p4+$p5+$p6);
    padLine($msgstr);
    if ( strlen($msgstr)>29 )
    {
	print $msgstr . "\n";
	$msgstr = "";
    }
    $msgstr = $msgstr . "X ";
    padLine($msgstr);
    if ( strlen($msgstr)>29 )
    {
	print $msgstr . "\n";
	$msgstr = "";
    }
    $msgstr = $msgstr . "73";
    print $msgstr . "\n&nbsp;\n" . $call . "\n&nbsp;\n";

    $str3 = "INSERT INTO `pshr` VALUES(" . $period . ",'" . $call . 
      "'," . $p1 .
      "," . $p2 .
      "," . $p3 .
      "," . $p4 .
      "," . $p5 .
      "," . $p6 .
      "," .  ($p1+$p2+$p3+$p4+$p5+$p6) . ",NULL,now());";
    print "</pre>\n";
    print "</div>\n";
}

#============================================================================
# Print trailing warning on the web page
#============================================================================
function trailer()
{
    print "<p><b>Note: </b>This report is formatted for you to\n";
    print "transmit to an NTS net, deliver by packet, or deliver by\n";
    print "email or other means.  No data is recorded on this site,\n";
    print "and this data is not transmitted anywhere.</p>\n";
    print "<p>WB8RCR may be reached by IP packet at wb8rcr@hamgate.midland.ampr.org.\n";
    print "Any of the Michigan section nets will also deliver " .
	"traffic to WB8RCR.</p>\n";
    print "<p>Reports are due to the Section Traffic Manager by the\n";
    print "fifth of the month following the activity reported.</p>\n";
}

#============================================================================
# M a i n l i n e
#============================================================================

//do "./common-functions.pl";
//
//PrintBanner();

$period = reportingDate($reportingmonth,$reportdate);

$filedate = $reportdate;
$month = $reportingmonth;

$msgnum = $_GET['msgnum'];
$call = strtoupper($_GET['call']);
$city = strtoupper($_GET['city']);

$so = $_GET['orig'];
$ss = $_GET['sent'];
$sr = $_GET['recd'];
$sd = $_GET['deld'];

$p2 = digZero($ss) + digZero($so) + digZero($sr) + digZero($sd);

if ( $p2 > 40 )
{
    $p2 = 40;
}

$p1 = $_GET['qni'];
if ( $p1 > 40 )
{
    $p1 = 40;
}

$p3 = 10 * $_GET['appts'];
$p4 = 5 * $_GET['planned'];
$p5 = 5 * $_GET['unplanned'];
$p6 = 10 * $_GET['bbs'];

$bpl = 0;
if ( ($so + $sd) > 100 )
{
    $bpl = 1;
}
if ( ($so + $sd + $sr + $ss) > 500 )
{
    $bpl = 1;
}

SAR( $so, $sr, $ss, $sd, $bpl, $msgnum, $call, $city, 
     $filedate, $month, $period, $str1, $str2 );
PSHR( $p1, $p2, $p3, $p4, $p5, $p6, $msgnum, $call, $city, 
      $filedate, $month, $period, $str3 );
trailer();

//print "<p>Report:</p><p>1) $str1</p><p>2) $str2</p><p>3) $str3</p>\n";

print "<hr />\n";
print "<a href=\"mailto:reports@is-sixsigma.com?subject=NTS report $call $month&body=%0a$str1%0a$str2%0a$str3\">Send report by email</a>\n";

print "<p class=\"rev\">Page modified 2011-04-15 17:09</p>\n";

//exit;
?>

<!--
#config timefmt="%Y-%m-%d %H:%M"
<p class="rev">Page modified 2011-04-15
#echo var="LAST_MODIFIED"
</p>
-->
<div class="i">
<!--
  <a href="http://validator.w3.org/check?uri=referer">
  <img src="http://www.w3.org/Icons/valid-xhtml10"
    alt="Valid XHTML 1.0 Strict" height="31" width="88" /></a>
-->
</div>
</body>
</html>
