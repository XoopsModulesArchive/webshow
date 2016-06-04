<?php
// $Id: submit.functions.php,webshow v.65 2008/07/05 19:59:00 tcnet Exp $ //
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

function saveEntry($op)
{
   global $xoopsDB, $xoopsConfig, $xoopsUser, $xoopsModule, $xoopsModuleConfig, $myts, $eh, $allowed_mimetypes, $logosize, $logowidth, $logoheight, $playerlogosize, $playerlogowidth, $playerlogoheight;	 

   //** CAPTCHA verification
   if($xoopsModuleConfig["captcha"]){
      if(file_exists(XOOPS_ROOT_PATH."/class/captcha/xoopscaptcha.php")) {
         include_once XOOPS_ROOT_PATH."/class/captcha/xoopscaptcha.php";            
         $xoopsCaptcha = XoopsCaptcha::getInstance();
         if(! $xoopsCaptcha->verify($_POST["skipmember"]) ) {
            echo $xoopsCaptcha->getMessage();
            redirect_header(XOOPS_URL."/modules/".$xoopsModule->getVar('dirname')."/submit.php", 3, _WS_CAPTCHA_INCORRECT."<br />"._NOPERM);
         }
      }else{
         if(file_exists(XOOPS_ROOT_PATH."/Frameworks/captcha/captcha.php")) {
            include_once XOOPS_ROOT_PATH."/Frameworks/captcha/captcha.php";
            $xoopsCaptcha = XoopsCaptcha::instance();
            if(! $xoopsCaptcha->verify($_POST["skipmember"]) ) {
               echo $xoopsCaptcha->getMessage();
               redirect_header(XOOPS_URL."/modules/".$xoopsModule->getVar('dirname')."/submit.php", 3, _WS_CAPTCHA_INCORRECT."<br />"._NOPERM);
            }
         }    
      }  
   }

   $lid = isset($_POST['lid']) ? intval($_POST['lid']):'';
   $cid = intval($_POST["cid"]);
   $title = isset($_POST["title"]) ? $myts->addSlashes($myts->censorString($_POST["title"])) : $eh->show("1001");
   $srctype = $myts->addSlashes($_POST["srctype"]);
   $listtype = $myts->addSlashes($_POST["listtype"]);
   $listurl = isset($_POST['listurl']) ? $_POST['listurl'] : "";
   if($listtype != "embed" && $listtype != "dir") { $listurl = formatURL ($listurl); }
   $listurl = $myts->addSlashes($listurl); 

   //** CACHE PLAYLIST
   $cachetime = isset($_POST['cachetime']) ? intval($_POST['cachetime']) : "";
   $listcache = isset($_POST['listcache']) ? $_POST['listcache'] : ""; 
   $refreshlist = isset($_POST['refreshlist']) ? intval($_POST['refreshlist']) : "";
   $message = "";

   if($listtype == "feed" || $listtype == "dir") {   
      include XOOPS_ROOT_PATH."/modules/".$xoopsModule->getVar('dirname')."/include/playlist.php";
      $cachetime = !$cachetime ? "604800" : $cachetime;  

     if($op != "addLink"){
       // Refresh the playlist if the cachetime has expired
       $cacheexpired = timePlaylist($listcache,$cachetime);
       if($cacheexpired > 0){
          $message .= "<br />"._WSA_LISTCACHE_EXPIRED;
          deletePlaylist($lid);
          $listcache = ""; //rename cached file
          $refreshlist = 1;
       }

       // Refresh the playlist if the media location changes
       $listurlresult=$xoopsDB->query("select listurl from ".$xoopsDB->prefix("webshow_links")." where lid=$lid");
       list($origlisturl)=$xoopsDB->fetchRow($listurlresult);
       if ($listurl != $origlisturl) {
          $message .= "<br />"._WSA_LOCATIONCHANGE.": ".$listurl; 
          $refreshlist = 1;
       }
     }

      if (!$listcache='' && !file_exists(XOOPS_ROOT_PATH."/modules/".$xoopsModule->getVar("dirname")."/playlist/".$listcache)){ 
         $message .= "<br />"._WSA_LISTCACHE_FILE." "._WS_NOTEXIST;
         $refreshlist = 1;     
      }
     $listcache = $myts->addSlashes($listcache);
   }
   
   //** SITE URL
   $url = isset($_POST["url"]) ? $myts->addSlashes(formatURL($_POST["url"])):''; 

   //** CREDITS
   $credit1 = $myts->addSlashes($myts->censorString($_POST["credit1"]));
   $credit2 = $myts->addSlashes($myts->censorString($_POST["credit2"]));
   $credit3 = $myts->addSlashes($myts->censorString($_POST["credit3"]));

   //** Comments
   $allowcomments = isset($_POST['allowcomments']) ? intval($_POST['allowcomments']) : "";
   $comments = !$allowcomments ? "" : commentCount($lid); //Comment counter

   //** TEXT
   $description = $myts->addSlashes($myts->censorString($_POST["description"]));
   $bodytext = $myts->addSlashes($myts->censorString($_POST["bodytext"]));   
 
   //** Submitter
   $submitter = isset($_POST['submitter']) ? intval($_POST['submitter']) : (!empty($xoopsUser) ? $xoopsUser->getVar('uid') : 0);

   //** Player
   $playerid = isset($_POST['playerid']) ? intval($_POST["playerid"]) : "1";  //If no player is selected, use Default player id #1

   //** Error checks
   $error="";
   // Check if the media url was submitted
   if ( $listurl == "" ) {
      $errormsg .= "<h4 style='color: #ff0000'>";
      $errormsg .= _WS_ERRORLINK."</h4>";
      $error =1;
   }  
   // Check if the media url is already in the db 
   if ($op =="addLink"){
      $result = $xoopsDB->query("select count(*) from ".$xoopsDB->prefix("webshow_links")." where listurl='$listurl'");
      list($numrows) = $xoopsDB->fetchRow($result);
      $errormsg = "";
      $error = 0;
      if ( $numrows > 0 ) {
         $errormsg .= "<h4 style='color: #ff0000'>";
         $errormsg .= _WSA_ERROREXIST."</h4>";
         $error = 1;
      }
   }
   // Check if Playlist was submitted
   if ( $listurl == "" ) {
      $errormsg .= "<h4 style='color: #ff0000'>";
      $errormsg .= _WSA_ERRORLINK."</h4>";
      $error =1;
   }
   // Check if Title was submitted
   if ( $title == "" ) {
      $errormsg .= "<h4 style='color: #ff0000'>";
      $errormsg .= _WSA_ERRORTITLE."</h4>";
      $error =1;
   }
   // Check if Description was submitted
   if ( $description == "" ) {
      $errormsg .= "<h4 style='color: #ff0000'>";
      $errormsg .= _WSA_ERRORDESC."</h4>";
      $error =1;
   } 
   if ( $error == 1 ) {
      //xoops_cp_header();
      include XOOPS_ROOT_PATH."/header.php";
      echo $errormsg;
      //xoops_cp_footer();
      include XOOPS_ROOT_PATH.'/footer.php';
      exit();
   }

   if($listtype !="embed"){       
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
         if ($uploader2->fetchMedia($_POST['xoops_upload_file'][1])) {
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
      $mediatype = $srctype;
   }

//** CREATED and PUBLISHED DATE
$date = isset($_POST['date']) ? $_POST['date'] : 0;
$publish_date = isset($_POST['publish_date']) ? $_POST['publish_date'] : time();
if ($op == "addLink"){
   $autodate = isset($_POST['autodate']) ? intval($_POST['autodate']) : 0; 
   if ($autodate == 1) {      
      $published = strtotime($publish_date['date']) + $publish_date['time']; 
      if( $published <= time() ){
         $date = $published;
      } else {
         $date = 0;
      }     
   } else {
      $published = time()-600;
      $date = $published;
   }
}else{
   //** LAST MODIFIED/REPUBLISH
      $published = isset($_POST['published']) ? $_POST['published'] : time()-600;

   //** CREATED DATE
   $autocreatedate = isset($_POST['autocreatedate']) ? intval($_POST['autocreatedate']) : 0;   
   if ($autocreatedate = 1) {
      $created_date = $_POST['created_date'];
      $date = strtotime($created_date['date']) + $created_date['time'];
   }else{
      $date = $_POST["date"];
      if($published > time()){
         $date = 0;
      } elseif (time()>$published & $date = 0){
         $date = $published;
      }
   }
}

//** EXPIRATION
$autoexpire = isset($_POST['autoexpire']) ? intval($_POST['autoexpire']) : '';
   if ($autoexpire != 1) {
      $expired = '';
   } else {
      $expiry_date = $_POST['expiry_date'];
      $expiry_date = strtotime($expiry_date['date']) + $expiry_date['time'];
      $offset = $xoopsUser -> timezone() - $xoopsConfig['server_TZ'];
      $expiry_date = $expiry_date - ( $offset * 3600 );
      $expired = $expiry_date;
   }
   //** STATUS
   // -1 = auto-publish, expired or set offline, 0 = waiting approval, 1 = online, 2 = modified
   // If auto_approve then $status = 1
   $groups = is_object($xoopsUser) ? $xoopsUser->getGroups() : XOOPS_GROUP_ANONYMOUS;
   $gperm_handler =& xoops_gethandler('groupperm');
   if($op == "addLink") { 
    if ($gperm_handler->checkRight('webshow_approve', $cid, $groups, $xoopsModule->getVar('mid'))) {
      $status = 1;
      if($published > time()) {
         $status= -1;
      }
      if($expired < time() & $expired > 0) {
         $status = -1;		
      }
    }else{ 
      $status = 0;
    }
   }elseif ($op == "modLinkS") {    
      $status = $_POST['status']<1 ? intval($_POST['status']) : 2;
   }
   $statusswitch = isset($_POST['statusswitch']) ? intval($_POST['statusswitch']) : '';
   if ($statusswitch == 0) {
      $status = $status > 0 ? '0' : $status;
   } elseif ($statusswitch == 1) {
      $status = $status > 1 ? $status : '1';
   } 

   //** ENTRY INFO
   $entryinfo = isset($_POST['entryinfo']) ? $_POST['entryinfo'] : $xoopsModuleConfig['showinfo2'];   
   $entryinfo = (is_array($entryinfo)) ? implode(" ", $entryinfo) : $entryinfo;  

   //** PERMISSIONS
   $ws_entryperm = isset($_POST['ws_entryperm']) ? $_POST['ws_entryperm'] : '';    
   $ws_entryperm = (is_array($ws_entryperm)) ? implode(" ", $ws_entryperm) : $ws_entryperm;   

   $ws_entrydownperm = isset($_POST['ws_entrydownperm']) ? $_POST['ws_entrydownperm'] : '';    
   $ws_entrydownperm = (is_array($ws_entrydownperm)) ? implode(" ", $ws_entrydownperm) : $ws_entrydownperm;

   //** Query db
   $groups = is_object($xoopsUser) ? $xoopsUser->getGroups() : XOOPS_GROUP_ANONYMOUS;
   $gperm_handler =& xoops_gethandler('groupperm');    
   if($op == "addLink"){
      //** Insert data
      $sql = sprintf("INSERT INTO %s (lid, cid, title, url, srctype, listtype, listurl, listcache, entryinfo, submitter, status, date, published, expired, hits, rating, votes, allowcomments, comments, player, credit1, credit2, credit3, cachetime, entryperm, entrydownperm) VALUES (%u, %u, '%s', '%s', '%s', '%s', '%s', '%s', '%s', %u, '%s', %u, %u, %u, %u, %u, %u, %u, '%s', %u, '%s', '%s', '%s', %u, '%s', '%s')", $xoopsDB->prefix("webshow_links"), $lid, $cid, $title, $url, $srctype, $listtype, $listurl, $listcache, $entryinfo, $submitter, $status, $date, $published, $expired, 0, 0, 0, $allowcomments, $comments, $playerid, $credit1, $credit2, $credit3, $cachetime, $ws_entryperm, $ws_entrydownperm);
      $xoopsDB->query($sql) or $eh->show("0013");
      $lid = !$lid ? $xoopsDB->getInsertId(): $lid;
      //** Insert Description and Text
      $sql = sprintf("INSERT INTO %s (lid, description, bodytext) VALUES (%u, '%s', '%s')", $xoopsDB->prefix("webshow_text"), $lid, $description, $bodytext);
      $xoopsDB->query($sql) or $eh->show("0013");       
      //** Insert Flash Variables
      if ($listtype != "embed"){
         $sql = sprintf("INSERT INTO %s (lid, mediatype, start, shuffle, replay, link, linktarget, showicons, stretch, showeq, rotatetime, shownav, transition, thumbslist, captions, enablejs, playerlogo, audio) VALUES (%u, '%s', %u, %u, %u, %u, '%s', %u, '%s', %u, %u, %u, '%s', %u, '%s', %u, '%s', '%s')", $xoopsDB->prefix("webshow_flashvar"), $lid, $mediatype, $start, $shuffle, $replay, $link, $linktarget, $showicons, $stretch, $showeq, $rotatetime, $shownav, $transition, $thumbslist, $captions, $enablejs, $playerlogo, $audio);
         $xoopsDB->query($sql) or $eh->show("0013");
      }
   }else{
      if ($gperm_handler->checkRight('webshow_approve', $cid, $groups, $xoopsModule->getVar('mid'))) {   
         //** UPDATE DATA
         $query = "update ".$xoopsDB->prefix("webshow_links")." set cid='$cid', title='$title', url='$url', srctype='$srctype', listtype='$listtype', listurl='$listurl', listcache='$listcache', entryinfo='$entryinfo', status='$status', date='$date', published='$published', expired='$expired', allowcomments='$allowcomments', player='$playerid', credit1='$credit1', credit2='$credit2', credit3='$credit3', cachetime='$cachetime', entryperm='$ws_entryperm', entrydownperm='$ws_entrydownperm' where lid=".$lid."";
         $xoopsDB->query($query) or $eh->show("0013");
         $query = "update ".$xoopsDB->prefix("webshow_text")." set description='$description', bodytext='$bodytext' where lid=".$lid."";
         $xoopsDB->query($query) or $eh->show("0013"); 
         if ($listtype != "embed"){
            $xoopsDB->query("update ".$xoopsDB->prefix("webshow_flashvar")." set start='$start', shuffle='$shuffle', replay='$replay', link='$link', linktarget='$linktarget', showicons='$showicons', stretch='$stretch', showeq='$showeq', rotatetime='$rotatetime', shownav='$shownav', transition='$transition', thumbslist='$thumbslist', captions='$captions', enablejs='$enablejs', playerlogo='$playerlogo', audio='$audio' where lid=".$lid."")  or $eh->show("0013");
         }
      } else {
         // Submitted Modification must be approved by an admin so save data to the mod_link table instead of the link table.
         $newid = $xoopsDB->genId($xoopsDB->prefix("webshow_mod")."_requestid_seq");      
         $ws_tag = $myts->addSlashes($_POST["item_tag"]); // Store tags in mod table until mod request is approved        
         $sql = sprintf("INSERT INTO %s (requestid, lid, cid, title, url, srctype, listurl, description, bodytext, modifysubmitter, credit1, credit2, credit3, ws_tag) VALUES (%u, %u, %u, '%s', '%s', '%s', '%s', '%s', '%s', '%s', %u, '%s', '%s', '%s', '%s')", $xoopsDB->prefix("webshow_mod"), $newid, $lid, $cid, $title, $url, $srctype, $listurl, $logourl, $description, $bodytext, $modifysubmitter, $credit1, $credit2, $credit3, $ws_tag);
         $xoopsDB->query($sql) or $eh->show("0013");      
         //** Save Flash Vars  // NEEDS WORK should be temp saved as array in the mod table
         $xoopsDB->query("update ".$xoopsDB->prefix("webshow_flashvar")." set start='$start', shuffle='$shuffle', replay='$replay', link='$link', linktarget='$linktarget', showicons='$showicons', stretch='$stretch', showeq='$showeq', rotatetime='$rotatetime', shownav='$shownav', transition='$transition', thumbslist='$thumbslist', captions='$captions', playerlogo='$playerlogo', enablejs='$enablejs' where lid=".$lid."")  or $eh->show("0013");
      }
   }

   //** ENTRY LOGO 
   // Needs work to use modify approval perm. Always updates.
   $logourl = empty($logourl)?(empty($_POST['logourl'])?"":$_POST['logourl']):$logourl;
   $embed_logo = empty($embed_logo)?(empty($_POST['embed_logo'])?"":$_POST['embed_logo']):'';    
   include_once XOOPS_ROOT_PATH.'/modules/'.$xoopsModule->getVar("dirname").'/include/wsimage.inc.php';
   if($logourl == "" & $embed_logo !=""){
      // Fetch logo image from embed source thumbnail link as defined in webshow/plugin then resize and save.
      $width = "";
      $height = "";
      $frontcolor = "";
      $backcolor = "";
      include XOOPS_ROOT_PATH."/modules/".$xoopsModule->getVar("dirname")."/plugin/".$srctype.".php";
      $logourl = fetchLogo($embed_logo,$lid);
      // example smart_resize_image( $file, $width = 0, $height = 0, $proportional = false, $output = 'file', $delete_original = true, $use_linux_commands = false )
      smart_resize_image(XOOPS_ROOT_PATH."/".$xoopsModuleConfig['path_logo']."/".$logourl, $logowidth, $logoheight, true, 'file', true, false); 
   }else{
      // if LOGO Link from URL
      // Fetch and resize image from an external link
      $logolink =  isset($_POST['logolink']) ? $_POST['logolink'] : "";
      if(!empty($logolink)){
         $logolink = formatURL ($logolink);
         $logourl = fetchLogo($logolink,$lid);
         if($logourl !=''){
            smart_resize_image(XOOPS_ROOT_PATH."/".$xoopsModuleConfig['path_logo']."/".$logourl, $logowidth, $logoheight, true, 'file', true, false); 
         }
      }
      // if LOGO UPLOAD
      if (!empty($_FILES['userfile']['name'])) {
         //** Fetch and resize logo from clients computer
         //$uploader = new XoopsMediaUploader('/home/xoops/uploads', $allowed_mimetypes, $maxfilesize, $maxfilewidth, $maxfileheight);
         $uploader = new XoopsMediaUploader(XOOPS_ROOT_PATH."/".$xoopsModuleConfig['path_logo'], $allowed_mimetypes, $logosize, $logowidth, $logoheight);
         if ($uploader->fetchMedia($_POST['xoops_upload_file'][0])) {
            if (!$uploader->upload()) {
               echo $uploader->getErrors();
            } else {
               echo '<strong>'._WS_LOGOIMAGE." "._WSA_FILESUCCESS.'</strong>';
               $logourl = $uploader->getSavedFileName();
               //Rename saved logo to use lid.ext               
               $oldlogourl = XOOPS_ROOT_PATH . "/".$xoopsModuleConfig['path_logo']."/".$logourl;
               $logoext = substr($logourl, -4);
               $logourl = $lid.$logoext;
               $newlogourl = XOOPS_ROOT_PATH . "/".$xoopsModuleConfig['path_logo']."/".$logourl;
               if (file_exists($newlogourl)) {
                   unlink($newlogourl); 
               }
               $logofilename = rename($oldlogourl,$newlogourl);
               smart_resize_image(XOOPS_ROOT_PATH."/".$xoopsModuleConfig['path_logo']."/".$logourl, $logowidth, $logoheight, true, 'file', true, false);                             
            }
         } else {
            echo $uploader->getErrors();
         }
      }
      // Select Stock Logo
      $stocklogo =  isset($_POST['stocklogo']) ? $_POST['stocklogo'] : "";
      if ($stocklogo){
          $logourl = $stocklogo;
      }
   }
   if ($op == "modLinkS" & $logourl == '') {
      // If saving a modified entry and the logourl field has been set to empty then check for and delete orphaned files
      deleteEntrylogo($lid);
   }
   $logourl = $myts->addSlashes($logourl); 
   $query = "update ".$xoopsDB->prefix("webshow_links")." set logourl='$logourl' where lid=".$lid."";
   $xoopsDB->query($query) or $eh->show("0013");   

   // Name, Fetch and Save the playlist file
      if ($refreshlist == 1){ 
         echo "<strong><br />"._WSA_LISTCACHE_NEW."</strong>";        
         // Name the cached playlist 
         // $listcache = !$listcache ? namePlaylist($lid,$title) : $listcache;
         // Fetch or create the cached playlist
         if($listtype == "feed"){
            fetchPlaylist($lid);
         }else{
            createPlaylist($lid);
         } 
      }  

   //** Save Tags requires Tag 1.60 module
   if($xoopsModuleConfig['tags'] & file_exists(XOOPS_ROOT_PATH."/modules/tag/class/tag.php")){
      $tag_handler = xoops_getmodulehandler('tag', 'tag');
      $itemid = $lid;
      $tag_handler->updateByItem($_POST["item_tag"], $lid, $xoopsModule->getVar("dirname"), $catid=0);
   }
  
   //** Notifications
      $tags = array();
      $tags['LINK_NAME'] = $title;
      $tags['LINK_URL'] = XOOPS_URL . '/modules/'. $xoopsModule->getVar('dirname') . '/singlelink.php?lid=' . $lid;
      $sql = "SELECT cattitle FROM " . $xoopsDB->prefix("webshow_cat") . " WHERE cid=" . $cid;
      $result = $xoopsDB->query($sql);
      $row = $xoopsDB->fetchArray($result);
      $tags['CATEGORY_NAME'] = $row['cattitle'];
      $tags['CATEGORY_URL'] = XOOPS_URL . '/modules/' . $xoopsModule->getVar('dirname') . '/viewcat.php?cid=' . $cid;
      $notification_handler =& xoops_gethandler('notification');   
   if($op == "addLink"){
      if ($gperm_handler->checkRight('webshow_approve', $cid, $groups, $xoopsModule->getVar('mid'))) {
         $notification_handler->triggerEvent('global', 0, 'new_link', $tags);
         $notification_handler->triggerEvent('category', $cid, 'new_link', $tags);
         if ($xoopsUser && $xoopsUser->isAdmin($xoopsModule->mid())) {
            redirect_header(XOOPS_URL . "/modules/". $xoopsModule->getVar('dirname') ."/admin/index.php?op=modLink&amp;lid=$lid",3,_WSA_DBUPDATED."<br />".$message);
         }else{
            redirect_header("index.php",3,_WS_RECEIVED."<br />"._WS_ISAPPROVED);      
         }      
      } else {
         $tags['WAITINGLINKS_URL'] = XOOPS_URL . '/modules/' . $xoopsModule->getVar('dirname') . '/admin/index.php?op=listNewLinks';
         $notification_handler->triggerEvent('global', 0, 'link_submit', $tags);
         $notification_handler->triggerEvent('category', $cid, 'link_submit', $tags);
         //if ($notify) {
            //include_once XOOPS_ROOT_PATH . '/include/notification_constants.php';
            //$notification_handler->subscribe('link', $lid, 'approve', XOOPS_NOTIFICATION_MODE_SENDONCETHENDELETE);
         //} Not used atm
         redirect_header("index.php",5,_WS_RECEIVED.'<br />'._WS_ALLPENDING);
	  }      
   }elseif($op == "modLinkS"){
      if ($gperm_handler->checkRight('webshow_approve', $cid, $groups, $xoopsModule->getVar('mid'))) {
         if ($xoopsUser && $xoopsUser->isAdmin($xoopsModule->mid())) {
            redirect_header(XOOPS_URL . "/modules/". $xoopsModule->getVar('dirname') ."/admin/index.php?op=modLink&amp;lid=$lid",3,_WSA_DBUPDATED."<br />".$message);
         }else{
            $notification_handler->triggerEvent('global', 0, 'new_link', $tags);
            $notification_handler->triggerEvent('category', $cid, 'new_link', $tags);
            $tags['LINK_URL'] = XOOPS_URL . '/modules/'. $xoopsModule->getVar('dirname') . '/singlelink.php?lid='.$lid;
            redirect_header("singlelink.php?lid=$lid",3,_WS_MODIFY."<br />"._WS_RECEIVED."<br />"._WS_ISAPPROVED."");
         }
      } else {
         $tags['LINK_URL'] = XOOPS_URL . '/modules/'. $xoopsModule->getVar('dirname') . '/singlelink.php?lid='.$newid;
         $tags['MODIFYREPORTS_URL'] = XOOPS_URL . '/modules/' . $xoopsModule->getVar('dirname') . '/admin/index.php?op=listModReq';
         $notification_handler->triggerEvent('global', 0, 'link_modify', $tags);
         //if ($notify) {
           // include_once XOOPS_ROOT_PATH . '/include/notification_constants.php';
           // $notification_handler->subscribe('link', $lid, 'approve', XOOPS_NOTIFICATION_MODE_SENDONCETHENDELETE);
         //}
         redirect_header("singlelink.php?lid=$lid",3,_WS_RECEIVED.'<br />'._WS_ALLPENDING);
      }
   }elseif($op == "approve"){
      $notification_handler->triggerEvent('global', 0, 'new_link', $tags);
      $notification_handler->triggerEvent('category', $cid, 'new_link', $tags);
      $notification_handler->triggerEvent('link', $lid, 'approve', $tags);
      redirect_header("index.php?op=listNewLinks",2,_WS_MODIFY."<br />".$message);
   }
exit();
}
?>