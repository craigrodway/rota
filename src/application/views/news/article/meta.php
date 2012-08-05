<p class="article-datetime"><?php echo $news->n_datetime_posted('d M Y H:i') ?></p>
	
<?php if ($news->n_e_year()): ?>
<p class="article-event">
	About the <a href="<?php echo site_url('news/' . $news->n_e_year()) ?>" title="View stations registered for this event"><?php echo $news->n_e_year() ?></a> event
</p>
<?php endif; ?>

<?php if ($news->n_o_id()): ?>
<p class="article-author">
	Posted by <span class="article-author-callsign"><?php echo anchor('news/author/' . $news->operator->o_slug(), $news->operator->o_callsign()) ?></span> (<span class="article-author-name"><?php echo $news->operator->o_name() ?></span>)
</p>
<?php endif; ?>

<?php if ($news->n_r_id()): ?>
<p class="article-railway">
	Related to <?php echo anchor('news/railway/' . $news->railway->r_slug(), $news->railway->r_name()) ?>
</p>
<?php endif; ?>