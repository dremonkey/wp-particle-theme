<?php

/**
 * Settings Singleton
 */

class particle_settings_singleton
{
	/*** private static variables ***/
	private static $_debug 							= true; // toggle debug information
	private static $_instance 					= null; // stores class instance	
	private static $options_page_slug 	= 'theme-options';
	
	// Homepage Options
	private static $options_key_general 	= 'particle_theme_options_general';
	public static $options_slug_general 	= 'general-settings';
	
	// Advanced Options
	private static $options_key_advanced 	= 'particle_theme_options_advanced';
	public static $options_slug_advanced 	= 'advanced-settings';


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
	

	private function __construct()
	{
		add_action( 'admin_menu', array( &$this, 'add_options_menu' ) );
		add_action( 'admin_init', array( &$this, 'reg_general_settings' ) );
		add_action( 'admin_init', array( &$this, 'reg_advanced_settings' ) );
	}


	public function add_options_menu()
	{
		$page_title = 'Theme Options';
		$menu_title = 'Theme Options';
		$capability = 'edit_theme_options';
		$menu_slug 	= self::$options_page_slug;
		$callback 	= array( &$this, 'get_options_page' );

  		add_theme_page( $page_title, $menu_title, $capability, $menu_slug, $callback );
	}


	/**
	 *
	 * @uses _include
	 */
	public function get_options_page() 
	{
		// set up page vars
		$page_slug 		= self::$options_page_slug;
		$tabs 		 	= $this->_get_tabs();
		$default_tab 	= self::$options_slug_general;
		$option_keys  	= $this->_get_option_keys();

    	// get the view
		$tpl_path = PARTICLE_INCLUDES_DIR . 'settings.inc';
		require_once( $tpl_path );
	}


	/**
	 Register Settings
	 */


	public function reg_general_settings()
	{ 
		// set up some variables
		$page = self::$options_slug_general;

		// register the settings
    	register_setting( self::$options_key_general, self::$options_key_general );

    	$sections = array(
    		'home' => array(
	    		'title' 	=> __( 'Home', 'particle' ),
	    		'callback' 	=> array( &$this, 'get_section_desc' ),
	    		'page'		=> $page,
	    	),
    	);

    	// use this to add settings sections from child themes
	    $sections = apply_filters( 'particle_setting_sections_general', $sections, $page );


	    foreach( $sections as $id=>$section ) {
	    	extract( $section );
	    	add_settings_section( $id, $title, $callback, $page );	
	    }


    	$fields = array(
    		'home_post_count' => array(
	    		'title' 	=> __( 'Homepage Post Count', 'particle' ),
	    		'callback' 	=> array( &$this, 'build_form_fields_general' ),
	    		'page' 		=> $page,
	    		'section' 	=> 'home',
	    		'args' 	=> array(
	    			'id' 	=> 'home_post_count',
	    			'type' 	=> 'text-input',
	    			'desc' 	=> __( 'The number of posts that you would like to show on the homepage', 'particle' ),
	    		)	
	    	),
    	);


    	 // use this to add settings fields from child themes
		$fields = apply_filters( 'particle_setting_fields_general', $fields, $page );

	    foreach( $fields as $id=>$field ) {
			extract( $field );
    		add_settings_field( $id, $title, $callback, $page, $section, $args );
    	}
	}


	public function reg_advanced_settings()
	{
		// Register the settings. All settings will be stored in one options field as an array.
    	register_setting( self::$options_key_advanced, self::$options_key_advanced );

    	$sections = array(
    		'dev' => array(
	    		'title' 	=> __( 'Development', 'particle' ),
	    		'callback' 	=> array( &$this, 'get_section_desc' ),
	    		'page'		=> self::$options_slug_advanced,
	    	),
    	);

    	// use this to add settings sections from child themes
	    $sections = apply_filters( 'particle_setting_sections_advanced', $sections );


	    foreach( $sections as $id=>$section ) {
	    	extract( $section );
	    	add_settings_section( $id, $title, $callback, $page );	
	    }


	    $fields = array(
	    	'restrict_access' => array(
	    		'title' 	=> __( 'Restrict Access', 'particle' ),
	    		'callback' 	=> array( &$this, 'build_form_fields_advanced' ),
	    		'page' 		=> self::$options_slug_advanced,
	    		'section' 	=> 'dev',
	    		'args' => array(
	    			'id' 	=> 'restrict_access',
	    			'type' 	=> 'checkbox',
	    			'desc' 	=> __( 'Check to restrict access to the site so that all users must login before they can view it.', 'particle' ),
	    		)	
	    	),
	    	'maintenance' => array(
	    		'title' 	=> __( 'Maintenance Mode', 'particle' ),
	    		'callback' 	=> array( &$this, 'build_form_fields_advanced' ),
	    		'page' 		=> self::$options_slug_advanced,
	    		'section' 	=> 'dev',
	    		'args' => array(
	    			'id' 	=> 'maintenance',
	    			'type' 	=> 'checkbox',
	    			'desc' 	=> __( 'Check to redirect all non-logged-in users to the site maintenance page. Only users with a user level of "editor" or above will be able to log in. This will take precedence over the "restrict_access" option in the event that both options are checked.', 'particle' ),
	    		)	
	    	),
	    );


	    // use this to add settings fields from child themes
		$fields = apply_filters( 'particle_setting_fields_advanced', $fields );

	    foreach( $fields as $id=>$field ) {
			extract( $field );
    		add_settings_field( $id, $title, $callback, $page, $section, $args );
    	}
	}


	/**
	 Helpers
	 */


	/**
	 * The tabs
	 */
	private function _get_tabs()
	{
		$tabs = array( 
			self::$options_slug_general 	=> 'General',
			self::$options_slug_advanced 	=> 'Advanced',
		);

		$tabs = apply_filters( 'particle_theme_option_tabs', $tabs );
		return $tabs;
	}



	/**
	 * _get_option_keys
	 *
	 * Used when building the option page so that the correct option key is used for the currently
	 * active option page tab
	 *
	 * @return (array) map of tab slugs to option key
	 */
	private function _get_option_keys()
	{
		// map tab slugs to option key
		return array(
			self::$options_slug_general 	=> self::$options_key_general,
			self::$options_slug_advanced 	=> self::$options_key_advanced
		);
	}


	public function get_section_desc( $args )
	{
		$description = '';

		switch ( $args['id'] ) {
			case 'home':
				$description = __( 'These settings will change the way that the homepage displays content.', 'particle' );
				break;
			
			case 'dev':
				$description = __( 'The following settings should probably only be used during development.', 'particle' );
				break;
		}

		echo '<p class="description" style="margin-bottom: 20px;">' . $description . '</p>';
	}


	/**
	 * Build the home options form fields
	 */
	public function build_form_fields_general( $args ) 
	{
		extract( $args );

		// prepare the template variables
		$name 					= self::$options_key_general . "[$id]";
		$theme_options 	= self::get_options();
		$value 					= esc_attr( $theme_options[$id] );

		// include the field template
		$file = $type . '.inc';
		$tpl_path = NU_VIEWS_DIR . 'setting-fields/' . $file;
		include( $tpl_path );
	}


	/**
	 * Build the advanced options form fields
	 */
	public function build_form_fields_advanced( $args ) 
	{
		extract( $args );

		// prepare the template variables
		$name 					= self::$options_key_advanced . "[$id]";
		$theme_options 	= self::get_advanced_options();
		$value 					= esc_attr( $theme_options[$id] );

		// include the field template
		$file = $type . '.inc';
		$tpl_path = NU_VIEWS_DIR . 'setting-fields/' . $file;
		include( $tpl_path );
	}


	/**
	 * Returns an array of all theme options. If an option has been previously set, the 
	 * stored option value will be return. If not, then the default option value will be 
	 * returned.
	 *
	 * @uses get_option()
	 */
	public static function get_options()
	{
		$options = (array) get_option( self::$options_key_general );

		$defaults = array(
			'home_post_count' => 10,
		);

		$defaults = apply_filters( 'particle_setting_default_options', $defaults );

		// Merge with defaults
	    $options = array_merge( $defaults, $options );

	    return $options;
	}


	/**
	 * Returns an array of all theme options. If an option has been previously set, the 
	 * stored option value will be return. If not, then the default option value will be 
	 * returned.
	 *
	 * @uses get_option()
	 */
	public static function get_advanced_options()
	{
		$options = (array) get_option( self::$options_key_advanced );

		$defaults = array(
			'restrict_access' 	=> 0,
			'maintenance'		=> 0,
		);

		$defaults = apply_filters( 'particle_setting_default_options_advanced', $defaults );

		// Merge with defaults
	    $options = array_merge( $defaults, $options );

	    return $options;
	}


	/**
	 * get_option_value
	 *
	 * Retrieves a single option value from the 'general' theme options.
	 *
	 * @return (mixed)
	 */
	public static function get_option_value( $option )
	{
		$options = self::get_options();
		$value = isset( $options[ $option ] ) ? $options[ $option ] : null;
		return $value;
	}


	/**
	 * get_advanced_option_value
	 *
	 * Retrieves a single option value from the 'advanced' theme options.
	 *
	 * @return (mixed)
	 */
	public static function get_advanced_option_value( $option )
	{
		$options = self::get_advanced_options();
		$value = isset( $options[ $option ] ) ? $options[ $option ] : null;
		return $value;
	}


	/**
	 * update_option
	 *
	 * @param $option (string) the general option to be updated
	 * @param $val (mixed) the new option value
	 *
	 * @return (bool) false if the update failed.
	 */
	public static function update_option( $option, $val )
	{
		$options = self::get_options();
		$options[ $option ] = $val;
		return update_option( self::$options_key_general, $options );
	}


	/**
	 * update_advanced_option
	 *
	 * @param $option (string) the advanced option to be updated
	 * @param $val (mixed) the new option value
	 *
	 * @return (bool) False if the update failed.
	 */
	public static function update_advanced_option( $option, $val )
	{
		$options = self::get_advanced_options();
		$options[ $option ] = $val;
		return update_option( self::$options_key_advanced, $options );
	}
}


/**
 Template Tags
 */

function particle_get_option( $option )
{
	return particle_settings_singleton::get_option_value( $option );
}


function particle_get_advanced_option( $option )
{
	return particle_settings_singleton::get_advanced_option_value( $option );
}


function particle_update_option( $option, $val )
{
	return particle_settings_singleton::update_option( $option, $val );
}


function particle_update_advanced_option( $option, $val )
{
	return particle_settings_singleton::update_advanced_option( $option, $val );
}