<?php
// $Id: vimeo.php 01/05/2008 19:59:00 tcnet Exp $ //
//** Vimeo Plugin for WebShow Module
//** Author: TCNet (http://technicalcrew.net)
//** Disclaimer: It is the users responsibility to check the embed site's terms of service to determine if their media may be displayed on the users website.
//** width, height, backcolor, frontcolor are called from the selected WebShow player.
//** listurl = user enters just the video id from the url

if (!defined('XOOPS_ROOT_PATH')){ exit(); }

// Enter The Embed Host's name
$hostname = "Vimeo";

// Enter the Host's URL
// Original: http://www.vimeo.com/192696
$hostlink = "http://www.vimeo.com/".$listurl;

// Enter instructions used in the submit form 
$embed_dsc = 'Enter just the video id from the url.<br />Example: http://www.vimeo.com/<span style="color: #FF0000;">192696</span>';

// Add listurl, width, height and colors to original embed code 
// Original: <object type="application/x-shockwave-flash" width="400" height="300" data="http://www.vimeo.com/moogaloop.swf?clip_id=192696&amp;server=www.vimeo.com&amp;fullscreen=1&amp;show_title=1&amp;show_byline=1&amp;show_portrait=0&amp;color=">	<param name="quality" value="best" />	<param name="allowfullscreen" value="true" />	<param name="scale" value="showAll" />	<param name="movie" value="http://www.vimeo.com/moogaloop.swf?clip_id=192696&amp;server=www.vimeo.com&amp;fullscreen=1&amp;show_title=1&amp;show_byline=1&amp;show_portrait=0&amp;color=" /></object><br /><a href="http://www.vimeo.com/192696/l:embed_192696">Space</a> from <a href="http://www.vimeo.com/whoswipedmybike/l:embed_192696">whoswipedmybike</a> on <a href="http://vimeo.com/l:embed_192696">Vimeo</a>.
$movie='<object type="application/x-shockwave-flash" width="'.$width.'" height="'.$height.'" data="http://www.vimeo.com/moogaloop.swf?clip_id='.$listurl.'&amp;server=www.vimeo.com&amp;fullscreen=1&amp;show_title=1&amp;show_byline=1&amp;show_portrait=0&amp;color=">	<param name="quality" value="best" />	<param name="allowfullscreen" value="true" />	<param name="scale" value="showAll" />	<param name="movie" value="http://www.vimeo.com/moogaloop.swf?clip_id='.$listurl.'&amp;server=www.vimeo.com&amp;fullscreen=1&amp;show_title=1&amp;show_byline=1&amp;show_portrait=0&amp;color=" /></object>';

// Embed Logo
$embed_logo = ''; 
?>