<?php
{

	if ( $keywords == "" )
	    $keywords="NTS Michigan 'National Traffic System' 'Michigan Section' MI";
	echo '<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">' . "\n";
	
	
	echo '<HTML><HEAD><TITLE>' . $title . '</TITLE>' . "\n";
	echo '<link REL="shortcut icon" HREF="favicon.ico">' . "\n";
	echo '<link REL="icon" HREF="favicon.ico">' . "\n";
	echo '<meta http-equiv="Content-Type" content="text/html; charset=' . _('ISO-8859-1') . '">' . "\n";
	echo '<meta name="KEYWORDS" content="' . $keywords . '">' . "\n";
	echo '<LINK HREF="css/'. $_SESSION['Theme'] .'/default.css" REL="stylesheet" TYPE="text/css">' . "\n";
	echo '</HEAD>' . "\n";

	echo '<BODY>' . "\n";
	echo '<H1>Michigan Section NTS</H1>' . "\n";
      echo "<P></P>\n";
      makeMenu($db);
}
?>