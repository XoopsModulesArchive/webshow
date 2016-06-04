<?php
// $Id: mediasearch.php 2008-04-06 1300 tcnet
//  Copied from search.php 506 2006-05-26 23:10:37Z skalpa $
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

/* mediasearch.php performs a search query and displays the entries with the flash player */

include "header.php";
$xoopsOption['template_main'] = 'webshow_search.html';
include XOOPS_ROOT_PATH."/header.php";
$xoopsTpl -> assign("xoops_module_header", '<link rel="stylesheet" type="text/css" href="'  . XOOPS_URL . '/modules/' . $xoopsModule -> getvar( 'dirname' ) .  '/templates/style.css" />'); 
$myts =& MyTextSanitizer::getInstance(); // MyTextSanitizer object

include_once XOOPS_ROOT_PATH."/class/xoopstree.php";
$mytree = new XoopsTree($xoopsDB->prefix("webshow_cat"),"cid","pid");

$logowidth = $xoopsModuleConfig['logowidth'];
$xoopsTpl->assign('logowidth', $logowidth);
$movielid = ''; //Holds first entry for movie
$metakey = "";  //Collects keyword content
$metadesc = ""; //Collects meta description content

$config_handler =& xoops_gethandler('config');
$xoopsConfigSearch =& $config_handler->getConfigsByCat(XOOPS_CONF_SEARCH);

$query = !empty($_GET['query']) ? $_GET['query'] : !empty($_POST['query']) ? $_POST['query'] : '';

//$redirect = !empty($_GET['redirect']) ? intval($_GET['redirect']) : !empty($_POST['redirect']) ? intval($_POST['redirect']) : '';

//if( $redirect == '1'){
//   $xoopsTpl->assign('srnotfound',_WS_SEARCHTERMENTER);
//}elseif($redirect == '2'){
//   $xoopsTpl->assign('srnotfound',sprintf(_WS_SEARCHTERMSHORT, $xoopsConfigSearch['keyword_min']));
//}

if($query){
   $query = $myts->htmlSpecialChars($query);
   $queries = array();
   $ignored_queries = array(); // holds keywords that are shorter than allowed minmum length
   $temp_queries = preg_split('/[\s,]+/', $query);
   foreach ($temp_queries as $q) {
      $q = trim($q);
      if (strlen($q) >= $xoopsConfigSearch['keyword_min']) {
         $queries[] = $myts->addSlashes($q);
      } else {
         $ignored_queries[] = $myts->addSlashes($q);
      }
   }
   if (count($queries) == 0) {
      redirect_header('mediasearch.php?redirect=2', 2, sprintf(_WS_SEARCHTERMSHORT, $xoopsConfigSearch['keyword_min']));
   }

   $andor = "AND";
   $mid = $xoopsModule->getVar('mid');
   $xoopsTpl->assign('ws_mid',$mid);
   $groups = is_object($xoopsUser) ? $xoopsUser -> getGroups() : XOOPS_GROUP_ANONYMOUS;
   $gperm_handler = & xoops_gethandler( 'groupperm' );
   $available_modules = $gperm_handler->getItemIds('module_read', $groups);

   $module_handler =& xoops_gethandler('module');
   $criteria = new CriteriaCompo(new Criteria('hassearch', 1));
   $criteria->add(new Criteria('isactive', 1));
   $criteria->add(new Criteria('mid', "(".implode(',', $available_modules).")", 'IN'));
   $modules =& $module_handler->getObjects($criteria, true);

   $srterm = "";
   foreach ($queries as $q) {
      $srterm .= htmlspecialchars(stripslashes($q))." ";
   }
   $xoopsTpl->assign('srterm',$srterm);
   $metakey .= $srterm;
   if (!empty($ignored_queries)) {
      $srtermx = "";
      foreach ($ignored_queries as $q) {
         $srtermx .= htmlspecialchars(stripslashes($q))." ";
      }
      $xoopsTpl->assign('srtermx',$srtermx);
   }

   if ( in_array($mid, $available_modules) ) {
      $module =& $modules[$mid];
      $results =& $module->search($queries, $andor, 5, 0);
      $numrows = count($results);
      if (!is_array($results) || $numrows == 0) {
         $xoopsTpl->assign('nomatch',_WS_SEARCHFOUNDNO."<br />"._WS_SEARCHTERMENTER);
         $orderby = '';
         $show = '';
         $min = '';
      } else {
         $xoopsTpl->assign('srfound', sprintf(_WS_SEARCHFOUND,$numrows, $srterm));
         $xoopsTpl->assign('srresult',sprintf(_WS_SEARCHRESULTS, $srterm));
         $xoopsTpl->assign('numrows', $numrows);
         $xoopsTpl->assign('show_links', true);
         $xoopsTpl->assign('show_nav', true);
         $xoopsTpl->assign('numrows', $numrows);

         //** GET QUERY START and number to show
         if (isset($_GET['show'])) {
           $show = intval($_GET['show']);
         } else {
           $show = $xoopsModuleConfig['newlinks'];
         }
         $min = isset($_GET['min']) ? intval($_GET['min']) : 0;
         $rowid = $min; //Assigns a db row number to the listing.  Defines $min (start row for query) when a listing is selected.

         if (!isset($max)) {
           $max = $min + $show;
         }

         //** Order By
         if(isset($_GET['orderby'])) {
           $orderby = convertorderbyin($_GET['orderby']);
         } else {
           $orderby = "date DESC";
         }

         //** SHOW INFO.  Content arrays
         $showinfo = $xoopsModuleConfig['showinfo']; // SHOWINFO  Sets contents of catalog views.  Assign in mod preferences.
         $codebox = $xoopsModuleConfig['codebox']; // CODEBOX  Sets contents of code box.  Assign in mod preferences.

         //** Show Player in catalog view.  Set in mod preferences "Catalog Contents"
/* // PLAYER DISABLED ATM       
         $showplayer = in_array('player',$showinfo);
         $show = $showplayer ? $show + 1 : $show;
*/
         $showplayer = 0;
         $xoopsTpl->assign('showplayer',$showplayer);

         //** PLAYER OFF LINK
         // Player switch in template
         $playeroff = isset($_GET['playeroff']) ? intval($_GET['playeroff']) : 0;  // PLAYER OFF SWITCH

         //** START ENTRY ITEMS
         for ($i = 0; $i < $numrows; $i++) {
            $lid = @intval($results[$i]['lid']);
            $sql = "select l.lid, l.cid, l.title, l.url, l.srctype, l.listtype, l.listurl, l.entryinfo, l.listcache, l.cachetime, l.logourl, l.credit1, l.credit2, l.credit3, l.submitter, l.status, l.date, l.published, l.hits, l.rating, l.votes, l.allowcomments, l.comments, l.player, l.entryperm, l.entrydownperm, l.views, t.description from ".$xoopsDB->prefix("webshow_links")." l, ".$xoopsDB->prefix("webshow_text")." t where l.lid=$lid and t.lid=$lid ORDER by $orderby";
            $result=$xoopsDB->query($sql);
            while(list($lid, $cid, $title, $url, $srctype, $listtype, $listurl, $entryinfo, $listcache, $cachetime, $logourl, $credit1, $credit2, $credit3, $submitter, $status, $date, $published, $hits, $rating, $votes, $allowcomments, $comments, $player, $ws_entryperm, $ws_entrydownperm, $views, $description) = $xoopsDB->fetchRow($result)){ 
               
               //** Entry Permission
               $group = is_object($xoopsUser) ? $xoopsUser->getGroups() : array(XOOPS_GROUP_ANONYMOUS);
               $ws_entryperm = explode(" ",$ws_entryperm);
               if (count(array_intersect($group,$ws_entryperm)) > 0) {

                  //** IS ADMIN
                  if ($xoopsUser && $xoopsUser->isAdmin($xoopsModule->mid())) {
                     $isadmin = true;
                     $adminlink = "<a href=\"".XOOPS_URL."/modules/".$xoopsModule->getVar('dirname')."/admin/index.php?op=modLink&amp;lid=".$lid."\"><img src=\"".XOOPS_URL."/modules/".$xoopsModule->getVar('dirname')."/images/editicon.gif\" border=\"0\" alt=\""._WS_ADMIN."\" /></a>";
                  } else {
                     $isadmin = false;
                     $adminlink = "";
                  }

                  //** MODIFY ENTRY LINK. Display to admin and submitter only.
                  $modlink = '';
                  if ($xoopsUser) {
                     $user = $xoopsUser->getVar('uid');
                     if ($user == $submitter | $isadmin) {
                        $modlink = "<a href=\"".XOOPS_URL."/modules/".$xoopsModule->getVar('dirname')."/modlink.php?lid=".$lid."\" title=\""._WS_MODIFY."\"><img src=\"".XOOPS_URL."/modules/".$xoopsModule->getVar('dirname')."/images/modify.gif\" border=\"0\" alt=\""._WS_MODIFY."\" /></a>";  
                     }
                  }

                  //** ENTRY INFO
                  // Select entry contents in entry editor.
                  $entryinfo = explode(' ', $entryinfo);  

                  //** MOVIE ID
                  // Assign the first listing from the loop to the movie
                  // Used below to determine contents
                  $movielid = !$movielid ? $lid : $movielid; 

                  //** Media Location (listurl)
                  // Error Check for listurl 
                  if(!$listurl){
                     // Missing media/playlist url reset status
                     $xoopsDB->queryF("update ".$xoopsDB->prefix("webshow_links")." set status=-3 where lid=".$lid."");
                     redirect_header('index.php',3,_WS_ERROR_NOMEDIALOCATION); //Missing Media Location
                  }
                  // if feed or dir check for cached playlist
                  if($listtype == "feed" && $listtype == "dir") {
                     if(!$listcache){
                        // Cache a new playlist
                        if($listtype == "dir") {  
                           createPlaylist($lid);
                        } elseif ($listtype == "feed") {
                           fetchPlaylist($lid);
                        }
                     }
                     if ( file_exists(XOOPS_URL."/modules/".$xoopsModule->getVar("dirname")."/playlist/".$listcache) ) {
                        $listurl =  XOOPS_URL."/modules/".$xoopsModule->getVar("dirname")."/playlist/".$listcache;
                     } //ELSE use original listurl
                  } 
                  $listurl = $myts->htmlSpecialChars($listurl);

                  //** TITLE
                  $ltitle = $myts->htmlSpecialChars($title);
                  if(!$showplayer){
                     $title = "<a href=\"".XOOPS_URL."/modules/".$xoopsModule->getVar('dirname')."/singlelink.php?lid=$lid\" target=\"_self\" title=\""._WS_VIEWMEDIA." - ".$ltitle."\" rel=\"follow\">".$ltitle."</a>";
                  }else{
                     $title = "<a href=\"".XOOPS_URL."/modules/".$xoopsModule->getVar('dirname')."/index.php?lid=$lid&amp;cid=$cid&amp;uid=$submitter&amp;min=$rowid&amp;orderby=$orderby&amp;show=$show\" target=\"_self\" title=\""._WS_VIEWMEDIA." - ".$ltitle."\" rel=\"nofollow\">".$ltitle."</a>";
                  }

                  //** Metakeyword Tag Collection
                  $metakey .= $ltitle." ";
                  
                  //** New or Updated Button
                  // display new button for 7 days
                  $startdate = (time()-(86400 * 7)); 
                  $newbutton = $startdate < $date ? 1 : '';
                  // Updated Button 
                  $newbutton =  !$newbutton & $status == 2 ? 2 : ''; 

                  //** Popular Button
                  $popularbutton = ( $views >= $xoopsModuleConfig['popular'] ) ? 1 : '';  

                  //** ENTRY CATEGORY            
                  if ( $lid == $movielid ) {            
                     //** Category Box
                     $catresult=$xoopsDB->query("SELECT cattitle, imgurl, catdesc FROM ".$xoopsDB->prefix("webshow_cat")." WHERE cid = $cid");
                     list($cattitle, $catimgurl, $catdesc) = $xoopsDB->fetchRow($catresult); 
                     $cattitle = $myts->htmlSpecialChars($cattitle);
                     $catimage = !empty($catimgurl) ? $myts->htmlSpecialChars($catimgurl) : '';
                     $catdesc = !empty($catdesc) ? $myts->displayTarea($catdesc,0) : '';
                  } else {
                     //** Category Path
                     $catresult=$xoopsDB->query("SELECT cattitle FROM ".$xoopsDB->prefix("webshow_cat")." WHERE cid = $cid");
                     list($cattitle) = $xoopsDB->fetchRow($catresult); 
                     $cattitle = $myts->htmlSpecialChars($cattitle);              
                  }

                  //** BLOCK DESCRIPTION
                  if(in_array('dsc',$showinfo)){
                        // if not the movielink limit the description length
                        $desclength = $xoopsModuleConfig['desclength'];  //Limit block description length to n characters
                        if ( strlen ( $description ) > $desclength ) {
                           $description = substr($description,0,$desclength)."...";
                        }
                     $description = $myts->displayTarea($description,0); 
                  } else {
                     $description = '';
                  }

                  //** ENTRY LOGO            
                  if(in_array('logo',$showinfo)){
                     if($logourl != ""){               
                        $ps = strpos($logourl,"http://");
                        if($ps === false) {
                           $logourl = XOOPS_URL."/".$xoopsModuleConfig['path_logo']."/".$logourl;
                        }
                     }else{
                        if ($xoopsModuleConfig['nologo'] == 'stocklogo' ) {              
                           $logourl = XOOPS_URL."/".$xoopsModuleConfig['path_logo']."/stock/logo.gif";
                        }
                     }
                     $logourl = $myts->htmlSpecialChars($logourl);               
                  }else{
                     $logourl = '';
                     $logowidth = '';
                  }

                  //** CREDITS (C) //Needs work for enablejs
                  if (in_array('cred',$showinfo) || in_array('cred',$entryinfo)){
                     $credits = 1;
                     $credit1 = $myts->htmlSpecialChars($credit1);
                     $credit2 = $myts->htmlSpecialChars($credit2);
                     $credit3 = $myts->htmlSpecialChars($credit3);
                     if(!$credit1 & !$credit2 & !$credit3) { $credits = ""; }

                  }else{
                     $credit1 = "";
                     $credit2 = "";
                     $credit3 = "";
                     $credits = "";
                  } 

                  //STATISTICS
                  $stats = "";
                  if(in_array('stat',$showinfo) || in_array('stat',$entryinfo)){
                     $stats = 1;
                     $updated = formatTimestamp($published,"s");
                     $date = formatTimestamp($date,"s");
                     if ($votes == 1) {
                        $votestring = _WS_ONEVOTE;
                     } else {
                        $votestring = sprintf(_WS_NUMVOTES,$votes);
                     }
                  }else{
                     $hits = "";
                     $votestring = "";
                     $updated = "";
                     $views = "0";
                  }
            
                  //** SUBMITTER
                  if(in_array('sbmt',$showinfo) & in_array('sbmt',$entryinfo)){ 
                     if(@include_once XOOPS_ROOT_PATH."/class/userutility.php"){
                        // example getUnameFromId( $userid, $usereal = false, $linked = false)
                        $submittername = XoopsUserUtility::getUnameFromId($submitter,0,'');
                     }else{
                        $submittername = XoopsUser::getUnameFromId($submitter); 
                     }
                  }else{
                     $submittername = '';
                  }

                  //** Comments
                  if($allowcomments = ""){ $comments = ""; }

                  //** RATE
                  if(in_array('rate',$showinfo) & in_array('rate',$entryinfo)){
                     $rating = number_format($rating, 1);
                  } else {
                     $rating = 'off';
                  }
            
                  //** POPUP PLAYER BUTTON
                  //** Java script opens popmovie.php in a new window.
                  $popbutton ="";
                  if(in_array('pop',$showinfo) || in_array('pop',$entryinfo)){
                     $popbutton = "<span onClick= \"window.open('popmovie.php?lid=".$lid."','popmovie".$lid."','directories=no,toolbar=no,location=no,menubar=no');return false;\" style=\"cursor:pointer;\"><img src=\"".XOOPS_URL."/modules/".$xoopsModule->getVar('dirname')."/images/popbutton.gif\" alt=\"".$ltitle." "._WS_POPUP." "._WS_PLAYER."\" /></span>";
                  }

                  //** CREATE MOVIE ITEMS
                  // if mod pref "showinfo" and the Player off link is not selected and this (lid) is the first entry (movielid)

                  if($showplayer & !$playeroff & $lid == $movielid){

                     // Set the catalog view default player 
                     $playerid = 1;

                     //** LOAD THE MOVIE and assign movielid
                     $xoopsTpl->assign('movielid',$movielid); // Assign the Movielid for template use             
                     include "include/movie.inc.php";

                     if($listtype == "embed"){
                        $embedmovie = embedMovie($lid, $playerid,'');
                        $movie = !empty($embedmovie['movie']) ? $embedmovie['movie'] : '';
                        $hostname = !empty($embedmovie['hostname']) ? $myts->htmlSpecialChars($embedmovie['hostname']) : '';         
                        $hostlink = !empty($embedmovie['hostlink']) ? $embedmovie['hostlink'] : '';
                        $url = !$url ? $hostlink : $url;  //if url is empty use hostlink
                     } else {
                        $movie = loadMovie($lid, $playerid,'');
                        $hostname = '';         
                        $hostlink = '';
                     }

                     $xoopsTpl->assign('movie',$movie);           

                     //** GET PLAYER VARS and assign to template
                     $playerresult = $xoopsDB->query("select bgcolor, backcolor, frontcolor, lightcolor, width, height, styleoption from ".$xoopsDB->prefix("webshow_player")." where playerid=$playerid");
                     list($bgcolor, $backcolor, $frontcolor, $lightcolor, $width, $height, $styleoption) = $xoopsDB->fetchRow($playerresult);
                     $xoopsTpl->assign('screen', array('bgcolor' => $bgcolor, 'backcolor' => $backcolor, 'frontcolor' => $frontcolor, 'lightcolor' => $lightcolor, 'width' => $width, 'height' => $height, 'styleoption' => $styleoption));
   
                     // Media's Website URL
                     $url = $myts->htmlSpecialChars($url); 

                     // include the Links, buttons and codeboxes
                     include "include/codebox.inc.php";

                     //** TAGS
                     $tagbar="";
                     if($xoopsModuleConfig['tags'] & in_array('tag',$xoopsModuleConfig['showinfo2']) & in_array('tag',$entryinfo)){       
                        if(file_exists(XOOPS_ROOT_PATH."/modules/tag/include/tagbar.php")) {
                           $itemid = $lid;
                           $catid = 0;
                           include_once XOOPS_ROOT_PATH."/modules/tag/include/tagbar.php";
                           //function tagBar($tags, $catid = 0, $modid = 0)
                           $tagbar = tagBar($itemid, $catid);
                           if($tagbar){
                               $xoopsTpl->assign('tagbar',$tagbar);
                               $metakey .= implode(' ',$tagbar["tags"]);
                           }
                        }
                     }

                     //** MOVIE ARRAY
                     $xoopsTpl->assign('link', array('id' => $lid, 'cid' => $cid, 'cattitle' => $cattitle, 'catimage' => $catimage, 'catdesc' => $catdesc, 'rating' => $rating, 'ltitle' => $ltitle, 'title' => $title, 'newbutton' => $newbutton, 'popularbutton' => $popularbutton, 'listtype'=> $listtype, 'srctype' =>$srctype, 'listurl' => $listurl, 'url' => $url, 'description' => $description, 'logourl' => $logourl, 'logowidth' => $logowidth, 'tagbar' => $tagbar, 'popbutton' => $popbutton, 'adminlink' => $adminlink, 'modlink' => $modlink, 'comments' => $comments, 'credits' => $credits, 'credit1' => $credit1, 'credit2' => $credit2, 'credit3' => $credit3, 'stats' => $stats, 'date' => $date, 'updated' => $updated, 'hits' => $hits, 'views' => $views, 'votes' => $votestring, 'submitter' => $submitter, 'submittername' => $submittername, 'permalink' => $permalink, 'sitebutton' => $sitebutton, 'sitelink' => $sitelink, 'sitecode' => $sitecode, 'downbutton' => $downbutton, 'downlink' => $downlink, 'feedbutton' => $feedbutton, 'feedlink' => $feedlink, 'feedcode' => $feedcode, 'mail_subject' => rawurlencode(sprintf(_WS_INTRESTLINK,$xoopsConfig['sitename'])), 'mail_body' => rawurlencode(sprintf(_WS_INTLINKFOUND,$xoopsConfig['sitename']).':  '.XOOPS_URL.'/modules/'.$xoopsModule->getVar("dirname").'/singlelink.php?lid='.$lid), 'hostname' => $hostname, 'hostlink' => $hostlink));
                  }else{
                      //** CATALOG ARRAY
                      $xoopsTpl->append('links', array('id' => $lid, 'cid' => $cid, 'cattitle' => $cattitle, 'rating' => $rating, 'ltitle' => $ltitle, 'title' => $title, 'newbutton' => $newbutton, 'popularbutton' => $popularbutton, 'listurl' => $listurl, 'description' => $description, 'logourl' => $logourl, 'logowidth' => $logowidth, 'popbutton' => $popbutton, 'adminlink' => $adminlink, 'modlink' => $modlink, 'comments' => $comments, 'credits' => $credits, 'credit1' => $credit1, 'credit2' => $credit2, 'credit3' => $credit3, 'stats' => $stats, 'date' => $date, 'updated' => $updated, 'hits' => $hits, 'views' => $views, 'votes' => $votestring, 'submitter' => $submitter, 'submittername' => $submittername, 'rowid' => $rowid));
                  } // END MOVIE ITEMS      
             } //END ENTRY VIEW PERM
            } // END WHILE LOOP
            $rowid ++; // Assigns Row # to entry in order to get the start($min) row for a new loop query
         }  // END FOR LOOP
      } //END RESULTS FOUND
   }  // END available modules

   unset($link);
   unset($links);
   unset($module);
   // Page Navigation
   include_once XOOPS_ROOT_PATH . '/class/pagenav.php';
   $orderby = convertorderbyout($orderby);
   //XoopsPageNav XoopsPageNav($total_items, $items_perpage, $current_start, $start_name = "start", $extra_arg = "")
   $pagenav = new XoopsPageNav($numrows, $show, $min, 'min', "orderby=".$orderby);
   $xoopsTpl->assign('lang_pagenav',_WS_PAGE);
   $xoopsTpl->assign('page_nav', $pagenav->renderNav(2));
} // END NOT REDIRECT

//** Category Select
ob_start();
//** Example makeMySelBox($title,$order="",$preset_id=0, $none=0, $sel_name="", $onchange="")
$mytree->makeMySelBox("cattitle","cattitle",0,1,"cid", 'window.location="playcat.php?cid="+this.value');
$catselbox = ob_get_contents();
ob_end_clean();
$xoopsTpl->assign('category_select', $catselbox);

//** Submit a Link
$groups = is_object($xoopsUser) ? $xoopsUser->getGroups() : XOOPS_GROUP_ANONYMOUS;
$gperm_handler =& xoops_gethandler('groupperm');
if ($gperm_handler->checkRight('webshow_submit', 0, $groups, $xoopsModule->getVar('mid'))) {  
   $xoopsTpl->assign('submitlink', 1);
}

//** TEXT VARIABLES
// Module Name
$wsmodname = $myts->htmlSpecialChars($xoopsModule->getVar('name'));
$xoopsTpl->assign('wsmodname', $wsmodname);

// Module Description
$wsmoddesc = $myts->displayTarea($xoopsModuleConfig['moddesc'],0);
$xoopsTpl->assign('wsmoddesc', $wsmoddesc);

//** METATAGS
// Page Title
$pagetitle = $myts->htmlSpecialChars($wsmodname." "._WS_MEDIASEARCH);
$xoopsTpl->assign('xoops_pagetitle',$pagetitle); 

// Page Description
$wsmoddesc = $myts->displayTarea($xoopsModuleConfig['moddesc'],0);
$xoopsTpl->assign('wsmoddesc', $wsmoddesc);

// META DESCRIPTION TAG
$metadesc = strip_tags($wsmoddesc);
$metadesc = $pagetitle." ".$myts->htmlSpecialChars($metadesc);

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

include XOOPS_ROOT_PATH."/footer.php";
?>