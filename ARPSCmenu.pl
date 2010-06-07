#!/usr/bin/perl -wT
# $Id: ARPSCmenu.pl,v 1.3 2006-11-27 10:45:44-05 jjmcd Exp jjmcd $
#
#=============================================================================
# ARPSC login check and menu
#
# Examine cookie to see if user logged on.  If not, look at usercode and
# password from ARPSClogin.html and validate against callsign table.
#
# If no valid login, display error and link to login
#
# If valid login, build menu based on privs table
#
# $Revision: 1.3 $
# $Date: 2006-11-27 10:45:44-05 $
# $Author: jjmcd $
#
#=============================================================================
use DBI;
#use strict;
use CGI qw(:standard escapeHTML);
#use CGI;

#-----------------------------------------------------------------------------
# County menu
#-----------------------------------------------------------------------------
sub countyMenu
{
    $pqs = "SELECT jurisdiction FROM arpsc_privs WHERE edit=1 AND call='"
	. $usercode . "' ORDER BY jurisdiction";
    my $pqh = $dbh->prepare($pqs);
    $pqh->execute();
    while ( my $ref = $pqh->fetchrow_hashref() )
    {
	$cqs = "SELECT countyname FROM arpsc_counties WHERE countycode='"
	    . $ref->{'jurisdiction'} . "'";
	my $cqh = $dbh->prepare($cqs);
	$cqh->execute();
	if ( $cref = $cqh->fetchrow_hashref() )
	{
	    print "<tr><td>Edit data for <a class=\"cnty\" href=\"FSD212edit.pl?county=" .
		$ref->{'jurisdiction'} . "\"> " .
		$cref->{'countyname'} . "</a></td></tr>\n";
	}
    }
}

#-----------------------------------------------------------------------------
# District menu
#-----------------------------------------------------------------------------
sub districtMenu
{
    $pqs = "SELECT jurisdiction FROM arpsc_privs WHERE edit=2 AND call='"
	. $usercode . "' ORDER BY jurisdiction";
    my $pqh = $dbh->prepare($pqs);
    $pqh->execute();
    while ( my $ref = $pqh->fetchrow_hashref() )
    {
	# Now find all counties in the district
	$cqs = "SELECT countyname,countycode FROM arpsc_counties WHERE district='"
	    . $ref->{'jurisdiction'} . "' ORDER BY countyname";
	my $cqh = $dbh->prepare($cqs);
	$cqh->execute();
	while ( my $cref = $cqh->fetchrow_hashref() )
	{
	    print "<tr><td>Edit data for <a class=\"dist\" href=\"FSD212edit.pl?county=" .
		$cref->{'countycode'} . "\"> " .
		$cref->{'countyname'} . "</a></td></tr>\n";
	}
    }
}

#-----------------------------------------------------------------------------
# System Menu
#-----------------------------------------------------------------------------
sub systemMenu
{
    $pqs = "SELECT jurisdiction FROM arpsc_privs WHERE edit=3 AND call='"
	. $usercode . "' ORDER BY jurisdiction";
    my $pqh = $dbh->prepare($pqs);
    $pqh->execute();
    while ( my $ref = $pqh->fetchrow_hashref() )
    {
	print "<tr><td>Maintain <a class=\"maint\" href=\"FSD212" . $ref->{'jurisdiction'} . ".shtml\"> " .
	    $ref->{'jurisdiction'} . "</a></td></tr>\n";
	#$cookie = cookie ( -NAME    => $ref->{'jurisdiction'},
	#		   -VALUE   => "valid",
	#		   -EXPIRES => "+2d" );
	#print header(-COOKIE => $cookie);
    }
}

#-----------------------------------------------------------------------------
# Successful login
#-----------------------------------------------------------------------------
sub goodLogin
{
    #print "<div class=\"callout_main\">\n";
    print "<div class=\"header\">\n";
    print "<h3>ARPSC FSD-212 Menu for " . $username . "</h3>\n";
    print "</div>\n";
    print "<center><table class=\"menu\">\n";
    #print "<tr><th class=\"tableheader\">ARPSC menu for " . $username . "</th></tr>\n";
    countyMenu;
    systemMenu;
    districtMenu;
    print "</table></center>\n";
    #print "</div>\n";
}

#-----------------------------------------------------------------------------
# Display failed login and link back to login page
#-----------------------------------------------------------------------------
sub badLogin
{
    print "<div class=\"header\">\n";
    print "<h3>Invalid usercode/password</h3>\n";
    print "</div>\n";
    print "<div class=\"report_area\">\n";
    print "<p>Please try again</p>\n";
    print "<p><a href=\"ARPSClogin.html\">Return to login screen</a></p>\n";
    print "<p>&nbsp;</p>\n";
    #print "User = " . $user . "<br>\n";
    #print "Usercode = " . $usercode . "<br>\n";
    #print "Password = " . $passwd . "<br>\n";
    print "</div>\n";
}

#-----------------------------------------------------------------------------
# Check usercode and password
#-----------------------------------------------------------------------------
sub checkUser
{
    # Fetch call and usercode from login panel
    $user = param("call");
    $passwd = param("pwd");
    # Upcase usercode
    $usercode = uc($user);

    # Prepare query string
    $lqs="SELECT password,name FROM calldirectory WHERE callsign='" . $usercode
        . "'";
    # Fetch password for user
    my $lqh = $dbh->prepare($lqs);
    $lqh->execute();
    # Check result
    if ( my $ref = $lqh->fetchrow_hashref() )
    {
        # Got a password, is it the right one?
	my $fpw = $ref->{'password'};
	#print "<p>Provided password:" . $passwd . ".</p>\n";
	#print "<p>Fetched password:" . $fpw . ".</p>\n";
	if ( $fpw eq $passwd )
	{
	    # Good password, allow login
	    $loginResult = 1;
	    #print "<p>Success</p>\n";
	    $username = $ref->{'name'};
	}
	else
	{
	    # Bad password, disallow login
	    $loginResult = 0;
	}
    }
    else
    {
	# No such user, disallow login
	$loginResult = 0;
    }
}

#-----------------------------------------------------------------------------
# Display boilerplate HTML opening
#-----------------------------------------------------------------------------
sub htmlStart
{
    print "<!DOCTYPE html PUBLIC \"-//W3C//DTD XHTML 1.0 Strict//EN\"\n";
    print "  \"http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd\">\n";
    print "<html>\n";
    print "<head>\n";
    print "<meta http-equiv=\"Content-Type\" content=\"text/html\" />\n";
    print "<link href=\"css/ARPSC/default.css\" rel=\"stylesheet\" type=\"text/css\" />\n";
    if ( $_[0] eq "bad" )
    {
	print "<title>Invalid username/password</title>\n";
    }
    else
    {
	print "<title>Logging in</title>\n";
    }
    print "</head>\n";
    print "<body>\n";
}
#-----------------------------------------------------------------------------
# M a i n l i n e
#-----------------------------------------------------------------------------

$q = new CGI;
$refer = $ENV{'HTTP_REFERER'};
#print "Referer = [" . $refer . "]<br>\n";
@parts = split(?/?,$refer);
foreach $p(@parts)
{
    $tp = $p;
}

$loginResult = 0;
$user = cookie("usercode");
if ( $user )
{
    if ( $user ne "INVALID" )
    {
	$cookievalue = $user;
	$usercode = uc($user);
	$loginResult = 1;
    }
}

do "./common-functions.pl";
$dbh=&ConnectToDatabase;

if ( $loginResult == 0 )
{
    # Pick up POST variables from form

    foreach $i($q->param())
    {
	$f{$i} = $q->param($i);
    }

    $user = param("call");
    &checkUser;
}

if ( $loginResult == 1 )
  {
      $cookie = cookie ( -NAME    => "usercode",
			 -VALUE   => uc($user),
			 -EXPIRES => "+2d");
      print header(-COOKIE => $cookie);
      htmlStart("good");
      #print "\n<p>Cookie value='" . $cookievalue . "'</p>\n";
      &goodLogin
  }
  else
  {
      $cookie = cookie ( -NAME    => "usercode",
			 -VALUE   => "INVALID",
			 -EXPIRES => "+1h");
      print header(-COOKIE => $cookie);
      htmlStart("bad");
      #print "\n<p>Cookie value='" . $cookievalue . "'</p>\n";
      &badLogin;
  }

#foreach $p(@parts)
#{
#    print "(" . $p . ")<br>\n";
#}
#print "Trailing part = (" . $tp . ")<br>\n";
#print "<hr />\n";
#print "Server port = [" . $ENV{'SERVER_PORT'} . "]<br>\n";
#print "Referer = [" . $ENV{'HTTP_REFERER'} . "]<br>\n";
#print "<hr />\n";
#foreach $e(%ENV)
#{
#    print $e . " = [" . $ENV{$e} . "]<br>\n";
#}
print "<hr />\n</body>\n</html>\n";
exit;
