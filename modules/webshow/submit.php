<?php
// $Id: submit.php,v.50 2007/03/01 19:59:00 tcnet Exp $ //
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
include_once XOOPS_ROOT_PATH.'/class/module.errorhandler.php'; 
$eh = new ErrorHandler;
$myts =& MyTextSanitizer::getInstance();
if (file_exists("language/".$xoopsConfig['language']."/admin.php") ) {
	include_once "language/".$xoopsConfig['language']."/admin.php";
} else {
	include_once "language/english/admin.php";
}

//** User Submit
function newLink()
{
   global $xoopsDB, $xoopsOption, $xoopsTpl, $xoopsConfig, $xoopsModule, $xoopsModuleConfig, $xoopsUser, $myts, $eh, $cattree, $playertree, $linktree, $allowed_mimetypes, $logosize, $logowidth, $logoheight, $playerlogosize, $playerlogowidth, $playerlogoheight;

   $xoopsOption['template_main'] = 'webshow_submit.html';   
   include XOOPS_ROOT_PATH."/header.php";
   include_once XOOPS_ROOT_PATH."/class/xoopslists.php";
   include_once XOOPS_ROOT_PATH."/class/xoopsformloader.php";
   include_once XOOPS_ROOT_PATH."/include/xoopscodes.php";
   include_once XOOPS_ROOT_PATH.'/class/xoopstree.php';
   $cattree = new XoopsTree($xoopsDB->prefix("webshow_cat"),"cid","pid");
   $linktree = new XoopsTree($xoopsDB->prefix("webshow_links"),"lid","title");
   $playertree = new XoopsTree($xoopsDB->prefix("webshow_player"),"playerid","playertitle");
   $xoopsTpl -> assign("xoops_module_header", '<link rel="stylesheet" type="text/css" href="'  . XOOPS_URL . '/modules/' . $xoopsModule -> getvar( 'dirname' ) .  '/templates/style.css" />'); 
 
   //** Logo Config	
   $allowed_mimetypes = array('image/gif', 'image/jpeg', 'image/pjpeg', 'image/x-png', 'image/png');
   $logosize = 30000;
   $logowidth = $xoopsModuleConfig['logowidth'];
   $logoheight = $xoopsModuleConfig['logowidth'];  //Square images
   $playerlogosize = 10000;
   $playerlogowidth = 48;
   $playerlogoheight = 48;

   //**Permissions 
    $groups = is_object($xoopsUser) ? $xoopsUser->getGroups() : XOOPS_GROUP_ANONYMOUS;
    $gperm_handler =& xoops_gethandler('groupperm');
    $cid = 0;
    // example checkRight($gperm_name, $gperm_itemid, $gperm_groupid, $gperm_modid = 1)
    if (!$gperm_handler->checkRight('webshow_submit', $cid, $groups, $xoopsModule->getVar('mid'))) {
        redirect_header("javascript:history.go(-1)", 3,_WS_REQUESTDENIED.'<br />'._NOPERM);
        exit();
    }

   // Link to admin entry form.
   if ($xoopsUser && $xoopsUser->isAdmin($xoopsModule->mid())) {
      $adminlink = "<a href=\"".XOOPS_URL."/modules/".$xoopsModule->getVar('dirname')."/admin/index.php?op=newLink\">"._WS_ADMIN."<img src=\"".XOOPS_URL."/modules/".$xoopsModule->getVar('dirname')."/images/editicon.gif\" border=\"0\" alt=\""._WS_ADMIN."\" /></a>";
      $xoopsTpl->assign('adminlink', $adminlink);
   } 

   //** INIT Variables
   $listtype = isset($_POST['listtype']) ? $_POST['listtype'] : isset($_GET['listtype']) ? $_GET['listtype'] : '';
   $xoopsTpl->assign('listtype', $listtype);	
   $srctype = isset($_POST['srctype']) ? $_POST['srctype'] : isset($_GET['srctype']) ? $_GET['srctype'] : '';
   $xoopsTpl->assign('srctype', $srctype);  
   $submitter = !empty($xoopsUser) ? $xoopsUser->getVar('uid') : 0;
   $lid = "";
   $cid = "1";
   $title = "";
   $credit1 = "";
   $credit2 ="";
   $credit3="";
   $listurl = "";
   $listcache ="";
   $cachetime = "";
   $logolink = "";
   $logourl = "";
   $url = "";
   $allowcomments = 1;
   $comments = "";
   $description = "";
   $bodytext = "";
   $playerid = 1;
   $status = 1;
   $date = 0;
   $published = 0;
   $expired = 0;
   $entryinfo = $xoopsModuleConfig['showinfo2']; // SHOWINFO2  Sets the default items displayed.  Assign in mod preferences.
   $member_handler =& xoops_gethandler('member');
   $xoopsgroups =& $member_handler->getgroups();
   $count = count($xoopsgroups);
   $ws_entryperm = array();
   for ($i = 0; $i < $count; $i++)  $ws_entryperm[] = $xoopsgroups[$i]->getVar('groupid');
   $ws_entrydownperm = array();
   for ($i = 0; $i < $count; $i++)  $ws_entrydownperm[] = $xoopsgroups[$i]->getVar('groupid');
  
   //** FLASH VARIABLES
   $flashvars = getFVdefault($lid);
   $start = $flashvars['start'];
   $shuffle = $flashvars['shuffle'];
   $replay = $flashvars['replay'];
   $link = $flashvars['link'];
   $linktarget = $flashvars['linktarget'];
   $showicons = $flashvars['showicons'];
   $stretch = $flashvars['stretch'];
   $showeq = $flashvars['showeq'];
   $rotatetime = $flashvars['rotatetime'];
   $shownav = $flashvars['shownav'];
   $transition = $flashvars['transition'];
   $thumbslist = $flashvars['thumbslist'];
   $captions = $flashvars['captions'];
   $enablejs = $flashvars['enablejs'];
   $playerlogo = $flashvars['playerlogo'];
   $audio = $flashvars['audio'];  

   //** Needed for embed plugins.
   $embed_logo = "";
   $width = "";
   $height = "";
   $frontcolor = "";
   $backcolor = "";

      //** USER SUBMIT FORM: See submitform.inc.php
       $wsformlabel =	_WSA_SUBMITFORMHEAD;
       $wsformname = "usersubmitform";
       $wsformaction = XOOPS_URL.'/modules/'.$xoopsModule->getVar('dirname').'/submit.php?op=newLink';
       $wsformmethod = "post";
       $wsformaddtoken = "false";		
       $op= 'addLink';
       $btnlabel = _ADD;
       include 'include/submitform.inc.php';
       $xoopsTpl->assign('sform',$sform->render());

   //** META TAGS
   // Module Name
   $wsmodname = $myts->htmlSpecialChars($xoopsModule->getVar('name'));
   $xoopsTpl->assign('wsmodname', $wsmodname);

   // Page Title
   $pagetitle = $wsmodname.' '.$myts->htmlSpecialChars($myts->htmlSpecialChars(_WSA_SUBMITPAGEHEAD));
   $xoopsTpl->assign('xoops_pagetitle',$pagetitle);

   // Module Description
   $wsmoddesc = $myts->displayTarea($xoopsModuleConfig['moddesc'],0);
   $xoopsTpl->assign('wsmoddesc', $wsmoddesc);

   // META DESCRIPTION TAG
   $metadesc = strip_tags($wsmoddesc);
   $metadesc = $myts->htmlSpecialChars($metadesc);

   // Meta Keywords
   include_once XOOPS_ROOT_PATH . '/modules/'.$xoopsModule->getVar('dirname').'/include/functions.php';
   $metakey = webshow_extract_keywords($pagetitle." ".$metadesc);
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

//**Add NEW Link to DB
function addLink()
{
   global $xoopsModule, $xoopsModuleConfig;
   include "include/submit.functions.php";
   saveEntry("addLink");
   //$xoopsTpl->assign('notify_show', !empty($xoopsUser) && !$xoopsModuleConfig['autoapprove'] ? 1 : 0); Not used atm.
}

if(!isset($_POST['op'])) {
	$op = isset($_GET['op']) ? $_GET['op'] : 'main';
} else {
	$op = $_POST['op'];
}

switch ($op) {
case "newLink":
	newLink();	
	break;
	
case "addLink":
	addLink();
	break;

case 'main':
   default:  
      newLink();
      break;
}
?>