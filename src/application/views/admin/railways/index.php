<p class="add-bottom hidden">
	<a href="<?php echo site_url('admin/railways/set') ?>" class="green button add icon" id="add_button"><span>Add new railway</span></a>
</p>


<?php if ($railways): ?>
	
	<table class="simple hidden" id="railways" width="100%">
		
		<thead>
			<tr>
				<th>Name</th>
				<th>WAB</th>
				<th>Locator</th>
				<th>Postcode</th>
				<th>&nbsp;</th>
			</tr>
		</thead>
		
		<tbody>
			
		<?php foreach ($railways as $r): ?>
			
			<tr>
				<td class="title"><?php echo anchor('admin/railways/set/' . $r->r_id, $r->r_name) ?></td>
				<td><?php echo $r->r_wab ?></td>
				<td><?php echo $r->r_locator ?></td>
				<td><?php echo $r->r_postcode ?></td>
				<td class="ops">
					<?php
					/*echo icon_link('world', $r->r_url, 'Visit website', 'target="_blank"');
					echo icon_link('map', current_url() . '#/', 'Show on map');
					echo icon_link('edit', 'admin/railways/edit/' . $r->r_id, 'Edit');
					echo icon_link('delete', 'admin/railways/delete/' . $r->r_id, 'Delete', 'rel="delete" data-id="' . $r->r_id . '"');*/
					?>
					<a href="<?php echo $r->r_url ?>" class="blue button website icon"><span>Visit website</span></a>
					<a href="<?php echo site_url('admin/railways/delete/' . $r->r_id) ?>" class="red button delete icon" rel="delete" data-id="<?php echo $r->r_id ?>"><span>Delete</span></a>
				</td>
			</tr>
		
		<?php endforeach; ?>
			
		</tbody>
		
	</table>
	
<?php else: ?>

	<div class="alert-box warning">No railways found.</div>

<?php endif; ?>


<div class="clear"></div>

<div style="border: 1px solid #ccc; padding: 0px; height: 400px; margin-top: 20px;" id="map"></div>


<div id="modal-delete" class="reveal-modal">
	<h1>Delete Railway</h1>
	
	<p>Are you sure you want to delete this railway?</p>
	
	<a class="close-reveal-modal">&#215;</a>
	
	<?php echo form_open(site_url('admin/railways/delete'), '', array(
		'railway_id' => '0',
		'redirect_to' => current_url(),
	)) ?>
	<button type="submit" class="red button delete icon"><span>Delete</span></button>
	<input type="reset" class="button" value="Cancel">

</div>


<script>
var railways = <?php echo json_encode($railways, JSON_NUMERIC_CHECK) ?>;

jsq.add(function() {
	
	$("table").delegate("tr", "click", function(e){
		if (e.target.tagName == "TD") {
			var a = $(this).find("td.title a")[0];
			window.location.href = $(a).attr("href");  
		}
	});
	
	$("table#railways").show().dataTable({
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
	
	$("#buttons").replaceWith($("a#add_button"));
	
	$("a[rel=delete]").click(function(e){
		// Show dialog on delete click and set hidden form ID field
		$("#modal-delete").reveal({
			animation: "fade",
			animationSpeed: 300
		});
		$("#modal-delete input[name=railway_id]").val($(this).data("id"));
		e.preventDefault();
	});
	
	$("#modal-delete input[type=reset]").click(function(){
		$(".close-reveal-modal").click();
	});
	
	$('#map').gmap3({
		action: "init",
		options: {
			center: [53, -0.5],
			zoom: 6,
			mapTypeId: google.maps.MapTypeId.ROADMAP
		}
	});
	
	var markers = [];
	$.each(railways, function(idx, r){
		if (r.r_lat != null)
		{
			markers.push({
				lat: r.r_lat,
				lng: r.r_lng,
				data: r.r_name
			});
		}
	});
	$("#map").gmap3({
		action: "addMarkers",
		radius: 50,
		markers: markers,
		marker: {
			options: {
				draggable: false,
				icon: new google.maps.MarkerImage('img/markers/steamtrain.png')
			}
		},
		clusters:{
			0: {
				content: '<div class="cluster cluster-1">CLUSTER_COUNT</div>',
				width: 40,
				height: 40
			},
			5: {
				content: '<div class="cluster cluster-2">CLUSTER_COUNT</div>',
				width: 50,
				height: 50
			},
			10: {
				content: '<div class="cluster cluster-3">CLUSTER_COUNT</div>',
				width: 60,
				height: 60
			},
			events: {
				click: function(){ alert('x'); }
			}
		},
	});
	
})
</script>