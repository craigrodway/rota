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