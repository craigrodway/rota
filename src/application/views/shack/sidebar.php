<div class="boxfill">
	<ul class="remove-bottom">
		<?php
		$uri = $this->uri->uri_string();
		foreach ($sidebar_menu as $item)
		{
			$cls = 'style="font-weight: normal"';
			$regex = str_replace('/', '\/', "{$item[0]}");
			if (preg_match("/^$regex/", $uri))
			{
				$cls = 'style="font-weight: bold"';
			}
			echo '<li>' . anchor($item[0], $item[1], $cls) . '</li>';
		}
		?>
	</ul>
</div>