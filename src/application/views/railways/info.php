<?php if ($railway): ?>

	<div class="one-third column alpha">
		
		<h3>Description</h3>
		<div class="add-bottom">
		<?php if ( ! empty($railway->r_info_html)): ?>
			<?php echo $railway->r_info_html ?>
		<?php else: ?>
			<?php if ($this->auth->logged_in()): ?>
				<p>This railway doesn't have any information about it! Why not 
				be the first to add something?</p>
				<a href="<?php echo site_url('railways/edit/' . $railway->r_slug) ?>" class="small btn">Add description</a>
			<?php else: ?>
				<p>This railway doesn't have any information about it! If you 
				had an account, you could be the first to add something.</p>
				<a href="<?php echo site_url('account/create') ?>" class="small btn">Create an account</a>
			<?php endif; ?>
		<?php endif; ?>
		</div>
		
		<p class="add-bottom"><?php echo anchor($railway->r_url, 'Visit website') ?></p>
		<p class="add-bottom"><strong>WAB area:</strong> <br><?php echo $railway->r_wab ?></p>
		<p class="add-bottom"><strong>Locator square:</strong> <br><?php echo $railway->r_locator ?></p>
		
	</div> <!-- / .span-one-third -->
	
	
	<div class="two-thirds column omega">
		
		<dl class="tabs contained">
			<dd><a href="#railway-photos" class="active">Photos</a></dd>
			<dd><a href="#railway-map">Map</a></dd>
			<dd><a href="#railway-stations">Amateur stations</a></dd>
		</dl>
		
		
		<ul class="tabs-content contained">

			<li class="active" id="railway-photosTab">
				<img src="http://lorempixel.com/500/440/transport/1" style="padding: 5px; border: 1px solid #ccc;">
			</li>
			
			<li id="railway-mapTab">
				<div style="width: 100%; height: 452px; border: 1px solid #ccc; padding: 5px;">
					<?php echo $map['html'] ?>
				</div>
			</li>
			
			<li id="railway-stationsTab">
				<p>List of all participating stations over the years...</p>
				<table class="simple" style="width: 100%">
					<thead>
						<tr>
							<th>Callsign</th>
							<th>Name</th>
							<th>Event year</th>
						</tr>
					</thead>
					<tbody>
						<tr>
							<td>GX4TTF</td>
							<td>Bishop Auckland Amateur Radio Club</td>
							<td>2011</td>
						</tr>
						<tr>
							<td>GX4TTF</td>
							<td>Bishop Auckland Amateur Radio Club</td>
							<td>2010</td>
						</tr>
					</tbody>
				</table>
			</li>
			
		</ul>
		
	</div>
	

<script>
jsq.add(function(){
	// Tell the Google Map to refresh itself when tab is changed
	$(".tabs").bind("change", function(e){
		if ($(e.target).attr("href") == "#railway-map"){
			var center = map.getCenter();
			google.maps.event.trigger(map, 'resize');
			map.setCenter(center);
		}
	})
})
</script>

<?php else: ?>

<div class="alert-message block-message error">
	<p>Sorry! We couldn&apos;t find the railway requested. Please check the 
	address and try again.</p><br>
	<?php if ( ! empty($search)): ?>
	<p>Do any of these sound like the one you&apos;re after?</p><br>
	<ul>
	<?php foreach ($search as $r): ?>
		<li><?php echo anchor('railways/' . $r->slug, $r->name) ?></li>
	<?php endforeach; ?>
	</ul>
	<br>
	<?php endif; ?>
	<div class="alert-actions">
		<a href="<?php echo site_url('railways') ?>" class="btn small">View all railways</a>
	</div>
</div>

<?php endif; ?>