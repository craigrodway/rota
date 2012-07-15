<ul class="railways">
<?php foreach ($railways as $r): ?>
	<?php $i = rand(0,10); ?>
	<li>
		<a href="<?php echo site_url('railways/' . $r->r_slug()) ?>">
			<img src="http://lorempixel.com/220/180/transport/<?php echo $i ?>"/>
			<h4 class="title"><?php echo $r->r_name() ?></h4>
		</a>
	</li>
<?php endforeach ;?>
</ul>
