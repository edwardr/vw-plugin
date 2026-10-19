<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              http://edwardr.me
 * @since             1.0.0
 * @package           VisitWeho
 *
 * @wordpress-plugin
 * Plugin Name:       Visit Weho
 * Plugin URI:        https://www.visitwesthollywood.com/
 * Description:       VisitWeho custom functionality and content structure
 * Version:           2.15.0
 * Author:            Edward Jenkins
 * Author URI:        http://edwardr.me
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       visitweho
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

if( strpos($_SERVER['HTTP_HOST'], 'dev.visitwesthollywood') !== FALSE ) {
	define('VW_ENVIRONMENT', 'development' );
} else {
	define('VW_ENVIRONMENT', 'production' );
}

date_default_timezone_set('America/Los_Angeles');

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-visitweho-activator.php
 */
function activate_visitweho() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-visitweho-activator.php';
	VisitWeho_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-visitweho-deactivator.php
 */
function deactivate_visitweho() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-visitweho-deactivator.php';
	VisitWeho_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_visitweho' );
register_deactivation_hook( __FILE__, 'deactivate_visitweho' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-visitweho.php';


/**
 * Event class
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-event.php';

/**
 * Venue class
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-venue.php';

/**
 * Location class
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-location.php';

/**
 * Hotel class
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-hotel.php';

/**
 * Offer class
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-offer.php';

/**
 * Story class
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-story.php';

/**
 * Page class
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-page.php';

/**
 * Video class
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-video.php';

/**
 * Main Menu class
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-main-menu.php';

/**
 * Slideshow class
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-cw-slideshow-post.php';

/**
 * Activity class
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-activity.php';

/**
 * Collection class
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-collection.php';

/**
 * Home-slide class
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-home-slide.php';


/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_visitweho() {

	$plugin = new VisitWeho();
	$plugin->run();

}
run_visitweho();
