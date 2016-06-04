<?php
// $Id: bliptv.php 05/01/2009 19:59:00 tcnet Exp $ //
//** bliptv Plugin for WebShow Module
//** Author: TCNet (http://technicalcrew.net)
//** Disclaimer: It is the users responsibility to check the embed site's terms of service to determine if their media may be displayed on the users website.
//** width, height, backcolor, frontcolor are called from the selected WebShow player.
//** listurl = user enters just the video id from the blip tv url

if (!defined('XOOPS_ROOT_PATH')){ exit(); }

// Enter The Embed Host's name
$hostname = "BlipTV";

// Enter the Host's URL
// original: http://blip.tv/play/AbbzI4yOeQ
$hostlink = "http://blip.tv";

// Enter instructions used in the submit form 
$embed_dsc = 'Enter the video id from the EMBED URL.  Example: embed src=http://blip.tv/play/<span style="color: #FF0000;">AbbzI4yOeQ</span>';

// Add listurl, width, height and colors to original embed code 
// Original: <embed src="http://blip.tv/play/AbbzI4yOeQ" type="application/x-shockwave-flash" width="480" height="300" allowscriptaccess="always" allowfullscreen="true"></embed> 
$movie='<embed src="http://blip.tv/play/'.$listurl.'" type="application/x-shockwave-flash" width="'.$width.'" height="'.$height.'" allowscriptaccess="always" allowfullscreen="true"></embed>';

// Embed Logo
$embed_logo = ''; //Not available.  Manual entry required 
?>