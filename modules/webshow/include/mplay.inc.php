<?php
// $Id: mplay.inc.php,v.67 2009/04/04 19:59:00 tcnet Exp $ //
// Flash Media Player by Jeroen Wijering ( http://www.jeroenwijering.com ) is licensed under a Creative Commons License (http://creativecommons.org/licenses/by-nc-sa/2.0/) //
// It allows you to use and modify the script for noncommercial purposes. //
// For commercial use you must purchase a license from Jereon Wijering at http://www.jeroenwijering.com/?order=form. //
// You must share a like any modifications. // 
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

/* Javascript functions
Here's an example of all the javascript interactions the players/rotator support. 
Note that javascript controls only work from Flash version 8 and only online, not locally! In order to make them work, 
make sure you have copied the javascript functions in the header of this page. 
Also make sure that, through SJWObject or the embed tag, you've given the SWF an ID that is similar to the id used in the javascripts (in this example it is "mpl").
An overview of all elements you can send along with a loadFile() or addItem() function can be found in the 
<a href="http://www.jeroenwijering.com/extras/readme.html#playlists">playlists table in the readme</a>.
*/

if (!defined('XOOPS_ROOT_PATH')){ exit(); }
$mplayjs = '
<script type="text/javascript">
// some variables to save
var currentItem;
var currentState;
var currentLoad;

// this function is caught by the JavascriptView object of the player.
function sendEvent(typ,prm) { thisMovie("play'.$lid.'").sendEvent(typ,prm); };

// these functions is called by the JavascriptView object of the player.
function getUpdate(typ,pr1,pr2,swf) { 
	if(typ == "item") { currentItem = pr1; setTimeout("getItemData(currentItem)",100);}
};

function getItemData(idx) {
	var obj = thisMovie("play'.$lid.'").itemData(idx);
	var tmp = document.getElementById("tracktitle"); if (tmp) { tmp.innerHTML = "<strong>'._WS_TRACKTITLE.'</strong> " + obj["title"]; }
	var tmp = document.getElementById("trackdescription"); if (tmp) { tmp.innerHTML = "<strong>'._WS_TRACKDESC.'</strong> " + obj["description"]; }
	//var tmp = document.getElementById("trackdate"); if (tmp) { tmp.innerHTML = "<strong>'._WS_TRACKDATE.'</strong> " + obj["date"]; } 
	//var tmp = document.getElementById("trackfile"); if (tmp) { tmp.innerHTML = "<strong>'._WS_TRACKFILE.'</strong> " + obj["file"]; } 
	//var tmp = document.getElementById("tracklink"); if (tmp) { tmp.innerHTML = "<strong>'._WS_TRACKLINK.'</strong> " + obj["link"]; } 
	//var tmp = document.getElementById("tracktype"); if (tmp) { tmp.innerHTML = "<strong>'._WS_TRACKTYPE.'</strong> " + obj["type"]; } 
	//var tmp = document.getElementById("trackid"); if (tmp) { tmp.innerHTML = "<strong>'._WS_TRACKID.'</strong> " + obj["id"]; } 
	//var tmp = document.getElementById("trackimage"); if (tmp) { tmp.innerHTML = "<strong>'._WS_TRACKIMAGE.'</strong> " + obj["image"]; } 
	//var tmp = document.getElementById("trackauthor"); if (tmp) { tmp.innerHTML = "<strong>'._WS_TRACKAUTHOR.'</strong> " + obj["author"]; } 
	//var tmp = document.getElementById("trackcaptions"); if (tmp) { tmp.innerHTML = "<strong>'._WS_TRACKCAPTIONS.'</strong> " + obj["captions"]; } 
	//var tmp = document.getElementById("trackaudio"); if (tmp) { tmp.innerHTML = "<strong>'._WS_TRACKAUDIO.'</strong> " + obj["audio"]; } 
	//var tmp = document.getElementById("trackstart"); if (tmp) { tmp.innerHTML = "<strong>'._WS_TRACKSTART.'</strong> " + obj["start"]; }  
	//var tmp = document.getElementById("trackcategory"); if (tmp) { tmp.innerHTML = "<strong>'._WS_TRACKCAT.'</strong> " + obj["category"]; } 
	//var tmp = document.getElementById("tracklatitude"); if (tmp) { tmp.innerHTML = "<strong>'._WS_TRACKLAT.'</strong> " + obj["latitude"]; } 
	//var tmp = document.getElementById("tracklongitude"); if (tmp) { tmp.innerHTML = "<strong>'._WS_TRACKLONG.'</strong> " + obj["longitude"]; } 
	//var tmp = document.getElementById("trackcity"); if (tmp) { tmp.innerHTML = "<strong>'._WS_TRACKCITY.'</strong> " + obj["city"]; } 
 
};

// These functions are caught by the feeder object of the player.
function loadFile(obj) { thisMovie("play'.$lid.'").loadFile(obj); };

// This is a javascript handler for the player and is always needed.
function thisMovie(movieName) {
	if(navigator.appName.indexOf("Microsoft") != -1) {
		return window[movieName];
	} else {
		return document[movieName];
	}
};
</script>';

/* More API Functions 
	      <b>SEND EVENTS</b>	       
	      <ul>
		<li><a href="javascript:sendEvent(\'playpause\')">Toggle the pause state</a>.</li>
		<li><a href="javascript:sendEvent(\'prev\')">Play the previous item</a>.</li>
		<li><a href="javascript:sendEvent(\'next\')">Play the next item</a>.</li>
		<li><a href="javascript:sendEvent(\'scrub\',currentPosition + 5)">Scrub 5 seconds forward</a>.</li>
		<li><a href="javascript:sendEvent(\'scrub\',currentPosition - 5)">Scrub 5 seconds backward</a>.</li>
		<li><a href="javascript:sendEvent(\'volume\',currentVolume + 10)">Increase the volume 10%</a></li>
		<li><a href="javascript:sendEvent(\'volume\',currentVolume - 10)">Decrease the volume 10%</a></li>
		<li><a href="javascript:sendEvent(\'playitem\',1)">Play the 2nd item of the playlist</a>.</li>
		<li><a href="javascript:sendEvent(\'getlink\',1)">Go to the 2nd link of the playlist</a>.</li>
		<li><a href="javascript:sendEvent(\'stop\')">Stop loading and playing</a>.</li>
	      </ul>

//API Data Updates
<script type="text/javascript">
	var currentPosition;
	var currentVolume;
	var currentItem;
	function sendEvent(typ,prm) { thisMovie("play'.$lid.'").sendEvent(typ,prm); };
	function getUpdate(typ,pr1,pr2) {
		if(typ == "time") { currentPosition = pr1; }
		else if(typ == "volume") { currentVolume = pr1; }
		else if(typ == "item") { getItemData(pr1); }
		var id = document.getElementById(typ);
		id.innerHTML = typ+ ": "+Math.round(pr1);
		pr2 == undefined ? null: id.innerHTML += ", "+Math.round(pr2);
	};
	function loadFile(obj) { thisMovie("play'.$lid.'").loadFile(obj); };
	function addItem(obj,idx) { thisMovie("play'.$lid.'").addItem(obj,idx); }
	function removeItem(idx) { thisMovie("play'.$lid.'").removeItem(idx); }
	function getItemData(idx) {
		var obj = thisMovie("play'.$lid.'").itemData(idx);
		var nodes = "";
		for(var i in obj) { 
			nodes += "<li>"+i+": "+obj[i]+"</li>"; 
		}
		document.getElementById("data").innerHTML = nodes;
	};
	function thisMovie(movieName) {
	    if(navigator.appName.indexOf("Microsoft") != -1) {
			return window[movieName];
		} else {
			return document[movieName];
		}
	};
</script>

	       <b>Updates</b>
	       <ul>
		 <li id="item">&nbsp;</li>
		 <li id="volume">&nbsp;</li>
		 <li id="state">&nbsp;</li>
		 <li id="time">&nbsp;</li>
		 <li id="load">&nbsp;</li>
		 <li id="size">&nbsp;</li>
	       </ul>
*/
?>