<?php
$errors = validation_errors();
?>

<p>Thank you for verifying your email address 
<strong><?php echo $account->email ?></strong>.</p>

<p>You will now need to set a password for your Railways on the Air account. 
Please choose something memorable, but not too short.</p>


<?php echo form_open(site_url('account/setpassword')) ?>
<?php echo form_hidden('verify', $account->verify) ?>
	<div class="box">
		<?php if (!empty($errors)) echo $errors ?>
		<div class="grid_8 omega prefix_3">
			<label for="password">Password</label>
			<input type="password" name="password1" size="40" value="">
			
			<label for="password">Password (confirm)</label>
			<input type="password" name="password2" size="40" value="">
			
			<input type="submit" value="Set password" class="green button">
		</div>
		<div class="clear"></div>
	</div>
</form>