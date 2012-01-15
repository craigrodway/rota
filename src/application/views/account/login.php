<br><br>

<div class="row">
	
	<div class="five columns alpha">&nbsp;</div>
	
	<div class="six columns">
	
		<div class="panel">

			<?php echo form_open(current_url()) ?>
			
				<label for="email">Email address</label>
				<input type="text" name="email" size="40" value="">
				
				<label for="password">Password</label>
				<input type="password" name="password" size="40" value="">
			
				<input type="submit" class="green button" value="Log In">
				&nbsp; &nbsp; 
				<?php echo anchor('account/elephant', 'Forgot your password?') ?>
				
			</form>
	
		</div> <!-- / .panel -->
	
	</div> <!-- / .eight columns -->
	
	
	<div class="five columns omega">&nbsp;</div>
	
</div> <!-- / .row -->

<br><br>