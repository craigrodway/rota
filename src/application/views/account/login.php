<br>

<div class="row">

	<div class="panel" style="margin: 0 auto; width: 340px;">
		
		<div style="margin: 25px;">
		
			<?php echo form_open(current_url()) ?>
			
				<label for="email">Email address</label>
				<input type="text" name="email" size="40" value="">
				
				<label for="password">Password</label>
				<input type="password" name="password" size="40" value="">
			
				<button type="submit" class="green button icon lock"><span>Log In</span></button>
				&nbsp; &nbsp; 
				<?php echo anchor('account/elephant', 'Forgot your password?') ?>
				
			</form>
			
		</div>
	
	</div> <!-- / .eight columns -->
	
</div> <!-- / .row -->