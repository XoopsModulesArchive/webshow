<?php
// $Id: youtube.php 05/27/2009 19:59:00 tcnet Exp $ //
//** Flickr Plugin for WebShow Module
//** Author: TCNet (http://technicalcrew.net)
//** Disclaimer: It is the users responsibility to check the embed site's terms of service to determine if their media may be displayed on the users website.
//** listurl is the video id from the url or the embed code

if (!defined('XOOPS_ROOT_PATH')){ exit(); }

// Enter The Embed Host's name
$hostname = "Flickr Tag Show";

// Enter the Host's URL.  If possible use listurl to create a link to the media's page.
// http://www.flickr.com/search/show/?q=yellowstone
$hostlink = "http://www.flickr.com/search/show/?q=".$listurl;

// Enter instructions used in the submit form 
$embed_dsc = 'Enter a short search term to embed a Flickr SlideShow.';

// Flickr SlideShow Embed
// http://www.flickr.com/search/show/?q=yellowstone
// Add listurl, lid to original embed code 
// Original: <object width="400" height="300"> <param name="flashvars" value="offsite=true&lang=en-us&page_show_url=%2Fsearch%2Fshow%2F%3Fq%3Dyellowstone&page_show_back_url=%2Fsearch%2F%3Fq%3Dyellowstone&method=flickr.photos.search&api_params_str=&api_text=yellowstone&api_tag_mode=bool&api_sort=relevance&jump_to=&start_index=0"></param> <param name="movie" value="http://www.flickr.com/apps/slideshow/show.swf?v=71649"></param> <param name="allowFullScreen" value="true"></param><embed type="application/x-shockwave-flash" src="http://www.flickr.com/apps/slideshow/show.swf?v=71649" allowFullScreen="true" flashvars="offsite=true&lang=en-us&page_show_url=%2Fsearch%2Fshow%2F%3Fq%3Dyellowstone&page_show_back_url=%2Fsearch%2F%3Fq%3Dyellowstone&method=flickr.photos.search&api_params_str=&api_text=yellowstone&api_tag_mode=bool&api_sort=relevance&jump_to=&start_index=0" width="400" height="300"></embed></object>

$listurl = str_replace(' ','+',$listurl);
$movie='<object width="'.$width.'" height="'.$height.'"> <param name="flashvars" value="offsite=true&lang=en-us&page_show_url=%2Fsearch%2Fshow%2F%3Fq%3D'.$listurl.'&page_show_back_url=%2Fsearch%2F%3Fq%3D'.$listurl.'&method=flickr.photos.search&api_params_str=&api_text='.$listurl.'&api_tag_mode=bool&api_sort=relevance&jump_to=&start_index=0"></param> <param name="movie" value="http://www.flickr.com/apps/slideshow/show.swf?v=71649"></param> <param name="allowFullScreen" value="true"></param><embed type="application/x-shockwave-flash" src="http://www.flickr.com/apps/slideshow/show.swf?v=71649" allowFullScreen="true" flashvars="offsite=true&lang=en-us&page_show_url=%2Fsearch%2Fshow%2F%3Fq%3D'.$listurl.'&page_show_back_url=%2Fsearch%2F%3Fq%3D'.$listurl.'&method=flickr.photos.search&api_params_str=&api_text='.$listurl.'&api_tag_mode=bool&api_sort=relevance&jump_to=&start_index=0" width="'.$width.'" height="'.$height.'"></embed></object>';

// Embed Logo
// If possible use listurl to create the hosts thumbnail url.  Else leave empty.
// Flickr Logo: http://l.yimg.com/g/images/spaceball.gif
$embed_logo = '';
?>