<?php
$errors = validation_errors();
echo form_open('admin/events/save');
if (isset($event)) echo form_hidden('e_id', $event->e_id);
if ( ! empty($errors)) echo '<div class="alert-box error"><ul>' . $errors . '</ul></div>';
?>


<div class="inline">
	<label for="e_year" <?php if (form_error('e_year')) echo 'class="error"' ?>>Year</label>
	<?php
	$now = (int) date('Y');
	for ($y = $now; $y <= $now + 5; $y++)
	{
		$years[$y] = $y;
	}
	echo form_dropdown('e_year', $years)
	?>
</div>


<div class="inline">
	<label for="e_start_date" <?php if (form_error('e_start_date')) echo 'class="error"' ?>>Start date</label>
	<input type="text">
</div>


<div class="inline">
	<label for="e_end_date" <?php if (form_error('e_end_date')) echo 'class="error"' ?>>End date</label>
	<input type="text">
</div>


<br>


<div class="actions" style="float:right">
	<?php
	$text = (isset($event)) ? 'Save' : 'Add';
	?>
	<input class="large right blue button" type="submit" name="submit" value="<?php echo $text ?>">
</div>