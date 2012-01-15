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

	<div class="row">
		
		<div class="span8">
		
			<div class="clearfix <?php if (form_error('postcode')) echo 'error' ?>">
				<label for="postcode">Post code</label>
				<div class="input"><?php
					echo form_input(array(
						'name' => 'postcode',
						'id' => 'postcode',
						'class' => 'span2',
						'value' => @set_value('postcode', $railway->postcode)
					))
				?> <button class="small btn" id="lookup_postcode">Look up</button></div>
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
				?> <button class="small btn" id="lookup_locator">Look up</button>
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
			
			
			<div class="clearfix">
				<label>Lat/Long</label>
				<div class="input"><?php
					echo form_input(array(
						'name' => 'lat',
						'id' => 'lat',
						'class' => 'span2 latlng',
						'value' => @set_value('lat', $railway->lat)
					));
					echo form_input(array(
						'name' => 'lng',
						'id' => 'lng',
						'class' => 'span2 latlng',
						'value' => @set_value('lng', $railway->lng)
					));
				?></div>
			</div>
			
			<?php
			/* echo form_hidden('lat', @set_value('lat', $railway->lat));
			echo form_hidden('lng', @set_value('lng', $railway->lng)); */
			@$railway_coords = "{$railway->lat},{$railway->lng}";
			?>
		
		</div> <!-- / .span8 -->
		
		<div class="span8">
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
		
	</div>

</fieldset>


<div class="actions">
	<?php
	$text = (isset($railway)) ? 'Save' : 'Add';
	?>
	<input class="btn primary" type="submit" name="submit" value="<?php echo $text ?>">
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