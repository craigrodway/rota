	<div class="pc100 header">
		
		<div class="container_16">
			
			<div class="grid_8 logo">
				<a href="<?php echo base_url() ?>"><img src="img/global/title.png"></a>
			</div>
			
			<div class="grid_8 account">
				<div style="margin-right:15px">
					<?php if ($this->auth->logged_in()): ?>
						Welcome <strong><?php echo $this->session->userdata('email') ?></strong> 
							<?php echo ($type == 'admin') ? '(admin)' : '' ?> | 
						<?php echo anchor('account/logout', 'Log Out') ?>
					<?php else: ?>
						<?php echo anchor('account/create', 'Create an account') ?> |
						<?php echo anchor('account/login', 'Log In') ?>
					<?php endif; ?> 
				</div>
			</div>
			
		</div>
		
		<div class="clear"></div>
		
	</div>
	<!-- / .pc100 -->
	
	
	<div class="pc100 nav add-bottom user-<?php echo $type ?>">
		
		<div class="container_16">
			
			<?php if ($type == 'normal'): ?>
			
			<div class="grid_8">
				<ul class="horizontal">
					<?php foreach ($nav_main as $item): ?>
						<?php
						$cls = '';
						$uri = $this->uri->uri_string();
						$uri = (empty($uri)) ? 'home' : $uri;
						if (stristr($uri, $item[0]))
						{
							$cls = 'class="active""';
						}
						?>
						<li><?php echo anchor($item[0], $item[1], $cls) ?></li>
					<?php endforeach; ?>
				</ul>
			</div>
			
			<div class="grid_8">
				<?php if ($this->auth->logged_in()): ?>
				<ul class="horizontal" style="float:right">
					<?php foreach ($nav_shack as $item): ?>
						<?php
						$cls = '';
						$uri = $this->uri->uri_string();
						$uri = (empty($uri)) ? 'home' : $uri;
						if (stristr($uri, $item[0]))
						{
							$cls = 'class="active""';
						}
						?>
						<li><?php echo anchor($item[0], $item[1], $cls) ?></li>
					<?php endforeach; ?>
				</ul>
				<?php endif; ?>
			</div>
			
			<?php elseif ($type == 'admin'): ?>
			
			<div class="grid_16">
				<ul class="horizontal">
					<?php foreach ($nav_admin as $item): ?>
						<?php
						$cls = '';
						$uri = $this->uri->uri_string();
						$uri = (empty($uri)) ? 'home' : $uri;
						if (stristr($uri, $item[0]))
						{
							$cls = 'class="active""';
						}
						?>
						<li><?php echo anchor($item[0], $item[1], $cls) ?></li>
					<?php endforeach; ?>
				</ul>
			</div>
			
			<?php endif; ?>
			
		</div>
		
	</div>