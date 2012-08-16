<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<style type="text/css">
html, body { font-family: "DejaVu Sans", Helvetica; font-size: 14px; }

table { border: 1px solid #666; border-collapse: collapse; width: 100%; }
table td, table th { border: 1px solid #666; vertical-align: top; padding: 5px; font-weight: normal; }
table th { background: #ccc; text-align: left; font-weight: bold; }
table .tr0 td { background: #fff; }
table .tr1 td { background: #ddd; }
</style>
</head>
<body>

	<div class="top">
		<img src="<?php echo $this->config->item('base_url') ?>/assets/img/global/title3.png" alt="">
		<h1><?php echo $year ?></h1>
	</div>
	
	<table>
	
		<thead>
			<tr>
				<th>Callsign</th>
				<th>Operator</th>
				<th>Railway</th>
				<th>Locator Sq</th>
				<th>WAB Area</th>
			</tr>
		</thead>
		
		<?php $i = 0; ?>
		
		<?php foreach ($stations as $s): ?>
		
		<tr class="tr<?php echo ($i & 1) ?>">
			<td><?php echo $s->operator->o_callsign() ?></td>
			<td><?php echo $s->operator->o_name() ?></td>
			<td><?php echo $s->railway->r_name() ?></td>
			<td><?php echo $s->railway->r_locator() ?></td>
			<td><?php echo $s->railway->r_wab() ?></td>
		</tr>
		
		<?php $i++; ?>

		<?php endforeach; ?>
	
	</table>
	
</body>
</html>