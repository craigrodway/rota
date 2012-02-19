<p class="add-bottom hidden">
	<a href="<?php echo site_url('admin/accounts/set') ?>" class="green button add icon" id="add_button"><span>Add new account</span></a>
</p>


<?php if ($accounts): ?>
	
	<table class="simple hidden" id="accounts" width="100%">
		
		<thead>
			<tr>
				<th>Enabled</th>
				<th>Email address</th>
				<th>Created</th>
				<th>Last login</th>
				<th>Type</th>
				<th>Verified</th>
				<th>&nbsp;</th>
			</tr>
		</thead>
		
		<tbody>
			
		<?php foreach ($accounts as $a): ?>
			
			<tr>
				<td><?php
					switch ($a->a_enabled)
					{
						case 'Y': $enabled_img = 'check_green.png'; break;
						case 'N': $enabled_img = 'cross_red.png'; break;
					}
					echo '<img src="img/global/icons/' . $enabled_img . '">';
				?></td>
				<td class="title"><?php echo anchor('admin/accounts/set/' . $a->a_id, $a->a_email) ?></td>
				<td><?php echo date('d M Y, H:i', strtotime($a->a_created)) ?></td>
				<td><?php
				if ($a->a_lastlogin == NULL)
				{
					echo 'Never';
				}
				else
				{
					echo date('d M Y, H:i', strtotime($a->a_lastlogin));
				}
				?></td>
				<td><?php
					switch ($a->a_type)
					{
						case 'user': $type_img = 'user_blue.png'; break;
						case 'admin': $type_img = 'user_black.png'; break;
					}
					echo '<img src="img/global/icons/' . $type_img . '">';
				?></td>
				<td><?php
					switch ($a->a_verify == NULL)
					{
						case TRUE: $verified_img = 'check_green.png'; break;
						case FALSE: $verified_img = 'cross_red.png'; break;
					}
					echo '<img src="img/global/icons/' . $verified_img . '">';
				?></td>
				<td class="ops">
					<a href="<?php echo site_url('admin/operators/' . $a->a_id) ?>" class="blue button website icon"><span>Profiles (<?php echo $a->a_operators_count ?>)</span></a>
					<a href="<?php echo site_url('admin/accounts/delete/' . $a->a_id) ?>" class="red button delete icon" rel="delete" data-id="<?php echo $a->a_id ?>"><span>Delete</span></a>
				</td>
			</tr>
		
		<?php endforeach; ?>
			
		</tbody>
		
	</table>
	
<?php else: ?>

	<div class="alert-box warning">No accounts found.</div>

<?php endif; ?>


<div class="clear"></div>


<div id="modal-delete" class="reveal-modal">
	<h1>Delete Account</h1>
	
	<p>Are you sure you want to delete this account?</p>
	
	<p>Deleting it will remove the account's operator profiles, as well as removing
	records relating to stations and events they have been part of.</p>
	
	<a class="close-reveal-modal">&#215;</a>
	
	<?php echo form_open(site_url('admin/accounts/delete'), '', array(
		'a_id' => '0',
	)) ?>
	<button type="submit" class="red button delete icon"><span>Delete</span></button>
	<input type="reset" class="button" value="Cancel">

</div>


<script>
var accounts = <?php echo json_encode($accounts, JSON_NUMERIC_CHECK) ?>;

jsq.add(function() {
	
	$("table").delegate("tr", "click", function(e){
		if (e.target.tagName == "TD") {
			var a = $(this).find("td.title a")[0];
			window.location.href = $(a).attr("href");  
		}
	});
	
	// Initialise the DataTable.
	$("table#accounts").show().dataTable({
		// Specify markup to allow button to be added at the top
		sDom: '<"eight columns alpha add-bottom"<"#buttons">><"eight columns omega"f><"wrapper"t<p>>',
		bLengthChange: false,
		bFilter: true,
		bInfo: false,
		bPaginate: true,
		oLanguage: {
			oPaginate: {
				sNext: '<img src="img/global/icons/arrow_right2.png">',
				sPrevious: '<img src="img/global/icons/arrow_left2.png">',
			},
			sSearch: "Filter: "
		}
	});
	
	// Add the add button to the top of the table
	$("#buttons").replaceWith($("a#add_button"));
	
	// Attach handler to delete buttons in the table to show modal confirmation
	$("a[rel=delete]").click(function(e){
		// Show dialog on delete click and set hidden form ID field
		$("#modal-delete").reveal({
			animation: "fade",
			animationSpeed: 300
		});
		$("#modal-delete input[name=a_id]").val($(this).data("id"));
		e.preventDefault();
	});
	
	// Close the modal
	$("#modal-delete input[type=reset]").click(function(){
		$(".close-reveal-modal").click();
	});
	
})
</script>