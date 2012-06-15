<?php
$errors = validation_errors();
echo form_open('', array('id' => 'events_set'));
if (isset($event)) echo form_hidden('s_id', $stations->s_id());
if ( ! empty($errors)) echo '<div class="alert error"><ul>' . $errors . '</ul></div>';
?>


<div class="boxfill add-bottom">
	
	<div class="boxfill-heading">
		Station details
	</div>
	
	<table class="form">
		
		<tr>
			<td class="title">
				<label>Date registered</label>
			</td>
			<td class="input">
				<?php echo $station->s_date_registered() ?>
			</td>
		</tr>
		<tr>
			<td class="title">
				<label for="s_e_year" <?php if (form_error('s_e_year')) echo 'class="error"' ?>>Event</label>
			</td>
			<td class="input">
				<?php echo form_dropdown('s_e_year', $events, $station->s_e_year(), 'id="s_e_year"') ?>
			</td>
		</tr>
		
		<tr class="vat">
			<td class="title">
				<label for="s_o_id" <?php if (form_error('s_o_id')) echo 'class="error"' ?>>Operator</label>
			</td>
			<td class="input">
				<?php echo form_dropdown('s_o_id', $operators, $station->s_o_id(), 'id="s_o_id"') ?>
			</td>
		</tr>
		
		<tr>
			<td class="title">
				<label for="s_r_id" <?php if (form_error('s_r_id')) echo 'class="error"' ?>>Railway</label>
			</td>
			<td class="input">
				<?php echo form_dropdown('s_r_id', $railways, $station->s_r_id(), 'id="s_r_id"') ?>
			</td>
		</tr>
		
		<tr>
			<td class="title"><label>Log</label></td>
			<td class="input">
				<?php if ($station->s_log()): ?>
					<span class="label green">uploaded</span> <?php echo $station->s_date_log_uploaded() ?>.
				<?php else: ?>
					<span class="label red">not uploaded</span>
				<?php endif; ?>
			</td>
		</tr>
		
		<tr>
			<td class="title">
				<label for="s_num_contacts" <?php if (form_error('s_num_contacts')) echo 'class="error"' ?>>Number of contacts</label>
			</td>
			<td class="input">
				
				<?php if ($station->s_log()): ?>
					
					<?php echo form_input(array(
						'name' => 'o_callsign',
						'id' => 'o_callsign',
						'size' => 10,
						'maxlength' => 10,
						'style' => 'text-transform: uppercase;',
						'value' => set_value('o_callsign', $operator->o_callsign())
					)); ?>
					
				<?php else: ?>
				
					<span class="orange label">waiting for log</span>
					
				<?php endif; ?>
				
			</td>
		</tr>
		
	</table>

</div>


<div class="form-actions">
	<?php $text = (isset($station)) ? 'Save' : 'Add'; ?>
	<button class="black button icon tick"><span><?php echo $text ?></span></button>
</div>


</form>