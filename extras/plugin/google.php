<?php
// $Id: google.php 01/05/2008 19:59:00 tcnet Exp $ //
//** Google Plugin for WebShow Module
//** Author: TCNet (http://technicalcrew.net)
//** Disclaimer: It is the users responsibility to check the embed site's terms of service to determine if their media may be displayed on the users website.
//** width, height are called from the selected WebShow player.
//** listurl = user enters just the Docid from the Google url

if (!defined('XOOPS_ROOT_PATH')){ exit(); }

// Enter The Embed Host's name
$hostname = "Google Video";

// Enter the Host's URL
// http://video.google.com/videoplay?docid=3166797753930210643
$hostlink = "http://video.google.com/videoplay?docid=".$listurl;

// Enter instructions used in the submit form 
$embed_dsc = 'Google Video displays video from multiple sources.  This embed link is for media hosted at video.google.com only. <br /><strong>Enter the Docid from the url.</strong><br />Example: http://video.google.com/videoplay?docid=<span style="color: #FF0000;">3166797753930210643</span>.';

// Add listurl, width, height and colors to original embed code 
// Original: <embed style="width:400px; height:326px;" id="VideoPlayback" type="application/x-shockwave-flash" src="http://video.google.com/googleplayer.swf?docId=3166797753930210643&hl=en" flashvars=""> </embed>
$movie='<embed style="width:'.$width.'px; height:'.$height.'px;" id="VideoPlayback" type="application/x-shockwave-flash" src="http://video.google.com/googleplayer.swf?docId='.$listurl.'" flashvars=""> </embed>';

// Embed Logo
$embed_logo = '';
?>