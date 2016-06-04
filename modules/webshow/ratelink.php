<?php
// $Id: ratelink.php,v.50 2007/03/01 19:59:00 tcnet Exp $ //
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
include "header.php";
include_once XOOPS_ROOT_PATH."/class/module.errorhandler.php";
$eh = new ErrorHandler; //ErrorHandler object
$myts =& MyTextSanitizer::getInstance(); // MyTextSanitizer object
error_reporting(0);
$xoopsLogger->activated = false;
   
function wsrateform(){
   global $xoopsConfigUser, $xoopsModule, $xoopsModuleConfig;
   include_once XOOPS_ROOT_PATH."/class/xoopsformloader.php";
   $lid = isset($_GET['lid']) ? intval($_GET['lid']) : redirect_header("index.php",2,_WS_NOTALLOWED);
   $rating = '';
   $formlabel = _WS_RATETHISSITE;
   $btnlabel = _WS_REPORT;					
   $sform = new XoopsThemeForm($formlabel, "wsrateform", XOOPS_URL.'/modules/'.$xoopsModule->getVar('dirname').'/ratelink.php?op=wsrate&amp;lid='.$lid, 'post');
   $sform->setExtra('enctype="multipart/form-data"');
   //**SELECT REPORT TYPE    
   $rate_tray = new XoopsFormElementTray("<span style=\"font-size:90%; font-weight: 500;\">"._WS_RATINGSCALE."</span>","");
   // example XoopsFormSelect($caption, $name, $value=null, $size=1, $multiple=false, $id="")
   $rateselect = new XoopsFormSelect('', 'rating', $rating, 1, false, $rating);
   $rateselect->setExtra("onchange='doWork(\"ratelink.php?op=wsrate&amp;lid=$lid&amp;rating=\"+this.value,\"wsrate\");'");    
   $rateselect->addOption('',_WS_RATETHISSITE);
   $rateselect->addOption('10','10');		   
   $rateselect->addOption('9','9');
   $rateselect->addOption('8','8');
   $rateselect->addOption('7','7');   
   $rateselect->addOption('6','6');
   $rateselect->addOption('5','5');
   $rateselect->addOption('4','4');
   $rateselect->addOption('3','3');
   $rateselect->addOption('2','2');
   $rateselect->addOption('1','1');
   $rate_tray->addElement($rateselect, true);
   $sform->addElement($rate_tray); 

   //** Hidden Variables
   //$sform->addElement(new XoopsFormHidden('lid', $lid), false);	
	     
   //** Submit buttons
   //$button_tray = new XoopsFormElementTray(_WS_REQUIRED,'');
   //$submit_btn = new XoopsFormButton('', 'post', $btnlabel, 'submit');
   //$button_tray->addElement($submit_btn);
   //$sform->addElement($button_tray);	  
   //$xoopsTpl->assign('sform',$sform->render());
   print $sform->render();
}

function wsrate(){
global $xoopsDB, $xoopsConfig, $xoopsModule, $xoopsModuleConfig, $xoopsUser, $myts, $eh;

$lid = isset($_GET['lid']) ? intval($_GET['lid']) : redirect_header("index.php",2,_WS_NOTALLOWED);

// Check if the lid is in the db
$result = $xoopsDB->queryF("Select lid FROM ".$xoopsDB->prefix('webshow_links')." where lid=$lid");
list($lid) = $xoopsDB->fetchRow($result); 
if (!$lid){
   redirect_header("index.php", 2, _WS_NOTEXIST);
}

// Check if Rating is Null
$rating = isset($_GET['rating']) ? intval($_GET['rating']) : '';
if (!$rating) {
   print '<div id="wsratemsg">'._WS_NORATING.'</div>';
   exit();
}

$ip = getenv("REMOTE_ADDR");

if(empty($xoopsUser)){
  $ratinguser = 0;
}else{
  $ratinguser = $xoopsUser->getVar('uid');
}

// Check if the entry owner is voting (UNLESS Anonymous users allowed to post)
if ($ratinguser != 0) {
  $result=$xoopsDB->query("select submitter from ".$xoopsDB->prefix("webshow_links")." where lid=$lid");
  while(list($ratinguserDB) = $xoopsDB->fetchRow($result)) {
    if ($ratinguserDB == $ratinguser) {
      print '<div id="wsratemsg">'._WS_CANTVOTEOWN.'</div>';
      exit();
    }
  }

  // Check if REG user is trying to vote twice.
  $result=$xoopsDB->queryF("select ratinguser from ".$xoopsDB->prefix("webshow_votedata")." where lid=$lid");
  while(list($ratinguserDB) = $xoopsDB->fetchRow($result)) {
    if ($ratinguserDB == $ratinguser) {
      print '<div id="wsratemsg">'._WS_VOTEONCE2.'</div>';
      exit();
    }
  }
} else {
  // Check if ANONYMOUS user is trying to vote more than once per day.
  $anonwaitdays = 1;
  $yesterday = (time()-(86400 * $anonwaitdays));
  $result=$xoopsDB->queryF("select count(*) FROM ".$xoopsDB->prefix("webshow_votedata")." WHERE lid=$lid AND ratinguser=0 AND ratinghostname = '$ip' AND ratingtimestamp > $yesterday");
  list($anonvotecount) = $xoopsDB->fetchRow($result);
  if ($anonvotecount > 0) {
    print '<div id="wsratemsg">'._WS_VOTEONCE2.'</div>';
    exit();
  }
}

//Vote accepted. Add to vote table
$newid = $xoopsDB->genId($xoopsDB->prefix("webshow_votedata")."_ratingid_seq");
$datetime = time();
$sql = sprintf("INSERT INTO %s (ratingid, lid, ratinguser, rating, ratinghostname, ratingtimestamp) VALUES (%u, %u, %u, %u, '%s', %u)", $xoopsDB->prefix("webshow_votedata"), $newid, $lid, $ratinguser, $rating, $ip, $datetime);
$xoopsDB->queryF($sql) or $eh->show("0013");
//Calculate new rating and add vote/rating to links table
        $query = "select rating FROM ".$xoopsDB->prefix("webshow_votedata")." WHERE lid = ".$lid."";
        $voteresult = $xoopsDB->queryF($query) or $eh->show("0013");
            $votesDB = $xoopsDB->getRowsNum($voteresult);
        $totalrating = 0;
            while(list($rating)=$xoopsDB->fetchRow($voteresult)){
                $totalrating += $rating;
            }
        $finalrating = $totalrating/$votesDB;
        $finalrating = number_format($finalrating, 4);
        $query =  "UPDATE ".$xoopsDB->prefix("webshow_links")." SET rating=$finalrating, votes=$votesDB WHERE lid = $lid";
        $xoopsDB->queryF($query) or $eh->show("0013");
print '<div id="wsratemsg">'._WS_THANKURATE.'</div>';
exit();
}

function wsratenew(){
global $xoopsDB, $xoopsConfig, $xoopsModule, $xoopsModuleConfig, $xoopsUser, $myts, $eh;

$lid = isset($_GET['lid']) ? intval($_GET['lid']) : '';
$result = $xoopsDB->queryF("select lid, rating, votes from ".$xoopsDB->prefix("webshow_links")." where lid=$lid");
list($lid, $rating, $votes) = $xoopsDB->fetchRow($result);
if (!$lid) {
   print _WS_NOTEXIST;
   exit();
}
$rating = number_format($rating, 2);
if ($votes == 1) {
      $votestring = _WS_ONEVOTE;
} else {
      $votestring = sprintf(_WS_NUMVOTES,$votes);
} 
$newrate = '<div style="float: left;"><strong>'._WS_RATING.': </strong>'.$rating.' ('.$votestring.')</div><div class="rating_bar" style="float: left;"><div style="width:'.($rating*10).'%"></div></div>';
print $newrate;

}

if(!isset($_POST['op'])) {
	$op = isset($_GET['op']) ? $_GET['op'] : 'main';
} else {
	$op = $_POST['op'];
}

switch ($op) {
case "wsratenew":
	wsratenew();
	break;
	
case "wsrateform":
	wsrateform();
	break;

case "wsrate":
	wsrate();
	break;

case 'main':
   default:  
      redirect_header(XOOPS_URL . '/modules/' . $xoopsModule->getVar('dirname') .'/index.php',2,_WS_NOTALLOWED);
      break;
}
?>