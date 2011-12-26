<?php if ($railway): ?>

<p>Railway info</p>

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