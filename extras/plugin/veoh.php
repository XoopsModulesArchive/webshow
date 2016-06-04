<?php
// $Id: veoh.php 01/05/2008 19:59:00 tcnet Exp $ //
//** Veoh embed Plug In for the WebShow Module for XOOPS
//** Author: TCNet (http://technicalcrew.net)
//** Disclaimer: It is the users responsibility to check the embed site's terms of service to determine if their media may be displayed on the users website.
//** listurl is the video id from the url

if (!defined('XOOPS_ROOT_PATH')){ exit(); }

// Enter The Embed Host's name
$hostname = "Veoh";

// Enter the Host's URL
// Original http://www.veoh.com/videos/v1688234qJjc3gNG
$hostlink = "http://www.veoh.com/videos/".$listurl;

// Enter instructions used in the submit form 
$embed_dsc = 'Enter just the video id from the url.<br />Example: http://www.veoh.com/videos/<span style="color: #FF0000;">v1688234qJjc3gNG</span>?source=featured';

// Add listurl, width, height and colors to original embed code 
// Original: <embed src="http://www.veoh.com/videodetails2.swf?permalinkId=v1688234qJjc3gNG&id=anonymous&player=videodetailsembedded&videoAutoPlay=0" allowFullScreen="true" width="540" height="438" bgcolor="#000000" type="application/x-shockwave-flash" pluginspage="http://www.macromedia.com/go/getflashplayer"></embed><br/><a href="http://www.veoh.com/">Online Videos by Veoh.com</a>
$movie='<embed src="http://www.veoh.com/videodetails2.swf?permalinkId='.$listurl.'&id=anonymous&amp;player=videodetailsembeddedamp;videoAutoPlay=0" allowFullScreen="true" width="'.$width.'" height="'.$height.'" bgcolor="#000000" type="application/x-shockwave-flash" pluginspage="http://www.macromedia.com/go/getflashplayer"></embed>';

// Embed Logo
$embed_logo = ''; // No thumbnail url available. Enter Manually
?>