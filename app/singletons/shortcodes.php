<?php

/**
 * Adds shortcodes
 */

class particle_shortcodes_singleton
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
		add_shortcode( 'button', array( &$this, 'button_handler' ) );
	}


	public function button_handler( $atts, $content = null )
	{
		$content = !$content ? 'Click Here' : $content;

		$defaults = array(
			'color' => 'blue',
			'size'	=> 'medium',
			'href'	=> '/',
			'title'	=> 'Click Here' 
		);

		extract( shortcode_atts( $defaults, $atts ) );

		$classes = "btn btn-$color btn-$size btn-$size-has-icon"; 
		$icon 	 = "<span><b class='icon'></b></span>";

		return '<a class="' . $classes . '" href="' . $href . '" title="' . $title . '">' . $content . $icon . '</a>';
	}
}