<?php
$errors = validation_errors();
echo form_open('', array('id' => 'events_set'));
if (isset($event)) echo form_hidden('e_id', $event->e_id());
if ( ! empty($errors)) echo '<div class="alert error"><ul>' . $errors . '</ul></div>';
?>


<div class="boxfill add-bottom">
	
	<div class="boxfill-heading">
		Event details
	</div>
	
	<table class="form">
	
		<tr>
			<td class="title">
				<label for="e_year" <?php if (form_error('e_year')) echo 'class="error"' ?>>Year</label>
			</td>
			<td class="input">
				<?php
				$now = (int) date('Y');
				for ($y = $now - 5; $y <= $now + 5; $y++)
				{
					$years[$y] = $y;
				}
				echo form_dropdown('e_year', $years, $event->e_year(), 'id="e_year"')
				?>
			</td>
		</tr>
		
		<tr class="vat">
			<td class="title">
				<label for="e_start_date" <?php if (form_error('e_start_date')) echo 'class="error"' ?>>Start date (Saturday)</label>
			</td>
			<td class="input">
				<?php echo form_hidden('e_start_date', set_value('e_start_date', $event->e_start_date('Y-m-d'))) ?>
				<div class="datepicker"></div>
			</td>
		</tr>
		
	</table>

</div>


<div class="form-actions">
	<?php $text = (isset($event)) ? 'Save' : 'Add'; ?>
	<button class="black button icon tick"><span><?php echo $text ?></span></button>
</div>



<script>
jsq.add(function(){
	
	// Add datepickers to form
	$(".datepicker")
		.datePicker({
			inline: true,
			month: 8,
			startDate: "01/09/2008"
		})
		.bind('dateSelected', function(e, date, $td){
			$td.parents("div.datepicker").siblings("input[type='hidden']").val(date.asString('yyyy-mm-dd'));
		}
	);
	
	// Set the selected date from form elements if present
	$(".datepicker").each(function(x){
		var dt = Date.fromString($(this).siblings("input[type='hidden']").val(), 'yyyy-mm-dd');
		$(this).dpSetSelected(dt.asString('dd/mm/yyyy'));
	});
	
	// Year change - update calendar
	$("select[name='e_year']").on("change", function(e){
		$(".datepicker").dpSetDisplayedMonth(8, $(this).val());
	}).trigger("change");
	
});
</script>