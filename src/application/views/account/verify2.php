<?php
$errors = validation_errors();
?>

<p>Thank you for verifying your email address 
<strong><?php echo $account->a_email() ?></strong>.</p>

<p>You will now need to set a password for your Railways on the Air account. 
Please choose something memorable, but not too short.</p>


<?php echo form_open(site_url('account/setpassword')) ?>
	
	<?php echo form_hidden('verify', $account->a_verify()) ?>

	<div class="boxfill add-bottom">
		
		<div class="boxfill-heading">
			Set password
		</div>
		
		<table class="form">
		
			<tr>
				<td class="title">
					<label for="password1" <?php if (form_error('password1')) echo 'class="error"' ?>>Password</label>
				</td>
				<td class="input"><?php echo form_password(array(
					'name' => 'password1',
					'id' => 'password1',
					'size' => '30'
				)); ?></td>
			</tr>
			
			<tr>
				<td class="title">
					<label for="password2" <?php if (form_error('password2')) echo 'class="error"' ?>>Password (again)</label>
				</td>
				<td class="input"><?php echo form_password(array(
					'name' => 'password2',
					'id' => 'password2',
					'size' => '30'
				)); ?></td>
			</tr>
		
		</table>
		
	</div>


	<div class="form-actions">
		<button class="black button icon tick"><span>Set password</span></button>
	</div>
	
</form>