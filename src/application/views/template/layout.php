<?php
// Decide on page title
$title_arr[] = (isset($title)) ? $title : null;
$title_arr[] = $this->config->item('site_name');
$title_string = implode(' - ', array_filter($title_arr));

// Sidebar? Presume not!
$left_classes = "span16 body";
$right_classes = "";
$show_sidebar = false;
if (isset($sidebar) && !empty($sidebar))
{
	$left_classes = "span12 body";
	$right_classes = "span4 sidebar";
	$show_sidebar = true;
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
	<title><?php echo $title_string ?></title>
	<meta name="description" content="Railways on the Air - amateur radio special event">
	<meta name="author" content="Craig A Rodway. Bishop Auckland Radio Amateur Club">
	
	<!--[if lt IE 9]>
		<script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
	<![endif]-->
	
	<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
	
	<link rel="stylesheet" href="css/bootstrap.css">
	<link rel="stylesheet" href="css/rota.css">
	
	<link rel="shortcut icon" href="img/global/favicon.ico">
	<link rel="apple-touch-icon" href="img/global/apple-touch-icon.png">
	<link rel="apple-touch-icon" sizes="72x72" href="img/global/apple-touch-icon-72x72.png">
	<link rel="apple-touch-icon" sizes="114x114" href="img/global/apple-touch-icon-114x114.png">
	
	<script type="text/javascript">
	var baseurl = "<?php echo $this->config->item('base_url') . 'assets/' ?>";
	var siteurl = "<?php echo site_url() ?>/";

	var jsq = (function(){
		
		var q = [];		// internal queue of functions that are added
		var fs = {};		// public functions
		
		// Add a function to the queue
		fs.add = function(f){
			q.push(f);
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

</head>
<body>


	<?php $this->load->view('template/header', $header) ?>
	
	
	<div class="container main">
		
		<div class="row">
	
			<div class="span16 title">
				<h1><?php echo (isset($title)) ? $title : $this->config->item('site_name') ?></h1>
			</div> <!-- / .title -->
			
			<?php
			$flashes = array('success', 'warning', 'error', 'info');
			$flashmsgs = null;
			foreach ($flashes as $f)
			{
				if ($this->session->flashdata($f))
				{
					$flashmsgs .= '<div class="alert-message ' . $f . '"><p>' . $this->session->flashdata($f) . '</p></div>';
				}
			}
			if ($flashmsgs) echo '<div class="span16">' . $flashmsgs . '</div>';
			?>
			
			<div class="<?php echo $left_classes ?>">
				
				<?php echo $body ?>
				
			</div> <!-- / .body -->
			
			<?php if ($show_sidebar): ?>
			
			<div class="<?php echo $right_classes ?>">
				<?php echo $sidebar ?>
			</div> <!-- / .sidebar -->
			
			<?php endif; ?>
		
		</div> <!-- / .row -->
		
	</div> <!-- / .container.main -->
	
	
	<?php $this->load->view('template/footer') ?>
	
	
	<script src="js/jquery-1.7.1.min.js"></script>
	<script src="js/bootstrap-modal.js"></script>
	<?php
	if ( ! empty($js))
	{
		foreach ($js as $s)
		{
			echo '<script src="js/' . $s . '"></script>'."\n";
		}
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