<?php
// $Id: yahoo-music-video.php 05/01/2009 19:59:00 tcnet Exp $ //
//** Yahoo Music Video Plugin for WebShow Module
//** Author: TCNet (http://technicalcrew.net)
//** Disclaimer: It is the users responsibility to check the embed site's terms of service to determine if their media may be displayed on the users website.
//** width, height, backcolor, frontcolor are called from the selected WebShow player.
//** listurl = user enters just the video id from the url

if (!defined('XOOPS_ROOT_PATH')){ exit(); }

// Enter The Embed Host's name
$hostname = "Yahoo Music Video";

// Enter the Host's URL
$hostlink = "http://new.music.yahoo.com/videos/";

// Enter instructions used in the submit form 
$embed_dsc = 'Enter the video id from the url.<br />Example: http://new.music.yahoo.com/videos/TaylorSwift/Love-Story--<span style="color: #FF0000;">201535082</span>';

// Add listurl, width, height and colors to original embed code 
// Original: <object width="400" height="255" id="uvp_fop" allowFullScreen="true"><param name="movie" value="http://d.yimg.com/m/up/fop/embedflv/swf/fop.swf"/><param name="flashVars" value="id=v201535082&amp;eID=1301797&amp;lang=us&amp;enableFullScreen=0&amp;shareEnable=1"/><param name="wmode" value="transparent"/><embed height="255" width="400" id="uvp_fop" allowFullScreen="true" src="http://d.yimg.com/m/up/fop/embedflv/swf/fop.swf" type="application/x-shockwave-flash" flashvars="id=v201535082&amp;eID=1301797&amp;lang=us&amp;ympsc=4195329&amp;enableFullScreen=1&amp;shareEnable=1" /></object>
$movie='<object width="'.$width.'" height="'.$height.'" id="uvp_fop" allowFullScreen="true"><param name="movie" value="http://d.yimg.com/m/up/fop/embedflv/swf/fop.swf"/><param name="flashVars" value="id=v'.$listurl.'&amp;lang=us&amp;enableFullScreen=0&amp;shareEnable=1"/><param name="wmode" value="transparent"/><embed height="'.$height.'" width="'.$width.'" id="uvp_fop" allowFullScreen="true" src="http://d.yimg.com/m/up/fop/embedflv/swf/fop.swf" type="application/x-shockwave-flash" flashvars="id=v'.$listurl.'&amp;lang=us&amp;enableFullScreen=1&amp;shareEnable=1" /></object>';

// Embed Logo
$embed_logo = 'http://d.yimg.com/ec/image/v1/video/'.$listurl.';encoding=jpg;size=150x94;locale=us;'; 
?>