<?php
// $Id: youtube.php 01/05/2008 19:59:00 tcnet Exp $ //
//** YouTube Plugin for WebShow Module
//** Author: TCNet (http://technicalcrew.net)
//** Disclaimer: It is the users responsibility to check the embed site's terms of service to determine if their media may be displayed on the users website.
//** listurl is the video id from the url or the embed code

if (!defined('XOOPS_ROOT_PATH')){ exit(); }

// Enter The Embed Host's name
$hostname = "You Tube";

// Enter the Host's URL.  If possible use listurl to create a link to the media's page.
$hostlink = "http://www.youtube.com/watch?v=".$listurl;

// Enter instructions used in the submit form 
$embed_dsc = 'Enter the video id from the url.<br />Example: http://www.youtube.com/watch?v=<span style="color: #FF0000;">xFnwzdKNtpI</span>';

// Add listurl, width, height and colors to original embed code 
// Original: <object width="425" height="373"><param name="movie" value="http://www.youtube.com/v/xFnwzdKNtpI&rel=0&color1=0xd6d6d6&color2=0xf0f0f0&border=1"></param><param name="wmode" value="transparent"></param><embed src="http://www.youtube.com/v/lGVwm326rnk&rel=0&color1=0xd6d6d6&color2=0xf0f0f0&border=1" type="application/x-shockwave-flash" wmode="transparent" width="425" height="373"></embed></object>
$movie='<object width="'.$width.'" height="'.$height.'"><param name="movie" value="http://www.youtube.com/v/'.$listurl.'&amp;rel=0&amp;color1='.$backcolor.'&amp;color2='.$frontcolor.'&amp;border=0"></param><param name="wmode" value="transparent"></param><embed src="http://www.youtube.com/v/'.$listurl.'&amp;rel=0&amp;color1='.$backcolor.'&amp;color2='.$frontcolor.'&amp;border=0" type="application/x-shockwave-flash" wmode="transparent" width="'.$width.'" height="'.$height.'"></embed></object>';

// Embed Logo
// If possible use listurl to create the hosts thumbnail url.  Else leave empty.
$embed_logo = 'http://img.youtube.com/vi/'.$listurl.'/2.jpg';
?>