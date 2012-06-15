<?php
$errors = validation_errors();
echo form_open('', array('id' => 'operators_set'));
if (isset($event)) echo form_hidden('o_id', $operator->o_id());
if ( ! empty($errors)) echo '<div class="alert error"><ul>' . $errors . '</ul></div>';
?>

<div class="boxfill add-bottom form">
	
	<div class="boxfill-heading">
		Operator profile
	</div>

	<table class="form">
	
		<tr class="vat">
			<td class="title">
				<label for="o_a_id" <?php if (form_error('o_a_id')) echo 'class="error"' ?>>Account</label>
				<span class="hint">Account for the operator</span>
			</td>
			<td class="input">
				<?php
				$accounts = array_reverse($accounts, TRUE);
				$accounts[''] = '(None)';
				$accounts = array_reverse($accounts, TRUE);
				echo form_dropdown('o_a_id', $accounts, $operator->o_a_id(), 'id="o_a_id"');
				?>
			</td>
		</tr>
		
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
					'value' => set_value('o_callsign', $operator->o_callsign())
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
					'checked' => ($operator->o_type('text') == 'person')
				)); ?> Person</label>
				<label><?php echo form_radio(array(
					'name' => 'o_type',
					'id' => 'o_type_club',
					'value' => 'club',
					'checked' => ($operator->o_type('text') == 'club')
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
					'value' => set_value('o_name', $operator->o_name())
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
					'value' => @set_value('o_url', $operator->o_url())
				)); ?>
			</td>
		</tr>
		
		<tr class="vat nob">
			<td class="title">
				<label for="o_info_src" <?php if (form_error('o_info_src')) echo 'class="error"' ?>>Information</label>
				<span class="hint">BBCode formatting</span>
			</td>
			<td class="input">
				<?php echo form_textarea(array(
					'name' => 'o_info_src',
					'id' => 'o_info_src',
					'cols' => '50',
					'rows' => '10',
					'value' => @set_value('o_info_src', $operator->o_info_src())
				)); ?>
			</td>
		</tr>
		
	</table>

</div>


<div class="form-actions">
	<?php
	$text = (isset($operator)) ? 'Save' : 'Add';
	?>
	<button class="black button icon tick"><span><?php echo $text ?></span></button>
</div>
