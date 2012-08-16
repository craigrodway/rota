<?php
$url_suffix = array(
	'list' => '',
	'map' => '/map',
);
?>


<div class="container events-header" id="headline">
	<div class="sixteen columns">
		<ul class="event-list-header">
			
			<?php foreach ($all_events as $e): ?>
			<?php
			$cls = ($year == $e->e_year()) ? 'current' : '';
			?>
			<li class="<?php echo $cls ?>">
				<?php echo anchor('events/' . $e->e_year() . $url_suffix[$view_mode], $e->e_year()) ?>
			</li>
			<?php endforeach; ?>
			
		</ul>
	</div>
</div>

<div class="event-info">

	<div class="container">

		<div class="two columns total">
			<span class="total-figure"><?php echo count($stations) ?></span>
			<span class="total-desc">reg&rsquo;d stations</span>
		</div>
		
		<div class="two columns total">
			<span class="total-figure"><?php echo $stats['operator_count'] ?></span>
			<span class="total-desc">operators</span>
		</div>
		
		<div class="two columns total">
			<span class="total-figure"><?php echo $stats['railway_count'] ?></span>
			<span class="total-desc">railways</span>
		</div>
		
		<?php if ($view_mode == 'list'): ?>
		<div class="two columns offset-by-six link">
			<a href="<?php echo site_url('events/' . $year . '/map') ?>"><img src="img/global/icons/map.png"><span>View map</span></a>
		</div>
		<?php endif; ?>
		
		<?php if ($view_mode == 'map'): ?>
		<div class="two columns offset-by-six link">
			<a href="<?php echo site_url('events/' . $year) ?>"><img src="img/global/icons/list.png"><span>View list</span></a>
		</div>
		<?php endif; ?>
		
		<div class="two columns omega link">
			<a href="<?php echo site_url('events/' . $year . '.pdf') ?>"><img src="img/global/icons/pdf.png"><span>Printable list</span></a>
		</div>
			
	</div>
	
</div>