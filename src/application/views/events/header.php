<div class="container events-header" id="headline">
	<div class="sixteen columns">
		<ul class="event-list-header">
			
			<?php foreach ($all_events as $e): ?>
			<?php
			$cls = ($year == $e->e_year()) ? 'current' : '';
			?>
			<li class="<?php echo $cls ?>">
				<?php echo anchor('events/' . $e->e_year(), $e->e_year()) ?>
			</li>
			<?php endforeach; ?>
			
		</ul>
	</div>
</div>