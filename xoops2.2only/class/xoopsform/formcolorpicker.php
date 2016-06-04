<?php
/**
* XoopsFormColorPicker component class file
* 
* This class provide a textfield with a color picker popup. This color picker
* come from Tigra project (http://www.softcomplex.com/products/tigra_color_picker/).
*
* @copyright	The Xoops project http://www.xoops.org/
* @license      http://www.fsf.org/copyleft/gpl.html GNU public license
* @author       Zoullou <webmaster@zoullou.org>
* @since        2.0.15
* @version		$Id: formcolorpicker.php 669 2006-08-25 22:14:09Z skalpa $
* @package 		xoops20
* @subpackage 	xoops20_XoopsForm
*/


if (!defined('XOOPS_ROOT_PATH')) {
	die("XOOPS root path not defined");
}


class XoopsFormColorPicker extends XoopsFormText
{

	function XoopsFormColorPicker($caption, $name, $value="#FFFFFF")
	{
		$this->XoopsFormText($caption, $name, 9, 7, $value);
	}

	function render()
	{
		if(isset($GLOBALS['xoTheme'])) {
			$GLOBALS['xoTheme']->addScript('include/color-picker.js');
		} else {
			echo "<script type=\"text/javascript\" src=\"".XOOPS_URL."/include/color-picker.js\"></script>";
		}
		$this->setExtra(' style="background-color:'.$this->getValue().';"');
		return parent::render()."\n<input type='reset' value=' ... ' onclick=\"return TCP.popup('".XOOPS_URL."/include/',document.getElementById('".$this->getName()."'));\">\n";
	}
	/**
	 * Returns custom validation Javascript
	 * 
	 * @return	string	Element validation Javascript
	 */
	function renderValidationJS() {
		$eltname    = $this->getName();
		$eltcaption = trim( $this->getCaption() );
		$eltmsg = empty($eltcaption) ? sprintf( _FORM_ENTER, $eltname ) : sprintf( _FORM_ENTER, $eltcaption );

		return "if ( !(new RegExp(\"^#[0-9a-fA-F]{6}\",\"i\").test(myform.{$eltname}.value)) ) { window.alert(\"{$eltmsg}\"); myform.{$eltname}.focus(); return false; }";
	}	

}

?>