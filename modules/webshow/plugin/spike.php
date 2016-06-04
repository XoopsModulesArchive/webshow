<?php
// $Id: spike.php 10/20/2008 19:59:00 tcnet Exp $ //
//** Spike.com Plugin for WebShow Module
//** Author: TCNet (http://technicalcrew.net)
//** Disclaimer: It is the users responsibility to check the embed site's terms of service to determine if their media may be displayed on the users website.
//** width, height, backcolor, frontcolor are called from the selected WebShow player.
//** listurl = user enters just the video id from the url
//** 

if (!defined('XOOPS_ROOT_PATH')){ exit(); }

// Enter The Embed Host's name and URL
$hostname = "Spike";

// Enter The Embed Host's URL
// Original URL: http://www.spike.com/video/30-second-hot-chick/2881459
$hostlink = "http://www.spike.com/video/".$listurl;

// Enter instructions used in the submit form 
$embed_dsc = 'Enter the video id from the url.<br />Example: http://www.spike.com/video/30-second-hot-chick/<span style="color: #FF0000;">2881459</span>';

// Add listurl, width, height and colors to original embed code 
// Original Embed: <embed width="448" height="365" src="http://www.spike.com/efp" quality="high" bgcolor="000000" name="efp" align="middle" type="application/x-shockwave-flash" pluginspage="http://www.macromedia.com/go/getflashplayer" flashvars="flvbaseclip=2881459&" allowfullscreen="true"> </embed> <div style="font-family: arial,helvetica,sans-serif;font-size:12px; background-color: #000; width: 448px; padding: 3px 0; color: #fff;"><a href="http://www.spike.com/video/30-second-hot-chick/2881459" style="color: #ffcc35; margin-left: 5px;">30 Second Hot Chick - French Maid</a> | <a href="http://www.spike.com/channel/girls" style="color: #ffcc35">Girls</a> | <a href="http://www.spike.com/" style="color: #ffcc35">SPIKE.com</a></div>
$movie='<embed width="'.$width.'" height="'.$height.'" src="http://www.spike.com/efp" quality="high" bgcolor="'.$backcolor.'" name="efp" align="middle" type="application/x-shockwave-flash" pluginspage="http://www.macromedia.com/go/getflashplayer" flashvars="flvbaseclip='.$listurl.'&amp;" allowfullscreen="true"> </embed>';

// Embed Logo
$embed_logo = 'http://dyn.ifilm.com/resize/image/stills/films/resize/istd/'.$listurl.'.jpg?width=150'; 
?>