<?php

/**
 * Widget Singleton Class
 */

class particle_widget_singleton
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
		// initialize the widgets
		add_action( 'widgets_init', array( &$this, 'init_widgets'), 10 );

  	// unregister wordpress widgets that are being replaced
  	add_action( 'widgets_init', array( &$this, 'unregister_widgets' ), 11 );
	}


	public function init_widgets()
	{
		$widgets = $this->_get_widgets();

		foreach( $widgets as $name=>$widget_file ) {
			require_once( $widget_file );
			register_widget( $name );
		}
	}


	private function _get_widgets()
	{
		// array mapping widget class name to the widget file
		$widgets = array();
		$widgets = apply_filters( 'particle_get_widgets', $widgets );
		return $widgets;
	}


	public function unregister_widgets()
	{
		$unregister = array();
		$widgets = apply_filters( 'particle_unregister_widgets', $unregister );

		foreach( $unregister as $name ) {
			unregister_widget( $name );
		}
	}
}