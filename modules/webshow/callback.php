<?php
// $Id: statistics.php, webshow v.63 2008/02/02 19:59:00 tcnet Exp $ //
//  ------------------------------------------------------------------------ //
//                XOOPS - PHP Content Management System                      //
//                    Copyright (c) 2000 XOOPS.org                           //
//                       <http://www.xoops.org/>                             //
// ------------------------------------------------------------------------- //
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

//** The JW Player callback function returns $title, $id, $file, $state, $duration
//** $somecontent = "$title (id $id): $file $state ($duration sec.)";
//** This counts media views and logs the start event using callback function of JW Player
include "header.php";
include XOOPS_ROOT_PATH."/header.php";
extract($_POST, EXTR_PREFIX_SAME, "post_");
$time = formatTimestamp(time(),"m");
$ref= $_SERVER['HTTP_REFERER'];
//$agent= $_SERVER['HTTP_USER_AGENT'];
$ip= $_SERVER['REMOTE_ADDR'];
$logfile = 'admin/log.txt';

if ($state == "start"){
   //** Collect start event data
   $event = "$time,$ref,$ip,$state,$id,$title,$file\r\n";
  // Write to log file.
  if (is_writable($logfile)) {
   if (!$handle = fopen($logfile, 'a')) {
         exit;
   }
   if (fwrite($handle, "$event") === FALSE) {
       exit;
   }   
   fclose($handle);
  }
}
exit;
?>