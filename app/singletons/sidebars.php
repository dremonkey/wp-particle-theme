<?php

/**
 * Sidebar Singleton
 */

class particle_sidebars_singleton
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
		// register the sidebars
		add_action( 'init', array( &$this, 'register_sidebars' ) );
	}


	/**
	 * register_sidebars
	 *
	 * Registers sidebars
	 */
	public function register_sidebars()
	{
		$sidebars = array(
			'Default',
		);

		$sidebars = apply_filters( 'particle_sidebars' , $sidebars );

		$args = array(
			'before_widget' => '<div id="%1$s" class="widget %2$s">
								<div class="inner">',
			'after_widget' 	=> '</div></div>',
			'before_title' 	=> '<h3 class="widget-title">',
			'after_title' 	=> '</h3>'
		);

		foreach( $sidebars as $sidebar ) {
			$args['name'] = $sidebar;
			register_sidebar( $args );
		}
	}
}