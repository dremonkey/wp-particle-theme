<?php

/**
 * Autoloader Class
 *
 * This automatically includes the file that contains the class that is to be instantiated, so that
 * we do not have to explicitly use an 'include' or 'require' statement before creating a new 
 * instance of a class.
 * 
 */

 /*** nullify any existing autoloads ***/
spl_autoload_register( null, false );


/*** specify extensions that may be loaded ***/
spl_autoload_extensions( '.php' );


/*** register the loader functions ***/
spl_autoload_register( 'particle_autoloader::get_controller' );
spl_autoload_register( 'particle_autoloader::get_model' );
spl_autoload_register( 'particle_autoloader::get_singleton' );


class particle_autoloader 
{
    
  /**
   * Loads controller file(s) in both the parent and child theme directories. 
   *
   * The parent and child theme controllers directories are not mutually exclusive, meaning that
   * if a child theme is active and there is a controllers/theme.php file in the child theme 
   * directory and a controllers/theme.php file in the parent theme directory, both files will 
   * be included.
   */
  public static function get_controller( $classname )
  {
    // stop unnecessary checks
    if( strpos( $classname, 'controller' ) === false ) 
      return;

    // check the parent directory and include the controller file
    $file = self::_get_fpath( $classname, PARTICLE_CONTROLLERS_DIR );
    if( $file ) include_once $file;

    // if a child theme is active check the child theme for the controller
    if( is_child_theme() ) {
      $file = self::_get_fpath( $classname, THEME_CONTROLLERS_DIR );
      if( $file ) include_once $file;
    }
  }


  /**
   * Loads model file(s) in both the parent and child theme directories. 
   *
   * The parent and child theme models directories are not mutually exclusive, meaning that
   * if a child theme is active and there is a models/theme.php file in the child theme 
   * directory and a models/theme.php file in the parent theme directory, both files will be 
   * included.
   */
  public static function get_model( $classname )
  {
    // stop unnecessary checks
    if( strpos( $classname, 'model' ) === false ) 
      return;

    // check the parent directory and include the controller file
    $file = self::_get_fpath( $classname, PARTICLE_MODELS_DIR );
    if( $file ) include_once $file;

    // if a child theme is active check the child theme for the controller
    if( is_child_theme() ) {
      $file = self::_get_fpath( $classname, THEME_MODELS_DIR );
      if( $file ) include_once $file;
    }
  }


  /**
   * Loads singleton file(s) in both the parent and child theme directories. 
   *
   * The parent and child theme singletons directories are not mutually exclusive, meaning that
   * if a child theme is active and there is a singletons/theme.php file in the child theme 
   * directory and a singletons/theme.php file in the parent theme directory, both files will be 
   * included.
   */
  public static function get_singleton( $classname )
  {
    // stop unnecessary checks
    if( strpos( $classname, 'singleton' ) === false ) 
      return;

    // check the parent directory and include the controller file
    $file = self::_get_fpath( $classname, PARTICLE_SINGLETONS_DIR );
    if( $file ) include_once $file;

    // if a child theme is active check the child theme for the controller
    if( is_child_theme() ) {
      $file = self::_get_fpath( $classname, THEME_SINGLETONS_DIR );
      if( $file ) include_once $file;
    }
  }


  private static function _get_fpath( $classname, $dir_path )
  {
  	$name = strtolower( $classname );

  	// Strips out the theme name (prepended to the front). This assumes that the part of the classname up until the first '_' is the theme name
    $pattern = '/^([a-zA-Z]+?[_])/i';
    $name = preg_replace( $pattern, '', $name );

  	// Strips out the classtype (appended to the end). This assumes that the part of the classname after the last '_' is the classtype
    $pattern = '/_([a-zA-Z])+$/i';
    $name = preg_replace( $pattern, '', $name );

  	// replaces '_' with '-' because that is how the files are named
  	$name = str_replace( '_', '-', $name );

    $file = $dir_path . $name . '.php';

    // check to see if the file exists and is readable
    if( !is_readable( $file ) )
      return false;

  	return $file;
  }
}