<form method="GET" action="<?php echo site_url('admin/operators/') ?>">

	<div class="boxfill filter">
	
		<table class="filter">
			<tr>
				<td><label for="o_callsign">Callsign</label></td>
				<td><label for="o_name">Name</label></td>
				<td><label for="name">Account email</label></td>
				<td><label for="name">Operator type</label></td>
				<td></td>
			</tr>
			<tr>
				<td>
					<?php echo form_input(array(
						'name' => 'o_callsign',
						'value' => element('o_callsign', $filter),
						'style' => 'text-transform: uppercase',
					)) ?>
				</td>
				<td>
					<?php echo form_input(array(
						'name' => 'o_name',
						'value' => element('o_name', $filter),
					)); ?>
				</td>
				<td>
					<?php echo form_input(array(
						'name' => 'a_email',
						'value' => element('a_email', $filter),
					)) ?>
				</td>
				<td>
					<?php
					$types = array(
						'' => '(Any)',
						'person' => 'Person',
						'club' => 'Club',
					);
					echo form_dropdown('o_type', $types, element('o_type', $filter));
					?>
				</td>
				
				<td>
					<input type="submit" class="black button" value="Filter">
				</td>
				
			</tr>
			
		</table>
	
	</div>

</form>

<?php if ($operators): ?>
	
	<table class="simple" id="operators" width="100%">
		
		<thead>
			<tr>
				<th style="width: 20px">Type</th>
				<th style="width: 100px">Callsign</th>
				<th>Name</th>
				<th>Account email</th>
				<th class="op">&nbsp;</th>
				<th class="op">&nbsp;</th>
				<th class="op">&nbsp;</th>
			</tr>
		</thead>
		
		<tbody>
		
		<?php foreach ($operators as $o): ?>
			
			<tr>
				<td><?php echo $o->o_type('icon') ?></td>
				<td><?php echo $o->o_callsign() ?></td>
				<td class="title"><?php echo anchor('admin/operators/set/' . $o->o_id(), $o->o_name()) ?></td>
				<td><?php echo $o->a_email() ?>
				<td class="icon"><?php echo $o->account_icon() ?></td>
				<td class="icon"><?php echo $o->edit_icon() ?></td>
				<td class="icon"><?php echo $o->delete_icon() ?></td>
			</tr>
		
		<?php endforeach; ?>
			
		</tbody>
		
	</table>
	
	<div class="add-bottom">
		<?php echo $this->pagination->create_links(); ?>
		<div class="clear"></div>
	</div>
	
<?php else: ?>

	<div class="alert warning">No operators found.</div>

<?php endif; ?>