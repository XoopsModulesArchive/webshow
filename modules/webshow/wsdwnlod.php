<?php
// $Id: wsdownlod.php, WebShow v.57 2007/06/24 19:59:00 tcnet Exp $ //
// Flash Media Player by Jereon Wijering ( http://www.jeroenwijering.com ) is licensed under a Creative Commons License (http://creativecommons.org/licenses/by-nc-sa/2.0/) //
// It allows you to use and modify the script for noncommercial purposes. //
// You must share a like any modifications. // 
// For commercial use you must purchase a license from Jereon Wijering at http://www.jeroenwijering.com/?order=form. //
//  ------------------------------------------------------------------------ //
//                XOOPS - PHP Content Management System                      //
//                    Copyright (c) 2000 XOOPS.org                           //
//                       <http://www.xoops.org/>                             //
//  ------------------------------------------------------------------------ //
//  This program is free software; you can redistribute it and/or modify     //
//  it under the terms of the GNU General Public License as published by     //
//  the Free Software Foundation; either version 2 of the License, or        //
//  (at your option) any later version.                                      //
//                                                                           //
//  You may not change or alter any portion of this comment or credits       //
//  of supporting developers from this source code or any supporting         //
//  source code which is considered copyrighted (c) material of the          //
//  original comment or credit authors.                                      //
//                                                                           //
//  This program is distributed in the hope that it will be useful,          //
//  but WITHOUT ANY WARRANTY; without even the implied warranty of           //
//  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the            //
//  GNU General Public License for more details.                             //
//                                                                           //
//  You should have received a copy of the GNU General Public License        //
//  along with this program; if not, write to the Free Software              //
//  Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307 USA //
//  ------------------------------------------------------------------------ //

/*
/ WebShow Downloader
*/
// Downloads LOCAL files ONLY

include "header.php";
include XOOPS_ROOT_PATH."/header.php";
include_once XOOPS_ROOT_PATH."/class/downloader.php";
error_reporting(0); 
$xoopsLogger->activated = false; 
$myts = & MyTextSanitizer :: getInstance(); // MyTextSanitizer object
global $xoopsUser, $xoopsModuleConfig, $myts;
if(!isset($_POST['lid'])) {
	$lid = isset($_GET['lid']) ? intval($_GET['lid']) : ' ';
} else {
	$lid = intval($_POST['lid']);
}

$result = $xoopsDB->query("select cid, title, listurl, listtype, status, entryperm, entrydownperm from ".$xoopsDB->prefix("webshow_links")." where lid=$lid ");
list($cid, $title, $listurl, $listtype, $status, $ws_entryperm, $ws_entrydownperm) = $xoopsDB->fetchRow($result);
if ($lid == "" || $status <= 0 || $cid == 0) {
  redirect_header("javascript:history.go(-1)", 2,_WS_NOTEXIST);
}
if($listtype != "single") {
 redirect_header("javascript:history.go(-1)",2,_WS_REQUESTDENIED);
}

//** View Permission
//If you can't view it you can't download it.
$group = is_object($xoopsUser) ? $xoopsUser->getGroups() : array(XOOPS_GROUP_ANONYMOUS);
$ws_entryperm = explode(" ",$ws_entryperm);
if (count(array_intersect($group,$ws_entryperm)) < 1)
{
   redirect_header("javascript:history.go(-1)",3, _NOPERM);
   exit();
}

//* Download Permission
$groups = is_object($xoopsUser) ? $xoopsUser->getGroups() : XOOPS_GROUP_ANONYMOUS;
$ws_entrydownperm = explode(" ",$ws_entrydownperm);
if (count(array_intersect($group,$ws_entrydownperm)) < 1) {
   redirect_header("javascript:history.go(-1)",3, _NOPERM);
   exit();
}

// Code below from Ivan Vieira - http://www.sedisoft.com.br
// Based on aarondunlap.com script from PHP:header - Manual

//Get the real path to the file
$parsed_url = parse_url($listurl);
$file_url = $_SERVER['DOCUMENT_ROOT'].$parsed_url["path"];

//See if the file exists
if (!is_file($file_url)) { redirect_header("javascript:history.go(-1)",2,_WS_ERROR_NOMEDIALOCATION); }

//Gather relevent info about file
$file_len = filesize($file_url);
$file_name = basename($listurl);
$file_extension = strtolower(substr(strrchr($filename,"."),1));
//This will set the Content-Type to the appropriate setting for the file
switch($file_extension) {
	case "mp3": $ctype = "audio/x-mp3"; break;
	case "jpg": $ctype = "image/jpeg"; break;
	case "gif": $ctype = "image/gif"; break;
	case "png": $ctype = "image/png"; break;
	case "swf": $ctype = "application/x-shockwave-flash"; break;
	case "flv": $ctype = "video/x-flv"; break;
	case "mp4": $ctype = "video/x-mp4"; break;
	case "mov": $ctype = "video/x-mov"; break;
	default: $ctype="application/force-download";
}

//Begin writing headers
if(ini_get('zlib.output_compression')) ini_set('zlib.output_compression', 'Off');
header("Pragma: public");
header("Expires: 0");
header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
header("Cache-Control: public"); 
header("Content-Description: File Transfer");
//Use the switch-generated Content-Type
header("Content-Type: $ctype");
//Force the download
$header="Content-Disposition: attachment; filename=".$file_name.";";
header($header);
header("Content-Transfer-Encoding: binary");
header("Content-Length: ".(string)filesize($file_url));
@readfile($file_url);
exit;

include XOOPS_ROOT_PATH.'/footer.php';
?>