<p class="add-bottom">
	<a href="<?php echo site_url('admin/railways/add') ?>" class="btn">Add new railway</a>
</p>

<div class="row">

	<div class="span11">
	
	<?php if ($railways): ?>
	
		<table class="condensed-table bordered-table zebra-striped">
			
			<thead>
				<tr>
					<th>Name</th>
					<th>WAB</th>
					<th>Locator</th>
					<th></th>
				</tr>
			</thead>
			
			<tbody>
				
			<?php foreach ($railways as $r): ?>
				
				<tr>
					<td><?php echo anchor('admin/railways/edit/' . $r->railway_id, $r->name) ?></td>
					<td><?php echo $r->wab ?></td>
					<td><?php echo $r->locator ?></td>
					<td class="ops">
						<?php
						echo icon_link('world', $r->url, 'Visit website');
						echo icon_link('map', '#', 'Show on map');
						echo icon_link('edit', 'admin/railways/edit/' . $r->railway_id, 'Edit');
						echo icon_link('delete', 'admin/railways/delete/' . $r->railway_id, 'Delete', 'data-controls-modal="delete"');
						?>
					</td>
				</tr>
			
			<?php endforeach; ?>
				
			</tbody>
			
		</table>
		
		<div class="pagination add-bottom">
			<?php echo $this->pagination->create_links(); ?>
		</div>
		
	<?php else: ?>

		<div class="alert-message block-message warning">No railways found.</div>

	<?php endif; ?>
	
	</div> <!-- / .span11 -->
	
	
	<div class="span5 bg add-bottom">
		
		<form class="form-stacked" method="GET" action="<?php echo site_url('admin/railways/') ?>">
			<div class="clearfix">
				<label for="name">Name</label>
				<div class="input">
					<input type="text" name="name" value="<?php echo @set_value('name', $filter_params['name']) ?>">
				</div>
			</div>
			
			<div class="clearfix">
				<label for="name">WAB</label>
				<div class="input">
					<input type="text" name="wab" value="<?php echo @set_value('wab', $filter_params['wab']) ?>">
				</div>
			</div>
			
			<div class="clearfix">
				<label for="name">Locator</label>
				<div class="input">
					<input type="text" name="locator" value="<?php echo @set_value('locator', $filter_params['locator']) ?>">
				</div>
			</div>
			<input type="submit" class="btn primary" value="Filter">
		</form>
		
	</div> <!-- .span5 -->
	

</div>


<div style="border: 1px solid #ccc; padding: 20px;">MAP</div>


<div id="delete" class="modal hide fade hidden">
	<div class="modal-header">
	<a href="#" class="close">X</a>
		<h3>Delete Railway</h3>
	</div>
	<div class="modal-body">
		<p>Are you sure you want to delete this railway?</p>
	</div>
	<div class="modal-footer">
		<a href="#" class="btn primary">Delete</a>
		<a href="#" class="btn secondary">Cancel</a>
	</div>
</div>