<!DOCTYPE html>

<!--[if IE 7]>                  <html class="ie7 no-js" lang="en">     <![endif]-->
<!--[if lte IE 8]>              <html class="ie8 no-js" lang="en">     <![endif]-->
<!--[if (gte IE 9)|!(IE)]><!--> <html class="not-ie no-js" lang="en">  <!--<![endif]-->

<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
	<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">

	<title><?php echo $title; ?></title>

	<meta name="description" content="Cauta, exploreaza, descopera!">

	<!--[if !lte IE 6]><!-->
		<link rel="stylesheet" href="<?php echo base_url(); ?>res/css/style.css" media="screen" />
		<link rel="stylesheet" href="<?php echo base_url(); ?>res/css/jquery.fancybox.css" media="screen" />
		<link rel="stylesheet" href="//fonts.googleapis.com/css?family=Open+Sans:400,600,300,800,700,400italic|PT+Serif:400,400italic" />

	<!--<![endif]-->

	<!--[if lte IE 6]>
		<link rel="stylesheet" href="//universal-ie6-css.googlecode.com/files/ie6.1.1.css" media="screen, projection">
	<![endif]-->

	<script type="text/javascript" src="//ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js"></script>
	<script type="text/javascript" src="http://www.google.com/jsapi"></script>
	<script type="text/javascript" src="http://maps.google.com/maps/api/js?sensor=false&amp;libraries=places,drawing,panoramio,weather&amp;language=ro&amp;region=ro"></script>
	<script type="text/javascript" src="http://www.panoramio.com/wapi/wapi.js?v=1"></script>

	<!--[if !lte IE 6]><!-->
		<!--[if lt IE 9]> <script src="<?php echo base_url(); ?>res/js/selectivizr-and-extra-selectors.min.js"></script> <![endif]-->
		<script src="<?php echo base_url(); ?>res/js/respond.min.js"></script>
		<script src="<?php echo base_url(); ?>res/js/modernizr.custom.js"></script>
		<script src="<?php echo base_url(); ?>res/js/jquery.easing-1.3.min.js"></script>
		<script src="<?php echo base_url(); ?>res/js/jquery.smartStartSlider.min.js"></script>
		<script src="<?php echo base_url(); ?>res/js/jquery.jcarousel.min.js"></script>
		<script src="<?php echo base_url(); ?>res/js/jquery.cycle.all.min.js"></script>
		<script src="<?php echo base_url(); ?>res/js/jquery.isotope.min.js"></script>
		<script src="<?php echo base_url(); ?>res/js/jquery.touchSwipe.min.js"></script>
		<script src="<?php echo base_url(); ?>res/js/jquery.fancybox.pack.js?v=2.1.4"></script>
		<script src="<?php echo base_url(); ?>res/js/custom.js"></script>
	<!--<![endif]-->

</head>
<body>

<header id="header" class="container clearfix">

	<a href="<?php echo site_url(); ?>" id="logo">
		<img src="<?php echo base_url(); ?>res/img/logo.png" alt="SmartStart">
	</a>

	<nav id="main-nav">

		<ul>
			<li<?php if ($page == 'home') echo ' class="current"'; ?>>
				<a href="<?php echo site_url(); ?>" data-description="stațiuni, obiective turistice">Caută</a>
			</li>
			<li<?php if ($page == 'place') echo ' class="current"'; ?>>
				<a data-description="informații, harți, vreme, poze">Descoperă</a>
			</li>
		</ul>

	</nav><!-- end #main-nav -->

</header><!-- end #header -->