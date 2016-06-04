<?php
// $Id: wsimage.php  2009-04-12 00:11:25EST TCNet $
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
// Author: Technical Crew Network (AKA TCNet)                                          //
// URL: http://wikiwebshow.com/ //
// Project: WebShow Module for XOOPS                                                //
// ------------------------------------------------------------------------- //

if (!defined('XOOPS_ROOT_PATH')){ exit(); }

/* function fetchLogo
* Uses snoopy to fetch image file from external url
*/
function fetchLogo($fetchurl,$lid)
{
      global $xoopsConfig, $xoopsModule, $xoopsModuleConfig, $allowed_mimetypes;
	  //** Fetch
      $logoinfo = getimagesize($fetchurl);
      $mime = $logoinfo['mime'];
	  if(!$mime && !in_array($mime,$allowed_mimetypes)){
            echo _WSA_LOGO_FETCHFAIL."<br />"._WSA_MIMETYPE_INVALID;
            return false; 
	  }        
      $logoext = trim(str_replace('image/','',$mime));
 	  require_once(XOOPS_ROOT_PATH."/class/snoopy.php");  
 	  $snoopy = new Snoopy;
	  if (@$snoopy->fetch($fetchurl)){
	     //** Rename the fetched file and save to logo path
	     $logofilename = $lid.'.'.$logoext;
	     $fetchurl = $logofilename; 	          	         
	     $logofilename = XOOPS_ROOT_PATH . "/".$xoopsModuleConfig['path_logo']."/".$logofilename;
	     $logofile = fopen($logofilename, "w+");
	     if ($logofile) {
  		    $fetchedlogo = $snoopy->results;       
  	        fputs($logofile,$fetchedlogo);
	     }
	     fclose($logofile);
	     echo _WSA_LOGO_FETCH;
	  } else {
         echo _WSA_LOGO_FETCHFAIL." ".$snoopy->error;
         return false;
      }  
return $fetchurl;
}

//** Delete Entry Logo
function deleteEntrylogo($lid)
{
global $xoopsConfig, $xoopsDB, $xoopsModule, $xoopsModuleConfig;
  $result = $xoopsDB->query("select logourl from ".$xoopsDB->prefix("webshow_links")." where lid = $lid");
  list($logourl) = $xoopsDB->fetchRow($result);
  if($logourl !='') {
  // delete file
     $ps = strpos($logourl,$lid); //Prevents delete of stock and external logos 
     if ($ps === true) {  
        if (file_exists(XOOPS_ROOT_PATH . "/".$xoopsModuleConfig['path_logo']."/".$logourl)) {
           unlink(XOOPS_ROOT_PATH . "/".$xoopsModuleConfig['path_logo']."/".$logourl); 
           echo _WSA_LOGO_DELETED." ".XOOPS_ROOT_PATH . "/".$xoopsModuleConfig['path_logo']."/".$logourl."<br />"; 
        }
     }
   }
   return;
}

//** Smart Resize Image
// Resize and save jpg, png or gif images.  Requires GD version 2
// Written by Maxim Chernyak from http://mediumexposure.com/techblog/smart-image-resizing-while-preserving-transparency-php-and-gd-library
/*
-If you pass width as 0 (zero) -- this function will disregard width, and use height as constraint. Same vice versa.
-If you set "proportional" to false - the function will simply stretch (or shrink) the image to its full constraints.
-If one of the dimensions is set to zero, and proportional set to "false" - then the image will be forced to stretch or shrink the other dimension, and disregard the zeroed dimension (leave it the same).
-If proportional is set to true - the image will resize to constraints proportionally, once again, with possibility to have either width or height set to zero.
-The function can use either linux "rm" command, or php @unlink. Most probably you don't need to ever use that flag, but on some setups - @unlink won't work due to user access restrictions.
-The function will simply replace the file that you give it, with the resized file.
-The function supports gif, png, and jpeg, and preserves the transparency of gif and png images.
*/
 
function smart_resize_image( $file, $width = 0, $height = 0, $proportional = false, $output = 'file', $delete_original = true, $use_linux_commands = false )
    {
        if ( $height <= 0 && $width <= 0 ) {
            return false;
        }

        $info = getimagesize($file);
        $image = '';

        $final_width = 0;
        $final_height = 0;
        list($width_old, $height_old) = $info;

        if ($proportional) {
            if ($width == 0) $factor = $height/$height_old;
            elseif ($height == 0) $factor = $width/$width_old;
            else $factor = min ( $width / $width_old, $height / $height_old);   

            $final_width = round ($width_old * $factor);
            $final_height = round ($height_old * $factor);

        }
        else {
            $final_width = ( $width <= 0 ) ? $width_old : $width;
            $final_height = ( $height <= 0 ) ? $height_old : $height;
        }

        switch ( $info[2] ) {
            case IMAGETYPE_GIF:
                $image = imagecreatefromgif($file);
            break;
            case IMAGETYPE_JPEG:
                $image = imagecreatefromjpeg($file);
            break;
            case IMAGETYPE_PNG:
                $image = imagecreatefrompng($file);
            break;
            default:
                return false;
        }
        
        $image_resized = imagecreatetruecolor( $final_width, $final_height );
                
        if ( ($info[2] == IMAGETYPE_GIF) || ($info[2] == IMAGETYPE_PNG) ) {
            $trnprt_indx = imagecolortransparent($image);
   
            // If we have a specific transparent color
            if ($trnprt_indx >= 0) {
   
                // Get the original image's transparent color's RGB values
                $trnprt_color    = imagecolorsforindex($image, $trnprt_indx);
   
                // Allocate the same color in the new image resource
                $trnprt_indx    = imagecolorallocate($image_resized, $trnprt_color['red'], $trnprt_color['green'], $trnprt_color['blue']);
   
                // Completely fill the background of the new image with allocated color.
                imagefill($image_resized, 0, 0, $trnprt_indx);
   
                // Set the background color for new image to transparent
                imagecolortransparent($image_resized, $trnprt_indx);
   
            
            } 
            // Always make a transparent background color for PNGs that don't have one allocated already
            elseif ($info[2] == IMAGETYPE_PNG) {
   
                // Turn off transparency blending (temporarily)
                imagealphablending($image_resized, false);
   
                // Create a new transparent color for image
                $color = imagecolorallocatealpha($image_resized, 0, 0, 0, 127);
   
                // Completely fill the background of the new image with allocated color.
                imagefill($image_resized, 0, 0, $color);
   
                // Restore transparency blending
                imagesavealpha($image_resized, true);
            }
        }

        imagecopyresampled($image_resized, $image, 0, 0, 0, 0, $final_width, $final_height, $width_old, $height_old);
    
        if ( $delete_original ) {
            if ( $use_linux_commands )
                exec('rm '.$file);
            else
                @unlink($file);
        }
        
        switch ( strtolower($output) ) {
            case 'browser':
                $mime = image_type_to_mime_type($info[2]);
                header("Content-type: $mime");
                $output = NULL;
            break;
            case 'file':
                $output = $file;
            break;
            case 'return':
                return $image_resized;
            break;
            default:
            break;
        }

        switch ( $info[2] ) {
            case IMAGETYPE_GIF:
                imagegif($image_resized, $output);
            break;
            case IMAGETYPE_JPEG:
                imagejpeg($image_resized, $output);
            break;
            case IMAGETYPE_PNG:
                imagepng($image_resized, $output);
            break;
            default:
                return false;
        }

        return true;
    }
?>
