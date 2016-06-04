<?php
// $Id: singing-fool.php 06/15/2009 19:59:00 tcnet Exp $ //
//** Singing Fool Plugin for WebShow Module
//** Author: TCNet (http://technicalcrew.net)
//** Disclaimer: It is the users responsibility to check the embed site's terms of service to determine if their media may be displayed on the users website.

if (!defined('XOOPS_ROOT_PATH')){ exit(); }

// Enter The Embed Host's name
$hostname = "Singing Fool";

// Enter the Host's URL.  If possible use listurl to create a link to the media's page.
$hostlink = "http://www.singingfool.com/Title.aspx?publishedid=".$listurl;

// Enter instructions used in the submit form 
$embed_dsc = 'Enter the published id from the url.<br />Example: http://www.singingfool.com/Title.aspx?publishedid=<span style="color: #FF0000;">73003</span>';

// Add listurl, width, height and colors to original embed code 
// Original: <embed src="http://www.videodetective.net/flash/players/musicapi/?publishedid=73003" width="320" height="260" allowfullscreen="true" allowscriptaccess="always" flashvars="autostart=false" />
$movie='<embed src="http://www.videodetective.net/flash/players/musicapi/?publishedid='.$listurl.'" width="'.$width.'" height="'.$height.'" allowfullscreen="true" allowscriptaccess="always" flashvars="autostart=false" />';

// Embed Logo
// If possible use listurl to create the hosts thumbnail url.  Else leave empty.
$embed_logo = '';
?>