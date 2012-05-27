<?php
$errors = validation_errors();
echo form_open('admin/accounts/save');
if (isset($account)) echo form_hidden('a_id', $account->a_id);
if ( ! empty($errors)) echo '<div class="alert-box error"><ul>' . $errors . '</ul></div>';
?>

<div class="inline">
	<label for="a_email" <?php if (form_error('a_email')) echo 'class="error"' ?>>Email address</label>
	<?php echo form_input(array(
		'name' => 'a_email',
		'id' => 'a_email',
		'size' => '50',
		'value' => @set_value('a_email', $account->a_email)
	)); ?>
</div>

<div class="inline">
	<label for="a_password1" <?php if (form_error('a_password1')) echo 'class="error"' ?>>Password</label>
	<?php echo form_password(array(
		'name' => 'a_password1',
		'id' => 'a_password1',
		'size' => '30'
	)); ?>
</div>

<div class="inline">
	<label for="a_password2" <?php if (form_error('a_password2')) echo 'class="error"' ?>>Password (again)</label>
	<?php echo form_password(array(
		'name' => 'a_password2',
		'id' => 'a_password2',
		'size' => '30'
	)); ?>
</div>

<div class="inline">
	<?php echo form_hidden('a_enabled', 'N') ?>
	<label for="a_enabled" <?php if (form_error('a_enabled')) echo 'class="error"' ?>>Enable</label>
	<label class="input"><?php echo form_checkbox(array(
		'name' => 'a_enabled',
		'id' => 'a_enabled',
		'value' => 'Y',
		'checked' => (@$account->a_enabled == 'Y')
	)); ?> Yes</label>
	<div class="clear"></div>
</div>

<?php if ( ! @$account): ?>
<div class="inline">
	<label for="a_enabled" <?php if (form_error('a_enabled')) echo 'class="error"' ?>>Verification</label>
	<div class="inputs">
		<label><?php echo form_radio(array(
			'name' => 'a_verify',
			'id' => 'a_verify_send',
			'value' => 'send',
			'checked' => FALSE,
		)); ?> Account must be verified by email</label>
		<div class="clear"></div>
		<label><?php echo form_radio(array(
			'name' => 'a_verify',
			'id' => 'a_verify_donotsend',
			'value' => 'do_not_send',
			'checked' => TRUE
		)); ?> Set account as verified now</label>
		<div class="clear"></div>
	</div>
</div>
<?php endif; ?>

<div class="inline">
	<label for="a_type" <?php if (form_error('a_type')) echo 'class="error"' ?>>Type</label>
	<div class="inputs">
		<label><?php echo form_radio(array(
			'name' => 'a_type',
			'id' => 'a_type_user',
			'value' => 'user',
			'checked' => (@$account->a_type == 'user')
		)); ?> User</label>
		<div class="clear"></div>
		<label><?php echo form_radio(array(
			'name' => 'a_type',
			'id' => 'a_type_admin',
			'value' => 'admin',
			'checked' => (@$account->a_type == 'admin')
		)); ?> Admin</label>
	</div>
</div>
		

<br>


<div class="actions" style="float:right">
	<?php
	$text = (isset($account)) ? 'Save' : 'Add';
	?>
	<input class="large right blue button" type="submit" name="submit" value="<?php echo $text ?>">
</div>