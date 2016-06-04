<?php
// $Id: youtube-playlist.php 01/05/2008 19:59:00 tcnet Exp $ //
//** You Tube Playlist Plugin for WebShow Module
//** Author: TCNet
//** Disclaimer: It is the users responsibility to check the embed site's terms of service to determine if their media may be displayed on the users website.
//** width, height, backcolor, frontcolor are called from the selected WebShow player.
//** listurl = user enters just the playlist id from the playlist url

if (!defined('XOOPS_ROOT_PATH')){ exit(); }

// Enter The Embed Host's name
$hostname = "You Tube Playlist";

// Enter the Host's URL
// original: http://www.youtube.com/view_play_list?p=A7F32A4DBF84C746
$hostlink = "http://www.youtube.com/view_play_list?p=".$listurl;

// Enter instructions used in the submit form
$embed_dsc = 'Enter the id from the PLAYLIST url.<br />Example: http://www.youtube.com/view_play_list?p=<span style="color: #FF0000;">A7F32A4DBF84C746</span>';

// Add listurl, width, height and colors to original embed code 
// Original: <object width="480" height="385"><param name="movie" value="http://www.youtube.com/p/A7F32A4DBF84C746" /><embed src="http://www.youtube.com/p/A7F32A4DBF84C746" type="application/x-shockwave-flash" width="480" height="385"></embed></object>
$movie='<object width="'.$width.'" height="'.$height.'"><param name="movie" value="http://www.youtube.com/p/'.$listurl.'" /><embed src="http://www.youtube.com/p/'.$listurl.'" type="application/x-shockwave-flash" width="'.$width.'" height="'.$height.'"></embed></object>';

// Embed Logo
$embed_logo = ''; 
?>