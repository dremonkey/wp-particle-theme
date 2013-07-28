<?php
/**
 * The Header
 *
 * #realm-center and #realm are both closed in the footer
 */

// IMPORTANT - this theme requires the naked-utils plugin
// -----------------------------------------------------------------
if( !defined ( 'NAKED_UTILS' ) ) { 
	particle_show_notice();
	die; 
}

?>

<!DOCTYPE html>
<html <?php language_attributes(); ?> class="wf-loading">

<head>

	<meta charset="<?php bloginfo( 'charset' ); ?>" />

	<meta name="viewport" content="width=device-width" />

	<meta http-equiv="X-UA-Compatible" content="IE=Edge,chrome=1">

	<title><?php echo trim( wp_title( '', false ) ); ?></title>

	<link rel="profile" href="http://gmpg.org/xfn/11" />

	<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>" />

	<?php wp_head(); ?>

</head>

<body <?php _get_page_var( 'body_attrs' ); ?> <?php body_class(); ?>>

<!-- Prompt IE 6 users to install Chrome Frame. 
chromium.org/developers/how-tos/chrome-frame-getting-started -->

<!--[if lt IE 7]>
	<p class=chromeframe>Your browser is <em>ancient!</em> <a href="http://browsehappy.com/">Upgrade to a different browser</a> or <a href="http://www.google.com/chromeframe/?redirect=true">install Google Chrome Frame</a> to experience this site.</p>
<![endif]-->

<div id="realm">

<?php _include( 'header' ); ?>

<div id="realm-content">