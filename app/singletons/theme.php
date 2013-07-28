<?php

/**
 * Theme Singleton
 */

class particle_theme_singleton
{
	/*** private static variables ***/
	private static $_debug 		= true; // toggle debug information
	private static $_instance 	= null; // stores class instance


	/**
	 * get_instance
	 *
	 * Retrieves an instance of this class or creates a new one if it doesn't exist
	 */
	static function get_instance() {

		if( null === self::$_instance )
			self::$_instance = new self();

		return self::$_instance;
	} 


	/**
	 * __construct
	 */
	private function __construct()
	{
		$this->_reg_theme_support();

		// set the template to maintenance if necessary
		add_action( 'init', array( &$this, 'maintenance_mode' ) );

		// add css files
		add_action( 'init', array( &$this, '_reg_css' ) );
		add_filter( 'nu_load_css', array( &$this, '_load_css' )  );

		// add js files
		add_action( 'init', array( &$this, '_reg_js' ) );
		add_filter( 'nu_load_js', array( &$this, '_load_js' )  );

		// load the template vars
		add_filter( 'template_include', array( &$this, 'load_controller' ), 20 );	
		add_filter( 'particle_template_include', array( &$this, 'load_loop_controller' ) );	
	}


	/**
	 * reg_theme_support
	 *
	 * Registers support for wordpress theme functions
	 */
	private function _reg_theme_support()
	{
		// Add admin_bar theme support ( not sure this is necessary)
		// To remove the 28px bump pass array( 'callback' => '__return_false') as the second var
		add_theme_support( 'admin-bar' );

		// Add custom menu support
		add_theme_support('menus');

		// Add thumbnail support
		add_theme_support('post-thumbnails');
	}


	public function maintenance_mode()
	{
		if( particle_get_advanced_option( 'maintenance' ) 
			&& !is_user_logged_in() 
			&& !current_user_can('edit_others_posts') ) {

        	add_filter( 'template_include', array( &$this, 'set_maintenance_template' ) );
        }
	}


	public function set_maintenance_template( $template )
	{
		$template = locate_template( 'maintenance.php' );
    	return $template;
	}


	/**
	 * Register Stylesheets
	 *
	 * Although not necessary, it is good to register all theme
	 * stylesheets here so that it is easy to see what styles have 
	 * and have not been registered. This only registers the 
	 * stylesheets. Styles still need to be enqueued.
	 */
	public function _reg_css()
	{ 
		$bn 	= PARTICLE_THEME;
		$dir 	= PARTICLE_ASSETS_URL . 'css/';

		nu_lazy_load_css::reg_css( $dir . 'bootstrap.css', null, '0.1', 'screen', $bn );
		nu_lazy_load_css::reg_css( $dir . 'responsive.css', null, '0.1', 'screen', $bn );
		nu_lazy_load_css::reg_css( $dir . 'screen.css', null, '0.1', 'screen', $bn );
	}


	/**
	 * load_css
	 *
	 * Use this to load sitewide styles
	 */
	public function _load_css( $styles )
	{
		$bn = PARTICLE_THEME;

		$styles['sitewide'][] = $bn . '-bootstrap';
		$styles['sitewide'][] = $bn . '-responsive';
		$styles['sitewide'][] = $bn . '-screen';

		return $styles;
	}


	/**
	 * Register Scripts
	 *
	 * Although not necessary, it is good to register all theme 
	 * javascript here so that it is easy to see what scripts have 
	 * and have not been registered. This only registers the 
	 * script(s). Scripts should be enqueued through the functions.
	 * php or the page controllers using the 'nu_load_js' hook 
	 * because this allows us to lazy load scripts.
	 */
	public function _reg_js()
	{ 
		$bn = PARTICLE_THEME;
		$dir = PARTICLE_ASSETS_URL . 'js/';

		// Register libraries
		nu_reg_js( $dir . 'modernizr.min.js', null, '2.6.2', false );
		nu_reg_js( $dir . 'underscore.min.js', null, '0.1', true );
		nu_reg_js( $dir . 'backbone.min.js', array( 'underscore' ), '0.1', true );

		// Register bootstrap files
		$this->_reg_bootstrap_js();

		// Register plugins
		$p_dir = $dir . 'plugins/';
		nu_reg_js( $p_dir . 'jquery.fitvids.js', array('jquery'), '0.1', true );
		
		
	}


	private function _reg_bootstrap_js()
	{
		$dir 	= PARTICLE_ASSETS_URL . 'js/';

		// uncomment for production
		// nu_reg_js( $dir . 'bootstrap.min.js', array( 'jquery' ), '0.1', true );

		// comment for production
		$bs_dir = PARTICLE_ASSETS_URL . 'js/bootstrap/';
		$bs_dep = array( 'jquery' );

		nu_reg_js( $bs_dir . 'bootstrap-affix.js', $bs_dep );
		nu_reg_js( $bs_dir . 'bootstrap-alert.js', $bs_dep );
		nu_reg_js( $bs_dir . 'bootstrap-button.js', $bs_dep );
		nu_reg_js( $bs_dir . 'bootstrap-carousel.js', $bs_dep );
		nu_reg_js( $bs_dir . 'bootstrap-collapse.js', $bs_dep );
		nu_reg_js( $bs_dir . 'bootstrap-dropdown.js', $bs_dep );
		nu_reg_js( $bs_dir . 'bootstrap-modal.js', $bs_dep );
		nu_reg_js( $bs_dir . 'bootstrap-popover.js', array( 'bootstrap_tooltip' ) );
		nu_reg_js( $bs_dir . 'bootstrap-scrollspy.js', $bs_dep );
		nu_reg_js( $bs_dir . 'bootstrap-tab.js', $bs_dep );
		nu_reg_js( $bs_dir . 'bootstrap-tooltip.js', $bs_dep );
		nu_reg_js( $bs_dir . 'bootstrap-transition.js', $bs_dep );
		nu_reg_js( $bs_dir . 'bootstrap-typeahead.js', $bs_dep );
	}


	/**
	 * load_js
	 *
	 * Use this to load sitewide javascript
	 */
	public function _load_js( $scripts )
	{
		$bn = PARTICLE_THEME;

		// frontend
		$scripts['sitewide'][] = 'modernizr';
		$scripts['sitewide'][] = 'backbone';
		$scripts['sitewide'][] = 'jquery_fitvids';

		// uncomment for production
		// $scripts['sitewide'][] = 'bootstrap';

		// comment for production
		$scripts['sitewide'][] = 'bootstrap_affix';
		$scripts['sitewide'][] = 'bootstrap_alert';
		$scripts['sitewide'][] = 'bootstrap_button';
		$scripts['sitewide'][] = 'bootstrap_carousel';
		$scripts['sitewide'][] = 'bootstrap_collapse';
		$scripts['sitewide'][] = 'bootstrap_dropdown';
		$scripts['sitewide'][] = 'bootstrap_modal';
		$scripts['sitewide'][] = 'bootstrap_popover';
		$scripts['sitewide'][] = 'bootstrap_scrollspy';
		$scripts['sitewide'][] = 'bootstrap_tab';
		$scripts['sitewide'][] = 'bootstrap_tooltip';
		$scripts['sitewide'][] = 'bootstrap_transition';
		$scripts['sitewide'][] = 'bootstrap_typeahead';

		return $scripts;
	}


	/**
	 Setup Template Variables
	 */

	/**
	 * Initialize the template vars for the given template. This uses the 
	 * template_include hook but does not alter the return value, rather it
	 * uses the $template name to create an instance of the class that generates
	 * the template variables for this template
	 *
	 * @uses template_include hook
	 */
	public function load_controller( $template )
	{
		$fpath = $this->_locate_controller( $template );
		
		// initialize the class
		if( $fpath ) 
			$this->_init_template_class( $fpath, $template );

		// return the template path unaltered
		return $template;
	}


	/**
	 * Locate the correct controller
	 *
	 * Controller Hierarchy. First condition that matches will be returned:
	 *
	 * 	1. 	Controller file that matches the template being loaded (child theme > parent theme)
	 *
	 *	2.	Controller file that matches the post type (child theme > parent theme)
	 *
	 *	3. 	index.php controller file (child theme > parent theme)
	 */
	private function _locate_controller( $template ) 
	{
		$located = ''; // controller filename to return

		// make sure template has the filetype attached
		if( !strpos( $template, '.php') )
			$template .= '.php';

		// clean the template path
		$start 		= strrpos( $template, '/' ) ? strrpos( $template, '/' ) + 1 : 0;
  		$template 	= nu_utils::slice_string( $template, $start );

		/*** matching template file **/

		if ( is_child_theme() )
			$fnames[] = THEME_CONTROLLERS_DIR . $template;
		$fnames[] = PARTICLE_CONTROLLERS_DIR . $template;


		/*** matching post type ***/
		if( $post_type = get_post_type() ) {
			if( is_child_theme() )
				$fnames[] = THEME_CONTROLLERS_DIR . $post_type . '.php';
			$fnames[] = PARTICLE_CONTROLLERS_DIR . $post_type . '.php';
		}


		/*** index.php ***/
		$fnames[] = THEME_CONTROLLERS_DIR . 'index.php';
		$fnames[] = PARTICLE_CONTROLLERS_DIR . 'index.php';


		/*** locate the file ***/
		foreach( $fnames as $fn ) {
			if( is_readable( $fn ) ) {
				$located = $fn;
				break;
			}
		}

		// should never happen... but just in case
		if( !$located ) 
			echo 'Could not locate a controller';

		return $located;
	}


	/**
	 * Initialize the looop template vars for the given template. This uses the 
	 * particle_template_include hook but does not alter the return value, rather it
	 * uses the $template name to create an instance of the class that generates
	 * the template variables for this template
	 *
	 * @uses template_include hook
	 */
	public function load_loop_controller( $template )
	{
		$fpath = $this->_locate_loop_controller( $template );

		// initialize the class
		if( $fpath ) 
			$this->_init_template_class( $fpath, $template );

		return $template;
	}


	private function _locate_loop_controller( $template )
	{
		$located = ''; // controller filename to return

		// stop if the template being included does not contain 'loop'
		if( false === strpos( $template, 'loop' ) )
			return '';

		// make sure template has the filetype attached
		if( !strpos( $template, '.php') )
			$template .= '.php';

		// clean the template path
		$start 		= strrpos( $template, '/' ) ? strrpos( $template, '/' ) + 1 : 0;
  	$template 	= nu_utils::slice_string( $template, $start );

		/*** matching template file **/
		if ( is_child_theme() )
			$fnames[] = THEME_CONTROLLERS_DIR . $template;
		$fnames[] = PARTICLE_CONTROLLERS_DIR . $template;


		/*** matching post type ***/
		if( $post_type = get_post_type() ) {
			if( is_child_theme() )
				$fnames[] = THEME_CONTROLLERS_DIR . 'loop-' . $post_type . '.php';
			$fnames[] = PARTICLE_CONTROLLERS_DIR . 'loop-' . $post_type . '.php';
		}


		/*** loop-post.php ***/
		$fnames[] = THEME_CONTROLLERS_DIR . 'loop-post.php';
		$fnames[] = PARTICLE_CONTROLLERS_DIR . 'loop-post.php';


		/*** locate the file ***/
		foreach( $fnames as $fn ) {
			if( is_readable( $fn ) ) {
				$located = $fn;
				break;
			}
		}

		// should never happen... but just in case
		if( !$located ) 
			echo 'Could not locate a loop controller';

		return $located;
	}


	/**
	 * Initialize a controller class based on the template path
	 *
	 * @param $fpath (string) the filepath of the controller to load
	 * @param $template (string) the template that the controller is being loaded for
	 */
	private function _init_template_class( $fpath, $template )
	{
		// extract the name from the controller file path
		$start 	= strrpos( $fpath, '/' ) ? strrpos( $fpath, '/' ) + 1 : 0;
		$name 	= nu_utils::slice_string( $fpath, $start );
		$name 	= str_replace( '.php', '', $name );
		$name 	= str_replace( '-', '_', $name );

		// use the filename to create the classname...
		// the classname will be different depending on whether or not the controller is in
		// the child or the parent theme
		if( false !== strpos( $fpath, THEME_CONTROLLERS_DIR ) ) {
			$classname	= THEME_NAME . '_' . $name . '_controller';
		}
		elseif( false !== strpos( $fpath, PARTICLE_CONTROLLERS_DIR ) ) {
			$classname	= PARTICLE_THEME . '_' . $name . '_controller';
		}

		if( self::$_debug )
			nu_debug( "Controller Loaded for $template", $classname );

		// instantiate the class
		if( $classname )
			new $classname;
	}
}