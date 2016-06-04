<?php
// $Id: visit.php,v.50 2007/03/01 19:59:00 tcnet Exp $ //
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
include '../../mainfile.php';
$lid = isset($_GET['lid']) ? intval($_GET['lid']) : '';
$cid = isset($_GET['cid']) ? intval($_GET['cid']) : '';
// Counter moved to singlelink.php to count page views. Uncomment here to count hits on the website link.
//$sql = sprintf("UPDATE %s SET hits = hits+1 WHERE lid = %u AND status > 0", $xoopsDB->prefix("webshow_links"), $lid);
//$xoopsDB->queryF($sql);
$result = $xoopsDB->query("select url from ".$xoopsDB->prefix("webshow_links")." where lid=$lid and status>0");
list($url) = $xoopsDB->fetchRow($result);
$url = htmlspecialchars(preg_replace( '/javascript:/si' , 'java script:', $url ), ENT_QUOTES);
echo "<html><head><meta http-equiv=\"Refresh\" content=\"0; URL=".$url."\"></meta></head><body>"._FETCHING."</body></html>";
exit();
?>
