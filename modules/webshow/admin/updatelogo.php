<?php
//** Update logo thumbnails
// Manually move your stock logos to uploads/webshow/logos/stock folder before running this script.
// Rename existing images to lid.ext instead of title.ext
// Fetch external logos.
// Save new logourl to db

include '../../../include/cp_header.php';
if ( file_exists("../language/".$xoopsConfig['language']."/main.php") ) {
	include "../language/".$xoopsConfig['language']."/main.php";
} else {
	include "../language/english/main.php";
}
include '../include/functions.php';
include '../include/submit.functions.php';
include_once XOOPS_ROOT_PATH.'/class/module.errorhandler.php'; 
$eh = new ErrorHandler;
$myts =& MyTextSanitizer::getInstance();

//** Logo Config	
$logowidth = $xoopsModuleConfig['logowidth'];
$logoheight = $xoopsModuleConfig['logowidth'];  //Square images

echo "<table class='outer' style ='width: 100%; font-size: 90%;' border='1'><th colspan='4'>Update Entry Logo to new directory structure</th>
<tr colspan='4' style='font-weight: 600;'><td>LID</td><td>Old File Name</td><td>New File Name</td></tr>";
$result = $xoopsDB->query("select lid, logourl from ".$xoopsDB->prefix("webshow_links")." where lid>0 and logourl!=''");
while(list($lid,$logourl) = $xoopsDB->fetchRow($result)) {
   echo "<tr colspan='4'><td>".$lid."</td><td>".$logourl."</td>";
   $newlogourl = '';
      //If logo is not at an external url
       $ps = strpos($logourl, 'http://');
       if ($ps === false) {
          // If the image file is in the stock logo directory
          if(file_exists(XOOPS_ROOT_PATH . "/".$xoopsModuleConfig['path_logo']."/stock/".$logourl)) {
               $newlogourl = "stock/".$logourl; 
          }elseif(!$newlogourl & file_exists(XOOPS_ROOT_PATH . "/".$xoopsModuleConfig['path_logo']."/".$logourl)) {
              // If the image file is in the stock logo directory  
              $oldlogopath = XOOPS_ROOT_PATH . "/".$xoopsModuleConfig['path_logo']."/".$logourl; 
              $logoext = substr($logourl, -4);
              $newlogourl = $lid.$logoext;
              $newlogopath = XOOPS_ROOT_PATH . "/".$xoopsModuleConfig['path_logo']."/".$newlogourl;
              if (file_exists($newlogopath)) {
                   unlink($newlogopath); 
              }
              rename($oldlogopath,$newlogopath);          
          }
       }else{
          // Fetch, name and save the image from the external url 
          $newlogourl = fetchLogo($logourl,$lid);
          // Resize the fetched image
          smart_resize_image(XOOPS_ROOT_PATH."/".$xoopsModuleConfig['path_logo']."/".$newlogourl, $logowidth, $logoheight, true, 'file', true, false); 
       }
   echo "<td>".$newlogourl."</td></tr>";

   $sql = "update ".$xoopsDB->prefix("webshow_links")." set logourl='".$newlogourl."' where lid=".$lid;
   $xoopsDB->queryF($sql) or $eh->show("0013");
}
echo "</table>";
echo "<div>Logo Update Complete</div>";
xoops_cp_footer();
?>