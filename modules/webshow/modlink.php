<?php
// $Id: modlink.php,v.50 2007/03/01 19:59:00 tcnet Exp $
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

/* modlink.php allows submitter and admin to modify an entry. */

include "header.php";
include_once XOOPS_ROOT_PATH.'/class/xoopstree.php';
include_once XOOPS_ROOT_PATH."/class/xoopslists.php";
include_once XOOPS_ROOT_PATH."/class/xoopsformloader.php";
include_once XOOPS_ROOT_PATH."/class/uploader.php";
include_once XOOPS_ROOT_PATH.'/class/module.errorhandler.php'; 
include_once XOOPS_ROOT_PATH."/include/xoopscodes.php";

$myts =& MyTextSanitizer::getInstance();
$eh = new ErrorHandler;
$cattree = new XoopsTree($xoopsDB->prefix("webshow_cat"),"cid","pid");
$linktree = new XoopsTree($xoopsDB->prefix("webshow_links"),"lid","title");
$playertree = new XoopsTree($xoopsDB->prefix("webshow_player"),"playerid","playertitle");

//** Logo Config	
$allowed_mimetypes = array('image/gif', 'image/jpeg', 'image/pjpeg', 'image/x-png', 'image/png');
$logosize = 30000;
$logowidth = $xoopsModuleConfig['logowidth'];
$logoheight = $xoopsModuleConfig['logowidth'];  //Square image area
$playerlogosize = 10000;
$playerlogowidth = 48;
$playerlogoheight = 48;

if (!$xoopsUser) {      
   redirect_header("javascript:history.go(-1)", 3,_WS_REQUESTDENIED.'<br />'._NOPERM);
   exit();
} 

//** Redirect admin to the admin form. Disabled. uncomment to use.
//if ($xoopsUser && $xoopsUser->isAdmin($xoopsModule->mid())) {      
//redirect_header(XOOPS_URL . '/modules/' . $xoopsModule->getVar('dirname') . '/admin/index.php?lid='.$lid.'&amp;op=modLink',2,_FETCHING.' '._WSA_ADMIN.': '._WSA_MODENTRY);
//exit();
//} 

   if ( file_exists("language/".$xoopsConfig['language']."/admin.php") ) {
	  include "language/".$xoopsConfig['language']."/admin.php";
   } else {
	  include "language/english/admin.php";
   }

function modLink()
{
   global $xoopsDB, $xoopsOption, $xoopsConfig, $xoopsUser, $xoopsModule, $xoopsModuleConfig, $myts, $eh, $cattree, $playertree, $linktree, $allowed_mimetypes, $logosize, $logowidth, $logoheight, $playerlogosize, $playerlogowidth, $playerlogoheight;

   //*****  Modify Link Form 
   $xoopsOption['template_main'] = 'webshow_modlink.html';   
   include XOOPS_ROOT_PATH."/header.php";
	
   $lid = intval($_GET['lid']);
   $result = $xoopsDB->query("select cid, title, url, srctype, listtype, listurl, listcache, cachetime, status, entryinfo, submitter, logourl, player, credit1, credit2, credit3, cachetime, allowcomments, comments, date, expired, published, entryperm, entrydownperm from ".$xoopsDB->prefix("webshow_links")." where lid=$lid") or $eh->show("0013");
   list($cid, $title, $url, $srctype, $listtype, $listurl, $listcache, $cachetime, $status, $entryinfo, $submitter, $logourl, $playerid, $credit1, $credit2, $credit3, $cachetime, $allowcomments, $comments, $date, $expired, $published, $ws_entryperm, $ws_entrydownperm) = $xoopsDB->fetchRow($result);

   //**Permissions 
    $groups = is_object($xoopsUser) ? $xoopsUser->getGroups() : XOOPS_GROUP_ANONYMOUS;
    $gperm_handler =& xoops_gethandler('groupperm');
    // example checkRight($gperm_name, $gperm_itemid, $gperm_groupid, $gperm_modid = 1)
    if (!$gperm_handler->checkRight('webshow_submit', $cid, $groups, $xoopsModule->getVar('mid'))) {
        redirect_header("javascript:history.go(-1)", 3,_WS_REQUESTDENIED.'<br />'._NOPERM);
        exit();
    }

   //** Check if this user is the entry owner or admin
   if ($xoopsUser && !$xoopsUser->isAdmin($xoopsModule->mid())) {
         if ($submitter != $xoopsUser->getVar('uid')) {
            redirect_header("javascript:history.go(-1)",2,_WS_REQUESTDENIED.'<br />'._WS_NOTOWNER);
            exit();
         }
   }

   $listtype =  isset($_GET["listtype"]) ? $_GET["listtype"] : $listtype;  	
   $title = $myts->htmlSpecialChars($title);
   $url = $myts->htmlSpecialChars($url);
   $listurl = $myts->htmlSpecialChars($listurl);
   $listcache = $myts->htmlSpecialChars($listcache);
   $ps = strpos($logourl,"http://");
   if($ps === false) {
      $logourl = $myts->htmlSpecialChars($logourl);
      $logolink = "";
   } else {
      $logolink = $myts->htmlSpecialChars($logourl);
   }
   if($allowcomments !=1){
      $comments = "";
   }  
   $player = $playerid;
   $credit1 = $myts->htmlSpecialChars($credit1);
   $credit2 = $myts->htmlSpecialChars($credit2);
   $credit3 = $myts->htmlSpecialChars($credit3);
   if(!$date & time()>$published){
      $date=$published;
   }

   $entryinfo = explode(" ", $entryinfo);  
   $ws_entryperm = explode(" ", $ws_entryperm);
   $ws_entrydownperm = explode(" ", $ws_entrydownperm);

   //** TEXT    
   $result2 = $xoopsDB->query("select description, bodytext from ".$xoopsDB->prefix("webshow_text")." where lid=$lid");
   list($description, $bodytext)=$xoopsDB->fetchRow($result2);
   $description = $myts->htmlSpecialChars($description);
   $bodytext = $myts->htmlSpecialChars($bodytext);	

   //** FLASH VARIABLES
   $result3 = $xoopsDB->query("select start, shuffle, replay, link, linktarget, showicons, stretch, showeq, rotatetime, shownav, transition, thumbslist, captions, playerlogo, enablejs, audio from ".$xoopsDB->prefix("webshow_flashvar")." where lid=$lid") or $eh->show("0013");
   list($start, $shuffle, $replay, $link, $linktarget, $showicons, $stretch, $showeq, $rotatetime, $shownav, $transition, $thumbslist, $captions, $playerlogo, $enablejs, $audio)=$xoopsDB->fetchRow($result3);
   $link = $myts->htmlSpecialChars($link);
   $linktarget = $myts->htmlSpecialChars($linktarget);  
   $stretch = $myts->htmlSpecialChars($stretch);       
   $transition = $myts->htmlSpecialChars($transition); 
   $captions = $myts->htmlSpecialChars($captions);
   $playerlogo = $myts->htmlSpecialChars($playerlogo); 
 
   //** MODIFY PLAYLIST FORM variables for submitform.inc   			
   $wsformlabel =	_WSA_MODENTRY;
   $wsformname = "usermodform";
   $wsformaction = XOOPS_URL.'/modules/'.$xoopsModule->getVar('dirname').'/modlink.php?op=modLinkS';
   $wsformmethod = "post";
   $wsformaddtoken = "false";	
   $op= 'modLinkS';
   $btnlabel = _WS_MODIFY;
   include 'include/submitform.inc.php';
   $xoopsTpl->assign('sform',$sform->render());

   //** METATAGS
   // Module Name
   $wsmodname = $myts->htmlSpecialChars($xoopsModule->getVar('name'));
   $xoopsTpl->assign('wsmodname', $wsmodname);

   // Page Title
   $pagetitle = $wsmodname.'&nbsp;'.$myts->htmlSpecialChars(_WSA_MODENTRY)
   $xoopsTpl->assign('xoops_pagetitle',$pagetitle); 

   // Page Description
   $wsmoddesc = $myts->displayTarea($xoopsModuleConfig['moddesc'],0);
   $xoopsTpl->assign('wsmoddesc', $wsmoddesc);

   // META DESCRIPTION TAG
   $metadesc = strip_tags($wsmoddesc);
   $metadesc = $pagetitle.'&nbsp;'.$myts->htmlSpecialChars($metadesc.);

   // Meta Keywords
   include_once XOOPS_ROOT_PATH . '/modules/'.$xoopsModule->getVar('dirname').'/include/functions.php';
   $metakey = webshow_extract_keywords($pagetitle." ".$metadesc." ".$metakey);
   $metakey = $myts->htmlSpecialChars($metakey);

   // Assign meta tags 
   if (is_object($xoTheme)) { 
      $xoTheme->addMeta( 'meta', 'keywords',$metakey);
      $xoTheme->addMeta( 'meta', 'description',$metadesc);     
      $xoTheme->addMeta( 'meta', 'title', $pagetitle); 
   } else {  
      $xoopsTpl->assign('xoops_meta_keywords',$metakey);
      $xoopsTpl->assign('xoops_meta_description',$metadesc);       
   }
   
   include XOOPS_ROOT_PATH.'/footer.php';
}

//** SAVE ENTRY MODIFICATION
function modLinkS()
{
   //** CAPTCHA verification:
   if(@include_once XOOPS_ROOT_PATH."/Frameworks/captcha/captcha.php") {
      $xoopsCaptcha = XoopsCaptcha::instance();
      if(!$xoopsCaptcha->verify(intval($_POST['skipmember'])) ) {
         echo $xoopsCaptcha->getMessage();
         redirect_header(XOOPS_URL."/modules/".$xoopsModule->getVar('dirname')."/index.php", 3, _NOPERM." Captcha");
      }
   }
   include "include/submit.functions.php";
   saveEntry("modLinkS");  
}

if(!isset($_POST['op'])) {
	$op = isset($_GET['op']) ? $_GET['op'] : 'main';
} else {
	$op = $_POST['op'];
}

switch ($op) {
   case "modLink":
      modLink();
      break;

   case "modLinkS":
      modLinkS();
      break;

   case 'main':
   default:  
      modLink();
      break;
}
?>
