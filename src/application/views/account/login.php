<div class="grid_5 prefix_5 suffix_6">

	<div class="box">
	
		<?php echo form_open(current_url()) ?>
		
			<label for="password">Email address</label>
			<input type="text" name="email" size="40" value="">
			
			<label for="password">Password</label>
			<input type="password" name="password" size="40" value="">
			
			<input type="submit" value="Log In" class="green button">
				&nbsp; &nbsp; 
				<?php echo anchor('account/elephant', 'Forgot your password?') ?>
				
		</form>
		
	</div>
	
	<div class="clear"></div>
		
</div>