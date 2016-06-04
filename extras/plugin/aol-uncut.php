<?php
// $Id: aol-uncut.php 01/05/2008 19:59:00 tcnet Exp $ //
//** AOL Uncut Video Plug In for the WebSHow Module for Xoops
//** Author: TCNet (http://technicalcrew.net)
//** Disclaimer: It is the users responsibility to check the embed site's terms of service to determine if their media may be displayed on the users website.
//** width, height are called from the selected WebShow player.
//** listurl = user enters the video id from the url

if (!defined('XOOPS_ROOT_PATH')){ exit(); }

// Enter The Embed Host's name
define("_WS_HOSTNAME","AOL Uncut Video");

// Enter the Host's URL
// http://uncutvideo.aol.com/videos/80d646d2bf149c6d04aa5989fcc85d6d
define("_WS_HOSTLINK","http://uncutvideo.aol.com/videos/".$listurl);

// Enter instructions used in the submit form 
$embed_dsc = 'Enter the video id number from the AOL-uncut url.<br />Example: http://uncutvideo.aol.com/videos/<span style="color: #FF0000;">80d646d2bf149c6d04aa5989fcc85d6d</span>';

// Add listurl, width, height and colors to original embed code 
// Original: <object width="415" height="347"><param name="wmode" value="opaque" /><param name="movie" value="http://uncutvideo.aol.com/v6.334/en-US/uc_videoplayer.swf" /><param name="FlashVars" value="aID=180d646d2bf149c6d04aa5989fcc85d6d&site=http://uncutvideo.aol.com/"/><embed src="http://uncutvideo.aol.com/v6.334/en-US/uc_videoplayer.swf" wmode="opaque" FlashVars="aID=180d646d2bf149c6d04aa5989fcc85d6d&site=http://uncutvideo.aol.com/" width="415" height="347" type="application/x-shockwave-flash"></embed></object>
$movie='<object width="'.$width.'" height="'.$height.'"><param name="wmode" value="opaque" /><param name="movie" value="http://uncutvideo.aol.com/v6.334/en-US/uc_videoplayer.swf" /><param name="FlashVars" value="aID='.$listurl.'&amp;site=http://uncutvideo.aol.com/"/><embed src="http://uncutvideo.aol.com/v6.334/en-US/uc_videoplayer.swf" wmode="opaque" FlashVars="aID=1'.$listurl.'&amp;site=http://uncutvideo.aol.com/" width="'.$width.'" height="'.$height.'" type="application/x-shockwave-flash"></embed></object>';

// Embed Logo
$embed_logo = ''; //Manually enter thumbnail url
?>