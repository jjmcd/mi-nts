#!/usr/bin/perl2 -w
#
#    Functions shared by all programs
#
#============================================================================
# Print the required content type
#============================================================================
sub PrintBanner
{
  print "Content-Type: text/html\n\n";
}

#============================================================================
# Connect to the database
#============================================================================
sub ConnectToDatabase
{
  $DSN = "DBI:mysql:database=mi-nts_org_-_website";

  my $dbt = DBI->connect($DSN, "wb8rcr", "wb8rcr" )
    or die "<P>Can't connect</P>\n";
  $dbt;
}

