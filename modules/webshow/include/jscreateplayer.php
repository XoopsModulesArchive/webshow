<?php
/* Under construction
//** Create Player with javascript
// Load the selected mediafile in the default player.
function createPlayer(mediafile) {
	var s1 = new SWFObject("http://localhost/testsite23/modules/webshow/mediaplayer.swf","play'.$lid.'","320","320","8");
	s1.addParam("allowfullscreen","true");
	s1.addParam("allowscriptaccess","always");
	s1.addVariable("file", mediafile);
	s1.addVariable("shuffle","false");
	s1.addVariable("linktarget","_blank");
	s1.addVariable("enablejs","true");
	s1.addVariable("javascriptid","play'.$lid.'");
	s1.addVariable("width","320");
	s1.addVariable("height","320");
	s1.addVariable("displayheight","240");
	s1.addVariable("autostart","true")
	s1.write("movie'.$lid.'");
};

//** Javascript Movie Loader
// Create a Player with javascript.
$jsmovie = '<script type="text/javascript">
function createPlayer(mediafile,listtype) { 
if (listtype == "embed")
document.getElementById("movie'.$lid.'").innerHTML = "EMBED TEST Movie Inc";
else
var s'.$lid.' = new SWFObject("'.XOOPS_URL.'/modules/'.$xoopsModule->getVar('dirname').'/'.$flashplayer.'","play'.$lid.'","'.$width.'","'.$height.'","7");s'.$lid.'.addParam("allowfullscreen","true");s'.$lid.'.addParam("allowscriptaccess","always");'.$bgcolor.'s'.$lid.'.addVariable("autostart","true");s'.$lid.'.addVariable("file",mediafile);s'.$lid.'.addVariable("id","'.$lid.'");s'.$lid.'.addVariable("width","'.$width.'");s'.$lid.'.addVariable("height","'.$height.'");s'.$lid.'.addVariable("displaywidth","'.$displaywidth.'");s'.$lid.'.addVariable("displayheight","'.$displayheight.'");'.$backcolor.$frontcolor.$lightcolor.$screencolor.$jsid.$type.$singletitle.$image.$showdigits.$usefullscreen.$scroll.$largecontrols.$shuffle.$replay.$linkfromdisplay.$link.$linktarget.$showicons.$overstretch.$showeq.$thumbsinplaylist.$rotatetime.$shownavigation.$transition.$captions.$playerlogo.$enablejs.$showdownload.$callback.$audio.$searchbar.$searchlink.'s'.$lid.'.write("movie'.$lid.'");
}</script>';
            $xoopsTpl->assign('jsmovie',$jsmovie);

<a href="#" rel="nofollow" onclick="createPlayer('<{$link.fileurl}>','<{$link.listtype}>'); return false;"><{$smarty.const._WS_PLAY}></a>  

*/

//** Javascript Movie Loader
// Create a Player with javascript.
$jsmovie = '<script type="text/javascript">
function createPlayer(mediafile,listtype) { 
if (listtype == "embed")
document.getElementById("movie'.$lid.'").innerHTML = "EMBED TEST Movie Inc";
else
//document.getElementById("movie'.$lid.'").innerHTML = "JS Movie Test";
//var s'.$lid.' = new SWFObject("'.XOOPS_URL.'/modules/'.$xoopsModule->getVar('dirname').'/'.$flashplayer.'","play'.$lid.'","'.$width.'","'.$height.'","7");s'.$lid.'.addParam("allowfullscreen","true");s'.$lid.'.addParam("allowscriptaccess","always");'.$bgcolor.'s'.$lid.'.addVariable("autostart","true");s'.$lid.'.addVariable("file",mediafile);s'.$lid.'.addVariable("id","'.$lid.'");s'.$lid.'.addVariable("width","'.$width.'");s'.$lid.'.addVariable("height","'.$height.'");s'.$lid.'.addVariable("displaywidth","'.$displaywidth.'");s'.$lid.'.addVariable("displayheight","'.$displayheight.'");'.$backcolor.$frontcolor.$lightcolor.$screencolor.$jsid.$type.$singletitle.$image.$showdigits.$usefullscreen.$scroll.$largecontrols.$shuffle.$replay.$linkfromdisplay.$link.$linktarget.$showicons.$overstretch.$showeq.$thumbsinplaylist.$rotatetime.$shownavigation.$transition.$captions.$playerlogo.$enablejs.$showdownload.$callback.$audio.$searchbar.$searchlink.'s'.$lid.'.write("movie'.$lid.'");
	var s1 = new SWFObject("http://localhost/testsite23/modules/webshow/mediaplayer.swf","play'.$lid.'","320","320","8");
	s1.addParam("allowfullscreen","true");
	s1.addParam("allowscriptaccess","always");
	s1.addVariable("file", mediafile);
	s1.addVariable("shuffle","false");
	s1.addVariable("linktarget","_self");
	s1.addVariable("enablejs","true");
	s1.addVariable("javascriptid","play'.$lid.'");
	s1.addVariable("width","320");
	s1.addVariable("height","320");
	s1.addVariable("displayheight","240");
	s1.addVariable("autostart","true")
	s1.write("movie'.$lid.'");
}</script>';
//$xoopsTpl->assign('jsmovie',$jsmovie);
print $jsmovie;
?>