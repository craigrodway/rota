<div class="seven columns alpha">
	
	<h2 class="page-title">Log In</h2>
	<br>
	
	<div class="alert">
		If you ever registered a station for a ROTA event, you already have an account! 
		Your password is the first part of your email address.
		<br><br>
		If you registered as <em>jsmith@example.com</em>, then your password is <em>jsmith</em>.
	</div>
	
	<div class="panel">
		
		<div style="margin: 25px;">
	
			<?php echo form_open('account/login') ?>
					
				<label for="email">Email address</label>
				<input type="text" name="email" size="40" value="">
				
				<label for="password">Password</label>
				<input type="password" name="password" size="40" value="">
			
				<button type="submit" class="black button icon lock"><span>Log In</span></button>
				&nbsp; &nbsp; 
				<?php echo anchor('account/elephant', 'Forgot your password?') ?>
				
			</form>
		</div>
	</div>
</div>

<div class="eight columns omega offset-by-one">
	
	<h2 class="page-title">Create an account</h2>
	<br>
	
	<?php
	$errors = validation_errors();
	?>

	<?php if (empty($errors)): ?>
		
		<p>To participate on the Railways on the Air website, you will need to create 
		an account. Here are just a few benefits you will receive when you have an account:</p>

		<p>
			<ul class="disc">
				<li>Manage registrations for you and/or your club from the same account</li>
				<li>Have your details saved for next time</li>
				<li>Post ROTA-related news updates</li>
				<li>Upload your QSO log and download your certificates</li>
				<li>Update railway information</li>
			</ul>
		</p>
		
		<p>Just enter your email address in the box below, and we&rsquo;ll send you a 
		message explaining what	to do next. Don&rsquo;t worry, it&rsquo;s quite straight-forward!</p>

	<?php else: ?>

		<?php echo $errors ?>
		
	<?php endif; ?>

	<?php echo form_open('account/create') ?>
		
		<div class="panel">
				
			<label for="email">Email address</label>
			<?php echo form_input(array(
				'name' => 'email',
				'id' => 'email',
				'size' => '40'
			)); ?>
			<br>
			<button type="submit" name="submit" class="black button icon tick"><span>Submit</span></button>
			<br><br>
		</div>
		
	</form>
	
	<?php if ( ! $errors): ?>
	
		<p>We treat your information with greate care and promise never to pass on
		your personal details to third parties. We may send you the odd email in 
		relation to ROTA, but we won&rsquo;t spam you.</p>
	
	<?php endif; ?>

</div>