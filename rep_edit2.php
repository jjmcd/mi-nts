<?php
//   rep_edit2.php
//
//    Post data on eighth region reps to the database

include('includes/session.inc');
$title=_('Michigan Section ARPSC');
include('includes/miscFunctions.inc');

// Pick up the passed in data for net, day and call
// no matter how it got here
if (isset($_GET['call']))
  {
    $call = strtoupper($_GET['call']);
  }
else
  {
    if (isset($_POST['call']))
      $call = strtoupper($_POST['call']);
  }
if (isset($_GET['day']))
  {
    $day =$_GET['day'];
  }
else
  if (isset($_POST['day']))
    $day = $_POST['day'];
if (isset($_GET['net']))
  {
    $net =$_GET['net'];
  }
else
  if (isset($_POST['net']))
    $net = $_POST['net'];

    echo "<p>Net [" . $net . "]</p>\n";
echo "<p>Day [" . $day . "]</p>\n";
echo "<p>Call [" . $call . "]</p>\n";

if ( $day=="#" || $net=="#" )
  header("Location: rep_edit1.php");

$db = myInit( $aa0, $aa1, $aa2, $aa3);

if ( $call == "" )
  $SQL = "UPDATE `rep_liaisons` SET `call`=NULL" .
    " WHERE `net`=" . $net . " AND `day_of_week`=" . $day;
else
  $SQL = "UPDATE `rep_liaisons` SET `call`='" . $call .
    "' WHERE `net`=" . $net . " AND `day_of_week`=" . $day;

$res=getResult($SQL,$db);
if ( !$res )
  echo "<p><b>Error!</p></b>\n";
else
  header("Location: rep_edit1.php");


?>