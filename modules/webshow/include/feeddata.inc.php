<?php
// $Id: feeddata.inc.php,v.71 2009/05/11 19:59:00 tcnet Exp $ //
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
//** feeddata.inc.php has functions that parse and display data from rss and atom feeds.

//** Feed Data
// Get the xml feed data and parse to html
if( $listtype == "feed" || $listtype == "dir" ) {
   if (in_array('feeddata',$showinfo) & in_array('feeddata',$entryinfo) & $listcache != '') {   
      // Makes a string from the local listcache file and parses it
      $content = file_get_contents(XOOPS_ROOT_PATH.'/modules/'.$xoopsModule->getVar("dirname").'/playlist/'.$listcache, true);
      include_once( XOOPS_ROOT_PATH.'/modules/'.$xoopsModule->getVar("dirname").'/class/magpie.inc.php');
      $rss = new MagpieRSS($content, 'UTF-8', 'UTF-8', '');

      /* 
      // Left here for testing only.  May change the fetch function to use magpierss. 
      // This one will fetch, cache and parse the feed from a url.
            require_once('./magpierss/rss_fetch.inc');
            $rss = fetch_rss(XOOPS_URL.'/modules/'.$xoopsModule->getVar("dirname").'/playlist/'.$listcache);
      */

      // Get the channel data and assign it to the feedchannel array
      $channeltitle = !empty ( $rss->channel['title'] ) ? $myts->htmlSpecialChars($rss->channel['title']) : '';
      $channellink = !empty ( $rss->channel['link'] ) ? $rss->channel['link'] : '';
      $channeldesc = !empty ( $rss->channel['description'] ) ? $myts->displayTarea($rss->channel['description'],0) : ''; //Set 0 to 1 to allow html
      $channelimage = '';
      if (!empty($rss->image['url'])) {
         $channelimage = $rss->image['url'];
         // Channel Image resize needs work.  Image must be stored local for resize to work
         // example smart_resize_image( $file, $width = 0, $height = 0, $proportional = false, $output = 'file', $delete_original = true, $use_linux_commands = false )
         //if(@include_once(XOOPS_ROOT_PATH.'/modules/'.$xoopsModule->getVar("dirname").'/include/wsimage.inc.php')) {  
            //$channelimage = smart_resize_image( $channelimage, $logowidth, 0, true, 'file', false, false );
         //}
      }
      $xoopsTpl->assign('wsfeedchannel', array('title' => $channeltitle, 'link' => $channellink, 'description' => $channeldesc, 'image' => $channelimage, ));
      // Get the track items from the feed.
      $itemlimit = 20;  //Limit number of items in array
      $itemnum = 0;
      foreach ($rss->items as $item) {
         $itemtitle = !empty($item['title']) ? $myts->htmlSpecialChars($item['title']) : '';
         $itemlink = !empty($item['link']) ? $item['link'] : '';
         $itemdesc = !empty($item['description']) ? $myts->displayTarea($item['description'],1) : '';
         $itemguid = !empty($item['guid']) ? $item['guid'] : '';
         $itempubdate = !empty($item['pubdate']) ? $item['pubdate'] : '';
         //$item['content']['encoded'];
      
         $xoopsTpl->append('wsfeeddata', array('itemnum' => $itemnum, 'title' => $itemtitle, 'link' => $itemlink, 'description' => $itemdesc, 'guid' => $itemguid, 'pubdate' => $itempubdate));
         $itemnum = $itemnum + 1;
         if ( $itemnum == $itemlimit ) {
            break;
         }
      }   
   }
}
?>