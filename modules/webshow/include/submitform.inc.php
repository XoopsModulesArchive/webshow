<?php
// $Id: include/submitform.php,v.50 2007/03/01 19:59:00 tcnet Exp $ //
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

if ($listtype == '') {
   //** Select Media Source - embed, webfeed, directory or single file.
   $list_tray = new XoopsFormElementTray("<b>"._WSA_LISTTYPE."</b><br /><span style=\"font-size:90%; font-weight: 500;\">"._WSA_LISTTYPE_DSC,'' );
   // example XoopsFormSelect($caption, $name, $value=null, $size=1, $multiple=false, $id="")
   $playlisttype=new XoopsFormSelect('', 'listtype', $listtype, 1, false, $listtype);
   //$playlisttype->setExtra("onchange='doWork(\"".$wsformaction."&amp;listtype=\"+this.value,\"listtype\")'"); 
   $playlisttype->setExtra("onchange='window.location=\"".$wsformaction."&amp;listtype=\"+this.value'");
   $playlisttype->addOption('',"----",false);
   $playlisttype->addOption('embed',_WSA_LISTTYPE_EMBED,false);   
   $playlisttype->addOption('feed',_WSA_LISTTYPE_FEED,false);		   
   if($xoopsUser && $xoopsUser->isAdmin($xoopsModule->mid())){
      $playlisttype->addOption('dir',_WSA_LISTTYPE_DIR,false);
   }
   $playlisttype->addOption('single',_WSA_LISTTYPE_SINGLE,false); 
   $list_tray->addElement($playlisttype, true);	
   $sform->addElement($list_tray);
} elseif ($listtype != '' & $srctype == '') {
   if ($listtype == "embed") {
            $listtypename = _WSA_LISTTYPE_EMBED;           
            //** read through the plugin directory and filter file names to an array
            @$d = dir(XOOPS_ROOT_PATH."/modules/".$xoopsModule->getVar("dirname")."/plugin");

            if ($d) {
               while($entry=$d->read()) {
	             $ps = strpos(strtolower($entry), '.php');
	             if (!($ps === false)) {
	                $entry= str_replace(".php","",$entry);
	  	   	        $items[] = $entry; 
	  	         }					  
	           }
	        }

	        $d->close();
            sort($items);      

            //**SELECT EMBED SOURCE
            $source_tray = new XoopsFormElementTray("<b>"._WSA_EMBED."</b>","" );
            //example XoopsFormSelect($caption, $name, $value=null, $size=1, $multiple=false, $id="")
            $sourcetype=new XoopsFormSelect(_WSA_EMBED_DSC, 'srctype', '', 1, false, $srctype);	   
            $sourcetype->addOption('','----',false);
            if($items){
               for($i=0; $i<sizeof($items); $i++) {
                  $sourcetype->addOption($items[$i],$items[$i],false);              
               }  
            }

            //$sourcetype->setExtra("onchange='doWork(\"".$wsformaction."&amp;listtype=$listtype&amp;srctype=\"+this.value,\"srctype\")'"); 
            $sourcetype->setExtra("onchange='window.location=\"".$wsformaction."&amp;listtype=$listtype&amp;srctype=\"+this.value'");
            $source_tray->addElement($sourcetype, true);	
            $sform->addElement($source_tray);
   }
   if ($listtype == "single" || $listtype == "dir"){
            //**SELECT MEDIA SOURCE      	
            $source_tray = new XoopsFormElementTray("<b>"._WSA_SRC."</b>",'' );
            //example XoopsFormSelect($caption, $name, $value=null, $size=1, $multiple=false, $id="")
            $sourcetype=new XoopsFormSelect(_WSA_SRC_DSC, 'srctype', '', 1, false, $srctype);	   
            $sourcetype->addOption('',"----",false);
            $sourcetype->addOption('mp3',_WSA_SRC_MP3,false);		   
            $sourcetype->addOption('swf',_WSA_SRC_SWF,false);
            $sourcetype->addOption('image',_WSA_SRC_IMAGE,false);
            $sourcetype->addOption('flv',_WSA_SRC_FLV,false);
            //$sourcetype->addOption('mp4',_WSA_SRC_MP4,false);

            //$sourcetype->setExtra("onchange='doWork(\"".$wsformaction."&amp;listtype=$listtype&amp;srctype=\"+this.value,\"srctype\")'"); 
            $sourcetype->setExtra("onchange='window.location=\"".$wsformaction."&amp;listtype=$listtype&amp;srctype=\"+this.value'"); 
            $source_tray->addElement($sourcetype, true);	
            $sform->addElement($source_tray);
   }
   if ($listtype == "feed"){ 
      $srctype = "feed";
   }
}

//** Show Full Form
if ($listtype != '' & $srctype != ''){
  //** Submitter
  if($op == "modLinkS" || $op == "approve"){
     $viewentry = "<a href=\"".XOOPS_URL."/modules/".$xoopsModule->getVar('dirname')."/singlelink.php?lid=".$lid."\" title=\""._WS_PAGE_VIEW."\" target=\"_blank\"><img src=\"".XOOPS_URL."/modules/".$xoopsModule->getVar('dirname')."/images/link.gif\" /></a>";
     if(@include_once XOOPS_ROOT_PATH."/class/userutility.php"){
        $submittername = XoopsUserUtility::getUnameFromId($submitter,0,1);
     }else{
        $submittername = xoops_getLinkedUnameFromId($submitter);
     }
     $sform->addElement(new XoopsFormLabel("<b>"._WSA_SUBMITTER."</b>",$submittername));
  }else{
     $viewentry = "";
  }
      
   //** LIST TYPE LANGUAGE
   $listtypeicon = "<img src=\"".XOOPS_URL."/modules/".$xoopsModule->getVar('dirname')."/images/link.gif\" alt=\""._VISITWEBSITE."\" />";
   $embed_logo = '';
   if ($listtype == "embed") {
     $width = '';
     $height = '';
     $frontcolor = '';
     $backcolor = '';
     include "".XOOPS_ROOT_PATH."/modules/".$xoopsModule->getVar('dirname')."/plugin/".$srctype.".php";
     $listtypedsc = $embed_dsc;
     if ($embed_dsc == ""){
       $listtypedsc = _WSA_LISTTYPE_EMBED_DSC;	
     }  
     $listtypename = _WSA_LISTTYPE_EMBED;
     $listtypeicon = "";
   }  
   if ($listtype == "feed") {
     $listtypename = _WSA_LISTTYPE_FEED;
     $listtypedsc = _WSA_LISTTYPE_FEED_DSC;
     $listtypeicon = "<img src=\"".XOOPS_URL."/modules/".$xoopsModule->getVar('dirname')."/images/webfeed.gif\" alt=\""._VISITWEBSITE."\" />";
     $srctype = "feed";
   }
   if ($listtype == "dir") {
     $listtypename = _WSA_LISTTYPE_DIR;
     $listtypedsc = _WSA_LISTTYPE_DIR_LOC." ".$xoopsModule->getVar('dirname')."/".$xoopsModuleConfig['path_media']."/.<br />"._WSA_LISTTYPE_DIR_DSC;
   }
   if ($listtype == "single") {
     $listtypename = _WSA_LISTTYPE_SINGLE;
     $listtypedsc = _WSA_LISTTYPE_SINGLE_DSC;	
   }

   if($op == "modLinkS") {
      //**SELECT PLAYLIST SOURCE
      $list_tray = new XoopsFormElementTray("<b>"._WSA_LISTTYPE."*</b><br /><span style=\"font-size:90%; font-weight: 500;\">"._WSA_LISTTYPE_DSC,'' );
      // example XoopsFormSelect($caption, $name, $value=null, $size=1, $multiple=false, $id="")
      $playlisttype=new XoopsFormSelect('', 'listtype', $listtype, 1, false, $listtype); 
      $playlisttype->setExtra("onchange='window.location=\"".$wsformaction."&amp;lid=".$lid."&amp;listtype=\"+this.value'");   
      $playlisttype->addOption('embed',_WSA_LISTTYPE_EMBED,false);   
      $playlisttype->addOption('feed',_WSA_LISTTYPE_FEED,false);		   
      $playlisttype->addOption('single',_WSA_LISTTYPE_SINGLE,false); 
      if($xoopsUser && $xoopsUser->isAdmin($xoopsModule->mid())){
         $playlisttype->addOption('dir',_WSA_LISTTYPE_DIR,false);
      }
      $list_tray->addElement($playlisttype, true);	
      $sform->addElement($list_tray);

      //**SELECT EMBED SOURCE
      if ($listtype == "embed") {
         //** read through the plugin directory and filter file names to an array
         @$d = dir(XOOPS_ROOT_PATH."/modules/".$xoopsModule->getVar("dirname")."/plugin");

         if ($d) {
            while($entry=$d->read()) {
	          $ps = strpos(strtolower($entry), '.php');
	          if (!($ps === false)) {
	             $entry= str_replace(".php","",$entry);  
	  		     $items[] = $entry; 
	  	      }					  
	        }
	     }
         
         $source_tray = new XoopsFormElementTray("<b>"._WSA_EMBED."</b><br /><span style=\"font-size:90%; font-weight: 500;\">"._WSA_EMBED_DSC."</span>","" );
         //example XoopsFormSelect($caption, $name, $value=null, $size=1, $multiple=false, $id="")
         $sourcetype=new XoopsFormSelect('', 'srctype', $srctype, 1, false, $srctype);	   
         if($items){
            for($i=0; $i<sizeof($items); $i++) {
               $sourcetype->addOption($items[$i],$items[$i],false);              
            }  
         }         
         $source_tray->addElement($sourcetype, true);
         $sform->addElement($source_tray);
      }elseif($listtype != "feed") {
         //**SELECT MEDIA FILE TYPE    
         $source_tray = new XoopsFormElementTray("<b>"._WSA_SRC."*</b><br /><span style=\"font-size:90%; font-weight: 500;\">"._WSA_SRC_DSC."</span>","" );
         // example XoopsFormSelect($caption, $name, $value=null, $size=1, $multiple=false, $id="")
         $sourcetype=new XoopsFormSelect('', 'srctype', $srctype, 1, false, $srctype);   
         $sourcetype->addOption('mp3',_WSA_SRC_MP3,false);		   
         $sourcetype->addOption('image',_WSA_SRC_IMAGE,false);
         $sourcetype->addOption('flv',_WSA_SRC_FLV,false);
         $sourcetype->addOption('swf',_WSA_SRC_SWF,false);
         //$sourcetype->addOption('mp4',_WSA_SRC_MP4,false);
         //$sourcetype->addOption('all',_WSA_SRC_ALLMEDIA,false); //not needed 
         $source_tray->addElement($sourcetype, true);
         $sform->addElement($source_tray);
      }elseif($listtype == "feed") {
         $sform->addElement(new XoopsFormHidden('srctype', $listtype), true);
      }
   } else {
      $sform->addElement(new XoopsFormLabel("<b>"._WSA_LISTTYPE."</b>",$listtypename));
      $sform->addElement(new XoopsFormHidden('listtype', $listtype), true);
      $sform->addElement(new XoopsFormLabel("<b>"._WSA_SRC."</b>",$srctype));
      $sform->addElement(new XoopsFormHidden('srctype', $srctype), true);
   }

   //** List URL		
   if($listtype != "embed" & $op == "modLinkS" || $op == "approve"){
      $sform->addElement(new XoopsFormText("<b>".$listtypename." "._WSA_LISTURL."</b><a href=\"".$listurl."\" title=\""._VISITWEBSITE."\" target=\"_blank\">".$listtypeicon."</a><br /><span style=\"font-size:90%; font-weight: 500;\">".$listtypedsc."</span>", 'listurl', 60, 250, $listurl), true);  
   }else{
      $sform->addElement(new XoopsFormText("<b>".$listtypename." "._WSA_LISTURL."</b><br /><span style=\"font-size:90%; font-weight: 500;\">".$listtypedsc."</span>", 'listurl', 60, 250, $listurl), true);  
   }

   //** Media Title
   $sform->addElement(new XoopsFormText("<b>"._WSA_MEDIATITLE."</b>".$viewentry, 'title', 60, 60, $title), true);

   //** Description
   $sform->addElement(new XoopsFormTextArea("<b>"._WSA_BLOCKDESCRIPTION.":</b><br /><span style=\"font-size:90%; font-weight: 500;\">"._WSA_BLOCKDESCRIPTION_DSC."</span>", 'description',$description,3,40), true);	

   //** TAGS
   if($xoopsModuleConfig['tags'] & file_exists(XOOPS_ROOT_PATH."/modules/tag/include/formtag.php")){
      include_once XOOPS_ROOT_PATH."/modules/tag/include/formtag.php";
      $itemid = $lid;
      $catid = 0;
      $sform->addElement(new XoopsFormTag("item_tag", 60, 255, $itemid, $catid));
   }else{
     $sform->addElement(new XoopsFormHidden('item_tag', ""), false);
   }

    //**Category Select
	$cat_tray = new XoopsFormElementTray("<b>"._WSA_CATEGORY."</b><br /><span style=\"font-size:90%; font-weight: 500;\">"._WSA_CATEGORY_DSC."</span>","" );
	// example XoopsFormSelect($caption, $name, $value=null, $size=1, $multiple=false, $id="")
	$catsel=new XoopsFormSelect('', 'cid', $cid, 1, false, $cid);   
	$catsel->addOption('0','----',false);
	$sql = "SELECT cid, cattitle FROM ".$xoopsDB->prefix('webshow_cat')." WHERE cid>0 ORDER BY cattitle";
	$result = $xoopsDB->query($sql);
	while (list($catid, $cattitle) = $xoopsDB->fetchRow($result) ) {
	   $sel="false";
	   if($cid == $catid){
	      $sel = "true";
	   }
	   $catsel->addOption($catid,$cattitle,$sel);
	}		   
	$cat_tray->addElement($catsel, true);
	$sform->addElement($cat_tray);

   //** Website URL
   if($url){	
      $viewsite = "<a href=\"".$url."\" title=\""._VISITWEBSITE."\" target=\"_blank\"><img src=\"../images/link.gif\" /></a>";
   }else{
      $viewsite = "";
   }
   $sform->addElement(new XoopsFormText("<b>"._WSA_SITEURL."</b>".$viewsite, 'url', 60, 250, $url), false);

   //** Credits
   //** Artist or Creator
   $sform->addElement(new XoopsFormText("<b>"._WSA_CREDIT1."</b>", 'credit1', 50, 100, $credit1), false);
   //** Album or Classification
   $sform->addElement(new XoopsFormText("<b>"._WSA_CREDIT2."</b>", 'credit2', 50, 100, $credit2), false);
   //** Record Label or Copyright Holder or Embed Source
   $sform->addElement(new XoopsFormText("<b>"._WSA_CREDIT3."</b>", 'credit3', 50, 100, $credit3), false);							

   //** ENTRY LOGO 
   if(!empty($xoopsModuleConfig["path_logo"])){
     if ($logourl == "" & $embed_logo != ""){
       $logo_image_tray = new XoopsFormElementTray("<b>"._WSA_LOGOIMAGE."<br />"._WSA_EMBEDLOGO."</b><br /><span style=\"font-size: 90%; font-weight: 500;\">"._WSA_EMBEDLOGO_DSC."</span>","");
       $sform->addElement(new XoopsFormHidden('embed_logo', $embed_logo), false);
     }else{
       $logo_image_tray = new XoopsFormElementTray("<b>"._WSA_LOGOIMAGE."</b><br /><span style=\"font-size: 90%; font-weight: 500;\">"._WSA_LOGOIMAGE_DSC."<br /><br />"._WSA_LOGOSIZE." ".$logosize."bytes<br />"._WSA_LOGODIM." ".$logowidth."px</span>", "");

       //** CURRENT LOGO
       if($op == "modLinkS"){
          if($xoopsUser && $xoopsUser->isAdmin($xoopsModule->mid())){
             $logo_image_tray->addElement(new XoopsFormText("<b>"._WSA_LOGO_CURRENT."</b> ".$xoopsModuleConfig['path_logo']."/", "logourl", 24, 250, $logourl), false);
          }else{
             $sform->addElement(new XoopsFormHidden('logourl', $logourl), false);
          }
       }

       //** LINK to LOGO URL
       //If logourl is an external link $logolink = $logourl;
       $ps = strpos($logourl, 'http://');
       if (!($ps === false)) { 
         $logolink = $logourl;
       } else {
         $logolink = "";
       }
       $image_option_tray = new XoopsFormElementTray("<br /><br /><b>"._WSA_LOGOURL."</b>", "");
       $image_option_tray->addElement(new XoopsFormText("", 'logolink', 50, 250, $logolink), false);
       $logo_image_tray->addElement($image_option_tray);    

       //** UPLOAD LOGO 
       //** code borrowed from Article module by phppp
       unset($image_option_tray);  
       $image_option_tray = new XoopsFormElementTray("<br /><br /><b>"._WSA_LOGOUPLOAD."</b>", ""); 
       $image_option_tray->addElement(new XoopsFormFile("", "userfile",""));
       $logo_image_tray->addElement($image_option_tray);    

       //** SELECT LOGO
       unset($image_tray);
       unset($image_option_tray);
       $ps = strpos($logourl,'stock/');
       if ($ps === false) {  
          $stocklogo = '';
       }else{
          $stocklogo = $logourl;
       }
       $path_image = $xoopsModuleConfig["path_logo"]."/stock"; 
       $image_option_tray = new XoopsFormElementTray("<br /><br /><b>"._WSA_LOGOSELECT."</b>", "");
       // example function getImgListAsArray($dirname, $prefix="")
       $image_array =& XoopsLists::getImgListAsArray(XOOPS_ROOT_PATH . "/". $path_image."/", "stock/");
       //array_unshift($image_array, "----");
       // example XoopsFormSelect($caption, $name, $value=null, $size=1, $multiple=false, $id="")
       $image_select = new XoopsFormSelect("", "stocklogo", $stocklogo);
       $image_select->addOption('','----',false);	
       $image_select->addOptionArray($image_array);
       $image_select->setExtra("onchange=\"showImgSelected('img', 'stocklogo', '".$xoopsModuleConfig["path_logo"]."', '', '" . XOOPS_URL . "')\"");
       $image_tray = new XoopsFormElementTray("", "");
       $image_tray->addElement($image_select);          

       //** IMAGE PREVIEW
       if (!empty($logourl) && file_exists(XOOPS_ROOT_PATH . "/" .$xoopsModuleConfig["path_logo"]."/" . $logourl)){
          $image_tray->addElement(new XoopsFormLabel("<br />", "<div style=\"padding: 8px;\"><img src=\"" . XOOPS_URL . "/" .$xoopsModuleConfig['path_logo']."/" . $logourl . "\" name=\"img\" id=\"img\" alt=\"\" /></div>"));
       }elseif(!empty($logolink)){
          $image_tray->addElement(new XoopsFormLabel("<br />", "<div style=\"padding: 8px;\"><img src=\"" .$logolink. "\" name=\"img\" id=\"img\" alt=\"\" /></div>"));  
       }else{
          $image_tray->addElement(new XoopsFormLabel("<br />", "<div style=\"padding: 8px;\"><img src=\"" . XOOPS_URL . "/images/blank.gif\" name=\"img\" id=\"img\" alt=\"\" /></div>"));
       }
       $image_option_tray->addElement($image_tray);
       $logo_image_tray->addElement($image_option_tray);
     }   
    $sform->addElement($logo_image_tray);
   }

   //** Allow Comments
   $sform->addElement(new XoopsFormRadioYN("<b>"._WSA_ALLOWCOMMENTS."</b>", 'allowcomments', $allowcomments, _YES, _NO));			
   if($op == "modLinkS"){
      $sform->addElement(new XoopsFormLabel("<b>"._COMMENTS."</b>",$comments));
   }

      if($xoopsUser && $xoopsUser->isAdmin($xoopsModule->mid())){
         //**Player Select Box
         ob_start();
         //** example makeMySelBox($title,$order="",$preset_id=0, $none=0, $sel_name="", $onchange="")			 
         $playertree->makeMySelBox("playertitle","playertitle",$playerid,0,"playerid");
         $playerselbox = ob_get_contents();	
         ob_end_clean();	
         $sform->addElement(new XoopsFormLabel("<b>"._WSA_PLAYER.":</b>",$playerselbox));	 
         if ($listtype != "embed"){
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

            //**FLASH VARIABLES
            $sform->addElement(new XoopsFormLabel("<h3>"._WSA_FLASHVARS."</h3><br /><span style=\"font-size:90%; font-weight: 500;\">",_WSA_FLASHVARS_DSC."</span>"));
            //example XoopsFormRadioYN($caption, $name, $value=null, $yes=_YES, $no=_NO, $id="")
            $sform->addElement(new XoopsFormRadioYN("<b>"._WSA_START."</b><br /><span style=\"font-size:90%; font-weight: 500;\">"._WSA_STARTDSC."</span>", 'start', $start, _YES, _NO));
            $sform->addElement(new XoopsFormRadioYN("<b>"._WSA_SHUFFLE."</b><br /><span style=\"font-size:90%; font-weight: 500;\">"._WSA_SHUFFLEDSC."</span>", 'shuffle', $shuffle, _YES, _NO));
            $sform->addElement(new XoopsFormRadioYN("<b>"._WSA_REPEAT."</b><br /><span style=\"font-size:90%; font-weight: 500;\">"._WSA_REPEATDSC."</span>", 'replay', $replay, _YES, _NO));			

            //** Display Screen Link and Target
            $screenlink_tray = new XoopsFormElementTray("<b>"._WSA_LINKDIS."</b><br /><span style=\"font-size:90%; font-weight: 500;\">"._WSA_LINKDISDSC."</span>","" );
            // example XoopsFormSelect($caption, $name, $value=null, $size=1, $multiple=false, $id="")
            // example XoopsFormSelect($caption, $name, $value=null, $size=1, $multiple=false, $id="")
            $screenlink=new XoopsFormSelect('', 'link', $link, 1, false, $link);   
            $screenlink->addOption('0',_WSA_SCREENLINKOFF,false);		   
            $screenlink->addOption('site',_WSA_SITEURL,false);
            $screenlink->addOption('page',_WS_PAGE_VIEW,false);
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
            if($listtype == "feed"){
               $sform->addElement(new XoopsFormRadioYN("<b>"._WSA_ENABLEJS."</b><br /><span style=\"font-size:90%; font-weight: 500;\">"._WSA_ENABLEJSDSC."</span>", 'enablejs', $enablejs, _YES, _NO));
            }else{
               $sform->addElement(new XoopsFormHidden('enablejs', '0'), false);
            }

            //** Thumbnails
            $sform->addElement(new XoopsFormRadioYN("<b>"._WSA_THUMB."</b><br /><span style=\"font-size:90%; font-weight: 500;\">"._WSA_THUMBDSC."</span>", 'thumbslist', $thumbslist, _YES, _NO));

            //** Text Captions from URL for single file video entry only
            if($listtype == "single" & $srctype == "flv"){
               $sform->addElement(new XoopsFormText("<b>"._WSA_CAPTIONS."</b><br /><span style=\"font-size:90%; font-weight: 500;\">"._WSA_CAPTIONSDSC."</span>", 'captions', 60, 250, $captions), false);
            }else{
               $sform->addElement(new XoopsFormHidden('captions', ""), false);	
            }

            //** Second Audio Program
           $sform->addElement(new XoopsFormText("<b>"._WSA_AUDIO."</b><br /><span style=\"font-size:90%; font-weight: 500;\">"._WSA_AUDIODSC."</span>", 'audio', 60, 250, $audio), false);

           //** Image Stretch
           $stretch_tray = new XoopsFormElementTray("<b>"._WSA_STRETCH."</b><br /><span style=\"font-size:90%; font-weight: 500;\">"._WSA_STRETCHDSC."</span>","");
           // example XoopsFormSelect($caption, $name, $value=null, $size=1, $multiple=false, $id="")
           $stretch=new XoopsFormSelect("", 'stretch', $stretch, 1, false, $stretch);
           $stretch->addOption('false',_WSA_FALSE,false);
           $stretch->addOption('fit',_WSA_FIT,false);
           $stretch->addOption('true',_WSA_TRUE,false);
           $stretch->addOption('none',_WSA_NONE,false);
           $stretch_tray->addElement($stretch,false);	
           $sform->addElement($stretch_tray);

           //** Image Rotation Time
           if ($listtype != "single") {
              $sform->addElement(new XoopsFormText("<b>"._WSA_ROTATETIME."</b><br /><span style=\"font-size:90%; font-weight: 500;\">"._WSA_ROTATETIMEDSC."</span>", 'rotatetime', 3, 2, $rotatetime), false);
           } else { 
              $sform->addElement(new XoopsFormHidden('rotatetime', 0), false);
           }

           if ($srctype == "image" || $srctype == "feed") {         
               //** Image Transition Effects
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
               $transition->addOption('flash',_WSA_TRANS_FLASH,false);
               $transition->addOption('fluids',_WSA_TRANS_FLUIDS,false);
               $transition->addOption('lines',_WSA_TRANS_LINES,false);
               $transition->addOption('random',_WSA_TRANS_RANDOM,false);			
               $transition_tray->addElement($transition, true);	
               $sform->addElement($transition_tray);
        
               //** Show Image Player Navigation Bar
               $sform->addElement(new XoopsFormRadioYN("<b>"._WSA_SHOWNAV."</b><br /><span style=\"font-size:90%; font-weight: 500;\">"._WSA_SHOWNAVDSC."</span>", 'shownav', $shownav, _YES, _NO));		
            } else {
               $sform->addElement(new XoopsFormHidden('transition', '0'), false);
               $sform->addElement(new XoopsFormHidden('shownav', 0), false);
            }

            //**List Cache
            if($op == "modLinkS" & $listtype != "single") {    
               $sform->addElement(new XoopsFormLabel("<b>"._WSA_LISTCACHE."</b>","<span style=\"font-size:90%; font-weight: 500;\"><a href=\"".XOOPS_URL."/modules/".$xoopsModule->getVar("dirname")."/playlist/".$listcache."\" target=\"_blank\" title=\""._WSA_VIEWFORM."\">".$xoopsModule->getVar("dirname")."/playlist/".$listcache."</a></span>"));	
               $sform->addElement(new XoopsFormHidden('listcache', $listcache), false);
               $cachetime_tray = new XoopsFormElementTray("<b>"._WSA_LISTCACHE_TIME."</b><br /><span style=\"font-size:90%; font-weight: 500;\">"._WSA_LISTCACHE_TIME_DSC."</span>",'');
               $cachetime=new XoopsFormSelect("", 'cachetime', $cachetime, 1, false, $cachetime);
               $cachetime->addOption('3600',_HOUR,false);		
               $cachetime->addOption('86400',_DAY,false);
               $cachetime->addOption('604800',_WEEK,false);
               $cachetime->addOption('2592000',_MONTH,false);		
               $cachetime_tray->addElement($cachetime, true);	
               $sform->addElement($cachetime_tray);
               $sform->addElement(new XoopsFormRadioYN("<b>"._WSA_LISTCACHE_REFRESH."</b><br /><span style=\"font-size:90%; font-weight: 500;\">"._WSA_LISTCACHE_REFRESHDSC."</span>", 'refreshlist', 0, _YES, _NO));   	 
            }
         }
      }

   if($op == "modLinkS"){
      //**Body Text
      /* To use html editors in 2.0.* or 2.2.* 
         Upload class/xoopseditors and Frameworks     
      */
      if(@(XOOPS_ROOT_PATH."/class/xoopseditor/xoopseditor.php")) {
         //Comment OUT the next three lines if it conflict with your modified formloader
         if(file_exists(XOOPS_ROOT_PATH."/Frameworks/xoops22/class/xoopsformloader.php")) {
            !@include_once XOOPS_ROOT_PATH."/Frameworks/xoops22/class/xoopsformloader.php";
         }
         $editor = strtolower($xoopsModuleConfig["texteditor"]);
         // options for the editor
         //required configs
         $options['name'] ='bodytext';
         //$options['value'] = empty($_REQUEST['bodytext'])? "" : $_REQUEST['bodytext'];
         $options['value'] = $bodytext;
         //optional configs
         $options['rows'] = 25; // default value = 5
         $options['cols'] = 60; // default value = 50
         $options['width'] = '550px'; // default value = 100%
         $options['height'] = '400px'; // default value = 400px
         $editor_configs = $options;
         $sform->addElement(new XoopsFormEditor(_WSA_TEXTBODY, $editor, $editor_configs, $nohtml = false, $onfailure = "textarea"), false);
      }else{
         //** example XoopsFormDhtmlTextArea($caption, $name, $value, $rows=5, $cols=50, $hiddentext="xoopsHiddenText")
         $sform->addElement(new XoopsFormDhtmlTextArea("<b>"._WSA_TEXTBODY."</b>", 'bodytext',$bodytext,10,10), false);	
      }
   }

   if($xoopsUser && $xoopsUser->isAdmin($xoopsModule->mid())){
     //** Created/Publish/Expire code from Herve Thoussard News Module
     $option_tray = new XoopsFormElementTray(_OPTIONS,'<br />');

     //Original CREATED DATE
      if($date != 0 & $op != "addLink"){
         $check = 0;
         $created_checkbox = new XoopsFormCheckBox('', 'autocreatedate',$check);
         $created_checkbox->addOption(1, _WSA_SETCREATEDATETIME);
         $option_tray->addElement($created_checkbox);
         $option_tray->addElement(new XoopsFormDateTime(_WSA_CREATED, 'created_date', 15, $date));
      }

     //ENTRY PUBLISHED or MODIFIED DATE
     $sform->addElement(new XoopsFormHidden('date', $date), false); //original creation date
     if ($op == "addLink"){   
      //$check = $published > 0 ? 1 : 0;
      $check = 1;
      $published_checkbox = new XoopsFormCheckBox('', 'autodate', $check);
      $published_checkbox->addOption(1, _WSA_SETDATETIME);
      $option_tray->addElement($published_checkbox);
      $published = time()-600; 
      $option_tray->addElement(new XoopsFormDateTime(_WSA_SETDATETIME, 'publish_date', 15, $published));	
     } else {
      //** LAST MODIFY
      $published = !$published ? $date : $published;
      $option_tray->addElement(new XoopsFormLabel("<br /><b>"._WSA_LASTMODIFY."</b>",formatTimestamp($published,"m")."<br />"));
      $published = time();
      $sform->addElement(new XoopsFormHidden('autodate',0), false);
      $sform->addElement(new XoopsFormHidden('publish_date',$published), false);
      $sform->addElement(new XoopsFormHidden('published',$published), false);
     }

     //EXPIRATION DATE

     $check = $expired > 0 ? 1 : 0;
     $expired_checkbox = new XoopsFormCheckBox('', 'autoexpire', $check);
     $expired_checkbox->addOption(1, _WSA_SETEXPDATETIME);
     $option_tray->addElement($expired_checkbox);
     $option_tray->addElement(new XoopsFormDateTime(_WSA_SETEXPDATETIME, 'expiry_date', 15, $expired));
     $sform->addElement($option_tray);
     
     //** STATUS SWITCH
     $statusswitch = 1;
     if($op == "modLinkS"){
       if ($status < 1){
          $statusswitch = 0; 
       }
       $sform->addElement(new XoopsFormRadioYN("<b>"._WSA_STATUSSWITCH."</b>", 'statusswitch', $statusswitch, _WSA_ONLINE, _WSA_OFFLINE));
     } else {     
       $sform->addElement(new XoopsFormHidden('statusswitch',1), false);   
     }
      //** ENTRY INFO
      // 'description|logo|credits|statistics|rate|submitter|popup|tag|sitelink|feedlink|feeddata|trackdata|downloadlink'
      // dsc|logo|cred|stat|rate|sbmt|pop|tag|site|feed|feeddata|trackdata|down  
      $entryinfo_tray = new XoopsFormElementTray("<b>"._WSA_ENTRYINFO."</b><br /><span style=\"font-size:90%; font-weight: 500;\">"._WSA_ENTRYINFO_DSC."</span>","" );
      // example XoopsFormSelect($caption, $name, $value=null, $size=1, $multiple=false, $id="")
      $showitem=new XoopsFormSelect('', 'entryinfo', $entryinfo, 14, true, $entryinfo);   
      $showitem->addOption('dsc',_WSA_BLOCKDESCRIPTION,false);		   
      $showitem->addOption('logo',_WSA_LOGOIMAGE,false);
      $showitem->addOption('cred',_WSA_CREDITS,false);
      $showitem->addOption('stat',_WSA_STATISTICS,false);
      $showitem->addOption('rate',_WSA_RATING,false);
      $showitem->addOption('sbmt',_WSA_SUBMITTER,false); 
      $showitem->addOption('pop',_WSA_POPUP,false); 
      $showitem->addOption('tag',_WSA_TAGS,false); 
      $showitem->addOption('site',_WSA_WEBSITE,false);
      if($listtype == "single"){
         $showitem->addOption('down',_WSA_DOWNLOAD,false);
      }elseif($listtype == "feed" || $listtype == "dir"){
         $showitem->addOption('feed',_WSA_WEBFEED,false);
         $showitem->addOption('feeddata',_WSA_FEEDDATA,false);
         $showitem->addOption('trackdata',_WSA_TRACKDATA,false);
      }
      $entryinfo_tray->addElement($showitem, true);
      $sform->addElement($entryinfo_tray);

      //**PERMISSIONS
/* Under construction.  Intersect cat view perms and entry view perms to set group list
      //Category view perm
      $member_handler = & xoops_gethandler('member');
      $group_list = &$member_handler->getGroupList();
      $gperm_handler = &xoops_gethandler('groupperm');
      $full_list = array_keys($group_list);
      $groups_ids = array();
      $groups_ids = $gperm_handler->getGroupIds('webshow_view', $cid, $xoopsModule->getVar('mid'));
      //$groups_ids = array_values($groups_ids);
      $ws_entryperm = array_intersect($ws_entryperm,$group_ids);
*/
      //View Entry Perm
      $sform -> addElement(new XoopsFormSelectGroup(_WSA_ENTRYPERM, "ws_entryperm", true, $ws_entryperm, 5, true));
      //Download Perm
      if ($listtype == "embed" || $listtype == "feed"){    
         $sform->addElement(new XoopsFormHidden('ws_entrydownperm',''), false);
      }else{
         $sform -> addElement(new XoopsFormSelectGroup(_WSA_ENTRYPERMDOWN, "ws_entrydownperm", true, $ws_entrydownperm, 5, true));
      }
   }else{
      // Entry Info
      $entryinfo = implode(" ", $entryinfo);
      if($listtype != "single"){
         $entryinfo = str_replace("down", "", $entryinfo);
      }
      if($listtype != "feed" || $listtype != "dir"){
         $entryinfo = str_replace("feed", "", $entryinfo);
      }
      $sform->addElement(new XoopsFormHidden('entryinfo',$entryinfo), false);   

      //Hidden Entry Perm
      $sform->addElement(new XoopsFormHidden('ws_entryperm',implode(" ", $ws_entryperm)), false);

      //Hidden Download Perm
      if ($listtype == "embed" || $listtype == "feed"){ 
          $sform->addElement(new XoopsFormHidden('ws_entrydownperm',''), false);
      } else {
         $sform->addElement(new XoopsFormHidden('ws_entrydownperm',implode(" ", $ws_entrydownperm)), false);
      }
   }
       
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
   $sform->addElement(new XoopsFormHidden('submit', 1), false);
   $sform->addElement(new XoopsFormHidden('op', $op), false);
   $sform->addElement(new XoopsFormHidden('lid', $lid), false);
   $sform->addElement(new XoopsFormHidden('submitter', $submitter), false);
   $sform->addElement(new XoopsFormHidden('status', $status), false);

   //** Submit buttons
   $button_tray = new XoopsFormElementTray('','');
   $submit_btn = new XoopsFormButton('', 'post', $btnlabel, 'submit');
   $button_tray->addElement($submit_btn);
   if($op == "modLinkS"){
      $delete_btn = "<input type=\"button\" value=\""._DELETE."\" onClick=\"location='".XOOPS_URL."/modules/".$xoopsModule->getVar('dirname')."/admin/index.php?lid=$lid&amp;op=delLink'\">";
      $button_tray->addElement(new XoopsFormLabel('   ',$delete_btn));
   }
   $sform->addElement($button_tray);
}
?>