<?php if ($events): ?>
	
	<table class="simple" id="events" width="100%">
		
		<thead>
			<tr>
				<th>Year</th>
				<th>Start</th>
				<th>End</th>
				<th class="op">&nbsp;</th>
				<th class="op">&nbsp;</th>
				<th class="op">&nbsp;</th>
			</tr>
		</thead>
		
		<tbody>
			
		<?php foreach ($events as $e): ?>
			
			<tr>
				<td class="title">
					<?php echo anchor('admin/events/set/' . $e->e_year(), $e->e_year()) ?>
					<?php if ($e->is_current()): ?>
					<span class="green label" style="margin-left: 10px">current</span>
					<?php endif; ?>
				</td>
				<td><?php echo $e->e_start_date() ?></td>
				<td><?php echo $e->e_end_date() ?></td>
				<td class="icon"><?php echo $e->view_stations_icon() ?></td>
				<td class="icon"><?php echo $e->edit_icon() ?></td>
				<td class="icon"><?php echo $e->delete_icon() ?></td>
			</tr>
		
		<?php endforeach; ?>
			
		</tbody>
		
	</table>
	
<?php else: ?>

	<div class="alert-box warning">No events found.</div>

<?php endif; ?>
