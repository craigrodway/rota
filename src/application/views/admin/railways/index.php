<p class="add-bottom">
	<a href="<?php echo site_url('admin/railways/set') ?>" class="green button add icon" id="add_button"><span>Add new railway</span></a>
</p>



<form method="GET" action="<?php echo site_url('admin/railways/') ?>">

	<div class="boxfill">
	
		<div class="boxfill-heading">
			Filter
		</div>
	
		<table class="filter">
			<tr>
				<td>
					<label for="name">Name</label>
					<?php echo form_input(array(
						'name' => 'r_name',
						'value' => element('r_name', $filter),
					)) ?>
				</td>
				<td>
					<label for="name">WAB</label>
					<?php echo form_input(array(
						'name' => 'r_wab',
						'value' => element('r_wab', $filter),
					)); ?>
				</td>
				<td>
					<label for="name">Locator</label>
					<?php echo form_input(array(
						'name' => 'r_locator',
						'value' => element('r_locator', $filter),
					)) ?>
				</td>
				<td>
					<label for="name">Post Code</label>
					<?php echo form_input(array(
						'name' => 'r_postcode',
						'value' => element('r_postcode', $filter),
					)) ?>
				</td>
				
				<td>
					<label>&nbsp;</label>
					<input type="submit" class="blue button" value="Filter">
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
				<td class="title"><?php echo anchor('admin/railways/set/' . $r->get('r_id'), $r->get('r_name')) ?></td>
				<td><?php echo $r->get('r_wab') ?></td>
				<td><?php echo $r->get('r_locator') ?></td>
				<td><?php echo $r->get('r_postcode') ?></td>
				<td class="icon">
					<?php echo icon_link('silk/world', $r->get('r_url'), 'Visit website', 'target="_blank"') ?>
				</td>
				<td class="icon">
					<?php echo icon_link('silk/edit', 'admin/railways/set/' . $r->get('r_id'), 'Edit') ?>
				</td>
				<td class="icon">
					<?php echo icon_link('silk/delete', 'admin/railways/delete/' . $r->get('r_id'), 'Delete', 'rel="delete" data-id="' . $r->get('r_id') . '"') ?>
				</td>
			</tr>
		
		<?php endforeach; ?>
			
		</tbody>
		
	</table>
	
	<div class="add-bottom">
		<?php echo $this->pagination->create_links(); ?>
		<div class="clear"></div>
	</div>
	
<?php else: ?>

	<div class="alert-box warning">No railways found.</div>

<?php endif; ?>


<div class="clear"></div>


<div style="border: 1px solid #ccc; padding: 0px;">
	<?php echo $map['html']; ?>
</div>
