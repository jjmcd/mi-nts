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
    $db = myInit( $aa0, $aa1, $aa2, $aa3);

    NTSheader($title,"NTS Michigan Section Traffic");

    leftBar( $db );
?>
  <div id="main">
    <h1>Welcome</h1>
    <p>
      This is the site of the Michigan Section of the National Traffic 
      System. Currently, net info, net reports, SAR and PSHR data are 
      available since 2005. Site navigation has been moved to the 
      left to keep the format consistent with other Michigan Section sites.
    </p>
    <p>
      The site also contains some reference material for NTS participants.
      In the future it will include contact information for the various nets.
    </p>
    <p>
      If you have thoughts as to other material that should be represented
      here, or any corrections to this information, please contact WB8RCR,
      either by radiogram, by packet at wb8rcr@wb8rcr.ampr.org, or by the
      email address listed at the right or on QRZ.
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
      "\$Revision: 1.7 $ - \$Date: 2008-01-22 10:53:54-05 $");
?>
</div>
</body>
</html>
