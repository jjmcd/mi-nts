<?php
//   ncsedit2.php
//
//    Post data on net controls to the database

include('includes/session.inc');
$title=_('Michigan Section ARPSC');
include('includes/miscFunctions.inc');

// Pick up the passed in data for net, day and call
// no matter how it got here
if (isset($_GET['call']))
  {
    if ( $_GET['call'] < 'A' )
      $call = $_GET['call'];
    else
      $call = strtoupper($_GET['call']);
  }
else
  {
    if (isset($_POST['call']))
      if ( $_POST['call'] < 'A' )
	$call = $_POST['call'];
      else
	$call = strtoupper($_POST['call']);
  }
if (isset($_GET['day']))
  {
    $day =$_GET['day'];
  }
else
  if (isset($_POST['day']))
    $day = $_POST['day'];

if (isset($_GET['netid']))
  {
    $netid =$_GET['netid'];
  }
else
  if (isset($_POST['netid']))
    $netid = $_POST['netid'];

echo "<p>Day [" . $day . "]</p>\n";
echo "<p>Call [" . $call . "]</p>\n";
echo "<p>Net [" . $netid . "]</p>\n";

if ( $day=="#" )
  header("Location: ncsedit.php?netid=". $netid);

$db = myInit( $aa0, $aa1, $aa2, $aa3);

$SQL0 = "SELECT COUNT(*) FROM `net_controls` WHERE `netid`=" .
  $netid . " AND `dow`=" . $day;
if ( singleResult($SQL0, $db) == 0 )
  {
    $SQL = "INSERT INTO `net_controls` VALUES(" . $netid . "," .
      $day . ",'" . $call . "',NOW())";
    $res = getResult($SQL,$db);
  }
else
  {
    if ( $call == "" )
      $SQL = "DELETE FROM `net_controls` " .
	" WHERE `netid`=" . $netid . " AND `dow`=" . $day;
    else
      $SQL = "UPDATE `net_controls` SET `call`='" . $call .
	"' WHERE `netid`=" . $netid . " AND `dow`=" . $day;
    $res=getResult($SQL,$db);
  }
if ( !$res )
  echo "<p><b>Error!</p></b>\n";
else
  header("Location: ncsedit.php?netid=" . $netid);


?>