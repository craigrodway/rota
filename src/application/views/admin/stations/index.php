<div class="row">
	
	
	<div class="two columns alpha">
		
		<div class="boxfill">
			<h3 class="heading">
				Events
			</h3>
			
			<?php
			if ($events)
			{
				foreach ($events as $event)
				{
					$text = $event->e_year();
					if ($year != $event->e_year())
					{
						$text = anchor('admin/stations/index/' . $event->e_year(), $text);
					}
					echo "<h4>$text</h4>";
				}
			}
			?>
			
		</div>
		
	</div>
	
	
	
	<div class="fourteen columns omega">
		
		<?php if ($stations): ?>
		
		<table class="simple" id="stations" width="100%">
		
			<thead>
				<tr>
					<th>Type</th>
					<th>Callsign</th>
					<th>Name</th>
					<th>Railway</th>
					<th>Registered</th>
					<th class="op">&nbsp;</th>
					<th class="op">&nbsp;</th>
				</tr>
			</thead>
			
			<tbody>
				
			<?php foreach ($stations as $s): ?>
				
				<tr>
					<td class="icon"><?php echo $s->operator->o_type('icon') ?></td>
					<td><?php echo $s->operator->o_callsign() ?></td>
					<td><?php echo $s->operator->o_name() ?></td>
					<td><?php echo $s->railway->r_name() ?></td>
					<td><?php echo $s->s_date_registered() ?></td>
					<td class="icon"><?php echo $s->edit_icon() ?></td>
					<td class="icon"><?php echo $s->delete_icon() ?></td>
				</tr>
			
			<?php endforeach; ?>
				
			</tbody>
			
		</table>
		
		<?php else: ?>
		
		<div class="alert warning">No stations found.</div>
		
		<?php endif; ?>
		
	</div>
	

</div>
