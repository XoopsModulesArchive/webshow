<?php
// $Id: menu.php,v.50 2007/03/01 19:59:00 tcnet Exp $ //
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

$adminmenu[1]['title'] = _MI_WEBSHOW_ADMENU1;
$adminmenu[1]['link'] = "admin/index.php";
$adminmenu[2]['title'] = _MI_WEBSHOW_ADMENU2;
$adminmenu[2]['link'] = "admin/category.php?op=newCat";
$adminmenu[3]['title'] = _MI_WEBSHOW_ADMENU3;
$adminmenu[3]['link'] = "admin/index.php?op=newLink";
$adminmenu[4]['title'] = _MI_WEBSHOW_ADMENU4;
$adminmenu[4]['link'] = "admin/flashconfig.php";
$adminmenu[5]['title'] = _MI_WEBSHOW_ADMENU5;
$adminmenu[5]['link'] = "admin/player.php?op=newPlayer";
$adminmenu[6]['title'] = _MI_WEBSHOW_ADMENU6;
$adminmenu[6]['link'] = "admin/perm.php";
$adminmenu[7]['title'] = _MI_WEBSHOW_ADMENU7;
$adminmenu[7]['link'] = "admin/index.php?op=listNewLinks";
$adminmenu[8]['title'] = _MI_WEBSHOW_ADMENU8;
$adminmenu[8]['link'] = "admin/index.php?op=listBrokenLinks";
$adminmenu[9]['title'] = _MI_WEBSHOW_ADMENU9;
$adminmenu[9]['link'] = "admin/index.php?op=listModReq";
$adminmenu[10]['title'] = _MI_WEBSHOW_ADMENU10;
$adminmenu[10]['link'] = "admin/index.php?op=webShowHelp";
$adminmenu[11]['title'] = _MI_WEBSHOW_ADMENU11;
$adminmenu[11]['link'] = 'index.php';
?>