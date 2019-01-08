<?php
/**
 * The plugin bootstrap file
 *
 * @link              http://syllogic.in
 * @since             1.0.0
 * @package           Cf7_Grid_Layout
 *
 * @wordpress-plugin
 * Plugin Name:       CF7 Smart Grid Design Extension
 * Plugin URI:        http://wordpress.syllogic.in
 * Description:       Enabled responsive grid layout designs for Contact Form 7 forms.
 * Version:           2.7.1
 * Author:            Aurovrata V.
 * Author URI:        http://syllogic.in
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       cf7-grid-layout
 * Domain Path:       /languages
 */
// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}
define( 'CF7_GRID_VERSION', '2.7.1' );

if(! defined('WPCF7_AUTOP') ) define('WPCF7_AUTOP', false);



/** @since 2.6.0, NOTIFY USERS of forms requiring udpates */
define( 'CF7SG_VERSION_FORM_UPDATE', '2.6.0');
/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-cf7-grid-layout-activator.php
 */
function activate_cf7_grid_layout() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-cf7-grid-layout-activator.php';
	Cf7_Grid_Layout_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-cf7-grid-layout-deactivator.php
 */
function deactivate_cf7_grid_layout() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-cf7-grid-layout-deactivator.php';
	Cf7_Grid_Layout_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_cf7_grid_layout' );
register_deactivation_hook( __FILE__, 'deactivate_cf7_grid_layout' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-cf7-grid-layout.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_cf7_grid_layout() {
	$plugin = new Cf7_Grid_Layout(CF7_GRID_VERSION);
	$plugin->run();

}
run_cf7_grid_layout();
