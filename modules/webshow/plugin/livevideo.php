<?php
//** $Id: livevideo.php 01/05/2008 19:59:00 tcnet Exp $ //
//** LiveVideo Plugin for WebShow Module
//** Author: TCNet (http://technicalcrew.net)
//** Disclaimer: It is the users responsibility to check the embed site's terms of service to determine if their media may be displayed on the users website.
//** width, height are called from the selected WebShow Player
//** listurl = user enters just the video id from the url
//** http://www.livevideo.com/video/mrmercedesman/F6D925B31BAB4DF080B176AABD5AFD17/surrounding-your-internets-.aspx
//** Example: 

if (!defined('XOOPS_ROOT_PATH')){ exit(); }

// Enter The Embed Host's name
$hostname = "Live Video";

// Enter the Host's URL]
// original: http://www.livevideo.com/video/5B3F2D36615A4C31A0E48F21025AFB25
$hostlink = "http://www.livevideo.com/video/".$listurl;

// Enter instructions used in the submit form 
$embed_dsc = 'Enter the video id from the url.<br />Example: http://www.livevideo.com/video/<span style="color: #FF0000;">5B3F2D36615A4C31A0E48F21025AFB25</span>/all-shook-up.aspx';

// Add listurl, width, height and colors to original embed code 
// Original: <div><embed src="http://www.livevideo.com/flvplayer/embed/5B3F2D36615A4C31A0E48F21025AFB25" type="application/x-shockwave-flash" quality="high" WIDTH="445" HEIGHT="369" wmode="transparent"></embed><br/><a href="http://www.livevideo.com/video/embedLink/5B3F2D36615A4C31A0E48F21025AFB25/669712/all-shook-up.aspx">All Shook Up</a></div>
$movie='<embed src="http://www.livevideo.com/flvplayer/embed/'.$listurl.'" type="application/x-shockwave-flash" quality="high" WIDTH="'.$width.'" HEIGHT="'.$height.'" wmode="transparent"></embed>';

// Embed Logo
$embed_logo = ''; 
?>