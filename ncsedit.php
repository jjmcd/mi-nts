<?php
//    ncsedit.php
//    $Revision: 1.1 $ - $Date: 2010-02-21 19:28:25-05 $
//
//
{
  include('includes/session.inc');
  $title=_('Michigan Section NTS');

  include('includes/miscFunctions.inc');

  $db = openDatabase( $dppE );
  mysql_select_db($DatabaseName,$db);

  NTSheader($title,"NTS Michigan Section Net Controls");

  // Remember the launch time
  $starttime = strftime("%A, %B %d %Y, %H:%M");
  $maxdate = 0;

  leftBar( $db );
  echo "  <div id=\"main\">\n";

  // Initialize the latest data counter
  $maxdate=0;

  // Get the requested net
  $netid = $_GET['netid'];
  if ( $netid < 1 )
    {
      echo "<h1 style=\"color:red;\">ERROR - No Net Provided</h1>\n";
      $netid = 0;
    }
  else
    {


      $SQLA = "SELECT netfullname FROM nets WHERE netid=" . $netid;
      $netname = singleResult( $SQLA, $db );
 
      //echo "<p>&nbsp;</p>\n";
      echo "    <center>\n";
      echo "      <h1>Net Controls</h1>\n";
      echo "      <h3>" . $netname
	. "</h3>\n";

      echo "      <table cellpadding=\"5px\">\n";
      echo "        <tr>\n";
      echo "          <th>Day</th>\n";

      // Get data for net
      $SQL1="SELECT `dow`, `call`,`updated` FROM `net_controls` "
	. "WHERE `netid`=" . $netid . " ORDER BY `dow`";
      $res1 = getResult( $SQL1, $db );
      $otherid = $netid + 1000;
      $SQL2="SELECT COUNT(*) FROM `net_controls` "
	. "WHERE `netid`=" . $otherid;
      $more = singleResult( $SQL2, $db );
      // All nets except QMN
      if ( $more == 0 )
	{
	  echo "          <th>Net Control</th>\n";
	  echo "        </tr>\n";
	  while ( $row1 = getRow( $res1, $db ) )
	    {
	      // Change the coded day of week into text
	      $SQL3 = "SELECT `day_of_week` FROM `rep_dow` WHERE `id`="
		. $row1[0];
	      $daytext = singleResult( $SQL3, $db );
	      echo "        <tr>\n";
	      echo "          <th>" . $daytext . "</th>\n";
	    
	      if ( $row1[1] )
		{
		  echo "          <td align=\"center\" " .
		    "style=\"background:white;\">" . $row1[1] . "</td>\n";
		  if ( $maxdate < $row1[2] )
		    $maxdate = $row1[2];
		}
	      else
		echo "    <td>&nbsp;</td>\n";
	      echo "        </tr>\n";
	    }
	}
      else
	{
	  // QMN
	  echo "          <th>Early NCS</th>\n          <th>Late NCS</th>\n";
	  echo "        </tr>\n";
	  for ( $day=0; $day<7; $day ++ )
	    {
	      $SQL3 = "SELECT `day_of_week` FROM `rep_dow` WHERE `id`="
		. $day;
	      $daytext = singleResult( $SQL3, $db );
	      echo "        <tr>\n";
	      echo "          <th>" . $daytext . "</th>\n";
	      // QMN Early
	      $SQL4 = "SELECT `call`,`updated` FROM `net_controls` " .
		"WHERE `netid`=" . $netid . " AND `dow`=" . $day;
	      $res4 = getResult( $SQL4, $db );
	      if ( $row4 = getRow($res4,$db) )
		{
		  echo "          <td align=\"center\" " .
		    "style=\"background:white;\">" . $row4[0] . "</td>\n";
		  if ( $maxdate < $row4[1] )
		    $maxdate = $row4[1];
		}
	      else
		echo "    <td>&nbsp;</td>\n";
	      // QMN Late
	      $SQL5 = "SELECT `call`,`updated` FROM `net_controls` " .
		"WHERE `netid`=" . $otherid . " AND `dow`=" . $day;
	      $res5 = getResult( $SQL5, $db );
	      if ( $row5 = getRow($res5,$db) )
		{
		  echo "          <td align=\"center\" " .
		    "style=\"background:white;\">" . $row5[0] . "</td>\n";
		  if ( $maxdate < $row5[1] )
		    $maxdate = $row5[1];
		}
	      else
		echo "          <td>&nbsp;</td>\n";
	      echo "        </tr>\n";
	    }
	}
      echo "      </table>\n";
      echo "      <p>&nbsp;</p>\n";

      // The form for making changes
      echo "      <form name=\"enterncs\" method=\"post\" " .
	"action=\"ncsedit2.php?netid=" . $netid . "\">\n";

      echo "        Day: <select name=\"day\">\n";
      echo "          <option value=\"#\">Select Day</option>\n";
      $SQL6="SELECT id, day_of_week FROM rep_dow ORDER BY id";
      $res6 = getResult( $SQL6, $db );
      while ( $row6 = getRow( $res6, $db ) )
	{
	  echo "          <option value=\"" . $row6[0] . "\">" . 
	    $row6[1] . "</option>\n";
	}
      echo "        </select><br />\n";

      echo "        Call: <input name=\"call\"><br />\n";

      echo "        <input type=\"submit\" name=\"Submit\" value=\"Submit\">\n";

      echo "      </form>\n";
      echo "      <p>&nbsp;</p>\n";
      echo "      <p>Below is your website image:</p>\n";
      echo "      <img src=\"ncs_image.php?netid=" . $netid . "\" />\n";
      echo "    </center>\n";
    }

  echo "  </div>\n";

  sectLeaders($db);
  footer($starttime,$maxdate,
	 "\$Revision: 1.16 $ - \$Date: 2008-01-22 19:28:25-05 $");
}
?>
</div>
</body>
</html>
