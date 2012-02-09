<?php
$errors = validation_errors();
echo form_open('admin/railways/save');
if (isset($railway)) echo form_hidden('r_id', $railway->r_id);
if ( ! empty($errors)) echo '<div class="alert-box error"><ul>' . $errors . '</ul></div>';
?>


<dl class="tabs contained">
	<dd><a href="#general" class="active">General info</a></dd>
	<dd><a href="#photo">Photo</a></dd>
	<dd><a href="#location">Location</a></dd>
</dl>


<ul class="tabs-content contained">
	
	
	<li class="active" id="generalTab">
		
		<div class="inline">
			<label for="r_name">Name</label>
			<?php echo form_input(array(
				'name' => 'r_name',
				'id' => 'r_name',
				'size' => '40',
				'value' => @set_value('r_name', $railway->r_name)
			)); ?>
		</div>
		
		<div class="inline">
			<label for="r_url" <?php if (form_error('r_url')) echo 'class="error"' ?>>Web address</label>
			<?php echo form_input(array(
				'name' => 'r_url',
				'id' => 'r_url',
				'size' => '50',
				'value' => @set_value('r_url', $railway->r_url)
			)); ?>
		</div>
		
		<div class="inline">
			<label for="r_info_src" <?php if (form_error('r_info_src')) echo 'class="error"' ?>>Information</label>
			<?php echo form_textarea(array(
				'name' => 'r_info_src',
				'id' => 'r_info_src',
				'cols' => '50',
				'rows' => '10',
				'value' => @set_value('r_info_src', $railway->r_info_src)
			)); ?>
		</div>
		
	</li>
	
	
	<li id="photoTab">
		
		<div class="inline">
			
			<label for="r_photo">Photo</label>
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
			
		</div>
		
	</li>
	
	
	<li id="locationTab">
	
		<div class="seven columns alpha">
		
		<div class="inline">
			<label for="r_postcode" <?php if (form_error('r_postcode')) echo 'class="error"' ?>>Post code</label>
			<?php echo form_input(array(
				'name' => 'r_postcode',
				'id' => 'r_postcode',
				'size' => '10',
				'value' => @set_value('r_postcode', $railway->r_postcode)
			)); ?>
			<button class="small black button" id="lookup_postcode">Look up</button>
		</div>
		
		<div class="inline">
			<label for="r_locator" <?php if (form_error('r_locator')) echo 'class="error"' ?>>Locator square</label>
			<?php echo form_input(array(
				'name' => 'r_locator',
				'id' => 'r_locator',
				'size' => '10',
				'value' => @set_value('r_locator', $railway->r_locator)
			)); ?>
			<button class="small black button" id="lookup_locator">Look up</button>
		</div>
		
		<div class="inline">
			<label for="r_wab" <?php if (form_error('r_wab')) echo 'class="error"' ?>>WAB area</label>
			<?php echo form_input(array(
				'name' => 'r_wab',
				'id' => 'r_wab',
				'size' => '10',
				'value' => @set_value('r_wab', $railway->r_wab)
			)); ?>
		</div>
		
		<div class="inline">
			<label>Lat/Long</label>
			<?php echo form_input(array(
				'name' => 'r_lat',
				'id' => 'r_lat',
				'class' => 'latlng',
				'size' => '10',
				'value' => @set_value('r_lat', $railway->r_lat)
			));
			echo form_input(array(
				'name' => 'r_lng',
				'id' => 'r_lng',
				'class' => 'latlng',
				'size' => '10',
				'value' => @set_value('r_lng', $railway->r_lng)
			)); ?>
		</div>
		
		</div>
			
		<?php
		/* echo form_hidden('lat', @set_value('lat', $railway->lat));
		echo form_hidden('lng', @set_value('lng', $railway->lng)); */
		@$railway_coords = "{$railway->r_lat},{$railway->r_lng}";
		?>
		
		<div class="seven columns omega">
		
		<div style="width: 100%; height: 200px; overflow: hidden; display: block;" id="map">
			<?php
			if (strlen($railway_coords > 1))
			{
				$data['urlonly'] = false;
				$data['coords'] = $railway_coords;
				$data['size'] = '460x200';
				echo $this->maps->staticmap($data);
			}
			?>
		</div>
		
		</div>
		
		<div class="clear"></div>
	
	</li>
	
</ul>


<br>


<div class="actions" style="float:right">
	<?php
	$text = (isset($railway)) ? 'Save' : 'Add';
	?>
	<input class="large right blue button" type="submit" name="submit" value="<?php echo $text ?>">
</div>


<script>
/*jsq.add(function(){
	//railways.init();
	/*$("#lookup_postcode").click(function(event){
		event.preventDefault();
		$("div#map").html("");
		ROTA.lookup_postcode($("input#postcode").val(), function(data){
			if (data.status == "ok"){
				$("input#locator").val(data.data.locator);
				var coords = data.data.latlng;
				coords = coords.split(",");
				$("input[name=lat]").val(coords[0]);
				$("input[name=lng]").val(coords[1]);
				var mapdata = { coords: data.data.latlng, size: "460x200" };
				ROTA.staticmap(mapdata, function(data){
					$('<img>').appendTo("div#map").attr("src", data.src);
					$("div#map").show();
				});
			} else {
				alert("Error: " + data.msg);
			}
		});
	})
});*/
</script>