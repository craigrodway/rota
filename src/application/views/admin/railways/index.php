<p class="add-bottom">
	<a href="<?php echo site_url('admin/railways/add') ?>" class="btn">Add new railway</a>
</p>


<form class="form-stacked" method="GET" action="<?php echo site_url('admin/railways/') ?>">

	<div class="row bg filter">
			
		<div class="span3">
			<label for="name">Name</label>
			<input class="span3" type="text" name="name" value="<?php echo @set_value('name', $filter_params['name']) ?>">
		</div>

		<div class="span3">
			<label for="name">WAB</label>
			<input class="span3" type="text" name="wab" value="<?php echo @set_value('wab', $filter_params['wab']) ?>">
		</div>

		<div class="span3">
			<label for="name">Locator</label>
			<input class="span3" type="text" name="locator" value="<?php echo @set_value('locator', $filter_params['locator']) ?>">
		</div>

		<div class="span3">
			<label for="name">Post Code</label>
			<input class="span3" type="text" name="postcode" value="<?php echo @set_value('postcode', $filter_params['postcode']) ?>">
		</div>

		<div class="span16">
			<input type="submit" class="btn primary" value="Filter">
		</div>
	
	</div> <!-- / .row -->

</form>


<br><br>

<div class="row">

	<div class="span16">
	
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
						echo icon_link('world', $r->url, 'Visit website', 'target="_blank"');
						echo icon_link('map', '#', 'Show on map');
						echo icon_link('edit', 'admin/railways/edit/' . $r->railway_id, 'Edit');
						echo icon_link('delete', 'admin/railways/delete/' . $r->railway_id, 'Delete', 'rel="delete" data-id="' . $r->railway_id . '"');
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
	
	</div> <!-- / .span16 -->
	
	<!--
	<div class="span4 bg add-bottom">
		
		<form class="form-stacked" method="GET" action="<?php echo site_url('admin/railways/') ?>">
			
			<div class="clearfix">
				<label for="name">Name</label>
				<div class="input">
					<input class="span3" type="text" name="name" value="<?php echo @set_value('name', $filter_params['name']) ?>">
				</div>
			</div>
			
			<div class="clearfix">
				<label for="name">WAB</label>
				<div class="input">
					<input class="span3" type="text" name="wab" value="<?php echo @set_value('wab', $filter_params['wab']) ?>">
				</div>
			</div>
			
			<div class="clearfix">
				<label for="name">Locator</label>
				<div class="input">
					<input class="span3" type="text" name="locator" value="<?php echo @set_value('locator', $filter_params['locator']) ?>">
				</div>
			</div>
			<input type="submit" class="btn primary" value="Filter">
		</form>
		
	</div> <!-- .span4 -->
	

</div>


<div style="border: 1px solid #ccc; padding: 20px;">MAP</div>


<div id="dialog-delete" class="modal hide fade hidden">
	<div class="modal-header">
	<a href="#" class="close">X</a>
		<h3>Delete Railway</h3>
	</div>
	<div class="modal-body">
		<p>Are you sure you want to delete this railway?</p>
	</div>
	<div class="modal-footer">
		<?php echo form_open(site_url('admin/railways/delete'), '', array(
			'railway_id' => '0'
		)) ?>
		<input type="submit" class="btn danger" value="Delete">
		<input type="reset" class="btn secondary x" value="Cancel">
	</div>
</div>


<script>
jsq.add(function(){
	$("a[rel=delete]").click(function(e){
		// Show dialog on delete click and set hidden form ID field
		$("#dialog-delete").modal('show');
		$("#dialog-delete input[name=railway_id]").val($(this).data("id"));
		e.preventDefault();
	});
	$(".btn.x").click(function(){
		// close me.
		$(this).parents("div.modal").data("modal").hide();
	})
})
</script>