<?php
// $Id: you-video-sina.php 10/12/2008 19:59:00 tcnet Exp $ //
// you-video-sina Plugin for WebShow Module for XOOPS
// Author: TCNet
// Disclaimer: It is the users responsibility to check the embed site's terms of service to determine if their media may be displayed on the users website.
// width, height, backcolor, frontcolor are called from the selected WebShow player.
// listurl = user enters the vid-uid from the you.video.sina.com.cn url

if (!defined('XOOPS_ROOT_PATH')){ exit(); }

// Enter The Embed Host's name
$hostname = "You Video Sina";

// Enter the Host's URL
// original: http://you.video.sina.com.cn/b/16186132-1280895811.html
$hostlink = "http://you.video.sina.com.cn/b/".$listurl.".html";

// Enter instructions used in the submit form
$embed_dsc = 'Enter the video id from the url.<br />Example: http://you.video.sina.com.cn/b/<span style="color: #FF0000;">16186132-1280895811</span>.html';

// Add listurl, width, height and colors to original embed code 
// Original: <div><object id="ssss" width="480" height="370" ><param name="allowScriptAccess" value="always" /><embed pluginspage="http://www.macromedia.com/go/getflashplayer" src="http://vhead.blog.sina.com.cn/player/outer_player.swf?auto=1&vid=16186132&uid=1280895811" type="application/x-shockwave-flash" name="ssss" allowFullScreen="true" allowScriptAccess="always" width="480" height="370"></embed></object></div>

$sina = explode("-", $listurl);
$movie='<object id="ssss" width="'.$width.'" height="'.$height.'" ><param name="allowScriptAccess" value="always" /><embed pluginspage="http://www.macromedia.com/go/getflashplayer" src="http://vhead.blog.sina.com.cn/player/outer_player.swf?auto=1&amp;vid='.$sina[0].'" type="application/x-shockwave-flash" name="ssss" allowFullScreen="true" allowScriptAccess="always" width="'.$width.'" height="'.$height.'"></embed></object>';

// Embed Logo
$embed_logo = ''; 
?>