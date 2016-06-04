<?php
//** $Id: myspace.php 01/05/2008 19:59:00 tcnet Exp $ //
//** MySpace Video Plugin for WebShow Module
//** Author: TCNet (http://technicalcrew.net)
//** Disclaimer: It is the users responsibility to check the embed site's terms of service to determine if their media may be displayed on the users website.

// width, height and colors are called from the selected WebShow player.
// listurl = the video id from the url

if (!defined('XOOPS_ROOT_PATH')){ exit(); }

// Enter The Embed Host's name and URL
$hostname = "MySpace";

// Enter the Host's URL
// Original: http://vids.myspace.com/index.cfm?fuseaction=vids.individual&videoid=2096626
$hostlink = "http://vids.myspace.com/index.cfm?fuseaction=vids.individual&videoid=".$listurl;

// Enter instructions used in the submit form
$embed_dsc = 'Enter just the video id from the url.<br />Example: http://vids.myspace.com/index.cfm?fuseaction=vids.individual&videoid=<span style="color: #FF0000;">2096626</span>';

// Add listurl, width, height and colors to original embed code 
// Original: <embed src="http://lads.myspace.com/videos/vplayer.swf" flashvars="m=2096626&v=2&type=video" type="application/x-shockwave-flash" width="430" height="346"></embed>
$movie='<embed src="http://lads.myspace.com/videos/vplayer.swf" flashvars="m='.$listurl.'&amp;v=2&amp;type=video" type="application/x-shockwave-flash" width="'.$width.'" height="'.$height.'"></embed>';

// Embed Logo
$embed_logo = '';
?>