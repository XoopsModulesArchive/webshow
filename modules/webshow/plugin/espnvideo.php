<?php
// $Id: espn.php 10/18/2008 19:59:00 tcnet Exp $ //
//** ESPN Video Plugin for WebShow Module
//** Author: TCNet (http://technicalcrew.net)
//** Disclaimer: It is the users responsibility to check the embed site's terms of service to determine if their media may be displayed on the users website.
//** width, height, backcolor, frontcolor are called from the selected WebShow player.
//** listurl = user enters just the video id from the ESPN video url
//** Original code: 

if (!defined('XOOPS_ROOT_PATH')){ exit(); }

// Enter The Embed Host's name
$hostname = "ESPN Video";

// Enter the Host's URL
// original: http://sports.espn.go.com/broadband/video/videopage?videoId=3647423
$hostlink = "http://sports.espn.go.com/broadband/video/videopage?videoId=".$listurl;

// Enter instructions used in the submit form 
$embed_dsc = 'Enter the video id from the url.<br />Example: http://sports.espn.go.com/broadband/video/videopage?videoId=<span style="color: #FF0000;">3647423</span>';

// Add listurl, width, height and colors to original embed code 
// Original: <object width="440" height="361"><param name="movie" value="http://sports.espn.go.com/broadband/player.swf?mediaId=3647423"/><param name="wmode" value="transparent"/><param name="allowScriptAccess" value="always"/><embed src="http://sports.espn.go.com/broadband/player.swf?mediaId=3647423" type="application/x-shockwave-flash" wmode="transparent" width="440" height="361" allowScriptAccess="always"></embed></object>
$movie='<object width="'.$width.'" height="'.$height.'"><param name="movie" value="http://sports.espn.go.com/broadband/player.swf?mediaId='.$listurl.'"/><param name="wmode" value="transparent"/><param name="allowScriptAccess" value="always"/><embed src="http://sports.espn.go.com/broadband/player.swf?mediaId='.$listurl.'" type="application/x-shockwave-flash" wmode="transparent" width="'.$width.'" height="'.$height.'" allowScriptAccess="always"></embed></object>';

// Embed Logo
$embed_logo = ''; 
?>