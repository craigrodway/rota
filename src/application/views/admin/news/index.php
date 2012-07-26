<form method="GET" action="<?php echo site_url('admin/news/') ?>">

	<div class="boxfill filter">
	
		<table class="filter">
			<tr>
				<td><label for="name">Event year</label></td>
				<td><label for="name">Operator name</label></td>
				<td><label for="name">Railway name</label></td>
				<td></td>
			</tr>
			<tr>
				<td>
					<?php $events = array('' => '(Any)') + $events; ?>
					<?php echo form_dropdown(
						'n_e_year',
						$events,
						element('n_e_year', $filter)
					); ?>
				</td>
				<td>
					<?php echo form_input(array(
						'name' => 'o_name',
						'value' => element('o_name', $filter),
					)); ?>
				</td>
				<td>
					<?php echo form_input(array(
						'name' => 'r_name',
						'value' => element('r_name', $filter),
					)); ?>
				</td>
				
				<td>
					<input type="submit" class="black button" value="Filter">
				</td>
				
			</tr>
		</table>
	
	</div>

</form>


		
<?php if ($news): ?>

<table class="simple" id="news" width="100%">

	<thead>
		<tr>
			<th>Date/time</th>
			<th>Title</th>
			<th>Author</th>
			<th>Event</th>
			<th>Railway</th>
			<th class="op">&nbsp;</th>
			<th class="op">&nbsp;</th>
		</tr>
	</thead>
	
	<tbody>
		
	<?php foreach ($news as $n): ?>
		
		<tr>
			<td><?php echo $n->n_datetime_posted() ?></td>
			<td><?php echo anchor('admin/news/set/' . $n->n_id(), $n->n_title()) ?></td>
			<td><?php
			if ($n->n_o_id())
			{
				echo anchor('admin/operators/set/' . $n->operator->o_id(), $n->operator->o_name());
			}
			?></td>
			<td><?php
			if ($n->n_e_year())
			{
				echo anchor('admin/events/set/' . $n->n_e_year(), $n->n_e_year());
			}
			?></td>
			<td><?php
			if ($n->n_r_id())
			{
				echo anchor('admin/railways/set/' . $n->railway->r_id(), $n->railway->r_name());
			}
			?></td>
			<td class="icon title"><?php echo $n->edit_icon() ?></td>
			<td class="icon"><?php echo $n->delete_icon() ?></td>
		</tr>
	
	<?php endforeach; ?>
		
	</tbody>
	
</table>

<?php else: ?>

<div class="alert warning">No news articles found.</div>

<?php endif; ?>