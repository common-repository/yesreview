<?php

/**
 * @wordpress-plugin
 * Plugin Name:       YesReview Plugin
 * Plugin URI:        https://yesreview.com/
 * Description:       Displays reviews from your YesReview account through customizable shortcodes.  
 * This plugin used the WordPress Plugin Boilerplate as a jump off by Tom McFarlin /Devin Vinson
 * Version:           1.2
 * Author:            Tim Switzer
 * Author URI:        http://tlslogic.com/
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * 
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

// prevent issues with W3 Total Cache 
if ( ! defined( 'DONOTCACHEPAGE' ) ) {
    define('DONOTCACHEPAGE', true);
}

if ( ! defined( 'DONOTCACHEDB' ) ) {
    define('DONOTCACHEDB', true);
}

if ( ! defined( 'DONOTCACHEOBJECT' ) ) {
    define('DONOTCACHEOBJECT', true);
}



define( 'YES_REVIEW_VERSION', '1.2' );


/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-yes-review.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_yes_review() {

	$plugin = new Yes_Review();
	$plugin->run();

}
run_yes_review();
