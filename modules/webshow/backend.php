<?php
// $Id: backend.php 2672 2009-01-11 07:49:15Z phppp $
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

include '../../mainfile.php';
$GLOBALS['xoopsLogger']->activated = false;
if (function_exists('mb_http_output')) {
    mb_http_output('pass');
}
header ('Content-Type:text/xml; charset=utf-8');

include_once XOOPS_ROOT_PATH.'/class/template.php';
$tpl = new XoopsTpl();
$tpl->xoops_setCaching(2);
$tpl->xoops_setCacheTime(3600);

$myts =& MyTextSanitizer::getInstance(); // MyTextSanitizer object

$channel_title = xoops_utf8_encode(htmlspecialchars($xoopsConfig['sitename']));
$channel_link = XOOPS_URL.'/modules/webshow/index.php';
$channel_desc = xoops_utf8_encode(htmlspecialchars($xoopsConfig['slogan']));
$image_url = XOOPS_URL . '/modules/webshow/images/logo.gif';

//** CATEGORY ID
//** Get the category id 
$cid = isset($_GET['cid']) ? intval($_GET['cid']) : '';

if (!$cid) {
   $where = "cid>0"; // DB where query
} else {
   //**Permissions
   $groups = is_object($xoopsUser) ? $xoopsUser->getGroups() : XOOPS_GROUP_ANONYMOUS;
   $gperm_handler =& xoops_gethandler('groupperm');
   // example checkRight($gperm_name, $gperm_itemid, $gperm_groupid, $gperm_modid = 1)
   if ($gperm_handler->checkRight('webshow_view', $cid, $groups, $xoopsModule->getVar('mid'))) {
      //** QUERY Selected Category
      $result=$xoopsDB->query("SELECT cid, cattitle, catdesc, imgurl FROM ".$xoopsDB->prefix("webshow_cat")." WHERE cid = $cid ");
      list($cid, $cattitle, $catdesc, $catimage) = $xoopsDB->fetchRow($result);
      $channel_title = xoops_utf8_encode( $myts->htmlSpecialChars($cattitle." "._WS_CATEGORY) );
      $channel_link = XOOPS_URL . '/modules/webshow/playcat.php?cid=' . $cid;
      $channel_desc = xoops_utf8_encode( $myts->htmlSpecialChars($catdesc) );
      $image_url = $myts->htmlSpecialChars($catimage);
      if ($cid > 0) {
         $where = "cid=$cid";
      }
   }else{
      $cid = '';
   }
}

if (!$tpl->is_cached('db:system_rss.html')) {

    $tpl->assign('channel_title', $channel_title);
    $tpl->assign('channel_link', $channel_link);
    $tpl->assign('channel_desc', $channel_desc);
    $tpl->assign('channel_lastbuild', formatTimestamp(time(), 'rss'));
    $tpl->assign('channel_webmaster', checkEmail($xoopsConfig['adminmail'], true));
    $tpl->assign('channel_editor', checkEmail($xoopsConfig['adminmail'], true));
    $tpl->assign('channel_category', 'Web Shows');
    $tpl->assign('channel_generator', 'Web Show Module for XOOPS');
    $tpl->assign('channel_language', _LANGCODE);
    $tpl->assign('image_url', $image_url);
    $dimention = getimagesize(XOOPS_ROOT_PATH . '/modules/webshow/images/logo.gif');
    if (empty($dimention[0])) {
        $width = 128;
    } else {
        $width = ($dimention[0] > 128) ? 128 : $dimention[0];
    }
    if (empty($dimention[1])) {
        $height = 128;
    } else {
        $height = ($dimention[1] > 128) ? 128 : $dimention[1];
    }
    $tpl->assign('image_width', $width);
    $tpl->assign('image_height', $height);

   $sql = "select l.lid, l.cid, l.title, l.url, l.srctype, l.listtype, l.listurl, l.entryinfo, l.listcache, l.logourl, l.credit1, l.credit2, l.credit3, l.status, l.date, l.published, l.entryperm, t.description from ".$xoopsDB->prefix("webshow_links")." l, ".$xoopsDB->prefix("webshow_text")." t where l.status>0 and $where and l.lid=t.lid ORDER by date DESC";
   $result=$xoopsDB->query($sql,20,0);
   while(list($lid, $cid, $title, $url, $srctype, $listtype, $listurl, $entryinfo, $listcache, $logourl, $credit1, $credit2, $credit3, $status, $date, $published, $ws_entryperm, $description) = $xoopsDB->fetchRow($result)) { 
     //**Permissions
      $isadmin = "";
      $adminlink = "";
      $groups = is_object($xoopsUser) ? $xoopsUser->getGroups() : XOOPS_GROUP_ANONYMOUS;
      $gperm_handler =& xoops_gethandler('groupperm');
      // example checkRight($gperm_name, $gperm_itemid, $gperm_groupid, $gperm_modid = 1)
      if ($gperm_handler->checkRight('webshow_view', $cid, $groups, $xoopsModule->getVar('mid'))) {

         //** Entry Permission
         $group = is_object($xoopsUser) ? $xoopsUser->getGroups() : array(XOOPS_GROUP_ANONYMOUS);
         $ws_entryperm = explode(" ",$ws_entryperm);
         if (count(array_intersect($group,$ws_entryperm)) > 0){

            //** TITLE
            $ltitle = xoops_utf8_encode( $myts->htmlSpecialChars($title) );

            //** BLOCK DESCRIPTION
            $description = xoops_utf8_encode( $myts->htmlSpecialChars( $description ) ); 

            //** ENTRY LOGO            
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

            //** CREDITS
            $credit1 = $myts->htmlSpecialChars($credit1);
            $credit2 = $myts->htmlSpecialChars($credit2);
            $credit3 = $myts->htmlSpecialChars($credit3);

            //** PUBDATE
            $date = formatTimestamp($date,'rss');

            //** Link
            $link = XOOPS_URL.'/modules/'.$xoopsModule->getVar('dirname').'/singlelink.php?lid='.$lid;
            
            //** GUID
            $guid = '';

            //** ITEM ARRAY
            $tpl->append('items', array( 'title' => $ltitle, 'link' => $link, 'guid' => $guid, 'pubdate' => $date, 'description' => $description, 'image' => $logourl, 'credit1' => $credit1, 'credit2' => $credit2, 'credit3' => $credit3));
         }

       } // End if entry view permissions
      } // End if Category view permissions
   }

$tpl->display('db:system_rss.html');
?>