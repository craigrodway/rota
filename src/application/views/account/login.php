<br><br>

<div class="row">

	<div class="span8 bg offset4">

		<?php echo form_open(current_url()) ?>
			
			<div class="clearfix">
				<label for="email">Email address</label>
				<div class="input">
					<input type="text" name="email" size="40" value="">
				</div>
			</div>
			
			<div class="clearfix">
				<label for="password">Password</label>
				<div class="input">
					<input type="password" name="password" size="40" value="">
				</div>
			</div>
			
			<div class="clearfix">
				<label></label>
				<div class="input">
					<input type="submit" class="btn primary" value="Log In">
					&nbsp; &nbsp; 
					<?php echo anchor('account/elephant', 'Forgot your password?') ?>
				</div>
			</div>
		
		</form>
	
	</div>

</div> <!-- / .row -->

<br><br>