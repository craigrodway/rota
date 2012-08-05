<ul class="pagination">
	<li class="current"><?php echo anchor('railways/list', 'All') ?></li>
	<?php
	$a = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
	for ($i = 0; $i < strlen($a); $i++)
	{
		echo '<li>' . anchor('railways/list/' . $a{$i}, $a{$i}, 'data-letter="' . $a{$i} . '"') . '</li>';
	}
	?>
</ul>


<hr>


<ul class="railways">
	
	<?php foreach ($railways as $r): ?>
	
	<?php $i = rand(0,10); ?>
	
	<li class="letter-<?php echo strtoupper(substr($r->r_name(), 0, 1)) ?>">
		<a href="<?php echo site_url('railways/' . $r->r_slug()) ?>">
			<?php if ($r->i_id()): ?>
			<img src="<?php echo image_src($r->data, 'c150x150') ?>">
			<?php else: ?>
			<img src="http://lorempixel.com/150/150/transport/<?php echo $i ?>"/>
			<?php endif; ?>
			<h4 class="title"><?php echo $r->r_name() ?></h4>
		</a>
	</li>
	
	<?php endforeach ;?>
	
</ul>


<script>
jsq.add(function() {
	$("ul.pagination").on("click", "a", function(e) {
		e.preventDefault();
		$(this).parent("li").addClass("current").siblings().removeClass("current");
		var letter = $(this).data("letter");
		if ( ! letter) {
			$("ul.railways > li").show();
		} else {			
			$("ul.railways > li[class!='letter-" + letter + "']").hide();
			$("ul.railways > li.letter-" + letter).show();
		}
	});
});
</script>