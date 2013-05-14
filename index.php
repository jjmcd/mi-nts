<?php
//    index.php
//    $Revision: 1.7 $ - $Date: 2008-01-22 10:53:54-05 $
//
//    index is the opening page of the mi-nts website.  It displays the
//    standard menu, and then only some text introducing the site.
//
    include('includes/session.inc');
    $title=_('Michigan Section NTS');

    include('includes/miscFunctions.inc');

    // Remember the launch time
    $starttime = strftime("%A, %B %d %Y, %H:%M");

    // Initialize environment
//    $db = myInit( $aa0, $aa1, $aa2, $aa3);
    $db = openDatabase( $dppE );
    mysql_select_db($DatabaseName,$db);

    NTSheader($title,"NTS Michigan Section Traffic");

    leftBar( $db );
?>
  <div id="main">
    <h1>Welcome</h1>
    <p>
      <b>Congratulations to Michigan traffic handlers for ever increasing
      traffic totals:</b>
      <center>
      <table>
      <tr id=OsRow3><td>2012</td><td>46,268</td></tr>
      <tr id=OsRow4><td>2011</td><td>42,157</td></tr>
      <tr id=OsRow3><td>2010</td><td>35,423</td></tr>
      <tr id=OsRow4><td>2009</td><td>28,238</td></tr>
      </table>
      </center>
    </p>
      <p>&nbsp;</p>
    <p>
      This is the site of the Michigan Section of the National Traffic 
      System. Currently, net info, net reports, SAR and PSHR data are 
      available since 2005.
    </p>
    <p>
      The site also contains some reference material for NTS participants.
    </p>

    <p>
      If you have thoughts as to other material that should be represented
      here, or any corrections to this information, please contact WB8RCR,
      either by radiogram, by packet at wb8rcr@hamgate.midland.ampr.org, or 
      by the email address listed at the right or on QRZ.
    </p>

    <div class="submitted">
      <p class="author">
        73 de WB8RCR
      </p>
    </div>
  </div>
<?php
    sectLeaders($db);
    footer($starttime,$maxdate,
      "\$Revision: 1.9 $ - \$Date: 2013-01-15 09:33:20-04 $");
?>
</div>
</body>
</html>
