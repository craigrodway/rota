<?php

/**
 * Helper to write markup tags for an image in a link.
 */
function icon_link($icon_file = '', $href = '', $title = '', $attrs = '')
{
	$format = '<a class="icon-link" href="%s" title="%s" %s><img src="%s" alt="%s"></a>';
	
	$attr_str = '';
	if (is_array($attrs))
	{
		foreach ($attrs as $attr => $value)
		{
			$attr_str .= $attr . '="' . $value . '" ';
		}
	}
	else
	{
		$attr_str = $attrs;
	}
	
	// Format some items
	$href = (preg_match('/^http/', $href)) ? $href : site_url($href);
	$imgsrc = 'img/global/icons/' . $icon_file . '.png';
	
	return sprintf($format,
		$href,
		$title,
		$attr_str,
		$imgsrc,
		$title
	);
}




/**
 * Parse a date for format and calendar correctness, and return as Y-m-d format
 */
function parse_date($date)
{	
	if( ! preg_match('/^[0-3]{1}[0-9]{1}\/[0-1]{1}[0-9]{1}\/[0-9]{4}$/', $date) )
		return NULL;
	
	// Check is valid date
	list($d, $m, $y) = explode("/", $date);
	if ( ! checkdate ($m, $d, $y))
	{
		return NULL;
	}
	
	// Convert to Y-m-d format
	$date = explode('/', $date);
	$mysql_date = $date[2] . '-' . $date[1] . '-' . $date[0];
	
	return $mysql_date;
}




/**
 * Selected tab class output
 */
function tab_selected($var, $current)
{
	if ($var === $current)
	{
		return ' class="selected" ';
	}
}




/**
 * Make presenter objects of the passed array
 */
function presenters($class, $data)
{
	$class_name = $class . '_presenter';
	foreach ($data as &$item)
	{
		$item = new $class_name($item);
	}
	return $data;
}





function parse_markdown($str = '')
{
	require_once(APPPATH . '/third_party/markdown.php');
	require_once(APPPATH . '/third_party/smartypants.php');
	
	return SmartyPants(Markdown($str));
}