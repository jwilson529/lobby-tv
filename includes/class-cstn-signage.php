<?php
/**
 * The core plugin class for CSTN Signage.
 *
 * @package    Cstn_Signage
 * @subpackage Cstn_Signage/includes
 */

class Cstn_Signage {

    /**
     * Loader for registering hooks with WordPress.
     *
     * @var Cstn_Signage_Loader
     */
    protected $loader;

    /**
     * Unique identifier for the plugin.
     *
     * @var string
     */
    protected $plugin_name = 'cstn-signage';

    /**
     * Current version of the plugin.
     *
     * @var string
     */
    protected $version;

    /**
     * Constructor.
     */
    public function __construct() {
        $this->version = CSTN_SIGNAGE_VERSION;

        $this->load_dependencies();
        $this->set_locale();
        $this->define_admin_hooks();
    }

    /**
     * Load plugin dependencies.
     */
    private function load_dependencies() {
        require_once CSTN_SIGNAGE_DIR . 'includes/class-cstn-signage-loader.php';
        require_once CSTN_SIGNAGE_DIR . 'includes/class-cstn-signage-i18n.php';
        require_once CSTN_SIGNAGE_DIR . 'includes/class-cstn-signage-capabilities.php';
        require_once CSTN_SIGNAGE_DIR . 'admin/class-cstn-signage-admin.php';

        $this->loader = new Cstn_Signage_Loader();
    }

    /**
     * Set up internationalization hooks.
     */
    private function set_locale() {
        $plugin_i18n = new Cstn_Signage_I18n();
        $this->loader->add_action( 'plugins_loaded', $plugin_i18n, 'load_plugin_textdomain' );
    }

    /**
     * Register hooks for the admin area.
     */
    private function define_admin_hooks() {
        $plugin_admin = new Cstn_Signage_Admin( $this->get_plugin_name(), $this->get_version() );

        $this->loader->add_action( 'init', $plugin_admin, 'register_post_types' );
        $this->loader->add_action( 'init', $plugin_admin, 'register_taxonomies' );
        $this->loader->add_action( 'admin_menu', $plugin_admin, 'register_menus' );
        $this->loader->add_action( 'add_meta_boxes', $plugin_admin, 'register_metaboxes' );
    }

    /**
     * Run the loader to execute hooks with WordPress.
     */
    public function run() {
        $this->loader->run();
    }

    /**
     * Get plugin name.
     *
     * @return string
     */
    public function get_plugin_name() {
        return $this->plugin_name;
    }

    /**
     * Get plugin version.
     *
     * @return string
     */
    public function get_version() {
        return $this->version;
    }

    /**
     * Get loader instance.
     *
     * @return Cstn_Signage_Loader
     */
    public function get_loader() {
        return $this->loader;
    }
}
