<?php if ($this->session->flashdata('new_railway_id')): ?><div class="alert success">	You added a new railway!	<?php echo anchor('railways/edit/' . $this->session->flashdata('new_railway_id'),		'Click here to add more details about ' . $this->session->flashdata('new_railway_name') . '.'); ?></div><?php endif; ?><?php if ($stations): ?><?php $last_year = NULL; ?><?php foreach ($stations as $station): ?><?php $year_diff = ($last_year !== $station->s_e_year()); ?><!-- 12 cols to play with --><div class="row station <?php if ($year_diff) echo 'heading'; ?>">		<div class="one column alpha station-year">		<?php		if ($last_year !== $station->s_e_year())		{			echo '<h3>' . $station->s_e_year() . '</h3>';			$last_year = $station->s_e_year();		}		else		{			echo '&nbsp;';		}		?>	</div>		<div class="seven columns station-operator station-railway">		<span class="callsign"><?php echo $station->operator->o_callsign() ?></span>		<span class="name"><?php echo $station->operator->o_name() ?></span>		<span class="railway"><?php echo $station->railway->r_name(); ?></span>	</div>		<div class="two columns log-status">				<!-- <span class="green label">Log received</span>		<span style="font-weight:normal; font-size: 85%">24 contacts</span> -->				<?php if ($station->s_e_year() < $current_event->e_year()): ?>				<a href="#" class="black button">Upload log</a>				<?php endif; ?>			</div>	<div class="two colums omega ops">		<?php		if ($station->s_e_year() == $current_event->e_year())		{			echo $station->shack_edit_icon();			echo $station->shack_delete_icon();		}		?>	</div>		<div class="clear"></div>	</div><?php endforeach; ?><?php else: ?><div class="alert info">You haven&rsquo;t registered any stations. <?php echo anchor('shack/stations/register', 'Register one now!') ?></div><?php endif; ?>