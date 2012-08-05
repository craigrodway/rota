<?php
if (count($news->images) > 0)
{
	$classes['content'] = 'eight columns alpha';
	$classes['meta'] = 'eight columns alpha omega article-meta';
	$classes['images'] = 'eight columns omega';
}
else
{
	$classes['content'] = 'twelve columns alpha';
	$classes['meta'] = 'four columns omega article-meta';
	$classes['images'] = '';
}
?>

<div class="row article">

	<div class="<?php echo $classes['content'] ?>">
		
		<?php echo $news->n_content() ?>
		
	</div>
	
	
	<?php if (count($news->images) > 0): ?>
	
	<div class="<?php echo $classes['images'] ?>">
		
		<div class="article-meta">
			<?php $this->load->view('news/article/meta') ?>
		</div>
		
		<div class="gallery">
			<ul>
				<?php foreach ($news->images as $image): ?>
				<li><img src="<?php echo $image->src('w460') ?>"></li>
				<?php endforeach; ?>
			</ul>
		</div>
		
		<script>
		jsq.add(function() { $(".gallery").gallery(); });
		</script>
		
	</div>
	
	<?php else: ?>
	
	<div class="<?php echo $classes['meta'] ?>">
		<?php $this->load->view('news/article/meta') ?>
	</div>
	
	<?php endif; ?>
	

</div>