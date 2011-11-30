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
	
		<div class="clearfix">
			<label for="password1">Password</label>
			<div class="input">
				<input type="password" name="password1" size="40" value="">
			</div>
		</div>
		
		<div class="clearfix">
			<label for="password2">Password (confirm)</label>
			<div class="input">
				<input type="password" name="password2" size="40" value="">
			</div>
		</div>
		
		<div class="clearfix remove-bottom">
			<label></label>
			<div class="input">
				<input class="btn primary" type="submit" value="Set password">
			</div>
		</div>

	</div>

</form>