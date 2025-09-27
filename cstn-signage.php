<?php
/**
 * Plugin Name:       CSTN Signage
 * Plugin URI:        https://centerstone.org
 * Description:       Digital signage management tools for Centerstone.
 * Version:           0.1.0
 * Author:            Centerstone
 * Author URI:        https://centerstone.org
 * Text Domain:       cstn-signage
 * Domain Path:       /languages
 */

if ( ! defined( 'WPINC' ) ) {
    die;
}

/**
 * Currently plugin version.
 */
define( 'CSTN_SIGNAGE_VERSION', '0.1.0' );

/**
 * Absolute path to the plugin file.
 */
define( 'CSTN_SIGNAGE_FILE', __FILE__ );

/**
 * Absolute path to the plugin directory.
 */
define( 'CSTN_SIGNAGE_DIR', plugin_dir_path( CSTN_SIGNAGE_FILE ) );

/**
 * URL to the plugin directory.
 */
define( 'CSTN_SIGNAGE_URL', plugin_dir_url( CSTN_SIGNAGE_FILE ) );

/**
 * The code that runs during plugin activation.
 */
function activate_cstn_signage() {
    require_once CSTN_SIGNAGE_DIR . 'includes/class-cstn-signage-activator.php';
    Cstn_Signage_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 */
function deactivate_cstn_signage() {
    require_once CSTN_SIGNAGE_DIR . 'includes/class-cstn-signage-deactivator.php';
    Cstn_Signage_Deactivator::deactivate();
}

register_activation_hook( CSTN_SIGNAGE_FILE, 'activate_cstn_signage' );
register_deactivation_hook( CSTN_SIGNAGE_FILE, 'deactivate_cstn_signage' );

require CSTN_SIGNAGE_DIR . 'includes/class-cstn-signage.php';

/**
 * Begins execution of the plugin.
 */
function run_cstn_signage() {
    $plugin = new Cstn_Signage();
    $plugin->run();
}

run_cstn_signage();
