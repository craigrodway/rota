<?php if ($accounts): ?>
	
	<table class="simple" id="accounts" width="100%">
		
		<thead>
			<tr>
				<th>Type</th>
				<th>Enabled</th>
				<th>Verified</th>
				<th>Email address</th>
				<th>Created</th>
				<th>Last login</th>
				
				<th class="op">&nbsp;</th>
				<th class="op">&nbsp;</th>
				<th class="op">&nbsp;</th>
			</tr>
		</thead>
		
		<tbody>
			
		<?php foreach ($accounts as $a): ?>
			
			<tr>
				<td style="text-align: right"><?php echo $a->a_type('html') ?></td>
				<td><?php echo $a->a_enabled('icon') ?></td>
				<td><?php echo $a->verified('icon') ?></td>
				<td class="title"><?php echo anchor('admin/accounts/set/' . $a->a_id(), $a->a_email()) ?></td>
				<td><?php echo $a->a_created('d M Y, H:i') ?></td>
				<td><?php echo $a->a_lastlogin('d M Y, H:i', 'Never') ?></td>
				
				<td class="icon"><?php echo $a->profiles_icon() ?></td>
				<td class="icon"><?php echo $a->edit_icon() ?></td>
				<td class="icon"><?php echo $a->delete_icon() ?></td>
			</tr>
		
		<?php endforeach; ?>
			
		</tbody>
		
	</table>
	
	<div class="add-bottom">
		<?php echo $this->pagination->create_links(); ?>
		<div class="clear"></div>
	</div>
	
<?php else: ?>

	<div class="alert warning">No accounts found.</div>

<?php endif; ?>