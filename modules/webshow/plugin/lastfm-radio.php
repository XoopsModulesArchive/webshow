<?php
// $Id: lastfm-radio.php 07/04/2008 19:59:00 tcnet Exp $ //
//** lastFM Radio Plugin for WebShow Module
//**  Uses the LastFM widget to create a playlist based on the search query
//** Author: TCNet (http://technicalcrew.net)
//** Disclaimer: It is the users responsibility to check the embed site's terms of service to determine if their media may be displayed on the users website.
//** width, height, backcolor, frontcolor are called from the selected WebShow player.
//** listurl = tag word

if (!defined('XOOPS_ROOT_PATH')){ exit(); }

// Enter The Embed Host's name
$hostname = "LastFM Radio";

// Enter the Host's URL
$hostlink = "http://last.fm";

// Enter instructions used in the submit form 
$embed_dsc = 'lastFM-radio plays music from tagged keywords.<br />Enter a tag word here.';

// Add listurl, width, height and colors to original embed code 
// Original: <object type="application/x-shockwave-flash" data="http://cdn.last.fm/widgets/radio/26.swf" codebase="http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=7,0,0,0" width="184" height="140" > <param name="movie" value="http://cdn.last.fm/widgets/radio/26.swf" /> <param name="flashvars" value="lfmMode=radio&amp;radioURL=globaltags%2FClassical&amp;title=Music+tagged+classical+&amp;theme=blue&amp;lang=en&amp;widget_id=radio_d0f693faead02e5227e309077c6e4e64" /> <param name="bgcolor" value="6598cd" /> <param name="quality" value="high" /> <param name="allowScriptAccess" value="always" /> <param name="allowNetworking" value="all" /> </object>
$lastfmtag = webshow_extract_keywords($listurl);
$movie='<object type="application/x-shockwave-flash" data="http://cdn.last.fm/widgets/radio/26.swf" codebase="http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=7,0,0,0" width="'.$width.'" height="'.$height.'" > <param name="movie" value="http://cdn.last.fm/widgets/radio/26.swf" /> <param name="flashvars" value="lfmMode=radio&amp;radioURL=globaltags%2F'.$lastfmtag.'&amp;title=Music+tagged+'.$lastfmtag.'+&amp;theme=blue&amp;lang=en&amp;widget_id=radio_d0f693faead02e5227e309077c6e4e64" /> <param name="bgcolor" value="'.$backcolor.'" /> <param name="quality" value="high" /> <param name="allowScriptAccess" value="always" /> <param name="allowNetworking" value="all" /> </object>';

// Embed Logo
$embed_logo = 'http://cdn.last.fm/depth/advertising/lastfm/badge_red.gif'; 
?>