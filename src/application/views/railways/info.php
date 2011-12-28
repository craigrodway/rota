<?php if ($railway): ?>

<div class="row">

	<div class="span-one-third">
		<h3>Description</h3>
		<div class="add-bottom">
		<?php if ( ! empty($railway->info_html)): ?>
			<?php echo $railway->info_html ?>
		<?php else: ?>
			<?php if ($this->auth->logged_in()): ?>
				<p>This railway doesn't have any information about it! Why not 
				be the first to add something?</p>
				<a href="<?php echo site_url('railways/edit/' . $railway->slug) ?>" class="small btn">Add description</a>
			<?php else: ?>
				<p>This railway doesn't have any information about it! If you 
				had an account, you could be the first to add something.</p>
				<a href="<?php echo site_url('account/create') ?>" class="small btn">Create an account</a>
			<?php endif; ?>
		<?php endif; ?>
		</div>
		
		<p class="add-bottom"><?php echo anchor($railway->url, 'Visit website') ?></p>
		<p class="add-bottom"><strong>WAB area:</strong> <br><?php echo $railway->wab ?></p>
		<p class="add-bottom"><strong>Locator square:</strong> <br><?php echo $railway->locator ?></p>
		
	</div> <!-- / .span-one-third -->
	
	
	<div class="span-two-thids">
		
		<ul class="tabs" data-tabs="tabs">
			<li class="active"><a href="#railway-photos">Photos</a></li>
			<li><a href="#railway-map">Map</a></li>
			<li><a href="#railway-stations">Amateur stations</a></li>
		</ul>
		
		<div class="tab-content">
			<div class="active" id="railway-photos">
				<img src="http://lorempixel.com/608/440/transport/1" style="padding: 5px; border: 1px solid #ccc;">
			</div>
			<div id="railway-map">
				<div style="width: 608px; height: 452px; border: 1px solid #ccc; padding: 5px;">
					<?php echo $map['html'] ?>
				</div>
			</div>
			<div id="railway-stations">
					<p>List of all participating stations over the years...</p>
					<table class="simple" style="width: 620px">
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
				</div>
				
			</div>
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
		
	</div>

</div> <!-- / .row -->

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