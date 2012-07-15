<p>If you have any queries regarding the Railways on the Air event, please use 
the contact form below to get in touch with us.</p>

<div class="boxfill">
	
	<div class="boxfill-heading">Contact us</div>

	<?php echo form_open('') ?>
		
		<table class="form">
			
			<?php $name = 'cn'; ?>
			<tr>
				<td class="title">
					<label for="<?php echo $name ?>" <?php if (form_error($name)) echo 'class="error"' ?>>Your name</label>
				</td>
				<td class="input">
					<?php echo form_input(array(
						'name' => $name,
						'id' => $name,
						'size' => 25,
						'maxlength' => 50,
						'value' => set_value($name)
					)); ?>
				</td>
			</tr>
			
			<?php $name = 'ce'; ?>
			<tr>
				<td class="title">
					<label for="<?php echo $name ?>" <?php if (form_error($name)) echo 'class="error"' ?>>Email address</label>
				</td>
				<td class="input">
					<?php echo form_input(array(
						'name' => $name,
						'id' => $name,
						'size' => 35,
						'maxlength' => 255,
						'value' => set_value($name, ($this->auth->logged_in()) ? $this->session->userdata('a_email') : ''),
					)); ?>
				</td>
			</tr>
			
			<?php $name = 'cm'; ?>
			<tr class="vat">
				<td class="title">
					<label for="<?php echo $name ?>" <?php if (form_error($name)) echo 'class="error"' ?>>Message</label>
				</td>
				<td class="input">
					<?php echo form_textarea(array(
						'name' => $name,
						'id' => $name,
						'rows' => 10,
						'cols' => 70,
						'value' => set_value($name),
					)); ?>
				</td> 
			</tr>
			
			<tr>
				<td class="title"></td>
				<td class="input">
					<button class="black button icon tick"><span>Send message</span></button>
				</td>
			</tr>
			
		</table>

	</form>
	
</div>