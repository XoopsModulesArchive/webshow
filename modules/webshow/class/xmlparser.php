<?php
// $Id: xmlparser.php,v 1.1.1.1 2005/11/14 00:33:48 phppp Exp $
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
 * @package module::blogline
 * @copyright copyright &copy; 2005 XoopsForge.com
 */
global $msg;

require_once "magpie.inc.php";
/**
 * XmlParser 
 * 
 * @author D.J. (phppp)
 * @copyright copyright &copy; 2005 XoopsForge.com
 * @package module::article
 *
 * {@link MagpieRSS} 
 **/

class XmlParser extends MagpieRSS
{
	var $content;
	var $charset_in;
	var $charset_out;
	
    /**
     *  Set up XML parser, parse source, and return populated RSS object..
     *
     *  @param string $content           string containing the RSS to be parsed
     *
     *
     *  @param string $output_encoding  output the parsed RSS in this character
     *                                  set defaults to ISO-8859-1 as this is PHP's
     *                                  default.
     *
     *  @param string $input_encoding   the character set of the incoming RSS source.
     *                                  Leave blank and Magpie will try to figure it
     *                                  out.
     *
     */
    function XmlParser ($content, $input_charset, $output_charset = _CHARSET, $tags = array())
    {
		if(!in_array(strtoupper($input_charset), array("UTF-8", "US-ASCII", "ISO-8859-1"))) {
		    xoops_load("XoopsLocal");
			$content = XoopsLocal::convert_encoding($content, "UTF-8", $input_charset);
			$content = preg_replace('/(<\?xml.*encoding=[\'"])(.*?)([\'"].*\?>)/m', '$1UTF-8$3', $content);
			$input_charset = "UTF-8";
		}
		$this->content = $content;
		$this->charset_in = $input_charset;
		$this->charset_out = $output_charset;
		
		/* TODO: parse specified tags only */
		$this->MagpieRSS( $content, $input_charset, $input_charset, false );
		
		//xoops_message($this);
		unset($this->content);
		$this->encoding_convert($tags);
    }

    function is_atom() {
        if ( $this->feed_type == ATOM ) {
	        $this->feed_version = empty($this->feed_version)?"0.3":$this->feed_version;
            return $this->feed_version;
        }
        else {
            return false;
        }
    }
    
    function normalize () {
        if ( $this->is_atom() ):
	    if(empty($this->channel["tagline"])){
		    /* ATOM */
		    $this->channel["tagline"] = @$this->channel["subtitle"];
		    unset($this->channel["subtitle"]);
    	}
        for ( $i = 0; $i < count($this->items); $i++) {
	        // ATOM time
	        if($date = @$this->items[$i]['modified']) continue;
	        if(empty($date)){
                $date = @$this->items[$i]['updated'];
        	}
	        if(empty($date)){
                $date = @$this->items[$i]['issued'];
        	}
	        if(empty($date)){
                $date = @$this->items[$i]['created'];
        	}
	        if(empty($date)){
                $date = @$this->items[$i]['created'];
        	}
	        $this->items[$i]['modified'] = $date;
        }
        elseif($this->is_rss() != '1.0'):
        for ( $i = 0; $i < count($this->items); $i++) {
	        if($date = @$this->items[$i]['pubdate']) continue;
	        $this->items[$i]['pubdate'] = @$this->items[$i]['dc']['date'];
        }
        endif;
	    parent::normalize();	    
    	/* ATOM */
	    if(empty($this->channel["language"]) && !empty($this->channel["dc"]["language"]) )  {
			$this->channel["language"] = $this->channel["dc"]["language"];
			unset($this->channel["dc"]["language"]);
		}
	    if(empty($this->channel["language"]) && preg_match('/<link.*hreflang=[\'"](.*?)[\'"].*?>/m', $this->content, $match) )  {
			$this->channel["language"] = $match[1];
		}
	    if(empty($this->channel["language"]) && preg_match('/<feed.*xml:lang=[\'"](.*?)[\'"].*?>/m', $this->content, $match) )  {
			$this->channel["language"] = $match[1];
		}
		/* remove to avoid redundant encoding conversion */
	    if(!empty($this->channel["tagline"])){
		    unset($this->channel["tagline"]);
	    }
		
        for ( $i = 0; $i < count($this->items); $i++) {
	        if($date_timestamp = @$this->items[$i]['date_timestamp']) continue;
	        if($date_timestamp = @$this->items[$i]['pubdate']){
                $this->items[$i]['date_timestamp'] = $date_timestamp;
            }elseif($date_timestamp = @$this->items[$i]['dc']['date']){
                $this->items[$i]['date_timestamp'] = $date_timestamp;
        	}else{
	        	$this->items[$i]['date_timestamp'] = time();
        	}
	        if(!is_numeric($this->items[$i]['date_timestamp'])){
		        if($date = parse_w3cdtf($this->items[$i]['date_timestamp'])) {
		        	$this->items[$i]['date_timestamp'] = $date;
	        	}elseif($date = strtotime($this->items[$i]['date_timestamp'])) {
		        	$this->items[$i]['date_timestamp'] = $date;
	        	}
	        }

			/* remove to avoid redundant encoding conversion */
            if ( isset($this->items[$i]['summary']) )
                unset($this->items[$i]['summary']);
            if ( isset($this->items[$i]['atom_content']))
                unset($this->items[$i]['atom_content']);
        }
	    return;
    }
    
    function encoding_convert($tags = array()){
	    if(empty($tags) || in_array("channel", $tags)){
	    	$this->channel = $this->_encoding($this->channel);
    	}
	    if(empty($tags) || in_array("items", $tags)){
	    	$this->items = $this->_encoding($this->items);
    	}
    }
    
    function _encoding($val){
	    if(is_array($val)){
		    foreach(array_keys($val) as $key){
			    $val[$key] = $this->_encoding($val[$key]);
		    }
	    }else{
		    $val = XoopsLocal::convert_encoding($val, $this->charset_out, $this->charset_in);
	    }
	    return $val;
    }
}
?>