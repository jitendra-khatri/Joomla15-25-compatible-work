<?php
/* 
 * +--------------------------------------------------------------------------+
 * | Copyright (c) 2009 Add This, LLC                                         |
 * +--------------------------------------------------------------------------+
 * | This program is free software; you can redistribute it and/or modify     |
 * | it under the terms of the GNU General Public License as published by     |
 * | the Free Software Foundation; either version 3 of the License, or        |
 * | (at your option) any later version.                                      |
 * |                                                                          |
 * | This program is distributed in the hope that it will be useful,          |
 * | but WITHOUT ANY WARRANTY; without even the implied warranty of           |
 * | MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the            |
 * | GNU General Public License for more details.                             |
 * |                                                                          |
 * | You should have received a copy of the GNU General Public License        |
 * | along with this program.  If not, see <http://www.gnu.org/licenses/>.    |
 * +--------------------------------------------------------------------------+
 */

// no direct access
	defined('_JEXEC') or die('Restricted access');
	
	$pub_id		 				= $params->get('pub_id');
	$button_style		 		= $params->get('button_style');
	$custom_url		 			= $params->get('custom_url');
	$addthis_button_language	= $params->get('addthis_button_language');
	$addthis_options 			= $params->get('addthis_options');
	$addthis_language 			= $params->get('addthis_language');
	$addthis_brand	 			= $params->get('addthis_brand');
	$addthis_header_color 		= $params->get('addthis_header_color');
	$addthis_header_background 	= $params->get('addthis_header_background');
	$addthis_offset_top			= $params->get('addthis_offset_top');
	$addthis_offset_left		= $params->get('addthis_offset_left');
	$addthis_hide_embed			= $params->get('addthis_hide_embed');
	$addthis_hover_delay		= $params->get('addthis_hover_delay');
	
	$outputValue = " <div class='joomla_add_this".$moduleclass_sfx."'>";

	$outputValue .= "<!-- AddThis Button BEGIN -->\r\n";
			
	$outputValue .= "<script type='text/javascript'>\r\n";
	
	if (trim($pub_id) != "Your Publisher ID" && trim($pub_id) != "")
	{
		$outputValue .= "var addthis_pub = '" . trim($pub_id) . "';\r\n";
	}
	if (trim($addthis_brand) != "")
	{
		$outputValue .= "var addthis_brand = '" . trim($addthis_brand) . "';\r\n";
	}
	if (trim($addthis_header_color) != "")
	{
	    $outputValue .= "var addthis_header_color = '" . trim($addthis_header_color) . "';\r\n";
	}
	if (trim($addthis_header_background) != "")
	{
	    $outputValue .= "var addthis_header_background = '" . trim($addthis_header_background) . "';\r\n";
	}
	if (trim($addthis_options) != "")
	{
		$outputValue .= "var addthis_options = '" . trim($addthis_options) . "';\r\n";
	}
	if (intval(trim($addthis_offset_top)) != 0)
	{
		$outputValue .= "var addthis_offset_top = " . $addthis_offset_top . ";\r\n";
	}
	if (intval(trim($addthis_offset_left)) != 0)
	{
		$outputValue .= "var addthis_offset_left = " . $addthis_offset_left . ";\r\n";
	}
	if (intval(trim($addthis_hover_delay)) > 0)
	{
		$outputValue .= "var addthis_hover_delay = " . $addthis_hover_delay . ";\r\n";
	}
	if (trim($addthis_language) != "")
	{
	    $outputValue .= "var addthis_language = '" . $addthis_language . "';\r\n"; 
	}
	if (trim($addthis_hide_embed) == '0')
	{
		$outputValue .= "var addthis_hide_embed = false;\r\n";
	}

	$outputValue .= "</script>\r\n";
	
	$outputValue .= "<a  href='http://www.addthis.com/bookmark.php?v=20' onMouseOver=\"return addthis_open(this, '',  '[URL]', '[TITLE]'); \"   onMouseOut='addthis_close();' onClick='return addthis_sendto();'>";
	
	$outputValue .= "<img src='";
	
    if (trim($this->_button_style === "custom"))
    {
        if (trim($this->_custom_url) == '')
        {
            $outputValue .= "http://s7.addthis.com/static/btn/" .  $this->getButtonImage('lg-share',$this->_addthis_button_language);
        }
        else $outputValue .= $this->_custom_url;
    }
    else
    {
		$outputValue .= "http://s7.addthis.com/static/btn/" . getButtonImage($button_style,$addthis_button_language);
	}
	$outputValue .= "' border='0' alt='AddThis Social Bookmark Button' />";
	$outputValue .= "</a>\r\n";
	
	$outputValue .= "<script type='text/javascript' src='http://s7.addthis.com/js/200/addthis_widget.js'></script>\r\n";
	
	$outputValue .= "<!-- AddThis Button END -->\r\n";
	
	$outputValue .= "</div>";
	
	echo $outputValue;
 
    /**
     * getButtonImage
     *
     * This is used for preparing the image button name.
     * 
     * @param string $name - Button style of addthis button selected.
     * @param string $language - The language selected for addthis button.
     */
    function getButtonImage($name, $language)
    {
        if ($name == "sm-plus")
        {
            $buttonImage = $name . '.gif';
        }
        elseif ($language != 'en')
        {
	        if ($name == 'lg-share' || $name == 'lg-bookmark' || $name == 'lg-addthis')
            {
                $buttonImage = 'lg-share-' . $language . '.gif';
            }
            elseif($name == 'sm-share' || $name == 'sm-bookmark')
            {
                $buttonImage = 'sm-share-' . $language . '.gif';
            }
        }
        else
        {
            $buttonImage = $name . '-' . $language . '.gif';
        }
 
        return $buttonImage;
    }
 
?>

