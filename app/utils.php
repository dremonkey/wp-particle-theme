<?php
/**
 * Theme Utility Functions and Template Tags
 */


/**
 * _get_singletons
 *
 * Convenience function to retrieve all php files within a directory and build the singleton
 * classname so that they can be instantiated without having to explicitly declare that the 
 * singleton needs to be instantiated.
 *
 * Assumes that the filenames in $dir look like 'fname.php'. All chacters in the filename will 
 * be mad lowercase and any dashes or whitespace characters in the filename will be converted 
 * to '_' when building the classnames.
 *
 * @param $dir (string) the directory to scan for files
 * @param $base (string) the base name which is used to build the singleton classname
 *
 * @return (array) an array of singleton classnames
 */
function _get_singletons( $dir, $base ) 
{
  // make sure base is lowercase
  $base = strtolower( $base );

  // make sure that the 'settings' and 'theme' singletons come first
  $fnames = array( 'settings', 'theme' );

  // get all the files in the singleton direcotry
  if( $handle = opendir( $dir ) ) {
    while ( false !== ( $entry = readdir( $handle ) ) ) {
      if( false !== strpos( $entry, '.php' ) ) {
        $fname = str_replace( '.php', '', $entry );

        // if not already added, add it
        if( !in_array( $fname, $fnames ) )
          $fnames[] = $fname;
      }
    }
    closedir( $handle );
  }

  // turn file names into class names
  foreach( $fnames as $fn ) {
    $fn = nu_utils::clean_string($fn, 50, '_');
    $singletons[] = $base . '_' . $fn . '_singleton';
  }

  return $singletons;
}


/**
 * _include
 *
 * Based on the wordpress get_template_part function
 *
 * Include the Post-Format-specific template for the content.
 *
 * If you want to overload this in a child theme then include a file
 * called single-___.php (where ___ is the Post Format name) at the same 
 * location within the child theme directory and that will be used instead.
 *
 * @uses locate_template()
 *
 * @param $name (str) The name of the template.
 * @param $format (str) The specific format of the template to retrieve.
 * @param $dir (str) The directory within the current theme folder.
 *
 * @return (str) The template filename if one is located, an empty string if not.
 */
function _include( $template, $format=false, $dir=false )
{
    // nu_debug( 'Particle Template Include', array( 'dir'=>$dir, 'format'=>$format ) );

    $template = apply_filters( 'particle_template_include', $template );

    // if not specificed set to the partials directory (relative to the theme top dir level)
    if( false === $dir )
        $dir = 'app/includes/';

    $templates = array();
    if( $format )
        $templates[] = trailingslashit( $dir ) . "$template-$format.inc";

    $templates[] = trailingslashit( $dir ) . "$template.inc";

    $located = locate_template( $templates, true, false );

    if( !$located ) {
        $msg = implode( ', ', $templates );
        echo "Failed to load the following template(s): $msg";
    }

    return $located;
}


/**
 * _include_sidebar
 *
 * Loads a sidebar template file
 * 
 * @uses _include()
 */
function _include_sidebar( $name )
{
    return _include( $name, '', 'app/includes/sidebars/' );
}


/**
 * _get_page_var
 *
 * Retrieve a page level (as opposed to loop) variable. A page level variable is considered
 * anything that can be set as soon as the page loads without having to do the wordpress
 * loop
 */
function _get_page_var( $key, $echo=true )
{
    global $particle_tpl_vars;
    $val = isset( $particle_tpl_vars[ $key ] ) ? $particle_tpl_vars[ $key ] : '';
    if( $echo ) echo $val;
    else return $val;
}


function _set_page_var( $key, $val )
{
    global $particle_tpl_vars;
    $particle_tpl_vars[ $key ] = $val;
}


/**
 * _get_loop_var
 *
 * Retrieve a loop (as opposed to page) variable. A loop variable is considered
 * anything that is set when the wordpress loop runs, and changes depending on 
 * the post that is currently being output
 */
function _get_loop_var( $key, $echo=true )
{
    global $particle_loop_vars;
    $val = isset( $particle_loop_vars[ $key ] ) ? $particle_loop_vars[ $key ] : '';
    if( $echo ) echo $val;
    else return $val;
}


function _set_loop_var( $key, $val )
{
    global $particle_loop_vars;
    $particle_loop_vars[ $key ] = $val;
}