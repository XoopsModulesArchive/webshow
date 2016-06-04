<?php
/**
 * Tag info
 *
 * @copyright	The XOOPS project http://www.xoops.org/
 * @license		http://www.fsf.org/copyleft/gpl.html GNU public license
 * @author		Taiwen Jiang (phppp or D.J.) <php_pp@hotmail.com>
 * @since		1.00
 * @version		$Id$
 * @package		module::tag
 */
if (!defined('XOOPS_ROOT_PATH')){ exit(); }

/**
 * Get item fields:
 * title
 * content
 * time
 * link
 * uid
 * uname
 * tags
 *
 * @var		array	$items	associative array of items: [modid][catid][itemid]
 *
 * @return	boolean
 * 
 */
/** Get item fields: title, content, time, link, uid, uname, tags **/

function webshow_tag_iteminfo(&$items)
{

	$items_id = array();
	$cat_id = $cid;
	foreach(array_keys($items) as $cat_id){
		// Some handling here to build the link upon catid
			// If catid is not used, just skip it
		foreach(array_keys($items[$cat_id]) as $item_id){
			// In article, the item_id is "art_id"
			$items_id[] = intval($item_id);
		}
	}
	$item_handler =& xoops_getmodulehandler("Wslinks","webshow");
	$criteria = new Criteria("lid", "(".implode(", ", $items_id).")", "IN");
	$items_obj = $item_handler->getObjects($criteria, 'lid');
	
	foreach(array_keys($items) as $cat_id){
		foreach(array_keys($items[$cat_id]) as $item_id){
			$item_obj =& $items_obj[$item_id];
			$items[$cat_id][$item_id] = array(
				"title"		=> $item_obj->getVar("title"),
				"uid"		=> $item_obj->getVar("submitter"),
				"link"		=> "singlelink.php?lid={$item_id}",
				"time"		=> $item_obj->getVar("date"),
				"tags"		=> tag_parse_tag($item_obj->getVar("item_tag", "n")), // optional
				"content"	=> "",
				);
		}
	}
	unset($items_obj);	
}

/**
 * Remove orphan tag-item links
 *
 * @return	boolean
 * 
 */
function webshow_tag_synchronization($mid)
{
	$item_handler =& xoops_getmodulehandler("Wslinks", "webshow");
	$link_handler =& xoops_getmodulehandler("link", "tag");
        
	/* clear tag-item links */
	if($link_handler->mysql_major_version() >= 4):
    $sql =	"	DELETE FROM {$link_handler->table}".
    		"	WHERE ".
    		"		tag_modid = {$mid}".
    		"		AND ".
    		"		( tag_itemid NOT IN ".
    		"			( SELECT DISTINCT {$item_handler->keyName} ".
    		"				FROM {$item_handler->table} ".
    		"				WHERE {$item_handler->table}.status > 0".
    		"			) ".
    		"		)";
    else:
    $sql = 	"	DELETE {$link_handler->table} FROM {$link_handler->table}".
    		"	LEFT JOIN {$item_handler->table} AS aa ON {$link_handler->table}.tag_itemid = aa.{$item_handler->keyName} ".
    		"	WHERE ".
    		"		tag_modid = {$mid}".
    		"		AND ".
    		"		( aa.{$item_handler->keyName} IS NULL".
    		"			OR aa.status < 1".
    		"		)";
	endif;
    if (!$result = $link_handler->db->queryF($sql)) {
        //xoops_error($link_handler->db->error());
  	}
}
?>