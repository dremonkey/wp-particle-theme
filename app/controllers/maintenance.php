<?php
/**
 * Maintenance Page Controller
 *
 * @todo make a new page and use the page title and the post content from that
 * page to fill in the content on the maintenance page
 */


class particle_maintenance_controller
{
	/*** private static variables ***/
	private static $_debug = true; // toggle debug information

	public function __construct()
	{
		_set_page_var( 'msg', $this->get_msg() );
		_set_page_var( 'page-title', $this->get_page_title() );
	}


	public function get_msg()
	{
		$text = "We are currently doing some work on the site. We apologize for the inconvenience and thank you for your patience.";

		return $text;
	}


	public function get_page_title()
	{
		$text = "Site Under Maintenance";
		return $text;
	}
}