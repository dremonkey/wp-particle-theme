<?php
/**
 * Single Index Controller
 *
 * If a more specific controller does not match for the template being loaded
 * then by default this controller willl load
 */


class particle_index_controller
{
	/*** private static variables ***/
	private static $_debug = true; // toggle debug information

	public function __construct()
	{
		// do only if not on the admin page
		if( !is_admin() ) {
			_set_page_var( 'main_menu', $this->get_main_menu() );
			_set_page_var( 'footer_menu', $this->get_footer_menu() );
		}
	}


	/**
   * Prepares the main menu
   */
	public function get_main_menu()
	{
		$args = array(
			'theme_location' => 'main',
			'echo' => 0,
			'container' => false,
			'menu_id' => 'main-nav-menu', 
			'menu_class' => 'nav',
			'walker' => new particle_main_menu_walker
		);

		return wp_nav_menu( $args );
	}


	/**
  	 * Prepares the footer menu
   	 */
	public function get_footer_menu()
	{
		$args = array(
			'theme_location' => 'footer',
			'echo' => 0,
			'container' => false,
			'menu_id' => 'footer-nav-menu',
			'walker' => new particle_footer_menu_walker
		);

		return wp_nav_menu( $args );
	}
}


/**
 * Create HTML list of nav menu items.
 *
 * @see http://wordpress.stackexchange.com/q/14037/
 */
class particle_main_menu_walker extends Walker_Nav_Menu
{
  /**
   * Start the element output.
   *
   * @param  string $output Passed by reference. Used to append additional content.
   * @param  object $item   Menu item data object.
   * @param  int $depth     Depth of menu item. May be used for padding.
   * @param  array $args    Additional strings.
   * @return void
   */
  function start_el( &$output, $item, $depth, $args ) 
  {
  	// make sure all menu urls have a trailing slash
  	$item->url = trailingslashit( $item->url );
  	if ('/' !== $item->url && false !== strpos(nu_utils::self_uri(), $item->url)) {
  		if( !in_array('current-menu-item', $item->classes))
  			$item->classes[] = 'current-menu-item';
  	}
    parent::start_el( $output, $item, $depth, $args );
	}
}


class particle_footer_menu_walker extends Walker_Nav_Menu
{
	/**
     * Start the element output.
     *
     * @param  string $output Passed by reference. Used to append additional content.
     * @param  object $item   Menu item data object.
     * @param  int $depth     Depth of menu item. May be used for padding.
     * @param  array $args    Additional strings.
     * @return void
     */
    function start_el( &$output, $item, $depth, $args ) 
    {
    	// make sure all menu urls have a trailing slash unless the url has a hash (#)
  		if( false === strrpos($item->url, '/#') )
     		$item->url = trailingslashit( $item->url );

      parent::start_el( $output, $item, $depth, $args );
	}
}