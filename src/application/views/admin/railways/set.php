<?php
$errors = validation_errors();
echo form_open_multipart('', array('id' => 'railway_set'));
if (isset($railway)) echo form_hidden('r_id', $railway->r_id());
if ( ! empty($errors)) echo '<div class="alert error"><ul>' . $errors . '</ul></div>';
?>



<div class="boxfill add-bottom">
	
	<div class="boxfill-heading">
		General details
	</div>
	
	<table class="form">
	
		<tr>
			<td class="title">
				<label for="r_name" <?php if (form_error('r_url')) echo 'class="error"' ?>>Name</label>
			</td>
			<td class="input"><?php echo form_input(array(
				'name' => 'r_name',
				'id' => 'r_name',
				'size' => '40',
				'value' => set_value('r_name', $railway->r_name())
			)); ?></td>
		</tr>
		
		<tr>
			<td class="title">
				<label for="r_url" <?php if (form_error('r_url')) echo 'class="error"' ?>>Web address</label>
			</td>
			<td class="input"><?php echo form_input(array(
				'name' => 'r_url',
				'id' => 'r_url',
				'size' => '50',
				'value' => set_value('r_url', $railway->r_url())
			)); ?></td>
		</tr>
		
		<tr class="vat nob">
			<td class="title">
				<label for="r_info_source" <?php if (form_error('r_info_source')) echo 'class="error"' ?>>Information</label>
			</td>
			<td class="input"><?php echo form_textarea(array(
				'name' => 'r_info_source',
				'id' => 'r_info_source',
				'cols' => 80,
				'rows' => 20,
				'value' => element('r_info_source', $_POST, $railway->r_info_source()),
			)); ?></td>
		</tr>	
	
	</table>
	
	
	<div class="boxfill-heading internal">
		Photos
	</div>
	
	
	<table class="form">
		
		<tr class="vat nob">
			<td class="title" <?php if (form_error('r_info_src')) echo 'class="error"' ?>>
				<label for="r_photo">Add new</label>
			</td>
			<td class="input">
				
				<?php echo form_input(array(
					'name' => 'r_photo_url',
					'id' => 'r_photo_url',
					'size' => 60,
					'value' => '',
					'placeholder' => 'URL to a photo'
				)); ?>
				<div class="more">
					<br>
					<strong>- OR - </strong>
					<br><br>
					<div id="file-uploader">		
					<noscript>
						<input type="file" name="userfile" size="20">
					</noscript>         
				</div>
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
									.val(res.i_id)
									.appendTo("form#railway_set");
							}
						}
					});
				});
				</script>
				
				<?php
				/*
				<?php echo form_input(array(
					'name' => 'r_photo_url',
					'id' => 'r_photo_url',
					'class' => 'xlarge'
				)); ?>
				<div class="more">
					<br><br>
					<strong>- OR - </strong>
					<br><br>
					<input type="file" class="input-file" name="userfile" id="userfile">
				</div>
				*/
				?>
				
			</td>
			
		</tr>
		
		<tr class="vat">
			<td class="title"><label>Current photos</label>
			</td>
			<td>
				<?php foreach ($railway->images as $image): ?>
				<div style="width: 100px; height: 140px; float: left; display: block; margin-right: 5px;">
					<img src="<?php echo $image->src('c100x100') ?>">
					<small><a href="#" data-r_id="<?php echo $railway->r_id() ?> "data-i_id="<?php echo $image->i_id() ?>" class="remove image">Remove</a></small>
				</div>
				<?php endforeach; ?>
				
				<?php if (empty($railway->images)): ?>
				<p>None yet!</p>
				<?php endif; ?>
			</td>
		</tr>
		
	</table>
	
	
	<div class="boxfill-heading internal">
		Location
	</div>
	
	
	<table class="form">
		
		<tr class="vat">
			<td class="title">
				<label for="r_postcode" <?php if (form_error('r_postcode')) echo 'class="error"' ?>>Post code</label>
			</td>
			<td class="input">
				<?php echo form_input(array(
					'name' => 'r_postcode',
					'id' => 'r_postcode',
					'size' => '10',
					'value' => set_value('r_postcode', $railway->r_postcode())
				)); ?>
				<button class="small black button" id="lookup_postcode">Look up</button>
			</td>
			
			
			<td rowspan="4" style="width: 400px">
				<div style="width: 100%; height: 200px; overflow: hidden; display: block;" id="map">
					<?php
					if ($railway->latlng())
					{
						$data['urlonly'] = false;
						$data['coords'] = $railway->latlng();
						$data['size'] = '460x200';
						echo $this->maps->staticmap($data);
					}
					?>
				</div>
			</td>
			
			
		</tr>
		
		<tr>
			<td class="title">
				<label for="r_locator" <?php if (form_error('r_locator')) echo 'class="error"' ?>>Locator square</label>
			</td>
			<td class="input">
				<?php echo form_input(array(
					'name' => 'r_locator',
					'id' => 'r_locator',
					'size' => '10',
					'value' => set_value('r_locator', $railway->r_locator())
				)); ?>
				<button class="small black button" id="lookup_locator">Look up</button>
			</td>
		</tr>
		
		<tr>
			<td class="title">
				<label for="r_wab" <?php if (form_error('r_wab')) echo 'class="error"' ?>>WAB area</label>
			</td>
			<td class="input">
				<?php echo form_input(array(
					'name' => 'r_wab',
					'id' => 'r_wab',
					'size' => '10',
					'value' => set_value('r_wab', $railway->r_wab())
				)); ?>
			</td>
		</tr>
		
		<tr class="nob">
			<td class="title">
				<label>Lat/Long</label>
			</td>
			<td class="input">
				<?php echo form_input(array(
					'name' => 'r_lat',
					'id' => 'r_lat',
					'class' => 'latlng',
					'size' => '10',
					'value' => set_value('r_lat', $railway->r_lat())
				));
				echo form_input(array(
					'name' => 'r_lng',
					'id' => 'r_lng',
					'class' => 'latlng',
					'size' => '10',
					'value' => set_value('r_lng', $railway->r_lng())
				)); ?>
			</td>
		</tr>
	
	</table>
	
	
</div>


<div class="form-actions">
	<?php
	$text = (isset($railway)) ? 'Save' : 'Add';
	?>
	<button class="black button icon tick"><span><?php echo $text ?></span></button>
</div>



<script>
jsq.add(function(){
	
	$('#r_info_source').markItUp(mySettings);
	
	// Remove attached images on click of remove link
	$("a.remove.image").on("click", function(e) {
		e.preventDefault();
		var c = $(this).parents("div")[0];
		$.post(siteurl + "admin/railways/remove_image", {
			i_id: $(this).data("i_id"),
			r_id: $(this).data("r_id"),
		}, function() {
			$(c).remove();
		});
	});
	
});
</script>