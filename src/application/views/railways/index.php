<p>The <strong>Railways on the Air</strong> event is not just about amateur 
radio and making contacts - it&apos; also about celebrating the unique 
position railways hold in our national heritage.</p>

<p>Find out more about the railways that have hosted amateur stations over the years ...</p>

<ul class="tabs">
	<li <?php echo ($tab == 'grid') ? 'class="active"' : '' ?>><?php echo anchor('railways/grid', 'List') ?></li>
	<li <?php echo ($tab == 'map') ? 'class="active"' : '' ?>><?php echo anchor('railways/map', 'Map') ?></li>
</ul>



<?php if ($tab == 'grid'): ?>

	<ul class="railways">
	<?php foreach ($railways as $r): ?>
		<?php $i = rand(0,10); ?>
		<li>
			<a href="<?php echo site_url('railways/' . $r->railway_id) ?>">
				<img src="http://lorempixel.com/220/180/transport/<?php echo $i ?>"/>
				<h4 class="title"><?php echo $r->name ?></h4>
			</a>
		</li>
	<?php endforeach ;?>
	</ul>

<?php endif; ?>




<?php if ($tab == 'map'): ?>

	<?php echo $map['html'] ?>

<?php endif; ?>