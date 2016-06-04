<?php
// $Id: yahoo-video.php 05/01/2009 19:59:00 tcnet Exp $ //
//** Yahoo Video Plugin for WebShow Module
//** Author: TCNet (http://technicalcrew.net)
//** Disclaimer: It is the users responsibility to check the embed site's terms of service to determine if their media may be displayed on the users website.
//** width, height, backcolor, frontcolor are called from the selected WebShow player.
//** listurl = user enters the vidid/id from the http://video.yahoo.com/ url

if (!defined('XOOPS_ROOT_PATH')){ exit(); }

// Enter The Embed Host's name]
$hostname = "Yahoo Video";

// Enter the Host's URL
// Original: http://video.yahoo.com/watch/4972821/13237259
$hostlink = "http://video.yahoo.com/watch/".$listurl;

// Enter instructions used in the submit form 
$embed_dsc = 'Enter the video id from the url.<br />Example: http://video.yahoo.com/watch/<span style="color: #FF0000;">4972821/13237259</span>';

// Add listurl, width, height and colors to original embed code 
// Original: <div><object width="512" height="322"><param name="movie" value="http://d.yimg.com/static.video.yahoo.com/yep/YV_YEP.swf?ver=2.2.40" /><param name="allowFullScreen" value="true" /><param name="AllowScriptAccess" VALUE="always" /><param name="bgcolor" value="#000000" /><param name="flashVars" value="id=13237259&vid=4972821&lang=en-us&intl=us&thumbUrl=http%3A//l.yimg.com/a/i/us/sch/cn/v/v14/w247/4972821_640_480.jpeg&embed=1" /><embed src="http://d.yimg.com/static.video.yahoo.com/yep/YV_YEP.swf?ver=2.2.40" type="application/x-shockwave-flash" width="512" height="322" allowFullScreen="true" AllowScriptAccess="always" bgcolor="#000000" flashVars="id=13237259&vid=4972821&lang=en-us&intl=us&thumbUrl=http%3A//l.yimg.com/a/i/us/sch/cn/v/v14/w247/4972821_640_480.jpeg&embed=1" ></embed></object><br /><a href="http://video.yahoo.com/watch/4972821/13237259">Robot Soccer Match</a> @ <a href="http://video.yahoo.com" >Yahoo! Video</a></div>
$yahooid = explode("/", $listurl);
$movie='<object width="'.$width.'" height="'.$height.'"><param name="movie" value="http://d.yimg.com/static.video.yahoo.com/yep/YV_YEP.swf?ver=2.2.40" /><param name="allowFullScreen" value="true" /><param name="AllowScriptAccess" VALUE="always" /><param name="bgcolor" value="#000000" /><param name="flashVars" value="id='.$yahooid[1].'&vid='.$yahooid[0].'&lang=en-us&intl=us" /><embed src="http://d.yimg.com/static.video.yahoo.com/yep/YV_YEP.swf?ver=2.2.40" type="application/x-shockwave-flash" width="'.$width.'" height="'.$height.'" allowFullScreen="true" AllowScriptAccess="always" bgcolor="#000000" flashVars="id='.$yahooid[1].'&vid='.$yahooid[0].'&lang=en-us&intl=us" ></embed></object>';

// Embed Logo
$embed_logo = ''; //Manually enter thumbnail URL
?>