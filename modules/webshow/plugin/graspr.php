<?php
// $Id: graspr.php 06/16/2009 19:59:00 tcnet Exp $ //
//** Graspr Plugin for WebShow Module
//** Author: TCNet (http://technicalcrew.net)
//** Disclaimer: It is the users responsibility to check the embed site's terms of service to determine if their media may be displayed on the users website.
//** listurl is the video id from the url or the embed code
//** pid is the graspr publisher id.
if (!defined('XOOPS_ROOT_PATH')){ exit(); }

// Enter The Embed Host's name
$hostname = "Graspr";

// Enter the Host's URL.  If possible use listurl to create a link to the media's page.
$hostlink = "http://www.graspr.com/?afid=203090164fb9e5062261f0713cfd1a9c";

// Enter instructions used in the submit form 
$embed_dsc = 'Enter the id from the EMBED url.<br />Example: dataID=http://www.graspr.com/html/flashplayer/data/data.php%3Fv=<span style="color: #FF0000;">1579779b98ce9edb98dd85606f2c119d</span>';

// Add listurl, width, height and colors to original embed code 
// Original: <object width='425' height='350'><param name='movie' value='http://www.graspr.com/html/flashplayer/swf/home_player.swf?dataID=http://www.graspr.com/html/flashplayer/data/data.php%3Fv=1579779b98ce9edb98dd85606f2c119d&pid=108&gh=www' /><param name='wmode' value='transparent' /><embed src='http://www.graspr.com/html/flashplayer/swf/home_player.swf?dataID=http://www.graspr.com/html/flashplayer/data/data.php%3Fv=1579779b98ce9edb98dd85606f2c119d&pid=108&gh=www&swid=10&lid=0' quality='high' bgcolor='#ffffff' width='425' height='350' name='myPlayer' swLiveConnect='true' align='left' allowFullScreen='true' allowScriptAccess='always' type='application/x-shockwave-flash' pluginspage='http://www.macromedia.com/go/getflashplayer'></embed></object><img src='http://www.graspr.com/pixeltracker50?vidID=1579779b98ce9edb98dd85606f2c119d' width='0' height='0' border='0'>
$movie='<object width="'.$width.'" height="'.$height.'"><param name="movie" value="http://www.graspr.com/html/flashplayer/swf/home_player.swf?dataID=http://www.graspr.com/html/flashplayer/data/data.php%3Fv='.$listurl.'&pid=25553&gh=www" /><param name="wmode" value="transparent" /><embed src="http://www.graspr.com/html/flashplayer/swf/home_player.swf?dataID=http://www.graspr.com/html/flashplayer/data/data.php%3Fv='.$listurl.'&pid=25553&gh=www" quality="high" bgcolor="#ffffff" width="'.$width.'" height="'.$height.'" name="myPlayer" swLiveConnect="true" align="left" allowFullScreen="true" allowScriptAccess="always" type="application/x-shockwave-flash" pluginspage="http://www.macromedia.com/go/getflashplayer"></embed></object>';

// Embed Logo
// If possible use listurl to create the hosts thumbnail url.  Else leave empty for manual entry.
$embed_logo = '';
?>