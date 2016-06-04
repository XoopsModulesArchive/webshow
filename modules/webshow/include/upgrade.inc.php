<?php
// $Id: admin/upgrade.php,v.63 2008/03/06 19:59:00 tcnet Exp $ //
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
//** Updates to the latest WebShow version
if (!defined('XOOPS_ROOT_PATH')){ exit(); }

if (!$xoopsUser){
   redirect_header("javascript:history.go(-1)", 3, _NOPERM);
   exit();
}elseif ($xoopsUser && !$xoopsUser->isAdmin($xoopsModule->mid())) {
   redirect_header("javascript:history.go(-1)", 3, _NOPERM);
   exit();
}
include_once XOOPS_ROOT_PATH.'/class/module.errorhandler.php';
$eh = new ErrorHandler;
$myts =& MyTextSanitizer::getInstance();
global $xoopsDB, $xoopsConfig, $xoopsModule, $xoopsModuleConfig;

//******************************* update v.57 to v.61 *****************************//
// Add showinfo to links table
$result61 = $xoopsDB->queryF("SHOW COLUMNS FROM ".$xoopsDB->prefix('webshow_mod')." LIKE 'author'");
if ($xoopsDB->getRowsNum($result61) > 0) {
  $xoopsDB->queryF("ALTER TABLE " . $xoopsDB->prefix('webshow_mod') . " CHANGE `author` `artist` VARCHAR( 100 ) DEFAULT '' NOT NULL;") or $eh->show("0013");
}
$result61a = $xoopsDB->queryF("SHOW COLUMNS FROM ".$xoopsDB->prefix('webshow_mod')." LIKE 'label'");
if (!$xoopsDB->getRowsNum($result61a) > 0) {
   $xoopsDB->queryF("ALTER TABLE " . $xoopsDB->prefix('webshow_mod') . " ADD  label VARCHAR( 100 ) DEFAULT '' NOT NULL;") or $eh->show("0013");
} 
$result61b = $xoopsDB->queryF("SHOW COLUMNS FROM ".$xoopsDB->prefix('webshow_mod')." LIKE 'ws_tag'");
if (!$xoopsDB->getRowsNum($result61b) > 0) {
   $xoopsDB->queryF("ALTER TABLE " . $xoopsDB->prefix('webshow_mod') . " ADD  ws_tag VARCHAR( 250 ) DEFAULT '' NOT NULL;") or $eh->show("0013");
} 

$xoopsDB->queryF("UPDATE ".$xoopsDB->prefix('webshow_links')." SET comments = 1") or $eh->show("0013");	

//** Delete old files
  if (file_exists(XOOPS_ROOT_PATH."/modules/webshow/include/wsdwnlod.php")) {
    unlink(XOOPS_ROOT_PATH."/modules/webshow/include/wsdwnlod.php"); 
  }

//******************************* update v.61 to v.63 *****************************//
// Add report fields to broken table
$result63 = $xoopsDB->queryF("SHOW COLUMNS FROM ".$xoopsDB->prefix('webshow_broken')." LIKE 'rpttype'");
if (!$xoopsDB->getRowsNum($result63) > 0) {
  $xoopsDB->queryF("ALTER TABLE " . $xoopsDB->prefix('webshow_broken') . " ADD rpttype VARCHAR( 100 ) DEFAULT '' NOT NULL;") or $eh->show("0013");
}
$result63a = $xoopsDB->queryF("SHOW COLUMNS FROM ".$xoopsDB->prefix('webshow_broken')." LIKE 'rptname'");
if (!$xoopsDB->getRowsNum($result63a) > 0) {
   $xoopsDB->queryF("ALTER TABLE " . $xoopsDB->prefix('webshow_broken') . " ADD rptname VARCHAR( 100 ) DEFAULT '' NOT NULL;") or $eh->show("0013");
} 
$result63b = $xoopsDB->queryF("SHOW COLUMNS FROM ".$xoopsDB->prefix('webshow_broken')." LIKE 'rptcmt'");
if (!$xoopsDB->getRowsNum($result63b) > 0) {
   $xoopsDB->queryF("ALTER TABLE " . $xoopsDB->prefix('webshow_broken') . " ADD rptcmt VARCHAR( 250 ) DEFAULT '' NOT NULL;") or $eh->show("0013");
} 
$result63c = $xoopsDB->queryF("SHOW COLUMNS FROM ".$xoopsDB->prefix('webshow_links')." LIKE 'views'");
if (!$xoopsDB->getRowsNum($result63c) > 0) {
   $xoopsDB->queryF("ALTER TABLE " . $xoopsDB->prefix('webshow_links') . " ADD views INT(11) DEFAULT '0' NOT NULL;") or $eh->show("0013");
} 
$result63d = $xoopsDB->queryF("SHOW COLUMNS FROM ".$xoopsDB->prefix('webshow_links')." LIKE 'allowcomments'");
if (!$xoopsDB->getRowsNum($result63d) > 0) {
   $xoopsDB->queryF("ALTER TABLE " . $xoopsDB->prefix('webshow_links') . " ADD allowcomments tinyint(2) NOT NULL default '1';") or $eh->show("0013");
}
$result63e = $xoopsDB->queryF("SHOW COLUMNS FROM ".$xoopsDB->prefix('webshow_links')." LIKE 'showinfo'");
if ($xoopsDB->getRowsNum($result63e) > 0) {
   $xoopsDB->queryF("UPDATE  " . $xoopsDB->prefix('webshow_links') . " SET `showinfo` = 'dsc logo cred stat sbmt pop tag feed site down' WHERE `lid` >0") or $eh->show("0013");
   $xoopsDB->queryF("ALTER TABLE " . $xoopsDB->prefix('webshow_links') . " CHANGE `showinfo` `entryinfo` VARCHAR(255) DEFAULT 'dsc logo cred stat sbmt pop tag feed site down' NOT NULL;") or $eh->show("0013");
}
$result63f = $xoopsDB->queryF("SHOW COLUMNS FROM ".$xoopsDB->prefix('webshow_links')." LIKE 'srctype'");
if ($xoopsDB->getRowsNum($result63f) > 0) {
   $xoopsDB->queryF("ALTER TABLE " . $xoopsDB->prefix('webshow_links') . " CHANGE `srctype` `srctype` VARCHAR(250) DEFAULT '' NOT NULL;") or $eh->show("0013");
}
$result63g = $xoopsDB->queryF("SHOW COLUMNS FROM ".$xoopsDB->prefix('webshow_links')." LIKE 'comments'");
if ($xoopsDB->getRowsNum($result63g) > 0) {
   $xoopsDB->queryF("UPDATE " . $xoopsDB->prefix('webshow_links') . " SET `comments` = 0 WHERE `comments` = 1") or $eh->show("0013");
}
$result63h = $xoopsDB->queryF("SHOW COLUMNS FROM ".$xoopsDB->prefix('webshow_links')." LIKE 'entrydownperm'");
if (!$xoopsDB->getRowsNum($result63h) > 0) {
   $xoopsDB->queryF("ALTER TABLE " . $xoopsDB->prefix('webshow_links') . " ADD entrydownperm varchar(64) NOT NULL default '';") or $eh->show("0013");
}

$result63i=$xoopsDB->queryF("SELECT lid, date, published from ".$xoopsDB->prefix("webshow_links")." WHERE published = 0");
while(list($lid, $date, $published) = $xoopsDB->fetchRow($result63i)) {
 $xoopsDB->queryF("UPDATE " . $xoopsDB->prefix('webshow_links') . " SET published = date WHERE published = 0") or $eh->show("0013");
}

$result63j=$xoopsDB->queryF("SELECT lid from ".$xoopsDB->prefix("webshow_flashvar")." WHERE lid = 0");
if (!$xoopsDB->getRowsNum($result63j) > 0) {
   $xoopsDB->queryF("INSERT INTO " . $xoopsDB->prefix('webshow_flashvar') . " (`lid`) VALUES ('0')") or $eh->show("0013");
}

$result63k = $xoopsDB->queryF("SHOW COLUMNS FROM ".$xoopsDB->prefix('webshow_flashvar')." LIKE 'link'");
if ($xoopsDB->getRowsNum($result63k) > 0) {
   $xoopsDB->queryF("ALTER TABLE " . $xoopsDB->prefix('webshow_flashvar') . " CHANGE `link` `link` VARCHAR(250) DEFAULT '0' NOT NULL;") or $eh->show("0013");
}

$result63m = $xoopsDB->queryF("SHOW COLUMNS FROM ".$xoopsDB->prefix('webshow_flashvar')." LIKE 'lid'");
if ($xoopsDB->getRowsNum($result63m) > 0) {
   $xoopsDB->queryF("ALTER TABLE " . $xoopsDB->prefix('webshow_flashvar') . " CHANGE `lid` `lid` INT(11) unsigned NOT NULL;") or $eh->show("0013");
}

$result63n = $xoopsDB->queryF("SHOW COLUMNS FROM ".$xoopsDB->prefix('webshow_flashvar')." LIKE 'audio'");
if (!$xoopsDB->getRowsNum($result63n) > 0) {
   $xoopsDB->queryF("ALTER TABLE " . $xoopsDB->prefix('webshow_flashvar') . " ADD audio varchar(250) NOT NULL default '';") or $eh->show("0013");
}

$result63o=$xoopsDB->queryF("SELECT lid, views, hits from ".$xoopsDB->prefix("webshow_links")." WHERE views = 0");
while(list($lid, $views, $hits) = $xoopsDB->fetchRow($result63o)) {
 $xoopsDB->queryF("UPDATE " . $xoopsDB->prefix('webshow_links') . " SET views = hits WHERE views = 0") or $eh->show("0013");
}

//** Delete old files
if (file_exists(XOOPS_ROOT_PATH."/modules/webshow/submit_utube.php")) {
    unlink(XOOPS_ROOT_PATH."/modules/webshow/submit_utube.php"); 
}
if (file_exists(XOOPS_ROOT_PATH."/modules/webshow/templates/webshow_linklite.html")) {
    unlink(XOOPS_ROOT_PATH."/modules/webshow/templates/webshow_linklite.html"); 
}
if (file_exists(XOOPS_ROOT_PATH."/modules/webshow/include/movie.php")) {
    unlink(XOOPS_ROOT_PATH."/modules/webshow/include/movie.php"); 
}

print "WebShow Update Successful<br />";
?>