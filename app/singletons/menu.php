<?php

/**
 * Menu Singleton Class
 */

class particle_menu_singleton
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
		add_action( 'init', array( &$this, 'setup_menus' ) );
	}


	public function setup_menus()
	{
		// register the menu location(s)
		register_nav_menus( $this->_get_menu_locations() );

		// add default items to the menus
		$this->_add_default_menus();
	}


	private function _get_menu_locations()
	{
		$locations = array(
			'main' 		=> __( 'Primary Navigation', 'particle' ),
			'footer'	=> __( 'Footer Navigation', 'particle' ),
		);

		$locations = apply_filters( 'particle_menu_locations', $locations );

		return $locations;
	}


	/**
	 * _add_default_menus
	 *
	 * Used to automatically create and assign menu items (if nothing exists)... 
	 * basically used to make launching a new site easier.
	 *
	 * Menu items are just taxonomy terms so to add menu items we use wp_insert_term
	 */
	private function _add_default_menus()
	{	
		$menu_terms = array(

			// main navigation
			'main' => array(
				'term' => __( 'Main Navigation', 'particle' ),
				'taxonomy' => 'nav_menu',
				'args' => array( 
					'description' 	=> __( 'The primary navigation menu', 'particle' ),
					'slug' 			=> 'main-navigation',
					'parent' 		=> 0
				)
			),

			// footer navigation
			'footer' => array(
				'term' => __( 'Footer Navigation', 'particle' ),
				'taxonomy' => 'nav_menu',
				'args' => array( 
					'description' 	=> __( 'The footer navigation menu', 'particle' ),
					'slug' 			=> 'footer-navigation',
					'parent' 		=> 0
				)
			),
		);

		$menu_terms = apply_filters( 'particle_default_menu_terms', $menu_terms );

		// boolean indicating whether or not the theme_mod option should be updated
		$update_theme_mod = false;

		// check to see if each of the menu items have been created
		foreach( $menu_terms as $name=>$menu ) {
			extract( $menu );

			if( !term_exists( $term, $taxonomy ) ) {
				$update_theme_mod = true;
				$ids = wp_insert_term( $term, $taxonomy, $args );

				// set value to be saved to the theme_mod option later
				$value[ $name ] = $ids['term_id'];
			}
		}

		if( $update_theme_mod )
			set_theme_mod( 'nav_menu_locations', $value );		
	}
}