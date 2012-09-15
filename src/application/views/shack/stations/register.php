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
		
		
		<?php if (count($operators) > 0): ?>
		
		
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
					'checked' => ($station->s_o_id() == $op->o_id()),
					'data-callsign' => $op->o_callsign(),
				)); ?> <?php echo $op->o_callsign() ?> <?php echo $op->o_name() ?></label>
				<div class="clear"></div>
				
				<?php endforeach; ?>
				
			</td>
		</tr>
		
		<tr>
			<td class="title">
				<label for="s_callsign" <?php if (form_error('s_callsign')) echo 'class="error"' ?>>Callsign</label>
			</td>
			<td class="input">
				<?php echo form_input(array(
					'name' => 's_callsign',
					'id' => 's_callsign',
					'value' => $station->s_callsign(),
					'size' => 15,
					'max_length' => 10,
					'style' => 'text-transform: uppercase',
				)); ?>
			</td>
		</tr>
		
		<?php endif; ?>
		
	</table>
	
	
	<?php if (count($operators) === 0): ?>
	
	<div class="boxfill-heading internal">
		Operator details
	</div>

	<table class="form">
	
		<tr class="vat">
			<td class="title">
				<label for="o_callsign" <?php if (form_error('o_callsign')) echo 'class="error"' ?>>Callsign</label>
				<span class="hint">/M and /P not required</span>
			</td>
			<td class="input">
				<?php echo form_input(array(
					'name' => 'o_callsign',
					'id' => 'o_callsign',
					'size' => 10,
					'maxlength' => 10,
					'style' => 'text-transform: uppercase;',
					'value' => set_value('o_callsign')
				)); ?>
			</td>
		</tr>
		
		<tr class="vat">
			<td class="title">
				<label for="o_type" <?php if (form_error('o_type')) echo 'class="error"' ?>>Type</label>
			</td>
			<td class="input">
				<label><?php echo form_radio(array(
					'name' => 'o_type',
					'id' => 'o_type_person',
					'value' => 'person',
					'checked' => FALSE,
				)); ?> Person</label>
				<label><?php echo form_radio(array(
					'name' => 'o_type',
					'id' => 'o_type_club',
					'value' => 'club',
					'checked' => TRUE,
				)); ?> Club</label>
			</td>
		</tr>
	
		<tr>
			<td class="title">
				<label for="o_name" <?php if (form_error('o_name')) echo 'class="error"' ?>>Name</label>
			</td>
			<td class="input">
				<?php echo form_input(array(
					'name' => 'o_name',
					'id' => 'o_name',
					'size' => 50,
					'maxlength' => 100,
					'value' => set_value('o_name')
				)); ?>
			</td>
		</tr>
		
		<tr>
			<td class="title">
				<label for="o_url" <?php if (form_error('o_url')) echo 'class="error"' ?>>Web address</label>
			</td>
			<td class="input">
				<?php echo form_input(array(
					'name' => 'o_url',
					'id' => 'o_url',
					'size' => 50,
					'value' => set_value('o_url', 'http://'),
				)); ?>
			</td>
		</tr>
		
		<tr class="vat nob">
			<td class="title">
				<label for="o_info_src" <?php if (form_error('o_info_src')) echo 'class="error"' ?>>Information</label>
				<span class="hint">Markdown format</span>
			</td>
			<td class="input">
				<?php echo form_textarea(array(
					'name' => 'o_info_src',
					'id' => 'o_info_src',
					'cols' => 55,
					'rows' => 20,
					'value' => set_value('o_info_src')
				)); ?>
			</td>
		</tr>
		
	</table>
	
	<?php endif; ?>
	
</div>

	
<div class="form-actions">
	<?php $text = (isset($s_id)) ? 'Update' : 'Register'; ?>
	<button class="black button icon tick"><span><?php echo $text ?></span></button>
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
	
	$('#o_info_src').markItUp(mySettings);
	
	$("input[name=s_o_id]").on("change", function() {
		var $s_callsign = $("input#s_callsign");
		if (!$("input[name=s_id]").val()) $s_callsign.val($(this).data("callsign")); 
	})
	
	
});
</script>