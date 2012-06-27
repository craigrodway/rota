<div class="boxfill">
	<ul class="remove-bottom">
		<?php
		foreach ($sidebar_menu as $item)
		{
			echo '<li>' . anchor($item[0], $item[1], 'style="font-weight: normal"') . '</li>';
		}
		?>
	</ul>
</div>