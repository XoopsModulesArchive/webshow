<?php
// $Id: admin/index.php,v.50 2007/03/01 19:59:00 tcnet Exp $ //
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
//  original comment or credit artists.                                      //
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
include_once XOOPS_ROOT_PATH."/class/xoopslists.php";
include_once XOOPS_ROOT_PATH."/class/xoopsformloader.php";
include_once XOOPS_ROOT_PATH.'/class/module.errorhandler.php'; 
include_once XOOPS_ROOT_PATH."/class/uploader.php";
$myts =& MyTextSanitizer::getInstance();
$eh = new ErrorHandler;

//** Logo Config	
$allowed_mimetypes = array('image/gif', 'image/jpeg', 'image/pjpeg', 'image/x-png', 'image/png');
$playerlogosize = 10000;
$playerlogowidth = 48;
$playerlogoheight = 48;

//** MAIN ADMIN INDEX
function fvConfig()
{
   global $xoopsDB, $xoopsConfig, $xoopsUser, $xoopsModule, $xoopsModuleConfig, $myts, $eh, $allowed_mimetypes, $playerlogosize, $playerlogowidth, $playerlogoheight;
   xoops_cp_header();
   adminmenu(1);
   checkFVdefrow();
   $lid =  isset($_GET['lid']) ? intval($_GET['lid']): 0 ;
   //** CREATE TABLE
   echo "<table class='outer'><tr><th><h3>"._WSA_FLASHVARS." "._WSA_EDITOR."</h3>
      <form style=\"float: left;\" name=\"linkform\" id=\"linkform\">           
      <select name=\"linkselect\" onChange=\"location = this.options[this.selectedIndex].value;\">
      <option value=\"flashconfig.php?lid=0\">"._WSA_SELECTMEDIA."</option>";
   $linkselect = $xoopsDB->query("select lid, title, listtype from ".$xoopsDB->prefix("webshow_links")." where lid>0 and listtype != 'embed'") or $eh->show("0013");
   while(list($link, $title, $listtype) = $xoopsDB->fetchRow($linkselect)) {	   
        echo "<option value=\"flashconfig.php?lid=".$link."\">".$title."</option>";
   }
   echo "</select>";

   if ( $lid > 0 ){
     echo "<input type=\"button\" value=\""._WSA_FVCONFIG."\" onClick=\"location='flashconfig.php?op=fvConfig'\">";
   }
   echo "</form>
      <input type=\"text\" value=\"".$lid."\" size=\"8\" maxlength=\"8\" name=\"getid\">
      <input type=\"button\" value=\""._WSA_GETID."\" onClick=\"location='flashconfig.php?op=fvConfig&amp;lid='+getid.value\">       
      </th></tr>";

   $result = $xoopsDB->query("select title, listtype, srctype, player, entrydownperm from ".$xoopsDB->prefix("webshow_links")." where lid=$lid") or $eh->show("0013");
   list($title, $listtype, $srctype, $playerid, $ws_entrydownperm) = $xoopsDB->fetchRow($result); 	   
   if ($listtype == "embed"){
       redirect_header("javascript:history.go(-1)", 2, _WSA_EMBEDLINK." "._WSA_NOTALLOWED);
   }
   $title = $myts->htmlSpecialChars($title);
   $listtype = $myts->htmlSpecialChars($listtype);
   $srctype = $myts->htmlSpecialChars($srctype);

   //** FLASH VARIABLES
   $flashvars = getFV($lid,"");
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

   //** Playlist Title
   if( $lid > 0 ){
      echo "<tr><td>
              <h3><a href=\"".XOOPS_URL."/modules/".$xoopsModule->getVar('dirname')."/singlelink.php?lid=".$lid."\" title=\""._WS_PAGE_VIEW."\" target=\"_blank\"><img src=\"".XOOPS_URL."/modules/".$xoopsModule->getVar('dirname')."/images/link.gif\" />".$title." "._WSA_FLASHVARS."</a></h3>
              <span style=\"font-size:90%; font-weight: 500;\">"._WSA_FLASHVARS_DSC."</span>
            </td></tr>";
   }else{
      echo "<tr><td><h3>". _WSA_FVCONFIG ."</h3><span style=\"font-size:90%; font-weight: 500;\">"._WSA_FVCONFIG_DSC."</span></td></tr>";
   }

   //** FLASH VARIABLE CONFIG FORM include/flvarform.inc.php   
   $wsformlabel =	_WS_MODIFY." "._WSA_FLASHVARS;
   $wsformname = "fvconfigform";
   $wsformaction = XOOPS_URL.'/modules/'.$xoopsModule->getVar('dirname').'/admin/flashconfig.php?op=fvConfig';
   $wsformmethod = "post";
   $wsformaddtoken = "false";				
   $op= 'fvConfigS';
   $btnlabel = _WS_MODIFY;		
   echo "<tr><td>";
      include '../include/flvarform.inc.php';
   echo "</td></tr></table>";      

xoops_cp_footer();
}

function fvConfigS()
{   
   global $xoopsDB, $xoopsConfig, $xoopsModule, $xoopsUser, $xoopsModuleConfig, $myts, $eh, $allowed_mimetypes, $playerlogosize, $playerlogowidth, $playerlogoheight;
   $lid = isset($_POST['lid']) ? intval($_POST['lid']) : redirect_header("javascript:history.go(-1)", 2, _WSA_NOTALLOWED);
   $listtype = '';   

   if($lid>0) {
      //check if the media entry exists
      $result = $xoopsDB->query("select lid from ".$xoopsDB->prefix("webshow_links")." where lid=$lid and listtype != 'embed'") or $eh->show("0013");
      if (!$xoopsDB->getRowsNum($result)>0) {
         redirect_header("javascript:history.go(-1)", 2, _WSA_NOTALLOWED2);
      }
   }
  
   //** FLASH VARIABLES
      $start = isset($_POST['start']) ? intval($_POST['start']) : 0;
      $shuffle = isset($_POST['shuffle']) ? intval($_POST['shuffle']) : 1;
      $replay = isset($_POST['replay']) ? intval($_POST['replay']) : 0;
      $link = isset($_POST['link']) ? $myts->addSlashes($_POST["link"]): "0";			
      $linktarget = isset($_POST['linktarget']) ? $myts->addSlashes($_POST["linktarget"]): "_blank";
      $showicons = isset($_POST['showicons']) ? intval($_POST['showicons']) : 1;
      $stretch = isset($_POST['stretch']) ? $myts->addSlashes($_POST["stretch"]): "false";
      $showeq = isset($_POST['showeq']) ? intval($_POST['showeq']) : 0;
      $rotatetime = isset($_POST['rotatetime']) ? intval($_POST['rotatetime']) : 0;			
      $shownav = isset($_POST['shownav']) ? intval($_POST['shownav']) : 0;
      $transition = isset($_POST["transition"]) ? $myts->addSlashes($_POST["transition"]) : 0;
      $thumbslist = isset($_POST['thumbslist']) ? intval($_POST['thumbslist']) : 1;
      $captions = isset($_POST["captions"]) ? $myts->addSlashes(formatURL($_POST["captions"])) : '';
      $enablejs = isset($_POST['enablejs']) ? intval($_POST['enablejs']) : 0;
      $audio = isset($_POST["audio"]) ? $myts->addSlashes(formatURL($_POST["audio"])) : '';

      //** PLAYERLOGO UPLOAD
      $playerlogo = "";
      if (!empty($_FILES['userfile2']['name'])) { 
         //$uploader = new XoopsMediaUploader('/home/xoops/uploads', $allowed_mimetypes, $maxfilesize, $maxfilewidth, $maxfileheight);
         $uploader2 = new XoopsMediaUploader(XOOPS_ROOT_PATH ."/modules/".$xoopsModule->getVar('dirname')."/images/player", $allowed_mimetypes, $playerlogosize, $playerlogowidth, $playerlogoheight);
         if ($uploader2->fetchMedia($_POST['xoops_upload_file'][0])) {
            if (!$uploader2->upload()) {
               echo $uploader2->getErrors();
            } else {
               echo '<h4>'._WSA_FILESUCCESS.'</h4>';
               $playerlogo = $uploader2->getSavedFileName();
            }
         } else {
            echo $uploader2->getErrors();
         }
      }
      $playerlogo = empty($playerlogo)?(empty($_POST['playerlogo'])?"":$_POST['playerlogo']):$playerlogo;  

      $xoopsDB->query("update ".$xoopsDB->prefix("webshow_flashvar")." set start='$start', shuffle='$shuffle', replay='$replay', link='$link', linktarget='$linktarget', showicons='$showicons', stretch='$stretch', showeq='$showeq', rotatetime='$rotatetime', shownav='$shownav', transition='$transition', thumbslist='$thumbslist', captions='$captions', enablejs='$enablejs', playerlogo='$playerlogo', audio='$audio' where lid=".$lid."")  or $eh->show("0013");
      redirect_header("flashconfig.php?op=fvConfig&amp;lid=$lid",3,_WSA_DBUPDATED);
      exit();
}

//** Restore to Default
function restoreFV() {
   global $xoopsDB, $eh;
   $lid =  isset($_GET['lid']) ? intval($_GET['lid']) : intval($_POST['lid']);
   if ($lid>0){
      // Get GLOBAL Defaults from Row 0
      $result = $xoopsDB->query("select start, shuffle, replay, link, linktarget, showicons, stretch, showeq, rotatetime, shownav, transition, thumbslist, captions, enablejs, playerlogo, audio from ".$xoopsDB->prefix("webshow_flashvar")." where lid=0") or $eh->show("0013");
      list($start, $shuffle, $replay, $link, $linktarget, $showicons, $stretch, $showeq, $rotatetime, $shownav, $transition, $thumbslist, $captions, $enablejs, $playerlogo, $audio)=$xoopsDB->fetchRow($result);
      $xoopsDB->queryF("update ".$xoopsDB->prefix("webshow_flashvar")." set start='$start', shuffle='$shuffle', replay='$replay', link='$link', linktarget='$linktarget', showicons='$showicons', stretch='$stretch', showeq='$showeq', rotatetime='$rotatetime', shownav='$shownav', transition='$transition', thumbslist='$thumbslist', captions='$captions', enablejs='$enablejs', playerlogo='$playerlogo', audio='$audio' where lid=$lid")  or $eh->show("0013");
   }else{
      //** FLASH VARIABLES
      $flashvars = getFVdefault();
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
      $xoopsDB->queryF("update ".$xoopsDB->prefix("webshow_flashvar")." set start='$start', shuffle='$shuffle', replay='$replay', link='$link', linktarget='$linktarget', showicons='$showicons', stretch='$stretch', showeq='$showeq', rotatetime='$rotatetime', shownav='$shownav', transition='$transition', thumbslist='$thumbslist', captions='$captions', enablejs='$enablejs', playerlogo='$playerlogo', audio='$audio' where lid=0")  or $eh->show("0013");
   }
   redirect_header("flashconfig.php?op=fvConfig&amp;lid=$lid",3,_WSA_DBUPDATED);
   exit();
}

function checkFVdefrow(){
   global $xoopsDB, $eh;
      //check if the modified default row0 exists
      $result = $xoopsDB->query("select lid from ".$xoopsDB->prefix("webshow_flashvar")." where lid=0") or $eh->show("0013");
      if (!$xoopsDB->getRowsNum($result)>0) {
         //** FLASH VARIABLES
         $flashvars = getFVdefault();
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
         $xoopsDB->queryF("INSERT INTO " . $xoopsDB->prefix('webshow_flashvar') . " (lid, start, shuffle, replay, link, linktarget, showicons, stretch, showeq, rotatetime, shownav, transition, thumbslist, captions, enablejs, playerlogo, audio) VALUES ( '0', '$start', '$shuffle', '$replay', '$link', '$linktarget', '$showicons', '$stretch', '$showeq', '$rotatetime', '$shownav', '$transition', '$thumbslist', '$captions', '$enablejs', '$playerlogo', '$audio')") or $eh->show("0013");
      }
   return;
}

if(!isset($_POST['op'])) {
	$op = isset($_GET['op']) ? $_GET['op'] : 'main';
} else {
	$op = $_POST['op'];
}

switch ($op) {
case "fvConfig": 
	fvConfig();
	break;

case "fvConfigS":
	fvConfigS();
	break;

case "restoreFV":
	restoreFV();
	break;
	
case 'main':
default:  
	fvConfig();
	break;
}
?>