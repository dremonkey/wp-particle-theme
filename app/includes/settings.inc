<?php 
/**
 * The template for the admin theme settings (options) page
 */
?>

<div class="wrap">
    <?php $current_tab = isset( $_GET['tab'] ) ? $_GET['tab'] : $default_tab; ?>

    <?php screen_icon(); ?>
   
   	<h2><?php echo __( 'Theme Options' , 'particle' ) ?></h2>

   	<h2 class="subsubsub nav-tab-wrapper" style="margin: 20px 0 30px;">
	    <?php foreach ( $tabs as $tab_slug => $tab_title ) : ?>
	        
	        <?php $active = $current_tab == $tab_slug ? 'nav-tab-active' : ''; ?>
	        <?php $link = '?page=' . $page_slug . '&tab=' . $tab_slug; ?>
	        
	        <a class="nav-tab <?php echo $active ?>" href="<?php echo $link ?>">
	        	<?php echo $tab_title ?>
	        </a>

	    <?php endforeach; ?>
    </h2>

    <form method="post" action="options.php" style="clear:both">
        <?php settings_fields( $option_keys[$current_tab] ); ?>
        <?php do_settings_sections( $current_tab ); ?>
        <?php submit_button(); ?>
    </form>
</div>