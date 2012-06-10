<?php
$errors = validation_errors();
echo form_open('', array('id' => 'accounts_set'));
if (isset($account)) echo form_hidden('a_id', $account->a_id());
if ( ! empty($errors)) echo '<div class="alert error"><ul>' . $errors . '</ul></div>';
?>

<div class="boxfill add-bottom">
	
	<div class="boxfill-heading">
		General details
	</div>
	
	<table class="form">
	
		<tr>
			<td class="title">
				<label for="a_email" <?php if (form_error('a_email')) echo 'class="error"' ?>>Email address</label>
			</td>
			<td class="input"><?php echo form_input(array(
				'name' => 'a_email',
				'id' => 'a_email',
				'size' => '50',
				'value' => set_value('a_email', $account->a_email())
			)); ?></td>
		</tr>
		
		<tr>
			<td class="title">
				<label for="a_password1" <?php if (form_error('a_password1')) echo 'class="error"' ?>>Password</label>
			</td>
			<td class="input"><?php echo form_password(array(
				'name' => 'a_password1',
				'id' => 'a_password1',
				'size' => '30'
			)); ?></td>
		</tr>
		
		<tr>
			<td class="title">
				<label for="a_password2" <?php if (form_error('a_password2')) echo 'class="error"' ?>>Password (again)</label>
			</td>
			<td class="input"><?php echo form_password(array(
				'name' => 'a_password2',
				'id' => 'a_password2',
				'size' => '30'
			)); ?></td>
		</tr>
		
		<tr>
			<td class="title">
				<label for="a_enabled" <?php if (form_error('a_enabled')) echo 'class="error"' ?>>Enable</label>
			</td>
			<td class="input">
				<?php echo form_hidden('a_enabled', 'N') ?>
				<label class="input"><?php echo form_checkbox(array(
					'name' => 'a_enabled',
					'id' => 'a_enabled',
					'value' => 'Y',
					'checked' => ($account->a_enabled())
				)); ?> Yes</label>
			</td>
		</tr>
		
		<?php if ( ! $account->a_id()): ?>
		
		<tr class="vat">
			<td class="title">
				<label for="a_enabled" <?php if (form_error('a_enabled')) echo 'class="error"' ?>>Verification</label>
			</td>
			<td class="input">
				<label class="input"><?php echo form_radio(array(
					'name' => 'a_verify',
					'id' => 'a_verify_send',
					'value' => 'send',
					'checked' => FALSE,
				)); ?> Account must be verified by email</label>
				<div class="clear"></div>
				<label class="input"><?php echo form_radio(array(
					'name' => 'a_verify',
					'id' => 'a_verify_donotsend',
					'value' => 'do_not_send',
					'checked' => TRUE,
				)); ?> Set account as verified now</label>
			</td>
		</tr>
		
		<?php endif; ?>
			
		<tr class="vat">
			<td class="title">
				<label for="a_type" <?php if (form_error('a_type')) echo 'class="error"' ?>>Type</label>
			</td>
			<td class="input">
				<label class="input"><?php echo form_radio(array(
					'name' => 'a_type',
					'id' => 'a_type_member',
					'value' => 'member',
					'checked' => ($account->a_type('text') == 'member')
				)); ?> Member</label>
				<div class="clear"></div>
				<label class="input"><?php echo form_radio(array(
					'name' => 'a_type',
					'id' => 'a_type_admin',
					'value' => 'admin',
					'checked' => ($account->a_type('text') == 'admin')
				)); ?> Admin</label>
			</td>
		</tr>
		
	</table>
	
</div>

<div class="form-actions">
	<?php $text = (isset($account)) ? 'Save' : 'Add'; ?>
	<button class="black button icon tick"><span><?php echo $text ?></span></button>
</div>