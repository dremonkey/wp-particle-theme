<?php
/**
 * Header Include
 *
 * The part of the header that can be seen
 */
?>

<header id="site-header">
	<nav id="access" class="navbar navbar-static-top" role="navigation">
		
		<a class="assistive-text" href="#realm-content" title="<?php esc_attr_e( 'Skip to content', 'toolbox' ); ?>">
			<?php _e( 'Skip to content', 'toolbox' ); ?>
		</a>

		<div class="navbar-inner">
			<div class="container">
				<?php _get_page_var( 'main_menu' ); ?>
			</div>
		</div>
	</nav>
</header><!-- #site-header -->