<?php
// $Id: admin/perm.php,v.52 2007/03/01 19:59:00 tcnet Exp $ //
// Flash Media Player by Jeroen Wijering ( http://www.jeroenwijering.com ) is licensed under a Creative Commons License (http://creativecommons.org/licenses/by-nc-sa/2.0/) //
// It allows you to use and modify the script for noncommercial purposes. //
// You must share a like any modifications. // 
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
include '../../../include/cp_header.php';
if ( file_exists("../language/".$xoopsConfig['language']."/main.php") ) {
	include "../language/".$xoopsConfig['language']."/main.php";
} else {
	include "../language/english/main.php";
}
include '../include/functions.php';
include_once XOOPS_ROOT_PATH . '/class/xoopsform/grouppermform.php';
$myts =& MyTextSanitizer::getInstance(); // MyTextSanitizer object
xoops_cp_header();
adminmenu(1);
//** The following has been adapted from Herve's News Module groupperm.php
echo "<h2>"._WSA_PERMFORM."</h2>";
$permtoset= isset($_POST['permtoset']) ? intval($_POST['permtoset']) : 1;
$selected=array('','','');
$selected[$permtoset-1]=' selected';
echo "<form method='post' name='fselperm' action='perm.php'><table border=0><tr><td><select name='permtoset' onChange='javascript: document.fselperm.submit()'><option value='1'".$selected[0].">"._WSA_VIEWFORM."</option><option value='2'".$selected[1].">"._WSA_SUBMITFORM."</option><option value='3'".$selected[2].">"._WSA_APPROVEFORM."</option></select></td><td><input type='submit' name='go'></tr></table></form>";
$module_id = $xoopsModule->getVar('mid');

switch($permtoset)
{
	case 1:
		$title_of_form = _WSA_VIEWFORM;
		$perm_name = "webshow_view";
		$perm_desc = _WSA_VIEWFORM_DESC;
		break;
	case 2:
		$title_of_form = _WSA_SUBMITFORM;
		$perm_name = "webshow_submit";
		$perm_desc = _WSA_SUBMITFORM_DESC;
		break;
	case 3:
		$title_of_form = _WSA_APPROVEFORM;
		$perm_name = "webshow_approve";
		$perm_desc = _WSA_APPROVEFORM_DESC."<br />"._WSA_APPROVEPERM_WARN;
		break;
}

// example XoopsGroupPermForm($title, $modid, $permname, $permdesc, $url = "")
$permform = new XoopsGroupPermForm($title_of_form, $module_id, $perm_name, $perm_desc, "admin/perm.php");
$catresult=$xoopsDB->query("SELECT cid, pid, cattitle FROM ".$xoopsDB->prefix('webshow_cat')." ORDER BY cattitle");

while($myrow = $xoopsDB->fetchArray($catresult)) {
   $cid = $myrow['cid'];
   $cattitle = $myts->htmlSpecialChars($myrow['cattitle']);	  
   $pid = $myrow['pid'];
   //example  addItem($itemId, $itemName, $itemParent = 0)      
   $permform->addItem($cid, $cattitle, $pid);	
   // get child category objects
   $arr =array();
   $pidresult=$xoopsDB->query("SELECT cid, pid, cattitle FROM ".$xoopsDB->prefix("webshow_cat")." WHERE cid=$cid ORDER BY cattitle");
   while ( $mypidrow = $xoopsDB->fetchArray($pidresult) ) {
	  foreach($arr as $sub){
         $subcid = $sub['cid'];
         $subcattitle = $myts->htmlSpecialChars($sub['cattitle']);	  
         $subpid = $sub['pid'];	
         $permform->addItem($subcid, $subcattitle, $subpid);
      }
   }
}	   
  
echo $permform->render();
echo "<br /><br /><br /><br />\n";
unset ($permform);

xoops_cp_footer();	
?>