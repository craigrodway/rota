<?php
$errors = validation_errors();
echo form_open('', array('id' => 'railways_set'));
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
		
		<tr class="nob">
			<td class="title">
				<label for="r_info_src" <?php if (form_error('r_info_src')) echo 'class="error"' ?>>Information</label>
			</td>
			<td class="input"><?php echo form_textarea(array(
				'name' => 'r_info_src',
				'id' => 'r_info_src',
				'cols' => '50',
				'rows' => '10',
				'value' => set_value('r_info_src', $railway->r_info_src())
			)); ?></td>
		</tr>	
	
	</table>
	
	
	<div class="boxfill-heading internal">
		Photo
	</div>
	
	
	<table class="form">
		
		<tr class="nob">
			<td class="title" <?php if (form_error('r_info_src')) echo 'class="error"' ?>>
				<label for="r_photo">Photo</label>
			</td>
			<td class="input">
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