<?php
// $Id: include/playlistform.php,v.50 2007/03/01 19:59:00 tcnet Exp $ //
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
if (!defined('XOOPS_ROOT_PATH')){ exit(); }

//  example XoopsForm($title, $name, $action, $method="post", $addtoken=false)
$sform = new XoopsThemeForm($wsformlabel, $wsformname, $wsformaction, $wsformmethod, $wsformaddtoken = "false");  
$sform->setExtra('enctype="multipart/form-data"');
//** Hidden Variables
$sform->addElement(new XoopsFormHidden('op', $op), false);
$sform->addElement(new XoopsFormHidden('lid', $lid), false);
$sform->addElement(new XoopsFormHidden('listtype', $listtype), false);

   //** FLASH VARIABLES
   //example XoopsFormRadioYN($caption, $name, $value=null, $yes=_YES, $no=_NO, $id="")
   $sform->addElement(new XoopsFormRadioYN("<b>"._WSA_SHOWNAV."</b><br /><span style=\"font-size:90%; font-weight: 500;\">"._WSA_SHOWNAVDSC."</span>", 'shownav', $shownav, _YES, _NO));			
   $sform->addElement(new XoopsFormRadioYN("<b>"._WSA_START."</b><br /><span style=\"font-size:90%; font-weight: 500;\">"._WSA_STARTDSC."</span>", 'start', $start, _YES, _NO));
   $sform->addElement(new XoopsFormRadioYN("<b>"._WSA_SHUFFLE."</b><br /><span style=\"font-size:90%; font-weight: 500;\">"._WSA_SHUFFLEDSC."</span>", 'shuffle', $shuffle, _YES, _NO));
   $sform->addElement(new XoopsFormRadioYN("<b>"._WSA_REPEAT."</b><br /><span style=\"font-size:90%; font-weight: 500;\">"._WSA_REPEATDSC."</span>", 'replay', $replay, _YES, _NO));			

   //** Display Screen Link, URL and Target
   //$sform->addElement(new XoopsFormRadioYN("<b>"._WSA_LINKDIS."</b><br /><span style=\"font-size:90%; font-weight: 500;\">"._WSA_LINKDISDSC."</span>", 'link', $link, _YES, _NO));
   $screenlink_tray = new XoopsFormElementTray("<b>"._WSA_LINKDIS."</b><br /><span style=\"font-size:90%; font-weight: 500;\">"._WSA_LINKDISDSC."</span>","" );
      // example XoopsFormSelect($caption, $name, $value=null, $size=1, $multiple=false, $id="")
      $screenlink=new XoopsFormSelect('', 'link', $link, 1, false, $link);   
      $screenlink->addOption('0',_WSA_SCREENLINKOFF,false);		   
      $screenlink->addOption('site',_WSA_SITEURL,false);
      $screenlink->addOption('page',_WSA_MODULEPAGE,false);
      if($listtype == "single"){
         $screenlink->addOption('down',_WSA_DOWNLOAD,false);
      }
   $screenlink_tray->addElement($screenlink, true);
   $sform->addElement($screenlink_tray);

   $target_tray = new XoopsFormElementTray("<b>"._WSA_LINKTARGET."</b><span style=\"font-size:90%; font-weight: 500;\">"._WSA_LINKTARGETDSC."</span>",'' );
   // example XoopsFormSelect($caption, $name, $value=null, $size=1, $multiple=false, $id="")
   $target=new XoopsFormSelect("", 'linktarget',$linktarget, 1, false, $linktarget);
   $target->addOption('_self','_self',false);
   $target->addOption('_blank','_blank',false);
   $target_tray->addElement($target, true);	
   $sform->addElement($target_tray);

   //** Show Activity Icons
   $sform->addElement(new XoopsFormRadioYN("<b>"._WSA_SHOWICONS."</b><br /><span style=\"font-size:90%; font-weight: 500;\">"._WSA_SHOWICONSDSC."</span>", 'showicons', $showicons, _YES, _NO));	

   //** Show EQ for MP3 Only
   if($listtype == "feed" || $srctype == "mp3") {
      $sform->addElement(new XoopsFormRadioYN("<b>"._WSA_SHOWEQ."</b><br /><span style=\"font-size:90%; font-weight: 500;\">"._WSA_MP3_ONLY."<br />"._WSA_SHOWEQDSC."</span>", 'showeq', $showeq, _YES, _NO));
   } else {
      $sform->addElement(new XoopsFormHidden('showeq', "0"), false);
   }

   //** enablejs  //Java script is disabled ATM
   //if($listtype != "single"){
      //$sform->addElement(new XoopsFormRadioYN("<b>"._WSA_ENABLEJS."</b><br /><span style=\"font-size:90%; font-weight: 500;\">"._WSA_ENABLEJSDSC."</span>", 'enablejs', $enablejs, _YES, _NO));
   //}else{
        $sform->addElement(new XoopsFormHidden('enablejs', "0"), false);
   //}

   //** Thumbnails
   $sform->addElement(new XoopsFormRadioYN("<b>"._WSA_THUMB."</b><br /><span style=\"font-size:90%; font-weight: 500;\">"._WSA_THUMBDSC."</span>", 'thumbslist', $thumbslist, _YES, _NO));

   //** Image Stretch
   $stretch_tray = new XoopsFormElementTray("<b>"._WSA_STRETCH."</b><br /><span style=\"font-size:90%; font-weight: 500;\">"._WSA_STRETCHDSC."</span>","");
   // example XoopsFormSelect($caption, $name, $value=null, $size=1, $multiple=false, $id="")
   $stretch=new XoopsFormSelect("", 'stretch', $stretch, 1, false, $stretch);
   $stretch->addOption('false',_WSA_FALSE,false);
   $stretch->addOption('fit',_WSA_FIT,false);
   $stretch->addOption('true',_WSA_TRUE,false);
   $stretch->addOption('none',_WSA_NONE,false);
   $stretch_tray->addElement($stretch,true);	
   $sform->addElement($stretch_tray);

   //** Image Rotation Time
   if ($listtype != "single") {
      $sform->addElement(new XoopsFormText("<b>"._WSA_ROTATETIME."</b><br /><span style=\"font-size:90%; font-weight: 500;\">"._WSA_ROTATETIMEDSC."</span>", 'rotatetime', 3, 2, $rotatetime), false);
   }

   if ($listtype != "single" & $srctype == "image") {
      //** Slide Show Only (Image Rotator)
      $transition_tray = new XoopsFormElementTray("<b>"._WSA_TRANSITION."</b><br /><span style=\"font-size:90%; font-weight: 500;\">"._WSA_IMAGES_ONLY."<br />"._WSA_TRANSITIONDSC."</span>",'');
      $transition=new XoopsFormSelect("", 'transition', $transition, 1, false, $transition);
      $transition->addOption('0',_WSA_TRANS_ROTATEOFF,false);		
      $transition->addOption('fade',_WSA_TRANS_FADE,false);
      $transition->addOption('slowfade',_WSA_TRANS_SLOWFADE,false);
      $transition->addOption('bgfade',_WSA_TRANS_BGFADE,false);
      $transition->addOption('blocks',_WSA_TRANS_BLOCKS,false);
      $transition->addOption('bubbles',_WSA_TRANS_BUBBLES,false);
      $transition->addOption('circles',_WSA_TRANS_CIRCLES,false);
      $transition->addOption('fluids',_WSA_TRANS_FLUIDS,false);
      $transition->addOption('lines',_WSA_TRANS_LINES,false);
      $transition->addOption('random',_WSA_TRANS_RANDOM,false);			
      $transition_tray->addElement($transition, true);	
      $sform->addElement($transition_tray);		
   } else {
      $sform->addElement(new XoopsFormHidden('rotatetime', 0), false);
      $sform->addElement(new XoopsFormHidden('transition', 'none'), false);
   }

   //** Text Captions from URL for single file video entry only
   if($xoopsUser->isAdmin($xoopsModule->mid())){
      if($listtype == "single" & $srctype == "flv"){
         $sform->addElement(new XoopsFormText("<b>"._WSA_CAPTIONS."</b><br /><span style=\"font-size:90%; font-weight: 500;\">"._WSA_CAPTIONSDSC."</span>", 'captions', 40, 250, $captions), false);
      }else{
         $sform->addElement(new XoopsFormHidden('captions', ""), false);	
      }
   }

   //** Second Audio Program
   $sform->addElement(new XoopsFormText("<b>"._WSA_AUDIO."</b><br /><span style=\"font-size:90%; font-weight: 500;\">"._WSA_AUDIODSC."</span>", 'audio', 60, 250, $audio), false);

   //** PLAYER LOGO IMAGE code borrowed from Article module by phppp
   $playerlogo_option_tray = new XoopsFormElementTray("<b>"._WSA_PLAYERLOGOUPLOAD."</b><br /><span style=\"font-size: 85%; font-weight: 500;\">"._WSA_LOGOSIZE." ".$playerlogosize."bytes<br />"._WSA_LOGODIM." ".$playerlogowidth."px</span>","");
   $playerlogo_option_tray->addElement(new XoopsFormFile("", "userfile2",""));
   $sform->addElement($playerlogo_option_tray);
   unset($playerlogo_tray);
   unset($playerlogo_option_tray);
   $path_playerlogo = $xoopsModule->getVar('dirname')."/images/player";
   $path_playerlogodsc = sprintf(_WSA_PLAYERLOGO_DSC, $path_playerlogo);
   $playerlogo_option_tray = new XoopsFormElementTray("<b>"._WSA_PLAYERLOGOSELECT."</b><br /><span style=\"font-size: 85%; font-weight: 500;\">".$path_playerlogodsc."</span>", "<br />");
   $playerlogo_array =& XoopsLists::getImgListAsArray(XOOPS_ROOT_PATH . "/modules/". $path_playerlogo."/");
   array_unshift($playerlogo_array, _NONE);
   $playerlogo_select = new XoopsFormSelect("", "playerlogo", $playerlogo);
   $playerlogo_select->addOptionArray($playerlogo_array);
   $playerlogo_select->setExtra("onchange=\"showImgSelected('plogo', 'playerlogo', '/modules/".$path_playerlogo."/', '', '" . XOOPS_URL . "')\"");
   $playerlogo_tray = new XoopsFormElementTray("", "&nbsp;");
   $playerlogo_tray->addElement($playerlogo_select);
      if (!empty($playerlogo) && file_exists(XOOPS_ROOT_PATH."/modules/".$path_playerlogo."/".$playerlogo)){
         $playerlogo_tray->addElement(new XoopsFormLabel("", "<div style=\"padding: 8px;\"><img src=\"".XOOPS_URL."/modules/".$path_playerlogo."/". $playerlogo."\" name=\"plogo\" id=\"plogo\" alt=\"\" /></div>"));
      }else{
         $playerlogo_tray->addElement(new XoopsFormLabel("", "<div style=\"padding: 8px;\"><img src=\"".XOOPS_URL ."/modules/".$path_playerlogo."/blank.gif\" name=\"plogo\" id=\"plogo\" alt=\"\" /></div>"));
      }
   $playerlogo_option_tray->addElement($playerlogo_tray);
   $sform->addElement($playerlogo_option_tray);
     
//** Submit buttons
$button_tray = new XoopsFormElementTray('','');
$submit_btn = new XoopsFormButton('', 'post', $btnlabel, 'submit');
$button_tray->addElement($submit_btn);
$restore_btn = "<input type=\"button\" value=\""._WSA_RESTORE."\" onClick=\"location='flashconfig.php?op=restoreFV&amp;lid=$lid'\">";
$button_tray->addElement(new XoopsFormLabel('',$restore_btn));
$sform->addElement($button_tray);		
$sform->display();
?>