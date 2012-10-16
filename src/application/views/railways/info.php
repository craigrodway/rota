<?php if (isset($railway)): ?>

<div class="eight columns alpha">
	
	<h3>Description</h3>
	<div class="add-bottom">
	<?php if ($railway->r_info_html()): ?>
		<?php echo $railway->r_info_html() ?>
	<?php else: ?>
		<?php if ($this->auth->logged_in()): ?>
			<div class="alert"><p>This railway doesn&rsquo;t have any information about it! Why not 
			be the first to add something?</p>
			<a href="<?php echo site_url('railways/edit/' . $railway->r_slug()) ?>" class="small black button">Add description</a>
			</div>
		<?php else: ?>
			<div class="alert">This railway doesn&rsquo;t have any information about it! If you 
			had an account, you could be the first to add something.
			<a href="<?php echo site_url('account') ?>" class="small btn">Log in or Create an account</a></div>
		<?php endif; ?>
	<?php endif; ?>
	</div>
	
	<p class="add-bottom"><?php echo anchor($railway->r_url(), 'Visit website') ?></p>
	<p class="add-bottom"><strong>WAB area:</strong> <br><?php echo $railway->r_wab() ?></p>
	<p class="add-bottom"><strong>Locator square:</strong> <br><?php echo $railway->r_locator() ?></p>
	
</div> <!-- / .span-one-third -->


<div class="eight columns omega">
	
	<?php if ( ! empty($railway->images)): ?>
	
	<div class="gallery">
		<ul>
			<?php foreach ($railway->images as $image): ?>
			<li><img src="<?php echo $image->src('w460') ?>"></li>
			<?php endforeach; ?>
		</ul>
	</div>
	<script>jsq.add(function() { $(".gallery").gallery(); });</script>
	
	<?php endif; ?>
	
	
	<?php if ($stations): ?>
	
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
			<?php foreach ($stations as $s): ?>
			<tr>
				<td><?php echo $s->s_callsign() ?></td>
				<td><?php echo $s->operator_link() ?></td>
				<td><?php echo $s->s_e_year() ?></td>
			</tr>
			<?php endforeach; ?>
		</tbody>
	</table>
	
	<?php else: ?>
	
	<div class="alert">This railway has never had a station for Railways on the Air!</div>
	
	<?php endif; ?>
	
</div>


<?php else: ?>

<div class="alert-message block-message error">
	<p>Sorry! We couldn&rsquo;t find the railway requested. Please check the 
	address and try again.</p><br>
	<?php if ( ! empty($search)): ?>
	<p>Do any of these sound like the one you&apos;re after?</p><br>
	<ul>
	<?php foreach ($search as $r): ?>
		<li><?php echo anchor('railways/' . $r->r_slug(), $r->r_name()) ?></li>
	<?php endforeach; ?>
	</ul>
	<br>
	<?php endif; ?>
	<div class="alert-actions">
		<a href="<?php echo site_url('railways') ?>" class="btn small">View all railways</a>
	</div>
</div>

<?php endif; ?>