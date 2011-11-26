	<div class="pc100 header">
		
		<div class="container">
			
			<div class="twelve columns logo">
				<a href="<?php echo base_url() ?>"><img src="img/global/title.png"></a>
			</div>
			
			<div class="four columns account">
				<div style="margin-right:15px">
					Register | Log In
				</div>
			</div>
			
		</div>
		
		<div class="clear"></div>
		
	</div>
	<!-- / .pc100 -->
	
	
	<div class="pc100 nav add-bottom">
		
		<div class="container">
			
			<div class="eight columns">
				<ul class="horizontal">
					<?php foreach ($nav as $item): ?>
						<li><?php echo anchor($item[0], $item[1]) ?></li>
					<?php endforeach; ?>
				</ul>
			</div>
			
			<div class="eight columns">
				<!-- if logged in: <ul class="horizontal" style="float:right">
					<li><a href="#">Post news update</a></li>
					<li><a href="#">Upload log</a></li>
					<li><a href="#">My Account</a></li>
				</ul> -->
			</div>
			
		</div>
		
	</div>