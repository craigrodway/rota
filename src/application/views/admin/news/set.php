<?php
$errors = validation_errors();
echo form_open_multipart('', array('id' => 'news_set'));
if (isset($news)) echo form_hidden('n_id', $news->n_id());
if ( ! empty($errors)) echo '<div class="alert error"><ul>' . $errors . '</ul></div>';
?>


<div class="boxfill add-bottom">
	
	<div class="boxfill-heading">
		News article
	</div>
	
	<table class="form">
		
		<tr>
			<td class="title">
				<label for="n_e_year" <?php if (form_error('n_e_year')) echo 'class="error"' ?>>Event</label>
			</td>
			<td class="input">
				<?php
				$current_event = $this->events_model->get_current();
				$events = array('' => '(None)') + $events;
				echo form_dropdown('n_e_year', $events, $news->n_e_year(), 'id="n_e_year"');
				?>
			</td>
		</tr>
		
		<tr class="vat">
			<td class="title">
				<label for="n_o_id" <?php if (form_error('n_o_id')) echo 'class="error"' ?>>Operator</label>
				<span class="hint">Author the article will appear to be written by.</span>
			</td>
			<td class="input">
				<?php
				$operators = array('' => 'Nobody (For site news)') + $operators;
				echo form_dropdown('n_o_id', $operators, $news->n_o_id(), 'id="n_o_id"');
				?>
			</td>
		</tr>
		
		<tr>
			<td class="title">
				<label for="n_r_id" <?php if (form_error('s_r_id')) echo 'class="error"' ?>>Railway</label>
			</td>
			<td class="input">
				<?php
				$railways = array('' => '(None)') + $railways;
				echo form_dropdown('n_r_id', $railways, $news->n_r_id(), 'id="n_r_id"');
				?>
			</td>
		</tr>
		
		<tr>
			<td class="title">
				<label for="n_title" <?php if (form_error('n_title')) echo 'class="error"' ?>>Title</label>
				<span class="hint">Keep it brief</span>
			</td>
			<td class="input">
				<?php echo form_input(array(
					'name' => 'n_title',
					'id' => 'n_title',
					'size' => 80,
					'maxlength' => 128,
					'value' => set_value('n_title', $news->n_title())
				)); ?>
			</td>
		</tr>
		
		<tr class="vat">
			<td class="title">
				<label for="n_content_source" <?php if (form_error('n_content)source')) echo 'class="error"' ?>>Content</label>
			</td>
			<td class="input">
				<?php echo form_textarea(array(
					'name' => 'n_content_source',
					'id' => 'n_content_source',
					'cols' => 80,
					'rows' => 20,
					'value' => element('n_content_source', $_POST, $news->n_content_source()),
				)); ?>
			</td>
		</tr>
		
		<tr class="vat">
			<td class="title">
				<label>Image</label>
			</td>
			<td class="input">
				
				<?php if ($news->n_id()): ?>
				
				<div id="file-uploader">		
					<noscript>
						<input type="file" name="userfile" size="20">
					</noscript>         
				</div>
				
				<script>
				jsq.add(function(){
					var uploader = new qq.FileUploader({
						element: document.getElementById('file-uploader'),
						multiple: true,
						action: siteurl + '/upload/image',
						debug: false,
						onComplete: function(id, file_name, res) {
							if (res.i_id) {
								$("<input>")
									.attr("name", "i_id[]")
									.attr("type", "hidden")
									.val(res.i_id).appendTo("form#news_set");
							}
						}
					});
				});
				</script>
				
				<?php else: ?>
				
				<input type="file" name="userfile" size="20">
				
				<?php endif; ?>
				
				<br><br>
				
				<?php foreach ($news->images as $image): ?>
					<div style="width: 100px; height: 140px; float: left; display: block; margin-right: 5px;">
						<img src="<?php echo $image->src('c100x100') ?>">
						<small><a href="#" data-n_id="<?php echo $news->n_id() ?> "data-i_id="<?php echo $image->i_id() ?>" class="remove image">Remove</a></small>
					</div>
				<?php endforeach; ?>
				
			</td>
		</tr>
		
	</table>

</div>


<div class="form-actions">
	<?php $text = (isset($news)) ? 'Save' : 'Create'; ?>
	<button class="black button icon tick"><span><?php echo $text ?></span></button>
</div>



<script>
jsq.add(function(){
	
	$('#n_content_source').markItUp(mySettings);
	
	// Remove attached images on click of remove link
	$("a.remove.image").on("click", function(e) {
		e.preventDefault();
		var c = $(this).parents("div")[0];
		$.post(siteurl + "admin/news/remove_image", {
			i_id: $(this).data("i_id"),
			n_id: $(this).data("n_id"),
		}, function() {
			$(c).remove();
		});
	})
	
});
</script>