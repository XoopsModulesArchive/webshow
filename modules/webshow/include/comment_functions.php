<?php
if (!defined('XOOPS_ROOT_PATH')){ exit(); }
// comment callback functions

function webshow_com_update($link_id, $total_num){
	$db =& Database::getInstance();
	$sql = 'UPDATE '.$db->prefix('webshow_links').' SET comments = '.$total_num.' WHERE lid = '.$link_id;
	$db->query($sql);
}

function webshow_com_count($com_item_id) {
//Count comments
}

function webshow_com_approve(&$comment){
	// notification mail here
}
?>