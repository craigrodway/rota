<?php
$errors = validation_errors();
echo form_open('', array('id' => 'news_set'));
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
				<label for="n_content" <?php if (form_error('n_content')) echo 'class="error"' ?>>Content</label>
			</td>
			<td class="input">
				<?php echo form_textarea(array(
					'name' => 'n_content',
					'id' => 'n_content',
					'cols' => 80,
					'rows' => 20,
					'value' => set_value('n_content', $news->n_content()),
				)); ?>
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
	
	var buttons = ['html', '|', 'formatting', '|', 'bold', 'italic', '|', 
		'unorderedlist', 'orderedlist', 'outdent', 'indent', '|', 
		'image', 'video', 'file', 'link', '|',
		'alignleft', 'aligncenter', 'alignright', 'justify', '|', 
		'horizontalrule', 'fullscreen'];
	$('#n_content').redactor({ autoresize: true, buttons: buttons });
	
});
</script>