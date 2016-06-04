<?php
// $Id: include/playerform.inc.php,v.51 2007/04/04 10:20 tcnet Exp $ //
// Flash Media Player by Jereon Wijering ( http://www.jeroenwijering.com ) is licensed under a Creative Commons License (http://creativecommons.org/licenses/by-nc-sa/2.0/) //
// It allows you to use and modify the script for noncommercial purposes. //
// For commercial use you must purchase a license from Jereon Wijering at http://www.jeroenwijering.com/?order=form. //
//
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

$sform = new XoopsThemeForm($formlabel, "playerform", XOOPS_URL.'/modules/'.$xoopsModule->getVar('dirname').'/admin/player.php', 'post');
$sform->setExtra('enctype="multipart/form-data"');
//** Style Options
$style_tray = new XoopsFormElementTray("<b>"._WSA_PLAYERSTYLE."</b>",'' );
// example XoopsFormSelect($caption, $name, $value=null, $size=1, $multiple=false, $id="")
$style_select=new XoopsFormSelect("", 'styleoption', $styleoption, 1, false, $styleoption);
if($op == "newPlayerS"){
$style_select->setExtra("onchange='window.location=\"player.php?op=newPlayer&amp;styleoption=\"+this.value'");
} elseif ($op == "modPlayerS"){
$style_select->setExtra("onchange='window.location=\"player.php?op=modPlayer&amp;playerid=".$playerid."&amp;styleoption=\"+this.value'");
} elseif ($op == "clonePlayerS"){
$style_select->setExtra("onchange='window.location=\"player.php?op=clonePlayer&amp;playerid=".$playerid."&amp;styleoption=\"+this.value'");
}
$style_select->addOption('1',_WSA_PLAYERSTYLE_OPT1,false);
$style_select->addOption('2',_WSA_PLAYERSTYLE_OPT2,false);
$style_select->addOption('3',_WSA_PLAYERSTYLE_OPT3,false);
$style_select->addOption('4',_WSA_PLAYERSTYLE_OPT4,false);
$style_tray->addElement($style_select, true);	
$sform->addElement($style_tray);

$sform->addElement(new XoopsFormText("<b>"._WSA_PLAYERTITLE."</b>", 'playertitle', 20, 18, $playertitle), true);

if($styleoption == 3 || $styleoption == 4 ){
$sform->addElement(new XoopsFormColorPicker("<b>"._WSA_PLAYBGCLR."</b>",'bgcolor',$bgcolor),false);
$sform->addElement(new XoopsFormColorPicker("<b>"._WSA_DISBKCLR."</b>",'backcolor',$backcolor),false);
$sform->addElement(new XoopsFormColorPicker("<b>"._WSA_DISFTCLR."</b>",'frontcolor',$frontcolor),false);
$sform->addElement(new XoopsFormColorPicker("<b>"._WSA_DISLTCLR."</b>",'lightcolor',$lightcolor),false);
} else {
$sform->addElement(new XoopsFormHidden('bgcolor', $bgcolor), false);
$sform->addElement(new XoopsFormHidden('backcolor', $backcolor), false);
$sform->addElement(new XoopsFormHidden('frontcolor', $frontcolor), false);
$sform->addElement(new XoopsFormHidden('lightcolor', $lightcolor), false);
}

$sform->addElement(new XoopsFormText("<b>"._WSA_PLAYHEIGHT."</b>", 'height', 4, 4, $height), false);	
$sform->addElement(new XoopsFormText("<b>"._WSA_PLAYWIDTH."</b>", 'width', 4, 4, $width), false);
$sform->addElement(new XoopsFormText("<b>"._WSA_DISPLAYHEIGHT."</b>", 'displayheight', 4, 4, $displayheight), false);		
$sform->addElement(new XoopsFormText("<b>"._WSA_DISPLAYWIDTH."</b><br /><span style=\"font-size: 85%; font-weight: 500;\">"._WSA_DISPLAYWIDTH_DSC."</span>", 'displaywidth', 4, 4, $displaywidth), false); 
$sform->addElement(new XoopsFormRadioYN("<b>"._WSA_SCROLL."</b>", 'scroll', $scroll,_YES,_NO));
$sform->addElement(new XoopsFormRadioYN("<b>"._WSA_SHOWDIGIT."</b>", 'showdigits', $showdigits, _YES, _NO));
$sform->addElement(new XoopsFormRadioYN("<b>"._WSA_SHOWFSBUTTON."</b>", 'showfsbutton', $showfsbutton, _YES, _NO));
$sform->addElement(new XoopsFormRadioYN("<b>"._WSA_LARGECONTROL."</b>", 'largecontrol', $largecontrol, _YES, _NO));
$sform->addElement(new XoopsFormRadioYN("<b>"._WSA_SEARCHBAR."</b><br /><span style=\"font-size:85%; font-weight: 500;\">"._WSA_SEARCHBARDSC."</span>", 'searchbar', $searchbar, _YES, _NO));
$sform->addElement(new XoopsFormText("<b>"._WSA_SEARCHLINK."</b><br /><span style=\"font-size:85%; font-weight: 500;\">"._WSA_SEARCHLINKDSC."</span>", 'searchlink', 30, 250, $searchlink), false);

//**HIDDEN VARS
$sform->addElement(new XoopsFormHidden('op', $op), false);
$sform->addElement(new XoopsFormHidden('playerid', $playerid), false);
$sform->addElement(new XoopsFormHidden('lid', $lid), false);

// SUBMIT BUTTONS	
$button_tray = new XoopsFormElementTray('' ,'');
$submit_btn = new XoopsFormButton('', 'post', $btnlabel, 'submit');
$button_tray->addElement($submit_btn);
$sform->addElement($button_tray);
$sform->display();
?>