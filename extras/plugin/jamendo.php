<?php
// $Id: jamendo.php 07/03/2008 19:59:00 tcnet Exp $ //
//** Jamendo Plugin for WebShow Module
//** Author: TCNet (http://technicalcrew.net)
//** Disclaimer: It is the users responsibility to check the embed site's terms of service to determine if their media may be displayed on the users website.
//** width, height, backcolor, frontcolor are called from the selected WebShow player.
//** listurl = user enters just the album id from the Jamendo url

if (!defined('XOOPS_ROOT_PATH')){ exit(); }

// Enter The Embed Host's name
$hostname = "Jamendo";

// Enter the Host's URL
// original: http://www.jamendo.com/en/album/24399
$hostlink = "http://www.jamendo.com/en/album/".$listurl;

// Enter instructions used in the submit form
$embed_dsc = 'Enter the album id from the url.<br />Example: http://www.jamendo.com/en/album/<span style="color: #FF0000;">24399</span>';

// Add listurl, width, height and colors to original embed code 
// Original: <div align="center"><object width="200" height="300" classid="clsid:d27cdb6e-ae6d-11cf-96b8-444553540000" codebase="http://fpdownload.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=7,0,0,0" align="middle"> <param name="allowScriptAccess" value="always" /><param name="wmode" value="transparent" /> <param name="movie" value="http://widgets.jamendo.com/en/album/?album_id=24399&playertype=2008" /> <param name="quality" value="high" /> <param name="bgcolor" value="#FFFFFF" /> <embed src="http://widgets.jamendo.com/en/album/?album_id=24399&playertype=2008" base="http://widgets.jamendo.com/en/album/" quality="high" wmode="transparent" bgcolor="#FFFFFF" width="200" height="300" align="middle" allowScriptAccess="always" type="application/x-shockwave-flash" pluginspage="http://www.macromedia.com/go/getflashplayer">&nbsp;</embed>&nbsp;</object></div>
$movie='<object width="'.$width.'" height="'.$height.'" classid="clsid:d27cdb6e-ae6d-11cf-96b8-444553540000" codebase="http://fpdownload.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=7,0,0,0" align="middle"> <param name="allowScriptAccess" value="always" /><param name="wmode" value="transparent" /> <param name="movie" value="http://widgets.jamendo.com/en/album/?album_id='.$listurl.'&amp;playertype=2008" /> <param name="quality" value="high" /> <param name="bgcolor" value="'.$backcolor.'" /> <embed src="http://widgets.jamendo.com/en/album/?album_id='.$listurl.'&amp;playertype=2008" base="http://widgets.jamendo.com/en/album/" quality="high" wmode="transparent" bgcolor="'.$backcolor.'" width="'.$width.'" height="'.$height.'" align="middle" allowScriptAccess="always" type="application/x-shockwave-flash" pluginspage="http://www.macromedia.com/go/getflashplayer">&amp;nbsp;</embed>&amp;nbsp;</object>';

// Embed Logo
$embed_logo = 'http://img.jamendo.com/albums/'.$listurl.'/covers/1.130.jpg'; 
?>