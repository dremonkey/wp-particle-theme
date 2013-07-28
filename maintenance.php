<?php

// IMPORTANT - this theme requires the naked-utils plugin
// -----------------------------------------------------------------
if( !defined ( 'NAKED_UTILS' ) ) { 
	particle_show_notice();
	die; 
}

?>


<!DOCTYPE html>

<!--[if lt IE 7]><html class="lt-ie9 lt-ie8 lt-ie7"><![endif]-->
<!--[if IE 7]><html class="lt-ie9 lt-ie8"><![endif]-->
<!--[if IE 8]><html class="lt-ie9"><![endif]-->
<!--[if gt IE 8]><!--> 

<html <?php language_attributes(); ?>>

<head>

	<meta charset="<?php bloginfo( 'charset' ); ?>" />

	<meta name="viewport" content="width=device-width" />

	<meta http-equiv="X-UA-Compatible" content="IE=Edge,chrome=1">

	<!-- Stop Browser Caching -->
	<meta http-equiv="Cache-Control" content="no-cache, no-store, must-revalidate" />
	
	<meta http-equiv="Pragma" content="no-cache" />
	
	<meta http-equiv="Expires" content="0" />
	<!-- End stop browser caching -->

	<title>Under Maintenance</title>

	<link rel="profile" href="http://gmpg.org/xfn/11" />

	<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>" />

	<?php wp_head(); ?>

</head>

<body id="maintenance">

    <!--[if lt IE 7]>
        <p class="chromeframe">You are using an <strong>outdated</strong> browser. Please <a href="http://browsehappy.com/">upgrade your browser</a> or <a href="http://www.google.com/chromeframe/?redirect=true">activate Google Chrome Frame</a> to improve your experience.</p>
    <![endif]-->

    <div id="realm">

		<div id="realm-content">

			<div class="container">
				<div class="row">
					<div class="span8">
						<p class="msg"><?php 
							_get_page_var('msg');
						?></p>
					</div>
				</div>
			</div><!-- .container -->

			<div class="lines">
				<div class="container">
					<h1 id="maintenance-title"><?php 
						_get_page_var('page-title'); 
					?></h1>
				</div><!-- .container -->
			</div>

	    </div><!-- #realm-content -->

	    <div class="overlay"></div>
	
	</div><!-- #realm -->

</body>

</html>