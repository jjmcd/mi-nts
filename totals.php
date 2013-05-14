<?php

    include('includes/session.inc');
 include('includes/miscFunctions.inc');

$db = mysql_connect($host , $dbuser, $dbpassword);
mysql_select_db($DatabaseName,$db);

$total=0;
$SQL1="SELECT DISTINCT `COUNTY` FROM `arpsc_ecrept` ORDER BY `COUNTY`";
$r1=getResult($SQL1,$db);
while ( $row1=getRow($r1,$db) )
{
	$SQL2="SELECT MAX(`PERIOD`) FROM `arpsc_ecrept` WHERE `COUNTY`='" .
	    $row1[0] . "'";
	$r2=getResult($SQL2,$db);
	$row2=getRow($r2,$db);
	$SQL3="SELECT `ARESMEM` FROM `arpsc_ecrept` WHERE `COUNTY`='" .
	    $row1[0] . "' AND `PERIOD`=" . $row2[0];
	$r3=getResult($SQL3,$db);
	$row3=getRow($r3,$db);
	echo $row1[0] . "\t" . $row3[0] . "\n";
	$total = $total + $row3[0];
}
echo "\t------\n";
echo "\t" . $total . "\n";
echo "\n";
?>

