<?php
$left_classes = 'sixteen columns content';
$right_classes = '';
if ($this->layout->has('sidebar'))
{
	$left_classes = 'twelve columns content';
	$right_classes = 'four columns sidebar';
}
?>
<!DOCTYPE html>
<!--[if lt IE 7 ]><html class="ie ie6" lang="en"> <![endif]-->
<!--[if IE 7 ]><html class="ie ie7" lang="en"> <![endif]-->
<!--[if IE 8 ]><html class="ie ie8" lang="en"> <![endif]-->
<!--[if (gte IE 9)|!(IE)]><!--><html lang="en"><!--<![endif]-->
<head>
	
	<meta charset="utf-8">
	<base href="<?php echo $this->config->item('base_url') . 'assets/' ?>">
	<title><?php echo $this->layout->get_title('full') ?></title>
	<meta name="description" content="Railways on the Air - amateur radio special event">
	<meta name="author" content="Craig A Rodway. Bishop Auckland Radio Amateur Club">
	
	<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
	
	<link rel="stylesheet" href="css/base.css">
	<link rel="stylesheet" href="css/skeleton.css">
	<link rel="stylesheet" href="css/layout.css">
	<link rel="stylesheet" href="vendor/reveal/reveal.css">
	
	<link rel="shortcut icon" href="img/global/favicon.ico">
	<!--<link rel="apple-touch-icon" href="img/global/apple-touch-icon.png">
	<link rel="apple-touch-icon" sizes="72x72" href="img/global/apple-touch-icon-72x72.png">
	<link rel="apple-touch-icon" sizes="114x114" href="img/global/apple-touch-icon-114x114.png"> -->
	
	<script type="text/javascript">
	var baseurl = "<?php echo $this->config->item('base_url') . 'assets/' ?>";
	var siteurl = "<?php echo site_url() ?>/";

	var jsq = (function(){
		
		var q = [];		// internal queue of functions that are added
		var fs = {};		// public functions
		
		// Add a function to the queue
		fs.add = function(f){
			//console.log(typeof(f));
			//if (f != "undefined" OR typeof(f) != "undefined") q.push(f);
			if (typeof(f) == "function") q.push(f);
		};
		
		// Run the queued functions
		fs.run = function(){
			if (typeof(q) != "undefined") {
				for (var i=0, len=q.length; i<len; i++) {
					q[i]();
				}
			}
		};
		
		return fs;
	})();
	</script>
	
	<!--[if lt IE 9]>
		<script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
	<![endif]-->

</head>
<body>
	
	<div class="container header">
		
		<div class="six columns left">
			<a href="<?php echo site_url() ?>"><img src="img/global/title.png" title="Railways on the Air"></a>
		</div>
		
		<div class="ten columns right">
			
			<ul class="horizontal">
				<?php
				foreach ($nav['top'] as $item){
					$cls = array('i', $item[2]);
					$uri = $this->uri->uri_string();
					$uri = (empty($uri)) ? 'home' : $uri;
					if (stristr($uri, $item[0]))
					{
						$cls[] = 'active';
					}
					$cls = 'class="' . implode(' ', $cls) . '"';
					echo '<li>' . anchor($item[0], $item[1], $cls) . '</li>';
				}
				?>
			</ul>
			
			<div class="clear"></div>
			
			<div class="status">
				<?php if ($this->auth->logged_in()): ?>
					<em>Logged in as <strong><?php echo $this->session->userdata('email') ?></strong>.</em>
				<?php endif; ?>
			</div> <!-- / .status -->
			
		</div> <!-- / .right -->
		
		<div class="clear"></div>
		
	</div>
	
	
	<div class="container body body-top">
	
		<div class="eight columns title">
			<h4><?php echo $this->layout->get_title() ?></h4>
		</div>
		
		<div class="eight columns flash">
			<?php
			$flashes = array('success', 'warning', 'error');
			$flashmsgs = null;
			foreach ($flashes as $f)
			{
				if ($this->session->flashdata($f))
				{
					$flashmsgs .= '<div class="alert alert-' . $f . '">' . $this->session->flashdata($f) . '</div>';
				}
			}
			if ($flashmsgs) echo $flashmsgs;
			?>
		</div>
		
		<div class="clear"></div>
		
	</div>
	
	
	<?php
	$nav_class = '';
	if ($this->session->userdata('type') == 'admin')
	{
		$nav_class = 'admin';
	}
	?>
	
	
	<div class="container body nav <?php echo $nav_class ?>">
		
		<div class="sixteen columns">
			<ul class="horizontal">
				<?php foreach ($nav['primary'] as $item): ?>
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
		
	</div>
	
	
	<div class="container body body-main">
		
		<div class="<?php echo $left_classes ?>">
			<?php echo $this->layout->get('content') ?>
		</div> <!-- / .content -->
		
		<?php if ($this->layout->has('sidebar')): ?>
		<div class="<?php echo $right_classes ?>">
			<?php echo $this->layout->get('sidebar'); ?>
		</div> <!-- / .sidebar -->
		<?php endif; ?>
		
		<div class="clear"></div>
	
	</div> <!-- / .body.body-main -->
	
	
	<div class="container footer">
		<div class="eight columns left">
			<p>&copy; Bishop Auckland Radio Amateur Club. Location data by <a href="http://nearby.org.uk/">Nearby.org.uk</a>.</p>
		</div>
		<div class="eight columns right"><p>Site by <a href="http://webman.me.uk/">Craig A Rodway</a>.</p></div>
		<div class="clear"></div>
	</div>
	
	
	<script src="js/jquery-1.7.1.min.js"></script>
	<script src="js/amplify.min.js"></script>
	<script src="js/tabs.js"></script>
	<script src="js/modules/ROTA.js"></script>
	<script src="vendor/reveal/jquery.reveal.js"></script>
	
	<?php
	foreach ($this->layout->get_js() as $s)
	{
		echo '<script src="js/' . $s . '"></script>'."\n";
	}
	?>
	<script type="text/javascript">
	$(document).ready(function(){ jsq.run(); });
	</script>
	
	<?php
	if ( ! empty($map))
	{
		echo $map['js'];
	}
	?>


</body>
</html>