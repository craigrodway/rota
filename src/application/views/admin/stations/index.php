<form method="GET" action="<?php echo site_url('admin/stations/') ?>">

	<div class="boxfill filter">
	
		<table class="filter">
			<tr>
				<td><label for="name">Year</label></td>
				<td><label for="name">Callsign</label></td>
				<td><label for="name">Name</label></td>
				<td><label for="name">Railway</label></td>
				<td></td>
			</tr>
			<tr>
				<td>
					<?php
					$events = array_reverse($events, TRUE);
					$events[''] = '(Any)';
					$events = array_reverse($events, TRUE);
					?>
					<?php echo form_dropdown(
						's_e_year',
						$events,
						element('s_e_year', $filter)
					); ?>
				</td>
				<td>
					
					<?php echo form_input(array(
						'name' => 'o_callsign',
						'value' => element('o_callsign', $filter),
					)); ?>
				</td>
				<td>
					
					<?php echo form_input(array(
						'name' => 'o_name',
						'value' => element('o_name', $filter),
					)) ?>
				</td>
				<td>
					
					<?php echo form_input(array(
						'name' => 'r_name',
						'value' => element('r_name', $filter),
					)) ?>
				</td>
				
				<td>
					<input type="submit" class="black button" value="Filter">
				</td>
				
			</tr>
		</table>
	
	</div>

</form>


		
<?php if ($stations): ?>

<table class="simple" id="stations" width="100%">

	<thead>
		<tr>
			<th>Year</th>
			<th>Type</th>
			<th>Callsign</th>
			<th>Name</th>
			<th>Railway</th>
			<th class="op">&nbsp;</th>
			<th class="op">&nbsp;</th>
		</tr>
	</thead>
	
	<tbody>
		
	<?php foreach ($stations as $s): ?>
		
		<tr>
			<td><?php echo $s->s_e_year() ?></td>
			<td class="icon"><?php echo $s->operator->o_type('icon') ?></td>
			<td><?php echo anchor('admin/operators/set/' . $s->s_o_id(), $s->operator->o_callsign()) ?></td>
			<td><?php echo anchor('admin/operators/set/' . $s->s_o_id(), $s->operator->o_name()) ?></td>
			<td><?php echo anchor('admin/railways/set/' . $s->s_r_id(), $s->railway->r_name()) ?></td>
			<td class="icon title"><?php echo $s->edit_icon() ?></td>
			<td class="icon"><?php echo $s->delete_icon() ?></td>
		</tr>
	
	<?php endforeach; ?>
		
	</tbody>
	
</table>

<?php else: ?>

<div class="alert warning">No stations found.</div>

<?php endif; ?>