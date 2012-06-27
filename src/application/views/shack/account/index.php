<?php$errors = validation_errors();echo form_open('', array('id' => 'accounts_set'));if (isset($account)) echo form_hidden('a_id', $account->a_id());if ( ! empty($errors)) echo '<div class="alert error"><ul>' . $errors . '</ul></div>';?><div class="boxfill add-bottom">		<div class="boxfill-heading">		Account details	</div>		<table class="form">			<tr>			<td class="title">				<label for="a_email" <?php if (form_error('a_email')) echo 'class="error"' ?>>Email address</label>			</td>			<td class="input"><?php echo form_input(array(				'name' => 'a_email',				'id' => 'a_email',				'size' => '50',				'value' => set_value('a_email', $account->a_email())			)); ?></td>		</tr>				<tr>			<td class="title">				<label for="a_password1" <?php if (form_error('a_password1')) echo 'class="error"' ?>>Password</label>			</td>			<td class="input"><?php echo form_password(array(				'name' => 'a_password1',				'id' => 'a_password1',				'size' => '30'			)); ?></td>		</tr>				<tr>			<td class="title">				<label for="a_password2" <?php if (form_error('a_password2')) echo 'class="error"' ?>>Password (again)</label>			</td>			<td class="input"><?php echo form_password(array(				'name' => 'a_password2',				'id' => 'a_password2',				'size' => '30'			)); ?></td>		</tr>				<tr>			<td class="title"></td>			<td class="input">				<?php $text = (isset($account)) ? 'Save' : 'Add'; ?>				<button class="black button icon tick"><span><?php echo $text ?></span></button>			</td>		</tr>			</table></div></form><div class="boxfill add-bottom">		<div class="boxfill-heading">		Delete account	</div>		<p>This will just remove your account entry from the system - your 	operator profiles and station registrations will remain as they are.	If you wish for these to be deleted as well, please contact us. 	<span style="color: darkred">There is no undo. This action is permanent.</span></p>		<?php echo form_open('shack/account/delete') ?>		<label for="delete"><input type="checkbox" name="delete" value="yes" id="delete"> I&rsquo;m sure</label>		<br>		<button class="red button" type="submit"><span>Delete account</span></button>	</form>	</div>