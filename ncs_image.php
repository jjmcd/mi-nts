<?php
//    ncs_image.php
//    $Revision: 1.31 $ - $Date: 2010-02-21 09:08:07-05 $
//
//    Display net controls as image of table
//

include('includes/session.inc');

include('includes/functions.inc');

// Open the database
$db = mysql_connect($host , $dbuser, $dbpassword);
mysql_select_db($DatabaseName,$db);

// Get the requested net
$netid = $_GET['netid'];
if ( $netid < 1 )
  $netid = 1;

$c11=235;
$c12=245;
$c13=255;
if ( isset($_GET['c1']) )
  {
    $ca = $_GET['c1'];
    $c11 = substr($ca,0,3);
    $c12 = substr($ca,3,3);
    $c13 = substr($ca,6,3);
  }

$c21=220;
$c22=230;
$c23=255;
if ( isset($_GET['c2']) )
  {
    $ca = $_GET['c2'];
    $c21 = substr($ca,0,3);
    $c22 = substr($ca,3,3);
    $c23 = substr($ca,6,3);
  }

$c31=0;
$c32=0;
$c33=0;
if ( isset($_GET['c3']) )
  {
    $ca = $_GET['c3'];
    $c31 = substr($ca,0,3);
    $c32 = substr($ca,3,3);
    $c33 = substr($ca,6,3);
  }

$c41=0;
$c42=32;
$c43=128;
if ( isset($_GET['c4']) )
  {
    $ca = $_GET['c4'];
    $c41 = substr($ca,0,3);
    $c42 = substr($ca,3,3);
    $c43 = substr($ca,6,3);
  }

$iw = 260;
if ( $netid==1 )
  $iw = 340;
$ih = 210;
$tl = 10;
$tr = $iw - 10;
$tb = $ih - 10;
$tt = 10;

$image = ImageCreate( $iw, $ih );
$background_color = ImageColorAllocate( $image, $c11, $c12, $c13 );
$rowbackground = ImageColorAllocate( $image, $c21, $c22, $c23 );
$callcolor = ImageColorAllocate( $image, $c31, $c32, $c33 );
$headcolor = ImageColorAllocate( $image, $c41, $c42, $c43 );
$gray1 = ImageColorAllocate( $image, 64, 64, 64 );
$gray2 = ImageColorAllocate( $image, 128, 128, 128 );
$gray3 = ImageColorAllocate( $image, 192, 192, 192 );
$gray4 = ImageColorAllocate( $image, 226, 226, 226 );
$black = ImageColorAllocate( $image, 0,0,0 );

$fonthead = 5;
$fontbody = 4;

for ( $i=0; $i<7; $i+=2 )
  {
    $ypos =  43 + 22 * $i;
    ImageFilledRectangle($image, 5, $ypos+18, $iw-2, $ypos-4, $rowbackground );
  }

ImageString( $image, $fonthead,  35, 15, "Day", $headcolor);
if ( $netid == 1 )
  {
    ImageString( $image, $fonthead, 150, 15, "Early", $headcolor);
    ImageString( $image, $fonthead, 250, 15, "Late", $headcolor);
  }
else
  ImageString( $image, $fonthead, 130, 15, "Net Control", $headcolor);

$SQL1 = "SELECT `day_of_week` FROM `rep_dow` ORDER BY `id`";
$res1 = getResult( $SQL1, $db );
$ypos = 43;
while ( $row1 = getRow( $res1, $db ) )
  {
    ImageString( $image, $fonthead, 20, $ypos, $row1[0], $headcolor );
    $ypos += 22;
  }

$SQL2 = "SELECT `dow`,`call` FROM `net_controls` WHERE " .
  "`netid`=" . $netid . " ORDER BY `dow`";
$res2 = getResult( $SQL2, $db );
while ( $row2 = getRow( $res2, $db ) )
  {
    $ypos = 43 + 22 * $row2[0];
    ImageString( $image, $fontbody, 150, $ypos, $row2[1], $callcolor );
  }

if ( $netid == 1 )
  {
    $SQL3 = "SELECT `dow`,`call` FROM `net_controls` WHERE " .
      "`netid`=1001 ORDER BY `dow`";
    $res3 = getResult( $SQL3, $db );
    while ( $row3 = getRow( $res3, $db ) )
      {
	$ypos = 43 + 22 * $row3[0];
	ImageString( $image, $fontbody, 250, $ypos, $row3[1], $callcolor );
      }
  }

// Black box around the image
ImageLine( $image, 4, $ih-1, $iw-1, $ih-1, $gray4);
ImageLine( $image, 4, $ih-2, $iw-2, $ih-2, $gray4);

ImageLine( $image, 3, $ih-4, $iw-3, $ih-5, $gray3);
ImageLine( $image, 3, $ih-6, $iw-5, $ih-6, $gray3);

ImageLine( $image, 2, $ih-8, $iw-6, $ih-8, $gray2);
ImageLine( $image, 2, $ih-9, $iw-7, $ih-9, $gray2);

ImageLine( $image, 1, $ih-11, $iw-8, $ih-11, $gray1);
ImageLine( $image, 1, $ih-12, $iw-9, $ih-12, $gray1);

ImageLine( $image, $iw-4, 1, $iw-4, $ih-4, $gray1);
ImageLine( $image, $iw-5, 1, $iw-5, $ih-5, $gray1);

ImageLine( $image, $iw-5, 2, $iw-5, $ih-5, $gray2);
ImageLine( $image, $iw-6, 2, $iw-6, $ih-6, $gray2);

ImageLine( $image, $iw-6, 3, $iw-6, $ih-6, $gray3);
ImageLine( $image, $iw-7, 3, $iw-7, $ih-7, $gray3);

ImageLine( $image, $iw-7, 4, $iw-7, $ih-7, $gray4);
ImageLine( $image, $iw-8, 4, $iw-8, $ih-8, $gray4);

ImageLine( $image, 1,     1, $iw-12, 1, $black);
ImageLine( $image, $iw-12, 1, $iw-12, $ih-12, $black);
ImageLine( $image, $iw-12, $ih-12, 1, $ih-12, $black);
ImageLine( $image, 1,     $ih-12, 1, 1, $black);

// Finally, actually expose the image
header('Content-type: image/png');
ImagePNG($image);

?>