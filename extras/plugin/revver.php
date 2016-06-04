<?php
//** $Id: revver.php 10/18/2008 19:59:00 tcnet Exp $ //
//** Revver Plugin for WebShow Module
//** Author: TCNet (http://technicalcrew.net)
//** Disclaimer: It is the users responsibility to check the embed site's terms of service to determine if their media may be displayed on the users website.

// width, height, backcolor, frontcolor are called from the selected WebShow player.
// listurl = user enters just the video id from the url

if (!defined('XOOPS_ROOT_PATH')){ exit(); }

// Enter The Embed Host's name and URL
$hostname = "Revver";
$hostlink = "http://revver.com/video/".$listurl;

// Enter instructions used in the submit form 
$embed_dsc = 'Enter the video id from the url.<br />Example: http://revver.com/video/<span style="color: #FF0000;">1248666</span>/the-next-big-music-artist/';

// Add listurl, width, height and colors to original embed code 
// Original: <script src="http://flash.revver.com/player/1.0/player.js?mediaId:1248666;width:480;height:392;" type="text/javascript"></script>
$movie='<script src="http://flash.revver.com/player/1.0/player.js?mediaId:'.$listurl.';width:'.$width.';height:'.$height.';" type="text/javascript"></script>';

// Embed Logo
$embed_logo = 'http://frame.revver.com/frame/120x90/'.$listurl.'.jpg'; 
?>