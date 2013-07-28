<?php
/**
 * Loop Post Controller
 *
 * The loop-{name} controller loads within the loop. If a more specific controller does not match
 * for the loop template being loaded then by default this controller willl load
 */


class particle_loop_post_controller
{
	/*** private static variables ***/
	private static $_debug = true; // toggle debug information


	public function __construct()
	{
		// nothing here
	}


	/**
	 * Retrieves the post excerpt.
	 */
	public function get_excerpt( $args=array() )
	{
		$defaults = array(
			'id' 		=> null,
			'length'	=> 400,
			'echo'		=> false,
		);
		$args = wp_parse_args( $args, $defaults );
		extract( $args );

		// if id is not set, get the id from the global post object
		if( !isset( $id ) ) { 
			global $post;
			$id = $post->ID; 
		}

		// check to see if the excerpt has already been constructed
		$key = "excerpt_{$id}_{$length}";
		if( $excerpt = _get_loop_var( $key ) )
			return $excerpt;
			
		$post = get_post( $id );
		$excerpt = $post->post_excerpt;

		if( empty( $excerpt ) ) {

			// Create the excerpt using the post content. 
			// Code copied from wp_trim_excerpt in wp-admin/formatting.php
			$excerpt = $post->post_content;
			$excerpt = strip_shortcodes( $excerpt );
			$excerpt = apply_filters('the_content', $excerpt);
			$excerpt = str_replace(']]>', ']]&gt;', $excerpt);
			$excerpt = strip_tags($excerpt);
			$excerpt = str_replace( '&nbsp;', ' ', $excerpt );
			$excerpt = trim( $excerpt );
		}
		
		$excerpt_more 	= apply_filters( 'excerpt_more', '...' );

		if ( is_search() ) {
			$s = get_search_query();
			$excerpt = str_ireplace( $s, "<span class='query'>$s</span>", $excerpt );			
		}

		// trim the excerpt to the desired size
		$excerpt = nu_utils::substr_utf8( $excerpt, $length, $excerpt_more );				

		// save the excerpt
		_set_loop_var( $key, $excerpt );

		if( $echo ) echo $excerpt;
		else return $excerpt;
	}
}