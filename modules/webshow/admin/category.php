<?php
// $Id: admin/category.php,v.50 2007/03/01 19:59:00 tcnet Exp $ //
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
include '../../../include/cp_header.php';
if ( file_exists("../language/".$xoopsConfig['language']."/main.php") ) {
	include "../language/".$xoopsConfig['language']."/main.php";
} else {
	include "../language/english/main.php";
}

function menuCat($case)
{
   global $xoopsDB, $xoopsModuleConfig, $xoopsModule;
   if(!isset($_POST['cid'])) {
      $cid = isset($_GET['cid']) ? intval($_GET['cid']) : '0';
   } else {
      $cid = intval($_POST['cid']);
   }
   $modcid = $cid;  
   $show = isset($_GET['show']) ? intval($_GET['show']): ($xoopsModuleConfig['perpage'] * 2);
   $min = isset($_GET['min']) ? intval($_GET['min']) : 0;
   if (!isset($max)) {
        $max = $min + $show;
   }
   if(isset($_GET['orderby'])) {
       $orderby = convertorderbyin($_GET['orderby']);
   } else {
       $orderby = "cattitle ASC";
   }
   $orderbyTrans = convertorderbytrans($orderby);
   $lang_cursortedby= sprintf(_WS_CURSORTEDBY, convertorderbytrans($orderby));
   echo "<table class='outer' style ='width: 99%; font-size: 90%;'>
   <tr><th colspan=\"6\">
      <h3 style=\"float: left; width: 50%;\">"._WSA_CATMGMT."</h3>    
      <form style=\"float: right; text-align: right;width: 50%;\" name=\"sortform\" id=\"sortform\">      
      <select name=\"orderby\" onChange=\"location = this.options[this.selectedIndex].value;\">
        <option value=\"\">".$lang_cursortedby."</option>
        <option value=\"category.php?op=".$case."&amp;orderby=cattitleA&amp;cid=".$cid.";\">"._WS_CATTITLEATOZ."</option>
        <option value=\"category.php?op=".$case."&amp;orderby=cattitleD&amp;cid=".$cid."\">"._WS_CATTITLEZTOA."</option>
        <option value=\"category.php?op=".$case."&amp;orderby=cidD&amp;cid=".$cid.";\">"._WS_LIDHTOL."</option>
        <option value=\"category.php?op=".$case."&amp;orderby=cidA&amp;cid=".$cid.";\">"._WS_LIDLTOH."</option>
        <option value=\"category.php?op=".$case."&amp;orderby=pidD&amp;cid=".$cid.";\">"._WS_PARENTHTOL."</option>
        <option value=\"category.php?op=".$case."&amp;orderby=pidA&amp;cid=".$cid.";\">"._WS_PARENTLTOH."</option>
      </select>
      <input type=\"button\" value=\""._WSA_ADDNEWCAT."\" onClick=\"location='category.php?op=newCat'\">
      </form>
      <input type=\"text\" value=\"".$cid."\" size=\"8\" maxlength=\"8\" name=\"getid\">
      <input type=\"button\" value=\""._WSA_GETID."\" onClick=\"location='category.php?op=modCat&amp;cid='+getid.value\">       
   </th></tr>
   <tr><td>
     <div style=\"height: 150px; width: 100%; overflow: auto;\">
       <table border=\"1\" cellspacing='0' cellpadding='1' style=\"font-size: 85%; width: 96%;\">
          <tr class=\"head\" style=\"font-weight: 600;\">
             <td>"._WSA_ACTION."</td>
             <td>"._WSA_ID."</td>
             <td>"._WSA_CATEGORY."</td>
             <td>"._WSA_PARENT."</td>
             <td>"._WSA_ENTRYCOUNT."</td>
             <td>"._IMAGE."</td>
          </tr>";
    $sql="SELECT cid, pid, cattitle, imgurl FROM ".$xoopsDB->prefix('webshow_cat')." order by $orderby";
    $catresult=$xoopsDB->query($sql,$show,$min);	
    while(list($cid,$pid,$cattitle,$imgurl) = $xoopsDB->fetchRow($catresult)) {
       //** Count Entries in Category
        $entrycountresult=$xoopsDB->query("select count(*) from ".$xoopsDB->prefix("webshow_links")." where cid=$cid");
        list($entrycount) = $xoopsDB->fetchRow($entrycountresult);
        $catimgurl = !empty($imgurl) ? "<img src=\"../images/category/".$imgurl."\">" :"<img src=\"../images/blank.gif\">";
        if (!$pid) {
           $parentpath = "---";
        }else{
           //$cattree = new XoopsTree($xoopsDB->prefix("webshow_cat"),"cid","cattitle");
           //$parentpath = $cattree->getNicePathFromId($pid, "cattitle", "category.php?op=modCat");
           $sql="SELECT cattitle FROM ".$xoopsDB->prefix('webshow_cat')." WHERE cid =$pid;";
           $subcatresult=$xoopsDB->query($sql);
           list($subcattitle) = $xoopsDB->fetchRow($subcatresult);          
           $parentpath = "<a href=\"../playcat.php?cid=$pid\" onClick=\"location='../playcat.php?cid=$pid'\" title=\""._WSA_VIEWFORM_DESC."\">".$subcattitle."</a>";
     }
        //** MODCID Highlights the entry that is active in the editor
        if ($cid != $modcid) {		   
		   echo "<tr class='even'>";
	    }else{
   	       echo "<tr class='odd' style='background-color: #FFFFFF;'>";
   	    }
        echo "   <td width=\"12%\">
                     <a href=\"category.php?op=modCat&amp;cid=$cid\" onClick=\"location='category.php?op=modCat&amp;cid=$cid'\" title=\""._WS_MODIFY."\"><img src=\"../images/modify.gif\"></a>
   	                 <a href=\"category.php?op=delCat&amp;cid=$cid\" onClick=\"location='category.php?op=delCat&amp;cid=$cid'\" title=\""._DELETE."\"><img src=\"../images/delete.gif\"></a>
                     <a href=\"../playcat.php?cid=$cid\" onClick=\"location='../playcat.php?cid=$cid'\" title=\""._WSA_VIEWFORM_DESC."\"><img src=\"../images/link.gif\"></a>
   	              </td>                 
                  <td>
                     <a href=\"category.php?op=modCat&amp;cid=$cid\" onClick=\"location='category.php?op=modCat&amp;cid=$cid'\" title=\""._WS_MODIFY."\">".$cid."</a>
                  </td>
                  <td><a href=\"../playcat.php?cid=$cid\" onClick=\"location='../playcat.php?cid=$cid'\" title=\""._WSA_VIEWFORM_DESC."\">".$cattitle."</a>
                  </td>
                  <td>".$parentpath."</td>
                  <td><a href=\"category.php?op=entryCat&amp;cid=$cid\" onClick=\"location='category.php?op=entryCat&amp;cid=$cid'\" title=\""._WSA_VIEWFORM_DESC."\">".$entrycount."</a></td>
                  <td>".$catimgurl."</td></tr>";
      }
   echo "</table></div>";
   //** Page Nav
   $fullcountresult=$xoopsDB->query("select count(*) from ".$xoopsDB->prefix("webshow_cat")." where cid>0");
   list($numrows) = $xoopsDB->fetchRow($fullcountresult);
   $page_nav = _WSA_PAGENAV;   
   $orderby = convertorderbyout($orderby);
   //Calculates how many pages exist.  Which page one should be on, etc...
   $linkpages = ceil($numrows / $show);
   //Page Numbering
   if ($linkpages!=1 && $linkpages!=0) {
      $prev = $min - $show;
      if ($prev>=0) {
          $page_nav .= "<a href='category.php?op=".$case."&amp;cid=$cid&amp;min=$prev&amp;orderby=$orderby&amp;show=$show'><b><u>&laquo;</u></b></a>&nbsp;";
      }
      $counter = 1;
      $currentpage = ($max / $show);
      while ( $counter<=$linkpages ) {
         $mintemp = ($show * $counter) - $show;
         if ($counter == $currentpage) {
             $page_nav .= "<b>($counter)</b>&nbsp;";
         } else {
             $page_nav .= "<a href='category.php?op=".$case."&amp;cid=$cid&amp;min=$mintemp&amp;orderby=$orderby&amp;show=$show'>$counter</a>&nbsp;";
         }
         $counter++;
      }
      if ( $numrows>$max ) {
         $page_nav .= "<a href='category.php?op=".$case."&amp;cid=$cid&amp;min=$max&amp;orderby=$orderby&amp;show=$show'>";
         $page_nav .= "<b><u>&raquo;</u></b></a>";
      }
   }
   echo $page_nav."</td></tr></table>";
}

//** entryCat gets a list of entries in the category
function entryCat($cid)
{
   global $xoopsDB, $xoopsModuleConfig, $xoopsModule;

    $cid = !$cid ? redirect_header("javascript:history.go(-1)", 3, _WS_NOTEXIST): $cid;
    //** Get the category data
    $sql="SELECT cid, pid, cattitle FROM ".$xoopsDB->prefix('webshow_cat')." WHERE cid = $cid";
    $catresult=$xoopsDB->query($sql);	
    list($cid,$pid,$cattitle) = $xoopsDB->fetchRow($catresult);
        if (!$pid) {
           $parentpath = "---";
        }else{
           $sql="SELECT cattitle FROM ".$xoopsDB->prefix('webshow_cat')." WHERE cid =$pid;";
           $subcatresult=$xoopsDB->query($sql);
           list($subcattitle) = $xoopsDB->fetchRow($subcatresult);          
           $parentpath = "<a href=\"../playcat.php?cid=$pid\" onClick=\"location='../playcat.php?cid=$pid'\" title=\""._WSA_VIEWFORM_DESC."\">".$subcattitle."</a>";
        }

echo "<table class='outer' style ='width: 99%; font-size: 90%;'>
   <tr><th colspan=\"5\">
      <h3 style=\"float: left; width: 50%;\">"._WSA_CATEGORY." "._WSA_ENTRYCOUNT."</h3>   
   </th></tr>
   <tr><td><div style=\"height: 150px; width: 100%; overflow: auto;\">
       <table border=\"1\" cellspacing='0' cellpadding='1' style=\"font-size: 85%; width: 96%;\">
          <tr class=\"head\" style=\"font-weight: 600;\">
             <td>"._WSA_ACTION."</td>
             <td>"._WSA_ID."</td>
             <td>"._WSA_MEDIAENTRY."</td>
             <td>"._WSA_CATID."</td>
             <td>"._WSA_CATPATH."</td>
          </tr>";

       //** Get the Entries in Category
        $entryresult=$xoopsDB->query("select lid, title from ".$xoopsDB->prefix("webshow_links")." where cid = $cid");
        while(list($lid, $title) = $xoopsDB->fetchRow($entryresult))  {
     
   echo "<tr class='odd' style='background-color: #FFFFFF;'>
                  <td width=\"12%\">
                     <a href=\"category.php?op=modCat&amp;cid=$cid\" onClick=\"location='category.php?op=modCat&amp;cid=$cid'\" title=\""._WS_MODIFY."\"><img src=\"../images/modify.gif\"></a>
   	                 <a href=\"category.php?op=delCat&amp;cid=$cid\" onClick=\"location='category.php?op=delCat&amp;cid=$cid'\" title=\""._DELETE."\"><img src=\"../images/delete.gif\"></a>
                     <a href=\"../playcat.php?cid=$cid\" onClick=\"location='../playcat.php?cid=$cid'\" title=\""._WSA_VIEWFORM_DESC."\"><img src=\"../images/link.gif\"></a>
   	              </td>                 
                  <td>
                     <a href=\"index.php?op=modLink&amp;lid=$lid\" onClick=\"location='index.php?op=modLink&amp;lid=$lid'\" title=\""._WS_MODIFY."\">".$lid."</a>
                  </td>
                  <td>
                     <a href=\"../singlelink.php?lid=$lid\" onClick=\"location='../singlelink.php?lid=$lid'\" title=\""._WS_MODIFY."\">".$title."</a>
                  </td>
                  <td>
                     <a href=\"category.php?op=modCat&amp;cid=$cid\" onClick=\"location='category.php?op=modCat&amp;cid=$cid'\" title=\""._WS_MODIFY."\">".$cid."</a>
                  </td>
                  <td><a href=\"../playcat.php?cid=$cid\" onClick=\"location='../playcat.php?cid=$cid'\" title=\""._WSA_VIEWFORM_DESC."\">".$cattitle."</a><br />
                  ".$parentpath."
                  </td>
          </tr>";
        }
   echo "</table></div></td></tr></table>";
}

//** MAIN CATEGORY ADMIN PAGE
function newCat()
{
	global $xoopsDB, $xoopsConfig, $xoopsModule, $xoopsModuleConfig;
	xoops_cp_header();
	include '../include/functions.php';
	include_once XOOPS_ROOT_PATH."/class/xoopsformloader.php";	
	include_once XOOPS_ROOT_PATH."/class/xoopslists.php";
	include_once XOOPS_ROOT_PATH."/include/xoopscodes.php";
	include_once XOOPS_ROOT_PATH.'/class/module.errorhandler.php';
	$eh = new ErrorHandler;
	$myts =& MyTextSanitizer::getInstance();
	adminmenu(1);
	menuCat("newCat");
	$cattitle ='';
	$imgurl = '';
	$catdesc = '';
	$catbody = '';
	$pid = 0;
	echo "<h3>"._WSA_CATEGORY." "._WSA_EDITOR."</h3>";			
	// Add a New Main Category
      $formlabel = _WSA_ADDNEWCAT;			
	  echo "<table class='outer'><tr><td>";						
      $cform = new XoopsThemeForm($formlabel, "categoryform", XOOPS_URL.'/modules/'.$xoopsModule->getVar('dirname').'/admin/category.php', 'post');
	  $cform->setExtra('enctype="multipart/form-data"');
	  $cform->addElement(new XoopsFormText("<b>"._WSA_CATTITLE."</b>", 'cattitle', 33, 100, $cattitle), true);
      //** Description
      $cform->addElement(new XoopsFormTextArea("<b>"._WSA_CATDESC."</b>", 'catdesc',$catdesc,3,10), false);				  	    
      //** IMAGE code borrowed from Article module by phppp
	   $image_option_tray = new XoopsFormElementTray("<b>"._WSA_CATIMGUPLOAD."</b>", "<br />");
	   $image_option_tray->addElement(new XoopsFormFile("", "userfile",""));
	   $cform->addElement($image_option_tray);
	   unset($image_tray);
	   unset($image_option_tray);
	   $path_catimg = "modules/".$xoopsModule->getVar('dirname')."/images/category";
	   $path_catimgdsc = sprintf(_WSA_CATIMG_DSC,$path_catimg);
	   // $path_catimg = XOOPS_ROOT_PATH . "/".$xoopsModuleConfig['path_catimg']
	   $image_option_tray = new XoopsFormElementTray("<b>"._WSA_CATIMGSELECT."</b><br />".$path_catimgdsc."<br />".$path_catimg, "<br />");
	   $image_array =& XoopsLists::getImgListAsArray(XOOPS_ROOT_PATH."/".$path_catimg."/");
	   array_unshift($image_array, _NONE);
	   $image_select = new XoopsFormSelect("", "imgurl", $imgurl);
	   $image_select->addOptionArray($image_array);
	   $image_select->setExtra("onchange=\"showImgSelected('img', 'imgurl', '/".$path_catimg."/', '', '" . XOOPS_URL . "')\"");
	   $image_tray = new XoopsFormElementTray("", "&nbsp;");
	   $image_tray->addElement($image_select);
	      if (!empty($imgourl) && file_exists(XOOPS_ROOT_PATH . "/" .$path_catimg."/" . $imgurl)){
	       $image_tray->addElement(new XoopsFormLabel("", "<div style=\"padding: 4px;\"><img src=\"" . XOOPS_URL . "/" .$path_catimg."/" . $imgurl . "\" name=\"img\" id=\"img\" alt=\"\" /></div>"));
	      }else{
	       $image_tray->addElement(new XoopsFormLabel("", "<div style=\"padding: 4px;\"><img src=\"" . XOOPS_URL . "/" .$path_catimg."/blank.gif\" name=\"img\" id=\"img\" alt=\"\" /></div>"));
	      }
	   $image_option_tray->addElement($image_tray);
	   $cform->addElement($image_option_tray);

    //**Category Select
	$parent_tray = new XoopsFormElementTray("<b>"._WSA_PARENT."</b><br /><span style=\"font-size:90%; font-weight: 500;\">"._WSA_PARENT_DSC."</span>","" );
	// example XoopsFormSelect($caption, $name, $value=null, $size=1, $multiple=false, $id="")
	$catsel=new XoopsFormSelect('', 'pid', $pid, 1, false, $pid);   
	$catsel->addOption('0','----',false);
	$sql = "SELECT cid, cattitle FROM ".$xoopsDB->prefix('webshow_cat')." WHERE cid>0 ORDER BY cattitle";
	$result = $xoopsDB->query($sql);
	while (list($parentcid, $parentcattitle) = $xoopsDB->fetchRow($result) ) {
	   $sel="false";
	   if($parentcid == $pid){
	      $sel = "true";
	   }
	   $catsel->addOption($parentcid,$parentcattitle,$sel);
	}		   
	$parent_tray->addElement($catsel, true);
	$cform->addElement($parent_tray);	

    //** CAT Body Text
    // Upload class/xoopseditors and Frameworks To use html editors in 2.0.* or 2.2.* 
    if(@(XOOPS_ROOT_PATH."/class/xoopseditor/xoopseditor.php")) {
       //Comment OUT the next three lines if it conflict with your modified formloader
       if(file_exists(XOOPS_ROOT_PATH."/Frameworks/xoops22/class/xoopsformloader.php")) {
          !@include_once XOOPS_ROOT_PATH."/Frameworks/xoops22/class/xoopsformloader.php";
       }
       $editor = strtolower($xoopsModuleConfig["texteditor"]);
       // options for the editor
       //required configs
       $options['name'] ='catbody';
       //$options['value'] = empty($_REQUEST['catbody'])? "" : $_REQUEST['catbody'];
       $options['value'] = $catbody;
       //optional configs
       $options['rows'] = 25; // default value = 5
       $options['cols'] = 60; // default value = 50
       $options['width'] = '550px'; // default value = 100%
       $options['height'] = '400px'; // default value = 400px
       $editor_configs = $options;
       $cform->addElement(new XoopsFormEditor(_WSA_TEXTBODY, $editor, $editor_configs, $nohtml = false, $onfailure = "textarea"), false);
    }else{
       //** example XoopsFormDhtmlTextArea($caption, $name, $value, $rows=5, $cols=50, $hiddentext="xoopsHiddenText")
       $cform->addElement(new XoopsFormDhtmlTextArea("<b>"._WSA_TEXTBODY."</b>", 'catbody',$catbody,10,10), false);	
    }

	// Permissions from Herve Thoussard News Module
    $member_handler = & xoops_gethandler('member');
    $group_list = &$member_handler->getGroupList();
    $gperm_handler = &xoops_gethandler('groupperm');
    $full_list = array_keys($group_list);

	$groups_ids = array();
    $groups_webshow_can_approve_checkbox = new XoopsFormCheckBox(_WSA_APPROVEFORM, 'groups_webshow_can_approve[]', $full_list);
    $groups_webshow_can_approve_checkbox->addOptionArray($group_list);
    $cform->addElement($groups_webshow_can_approve_checkbox);

	$groups_ids = array();
    $groups_webshow_can_submit_checkbox = new XoopsFormCheckBox(_WSA_SUBMITFORM, 'groups_webshow_can_submit[]', $full_list);
    $groups_webshow_can_submit_checkbox->addOptionArray($group_list);
    $cform->addElement($groups_webshow_can_submit_checkbox);

	$groups_ids = array();
    $groups_webshow_can_view_checkbox = new XoopsFormCheckBox(_WSA_VIEWFORM, 'groups_webshow_can_view[]', $full_list);
    $groups_webshow_can_view_checkbox->addOptionArray($group_list);
    $cform->addElement($groups_webshow_can_view_checkbox);

	//** Hidden variables     
      $cform->addElement(new XoopsFormHidden('op', 'addCat'), false);	
      //** Submit buttons
      $btnlabel = _ADD;
	$button_tray = new XoopsFormElementTray('' ,'');	
	$submit_btn = new XoopsFormButton('', 'post', $btnlabel, 'submit');
	$button_tray->addElement($submit_btn);
	$cform->addElement($button_tray);
	$cform->display();
	echo "</td></tr></table>";
	xoops_cp_footer();
}

function addCat()
{
	global $xoopsDB, $xoopsModule;
	include_once XOOPS_ROOT_PATH."/class/uploader.php";
    include_once XOOPS_ROOT_PATH.'/class/module.errorhandler.php';
    $myts =& MyTextSanitizer::getInstance();
    $eh = new ErrorHandler;
    $pid = isset($_POST['pid']) ? intval($_POST['pid']) : 0;

	$cattitle = $myts->addSlashes($_POST["cattitle"]);
	if (empty($cattitle)) {
	  redirect_header("category.php",2,_WSA_ERRORTITLE);
	  exit();
	}

	$catdesc = $myts->addSlashes($_POST["catdesc"]);
	$catbody = $myts->addSlashes($_POST["catbody"]);

	//** IMAGE UPLOAD
	$imgurl = "";
	$maxfilesize = 30000;
	$maxfilewidth = 128;
	$maxfileheight = 128;
	if (!empty($_FILES['userfile']['name'])) { 
	  $allowed_mimetypes = array('image/gif', 'image/jpeg', 'image/pjpeg', 'image/x-png', 'image/png');
	  //$uploader = new XoopsMediaUploader('/home/xoops/uploads', $allowed_mimetypes, $maxfilesize, $maxfilewidth, $maxfileheight);
	  $uploader = new XoopsMediaUploader(XOOPS_ROOT_PATH ."/modules/".$xoopsModule->getVar('dirname')."/images/category/", $allowed_mimetypes, $maxfilesize, $maxfilewidth, $maxfileheight);
      if ($uploader->fetchMedia($_POST['xoops_upload_file'][0])) {
         if (!$uploader->upload()) {
            echo $uploader->getErrors();
         } else {
            echo '<h4>'._WSA_FILESUCCESS.'</h4>';
            $imgurl = $uploader->getSavedFileName();
         }
      } else {
 	     echo $uploader->getErrors();
      }
	}
	$imgurl = empty($imgurl)?(empty($_POST['imgurl'])?"":$_POST['imgurl']):$imgurl;

	$newid = $xoopsDB->genId($xoopsDB->prefix("webshow_cat")."_cid_seq");
	$sql = sprintf("INSERT INTO %s (cid, pid, cattitle, catdesc, imgurl, catbody) VALUES (%u, %u, '%s', '%s', '%s', '%s')", $xoopsDB->prefix("webshow_cat"), $newid, $pid, $cattitle, $catdesc, $imgurl, $catbody);
	$xoopsDB->query($sql) or $eh->show("0013");
    if ($newid == 0) {
       $newid = $xoopsDB->getInsertId();
    }

	// Permissions
	$gperm_handler = &xoops_gethandler('groupperm');
	if(isset($_POST['groups_webshow_can_approve'])) {
		foreach($_POST['groups_webshow_can_approve'] as $onegroup_id) {
			$gperm_handler->addRight('webshow_approve', $newid, $onegroup_id, $xoopsModule->getVar('mid'));
		}
	}

	if(isset($_POST['groups_webshow_can_submit'])) {
		foreach($_POST['groups_webshow_can_submit'] as $onegroup_id) {
			$gperm_handler->addRight('webshow_submit', $newid, $onegroup_id, $xoopsModule->getVar('mid'));
		}
	}

	if(isset($_POST['groups_webshow_can_view'])) {
		foreach($_POST['groups_webshow_can_view'] as $onegroup_id) {
			$gperm_handler->addRight('webshow_view', $newid, $onegroup_id, $xoopsModule->getVar('mid'));
		}
	}
 
	$tags = array();
	$tags['CATEGORY_NAME'] = $cattitle;
	$tags['CATEGORY_URL'] = XOOPS_URL . '/modules/' . $xoopsModule->getVar('dirname') . '/viewcat.php?cid=' . $newid;
	$notification_handler =& xoops_gethandler('notification');
	$notification_handler->triggerEvent('global', 0, 'new_category', $tags);
	redirect_header("category.php?cid=".$newid."&amp;op=modCat",1,_WSA_DBUPDATED);
	exit();
}

function modCat()
{
	global $xoopsDB, $xoopsModule, $xoopsModuleConfig;
	xoops_cp_header();
	include '../include/functions.php';
	include_once XOOPS_ROOT_PATH."/class/xoopsformloader.php";	
	include_once XOOPS_ROOT_PATH."/class/xoopslists.php";
	include_once XOOPS_ROOT_PATH."/include/xoopscodes.php";
	include_once XOOPS_ROOT_PATH.'/class/module.errorhandler.php';
	$eh = new ErrorHandler;
	$myts =& MyTextSanitizer::getInstance();
    $cid = isset($_GET['cid']) ? intval($_GET['cid']) : 0;
	adminmenu(1);	
	menuCat("modCat");
	echo "<h3>"._WSA_CATEGORY." "._WSA_EDITOR."</h3>";		
	$result=$xoopsDB->query("select pid, cattitle, catdesc, imgurl, catbody from ".$xoopsDB->prefix("webshow_cat")." where cid=$cid");
	list($pid,$cattitle,$catdesc,$imgurl, $catbody) = $xoopsDB->fetchRow($result);
	$cattitle = $myts->htmlSpecialChars($cattitle);
	$catdesc = $myts->htmlSpecialChars($catdesc);
	$catbody = $myts->htmlSpecialChars($catbody);
	$imgurl = $myts->htmlSpecialChars($imgurl);
	//** Modify Category
      $formlabel = _WSA_MODCAT;			
	echo "<table class='outer'><tr><td>";						
    $cform = new XoopsThemeForm($formlabel, "modcatform", XOOPS_URL.'/modules/'.$xoopsModule->getVar('dirname').'/admin/category.php', 'post');
	$cform->setExtra('enctype="multipart/form-data"');
	$cform->addElement(new XoopsFormLabel("<b>"._WSA_CATID."</b>", $cid));	
	$cform->addElement(new XoopsFormText("<b>"._WSA_CATTITLE."</b>", 'cattitle', 33, 100, $cattitle), true);	  	    
    $cform->addElement(new XoopsFormTextArea("<b>"._WSA_CATDESC."</b>", 'catdesc',$catdesc,3,10), false);	
    //** IMAGE code borrowed from Article module by phppp
    $image_option_tray = new XoopsFormElementTray("<b>"._WSA_CATIMGUPLOAD."</b>", "<br />");
    $image_option_tray->addElement(new XoopsFormFile("", "userfile",""));
    $cform->addElement($image_option_tray);
    unset($image_tray);
    unset($image_option_tray);
    $path_catimg = "modules/".$xoopsModule->getVar('dirname')."/images/category";
    // $path_catimg = XOOPS_ROOT_PATH . "/".$xoopsModuleConfig['path_catimg']
    $image_option_tray = new XoopsFormElementTray("<b>"._WSA_CATIMGSELECT."</b><br />"._WSA_CATIMG_DSC."<br />".$path_catimg, "<br />");
    $image_array =& XoopsLists::getImgListAsArray(XOOPS_ROOT_PATH."/".$path_catimg."/");
    array_unshift($image_array, _NONE);
    $image_select = new XoopsFormSelect("", "imgurl", $imgurl);
    $image_select->addOptionArray($image_array);
    $image_select->setExtra("onchange=\"showImgSelected('img', 'imgurl', '/".$path_catimg."/', '', '" . XOOPS_URL . "')\"");
    $image_tray = new XoopsFormElementTray("", "&nbsp;");
    $image_tray->addElement($image_select);
    if (!empty($imgurl) && file_exists(XOOPS_ROOT_PATH . "/" .$path_catimg."/" . $imgurl)){
       $image_tray->addElement(new XoopsFormLabel("", "<div style=\"padding: 4px;\"><img src=\"" . XOOPS_URL . "/" .$path_catimg."/" . $imgurl . "\" name=\"img\" id=\"img\" alt=\"\" /></div>"));
    }else{
       $image_tray->addElement(new XoopsFormLabel("", "<div style=\"padding: 4px;\"><img src=\"" . XOOPS_URL . "/" .$path_catimg."/blank.gif\" name=\"img\" id=\"img\" alt=\"\" /></div>"));
    }
    $image_option_tray->addElement($image_tray);
    $cform->addElement($image_option_tray);
	
    //**Category Select
	$parent_tray = new XoopsFormElementTray("<b>"._WSA_PARENT."</b><br /><span style=\"font-size:90%; font-weight: 500;\">"._WSA_PARENT_DSC."</span>","" );
	// example XoopsFormSelect($caption, $name, $value=null, $size=1, $multiple=false, $id="")
	$catsel=new XoopsFormSelect('', 'pid', $pid, 1, false, $pid);   
	$catsel->addOption('0','----',false);
	$sql = "SELECT cid, cattitle FROM ".$xoopsDB->prefix('webshow_cat')." WHERE cid>0 ORDER BY cattitle";
	$result = $xoopsDB->query($sql);
	while (list($parentcid, $parentcattitle) = $xoopsDB->fetchRow($result) ) {
	   $sel="false";
	   if($parentcid == $pid){
	      $sel = "true";
	   }
	   $catsel->addOption($parentcid,$parentcattitle,$sel);
	}		   
	$parent_tray->addElement($catsel, false);
	$cform->addElement($parent_tray);

    //** CAT Body Text
    // Upload class/xoopseditors and Frameworks To use html editors in 2.0.* or 2.2.* 
    if(@(XOOPS_ROOT_PATH."/class/xoopseditor/xoopseditor.php")) {
       //Comment OUT the next three lines if it conflict with your modified formloader
       if(file_exists(XOOPS_ROOT_PATH."/Frameworks/xoops22/class/xoopsformloader.php")) {
          !@include_once XOOPS_ROOT_PATH."/Frameworks/xoops22/class/xoopsformloader.php";
       }
       $editor = strtolower($xoopsModuleConfig["texteditor"]);
       // options for the editor
       //required configs
       $options['name'] ='catbody';
       //$options['value'] = empty($_REQUEST['catbody']) ? "" : $_REQUEST['catbody'];
       $options['value'] = $catbody;
       //optional configs
       $options['rows'] = 25; // default value = 5
       $options['cols'] = 60; // default value = 50
       $options['width'] = '550px'; // default value = 100%
       $options['height'] = '400px'; // default value = 400px
       $editor_configs = $options;
       $cform->addElement(new XoopsFormEditor(_WSA_TEXTBODY, $editor, $editor_configs, $nohtml = false, $onfailure = "textarea"), false);
    }else{
       //** example XoopsFormDhtmlTextArea($caption, $name, $value, $rows=5, $cols=50, $hiddentext="xoopsHiddenText")
       $cform->addElement(new XoopsFormDhtmlTextArea("<b>"._WSA_TEXTBODY."</b>", 'catbody',$catbody,10,10), false);	
    }

	// Permissions
    $member_handler = & xoops_gethandler('member');
    $group_list = &$member_handler->getGroupList();
    $gperm_handler = &xoops_gethandler('groupperm');
    $full_list = array_keys($group_list);

	$groups_ids = array();
    $groups_ids = $gperm_handler->getGroupIds('webshow_approve', $cid, $xoopsModule->getVar('mid'));
    $groups_ids = array_values($groups_ids);
    $groups_webshow_can_approve_checkbox = new XoopsFormCheckBox(_WSA_APPROVEFORM, 'groups_webshow_can_approve[]', $groups_ids);
    $groups_webshow_can_approve_checkbox->addOptionArray($group_list);
    $cform->addElement($groups_webshow_can_approve_checkbox);

	$groups_ids = array();
    $groups_ids = $gperm_handler->getGroupIds('webshow_submit', $cid, $xoopsModule->getVar('mid'));
    $groups_ids = array_values($groups_ids);
    $groups_webshow_can_submit_checkbox = new XoopsFormCheckBox(_WSA_SUBMITFORM, 'groups_webshow_can_submit[]', $groups_ids);
    $groups_webshow_can_submit_checkbox->addOptionArray($group_list);
    $cform->addElement($groups_webshow_can_submit_checkbox);

	$groups_ids = array();
    $groups_ids = $gperm_handler->getGroupIds('webshow_view', $cid, $xoopsModule->getVar('mid'));
    $groups_ids = array_values($groups_ids);
    $groups_webshow_can_view_checkbox = new XoopsFormCheckBox(_WSA_VIEWFORM, 'groups_webshow_can_view[]', $groups_ids);
    $groups_webshow_can_view_checkbox->addOptionArray($group_list);
    $cform->addElement($groups_webshow_can_view_checkbox);

	//** Hidden variables
	$cform->addElement(new XoopsFormHidden('cid', $cid), false);
	//$cform->addElement(new XoopsFormHidden('pid', $pid), false);		     
    $cform->addElement(new XoopsFormHidden('op', 'modCatS'), false);	

	//** Submit buttons
	$btnlabel = _WS_MODIFY;
	$button_tray = new XoopsFormElementTray('','');
	$submit_btn = new XoopsFormButton('', 'post', $btnlabel, 'submit');
	$button_tray->addElement($submit_btn);
	$delete_btn = "<input type=\"button\" value=\""._DELETE."\" onClick=\"location='category.php?pid=$pid&amp;cid=$cid&amp;op=delCat'\">";
	$button_tray->addElement(new XoopsFormLabel("",$delete_btn));
	$cancel_btn = "<input type=\"button\" value=\""._CANCEL."\" onclick=\"javascript:history.go(-1)\">";
	$button_tray->addElement(new XoopsFormLabel("",$cancel_btn));
	$cform->addElement($button_tray);			
	$cform->display(); 
	echo "</td></tr></table>";	  
	xoops_cp_footer();
}

function modCatS()
{
    global $xoopsDB, $xoopsModule, $xoopsModuleConfig;
    include_once XOOPS_ROOT_PATH."/class/uploader.php";
    include_once XOOPS_ROOT_PATH.'/class/module.errorhandler.php';
    $eh = new ErrorHandler;
    $myts =& MyTextSanitizer::getInstance();
    $cid = isset($_POST['cid']) ? intval($_POST['cid']) : '';
    $pid = isset($_POST['pid']) ? intval($_POST['pid']) : 0;
    if($pid == $cid) {
       $pid = 0;  //Category can't be a child of itself
    }

    $cattitle =  $myts->addSlashes($_POST['cattitle']);
    if (empty($cattitle)) {
        redirect_header("category.php", 2, _WSA_ERRORTITLE);
    }

    $catdesc = $myts->addSlashes($_POST["catdesc"]);
    $catbody = $myts->addSlashes($_POST["catbody"]);	

    //** IMAGE UPLOAD
    $imgurl = "";
    $maxfilesize = 30000;
    $maxfilewidth = 128;
    $maxfileheight = 128;
    if (!empty($_FILES['userfile']['name'])) { 
        $allowed_mimetypes = array('image/gif', 'image/jpeg', 'image/pjpeg', 'image/x-png', 'image/png');
        //$uploader = new XoopsMediaUploader('/home/xoops/uploads', $allowed_mimetypes, $maxfilesize, $maxfilewidth, $maxfileheight);
        $uploader = new XoopsMediaUploader(XOOPS_ROOT_PATH ."/modules/".$xoopsModule->getVar('dirname')."/images/category/", $allowed_mimetypes, $maxfilesize, $maxfilewidth, $maxfileheight);
        if ($uploader->fetchMedia($_POST['xoops_upload_file'][0])) {
            if (!$uploader->upload()) {
                echo $uploader->getErrors();
            } else {
                echo '<h4>'._WSA_FILESUCCESS.'</h4>';
                $imgurl = $uploader->getSavedFileName();
            }
        } else {
                echo $uploader->getErrors();
        }
    }
    $imgurl = empty($imgurl)?(empty($_POST['imgurl'])?"":$_POST['imgurl']):$imgurl;

	// Permissions
	$gperm_handler = &xoops_gethandler('groupperm');
	$criteria = new CriteriaCompo();
	$criteria->add(new Criteria('gperm_itemid', $cid, '='));
	$criteria->add(new Criteria('gperm_modid', $xoopsModule->getVar('mid'),'='));
	$criteria->add(new Criteria('gperm_name', 'webshow_approve', '='));
	$gperm_handler->deleteAll($criteria);

	$criteria = new CriteriaCompo();
	$criteria->add(new Criteria('gperm_itemid', $cid, '='));
	$criteria->add(new Criteria('gperm_modid', $xoopsModule->getVar('mid'),'='));
	$criteria->add(new Criteria('gperm_name', 'webshow_submit', '='));
	$gperm_handler->deleteAll($criteria);

	$criteria = new CriteriaCompo();
	$criteria->add(new Criteria('gperm_itemid', $cid, '='));
	$criteria->add(new Criteria('gperm_modid', $xoopsModule->getVar('mid'),'='));
	$criteria->add(new Criteria('gperm_name', 'webshow_view', '='));
	$gperm_handler->deleteAll($criteria);

	if(isset($_POST['groups_webshow_can_approve'])) {
		foreach($_POST['groups_webshow_can_approve'] as $onegroup_id) {
			$gperm_handler->addRight('webshow_approve', $cid, $onegroup_id, $xoopsModule->getVar('mid'));
		}
	}

	if(isset($_POST['groups_webshow_can_submit'])) {
		foreach($_POST['groups_webshow_can_submit'] as $onegroup_id) {
			$gperm_handler->addRight('webshow_submit', $cid, $onegroup_id, $xoopsModule->getVar('mid'));
		}
	}

	if(isset($_POST['groups_webshow_can_view'])) {
		foreach($_POST['groups_webshow_can_view'] as $onegroup_id) {
			$gperm_handler->addRight('webshow_view', $cid, $onegroup_id, $xoopsModule->getVar('mid'));
		}
	}

    $xoopsDB->query("update ".$xoopsDB->prefix("webshow_cat")." set pid=$pid, cattitle='$cattitle', catdesc='$catdesc', imgurl='$imgurl', catbody='$catbody' where cid=$cid") or $eh->show("0013");
    redirect_header("category.php?cid=".$cid."&amp;op=modCat",1,_WSA_DBUPDATED);
}

function delCat()
{
   	global $xoopsDB, $xoopsModule;
   	include_once XOOPS_ROOT_PATH.'/class/module.errorhandler.php';
    $eh = new ErrorHandler;
	include_once XOOPS_ROOT_PATH.'/class/xoopstree.php';
    $cattree = new XoopsTree($xoopsDB->prefix("webshow_cat"),"cid","pid");
   	$cid =  isset($_POST['cid']) ? intval($_POST['cid']) : intval($_GET['cid']);
	$ok =  isset($_POST['ok']) ? intval($_POST['ok']) : 0;
    if ( $ok == 1 ) {
		//get all subcategories under the specified category
		$arr=$cattree->getAllChildId($cid);
		$dcount=count($arr);
		for ( $i=0;$i<$dcount;$i++ ) {
			//get all links in each subcategory
			$result=$xoopsDB->query("select lid from ".$xoopsDB->prefix("webshow_links")." where cid=".$arr[$i]."") or $eh->show("0013");
			//now for each link, delete the text data and vote ata associated with the link
			while ( list($lid)=$xoopsDB->fetchRow($result) ) {
				$sql = sprintf("DELETE FROM %s WHERE lid = %u", $xoopsDB->prefix("webshow_text"), $lid);
				$xoopsDB->query($sql) or $eh->show("0013");
				$sql = sprintf("DELETE FROM %s WHERE lid = %u", $xoopsDB->prefix("webshow_votedata"), $lid);
				$xoopsDB->query($sql) or $eh->show("0013");
				$sql = sprintf("DELETE FROM %s WHERE lid = %u", $xoopsDB->prefix("webshow_links"), $lid);
				$xoopsDB->query($sql) or $eh->show("0013");
				$sql = sprintf("DELETE FROM %s WHERE lid = %u", $xoopsDB->prefix("webshow_flashvar"), $lid);
				$xoopsDB->query($sql) or $eh->show("0013");				
				xoops_comment_delete($xoopsModule->getVar('mid'), $lid);
				xoops_notification_deletebyitem($xoopsModule->getVar('mid'), 'link', $lid);
			}
            //delete notifications and permissions
			xoops_notification_deletebyitem($xoopsModule->getVar('mid'), 'category', $arr[$i]);
			
			//all links for each subcategory are deleted, now delete the subcategory data
			$sql = sprintf("DELETE FROM %s WHERE cid = %u", $xoopsDB->prefix("webshow_cat"), $arr[$i]);
			$xoopsDB->query($sql) or $eh->show("0013");
		}
		//all subcategory and associated data are deleted, now delete category data and its associated data
		$result=$xoopsDB->query("select lid from ".$xoopsDB->prefix("webshow_links")." where cid=".$cid."") or $eh->show("0013");
		while ( list($lid)=$xoopsDB->fetchRow($result) ) {
			$sql = sprintf("DELETE FROM %s WHERE lid = %u", $xoopsDB->prefix("webshow_links"), $lid);
			$xoopsDB->query($sql) or $eh->show("0013");
			$sql = sprintf("DELETE FROM %s WHERE lid = %u", $xoopsDB->prefix("webshow_text"), $lid);
			$xoopsDB->query($sql) or $eh->show("0013");
			$sql = sprintf("DELETE FROM %s WHERE lid = %u", $xoopsDB->prefix("webshow_votedata"), $lid);
			$xoopsDB->query($sql) or $eh->show("0013");
			$sql = sprintf("DELETE FROM %s WHERE lid = %u", $xoopsDB->prefix("webshow_flashvar"), $lid);
			$xoopsDB->query($sql) or $eh->show("0013");	
			// delete comments
			xoops_comment_delete($xoopsModule->getVar('mid'), $lid);
			// delete notifications
			xoops_notification_deletebyitem($xoopsModule->getVar('mid'), 'link', $lid);
		}
		$sql = sprintf("DELETE FROM %s WHERE cid = %u", $xoopsDB->prefix("webshow_cat"), $cid);
	    $xoopsDB->query($sql) or $eh->show("0013");
		xoops_notification_deletebyitem($xoopsModule->getVar('mid'), 'category', $cid);
		xoops_groupperm_deletebymoditem($xoopsModule->getVar('mid'), 'webshow_approve', $cid);
		xoops_groupperm_deletebymoditem($xoopsModule->getVar('mid'), 'webshow_submit', $cid);
		xoops_groupperm_deletebymoditem($xoopsModule->getVar('mid'), 'webshow_view', $cid);
        redirect_header("category.php",1,_WSA_CATDELETED);
		exit();
    } else {
		xoops_cp_header();
		xoops_confirm(array('op' => 'delCat', 'cid' => $cid, 'ok' => 1), 'category.php', _WSA_WARNING);
		$entrytable = entryCat($cid);
		echo $entrytable;
		xoops_cp_footer();
    }
}

if(!isset($_POST['op'])) {
	$op = isset($_GET['op']) ? $_GET['op'] : 'main';
} else {
	$op = $_POST['op'];
}

switch ($op) {
    case "menuCat":
	    menuCat();
	    break;
    case "newCat":	
	    newCat();
	    break;
    case "addCat":
	    addCat();
	    break;
    case "delCat":
	    delCat();
	    break;
    case "modCat":	
	    modCat();
    	break;
    case "modCatS":
	    modCatS();
    	break;
    case "entryCat":
        if (isset($_GET['cid'])) {
          $cid = intval($_GET['cid']);
          xoops_cp_header();
        }
	    entryCat($cid);
    	break;
    case 'main':
        default:
    	newCat();
    	break;
}
?>