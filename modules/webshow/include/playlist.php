<?php
// $Id: playlist.php,v.53 2007/05/01 19:59:00 tcnet Exp $ //
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

/*
XSPF generator, name playlist, save name to db, fetch external playlist file, save fetched file to path_playlist, 
*/

if (!defined('XOOPS_ROOT_PATH')){ exit(); }

include_once XOOPS_ROOT_PATH.'/class/module.errorhandler.php'; 
$myts =& MyTextSanitizer::getInstance();
$playlistmsg = "";

//** Cache Timeout
function timePlaylist($listcache,$cachetime)
{
global $xoopsConfig, $xoopsModule, $xoopsModuleConfig;
 
$listfilename = XOOPS_ROOT_PATH."/modules/".$xoopsModule->getVar("dirname")."/playlist/".$listcache;
$listtime = date ("U.", filemtime($listfilename));
$time = time();
$expired = $time - ($listtime + $cachetime);
return $expired; 
}

//** Cache Playlist Filename - Name the cached playlist file
function namePlaylist($lid,$title)
{
global $xoopsDB, $myts, $eh;
   $listcache = $lid.".xml";
   $listcache = $myts->addSlashes($listcache);
    if($lid !=0){    
       $xoopsDB->queryf("update ".$xoopsDB->prefix("webshow_links")." set listcache='$listcache' where lid=".$lid."")  or $eh->show("0013");
    }
return $listcache;
}

//** Delete playlist FILE
function deletePlaylist($lid)
{
global $xoopsConfig, $xoopsDB, $xoopsModule, $xoopsModuleConfig;
  $listresult = $xoopsDB->query("select listcache from ".$xoopsDB->prefix("webshow_links")." where lid = $lid");
  list($listcache) = $xoopsDB->fetchRow($listresult);
  // delete file
    if ($listcache != "" & file_exists(XOOPS_ROOT_PATH."/modules/".$xoopsModule->getVar("dirname")."/playlist/".$listcache)) {
      unlink(XOOPS_ROOT_PATH."/modules/".$xoopsModule->getVar("dirname")."/playlist/".$listcache); 
      echo _WSA_LISTCACHE_DELETED;
    }
}

//** Creates an XSPF playlist from a directory of media files.
function createPlaylist($lid)
{
global $xoopsDB, $xoopsConfig, $xoopsTpl, $xoopsModule, $xoopsModuleConfig, $xoopsUser, $myts, $eh;

$playlistresult = $xoopsDB->query("select title, url, srctype, listurl, listcache, credit1, credit2, credit3 from ".$xoopsDB->prefix("webshow_links")." where lid = $lid and status>0");
list($title, $url, $srctype, $listurl, $listcache, $credit1, $credit2, $credit3) = $xoopsDB->fetchRow($playlistresult);
$title = $myts->htmlSpecialChars($title);
$listcache = !$listcache ? namePlaylist($lid,$title) : $listcache;
//** Set track link  
   if($url !=""){
      $trackinfo = $url;
   }else{
      $trackinfo = XOOPS_URL."/modules/".$xoopsModule->getVar("dirname")."/singlelink.php?lid=".$lid;
   }
//** path to the directory you want to scan
$directory = "modules/".$xoopsModule->getVar("dirname")."/".$xoopsModuleConfig['path_media']."/".$listurl;
// read through the directory and filter files to an array
@$d = dir(XOOPS_ROOT_PATH."/".$directory);
   if ($d) {
      while($entry=$d->read()) {
	   if ($srctype == 'flv') {
	     $ps = strpos(strtolower($entry), '.flv');
	        if (!($ps === false)) {  
	  		$items[] = $entry; 
	  	  }					  
	   } 
	   if ($srctype == 'swf') {	  
	      $ps = strpos(strtolower($entry), '.swf');
	        if (!($ps === false)) {  
	  		  $items[] = $entry; 
	   	  }			
	   } 
	   if ($srctype == 'mp3') {	  
	      $ps = strpos(strtolower($entry), '.mp3');
	        if (!($ps === false)) {  
		  	  $items[] = $entry; 
		  }			
	   } 
	   if ($srctype == 'image') {
	      $ps = strpos(strtolower($entry), '.jpg');
		  if (!($ps === false)) {  
			   $items[] = $entry; 
		  }
              $ps = strpos(strtolower($entry), '.gif');
		  if (!($ps === false)) {  
		 	  $items[] = $entry; 
		  }
	        $ps = strpos(strtolower($entry), '.png');
		  if (!($ps === false)) {  
			  $items[] = $entry; 
		  }
	   } 
	   $ps = strpos(strtolower($entry), '.jpg');
	    if (!($ps === false)) {  
		$images[] = $entry; 
	   }
	   $ps = strpos(strtolower($entry), '.gif');
	    if (!($ps === false)) {  
		 $images[] = $entry; 
	    }
	   $ps = strpos(strtolower($entry), '.png');
	    if (!($ps === false)) {  
		 $images[] = $entry; 
	    } 
	   $ps = strpos(strtolower($entry), '.xml');
	    if (!($ps === false)) {  
		 $captions[] = $entry; 
	    } 
      }
      $d->close();
      sort($items);
      sort($images);

      if($items){
      // the playlist is built in xspf format
      // we'll first add an xml header and the opening tags .. 
      //header(\"content-type:text/xml;charset=utf-8\"); //NEEDS WORK
      $data = "<playlist version='1' xmlns='http://xspf.org/ns/0/'>\n
      <title>".$title."</title>\n
      <info>".$trackinfo."</info>\n
      <trackList>\n";
      //  then we loop through the directory files ...
      for($i=0; $i<sizeof($items); $i++) {
         $data.="<track>\n
         <title>".$items[$i]."</title>\n
         <location>".XOOPS_URL."/".$directory."/".$items[$i]."</location>\n
         <image>".XOOPS_URL."/".$directory."/".$images[$i]."</image>\n
         <info>".$trackinfo."</info>\n
         <creator>".$credit1."</creator>\n
         <album>".$credit2."</album>\n
         <label>".$credit3."</label>\n
         </track>\n";
      }  
      // .. and last we add the closing tags
      $data.="</trackList>\n</playlist>\n";

      //** Save file named $listcache to path_playlist
      $listfilename = XOOPS_ROOT_PATH."/modules/".$xoopsModule->getVar("dirname")."/playlist/".$listcache;
      $listfile = fopen($listfilename, "w+");
         if ($listfile) {
         fputs($listfile,$data);
         // fclose($listfile);
         $cachereport = "<br />"._WSA_LISTCACHE_NEW.": ".$listcache;
         } else {
            $playlistmsg =  "<br /><b>"._WSA_LISTCACHE_ERROR."</b>";
         	$xoopsDB->queryF("update ".$xoopsDB->prefix("webshow_links")." set status=-4 where lid=".$lid."");		
         }
      fclose($listfile);
      } else {
         $cachereport =  "<br />"._WSA_LISTCACHE_ERROR.": "._WS_STATUSEMPTYDIR.": ".$listcache; 
	   $xoopsDB->queryF("update ".$xoopsDB->prefix("webshow_links")." set status=-4 where lid=".$lid."");				
      }
   } else {
      $cachereport =  "<br />"._WSA_LISTCACHE_ERROR.": "._WS_STATUSNODIR.": ".$listurl;
      $xoopsDB->queryF("update ".$xoopsDB->prefix("webshow_links")." set status=-3 where lid=".$lid."");	
   }
   if($xoopsUser && $xoopsUser->isAdmin($xoopsModule->mid())){
      $cachereport = _WSA_STATUSREPORT.": ".$cachereport;
      return $cachereport;
   }
}

//** Fetch an external playlist file
function fetchPlaylist($lid)
{
  global $xoopsDB, $xoopsConfig, $xoopsTpl, $xoopsModule, $xoopsModuleConfig, $xoopsUser, $myts, $eh;

  require_once(XOOPS_ROOT_PATH."/class/snoopy.php");  
  $result = $xoopsDB->query("select title, listurl, listcache from ".$xoopsDB->prefix("webshow_links")." where lid = $lid");
  list($title, $listurl, $listcache) = $xoopsDB->fetchRow($result);
  $listcache = !$listcache ? namePlaylist($lid,$title) : $listcache;

  //** Fetch the playlist file
  $snoopy = new Snoopy;
  $data = "";
	if (@$snoopy->fetch($listurl)){
         $data = (is_array($snoopy->results))?implode("\n",$snoopy->results):$snoopy->results;
         //** Save file named $listcache to path_playlist
        $listfilename = XOOPS_ROOT_PATH."/modules/".$xoopsModule->getVar("dirname")."/playlist/".$listcache;
        $listfile = fopen($listfilename, "w+");
        if ($listfile) {
           fputs($listfile,$data);
        }
        fclose($listfile);
        $cachereport = _WSA_LISTCACHE_FETCH;
	} else {
         //**the web feed fetch has failed, reset status and report to admin
         $xoopsDB->queryF("update ".$xoopsDB->prefix("webshow_links")." set status=-4 where lid=".$lid."");	         
         if($xoopsUser && $xoopsUser->isAdmin($xoopsModule->mid())){
            $cachereport = "<a href=\"".XOOPS_URL."/modules/".$xoopsModule->getVar('dirname')."/admin/index.php?op=modLink&amp;lid=$lid\" target=\"_blank\">"._WSA_LISTCACHE_FETCH_ERROR."</a>";        
         }              	   
     }  
   if($xoopsUser && $xoopsUser->isAdmin($xoopsModule->mid())){
      $cachereport = _WSA_STATUSREPORT.": ".$cachereport;
      return $cachereport;
   }
}

function cacheAllplaylist()
{
  global $xoopsDB, $xoopsConfig, $xoopsModule, $xoopsModuleConfig;
  $feedresult = $xoopsDB->query("select lid from ".$xoopsDB->prefix("webshow_links")." where listtype = 'feed' and status>0");
     while(list($lid) = $xoopsDB->fetchRow($feedresult)) {
       namePlaylist($lid,"");  
       fetchPlaylist($lid);
     }    
  $dirresult = $xoopsDB->query("select lid from ".$xoopsDB->prefix("webshow_links")." where listtype = 'dir' and status>0");
     while(list($lid) = $xoopsDB->fetchRow($dirresult)) {     
       namePlaylist($lid,"");
       createPlaylist($lid);
     } 
}
?>