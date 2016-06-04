<?php
// $Id: lastfm-video.php 07/05/2008 19:59:00 tcnet Exp $ //
//** last.FM Video Plugin for WebShow Module
//** Author: TCNet (http://technicalcrew.net)
//** Disclaimer: It is the users responsibility to check the embed site's terms of service to determine if their media may be displayed on the users website.
//** width, height, backcolor, frontcolor are called from the selected WebShow player.
//** listurl = user enters just the video id from the url

if (!defined('XOOPS_ROOT_PATH')){ exit(); }

// Enter The Embed Host's name
$hostname = "last.FM Video";

// Enter the Host's URL
$hostlink = "http://www.last.fm/videos";

// Enter instructions used in the submit form 
$embed_dsc = 'Enter the video id from the url.<br />Example: http://www.last.fm/music/Sigur+R%C3%B3s/+videos/<span style="color: #FF0000;">3103985</span>';

// Add listurl, width, height and colors to original embed code 
// Original: <object width="340" height="289" id="player" classid="clsid:d27cdb6e-ae6d-11cf-96b8-444553540000" codebase="http://fpdownload.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=9,0,0,0" align="middle"> <param name="movie" value="http://cdn.last.fm/videoplayer/33/VideoPlayer.swf" /> <param name="menu" value="false" /> <param name="quality" value="high" /> <param name="bgcolor" value="#000000" /> <param name="allowFullScreen" value="true" /> <param name="flashvars" value="embed=true&creator=Sigur+R%C3%B3s&title=Svefn-g-Englar&uniqueName=3103985&albumArt=http://images.amazon.com/images/P/B00005IC2H.01.MZZZZZZZ.jpg&album=%C3%81g%C3%A6tis+Byrjun&duration=&image=http://userserve-ak.last.fm/serve/image:320/3103985.jpg&FSSupport=true" /> <embed src="http://cdn.last.fm/videoplayer/33/VideoPlayer.swf" menu="false" quality="high" bgcolor="#000000" width="340" height="289" name="player" align="middle" allowFullScreen="true" flashvars="embed=true&creator=Sigur+R%C3%B3s&title=Svefn-g-Englar&uniqueName=3103985&albumArt=http://images.amazon.com/images/P/B00005IC2H.01.MZZZZZZZ.jpg&album=%C3%81g%C3%A6tis+Byrjun&duration=&image=http://userserve-ak.last.fm/serve/image:320/3103985.jpg&FSSupport=true" type="application/x-shockwave-flash" pluginspage="http://www.macromedia.com/go/getflashplayer" /> </object>
$movie='<object width="'.$width.'" height="'.$height.'" id="player" classid="clsid:d27cdb6e-ae6d-11cf-96b8-444553540000" codebase="http://fpdownload.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=9,0,0,0" align="middle"> <param name="movie" value="http://cdn.last.fm/videoplayer/33/VideoPlayer.swf" /> <param name="menu" value="false" /> <param name="quality" value="high" /> <param name="bgcolor" value="'.$backcolor.'" /> <param name="allowFullScreen" value="true" /> <param name="flashvars" value="embed=true&amp;uniqueName='.$listurl.'&amp;image=http://userserve-ak.last.fm/serve/image:320/'.$listurl.'.jpg&FSSupport=true" /> <embed src="http://cdn.last.fm/videoplayer/33/VideoPlayer.swf" menu="false" quality="high" bgcolor="'.$backcolor.'" width="'.$width.'" height="'.$height.'" name="player" align="middle" allowFullScreen="true" flashvars="embed=true&amp;uniqueName='.$listurl.'&amp;image=http://userserve-ak.last.fm/serve/image:320/'.$listurl.'.jpg&amp;FSSupport=true" type="application/x-shockwave-flash" pluginspage="http://www.macromedia.com/go/getflashplayer" /> </object>';

// Embed Logo
$embed_logo = 'http://userserve-ak.last.fm/serve/image:160/'.$listurl.'.jpg'; 
?>