<p class="add-bottom hidden buttons">
	<a href="<?php echo site_url('admin/events/set') ?>" class="green button add icon" id="add_button"><span>Add new event</span></a>
</p>


<?php if ($events): ?>
	
	<table class="simple hidden" id="events" width="100%">
		
		<thead>
			<tr>
				<th>Current</th>
				<th>Year</th>
				<th>start</th>
				<th>End</th>
				<th>&nbsp;</th>
			</tr>
		</thead>
		
		<tbody>
			
		<?php foreach ($events as $e): ?>
			
			<tr>
				<td><?php
					switch ($e->e_should_be_current)
					{
						case 1: $enabled_img = 'check_green.png'; break;
						case 0: $enabled_img = 'cross_red.png'; break;
					}
					echo '<img src="img/global/icons/' . $enabled_img . '">';
				?></td>
				<td class="title"><?php echo anchor('admin/events/set/' . $e->e_id, $e->e_year) ?></td>
				<td><?php echo date('d M Y', strtotime($e->e_start_date)) ?></td>
				<td><?php echo date('d M Y', strtotime($e->e_end_date)) ?></td>
				<td class="ops">
					<a href="<?php echo site_url('admin/stations/event/' . $e->e_id) ?>" class="blue button website icon"><span>Stations (<?php echo $e->e_stations_count ?>)</span></a>
					<a href="<?php echo site_url('admin/events/delete/' . $e->e_id) ?>" class="red button delete icon" rel="delete" data-id="<?php echo $a->a_id ?>"><span>Delete</span></a>
				</td>
			</tr>
		
		<?php endforeach; ?>
			
		</tbody>
		
	</table>
	
<?php else: ?>

	<div class="alert-box warning">No events found.</div>

<?php endif; ?>


<div class="clear"></div>


<div id="modal-delete" class="reveal-modal">
	<h1>Delete event</h1>
	
	<p>Are you sure you want to delete this event?</p>
	
	<p>It is <strong>NOT</strong> possible to delete the event once 
	stations have registered for the event. You must first de-register
	all stations if you want to do this.</p>
	
	<a class="close-reveal-modal">&#215;</a>
	
	<?php echo form_open(site_url('admin/events/delete'), '', array(
		'e_id' => '',
	)) ?>
	<button type="submit" class="red button delete icon"><span>Delete</span></button>
	<input type="reset" class="button" value="Cancel">

</div>


<script>
var accounts = <?php echo json_encode($events, JSON_NUMERIC_CHECK) ?>;

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
	if ($("table#events") == []) {
		$("#buttons").replaceWith($("a#add_button"));
	} else {
		$("p.buttons").fadeIn();
	}
	
	
	// Attach handler to delete buttons in the table to show modal confirmation
	$("a[rel=delete]").click(function(e){
		// Show dialog on delete click and set hidden form ID field
		$("#modal-delete").reveal({
			animation: "fade",
			animationSpeed: 300
		});
		$("#modal-delete input[name=e_id]").val($(this).data("id"));
		e.preventDefault();
	});
	
	// Close the modal
	$("#modal-delete input[type=reset]").click(function(){
		$(".close-reveal-modal").click();
	});
	
})
</script>