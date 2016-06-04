<?php
// $Id: admin/index.php,v.50 2007/03/01 19:59:00 tcnet Exp $ //
// Flash Media Player by Jeroen Wijering ( http://www.jeroenwijering.com ) is licensed under a Creative Commons License (http://creativecommons.org/licenses/by-nc-sa/2.0/) //
// It allows you to use and modify the script for noncommercial purposes. //
// You must share alike any modifications. // 
// For commercial use you must purchase a license from Jereon Wijering at http://www.jeroenwijering.com/?order=form. //
// By installing and using the WebShow module you agree that you will not use it to display, distribute
// or syndicate a third party's copyright protected media without the owner's permission.  
// The WebShow software is not to be used to display or syndicate illegal or copyright protected media content.
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
//** Updates WebShow version .63 thru .65 to .71
if (!defined('XOOPS_ROOT_PATH')){ exit(); }
include 'functions.php'; 
include_once XOOPS_ROOT_PATH.'/class/module.errorhandler.php';
$eh = new ErrorHandler;

global $xoopsDB, $xoopsConfig, $xoopsModule, $xoopsModuleConfig;

//******  All Updates  *****//
//** Clear Tpl Cache code from Herve Thoussard News Module
	$tpllist=array('webshow_brokenlink.html','webshow_block_billboard.html','webshow_block_links.html','webshow_block_playit.html','webshow_tag_block_cloud.html','webshow_tag_block_top.html');
	include_once XOOPS_ROOT_PATH."/class/xoopsblock.php";
	include_once XOOPS_ROOT_PATH.'/class/template.php';
	// Clear blocks cache
	xoops_template_clear_module_cache($xoopsModule->getVar('mid'));
	// Clear pages cache
	$xoopsTpl = new XoopsTpl();
	foreach ($tpllist as $onetemplate) {
		$xoopsTpl->clear_cache('db:'.$onetemplate);
	}

// update v.63 to v.65
$result65a = $xoopsDB->queryF("SHOW COLUMNS FROM ".$xoopsDB->prefix('webshow_player')." LIKE 'searchbar'");
if (!$xoopsDB->getRowsNum($result65a) > 0) {
   $xoopsDB->queryF("ALTER TABLE " . $xoopsDB->prefix('webshow_player') . " ADD searchbar tinyint(2) NOT NULL default '0';") or $eh->show("0013");
}
$result65b = $xoopsDB->queryF("SHOW COLUMNS FROM ".$xoopsDB->prefix('webshow_player')." LIKE 'searchlink'");
if (!$xoopsDB->getRowsNum($result65b) > 0) {
   $xoopsDB->queryF("ALTER TABLE " . $xoopsDB->prefix('webshow_player') . " ADD searchlink varchar(250) NOT NULL default '';") or $eh->show("0013");
}

if (file_exists(XOOPS_ROOT_PATH."/modules/webshow/templates/webshow_viewcat.html")) {
    unlink(XOOPS_ROOT_PATH."/modules/webshow/templates/webshow_viewcat.html"); 
}

if (file_exists(XOOPS_ROOT_PATH."/modules/webshow/templates/webshow_poster.html")) {
    unlink(XOOPS_ROOT_PATH."/modules/webshow/templates/webshow_poster.html"); 
}
if (file_exists(XOOPS_ROOT_PATH."/modules/webshow/include/playlistform.inc.php")) {
    unlink(XOOPS_ROOT_PATH."/modules/webshow/include/playlistform.inc.php"); 
}
if (file_exists(XOOPS_ROOT_PATH."/modules/webshow/plugin/soapbox.php")) {
    unlink(XOOPS_ROOT_PATH."/modules/webshow/plugin/soapbox.php"); 
}
if (file_exists(XOOPS_ROOT_PATH."/modules/webshow/plugin/stage6.php")) {
    unlink(XOOPS_ROOT_PATH."/modules/webshow/plugin/stage6.php"); 
}

$result655a =$xoopsDB->queryF("SELECT lid from ".$xoopsDB->prefix("webshow_flashvar")." WHERE transition = 'none'");
while(list($lid) = $xoopsDB->fetchRow($result655a)) {
 $xoopsDB->queryF("UPDATE " . $xoopsDB->prefix('webshow_flashvar') . " SET transition = 0 WHERE lid = $lid" ) or $eh->show("0013");
}

//v.71
// Add credits to links table
$result71 = $xoopsDB->queryF("SHOW COLUMNS FROM ".$xoopsDB->prefix('webshow_mod')." LIKE 'artist'");
if ($xoopsDB->getRowsNum($result71) > 0) {
  $xoopsDB->queryF("ALTER TABLE " . $xoopsDB->prefix('webshow_mod') . " CHANGE `artist` `credit1` VARCHAR( 100 ) DEFAULT '' NOT NULL;") or $eh->show("0013");
}
$result71a = $xoopsDB->queryF("SHOW COLUMNS FROM ".$xoopsDB->prefix('webshow_mod')." LIKE 'album'");
if ($xoopsDB->getRowsNum($result71a) > 0) {
   $xoopsDB->queryF("ALTER TABLE " . $xoopsDB->prefix('webshow_mod') . " CHANGE `album` `credit2` VARCHAR( 100 ) DEFAULT '' NOT NULL;") or $eh->show("0013");
}
$result71b = $xoopsDB->queryF("SHOW COLUMNS FROM ".$xoopsDB->prefix('webshow_mod')." LIKE 'label'");
if ($xoopsDB->getRowsNum($result71b) > 0) {
   $xoopsDB->queryF("ALTER TABLE " . $xoopsDB->prefix('webshow_mod') . " CHANGE `label` `credit3` VARCHAR( 100 ) DEFAULT '' NOT NULL;") or $eh->show("0013");
}
$result71c = $xoopsDB->queryF("SHOW COLUMNS FROM ".$xoopsDB->prefix('webshow_links')." LIKE 'artist'");
if ($xoopsDB->getRowsNum($result71c) > 0) {
  $xoopsDB->queryF("ALTER TABLE " . $xoopsDB->prefix('webshow_links') . " CHANGE `artist` `credit1` VARCHAR( 100 ) DEFAULT '' NOT NULL;") or $eh->show("0013");
}
$result71d = $xoopsDB->queryF("SHOW COLUMNS FROM ".$xoopsDB->prefix('webshow_links')." LIKE 'album'");
if ($xoopsDB->getRowsNum($result71d) > 0) {
   $xoopsDB->queryF("ALTER TABLE " . $xoopsDB->prefix('webshow_links') . " CHANGE `album` `credit2` VARCHAR( 100 ) DEFAULT '' NOT NULL;") or $eh->show("0013");
}
$result71e = $xoopsDB->queryF("SHOW COLUMNS FROM ".$xoopsDB->prefix('webshow_links')." LIKE 'label'");
if ($xoopsDB->getRowsNum($result71e) > 0) {
   $xoopsDB->queryF("ALTER TABLE " . $xoopsDB->prefix('webshow_links') . " CHANGE `label` `credit3` VARCHAR( 100 ) DEFAULT '' NOT NULL;") or $eh->show("0013");
}

$result71f = $xoopsDB->queryF("SHOW COLUMNS FROM ".$xoopsDB->prefix('webshow_flashvar')." LIKE 'linktarget'");
if ($xoopsDB->getRowsNum($result71f) > 0) {
   $xoopsDB->queryF("ALTER TABLE " . $xoopsDB->prefix('webshow_flashvar') . " CHANGE `linktarget` `linktarget` VARCHAR( 24 ) DEFAULT '_blank' NOT NULL;") or $eh->show("0013");
}

// Add rate to entry info
$result71g = $xoopsDB->queryF("SELECT lid, entryinfo from ".$xoopsDB->prefix("webshow_links")." WHERE lid > 0");
while(list($lid, $entryinfo) = $xoopsDB->fetchRow($result71g)) {
   $entryinfo = $entryinfo." rate";
   $xoopsDB->queryF("UPDATE " . $xoopsDB->prefix('webshow_links') . " SET entryinfo = '$entryinfo' WHERE lid = $lid") or $eh->show("0013");
}

// Add category body to cat table
$result71h = $xoopsDB->queryF("SHOW COLUMNS FROM ".$xoopsDB->prefix('webshow_cat')." LIKE 'catbody'");
if (!$xoopsDB->getRowsNum($result71h) > 0) {
   $xoopsDB->queryF("ALTER TABLE " . $xoopsDB->prefix('webshow_cat') . " ADD catbody text NOT NULL;") or $eh->show("0013");
}

if (file_exists(XOOPS_ROOT_PATH."/modules/webshow/brokenlink.php")) {
    unlink(XOOPS_ROOT_PATH."/modules/webshow/brokenlink.php"); 
}
if (file_exists(XOOPS_ROOT_PATH."/modules/webshow/viewcat.php")) {
    unlink(XOOPS_ROOT_PATH."/modules/webshow/viewcat.php"); 
}
if (file_exists(XOOPS_ROOT_PATH."/modules/webshow/poster.php")) {
    unlink(XOOPS_ROOT_PATH."/modules/webshow/poster.php"); 
}
?>