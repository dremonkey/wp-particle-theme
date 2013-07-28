<?php
/**
 * Particle Theme functions.php file
 *
 * This file does/contains the following:
 *  
 *  1. Defines constants
 *  2. sets up the autoloader class
 *  3. instantiates all singleton classes
 *  4. utility 
 */

/**
 Constants
 */

/*** Parent Theme Name ***/
define( 'PARTICLE_THEME', 'particle' );


/*** Current Theme Name ***/
$theme = wp_get_theme();
define( 'THEME_NAME', strtolower( $theme->name ) );


/*** Parent Theme Directory and URL ***/
define( 'PARTICLE_DIR', get_template_directory() );
define( 'PARTICLE_URL', get_template_directory_uri() );


/*** Child Theme Directory and URL ***/
define( 'THEME_DIR', get_stylesheet_directory() );
define( 'THEME_URL', get_stylesheet_directory_uri() );


/*** Parent Theme App Directory and URL ***/
define( 'PARTICLE_APP_DIR', PARTICLE_DIR . '/app/' );
define( 'PARTICLE_APP_URL', PARTICLE_URL . '/app/' );


/*** Child Theme App Directory and URL ***/
define( 'THEME_APP_DIR', THEME_DIR . '/app/' );
define( 'THEME_APP_URL', THEME_URL . '/app/' );


/*** Parent Theme App Sub-Directories ***/
define( 'PARTICLE_CONTROLLERS_DIR', PARTICLE_APP_DIR . 'controllers/' );
define( 'PARTICLE_LANGUAGES_DIR', PARTICLE_APP_DIR . 'languages/' );
define( 'PARTICLE_MODELS_DIR', PARTICLE_APP_DIR . 'models/' );
define( 'PARTICLE_SINGLETONS_DIR', PARTICLE_APP_DIR . 'singletons/' );
define( 'PARTICLE_WIDGETS_DIR', PARTICLE_APP_DIR . 'widgets/' );
define( 'PARTICLE_INCLUDES_DIR', PARTICLE_APP_DIR . 'includes/' );


/*** Child Theme App Sub-Directories ***/
define( 'THEME_CONTROLLERS_DIR', THEME_APP_DIR . 'controllers/' );
define( 'THEME_LANGUAGES_DIR', THEME_APP_DIR . 'languages/' );
define( 'THEME_MODELS_DIR', THEME_APP_DIR . 'models/' );
define( 'THEME_SINGLETONS_DIR', THEME_APP_DIR . 'singletons/' );
define( 'THEME_WIDGETS_DIR', THEME_APP_DIR . 'widgets/' );
define( 'THEME_INCLUDES_DIR', THEME_APP_DIR . 'includes/' );


/*** Parent Theme Assets Directory and URL ***/
define( 'PARTICLE_ASSETS_DIR', PARTICLE_DIR . '/assets/' );
define( 'PARTICLE_ASSETS_URL', PARTICLE_URL . '/assets/' );


/*** Child Theme Assets Directory and URL ***/
define( 'THEME_ASSETS_DIR', THEME_DIR . '/assets/' );
define( 'THEME_ASSETS_URL', THEME_URL . '/assets/' );


/**
 IMPORTANT - include the autoloader and utils files before any other theme file.
 */
require_once( 'app/autoloader.class.php' );
require_once( 'app/utils.php' );


/** 
 Requires the naked-utils plugin
 */

if( !defined ( 'NAKED_UTILS' ) ) {
    if( current_user_can( 'install_plugins' ) )
        add_action( 'admin_notices', 'particle_show_notice');
} 
else {

    $singletons = _get_singletons( PARTICLE_SINGLETONS_DIR, PARTICLE_THEME );

    // allow child themes to add new singletons
    $singletons = apply_filters( 'particle_singletons', $singletons );

    foreach( $singletons as $singleton ) {
        $singleton::get_instance();
        nu_debug( 'Singletons Loaded', $singleton );
    }


    /**
     * Access Restriction / Under Construction Page
     *
     * If enabled all requests will be redirected to the login page until the user logs in
     */
    if( ( particle_get_advanced_option( 'restrict_access' ) 
          || particle_get_advanced_option( 'maintenance' ) )
        && !is_user_logged_in() 
        && !particle_is_whitelisted() ) {

        nocache_headers();

        // if on maitenance mode redirect everything to the homepage 
        if ( particle_get_advanced_option( 'maintenance' ) ) {
            $url = site_url();
        }
        // if on restrict access redirect everything to the login page
        else if( particle_get_advanced_option( 'restrict_access' ) ) {
            $url = wp_login_url( site_url() );
        }

        if( $url ) {
            wp_redirect( $url );
            exit();
        }
    }

} // end if defined( "NAKED_UTILS" )


/**
 function.php helpers
 */

function particle_show_notice()
{
    $name = '<strong>' . THEME_NAME . '</strong>';

    echo '<div class="error"><p>';
    printf( __('This theme requires Naked Utils to work. Please make sure that you have installed and activated <a href="%s">Naked Utils</a>. They are like peas in a pod.', 'naked' ), $name, '#' );
    echo "</p></div>";
}


function particle_is_whitelisted()
{
    $current_uri = trailingslashit( nu_utils::self_uri() );
    
    // whitelisted urls and url parts
    $whitelist = array(
        wp_login_url(),
        'wp-admin',
        'social-connect', // make sure social connect works
        'wp-cron.php',
        'xmlrpc.php', // make sure jetpack works
    );

    foreach( $whitelist as $url ) {
        if( false !== strpos( $current_uri, $url ) )
            return true;
    }

    // make homepage accessible if maintenance mode is active
    if( particle_get_advanced_option( 'maintenance' ) ) {
        if( $current_uri == trailingslashit( site_url() ) )
            return true;
    }

    return false;
}