<?php
// Decide on page title
$title_arr[] = (isset($title)) ? $title : null;
$title_arr[] = $this->config->item('site_name');
$title_string = implode(' - ', array_filter($title_arr));
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
	
	<link rel="stylesheet" href="css/base.css">
	<link rel="stylesheet" href="css/skeleton.css">
	<link rel="stylesheet" href="css/layout.css">

	<!-- <link rel="shortcut icon" href="images/favicon.ico">
	<link rel="apple-touch-icon" href="images/apple-touch-icon.png">
	<link rel="apple-touch-icon" sizes="72x72" href="images/apple-touch-icon-72x72.png">
	<link rel="apple-touch-icon" sizes="114x114" href="images/apple-touch-icon-114x114.png"> -->
	
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
		
		<div class="sixteen columns">
			
			<h1><?php echo (isset($title)) ? $title : $this->config->item('site_name') ?></h1>
			<hr>
			
			<?php echo $body ?>
			
		</div>
		
	</div> <!-- / .container.main -->
	
	
	<div class="container footer">
		
		<div class="sixteen columns"><hr class="remove-bottom"></div>
		
		<div class="eight columns left">
			&copy; <?php echo date('Y') ?> 
				<a href="http://barac.m0php.net/">Bishop Auckland Radio Amateur Club</a>. 
				Location data by <a href="http://nearby.org.uk/">nearby.org.uk</a>. 
		</div>
		
		<div class="eight columns right">
			Site by <a href="http://webman.me.uk/">Craig A Rodway</a>. 
		</div>
		
	</div> <!-- / .container.footer -->
	
	
	<script src="js/jquery-1.7.1.min.js"></script>
	<script type="text/javascript">
	$(document).ready(function(){ jsq.run(); });
	</script>


</body>
</html>