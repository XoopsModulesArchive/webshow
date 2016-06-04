<?php
// $Id: webshow_block_tag.php, v.61 2007/07/31 19:59:00 tcnet Exp $ //
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
/** Requires Xoops Tag Module
 * Tag management for XOOPS
 *
 * @copyright	The XOOPS project http://www.xoops.org/
 * @license		http://www.fsf.org/copyleft/gpl.html GNU public license
 * @author		Taiwen Jiang (phppp or D.J.) <php_pp@hotmail.com>
 * @since		1.10
 * @version		$Id$
 * @package		module::tag
 */
/******************************************************************************
// File: xoops_version.php
 *  function webshow_tag_block_cloud_show
 *  $options:  
 *					$options[0] - number of tags to display
 *					$options[1] - time duration, in days, 0 for all the time
 *					$options[2] - max font size (px or %)
 *					$options[3] - min font size (px or %)
*
*   function webshow_tag_block_top_show
 *  $options:  
 *					$options[0] - number of tags to display
 *					$options[1] - time duration, in days, 0 for all the time
 *					$options[2] - sort: a - alphabet; c - count; t - time
 ******************************************************************************/
function webshow_tag_block_cloud_show($options) 
{
	$module_dirname ="";
	include_once XOOPS_ROOT_PATH."/modules/tag/blocks/block.php";
	return tag_block_cloud_show($options, $module_dirname);
}

function webshow_tag_block_cloud_edit($options) 
{
	include_once XOOPS_ROOT_PATH."/modules/tag/blocks/block.php";
	return tag_block_cloud_edit($options);
}

function webshow_tag_block_top_show($options) 
{
	$module_dirname ="";
	include_once XOOPS_ROOT_PATH."/modules/tag/blocks/block.php";
	return tag_block_top_show($options, $module_dirname);
}

function webshow_tag_block_top_edit($options) 
{
	include_once XOOPS_ROOT_PATH."/modules/tag/blocks/block.php";
	return tag_block_top_edit($options);
}
?>