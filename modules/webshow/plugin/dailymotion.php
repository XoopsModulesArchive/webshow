<?php
// $Id: dailymotion.php 01/05/2008 19:59:00 tcnet Exp $ //
//** Daily Motion Plugin for WebShow Module
//** Author: TCNet (http://technicalcrew.net)
//** Disclaimer: It is the users responsibility to check the embed site's terms of service to determine if their media may be displayed on the users website.
//** width, height are called from the selected WebShow player.
//** listurl = user enters the video id from the url

if (!defined('XOOPS_ROOT_PATH')){ exit(); }

// Enter The Embed Host's name
$hostname = "Daily Motion";

// Enter the Host's URL
//** original: http://www.dailymotion.com/cluster/tech/video/x3y6yk_no-more-keyboardsmicrosoft_tech
$hostlink = "http://www.dailymotion.com/video/".$listurl;

// Enter instructions used in the submit form 
$embed_dsc = 'Enter just the video id from the url.<br />Example: http://www.dailymotion.com/cluster/tech/video/<span style="color: #FF0000;">x3y6yk</span>/_no-more-keyboardsmicrosoft_tech';

// Add listurl, width, height and colors to original embed code 
// Original: <div><object width="420" height="253"><param name="movie" value="http://www.dailymotion.com/swf/x3y6yk"></param><param name="allowFullScreen" value="true"></param><param name="allowScriptAccess" value="always"></param><embed src="http://www.dailymotion.com/swf/x3y6yk" type="application/x-shockwave-flash" width="420" height="253" allowFullScreen="true" allowScriptAccess="always"></embed></object><br /><b><a href="http://www.dailymotion.com/video/x3y6yk_no-more-keyboardsmicrosoft_tech">No more keyboards(Microsoft)</a></b><br /><i>Uploaded by <a href="http://www.dailymotion.com/jeansergio">jeansergio</a></i></div>
$movie='<object width="'.$width.'" height="'.$height.'"><param name="movie" value="http://www.dailymotion.com/swf/'.$listurl.'"></param><param name="allowFullScreen" value="true"></param><param name="allowScriptAccess" value="always"></param><embed src="http://www.dailymotion.com/swf/'.$listurl.'" type="application/x-shockwave-flash" width="'.$width.'" height="'.$height.'" allowFullScreen="true" allowScriptAccess="always"></embed></object>';

// Embed Logo
$embed_logo = '';
?>