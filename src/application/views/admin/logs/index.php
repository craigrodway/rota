<h2><?php echo $previous_event ?> - Logs received</h2>

<table class="simple" style="width: 100%">
	<thead>
		<tr>
			<th>Callsign</th>
			<th>Operator</th>
			<th>Railway</th>
			<th>Actions</th>
		</tr>
	</thead>
	<tbody>
		<?php foreach ($previous_stations_uploaded as $s): ?>
		<tr>
			<td><?php echo $s->s_callsign() ?></td>
			<td><?php echo $s->operator->o_name() ?></td>
			<td><?php echo $s->railway->r_name() ?></td>
			<td class="right">
				<?php echo anchor('admin/stations/log/' . $s->s_id(), 'View log', 'class="black button"'); ?>
				<?php echo anchor('', 'Verify', 'class="green button"'); ?>
			</td>
		</tr>
		<?php endforeach; ?>
	</tbody>
</table>



<h2><?php echo $previous_event ?> - Waiting for logs</h2>

<table class="simple" style="width: 100%">
	<thead>
		<tr>
			<th>Callsign</th>
			<th>Operator</th>
			<th>Railway</th>
			<th>Actions</th>
		</tr>
	</thead>
	<tbody>
		<?php foreach ($previous_stations_waiting as $s): ?>
		<tr>
			<td><?php echo $s->s_callsign() ?></td>
			<td><?php echo $s->operator->o_name() ?></td>
			<td><?php echo $s->railway->r_name() ?></td>
			<td class="right">
				<?php echo anchor('', 'Send reminder', 'class="black button"'); ?>
			</td>
		</tr>
		<?php endforeach; ?>
	</tbody>
</table>


