<?php
// $Id: megavideo.php 05/01/2009 19:59:00 tcnet Exp $ //
//** Megavideo Plugin for WebShow Module
//** Author: TCNet (http://technicalcrew.net)
//** Disclaimer: It is the users responsibility to check the embed site's terms of service to determine if their media may be displayed on the users website.
//** listurl is the video id from either the url or the embed code

if (!defined('XOOPS_ROOT_PATH')){ exit(); }

// Enter The Embed Host's name and URL
$hostname = "MegaVideo";
$hostlink = "http://megavideo.com";

// Enter the embed instructions used in the submit form 
$embed_dsc = 'Enter the video id from the embed code.<br />Example: http://www.megavideo.com/v/<span style="color: #FF0000;">02CJSGES108a8d599f60425f9a80a582a8c034de</span>';

// Add listurl, width, height and colors to original embed code 
// Original: <object width="640" height="480"><param name="movie" value="http://www.megavideo.com/v/02CJSGES108a8d599f60425f9a80a582a8c034de"></param><param name="allowFullScreen" value="true"></param><embed src="http://www.megavideo.com/v/02CJSGES108a8d599f60425f9a80a582a8c034de" type="application/x-shockwave-flash" allowfullscreen="true" width="640" height="480"></embed></object>
$movie='<object width="'.$width.'" height="'.$height.'"><param name="movie" value="http://www.megavideo.com/v/'.$listurl.'"></param><param name="allowFullScreen" value="true"></param><embed src="http://www.megavideo.com/v/'.$listurl.'" type="application/x-shockwave-flash" allowfullscreen="true" width="'.$width.'" height="'.$height.'"></embed></object>';

// Embed Logo.
$embed_logo = ''; //Not available. Manually enter thumbnail url.
?>