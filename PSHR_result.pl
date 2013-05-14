#!/usr/bin/perl2 -w
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
#============================================================================
sub msgHeader
{
    print $msgnum . "  R  " . $call ."  " . $count . "  " . $city . 
	" MI  " . $filedate . "\n";
    print "WB8RCR\n";
    print " \n";
}

#============================================================================
# Pad line to multiple of 7 so groups align
#============================================================================
sub padLine
{
    $target = 7;
    if ( length($msgstr) > 7 )
    {
	$target = 14;
    }
    if ( length($msgstr) > 14 )
    {
	$target = 21;
    }
    if ( length($msgstr) > 21 )
    {
	$target = 28;
    }
    while (length($msgstr)<$target)
    {
	$msgstr = $msgstr . " ";
    }
}

#============================================================================
# Convert null numeric field, or zero numeric fields to 'ZERO'
#============================================================================
sub checkZero
{
    if ( $_[0] < 1 )
    {
	return "ZERO";
    }
    else
    {
	return $_[0];
    }
}

#============================================================================
# Be sure empty numeric fields contain zero
#============================================================================
sub digZero
{
    if ( $_[0] < 1 )
    {
	return 0;
    }
    else
    {
	return $_[0];
    }
}

#============================================================================
# Calculate reporting month and format current date
#============================================================================
sub reportingDate
{
    ($DAY, $MONTH, $YEAR) = (localtime)[3,4,5];
    $rm = $MONTH;
    print "<P>RM=$MONTH</p>\n";
    if ($DAY < 25)
    {
	$rm = $MONTH - 1;
    }
    if ( $rm < 0 )
    {
	$rm = 11;
    }
    $timestring = timelocal(0,0,12,15,$rm,$YEAR);
    $reportingmonth = uc(strftime("%b",localtime($timestring)));
    $reportdate = uc(strftime("%b %d",(localtime)));
}

#============================================================================
# Station Activity Report or Brass Pounder's League
#============================================================================
sub SAR
{
    # If no traffic, no report
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
	    msgHeader;
	    $msgstr = "BPL    " . $month . "    O/" . $sop;
	    padLine;

	    $msgstr = $msgstr . "S/" . $ssp;
	    padLine;

	    $msgstr = $msgstr . "R/" . $srp . "\n";
	    print $msgstr;
	    $msgstr = "D/" . $sdp;
	    padLine;

	    $msgstr = $msgstr . "TOTAL  " . ($so+$ss+$sr+$sd);
	    padLine;

	    $msgstr = $msgstr . "X      73";
	    print $msgstr . "\n&nbsp\n";
	    print $call . "\n&nbsp;\n";
	}
	# Print SAR if not BPL
	else
	{
	    $count = 6;
	    msgHeader;
	    $msgstr = "SAR    " . $month . "    TOTAL  ";
	    $msgstr = $msgstr . ($so + $sr + $ss + $sd);
	    padLine;
	    $msgstr = $msgstr . "X ";
	    print $msgstr . "\n";
	    print "73\n&nbsp;\n";
	    print $call . "\n&nbsp;\n";
	}
	print "</pre>\n";
	print "</div>\n";
	print "<p>&nbsp;</p>\n";
	$msgnum = $msgnum + 1;
    }
}

#============================================================================
# Public Service Honor Roll
#============================================================================
sub PSHR
{
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
    msgHeader;

    $msgstr = "PSHR   " . $month . "    ";
    if ( $p1 > 0 )
    {
	$msgstr = $msgstr . "1/" . $p1;
	padLine;
    }
    if ( $p2 > 0 )
    {
	$msgstr = $msgstr . "2/" . $p2;
	padLine;
    }
    if ( $p3 > 0 )
    {
	$msgstr = $msgstr . "3/" . $p3;
	padLine;
    }
    if ( length($msgstr)>29 )
    {
	print $msgstr . "\n";
	$msgstr = "";
    }
    if ( $p4 > 0 )
    {
	$msgstr = $msgstr . "4/" . $p4;
	padLine;
    }
    if ( length($msgstr)>29 )
    {
	print $msgstr . "\n";
	$msgstr = "";
    }
    if ( $p5 > 0 )
    {
	$msgstr = $msgstr . "5/" . $p5;
	padLine;
    }
    if ( length($msgstr)>29 )
    {
	print $msgstr . "\n";
	$msgstr = "";
    }
    if ( $p6 > 0 )
    {
	$msgstr = $msgstr . "6/" . $p6;
	padLine;
    }
    if ( length($msgstr)>29 )
    {
	print $msgstr . "\n";
	$msgstr = "";
    }
    $msgstr = $msgstr . "TOTAL  ";
    if ( length($msgstr)>29 )
    {
	print $msgstr . "\n";
	$msgstr = "";
    }
    $msgstr = $msgstr . ($p1+$p2+$p3+$p4+$p5+$p6);
    padLine;
    if ( length($msgstr)>29 )
    {
	print $msgstr . "\n";
	$msgstr = "";
    }
    $msgstr = $msgstr . "X ";
    padLine;
    if ( length($msgstr)>29 )
    {
	print $msgstr . "\n";
	$msgstr = "";
    }
    $msgstr = $msgstr . "73";
    print $msgstr . "\n&nbsp;\n" . $call . "\n&nbsp;\n";

    print "</pre>\n";
    print "</div>\n";
}

#============================================================================
# Print trailing warning on the web page
#============================================================================
sub trailer
{
    print "<p><b>Note: </b>This report is formatted for you to\n";
    print "transmit to an NTS net, deliver by packet, or deliver by\n";
    print "email or other means.  No data is recorded on this site,\n";
    print "and this data is not transmitted anywhere.</p>\n";
    print "<p>WB8RCR may be reached by IP packet at wb8rcr\@wb8rcr.ampr.org.\n";
    print "Any of the Michigan section nets will also deliver " .
	"traffic to WB8RCR.</p>\n";
    print "<p>Reports are due to the Section Traffic Manager by the\n";
    print "fifth of the month following the activity reported.</p>\n";
}

#============================================================================
# M a i n l i n e
#============================================================================

do "./common-functions.pl";

&PrintBanner;

reportingDate;

$filedate = $reportdate;
$month = $reportingmonth;

$msgnum = param('msgnum');
$call = uc(param('call'));
$city = uc(param('city'));

$so = param('orig');
$ss = param('sent');
$sr = param('recd');
$sd = param('deld');

$p2 = digZero($ss) + digZero($so) + digZero($sr) + digZero($sd);

if ( $p2 > 40 )
{
    $p2 = 40;
}

$p1 = param('qni');
if ( $p1 > 40 )
{
    $p1 = 40;
}

$p3 = 10 * param('appts');
$p4 = 5 * param('planned');
$p5 = 5 * param('unplanned');
$p6 = 10 * param('bbs');

$bpl = 0;
if ( ($so + $sd) > 100 )
{
    $bpl = 1;
}
if ( ($so + $sd + $sr + $ss) > 500 )
{
    $bpl = 1;
}

SAR;
PSHR;
trailer;

exit;
