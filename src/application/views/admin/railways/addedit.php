<?php
$errors = validation_errors();
echo form_open('admin/railways/save');
if (isset($railway)) echo form_hidden('railway_id', $railway->railway_id);

if ( ! empty($errors)) echo '<div class="alert-message block-message error"><ul>' . $errors . '</ul></div>';
?>

<fieldset>
	<legend>General info</legend>
	
	<div class="clearfix <?php if (form_error('name')) echo 'error' ?>">
		<label for="name">Name</label>
		<div class="input"><?php
			echo form_input(array(
				'name' => 'name',
				'id' => 'name',
				'class' => 'xlarge',
				'value' => @set_value('name', $railway->name)
			))
		?></div>
	</div>

	<div class="clearfix <?php if (form_error('url')) echo 'error' ?>">
		<label for="url">Web address</label>
		<div class="input">
			<div class="input-prepend">
				<span class="add-on">http://</span>
				<?php
				echo form_input(array(
					'name' => 'url',
					'id' => 'url',
					'class' => 'xlarge',
					'value' => @set_value('url', $railway->url)
				))
				?>
			</div>
		</div>
	</div>
	
	<div class="clearfix <?php if (form_error('info_src')) echo 'error' ?>">
		<label for="info_src">Information</label>
		<div class="input"><?php
			echo form_textarea(array(
				'name' => 'info_src',
				'id' => 'info_src',
				'class' => 'span6',
				'rows' => '6',
				'value' => @set_value('info_src', $railway->info_src)
			))
		?></div>
	</div>
	
	<div class="clearfix">
		<label for="photo">Photo</label>
		<div class="input">
			<div class="input-prepend">
				<span class="add-on">http://</span>
				<?php
				echo form_input(array(
					'name' => 'photo_url',
					'id' => 'photo_url',
					'class' => 'xlarge'
				))
				?>
				<p><strong><br> - OR - <br></strong></p>
				<input type="file" class="input-file" name="userfile" id="userfile">
			</div>
		</div>
	</div>
	
</fieldset>


<fieldset>
	<legend>Location</legend>
	
	<div class="clearfix <?php if (form_error('postcode')) echo 'error' ?>">
		<label for="postcode">Post code</label>
		<div class="input"><?php
			echo form_input(array(
				'name' => 'postcode',
				'id' => 'postcode',
				'class' => 'span2',
				'value' => @set_value('postcode', $railway->postcode)
			))
		?> <button class="small btn" id="postcode-lookup">Look up</button></div>
	</div>
	
	<div class="clearfix <?php if (form_error('locator')) echo 'error' ?>">
		<label for="locator">Locator square</label>
		<div class="input"><?php
			echo form_input(array(
				'name' => 'locator',
				'id' => 'locator',
				'class' => 'span2',
				'value' => @set_value('locator', $railway->locator)
			))
		?>
		<span class="help-block">Essential for placing it on the map.</span>
		</div>
	</div>
	
	<div class="clearfix <?php if (form_error('wab')) echo 'error' ?>">
		<label for="wab">WAB area</label>
		<div class="input"><?php
			echo form_input(array(
				'name' => 'wab',
				'id' => 'wab',
				'class' => 'span2',
				'value' => @set_value('wab', $railway->wab)
			))
		?></div>
	</div>
	
</fieldset>


<div class="actions">
	<?php
	$text = (isset($railway)) ? 'Save' : 'Add';
	?>
	<input class="btn primary" type="submit" name="submit" value="<?php echo $text ?>">
</div>