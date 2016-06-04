<?php
// $Id: article.php,v 1.1.1.1 2005/11/10 19:51:06 phppp Exp $
// ------------------------------------------------------------------------ //
// This program is free software; you can redistribute it and/or modify     //
// it under the terms of the GNU General Public License as published by     //
// the Free Software Foundation; either version 2 of the License, or        //
// (at your option) any later version.                                      //
//                                                                          //
// You may not change or alter any portion of this comment or credits       //
// of supporting developers from this source code or any supporting         //
// source code which is considered copyrighted (c) material of the          //
// original comment or credit authors.                                      //
//                                                                          //
// This program is distributed in the hope that it will be useful,          //
// but WITHOUT ANY WARRANTY; without even the implied warranty of           //
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the            //
// GNU General Public License for more details.                             //
//                                                                          //
// You should have received a copy of the GNU General Public License        //
// along with this program; if not, write to the Free Software              //
// Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307 USA //
// ------------------------------------------------------------------------ //
// Author: phppp (D.J., infomax@gmail.com)                                  //
// URL: http://xoopsforge.com, http://xoops.org.cn                          //
// Project: Article Project                                                 //
// ------------------------------------------------------------------------ //
/**
 * @package module::article
 * @copyright copyright &copy; 2005 XoopsForge.com
 */

if (!defined("XOOPS_ROOT_PATH")) {
	exit();
}

if(!class_exists("ArtObject")) {
	//require_once(XOOPS_ROOT_PATH."/modules/".$GLOBALS["xoopsModule"]->getVar("dirname")."/class/object.php");
	require_once(XOOPS_ROOT_PATH."/modules/webshow/class/object.php");
}

/**Webshow
 *  
 * From Article Module by phppp
 * @author D.J. (phppp)
 * @copyright copyright &copy; 2005 XoopsForge.com
 * @package module::article
 *
 * {@link XoopsObject} 
 **/

if(!class_exists("Wslinks")){
  class Wslinks extends ArtObject
  {
    /**

    /**
     * Constructor
     *
     * @param int $id ID of the article
     */

    function Wslinks($id = null)
    {
	  $this->ArtObject("webshow_links");
        $this->initVar('lid', XOBJ_DTYPE_INT);
        $this->initVar('cid', XOBJ_DTYPE_INT);
        $this->initVar('title', XOBJ_DTYPE_TXTBOX);
        $this->initVar('url', XOBJ_DTYPE_TXTBOX);
        $this->initVar('srctype', XOBJ_DTYPE_TXTBOX);
        $this->initVar('listtype', XOBJ_DTYPE_TXTBOX);
        $this->initVar('listurl', XOBJ_DTYPE_TXTBOX);                
        $this->initVar('logourl', XOBJ_DTYPE_TXTBOX);
        $this->initVar('submitter', XOBJ_DTYPE_INT);
        $this->initVar('status', XOBJ_DTYPE_INT);
        $this->initVar('date', XOBJ_DTYPE_INT);
        $this->initVar('hits', XOBJ_DTYPE_INT);
        $this->initVar('rating', XOBJ_DTYPE_OTHER);
        $this->initVar('votes', XOBJ_DTYPE_INT);
        $this->initVar('comments', XOBJ_DTYPE_INT);
        $this->initVar('player', XOBJ_DTYPE_INT);
        $this->initVar('credit2', XOBJ_DTYPE_TXTBOX);
        $this->initVar('cachetime', XOBJ_DTYPE_INT);
        $this->initVar('expired', XOBJ_DTYPE_INT);
        $this->initVar('published', XOBJ_DTYPE_INT);
        $this->initVar('listcache', XOBJ_DTYPE_TXTBOX);
        $this->initVar('entryperm', XOBJ_DTYPE_TXTBOX); 
        $this->initVar('credit1', XOBJ_DTYPE_TXTBOX);                        
        $this->initVar('credit3', XOBJ_DTYPE_TXTBOX);
        $this->initVar('showinfo', XOBJ_DTYPE_TXTBOX);
    }
  }

  class webshowWslinksHandler extends ArtObjectHandler
  {
      function webshowWslinksHandler(&$db) {
        $this->ArtObjectHandler($db, 'webshow_links', 'Wslinks', 'lid', 'title');
      }
   }
}
?>