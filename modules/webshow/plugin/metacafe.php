<?php
// $Id: metacafe.php 01/05/2008 19:59:00 tcnet Exp $ //
//** Metacafe Plug In
//** Author: TCNet (http://technicalcrew.net)
//** Disclaimer: It is the users responsibility to check the embed site's terms of service to determine if their media may be displayed on the users website.
//** listurl = user inputs directory#/directoryname

if (!defined('XOOPS_ROOT_PATH')){ exit(); }

// Enter The Embed Host's name 
$hostname ="Metacafe";

// Enter The Embed Host's URL
// Original: http://www.metacafe.com/watch/714487/amazing_crash_and_crazy_tractor/
$hostlink = "http://www.metacafe.com/watch/".$listurl;

// Enter instructions used in the submit form 
$embed_dsc = 'Enter the number/name from the metacafe url.<br />Example: http://www.metacafe.com/watch/<span style="color: #FF0000;">714487/amazing_crash_and_crazy_tractor</span>/';

// Add listurl, width, height and colors to original embed code 
// Original: <embed src="http://www.metacafe.com/fplayer/714487/amazing_crash_and_crazy_tractor.swf" width="460" height="395" wmode="transparent" pluginspage="http://www.macromedia.com/go/getflashplayer" type="application/x-shockwave-flash"> </embed>
$movie = '<embed src="http://www.metacafe.com/fplayer/'.$listurl.'.swf" width="'.$width.'" height="'.$height.'" wmode="transparent" pluginspage="http://www.macromedia.com/go/getflashplayer" type="application/x-shockwave-flash"></embed>';

// Embed Logo
$embed_logo = 'http://www.metacafe.com/thumb/'.substr($listurl, 0, strpos($listurl, '/')).'.jpg'; 
?>