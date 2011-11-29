<?php
$errors = validation_errors();
?>

<?php if (empty($errors)): ?>

	<p>To participate on the Railways on the Air website, you will need to create 
	an account. Here are just a few benefits you will receive when you have an account:</p>

	<p>
		<ul class="ticks">
			<li>Register your own or your club's station for the event</li>
			<li>Have your details saved for next time</li>
			<li>Post ROTA-related news updates</li>
			<li>Upload your QSO log</li>
			<li>Update railway information</li>
		</ul>
	</p>

	<p>We treat your information with greate care and promise never to pass on
	your personal details to third parties. We may send you the odd email in 
	relation to ROTA, but we won't spam you.</p>

	<p>Just enter your email address and we'll send you a message explaining what 
	to do next.</p>

<?php else: ?>

	<?php echo $errors ?>
	
<?php endif; ?>


<?php echo form_open(current_url()) ?>
	<div class="box">
		<div class="grid_3 alpha">
			<h5>Your email address</h5>
		</div>

		<div class="grid_8 omega">
			<input type="text" name="email" size="40" value="<?php echo set_value('email') ?>">
			<input type="submit" value="Submit" class="green button">
		</div>
		<br class="clear">
	</div>
</form>