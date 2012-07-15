<?php
$errors = validation_errors();
if ( ! empty($errors)) echo '<div class="alert error"><ul>' . $errors . '</ul></div>';
echo form_open('', array('id' => 'station_register'));
if (isset($s_id)) echo form_hidden('s_id', $station->s_id());
?>

<?php if (isset($s_id)): ?>

<p>You are updating your entry for the <strong><?php echo $station->s_e_year() ?></strong> event.</p>

<?php else: ?>

<p>You are registering for the <strong><?php echo $current_event->e_year() ?></strong> event.</p>

<?php endif; ?>


<div class="boxfill add-bottom">
	
	<div class="boxfill-heading">
		Station deatils
	</div>
	
	<table class="form">
		
		
		<tr class="vat">
			<td class="title">
				<label for="r_id" <?php if (form_error('s_r_id')) echo 'class="error"' ?>>Railway</label>
			</td>
			<td class="input">
				
				<?php if (count($past_railways) > 0): ?>
				
				<div class="railway-list">
					
					<?php foreach ($past_railways as $r): ?>
					
					<label class="input"><?php echo form_radio(array(
						'name' => 's_r_id',
						'id' => 's_r_id_' . $r->r_id(),
						'value' => $r->r_id(),
						'checked' => ($station->r_id() == $r->r_id())
					)); ?> <?php echo $r->r_name() ?></label>
					<div class="clear"></div>
					
					<?php endforeach; ?>
					
					<label class="input"><?php echo form_radio(array(
						'name' => 's_r_id',
						'id' => 's_r_id_other',
						'value' => '',
						'checked' => FALSE
					)); ?> Another...</label>
					<div class="clear"></div>
					
				</div>
				
				<?php endif; ?>
				
				<?php if (count($past_railways) > 0): ?>
				<?php $new_railway_class = 'hidden'; ?>
				<?php endif; ?>
				
				<div class="new-railway <?php echo (isset($new_railway_class)) ? $new_railway_class : '' ?>">
					<?php echo form_input(array(
						'name' => 'railway_new',
						'id' => 'railway_new',
						'size' => '50',
						'autocomplete' => 'off',
						'value' => '',
					)); ?>
					<?php echo form_hidden('hidden_s_r_id', '') ?>
				</div>
				
			</td>
			
		</tr>
		
		
		<tr class="vat">
			<td class="title">
				<label for="s_o_id" <?php if (form_error('s_o_id')) echo 'class="error"' ?>>Operator</label>
			</td>
			<td class="input">
				
				<?php foreach ($operators as $op): ?>
				
				<label class="input"><?php echo form_radio(array(
					'name' => 's_o_id',
					'id' => 's_o_id_' . $op->o_id(),
					'value' => $op->o_id(),
					'checked' => ($station->s_o_id() == $op->o_id())
				)); ?> <?php echo $op->o_callsign() ?> - <?php echo $op->o_name() ?></label>
				<div class="clear"></div>
				
				<?php endforeach; ?>
				
			</td>
		</tr>
				
		
		<tr>
			<td class="title"></td>
			<td class="input">
				<?php $text = (isset($station)) ? 'Update' : 'Register'; ?>
				<button class="black button icon tick"><span><?php echo $text ?></span></button>
			</td>
		</tr>
		
		
	</table>

</div>

</form>

<script>
jsq.add(function() {
	
	var ac = $("input#railway_new").autocomplete({
		serviceUrl: siteurl + "/ajax/railways",
		minChars: 1,
		deferRequestBy: 0,
		noCache: true,
		onSelect: function(value, data) {
			$("input[name='hidden_s_r_id']").attr("name", "s_r_id").val(data);
		}
	});
	
	$("input[name=s_r_id]").on("change", function() {
		if ($(this).val() == '') {
			// Show, position and set autocomplete options for new railway text box
			var txtbox = $("div.new-railway").show().find("input[type='text']");
			txtbox.css("margin", "10px 0 0 25px").val("").focus();
			ac.setOptions({ width: txtbox.width() + 8 });
		} else {
			$("div.new-railway").hide();
		}
	});
	
});
</script>