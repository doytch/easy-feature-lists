<?php

/**
 * @since             1.0.0
 * @package           Easy_Feature_Lists
 *
 * @wordpress-plugin
 * Plugin Name:       Easy Feature Lists
 * Plugin URI:        http://wordpress.org/plugins/easy-feature-lists
 * Description:       Easily create checklists of features in your posts and pages.
 * Version:           1.0.0
 * Author:            Mark Hurst Deutsch
 * Author URI:        http://qedev.com/
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       plugin-name
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * The code that runs during plugin activation.
 */
require_once plugin_dir_path( __FILE__ ) . 'includes/EFLActivator.php';

/**
 * The code that runs during plugin deactivation.
 */
require_once plugin_dir_path( __FILE__ ) . 'includes/EFLDeactivator.php';

/** This action is documented in includes/activator.php */
register_activation_hook( __FILE__, array( 'EFLActivator', 'activate' ) );

/** This action is documented in includes/deactivator.php */
register_deactivation_hook( __FILE__, array( 'EFLDeactivator', 'deactivate' ) );

/**
 * The core plugin class that is used to define internationalization,
 * dashboard-specific hooks, and public-facing site hooks.
 */
require_once plugin_dir_path( __FILE__ ) . 'includes/easy-feature-lists.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_plugin_name() {

	$plugin = new EFL();
	$plugin->run();

}
run_plugin_name();
