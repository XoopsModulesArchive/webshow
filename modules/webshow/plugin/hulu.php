<?php
// $Id: hulu.php 05/01/2009 19:59:00 tcnet Exp $ //
//** Hulu Plugin for WebShow Module
//** Author: TCNet (http://technicalcrew.net)
//** Disclaimer: It is the users responsibility to check the embed site's terms of service to determine if their media may be displayed on the users website.
//** User enters the video id from http://www.hulu.com embed code

if (!defined('XOOPS_ROOT_PATH')){ exit(); }

// Enter The Embed Hosts name and URL
$hostname = "Hulu";
$hostlink = "http://www.hulu.com";

//** Embed entry instructions are used in submit form
$embed_dsc = 'Enter the video id from the EMBED code.<br />Example: embed src=http://www.hulu.com/embed/<span style="color: #FF0000;">H6jTWfRTqa3oScpeTeCUmw</span>';

// Add listurl, width, height and colors to original embed code
// Original: <object width="512" height="296"><param name="movie" value="http://www.hulu.com/embed/H6jTWfRTqa3oScpeTeCUmw"></param><param name="allowFullScreen" value="true"></param><embed src="http://www.hulu.com/embed/H6jTWfRTqa3oScpeTeCUmw" type="application/x-shockwave-flash" allowFullScreen="true"  width="512" height="296"></embed></object></body>
$movie='<object width="'.$width.'" height="'.$height.'"><param name="movie" value="http://www.hulu.com/embed/'.$listurl.'"></param><param name="allowFullScreen" value="true"></param><embed src="http://www.hulu.com/embed/'.$listurl.'" type="application/x-shockwave-flash" allowFullScreen="true"  width="'.$width.'" height="'.$height.'"></embed></object></body>';

// Embed Logo
// If possible use listurl to define image url
$embed_logo = ''; // Thumbnail url must be entered manually
?>