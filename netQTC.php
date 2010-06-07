<?php
//    ecrptigl.php
//    $Revision: 1.3 $ - $Date: 2007-11-30 12:33:07-05 $
//
//    Generate a graph of a single county's FSD-212 results
//

include('includes/session.inc');

include('includes/functions.inc');

// Open the database
$db = mysql_connect($host , $dbuser, $dbpassword);
mysql_select_db($DatabaseName,$db);

// Get the requested county
$netid = $_GET['netid'];

//===========================================================================
// D a t a b a s e   D a t a
//===========================================================================

$q2 = "SELECT MIN(`period`),MAX(`period`),MIN(`qtc`),MAX(`qtc`) "
  . "FROM `netreport` WHERE `netid`=" . $netid ;
$r2=getResult($q2,$db);
$limits = getRow( $r2, $db );

// Initialize the database query
$q3 = "SELECT `period`,`qtc` FROM `netreport` WHERE `netid`=" .
  $netid . " ORDER BY `period`";
$r3=getResult($q3,$db);

$i=0;  // Index into arrays to store results to graph
// Loop through all returned rows
while ( $row3 = getRow($r3,$db) )
  {
    if ( $row3[0] > 0 )
      {
	$i = $i + 1;
	$x[$i] = $row3[0];  // Period
	$y[$i] = $row3[1];    // Total hours
      }
  }

//===========================================================================
// G e n e r a t e   G r a p h
//===========================================================================

// Graph left, top, right, bottom positions
$gl = 30;
$gt = 5;
$gr = 330;
$gb = 195;

$n = $i;             // Number of points
$maxy = $limits[3];  // Initialize max Y value
$minx = $limits[0];  // Minimum X
$maxx = $limits[1];  // Maximum X

// Calculate the position on the graph for each data point
for ( $i=1; $i<=$n; $i++ )
{
  $yp[$i] = $gb - ($gb-$gt) * $y[$i] / $maxy;
  if ( $i>5 )
    {
      $val = ($y[$i-5]+$y[$i-4]+$y[$i-3]+$y[$i-2]+$y[$i-1]+$y[$i])/6;
      $yp1[$i] = $gb - ($gb-$gt) * $val / $maxy;
    }
  $yp[$i] = $gb - ($gb-$gt) * $y[$i] / $maxy;
  if ( $i>11 )
    {
      $val = ($y[$i-11]+$y[$i-10]+$y[$i-9]+$y[$i-8]+$y[$i-7]+$y[$i-6] +
	      $y[$i-5]+$y[$i-4]+$y[$i-3]+$y[$i-2]+$y[$i-1]+$y[$i])/12;
      $yp2[$i] = $gb - ($gb-$gt) * $val / $maxy;
    }
  $xp[$i] = $gl + ($gr-$gl) * ( $x[$i]-$minx ) / ( $maxx - $minx );
}

// Allocate the image to display
$image = ImageCreate( 380, 220 );
// Fill the canvas with a barely bluish gray
$background_color = ImageColorAllocate($image, 240, 245, 245);
// Set up a number of colors to be used
$paleblue = ImageColorAllocate($image, 240, 255, 255);
$barely = ImageColorAllocate($image, 220, 245, 245 );
$black = ImageColorAllocate( $image, 0, 0, 0);
$dark = ImageColorAllocate( $image, 160, 160, 160);
$ltgray = ImageColorAllocate( $image, 220, 220, 220);
$line = ImageColorAllocate( $image, 0, 0, 0);
$line1 = ImageColorAllocate( $image, 96, 96, 255);
$line2 = ImageColorAllocate( $image, 192, 192, 192);
//$line3 = ImageColorAllocate( $image, 0, 0, 224);
//$line4 = ImageColorAllocate( $image, 224, 224, 0);
// Fill the graph area with a pale blue
ImageFilledRectangle($image, $gl, $gt, $gr, $gb, $paleblue);

// Determine how many Y tic marks we want
$ts = 5;
if ( $maxy > 25 )
  $ts = 10;
if ( $maxy > 150 )
  $ts = 50;
if ( $maxy > 250 )
  $ts = 100;
if ( $maxy > 1000 )
  $ts = 200;
if ( $maxy > 2500 )
  $ts = 1000;

// Y-axis tic marks
for ( $y=0; $y<$maxy; $y+=$ts )
  {
    $yt = $gb - ($gb-$gt) * $y / $maxy;
    // Tic mark
    ImageLine( $image, $gl-5, $yt, $gl, $yt, $black );
    // Grid line
    ImageLine( $image, $gl, $yt, $gr, $yt, $barely );
    // Annotation
    ImageString($image, 1, 2, $yt-4, round($y), $black);
  }

// Array of month letters, starting to align with period numbers
$months = array( 'D','J','F','M','A','M','J','J','A','S','O','N' );



// X-axis tic marks
for ( $i=1; $i<=$n; $i++ )
  {
    // Tic mark
    ImageLine( $image, $xp[$i], $gb+5, $xp[$i], $gb, $black );
    // Grid line
    ImageLine( $image, $xp[$i], $gb, $xp[$i], $gt, $barely );
    // Annotation
    $mm = $x[$i] % 12;
    ImageString($image, 1, $xp[$i]-2, $gb+8, $months[$mm+1], $black);
  }

// Black box around the graph area
ImageLine( $image, $gl, $gt, $gr, $gt, $black);
ImageLine( $image, $gr, $gt, $gr, $gb, $black);
ImageLine( $image, $gr, $gb, $gl, $gb, $black);
ImageLine( $image, $gl, $gb, $gl, $gt, $black);
// Gray box around the canvas
ImageLine( $image, 0, 0, 379, 0, $dark);
ImageLine( $image, 379, 0, 379, 219, $dark);
ImageLine( $image, 379, 219, 0, 219, $dark);
ImageLine( $image, 0, 219, 0, 0, $dark);

// Draw the actual lines on the graph
for ( $i=1; $i<$n; $i++ )
{
    if ( $i>11 )
      {
	ImageLine( $image, $xp[$i], $yp2[$i]-1, $xp[$i+1], $yp2[$i+1]-1, $line2 );
	ImageLine( $image, $xp[$i], $yp2[$i], $xp[$i+1], $yp2[$i+1], $line2 );
	ImageLine( $image, $xp[$i], $yp2[$i]+1, $xp[$i+1], $yp2[$i+1]+1, $line2 );
      }
    if ( $i>5 )
      ImageLine( $image, $xp[$i], $yp1[$i], $xp[$i+1], $yp1[$i+1], $line1 );
    ImageLine( $image, $xp[$i], $yp[$i], $xp[$i+1], $yp[$i+1], $line );
}

// Display the Legend
ImageString($image, 3, $gr+5, 40,  'Month',  $line);
ImageString($image, 3, $gr+5, 60,  '6 mo', $line1);
ImageString($image, 3, $gr+5, 80,  '12 mo',     $line2);
ImageString($image, 1, 220, 210, 'netQTC.php $Rev 1.0 $', $ltgray );

// Finally, actually expose the image
header('Content-type: image/png');
ImagePNG($image);

?>
