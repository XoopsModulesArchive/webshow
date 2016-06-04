<?php
// $Id: brokenlink.php,v 1.1 2004/01/29 14:45:56 buennagel Exp $
// $Id: menu.php,v.50 2007/03/01 19:59:00 tcnet Exp $ //
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
include "header.php";
include_once XOOPS_ROOT_PATH."/class/module.errorhandler.php";
$eh = new ErrorHandler; //ErrorHandler object
$myts =& MyTextSanitizer::getInstance(); // MyTextSanitizer object
error_reporting(0);
$xoopsLogger->activated = false;

function wsreportform(){
   //*****  START ENTRY FORM  ******************//      
   include_once XOOPS_ROOT_PATH."/class/xoopsformloader.php";
   global $xoopsConfigUser, $xoopsModule, $xoopsModuleConfig;
   //** Set Entry Variables (links table) 
   $lid = isset($_GET['lid']) ? intval($_GET['lid']) : redirect_header("javascript:history.go(-1)",2,_WS_NOTALLOWED);
   $rpttype = "";
   $rptcmt = "";      
   $formlabel = _WS_REPORT;
   $btnlabel = _WS_REPORT;					
   $sform = new XoopsThemeForm($formlabel, "reportform", XOOPS_URL.'/modules/'.$xoopsModule->getVar('dirname').'/report.php?op=wsreport&amp;lid='.$lid, 'post');
   $sform->setExtra('enctype="multipart/form-data"');
   //**SELECT REPORT TYPE    
   $report_tray = new XoopsFormElementTray("<b>"._WS_REPORT.": </b><br /><span style=\"font-size:90%; font-weight: 500;\">"._WS_REPORT_DSC."</span>","" );
   // example XoopsFormSelect($caption, $name, $value=null, $size=1, $multiple=false, $id="")
   $reporttype=new XoopsFormSelect('Select One<br />', 'rpttype', $rpttype, 3, false, $rpttype);     
   $reporttype->addOption('1',_WS_REPORTABUSE,false);		   
   $reporttype->addOption('2',_WS_REPORTBROKEN,false);
   $reporttype->addOption('3',_WS_REPORTCOPYRIGHT,false);
   $report_tray->addElement($reporttype, true);
   $sform->addElement($report_tray); 
   //** Description
   $sform->addElement(new XoopsFormTextArea("<b>"._WS_REPORTCOMMENT.": </b><br /><span style=\"font-size: 85%; font-weight: 500;\">"._WS_REPORTCOMMENT_DSC."</span>", "rptcmt", $rptcmt, 4, 30), true);								   

   //** CAPTCHA requires Frameworks Captcha
   // Set $xoopsModuleConfig["captcha"] in module preferences;
   // Defaults are set in Frameworks/captcha/config.php or class/captcha
   // example $sform->addElement(new XoopsFormCaptcha($caption, $name,$skipmember,$numchar, $minfontsize,$maxfontsize,$backgroundtype,$backgroundnum));
   if($xoopsModuleConfig["captcha"]){
      if(!file_exists(XOOPS_ROOT_PATH."/class/captcha/xoopscaptcha.php")) {
         include_once XOOPS_ROOT_PATH."/Frameworks/captcha/formcaptcha.php";
      }    
      $skipmember = 1;   
      if($xoopsModuleConfig["captcha"] == 2) {
         $skipmember = 0;     
      }
      // example $sform->addElement(new XoopsFormCaptcha($caption, $name,$skipmember,$numchar, $minfontsize,$maxfontsize,$backgroundtype,$backgroundnum));
      $sform->addElement(new XoopsFormCaptcha("", "wscaptcha", $skipmember));   
      $sform->addElement(new XoopsFormHidden('skipmember', $skipmember), false);
   }

   //** Hidden Variables
   $sform->addElement(new XoopsFormHidden('lid', $lid), false);	
	     
   //** Submit buttons
   $button_tray = new XoopsFormElementTray(_WS_REQUIRED,'');
   $submit_btn = new XoopsFormButton('', 'post', $btnlabel, 'submit');
   $button_tray->addElement($submit_btn);
   //$cancel_btn = new XoopsFormButton('', 'onclick="showstuff(\'wsinfobox\'); hidestuff(\'wsinfobox\'); return false;"', _CANCEL, 'button');
   //$button_tray->addElement($cancel_btn);
   //<a id="codeoff" href="#" onclick="showstuff(\'wscodebox\'); hidestuff(\'wscodebox\'); return false;"><{$smarty.const._WS_CODEBOXOFF}></a>
    //<input type=button value="<{$smarty.const._CANCEL}>" onclick="showstuff(\'wscodebox\'); hidestuff(\'wscodebox\'); return false;" />
   $cancel_btn = new XoopsFormLabel("", '<input type=button value="'._CANCEL.'" onclick="showstuff(\'wscodebox\'); hidestuff(\'wscodebox\'); return false;" />', "");
   $button_tray->addElement($cancel_btn);
   $sform->addElement($button_tray);
   echo $sform->render();
   //$xoopsTpl->assign('wsreport',$sform->render());
   //** END Report Form **//
}

function wsreport(){
   global $xoopsDB, $xoopsConfigUser, $xoopsConfig, $xoopsModule, $xoopsModuleConfig, $xoopsUser, $myts, $eh;
   //** CAPTCHA verification
   if($xoopsModuleConfig["captcha"]){
      if(file_exists(XOOPS_ROOT_PATH."/class/captcha/xoopscaptcha.php")) {
         include_once XOOPS_ROOT_PATH."/class/captcha/xoopscaptcha.php";            
         $xoopsCaptcha = XoopsCaptcha::getInstance();
         if(! $xoopsCaptcha->verify($_POST["skipmember"]) ) {
            echo $xoopsCaptcha->getMessage();
            redirect_header("javascript:history.go(-1)", 3, _WS_CAPTCHA_INCORRECT."<br />"._NOPERM);
         }
      }else{
         if(file_exists(XOOPS_ROOT_PATH."/Frameworks/captcha/captcha.php")) {
            include_once XOOPS_ROOT_PATH."/Frameworks/captcha/captcha.php";
            $xoopsCaptcha = XoopsCaptcha::instance();
            if(! $xoopsCaptcha->verify($_POST["skipmember"]) ) {
               echo $xoopsCaptcha->getMessage();
               redirect_header("javascript:history.go(-1)", 3, _WS_CAPTCHA_INCORRECT."<br />"._NOPERM);
            }
         }    
      }  
   }

	$lid = isset($_POST['lid']) ? intval($_POST['lid']) : redirect_header("javascript:history.go(-1)", 2, _WS_NOTALLOWED);
	$rpttype = isset($_POST['rpttype']) ? intval($_POST['rpttype']) : redirect_header("javascript:history.go(-1)", 2, _WS_NOTALLOWED);
	$rptcmt = isset($_POST['rpttype']) ? $myts->addSlashes($_POST['rptcmt']) : '';	
    $sender = !empty($xoopsUser) ? $xoopsUser->getVar('uid') : 0;
    $ip = getenv("REMOTE_ADDR");

	if ($rpttype == 1){
		$rptname = _WS_REPORTABUSE;		
	} elseif ($rpttype == 2){
		$rptname = _WS_REPORTBROKEN;
	} elseif ($rpttype == 3){
		$rptname = _WS_REPORTCOPYRIGHT;
	} else {
		redirect_header("javascript:history.go(-1)",2,_WS_NOTALLOWED);
		exit();
	}

   	// Check if already reported.
    $result=$xoopsDB->query("SELECT COUNT(*) FROM ".$xoopsDB->prefix("webshow_broken")." WHERE lid=".$lid." AND rpttype=".$rpttype."");
   	list($count)=$xoopsDB->fetchRow($result);  
    if ($count > 0) {
		redirect_header("javascript:history.go(-1)",2,_WS_ALREADYREPORTED);
		exit();
    }

   include_once XOOPS_ROOT_PATH.'/class/module.errorhandler.php'; 
   $eh = new ErrorHandler;

   $newid = $xoopsDB->genId($xoopsDB->prefix("webshow_broken")."_reportid_seq");
   $sql = sprintf("INSERT INTO %s (reportid, lid, sender, ip, rpttype, rptname, rptcmt) VALUES (%u, %u, %u, '%s', %u, '%s', '%s')", $xoopsDB->prefix("webshow_broken"), $newid, $lid, $sender, $ip, $rpttype, $rptname, $rptcmt);
   $xoopsDB->query($sql) or $eh->show("0013");

    // IF TOS ABUSE
	if ($rpttype == 1){	
        $query = "update ".$xoopsDB->prefix("webshow_links")." set status= -5 where lid=".$lid."";
        $xoopsDB->query($query) or $eh->show("0013");
	}

   $tags = array();
   $tags['BROKENREPORTS_URL'] = XOOPS_URL . '/modules/' . $xoopsModule->getVar('dirname') . '/admin/index.php?op=listBrokenLinks';
   $notification_handler =& xoops_gethandler('notification');
   $notification_handler->triggerEvent('global', 0, 'link_broken', $tags);
   redirect_header("index.php",2,_WS_THANKSFORINFO);
   exit();
}

if(!isset($_POST['op'])) {
	$op = isset($_GET['op']) ? $_GET['op'] : 'main';
} else {
	$op = $_POST['op'];
}

switch ($op) {

case 'wsreport':
	wsreport();
	break;
	
case 'wsreportform':
	wsreportform();
	break;

case 'main':
   default:  
      redirect_header(XOOPS_URL . '/modules/' . $xoopsModule->getVar('dirname') .'/index.php',2,_WS_NOTALLOWED);
      break;
}
?>