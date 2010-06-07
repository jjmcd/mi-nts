<?php
//    rep_img_for_QMN.php
//    $Revision: 1.3 $ - $Date: 2007-11-30 12:33:07-05 $
//
//    Display Cycle 4 Reps as image of table
//

include('includes/session.inc');

include('includes/functions.inc');

// Open the database
$db = mysql_connect($host , $dbuser, $dbpassword);
mysql_select_db($DatabaseName,$db);

$iw = 380;
$ih = 220;
$tl = 10;
$tr = $iw - 10;
$tb = $ih - 10;
$tt = 10;

$image = ImageCreate( $iw, $ih );
$background_color = ImageColorAllocate( $image, 255, 255, 192 );
$rowbackground = ImageColorAllocate( $image, 255, 255, 226 );
$callcolor = ImageColorAllocate( $image, 0, 0, 0 );
$headcolor = ImageColorAllocate( $image, 127, 127, 0 );
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
    ImageFilledRectangle($image, 5, $ypos+18, $iw-8, $ypos-4, $rowbackground );
  }

ImageString( $image, $fonthead,  20, 15, "Day", $headcolor);
ImageString( $image, $fonthead, 130, 15, "Early", $headcolor);
ImageString( $image, $fonthead, 220, 15, "Late", $headcolor);
ImageString( $image, $fonthead, 300, 15, "NWS", $headcolor);

$SQL1 = "SELECT `day_of_week` FROM `rep_dow` ORDER BY `id`";
$res1 = getResult( $SQL1, $db );
$ypos = 43;
while ( $row1 = getRow( $res1, $db ) )
  {
    ImageString( $image, $fonthead, 20, $ypos, $row1[0], $headcolor );
    $ypos += 22;
  }

$SQL2 = "SELECT `day_of_week`,`call` FROM `rep_liaisons` WHERE " .
  "`net`=3 ORDER BY `day_of_week`";
$res2 = getResult( $SQL2, $db );
while ( $row2 = getRow( $res2, $db ) )
  {
    $ypos = 43 + 22 * $row2[0];
    ImageString( $image, $fontbody, 130, $ypos, $row2[1], $callcolor );
  }

$SQL3 = "SELECT `day_of_week`,`call` FROM `rep_liaisons` WHERE " .
  "`net`=4 ORDER BY `day_of_week`";
$res3 = getResult( $SQL3, $db );
while ( $row3 = getRow( $res3, $db ) )
  {
    $ypos = 43 + 22 * $row3[0];
    ImageString( $image, $fontbody, 220, $ypos, $row3[1], $callcolor );
  }

$SQL4 = "SELECT `day_of_week`,`call` FROM `rep_nws` ORDER BY `day_of_week`";
$res4 = getResult( $SQL4, $db );
while ( $row4 = getRow( $res4, $db ) )
  {
    $ypos = 43 + 22 * $row4[0];
    ImageString( $image, $fontbody, 300, $ypos, $row4[1], $callcolor );
  }


// Black box around the image
ImageLine( $image, 4, $ih-1, $iw-1, $ih-1, $gray4);
ImageLine( $image, 3, $ih-2, $iw-1, $ih-2, $gray3);
ImageLine( $image, 2, $ih-3, $iw-1, $ih-3, $gray2);
ImageLine( $image, 1, $ih-4, $iw-1, $ih-4, $gray1);
ImageLine( $image, $iw-4, 1, $iw-4, $ih-4, $gray1);
ImageLine( $image, $iw-3, 2, $iw-3, $ih-3, $gray2);
ImageLine( $image, $iw-2, 3, $iw-2, $ih-2, $gray3);
ImageLine( $image, $iw-1, 4, $iw-1, $ih-1, $gray4);
ImageLine( $image, 1,     1, $iw-5, 1, $black);
ImageLine( $image, $iw-5, 1, $iw-5, $ih-5, $black);
ImageLine( $image, $iw-5, $ih-5, 1, $ih-5, $black);
ImageLine( $image, 1,     $ih-5, 1, 1, $black);


//ImageLine( $image, $gr, $gt, $gr, $gb, $black);
//ImageLine( $image, $gr, $gb, $gl, $gb, $black);
//ImageLine( $image, $gl, $gb, $gl, $gt, $black);


// Finally, actually expose the image
header('Content-type: image/png');
ImagePNG($image);

?>