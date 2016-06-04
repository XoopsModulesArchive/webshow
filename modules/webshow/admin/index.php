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
include_once XOOPS_ROOT_PATH.'/class/xoopstree.php';
include_once XOOPS_ROOT_PATH."/class/xoopslists.php";
include_once XOOPS_ROOT_PATH."/class/xoopsformloader.php";
include_once XOOPS_ROOT_PATH.'/class/module.errorhandler.php'; 
include_once XOOPS_ROOT_PATH."/include/xoopscodes.php";
include_once XOOPS_ROOT_PATH."/class/uploader.php";
$myts =& MyTextSanitizer::getInstance();
$eh = new ErrorHandler;
$cattree = new XoopsTree($xoopsDB->prefix("webshow_cat"),"cid","pid");
$linktree = new XoopsTree($xoopsDB->prefix("webshow_links"),"lid","title");
$playertree = new XoopsTree($xoopsDB->prefix("webshow_player"),"playerid","playertitle");

//** Logo Config	
$allowed_mimetypes = array('image/gif', 'image/jpeg', 'image/pjpeg', 'image/x-png', 'image/png');
$logosize = 30000;
$logowidth = $xoopsModuleConfig['logowidth'];
$logoheight = $xoopsModuleConfig['logowidth'];  //Square images
$playerlogosize = 10000;
$playerlogowidth = 48;
$playerlogoheight = 48;
$logfile = "log.txt";

//** MAIN ADMIN INDEX
function webShow()
{
global $xoopsDB, $xoopsConfig, $xoopsModule, $xoopsModuleConfig, $cattree, $playertree, $linktree, $eh;
xoops_cp_header();
adminmenu(1);
echo "<table class='outer' width='100%'><tr><th>"._WSA_ADMIN."</th></tr>";
//**Category Select	
echo "<tr class=\"odd\"><td><form method=get action=\"category.php\">";
$cattree->makeMySelBox("cattitle", "cattitle",0,1,"",'window.location="category.php?op=modCat&amp;cid="+this.value');
echo "<input type=\"button\" value=\""._WSA_ADDNEWCAT."\" onClick=\"location='category.php?op=newCat'\"></form></td></tr>";	
//** Links Select
echo "<tr class='odd'><td><form method=get action=\"index.php\">";
$linktree->makeMySelBox('title', 'title', 0, 1, '','window.location="index.php?op=modLink&amp;lid="+this.value');
echo "<input type=\"button\" value=\""._WSA_ADDMEDIA."\" onClick=\"location='index.php?op=newLink'\"> <input type=\"button\" value=\""._WSA_LOGFILE."\" onClick=\"location='index.php?op=showLog'\"></form></td></tr>";		
//**Player Select 		
echo "<tr class='odd'><td><form method=get action=\"player.php\">";
$playertree->makeMySelBox("playertitle","playertitle",0,1,"",'window.location="player.php?op=modPlayer&amp;playerid="+this.value');
echo "<input type=\"button\" value=\""._WSA_ADDNEWPLAYER."\" onClick=\"location='player.php?op=newPlayer'\"></form></td></tr>";
//** Check Paths - code from PHPPP Article Module
echo "<tr class='odd'><td><div style='padding: 8px;'>";
$path_logo = XOOPS_ROOT_PATH . '/' . $xoopsModuleConfig['path_logo'];
$path_status = ws_admin_getPathStatus($path_logo);
echo "<label>" ._WSA_PATH_LOGO .": </label><text>". $path_logo . ' ( ' . $path_status . ' )';
echo "</text><br />";
$path_media = XOOPS_ROOT_PATH."/modules/".$xoopsModule->getVar('dirname')."/".$xoopsModuleConfig['path_media'];
$path_status = ws_admin_getPathStatus($path_media);
echo "<label>" ._WSA_PATH_MEDIA .": </label><text>". $path_media . ' ( ' . $path_status . ' )';
echo "</text><br />";
$path_playlist = XOOPS_ROOT_PATH."/modules/".$xoopsModule->getVar('dirname')."/playlist";
$path_status = ws_admin_getPathStatus($path_playlist);
echo "<label>" ._WSA_PATH_PLAYLIST .": </label><text>". $path_playlist . " ( " . $path_status . " ) </text><br />";

// Check for XOOPS version 2.3
$xv=str_replace('XOOPS ','',XOOPS_VERSION);
$x23 = substr($xv,2,1);

if ($x23 != 3) {    
   $path_frameworks = XOOPS_ROOT_PATH."/Frameworks";
   $path_status = ws_admin_getAddonStatus($path_frameworks);
   echo "<label>" ._WSA_PATH_FRAMEWORKS .": </label><text>". $path_frameworks . " ( " . $path_status . " ) </text><br />";

   $path_xoopseditor = XOOPS_ROOT_PATH."/class/xoopseditor/xoopseditor.php";
   $path_status = ws_admin_getAddonStatus($path_xoopseditor);
   echo "<label>" ._WSA_PATH_XOOPSEDITOR .": </label><text>". $path_xoopseditor . " ( " . $path_status . " ) </text><br />";
}

$path_tagmodule = XOOPS_ROOT_PATH."/modules/tag/class/tag.php";
$path_status = ws_admin_getAddonStatus($path_tagmodule);
echo "<label>" ._WSA_PATH_TAGMODULE .": </label><text>". $path_tagmodule . " ( " . $path_status . " ) </text><br />";
echo "</div></td></tr>";
//** Playlist Administration
$result = $xoopsDB->query("select count(*) from ".$xoopsDB->prefix("webshow_broken")."");
list($totalbrokenlinks) = $xoopsDB->fetchRow($result);
if($totalbrokenlinks>0){
	$totalbrokenlinks = "<span style='color: #ff0000; font-weight: bold'>$totalbrokenlinks</span>";
	}
$result2 = $xoopsDB->query("select count(*) from ".$xoopsDB->prefix("webshow_mod")."");
list($totalmodrequests) = $xoopsDB->fetchRow($result2);
if($totalmodrequests>0){
	$totalmodrequests = "<span style='color: #ff0000; font-weight: bold'>$totalmodrequests</span>";
	}
$result3 = $xoopsDB->query("select count(*) from ".$xoopsDB->prefix("webshow_links")." where status=0");
list($totalnewlinks) = $xoopsDB->fetchRow($result3);
if($totalnewlinks>0){
   $totalnewlinks = "<span style='color: #ff0000; font-weight: bold'>$totalnewlinks</span>";
}
echo "<tr class='odd'><td><a href=index.php?op=listNewLinks>"._WSA_LINKSWAITING." ($totalnewlinks)</a> | <a href=index.php?op=listBrokenLinks>"._WSA_BROKENREPORTS." ($totalbrokenlinks)</a> | <a href=index.php?op=listModReq>"._WSA_MODREQUESTS." ($totalmodrequests)</a><br /></td></tr>";
//$result4 =$xoopsDB->query("select count(*) from ".$xoopsDB->prefix("webshow_links")." where status>0");
//list($numrows) = $xoopsDB->fetchRow($result4);
//echo "<tr class='even'><td>".printf(_WSA_THEREARE,$numrows)."</td></tr>";		

//** Maintenance Table
//** ADMIN Table
$admintable = "";
$admintable = wsadminTable('');
if($admintable) {
echo "<tr><td>$admintable</td></tr>";
}

//** MESSAGES
echo "<tr><td>"._WSA_WEBSHOW_DISCLAIMER."<br /><br />"._WSA_WEBSHOW_DSC." "._WSA_JWFLASH."<br /><br />"._WSA_WEBSHOW_GETSTARTED."</td></tr>";
xoops_cp_footer();
}

function menuLink($case)
{
   global $xoopsDB, $xoopsModuleConfig, $xoopsModule, $myts, $eh, $cattree, $playertree, $logfile;
   autoPublish();
   player1Exist(); //check to see if the default player exists in the db.
   $lid = isset($_GET['lid']) ? intval($_GET['lid']) : 0;	
   $modlid = $lid; //** MODID Highlights the entry that is active in the editor
   $op = isset($_GET['op']) ? $myts->htmlSpecialChars($_GET['op']) : '';

//** GET QUERY START and number to show
   $show = isset($_GET['show']) ? intval($_GET['show']): ($xoopsModuleConfig['perpage'] * 2);
   $min = isset($_GET['min']) ? intval($_GET['min']) : 0;
   if (!isset($max)) {
        $max = $min + $show;
   }
   if(isset($_GET['orderby'])) {
       $orderby = convertorderbyin($_GET['orderby']);
   } else {
       $orderby = "lid DESC";
   }
   $orderbyTrans = convertorderbytrans($orderby);
   $lang_cursortedby= sprintf(_WS_CURSORTEDBY, convertorderbytrans($orderby));  
   
   print "<table class='outer' style ='width: 100%; font-size: 90%;'>
   <tr><th colspan=\"14\"><h3>"._WSA_LINKMGMT."</h3>
    <form style=\"float: left; width: 60%;\" name=\"sortform\" id=\"sortform\">      
      <select name=\"orderby\" onChange=\"location = this.options[this.selectedIndex].value;\">
        <option value=\"\">".$lang_cursortedby."</option>
        <option value=\"index.php?op=".$case."&amp;orderby=titleA&amp;lid=".$lid.";\">"._WS_TITLEATOZ."</option>
        <option value=\"index.php?op=".$case."&amp;orderby=titleD&amp;lid=".$lid."\">"._WS_TITLEZTOA."</option>
        <option value=\"index.php?op=".$case."&amp;orderby=dateD&amp;lid=".$lid.";\">"._WS_DATENEW."</option>
        <option value=\"index.php?op=".$case."&amp;orderby=dateA&amp;lid=".$lid.";\">"._WS_DATEOLD."</option>
        <option value=\"index.php?op=".$case."&amp;orderby=ratingD&amp;lid=".$lid.";\">"._WS_RATINGHTOL."</option>
        <option value=\"index.php?op=".$case."&amp;orderby=ratingA&amp;lid=".$lid.";\">"._WS_RATINGLTOH."</option>
        <option value=\"index.php?op=".$case."&amp;orderby=votesD&amp;lid=".$lid.";\">"._WS_VOTESMTOL."</option>
        <option value=\"index.php?op=".$case."&amp;orderby=votesA&amp;lid=".$lid.";\">"._WS_VOTESLTOM."</option>
        <option value=\"index.php?op=".$case."&amp;orderby=hitsD&amp;lid=".$lid.";\">"._WS_PAGEHITSMTOL."</option>
        <option value=\"index.php?op=".$case."&amp;orderby=hitsA&amp;lid=".$lid.";\">"._WS_PAGEHITSLTOM."</option>
        <option value=\"index.php?op=".$case."&amp;orderby=viewsD&amp;lid=".$lid.";\">"._WS_VIEWSMTOL."</option>
        <option value=\"index.php?op=".$case."&amp;orderby=viewsA&amp;lid=".$lid.";\">"._WS_VIEWSLTOM."</option>
        <option value=\"index.php?op=".$case."&amp;orderby=lidD&amp;lid=".$lid.";\">"._WS_LIDHTOL."</option>
        <option value=\"index.php?op=".$case."&amp;orderby=lidA&amp;lid=".$lid.";\">"._WS_LIDLTOH."</option>
      </select>
      <input type='button' value=\""._WSA_ADDMEDIA."\" onClick=\"location='index.php?op=newLink'\">         
      <input type=\"button\" value=\""._WSA_LOGFILE."\" onClick=\"location='index.php?op=showLog'\">
     </form>
      <input type=\"text\" value=\"".$lid."\" size=\"8\" maxlength=\"8\" name=\"getid\">
      <input type=\"button\" value=\""._WSA_GETID."\" onClick=\"location='index.php?op=modLink&amp;lid='+getid.value\">        
   </th></tr>
   <tr><td>
     <div style=\"height: 180px; width: 100%; overflow: auto;\">
       <table border=\"1\" cellspacing='0' cellpadding='1' style=\"font-size: 85%;\">
          <tr class=\"head\" style=\"font-weight: 600;\" colspan=\"14\">
             <td width=\"10%\">"._WSA_ACTION."</td>
             <td>"._WSA_ID."</td>
             <td width=\"18%\">"._WSA_MEDIATITLE."</td>
             <td>"._WSA_LISTTYPE."</td>
             <td>"._WSA_SRC."</td>
             <td>"._WSA_CATEGORY."</td>
             <td>"._WSA_PLAYER."</td>
             <td>"._WSA_CREATED."</td>
             <td>"._WS_MODIFY."</td>
             <td>"._WSA_STATUSEXPIRATION."</td>
             <td>"._WSA_PAGEHITS."</td>
             <td>"._WSA_VIEWS."</td>
             <td>"._WSA_RANK."</td>
             <td>"._WSA_VOTE."</td>
          </tr>";

   $sql="SELECT lid, cid, title, listtype, srctype, player, status, date, published, expired, hits, views, rating, votes FROM ".$xoopsDB->prefix('webshow_links')." WHERE lid>0 order by $orderby";
   $result=$xoopsDB->query($sql,$show,$min);	
   while(list($lid, $cid, $title, $listtype, $srctype, $playerid, $status, $date, $published, $expired, $hits, $views, $rating, $votes) = $xoopsDB->fetchRow($result)) {
 	      if($status > 0){
   	         $statusreport= _WSA_ONLINE;
   	         $statuslink = "../singlelink.php?lid=".$lid;
   	         $statusicon = "online.gif";
   	      }  	          	      
	      if($status == 0){
	         // New Entry Waiting for Approval
   	         $statusreport = _WSA_STATUSWAITING;
   	         $statuslink = "index.php?op=listNewLinks";
   	         $statusicon = "waiting.gif";
   	      }  	       
  	      if($status == -1){
  	        // Entry will Auto-Publish
		    if($date == 0 & $published > time()) {  	     
  	           $statusreport = _WSA_STATUSAUTO.": ".formatTimestamp($published,'m');
  	           $statuslink = "index.php?op=modLink&amp;lid=".$lid;
  	           $statusicon = "offline.gif";   	           
  	        }elseif($date == 0 & $published < time()) {
  	           autoPublish();
  	           $date = $published;
  	           $statusicon = "online.gif";	      
            }
  	      } 	      
		  if($expired > 0 & $expired < time()) {
		     // Entry has Expired
		     $statusreport = _WSA_STATUSEXPIRED.": ".formatTimestamp($expired,'m');
  	         $statuslink = "index.php?op=modLink&amp;lid=".$lid;
  	         $statusicon = "offline.gif";   
             autoExpire();
		  }
		
		  //** Modification Requested 
		  $modresult=$xoopsDB->query("SELECT lid FROM ".$xoopsDB->prefix('webshow_mod')." WHERE lid=$lid");
		  list($modrequest) = $xoopsDB->fetchRow($modresult); 	     
          if($modrequest){
             $statusreport = _WSA_USERMODREQ;
             $statuslink = "index.php?op=listModReq";
  	         $statusicon = "offline.gif";  
          }            
        //if($statusreport){
           //$statusreport = "<a href=\"".XOOPS_URL."/modules/".$xoopsModule->getVar('dirname')."/".$statuslink."\" title=\""._WSA_EDITTHISLINK."\">".$statusreport."</a>";          
        //}

        if($cid) {
        $catpath = $cattree->getNicePathFromId($cid, "cattitle", "category.php?op=modCat");
        $catpath = str_replace(":","",$catpath);
        } else {
        $catpath = '----';
        }
        $playerpath = "<a href=\"".XOOPS_URL."/modules/".$xoopsModule->getVar('dirname')."/admin/player.php?op=modPlayer&amp;playerid=".$playerid."\" title=\""._WSA_MODPLAYER."\">".$playerid."</a>";

          if ( $date == '0' ) { 
             $createddate = '----';
          } else {
            $createddate = formatTimestamp($date,"m");
          }

        if ( $published > time() ) {
           $published = '<span style="color: #FF0000;">'._WSA_STATUSAUTO.": ".formatTimestamp($published,'m').'</span>';
        } else { 
           $published = formatTimestamp($published,"m");
        }

        if ( $expired > time() ) {
           $statusexpired = '<span style="color: #00CC00;">'._WSA_STATUSEXPIRATION.': '.formatTimestamp($expired,"m").'</span>';
        } elseif ( $expired > 0 & $expired < time() ) { 
           $statusexpired = '<span style="color: #FF0000;">'._WSA_STATUSEXPIRED.": ".formatTimestamp($expired,"m").'</span>';
        } elseif ( $expired == 0 ) {
           $statusexpired = '----';
        }
                
        //** MODLID Highlights the entry that is active in the editor
        if ($lid != $modlid) {		   
		   print "<tr class='even' colspan='14'>";
	    }else{
   	       print "<tr colspan='14'>";
   	    }	
        print "   <td width=\"10%\">
                     <a href=\"index.php?op=modLink&amp;lid=$lid\" onClick=\"location='index.php?op=modLink&amp;lid=$lid'\" title=\""._WS_MODIFY."\"><img src=\"../images/modify.gif\"></a>
   	                 <a href=\"index.php?op=delLink&amp;lid=$lid\" onClick=\"location='index.php?op=delLink&amp;lid=$lid'\" title=\""._DELETE."\"><img src=\"../images/delete.gif\"></a>
                     <a href=\"".$statuslink."\" title=\"".$statusreport."\"><img src=\"../images/".$statusicon."\"></a>
   	              </td>
                  <td><a href=\"index.php?op=modLink&amp;lid=$lid\" onClick=\"location='index.php?op=modLink&amp;lid=$lid'\" title=\""._WS_MODIFY."\">".$lid."</a></td>
                  <td width=\"18%\"><a href=\"".XOOPS_URL."/modules/".$xoopsModule->getVar('dirname')."/singlelink.php?lid=".$lid."\" title=\""._VISITWEBSITE."\" target=\"_blank\">".$title."</a></td>
                  <td>".$listtype."</td>
                  <td>".$srctype."</td>
                  <td>".$catpath."</td>
                  <td>".$playerpath."</td>
                  <td>".$createddate."</td>
                  <td>".$published."</td>
                  <td>".$statusexpired."</td>
                  <td>".$hits."</td>
                  <td>".$views."</td>
                  <td>".$rating."</td>
                  <td><a href='index.php?op=voteStats&amp;lid=".$lid."' title=\"".$title." "._WSA_VOTESTATS."\" onClick= \"location='index.php?op=voteStats&amp;lid=".$lid."'\">".$votes."</a></td>
   	           </tr>";     
   }
   print "</table></div>";

   //** List Entries
   $fullcountresult=$xoopsDB->query("select count(*) from ".$xoopsDB->prefix("webshow_links")." where lid>0");
   list($numrows) = $xoopsDB->fetchRow($fullcountresult);
   //$page_nav = _WSA_PAGENAV;


//** Page Navigation
include_once XOOPS_ROOT_PATH . '/class/pagenav.php';
//$orderby = convertorderbyout($orderby);
//XoopsPageNav XoopsPageNav($total_items, $items_perpage, $current_start, $start_name = "start", $extra_arg = "")
$pagenav = new XoopsPageNav($numrows, $show, $min, 'min', 'op='.$op.'&amp;orderby='.$orderby);
print $pagenav->renderNav(2);

/*
   //Calculates how many pages exist.  Which page one should be on, etc...
   $linkpages = ceil($numrows / $show);
   //Page Numbering
   if ($linkpages!=1 && $linkpages!=0) {
      $prev = $min - $show;
      if ($prev>=0) {
          $page_nav .= "<a href='index.php?op=".$case."&amp;lid=$lid&amp;min=$prev&amp;orderby=$orderby&amp;show=$show'><b><u>&laquo;</u></b></a>&nbsp;";
      }
      $counter = 1;
      $currentpage = ($max / $show);
      while ( $counter<=$linkpages ) {
         $mintemp = ($show * $counter) - $show;
         if ($counter == $currentpage) {
             $page_nav .= "<b>($counter)</b>&nbsp;";
         } else {
             $page_nav .= "<a href='index.php?op=".$case."&amp;lid=$lid&amp;min=$mintemp&amp;orderby=$orderby&amp;show=$show'>$counter</a>&nbsp;";
         }
         $counter++;
      }
      if ( $numrows>$max ) {
         $page_nav .= "<a href='index.php?op=".$case."&amp;lid=$lid&amp;min=$max&amp;orderby=$orderby&amp;show=$show'>";
         $page_nav .= "<b><u>&raquo;</u></b></a>";
      }
        print $page_nav;
   }
*/
print "</td></tr></table>";
}

//** MAIN ADMIN PAGE
function newLink()
{
   global $xoopsDB, $xoopsConfig, $xoopsModule, $xoopsModuleConfig, $xoopsUser, $myts, $eh, $playertree, $cattree, $linktree, $allowed_mimetypes, $logosize, $logowidth, $logoheight, $playerlogosize, $playerlogowidth, $playerlogoheight;
   player1Exist();
   //** Variables
   $lid = "";
   $cid = "0";
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
   $submitter = $xoopsUser->getVar('uid');
   $allowcomments = 1;
   $comments = 0;
   $description = "";
   $bodytext = "";
   $playerid = 1;
   	
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
   $status = 1;
   $date = 0;
   $published = 0;
   $expired = 0;
   $entryinfo = $xoopsModuleConfig['showinfo2']; // SHOWINFO2  Sets default contents of entry page.  Assign in mod preferences.
   //$entryinfo = explode(" ",$entryinfo);
   $member_handler =& xoops_gethandler('member');
   $xoopsgroups =& $member_handler->getgroups();
   $count = count($xoopsgroups);
   $ws_entryperm = array();
   for ($i = 0; $i < $count; $i++)  $ws_entryperm[] = $xoopsgroups[$i]->getVar('groupid');
   $ws_entrydownperm = array();
   for ($i = 0; $i < $count; $i++)  $ws_entrydownperm[] = $xoopsgroups[$i]->getVar('groupid');

   xoops_cp_header();
   adminmenu(1);
   //** ADMIN FORM
   //Check that a category exists 
   $result=$xoopsDB->query("select count(*) from ".$xoopsDB->prefix("webshow_cat")."");
   list($numrows)=$xoopsDB->fetchRow($result);
   if ( $numrows == 0 ) {
      redirect_header("category.php",3,_WSA_NOCAT);
      exit();	  
   } else { 
      menuLink("newLink");	
      $srctype = isset($_POST['srctype']) ? $_POST['srctype'] : isset($_GET['srctype']) ? $_GET['srctype'] : '';
      $listtype = isset($_POST['listtype']) ? $_POST['listtype'] : isset($_GET['listtype']) ? $_GET['listtype'] : '';    	

      //** NEW PLAYLIST FORM
      $wsformlabel =	_WSA_ADDMEDIA;	
      $wsformname = "newlinkform";
      $wsformaction = XOOPS_URL.'/modules/'.$xoopsModule->getVar('dirname').'/admin/index.php?op=newLink';
      $wsformmethod = "post";
      $wsformaddtoken = "false";	 			
      $op= 'addLink';
      $btnlabel = _ADD;
      echo "<h3>"._WSA_NEWENTRYEDITOR."</h3>";
      echo "<table class='outer'><tr><td>";  
      include '../include/submitform.inc.php';
      $sform->display();
      echo "</td></tr></table>";      
   }	
xoops_cp_footer();	
}

//**Add NEW Link to DB
function addLink()
{
   include '../include/submit.functions.php';
   saveEntry("addLink");  
}

function delLink()
{
   	global $xoopsDB, $xoopsModule, $xoopsModuleConfig, $eh;	  
   	$lid =  $_GET['lid'] ? intval($_GET['lid']) : intval($_POST['lid']);
	$ok =  isset($_POST['ok']) ? intval($_POST['ok']) : 0;
    if ( $ok == 1 ) {
        // Delete Logo
        include XOOPS_ROOT_PATH . "/modules/".$xoopsModule->getVar('dirname')."/include/wsimage.inc.php";
        deleteEntrylogo($lid);
        // Delete Playlist
        include '../include/playlist.php';
        deletePlaylist($lid);		
        // Delete from DB tables 
        $sql = sprintf("DELETE FROM %s WHERE lid = %u", $xoopsDB->prefix("webshow_links"), $lid);
       	$xoopsDB->query($sql) or $eh->show("0013");
    	$sql = sprintf("DELETE FROM %s WHERE lid = %u", $xoopsDB->prefix("webshow_text"), $lid);
    	$xoopsDB->query($sql) or $eh->show("0013");
    	$sql = sprintf("DELETE FROM %s WHERE lid = %u", $xoopsDB->prefix("webshow_flashvar"), $lid);
    	$xoopsDB->query($sql) or $eh->show("0013");
    	$sql = sprintf("DELETE FROM %s WHERE lid = %u", $xoopsDB->prefix("webshow_votedata"), $lid);
    	$xoopsDB->query($sql) or $eh->show("0013");
    	// delete comments
    	xoops_comment_delete($xoopsModule->getVar('mid'), $lid);
    	// delete notifications
    	xoops_notification_deletebyitem ($xoopsModule->getVar('mid'), 'link', $lid);
        redirect_header("index.php?op=newLink",1,_WSA_LINKDELETED);
    } else {
		xoops_cp_header();
		xoops_confirm(array('op' => 'delLink', 'lid' => $lid, 'ok' => 1), 'index.php', _WSA_LINKWARNING);
		xoops_cp_footer();
    }		
}

function modLink()
{ 	
   global $xoopsDB, $xoopsConfig, $xoopsUser, $xoopsModule, $xoopsModuleConfig, $myts, $eh, $cattree, $playertree, $linktree, $allowed_mimetypes, $logosize, $logowidth, $logoheight, $playerlogosize, $playerlogowidth, $playerlogoheight;
   xoops_cp_header();
   adminmenu(1);
   menuLink("modLink");	
   echo "<h3>"._WSA_MODENTRY." "._WSA_EDITOR."</h3>";
   $lid =  isset($_POST['lid']) ? intval($_POST['lid']) : intval($_GET['lid']);
   if($lid == '') { redirect_header(XOOPS_URL.'/modules/'.$xoopsModule->getVar('dirname').'/admin/index.php?op=newLink', 0, ''); }
   $result = $xoopsDB->query("select cid, title, url, srctype, listtype, listurl, listcache, status, entryinfo, submitter, logourl, player, credit1, credit2, credit3, cachetime, allowcomments, comments, date, expired, published, entryperm, entrydownperm from ".$xoopsDB->prefix("webshow_links")." where lid=$lid") or $eh->show("0013");
   list($cid, $title, $url, $srctype, $listtype, $listurl, $listcache, $status, $entryinfo, $submitter, $logourl, $playerid, $credit1, $credit2, $credit3, $cachetime, $allowcomments, $comments, $date, $expired, $published, $ws_entryperm, $ws_entrydownperm) = $xoopsDB->fetchRow($result);
   $listtype =  isset($_GET['listtype']) ? $_GET['listtype'] : $listtype;  	
   $title = $myts->htmlSpecialChars($title);
   $url = $myts->htmlSpecialChars($url);
   $srctype = $myts->htmlSpecialChars($srctype);
   $listtype = $myts->htmlSpecialChars($listtype);
   $listurl = $myts->htmlSpecialChars($listurl);
   $listcache = $myts->htmlSpecialChars($listcache);
   $entryinfo = explode(" ", $entryinfo);  
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
   $ws_entryperm = explode(" ", $ws_entryperm);
   $ws_entrydownperm = explode(" ", $ws_entrydownperm);

   //** TEXT    
   $result2 = $xoopsDB->query("select description, bodytext from ".$xoopsDB->prefix("webshow_text")." where lid=$lid");
   list($description, $bodytext)=$xoopsDB->fetchRow($result2);
   $description = $myts->htmlSpecialChars($description);
   $bodytext = $myts->htmlSpecialChars($bodytext);	

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

   //** MODIFY PLAYLIST FORM variables for submitform.inc   
   $wsformlabel =	_WSA_MODENTRY;
   $wsformname = "modlinkform";
   $wsformaction = XOOPS_URL.'/modules/'.$xoopsModule->getVar('dirname').'/admin/index.php?op=modLink';
   $wsformmethod = "post";
   $wsformaddtoken = "false";				
   $op= 'modLinkS';
   $btnlabel = _WS_MODIFY;		
   echo "<table class='outer'><tr><td width='40%'>";

   include '../include/submitform.inc.php';
   $sform->display();
   echo "</td></tr></table>";      
   xoops_cp_footer();
}

function modLinkS()
{
   include '../include/submit.functions.php';
   saveEntry("modLinkS");  
}

function listNewLinks()
{
// List links waiting for validation
global $xoopsDB, $xoopsModule, $xoopsModuleConfig, $xoopsConfig, $xoopsUser, $myts, $eh, $cattree, $playertree, $linktree, $allowed_mimetypes, $logosize, $logowidth, $logoheight, $playerlogosize, $playerlogowidth, $playerlogoheight;
$result = $xoopsDB->query("select lid, cid, title, url, srctype, listtype, listurl, listcache, entryinfo, logourl, submitter, player, credit1, credit2, credit3, cachetime, allowcomments, comments, date, published, expired, entryperm, entrydownperm from ".$xoopsDB->prefix("webshow_links")." where status=0 order by date DESC");
$numrows = $xoopsDB->getRowsNum($result);
xoops_cp_header();
adminmenu(1);	
   if ( $numrows == 0 ) {
   echo ""._WSA_NOSUBMITTED."";
   } else {
   while(list($lid, $cid, $title, $url, $srctype, $listtype, $listurl, $listcache, $entryinfo, $logourl, $submitter, $playerid, $credit1, $credit2, $credit3, $cachetime, $allowcomments, $comments, $date, $published, $expired, $ws_entryperm, $ws_entrydownperm) = $xoopsDB->fetchRow($result)) {
   $result2 = $xoopsDB->query("select description, bodytext from ".$xoopsDB->prefix("webshow_text")." where lid=$lid");
   list($description, $bodytext) = $xoopsDB->fetchRow($result2);
   $title = $myts->htmlSpecialChars($title);
   $url = $myts->htmlSpecialChars($url); 
   $srcttype = $myts->htmlSpecialChars($srctype);
   $listtype = $myts->htmlSpecialChars($listtype);
   $listurl = $myts->htmlSpecialChars($listurl);
   $listcache = $myts->htmlSpecialChars($listcache);
   //** Show Info
   //$entryinfo = $myts->htmlSpecialChars($entryinfo);
   $entryinfo = explode(" ", $entryinfo); 
   $logourl = $myts->htmlSpecialChars($logourl);
   $credit1 = $myts->htmlSpecialChars($credit1);
   $credit2 = $myts->htmlSpecialChars($credit2); 
   $credit3 = $myts->htmlSpecialChars($credit3);         
   $ws_entryperm = explode(" ", $ws_entryperm);
   $ws_entrydownperm = explode(" ", $ws_entrydownperm);
   $description = $myts->htmlSpecialChars($description);
   $bodytext = $myts->htmlSpecialChars($bodytext);       
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

  //** Modify Playlist Form
  $wsformlabel =	 _WSA_LINKSWAITING;	
  $wsformname = "linkwaitingform";
  $wsformaction = XOOPS_URL.'/modules/'.$xoopsModule->getVar('dirname').'/admin/index.php?op=listNewLinks';
  $wsformmethod = "post";
  $wsformaddtoken = "false";	 			
  $op= 'approve';
  $btnlabel = _WSA_APPROVE;
  echo "<h3>". _WSA_LINKSWAITING."</h3>";	  	
  echo "<table class='outer'><tr><td>";  
  include '../include/submitform.inc.php';
  $sform->display();
  echo "</td></tr></table>";      
  }  
 }
xoops_cp_footer();
}

function delVote()
{
	global $xoopsDB, $eh;
    $rid = intval($_GET['rid']);
    $lid = intval($_GET['lid']);
	$sql = sprintf("DELETE FROM %s WHERE ratingid = %u", $xoopsDB->prefix("webshow_votedata"), $rid);
    $xoopsDB->queryF($sql) or $eh->show("0013");
    updaterating($lid);
    redirect_header("index.php",1,_WSA_VOTEDELETED);
    exit();
}

function listBrokenLinks()
{
   	global $xoopsDB, $xoopsModule, $eh;
   	//$result = $xoopsDB->query("select * from ".$xoopsDB->prefix("webshow_broken")." group by lid order by reportid DESC");
   	$result = $xoopsDB->query("select * from ".$xoopsDB->prefix("webshow_broken")." order by rpttype DESC");

   	$totalbrokenlinks = $xoopsDB->getRowsNum($result);
	xoops_cp_header();
   	adminmenu(1);	
	echo "<h4>"._WSA_WEBSHOWCONF."</h4>";
	echo"<table width='100%' border='0' cellspacing='1' class='outer'>"
	."<tr class=\"odd\"><td>";
	echo "<h4>"._WSA_BROKENREPORTS." ($totalbrokenlinks)</h4><br />";
   	if ( $totalbrokenlinks == 0 ) {
		echo _WSA_NOBROKEN;
    } else {
		echo "<center>
		"._WSA_IGNOREDESC."<br />
		"._WSA_DELETEDESC."</center><br /><br /><br />";
        $colorswitch="dddddd";
		echo "<table align=\"center\" width=\"90%\">";
        echo "
        <tr>
        <td><b>"._WSA_MEDIATITLE."</b></td>
        <td><b>"._WSA_REPORT."</b></td>
        <td><b>"._WSA_REPORTCOMMENT."</b></td>
        <td><b>" ._WSA_REPORTER."</b></td>
        <td><b>" ._WSA_SUBMITTER."</b></td>
        <td><b>" ._WSA_IGNORE."</b></td>
        <td><b>" ._EDIT."</b></td>
        <td><b>" ._DELETE."</b></td>
        </tr>";
        while ( list($reportid, $lid, $sender, $ip, $rpttype, $rptname, $rptcmt)=$xoopsDB->fetchRow($result) ) {
            $email = '';
			$result2 = $xoopsDB->query("select title, url, submitter from ".$xoopsDB->prefix("webshow_links")." where lid=$lid");
			if ( $sender != 0 ) {
				$result3 = $xoopsDB->query("select uname, email from ".$xoopsDB->prefix("users")." where uid=$sender");
				list($uname, $email)=$xoopsDB->fetchRow($result3);
			}
    		list($title, $url, $ownerid)=$xoopsDB->fetchRow($result2);
    		$result4 = $xoopsDB->query("select uname, email from ".$xoopsDB->prefix("users")." where uid='$ownerid'");
    		list($owner, $owneremail)=$xoopsDB->fetchRow($result4);
    		echo '<tr><td bgcolor=$colorswitch><a href="'.XOOPS_URL.'/modules/'.$xoopsModule->getVar("dirname").'/singlelink.php?lid='.$lid.'" target="_blank">'.$title.'</a></td>
    		<td>'.$rptname.'</td><td>'.$rptcmt.'</td>';
    		if ( $email == '' ) {
				echo "<td bgcolor=\"".$colorswitch."\">".$sender." (".$ip.")";
			} else {
				echo "<td bgcolor=\"".$colorswitch."\"><a href=\"mailto:".$email."\">".$uname."</a> (".$ip.")";
			}
   			echo "</td>";
   			if ( $owneremail == '' ) {
				echo "<td bgcolor=\"".$colorswitch."\">".$owner."";
			} else {
				echo "<td bgcolor=\"".$colorswitch."\"><a href=\"mailto:".$owneremail."\">".$owner."</a>";
			}
			echo "</td><td bgcolor='$colorswitch' align='center'>\n";
			echo myTextForm("index.php?op=ignoreBrokenLinks&reportid=$reportid" , "X");
			echo "</td><td bgcolor='$colorswitch' align='center'>\n";
			echo myTextForm("index.php?op=modLink&lid=$lid" , "X");
			echo "</td><td align='center' bgcolor='$colorswitch'>\n";
			echo myTextForm("index.php?op=delBrokenLinks&lid=$lid" , "X");
			echo "</td></tr>\n";
    		if ( $colorswitch == "#dddddd" ) {
				$colorswitch="#ffffff";
			} else {
				$colorswitch="#dddddd";
			}
    	}
		echo "</table>";
    }
	echo"</td></tr></table>";
	xoops_cp_footer();
}


function delBrokenLinks()
{
    //** NEEDS WORK add tag sync
   	global $xoopsDB, $eh, $xoopsModule;
   	include '../include/playlist.php';  
   	$lid =  isset($_POST['lid']) ? intval($_POST['lid']) : die( 'Restricted access' );
	$ok =  isset($_POST['ok']) ? intval($_POST['ok']) : 0;
    if ( $ok == 1 ) {
      deletePlaylist($lid);		
      $sql = sprintf("DELETE FROM %s WHERE lid = %u", $xoopsDB->prefix("webshow_broken"), $lid);
      $xoopsDB->query($sql) or $eh->show("0013");
      $sql = sprintf("DELETE FROM %s WHERE lid = %u", $xoopsDB->prefix("webshow_links"), $lid);
      $xoopsDB->query($sql) or $eh->show("0013");
      $sql = sprintf("DELETE FROM %s WHERE lid = %u", $xoopsDB->prefix("webshow_text"), $lid);
      $xoopsDB->query($sql) or $eh->show("0013");
      $sql = sprintf("DELETE FROM %s WHERE lid = %u", $xoopsDB->prefix("webshow_flashvar"), $lid);
      $xoopsDB->query($sql) or $eh->show("0013");
      $sql = sprintf("DELETE FROM %s WHERE lid = %u", $xoopsDB->prefix("webshow_votedata"), $lid);
      $xoopsDB->query($sql) or $eh->show("0013");
      // delete comments
      xoops_comment_delete($xoopsModule->getVar('mid'), $lid);
      // delete notifications
      xoops_notification_deletebyitem ($xoopsModule->getVar('mid'), 'link', $lid);
      redirect_header("index.php?op=listBrokenLinks",1,_WSA_LINKDELETED);
      exit();
    } else {
      xoops_cp_header();
      xoops_confirm(array('op' => 'delLink', 'lid' => $lid, 'ok' => 1), 'index.php', _WSA_LINKWARNING);
      xoops_cp_footer();
    }		
}

function ignoreBrokenLinks()
{
	global $xoopsDB, $eh;
	$sql = sprintf("DELETE FROM %s WHERE reportid = %u", $xoopsDB->prefix("webshow_broken"), intval($_GET['reportid']));
    $xoopsDB->query($sql) or $eh->show("0013");
    redirect_header("index.php?op=listBrokenLinks",1,_WSA_BROKENDELETED);
	exit();
}

function listModReq()
{
//** NEEDS WORK to ADD TAG handler for $ws_tag
    global $xoopsDB, $myts, $eh, $cattree, $playertree, $linktree, $xoopsModule, $xoopsModuleConfig;
    $modresult = $xoopsDB->query("SELECT requestid, lid, cid, title, url, listurl, logourl, credit1, credit2, credit3, description, bodytext, modifysubmitter, ws_tag FROM ".$xoopsDB->prefix("webshow_mod")." order by requestid");
    $totalmodrequests = $xoopsDB->getRowsNum($modresult);
	xoops_cp_header();
	adminmenu(1);	
	echo "<h4>"._WSA_WEBSHOWCONF."</h4>";
	echo"<table width='100%' border='0' cellspacing='1' class='outer'>"
	."<tr class=\"odd\"><td>";
    echo "<h4>"._WSA_USERMODREQ." ($totalmodrequests)</h4><br />";
	if ( $totalmodrequests > 0 ) {
		echo "<table width=95%><tr><td>";
		$lookup_lid = array();
		while (list($requestid, $lid, $cid, $title, $url, $listurl, $logourl, $credit1, $credit2, $credit3, $description, $bodytext, $submitterid, $ws_tag)=$xoopsDB->fetchRow($modresult)) {
			$lookup_lid[$requestid] = $lid; //** ??
			$origresult1 = $xoopsDB->query("SELECT cid, title, url, listurl, logourl, submitter, player, credit1, credit2, credit3 FROM ".$xoopsDB->prefix("webshow_links")." WHERE lid=$lid");
			list($origcid, $origtitle, $origurl, $origlisturl, $origlogourl, $ownerid, $origplayer, $origartist, $origalbum, $origlabel)=$xoopsDB->fetchRow($origresult1);
			$origresult2 = $xoopsDB->query("select description, bodytext from ".$xoopsDB->prefix("webshow_text")." where lid=$lid");
			list($origdescription, $origbodytext) = $xoopsDB->fetchRow($origresult2);
			$result7 = $xoopsDB->query("select uname, email from ".$xoopsDB->prefix("users")." where uid='$submitterid'");
			$result8 = $xoopsDB->query("select uname, email from ".$xoopsDB->prefix("users")." where uid='$ownerid'");
			list($submitter, $submitteremail)=$xoopsDB->fetchRow($result7);
			list($owner, $owneremail)=$xoopsDB->fetchRow($result8);

		$cattitle=$cattree->getPathFromId($cid, "cattitle");
		$origcattitle=$cattree->getPathFromId($origcid, "cattitle");
    		$title = $myts->htmlSpecialChars($title);
     		$origtitle = $myts->htmlSpecialChars($origtitle);
    		$credit1 = $myts->htmlSpecialChars($credit1);
     		$origartist = $myts->htmlSpecialChars($origartist);
    		$credit2 = $myts->htmlSpecialChars($credit2);
     		$origalbum = $myts->htmlSpecialChars($origalbum);
    		$credit3 = $myts->htmlSpecialChars($credit3);
     		$origlabel = $myts->htmlSpecialChars($origlabel);     							
    		$url = $myts->htmlSpecialChars($url);
     		$origurl = $myts->htmlSpecialChars($origurl);  	   		 		
    		$listurl = $myts->htmlSpecialChars($listurl);
    		$origlisturl = $myts->htmlSpecialChars($origlisturl);    			   	    		    			 						
    		$logourl = $myts->htmlSpecialChars($logourl);		
		$origlogourl = $myts->htmlSpecialChars($origlogourl);		
    		$description = $myts->displayTarea($description,0);	
    		$origdescription = $myts->displayTarea($origdescription,0);
    		$bodytext = $myts->displayTarea($bodytext,0);	
    		$origbodytext = $myts->displayTarea($origbodytext,0);    	
    		
    		echo "<table border=1 bordercolor=black cellpadding=5 cellspacing=0 align=center width=100%><tr><td>
    	   	<table style=\"width:100%; text-size: 85%; background-color:#dddddd; \">
    	   	<style>
    	   	.same {border: 1px; vertical-align:top;}
    	   	.change { border: 4px solid yellow; vertical-align: top;}
    	   	</style>
    	    <tr colspan='3'><td width=10%></td><td width=45%><b>"._WSA_ORIGINAL."</b></td><td width=45%><b>"._WSA_PROPOSED."</b></td></tr>   	    
    	    <tr colspan='3'><td><b>"._WSA_SITETITLE."</b></td><td>".$origtitle."</td>";
    	    if ($title != $origtitle) {
	        echo "<td class=\"change\">".$title."</td></tr>";
	        } else {
           echo "<td class=\"same\">".$title."</td></tr>";
           }
    	    echo "<tr colspan='3'><td><b>"._WSA_ARTIST."</b></td><td>".$origartist."</td>";
    	    if ($credit1 != $origartist) {
	        echo "<td class=\"change\">".$credit1."</td></tr>";
	        } else {
           echo "<td class=\"same\">".$credit1."</td></tr>";
           }
    	    echo "<tr colspan='3'><td><b>"._WSA_ALBUM."</b></td><td>".$origalbum."</td>";
    	    if ($credit2 != $origalbum) {
	        echo "<td class=\"change\">".$credit2."</td></tr>";
	        } else {
           echo "<td class=\"same\">".$credit2."</td></tr>";
           }
          echo "<tr colspan='3'><td><b>"._WSA_LABEL."</b></td><td>".$origlabel."</td>";
    	    if ($credit3 != $origlabel) {
	        echo "<td class=\"change\">".$credit3."</td></tr>";
	        } else {
           echo "<td class=\"same\">".$credit3."</td></tr>";
           }
    	    echo "<tr colspan='3'><td><b>"._WSA_SITEURL."</b></td><td>".$origurl."</td>";
    	    if ($url != $origurl) {
	        echo "<td class=\"change\">".$url."</td></tr>";
	        } else {
           echo "<td class=\"same\">".$url."</td></tr>";
           }    	    
    	    echo "<tr colspan='3'><td><b>"._WSA_LISTURL."</b></td><td>".$origlisturl."</td>";
    	    if ($listurl != $origlisturl) {
	        echo "<td class=\"change\">".$listurl."</td></tr>";
	        } else {
           echo "<td class=\"same\">".$listurl."</td></tr>";
           }      	    
	     	echo "<tr colspan='3'><td><b>"._WSA_CATEGORYC."</b></td><td>".$origcattitle."</b></td>";
	     	 if ($cattitle != $origcattitle) {
	        echo "<td class=\"change\">".$cattitle."</td></tr>";
	        } else {
           echo "<td class=\"same\">".$cattitle."</td></tr>";
           } 
	     	echo "<tr colspan='3'><td><b>"._WSA_LOGOIMAGE."</b></td><td>";
	                  $path_image = $xoopsModuleConfig["path_logo"];	     				
			if ( $xoopsModuleConfig['path_logo'] && !empty($origlogourl) ) {
			echo "<img src=\"".XOOPS_URL."/".$path_image."/".$origlogourl."\" />";
			} else {
				echo "&nbsp;";
			}
			echo "</td><td width=45%>";
			if ( $xoopsModuleConfig['path_logo'] && !empty($logourl) ) {
				echo "<img src=\"".XOOPS_URL."/".$path_image."/".$logourl."\" />";
			} else {
				echo "&nbsp;";
			}
			echo "</td></tr>";
			echo "<tr colspan='3'><td align=left><b>"._DESCRIPTION.": </b></td><td>".$origdescription."</td>";	        
	        if ($description != $origdescription) {
	        echo "<td class=\"change\">".$description."</td></tr>";
	        } else {
           echo "<td class=\"same\">".$description."</td></tr>";
           }	
     	    echo "<tr colspan='3'><td align=left><b>"._WSA_TEXTBODY."</b></td><td>".$origbodytext."</td>";
	        if ($bodytext != $origbodytext) {     	       	    
	        echo "<td class=\"change\">".$bodytext."</td></tr>";
	        } else {
           echo "<td class=\"same\">".$bodytext."</td></tr>";
           }	        
    	   	echo "</table></td></tr></table>
    		<table align=center width=100%>
    	  	<tr>";
    		if ( $submitteremail == "" ) {
				echo "<td align=left>"._WSA_SUBMITTER."$submitter</td>";
			} else {
				echo "<td align=left>"._WSA_SUBMITTER."<a href=mailto:".$submitteremail.">".$submitter."</a></td>";
			}
    		if ( $owneremail == "" ) {
				echo "<td align=center>"._WSA_OWNER."".$owner."</td>";
			} else {
				echo "<td align=center>"._WSA_OWNER."<a href=mailto:".$owneremail.">".$owner."</a></td>";
			}
			echo "<td align=right>\n";
			echo "<table><tr><td>\n";
			echo myTextForm("index.php?op=changeModReq&requestid=$requestid" , _WSA_APPROVE);
			echo "</td><td>\n";
			echo myTextForm("index.php?op=modLink&lid=$lookup_lid[$requestid]", _EDIT);
			echo "</td><td>\n";
			echo myTextForm("index.php?op=ignoreModReq&requestid=$requestid", _WSA_IGNORE);
			echo "</td></tr></table>\n";
			echo "</td></tr>\n";
    		echo "</table><br /><br />";
    	}
    	echo "</td></tr></table>";
	} else {
		echo _WSA_NOMODREQ;
	}
	echo"</td></tr></table>";
	xoops_cp_footer();
}

function changeModReq()
{
    global $xoopsDB, $eh, $myts;
    $requestid = intval($_GET['requestid']);
    $query = "select lid, cid, title, url, srctype, listurl, logourl, player, credit1, credit2, credit3, description, bodytext from ".$xoopsDB->prefix("webshow_mod")." where requestid=".$requestid."";
    $result = $xoopsDB->query($query);
    while ( list($lid, $cid, $title, $url, $srctype, $listurl, $logourl, $player, $credit1, $credit2, $credit3, $description, $bodytext)=$xoopsDB->fetchRow($result) ) {
		if ( get_magic_quotes_runtime() ) {
		$title = stripslashes($title);
		$credit1 = stripslashes($credit1);
		$credit2 = stripslashes($credit2);
		$credit3 = stripslashes($credit3);
		$url = stripslashes($url);  		
    		$listurl = stripslashes($listurl);    		
    		$logourl = stripslashes($logourl);
    		$description = stripslashes($description);
    		$bodytext = stripslashes($bodytext);    		
		}
		$title = addslashes($title);
    	$url = addslashes($url);    	
    	$listurl = addslashes($listurl);
    	$logourl = addslashes($logourl);
    	$description = addslashes($description);
    	$bodytext = addslashes($bodytext);    	
		$sql= sprintf("UPDATE %s SET cid = %u, title = '%s', url = '%s', srctype='%s', listurl = '%s', logourl = '%s', status = %u, date = %u, player=%u, credit1 = '%s', credit2 = '%s', credit3 = '%s' WHERE lid = %u", $xoopsDB->prefix("webshow_links"), $cid, $title, $url, $srctype, $listurl, $logourl, 2, time(), $player, $credit1, $credit2, $credit3, $lid);
    		$xoopsDB->query($sql) or $eh->show("0013");
		$sql = sprintf("UPDATE %s SET description = '%s' , bodytext = '%s' WHERE lid = %u", $xoopsDB->prefix("webshow_text"), $description, $bodytext, $lid);
		$xoopsDB->query($sql) or $eh->show("0013");		
		$sql = sprintf("DELETE FROM %s WHERE requestid = %u", $xoopsDB->prefix("webshow_mod"), $requestid);
		$xoopsDB->query($sql) or $eh->show("0013");
    }
    redirect_header("index.php?op= listModReq",1,_WSA_DBUPDATED);
	exit();
}

function ignoreModReq()
{
	global $xoopsDB, $eh;
	$sql = sprintf("DELETE FROM %s WHERE requestid = %u", $xoopsDB->prefix("webshow_mod"), intval($_GET['requestid']));
    $xoopsDB->query($sql) or $eh->show("0013");
    redirect_header("index.php?op=listModReq",1,_WSA_MODREQDELETED);
	exit();
}

function approve()
{
   include '../include/submit.functions.php';
   saveEntry("approve");  
}

function webShowHelp()
{
	global $xoopsConfig, $xoopsModule;
	xoops_cp_header();
	adminmenu(1);
	include "docs/help.html";	
	xoops_cp_footer();	
}

/**
 * Function to check status of a folder
 * Code from PHPPP Article Module 
 * @return bool
 */
function ws_admin_getPathStatus($path)
{
   global $xoopsModule;
	if(empty($path)) return false;
	if(@is_writable($path)){
		$path_status = _WSA_AVAILABLE;
	}elseif(!@is_dir($path)){
		$path_status = "<font color=\"red\">"._WSA_NOTAVAILABLE."</font> <a href=".XOOPS_URL."/modules/".$xoopsModule->getVar('dirname')."/admin/index.php?op=createdir&amp;path=$path>"._WSA_CREATETHEDIR."</a>";
	}else{
		$path_status = "<font color=\"red\">"._WSA_NOTWRITABLE."</font> <a href=".XOOPS_URL."/modules/".$xoopsModule->getVar('dirname')."/admin/index.php?op=setperm&amp;path=$path>"._WSA_SETMPERM."</a>";
	}
	return $path_status;
}

/**
 * Function to check status of addon features
 * @return bool
 */
function ws_admin_getAddonStatus($path)
{
   global $xoopsModule;
	if(empty($path)) return false;
	if(file_exists($path)){
		$path_status = _WSA_AVAILABLE;
	}else{
		$path_status = "<font color=\"red\">"._WSA_NOTAVAILABLE."</font>";
	}
	return $path_status;
}

function createdir($path)
{
  global $xoopsDB, $xoopsConfig, $xoopsModule, $xoopsModuleConfig;
      $path_status_msg = "path: " .$path;               
      if(!is_dir($path)){
       mkdir($path, 755);
      }
}

function voteStats(){
   global $xoopsDB, $xoopsModuleConfig, $myts, $eh, $linktree;
   xoops_cp_header();
   adminmenu(1);
   //showStats();
   $lid =  isset($_GET['lid']) ? intval($_GET['lid']) : '';
   $selectmedia = !$lid ? _WSA_SELECTMEDIA : '';  
   //** LINK SELECT
   ob_start();
   //**function makeMySelBox($title,$order="",$preset_id=0, $none=0, $sel_name="", $onchange="")
   $linktree->makeMySelBox('title','title',$lid,1,'lid','window.location="stats.php?op=voteStats&amp;lid="+this.value');
   $linkselbox = ob_get_contents();
   ob_end_clean();
     
   //** Stats
   $result5=$xoopsDB->query("SELECT count(*) FROM ".$xoopsDB->prefix("webshow_votedata")." WHERE lid=$lid");
   list($totalvotes) = $xoopsDB->fetchRow($result5);

   print "<table class=\"outer\" style =\"width: 700px; font-size: 90%;\">
   <tr><th colspan=\"7\"><h3>"._WSA_VOTESTATS."</h3>
    <div style=\"float: left; width: 60%;\">".$selectmedia." ".$linkselbox."</div>
    <div style=\"float: right; width: 35%; text-align: right;\">
      <input type=\"button\" value=\""._WSA_LINKMGMT."\" onClick=\"location='index.php?op=newLink'\">
      <input type=\"button\" value=\""._WSA_LOGFILE."\" onClick=\"location='index.php?op=showLog'\">  
    </div></th></tr>";

    if($lid){
       print "<tr><td colspan=7><strong>";
	   printf(_WSA_TOTALVOTES,$totalvotes);
	   print "</strong><br /><br /></td></tr>\n";
       // Show Registered Users Votes
       $result5=$xoopsDB->query("SELECT ratingid, ratinguser, rating, ratinghostname, ratingtimestamp FROM ".$xoopsDB->prefix("webshow_votedata")." WHERE lid = $lid AND ratinguser >0 ORDER BY ratingtimestamp DESC");
       $votes = $xoopsDB->getRowsNum($result5);    
       print "<tr><td colspan='7'><br /><br /><strong>";
	   printf(_WSA_USERTOTALVOTES,$votes);
	   print "</strong><br /><br /></td></tr>\n";
       print "<tr><td><strong>" ._WSA_USER."  </strong></td><td><strong>" ._WSA_IP."  </strong></td><td><strong>" ._WSA_RATING."  </strong></td><td><strong>" ._WSA_USERAVG."  </strong></td><td><strong>" ._WSA_TOTALRATE."  </strong></td><td><strong>" ._DATE."  </strong></td><td align=\"center\"><strong>" ._DELETE."</strong></td></tr>\n";
       if ($votes == 0){
		   echo "<tr><td align=\"center\" colspan=\"7\">" ._WSA_NOREGVOTES."<br /></td></tr>\n";
       }
       $x=0;
       $colorswitch="dddddd";
       while(list($ratingid, $ratinguser, $rating, $ratinghostname, $ratingtimestamp)=$xoopsDB->fetchRow($result5)) {
		   //	$ratingtimestamp = formatTimestamp($ratingtimestamp);
    	   //Individual user information
       	   $result2=$xoopsDB->query("SELECT rating FROM ".$xoopsDB->prefix("webshow_votedata")." WHERE ratinguser = '$ratinguser'");
           $uservotes = $xoopsDB->getRowsNum($result2);
           $useravgrating = 0;
           while ( list($rating2) = $xoopsDB->fetchRow($result2) ) {
			   $useravgrating = $useravgrating + $rating2;
		   }
           $useravgrating = $useravgrating / $uservotes;
           $useravgrating = number_format($useravgrating, 1);
		   $ratingusername = XoopsUser::getUnameFromId($ratinguser);
           print "<tr><td bgcolor=\"".$colorswitch."\">".$ratingusername."</td><td bgcolor=\"$colorswitch\">".$ratinghostname."</td><td bgcolor=\"$colorswitch\">$rating</td><td bgcolor=\"$colorswitch\">".$useravgrating."</td><td bgcolor=\"$colorswitch\">".$uservotes."</td><td bgcolor=\"$colorswitch\">".formatTimestamp($ratingtimestamp)."</td><td bgcolor=\"$colorswitch\" align=\"center\"><a href=\"index.php?op=delVote&lid=$lid&rid=$ratingid\" title=\""._DELETE."\"><img src=\"../images/delete.gif\"></a></td></tr>\n";
    	   $x++;
    	   if ( $colorswitch == "dddddd" ) {
		      	$colorswitch="ffffff";
    	   } else {
			   $colorswitch="dddddd";
		   }
       }
	   // Show Unregistered Users Votes
       $result5=$xoopsDB->query("SELECT ratingid, rating, ratinghostname, ratingtimestamp FROM ".$xoopsDB->prefix("webshow_votedata")." WHERE lid = $lid AND ratinguser = 0 ORDER BY ratingtimestamp DESC");
       $votes = $xoopsDB->getRowsNum($result5);
       print "<tr><td colspan=7><strong><br /><br />";
	   printf(_WSA_ANONTOTALVOTES,$votes);
	   print "</strong><br /><br /></td></tr>\n";
       print "<tr><td colspan=2><strong>" ._WSA_IP."  </strong></td><td colspan=3><strong>" ._WSA_RATING."  </strong></td><td><strong>" ._DATE."  </strong></strong></td><td align=\"center\"><strong>" ._DELETE."</strong></td><br /></tr>";
       if ( $votes == 0 ) {
	      	print "<tr><td colspan=\"7\" align=\"center\">" ._WSA_NOUNREGVOTES."<br /></td></tr>";
       }
       $x=0;
       $colorswitch="dddddd";
       while ( list($ratingid, $rating, $ratinghostname, $ratingtimestamp)=$xoopsDB->fetchRow($result5) ) {
		   $formatted_date = formatTimestamp($ratingtimestamp);
           print "<td colspan=\"2\" bgcolor=\"$colorswitch\">$ratinghostname</td><td colspan=\"3\" bgcolor=\"$colorswitch\">$rating</td><td bgcolor=\"$colorswitch\">$formatted_date</td><td bgcolor=\"$colorswitch\" align=\"center\"><a href=\"index.php?op=delVote&lid=$lid&rid=$ratingid\" title=\""._DELETE."\"><img src=\"../images/delete.gif\"></a></td></tr>";
    	   $x++;
    	   if ( $colorswitch == "dddddd" ) {
		      	$colorswitch="ffffff";
    	   } else {
	      	   $colorswitch="dddddd";
	   	   }
       }
       print "<tr><td colspan=\"6\">&nbsp;<br /></td></tr>\n";
       print "</table></td></tr>\n";   
    }
    print "</table>";
}

//** function showLog
// Reads and displays the comma seperated values in the log file 
function showLog()
{
   global $myts, $eh, $logfile;
   xoops_cp_header();
   adminmenu(1);	
   print "<table class='outer' style ='width: 700px; font-size: 90%;'>
   <th colspan=\"8\"><h3>"._WSA_LOGFILE."</h3>
      <input type=\"button\" value=\""._WSA_LINKMGMT."\" onClick=\"location='index.php?op=newLink'\">
      <input type=\"button\" value=\""._WSA_EMPTYLOG."\" onClick=\"location='index.php?op=emptyLog'\">      
   </th>
   <tr><td>
     <div style=\"height: 300px; width: 700px; overflow: auto;\">
       <table border=\"1\" style=\"font-size: 85%;\">
        <tr class=\"head\" style=\"font-weight: 600;\" colspan=\"8\"><td>"._WSA_LINENUMBER."</td><td>"._DATE."</td><td>"._WSA_REFERER."</td><td>"._WSA_IP."</td><td>"._WSA_EVENT."</td><td>"._WSA_LINKID."</td><td>"._WSA_MEDIATITLE."</td><td>"._WSA_LISTTYPE."</td></tr>";
       $linenumber = 1;
       $file_handle = fopen($logfile, "r");
        while (!feof($file_handle) ) {        
          $line_of_text = fgetcsv($file_handle, 1024);
          if ($line_of_text[1] != "") {  
             print "<tr class=\"odd\"><td>" . $linenumber. "</td><td>" . $line_of_text[0] . "</td><td>" . $line_of_text[1]. "</td><td>" . $line_of_text[2] . "</td><td>" . $line_of_text[3] . "</td><td>" . $line_of_text[4]. "</td><td>" . $line_of_text[5] . "</td><td>" . $line_of_text[6] . "</td></tr>";
             $linenumber = $linenumber + 1;
          }
        }
     fclose($file_handle);
     print "</td></tr></table></div>
   </td></tr></table>";
}

//** function emptyLog
// Delete all data from log file
function emptyLog()
{
	global $myts, $eh, $logfile;	
	file_put_contents ( $logfile , "" );
	redirect_header("index.php?op=showLog",3,_WSA_EMPTYLOG);
	exit();
}

if(!isset($_POST['op'])) {
	$op = isset($_GET['op']) ? $_GET['op'] : 'main';
} else {
	$op = $_POST['op'];
}

switch ($op) {
case "webShow":
	webShow();
	break;

case "menuLink":
	menuLink();
	break;

case "newLink":
	newLink();	
	break;
	
case "addLink":
	addLink();
	break;

case "modLink": 
	modLink();
	break;

case "modLinkS":
	modLinkS();
	break;
	
case "listNewLinks":
	listNewLinks();
	break;	

case "approve":
	approve();
	break;	
	
case "listBrokenLinks":
	listBrokenLinks();
	break;
	
case "delBrokenLinks":
	delLink();
	break;
	
case "ignoreBrokenLinks":
	ignoreBrokenLinks();
	break;
	
case "listModReq":
	listModReq();
	break;
	
case "changeModReq":
	changeModReq();
	break;
	
case "ignoreModReq":
	ignoreModReq();
	break;

case "delVote":
	delVote();
	break;
			
case "delLink":
	delLink();
	break;

case "webShowHelp":
	webShowHelp();
	break;

case "createdir":
	$path = isset($_GET['path']) ? $_GET['path'] : $path;
	createdir($path);
	break;

case "voteStats":
	voteStats();
	break;

case "showLog":
	showLog();
	break;

case "emptyLog":
	emptyLog();
	break;					

case 'main':
default:  
	webShow();
	break;
}
?>