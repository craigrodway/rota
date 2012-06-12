<form method="GET" action="<?php echo site_url('admin/railways/') ?>">

	<div class="boxfill filter">
	
		<table class="filter">
			<tr>
				<td><label for="name">Name</label></td>
				<td><label for="name">WAB</label></td>
				<td><label for="name">Locator</label></td>
				<td><label for="name">Post Code</label></td>
				<td></td>
			</tr>
				<td>
					<?php echo form_input(array(
						'name' => 'r_name',
						'value' => element('r_name', $filter),
					)) ?>
				</td>
				<td>
					<?php echo form_input(array(
						'name' => 'r_wab',
						'value' => element('r_wab', $filter),
					)); ?>
				</td>
				<td>
					<?php echo form_input(array(
						'name' => 'r_locator',
						'value' => element('r_locator', $filter),
					)) ?>
				</td>
				<td>
					<?php echo form_input(array(
						'name' => 'r_postcode',
						'value' => element('r_postcode', $filter),
					)) ?>
				</td>
				
				<td>
					<input type="submit" class="black button" value="Filter">
				</td>
				
			</tr>
		</table>
	
	</div>

</form>



<?php if ($railways): ?>
	
	<table class="simple" id="railways" width="100%">
		
		<thead>
			<tr>
				<th>Name</th>
				<th>WAB</th>
				<th>Locator</th>
				<th>Postcode</th>
				<th class="op">&nbsp;</th>
				<th class="op">&nbsp;</th>
				<th class="op">&nbsp;</th>
			</tr>
		</thead>
		
		<tbody>
			
		<?php foreach ($railways as $r): ?>
			
			<tr>
				<td class="title"><?php echo anchor('admin/railways/set/' . $r->r_id(), $r->r_name()) ?></td>
				<td><?php echo $r->r_wab() ?></td>
				<td><?php echo $r->r_locator() ?></td>
				<td><?php echo $r->r_postcode() ?></td>
				<td class="icon"><?php echo $r->website_icon() ?></td>
				<td class="icon"><?php echo $r->edit_icon() ?></td>
				<td class="icon"><?php echo $r->delete_icon() ?></td>
			</tr>
		
		<?php endforeach; ?>
			
		</tbody>
		
	</table>
	
	<div class="add-bottom">
		<?php echo $this->pagination->create_links(); ?>
		<div class="clear"></div>
	</div>
	
<?php else: ?>

	<div class="alert warning">No railways found.</div>

<?php endif; ?>


<div class="clear"></div>
