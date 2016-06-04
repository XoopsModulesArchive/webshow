<?php
// $Id: youtube.php 06/16/2009 19:59:00 tcnet Exp $ //
//** Break.com Plugin for WebShow Module
//** Author: TCNet (http://technicalcrew.net)
//** Disclaimer: It is the users responsibility to check the embed site's terms of service to determine if their media may be displayed on the users website.
//** listurl is the video id from the url or the embed code

if (!defined('XOOPS_ROOT_PATH')){ exit(); }

// Enter The Embed Host's name
$hostname = "Break";

// Enter the Host's URL.  If possible use listurl to create a link to the media's page.
$hostlink = "http://embed.break.com/".$listurl;

// Enter instructions used in the submit form 
$embed_dsc = 'Enter the id from the EMBED code.<br />Example: value="http://embed.break.com/"<span style="color: #FF0000;">NzUzMTMy</span>';

// Add listurl, width, height and colors to original embed code 
// Original: <object width="464" height="384"><param name="movie" value="http://embed.break.com/NzUzMTMy"></param><param name="allowScriptAccess" value="always"></param><embed src="http://embed.break.com/NzUzMTMy" type="application/x-shockwave-flash" allowScriptAccess=always width="464" height="384"></embed></object><br><font size=1><a href="http://www.break.com/index/wolf-pup-learns-the-call-of-the-wild.html">Wolf Pup Learns the Call of the Wild</a> - Watch more <a href="http://www.break.com/">Funny Videos</a></font>
$movie='<object width="'.$width.'" height="'.$height.'"><param name="movie" value="http://embed.break.com/'.$listurl.'"></param><param name="allowScriptAccess" value="always"></param><embed src="http://embed.break.com/'.$listurl.'" type="application/x-shockwave-flash" allowScriptAccess=always width="'.$width.'" height="'.$height.'"></embed></object>';

// Embed Logo
// If possible use listurl to create the hosts thumbnail url.  Else leave empty.
$embed_logo = '';
?>