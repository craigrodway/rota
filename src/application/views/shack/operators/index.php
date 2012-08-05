<?php
$errors = validation_errors();
if ( ! empty($errors)) echo '<div class="alert error"><ul>' . $errors . '</ul></div>';
?>

<dl class="htabs">
	<dd><a href="#help"<?php echo tab_selected($tab, 'help') ?>>Help</a></dd>
	<?php foreach ($operators as $op): ?>
	<dd><a href="#<?php echo $op->o_id() ?>"<?php echo tab_selected($tab, $op->o_id()) ?>><span style="background: url(<?php echo $op->o_type('icon', FALSE) ?>) 0 50% no-repeat transparent; padding: 4px 0 4px 24px;"><?php echo $op->o_callsign() ?></span></a></dd>
	<?php endforeach; ?>
	<dd><a href="#new"<?php echo tab_selected($tab, 'new') ?>><span style="background: url(img/global/icons/add.png) 0 50% no-repeat transparent; padding: 4px 0 4px 24px;">Add new</span></a></dd>
</dl>


<?php echo form_open('') ?>

<ul class="htabs-content">

	<li id="helpTab" <?php echo tab_selected($tab, 'help') ?>>
		<h2>What is an operator profile?</h2>

		<p>An operator profile is your name and callsign that you will use for the 
		ROTA event.</p>

		<p>If you&rsquo;re taking part in ROTA individually, you should create a 
		<strong>personal</strong> profile. However, if you&rsquo;re a club, you should 
		create a <strong>club</strong> one.</p>

		<p><span class="label notice">Note</span> You can add more than one profile to 
		your account; for example, if you&rsquo;re taking part on your own but are also 
		responsible for registering a club, or if you use a different callsign a 
		following year.</p>
	</li>

	<?php foreach ($operators as $operator): ?>
	
	<li id="<?php echo $operator->o_id() ?>Tab"<?php echo tab_selected($tab, $operator->o_callsign()) ?>>
	
		<table class="form">
			
			<?php $name = 'operator[' . $operator->o_id() . '][o_callsign]'; ?>
			<tr class="vat">
				<td class="title">
					<label for="<?php echo $operator->o_id() ?>_o_callsign" <?php if (form_error($name)) echo 'class="error"' ?>>Callsign</label>
					<span class="hint">/M and /P not required</span>
				</td>
				<td class="input">
					<?php echo form_input(array(
						'name' => $name,
						'id' => $operator->o_id() . '_o_callsign',
						'size' => 10,
						'maxlength' => 10,
						'style' => 'text-transform: uppercase;',
						'value' => set_value($name, $operator->o_callsign())
					)); ?>
				</td>
			</tr>
			
			<?php $name = 'operator[' . $operator->o_id() . '][o_type]'; ?>
			<tr class="vat">
				<td class="title">
					<label for="<?php echo $operator->o_id() ?>_o_type_person" <?php if (form_error('o_type')) echo 'class="error"' ?>>Type</label>
				</td>
				<td class="input">
					<label><?php echo form_radio(array(
						'name' => $name,
						'id' => $operator->o_id() . '_o_type_person',
						'value' => 'person',
						'checked' => ($operator->o_type('text') == 'person')
					)); ?> Person</label>
					<label><?php echo form_radio(array(
						'name' => 'operator[' . $operator->o_id() . '][o_type]',
						'id' => $operator->o_id() . '_o_type_club',
						'value' => 'club',
						'checked' => ($operator->o_type('text') == 'club')
					)); ?> Club</label>
				</td>
			</tr>
			
			<?php $name = 'operator[' . $operator->o_id() . '][o_name]'; ?>
			<tr>
				<td class="title">
					<label for="<?php echo $operator->o_id() ?>_o_name" <?php if (form_error($name)) echo 'class="error"' ?>>Name</label>
				</td>
				<td class="input">
					<?php echo form_input(array(
						'name' => $name,
						'id' => $operator->o_id() . '_o_name',
						'size' => 50,
						'maxlength' => 100,
						'value' => set_value($name, $operator->o_name())
					)); ?>
				</td>
			</tr>
			
			<?php $name = 'operator[' . $operator->o_id() . '][o_url]'; ?>
			<tr>
				<td class="title">
					<label for="<?php echo $operator->o_id() ?>_o_url" <?php if (form_error('o_url')) echo 'class="error"' ?>>Web address</label>
				</td>
				<td class="input">
					<?php echo form_input(array(
						'name' => $name,
						'id' => $operator->o_id() . '_o_url',
						'size' => 50,
						'value' => set_value('o_url', $operator->o_url())
					)); ?>
				</td>
			</tr>
			
			<?php $name = 'operator[' . $operator->o_id() . '][o_info_src]'; ?>
			<tr class="vat nob">
				<td class="title">
					<label for="<?php echo $operator->o_id() ?>_o_info_src" <?php if (form_error($name)) echo 'class="error"' ?>>Information</label>
					<span class="hint">BBCode formatting</span>
				</td>
				<td class="input">
					<?php echo form_textarea(array(
						'name' => $name,
						'id' => $operator->o_id() . '_o_info_src',
						'cols' => '50',
						'rows' => '10',
						'value' => set_value($name, $operator->o_info_src())
					)); ?>
				</td>
			</tr>
			
			<?php $name = 'delete[' . $operator->o_id() . ']'; ?>
			<tr class="vat">
				<td class="title">
					<label for="delete_<?php echo $operator->o_id() ?>" style="color: darkred">Delete</label>
					<span class="hint">This cannot be undone.</span>
				</td>
				<td class="input">
					<?php echo form_hidden($name, '0'); ?>
					<?php echo form_checkbox(array(
						'name' => $name,
						'id' => 'delete_' . $operator->o_id(),
						'value' => '1',
						'checked' => FALSE,
					)); ?>
				</td>
			</tr>
			
			<tr>
				<td class="title"></td>
				<td class="input">
					<button class="black button icon tick"><span>Save</span></button>
				</td>
			</tr>
			
		</table>
	
	</li>
	
	<?php endforeach; ?>
	
	
	
	
	<li id="newTab"<?php echo tab_selected($tab, 'new') ?>>
	
		<table class="form">
			
			<?php $name = 'operator[new][o_callsign]'; ?>
			<tr class="vat">
				<td class="title">
					<label for="new_o_callsign" <?php if (form_error($name)) echo 'class="error"' ?>>Callsign</label>
					<span class="hint">/M and /P not required</span>
				</td>
				<td class="input">
					<?php echo form_input(array(
						'name' => $name,
						'id' => 'new_o_callsign',
						'size' => 10,
						'maxlength' => 10,
						'style' => 'text-transform: uppercase;',
						'value' => set_value($name)
					)); ?>
				</td>
			</tr>
			
			<?php $name = 'operator[new][o_type]'; ?>
			<tr class="vat">
				<td class="title">
					<label for="new_o_type_person" <?php if (form_error($name)) echo 'class="error"' ?>>Type</label>
				</td>
				<td class="input">
					<label><?php echo form_radio(array(
						'name' => $name,
						'id' => 'new_o_type_person',
						'value' => 'person',
						'checked' => set_checkbox($name, 'person'),
					)); ?> Person</label>
					<label><?php echo form_radio(array(
						'name' => $name,
						'id' => 'new_o_type_club',
						'value' => 'club',
						'checked' => set_checkbox($name, 'club'),
					)); ?> Club</label>
				</td>
			</tr>
			
			<?php $name = 'operator[new][o_name]'; ?>
			<tr>
				<td class="title">
					<label for="new_o_name" <?php if (form_error($name)) echo 'class="error"' ?>>Name</label>
				</td>
				<td class="input">
					<?php echo form_input(array(
						'name' => $name,
						'id' => 'new_o_name',
						'size' => 50,
						'maxlength' => 100,
						'value' => set_value($name)
					)); ?>
				</td>
			</tr>
			
			<?php $name = 'operator[new][o_url]'; ?>
			<tr>
				<td class="title">
					<label for="new_o_url" <?php if (form_error($name)) echo 'class="error"' ?>>Web address</label>
				</td>
				<td class="input">
					<?php echo form_input(array(
						'name' => $name,
						'id' => 'new_o_url',
						'size' => 50,
						'value' => set_value($name)
					)); ?>
				</td>
			</tr>
			
			<?php $name = 'operator[new][o_info_src]'; ?>
			<tr class="vat nob">
				<td class="title">
					<label for="new_o_info_src" <?php if (form_error($name)) echo 'class="error"' ?>>Information</label>
					<span class="hint">BBCode formatting</span>
				</td>
				<td class="input">
					<?php echo form_textarea(array(
						'name' => $name,
						'id' => 'new_o_info_src',
						'cols' => '50',
						'rows' => '10',
						'value' => set_value($name)
					)); ?>
				</td>
			</tr>
			
			<tr>
				<td class="title"></td>
				<td class="input">
					<button class="black button icon tick"><span>Add</span></button>
				</td>
			</tr>
			
		</table>
	
	</li>
	
	
</ul>


</form>