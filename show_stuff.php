<?php
//    show_stuff.php
//    $Revision: 1.7 $ - $Date: 2008-01-22 10:53:54-05 $
//
//    index is the opening page of the mi-nts website.  It displays the
//    standard menu, and then only some text introducing the site.
//
    include('includes/session.inc');
    $title=_('Michigan Section NTS');

    include('includes/miscFunctions.inc');


    // Open the database
//$dppE = array( $host, $dbuser, $dbpassword );
    $db=openDatabase($dppE);
    mysql_select_db($DatabaseName,$db);

    NTSheader($title,"NTS Michigan Section Traffic");

    leftBar( $db );

    print "  <div id=\"main\">\n";
    print "dppE =" . $dppE . "<br>\n";
    print "     =" . base64_decode($dppE) . "<br>\n";

    $dppF = "wb8rcraaaaaaaaaa";
    print "New one:" . $dppF . "<br>\n";
    print "     =" . base64_encode($dppF) . "<br>\n";
print "Host:" . base64_encode("localhost") . "<br>\n";

print "<p><hr><br>\n";
print "host=" . $host . ".<br>\n";
print "dbuser=" . $dbuser . ".<br>\n";
print "dbpassword=" . $dbpassword . ".<br>\n";
print "<hr></p>\n";

    print "--- (" . base64_encode( "localhost" ) . ") ---<br>\n";
    print "--- (" . base64_encode( "wb8rcraaaaaaaaaa" ) . ") ---<br>\n";
    print "--- (" . base64_encode( "wb8rcr" ) . ") ---<br>\n";

    print "<p>===&gt;<b>" . base64_encode($DatabaseName) . "</b>&lt;===>/p>\n";

?>