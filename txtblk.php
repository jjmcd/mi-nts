<?php

//    pshri.php
//    $Revision: 1.5 $ - $Date: 2007/08/31 16:03:49 $
//
//    txtblk is used for web pages that are mostly prose.  txtblk
//    accepts a single, named argument, topic.  topic is then used
//    as a key to find paragraphs in the database which are displayed
//    in a single-celled table in the middle of the page.  A paragraph
//    with a sequence number of zero is taken as a title.  Otherwise,
//    paragraphs for the topic are displayed in order of sequence.
//
{
    include('includes/session.inc');
    $title=_('Michigan Section NTS');

    include('includes/functions.inc');

    $db = mysql_connect($host , $dbuser, $dbpassword);
    mysql_select_db($DatabaseName,$db);

    NTSheader($title,"NTS Michigan Section Traffic");

    leftBar( $db );

    // Remember the launch time
    $starttime = strftime("%A, %B %d %Y, %H:%M");

    echo '  <div id="main">' . "\n";

    // Initialize the last date counter
    $maxdate=0;
   
    // Get the requested topic
    $Topic=0;
    $Topic = $_GET['topic'];
    if ( $Topic == "" ) { $Topic="0"; }


     // Display the title for this topic, if it exists
    $SQL="SELECT content FROM `textblocks` WHERE `pageid`="
        . $Topic . " AND sequence=0";
    $result=getResult($SQL,$db);
    if ($myrow = getRow($result,$db) )
    {
      echo "    <center>\n";
	echo "      <p><h1> " . $myrow[0] . "</h1></p>\n";
      echo "    </center>\n";

	// Get the actual text for this topic
	    $SQL="SELECT sequence,content,updated" .
               " FROM `textblocks`" .
               " WHERE `pageid`='" . $Topic .
              "' ORDER BY sequence";
	$result=getResult($SQL,$db);

	// Loop through the data displaying each paragraph
	    $rownum=1;
	while ($myrow = getRow($result,$db) )
	{
	    if ( $myrow[0] > 0 )
		echo "    <p class=textbody>" . $myrow[1] . "</p>\n";
	    if ( $myrow[2] > $maxdate )
		$maxdate = $myrow[2];
	}
    }
    else
    {
        echo "<br>&nbsp;<br>&nbsp;<br>&nbsp;<br>&nbsp;<br>\n";
        echo "<center><h2>Sorry, the page you reqested does not exist</h2></center>\n";
        echo "<br>&nbsp;<br>&nbsp;<br>&nbsp;<br>&nbsp;<br>\n";
    }



    echo "</div>\n";
    sectLeaders($db);
    footer($starttime,$maxdate,"\$Revision: 1.5 $ - \$Date: 2007/08/31 16:03:49 $");
}
?>
</body>
</html>
