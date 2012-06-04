<div class="two-thirds column alpha">

<?php
$errors = validation_errors();
?>

<?php if (empty($errors)): ?>

	<p>To participate on the Railways on the Air website, you will need to create 
	an account. Here are just a few benefits you will receive when you have an account:</p>

	<p>
		<ul class="disc">
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

	<p>Just enter your email address in the box below, and we'll send you a 
	message explaining what	to do next. Don't worry, it's quite straight-forward!</p>

<?php else: ?>

	<?php echo $errors ?>
	
<?php endif; ?>


<?php echo form_open(current_url()) ?>
	
	<div class="panel">
		
		<div class="inline">
			<label for="email">Email address</label>
			<?php echo form_input(array(
				'name' => 'email',
				'id' => 'email',
				'size' => '40'
			)); ?>
		</div>
		
		<div class="inline">
			<label>&nbsp;</label>
			<button type="submit" name="submit" class="black button icon tick"><span>Submit</span></button>
		</div>

	</div>
	
</form>

</div>

<div class="one-third column omega">&nbsp;</div>