<?php
// $Id: graspr.php 06/16/2009 19:59:00 tcnet Exp $ //
//** Graspr Plugin for WebShow Module
//** Author: TCNet (http://technicalcrew.net)
//** Disclaimer: It is the users responsibility to check the embed site's terms of service to determine if their media may be displayed on the users website.
//** listurl is the video id from the url or the embed code
//** pid is the graspr publisher id.
if (!defined('XOOPS_ROOT_PATH')){ exit(); }

// Enter The Embed Host's name
$hostname = "Graspr Collection";

// Enter the Host's URL.  If possible use listurl to create a link to the media's page.
$hostlink = "http://www.graspr.com/?afid=203090164fb9e5062261f0713cfd1a9c";

// Enter instructions used in the submit form 
$embed_dsc = 'Enter the collection id from the EMBED url.<br />Example: playerEmbedCollections.swf?collectionID=<span style="color: #FF0000;">894</span>';

// Add listurl, width, height and colors to original embed code 
// Original: <object classid='clsid:d27cdb6e-ae6d-11cf-96b8-444553540000' codebase='http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=9,0,0,0' width='640' height='400' id='playerEmbedCollections' align='middle'><param name='allowScriptAccess' value='always' /><param name='allowFullScreen' value='true' /><param name='wmode' value='transparent'><param name='movie' value='http://www.graspr.com/html/flashplayer/swf/playerEmbedCollections.swf?collectionID=894&pid=25553&gh=www&swid=20&lid=0' /><param name='quality' value='high' /><param name='bgcolor' value='#000000' /><embed src='http://www.graspr.com/html/flashplayer/swf/playerEmbedCollections.swf?collectionID=894&pid=25553&gh=www' quality='high' bgcolor='#000000' width='640' height='400' name='playerEmbedCollections' align='middle' allowScriptAccess='always' allowFullScreen='true' wmode='transparent' type='application/x-shockwave-flash' pluginspage='http://www.macromedia.com/go/getflashplayer' /></embed></object><img src='http://www.graspr.com/pixeltracker20?collID=894' width='1' height='1' border='0'>
$movie='<object classid="clsid:d27cdb6e-ae6d-11cf-96b8-444553540000" codebase="http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=9,0,0,0" width="'.$width.'" height="'.$height.'" id="playerEmbedCollections" align="middle"><param name="allowScriptAccess" value="always" /><param name="allowFullScreen" value="true" /><param name="wmode" value="transparent"><param name="movie" value="http://www.graspr.com/html/flashplayer/swf/playerEmbedCollections.swf?collectionID='.$listurl.'&pid=25553&gh=www&swid=20&lid=0" /><param name="quality" value="high" /><param name="bgcolor" value="#000000" /><embed src="http://www.graspr.com/html/flashplayer/swf/playerEmbedCollections.swf?collectionID='.$listurl.'&pid=25553&gh=www" quality="high" bgcolor="#000000" width="'.$width.'" height="'.$height.'" name="playerEmbedCollections" align="middle" allowScriptAccess="always" allowFullScreen="true" wmode="transparent" type="application/x-shockwave-flash" pluginspage="http://www.macromedia.com/go/getflashplayer" /></embed></object>';

// Embed Logo
// If possible use listurl to create the hosts thumbnail url.  Else leave empty for manual entry.
$embed_logo = '';
?>