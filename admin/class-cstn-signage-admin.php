<?php
/**
 * Admin functionality for CSTN Signage.
 *
 * @package    Cstn_Signage
 * @subpackage Cstn_Signage/admin
 */

class Cstn_Signage_Admin {

    /**
     * Plugin slug.
     *
     * @var string
     */
    private $plugin_name;

    /**
     * Plugin version.
     *
     * @var string
     */
    private $version;

    /**
     * Capability helper.
     *
     * @var Cstn_Signage_Capabilities
     */
    private $capabilities;

    /**
     * Constructor.
     *
     * @param string $plugin_name Plugin slug.
     * @param string $version     Plugin version.
     */
    public function __construct( $plugin_name, $version ) {
        $this->plugin_name  = $plugin_name;
        $this->version      = $version;
        $this->capabilities = new Cstn_Signage_Capabilities();
    }

    /**
     * Register custom post types.
     */
    public function register_post_types() {
        $this->register_asset_post_type();
        $this->register_playlist_post_type();
        $this->register_channel_post_type();
        $this->register_screen_post_type();
    }

    /**
     * Register taxonomies.
     */
    public function register_taxonomies() {
        $this->register_location_taxonomy();
        $this->register_category_taxonomy();
    }

    /**
     * Register admin menus.
     */
    public function register_menus() {
        add_menu_page(
            __( 'Signage', 'cstn-signage' ),
            __( 'Signage', 'cstn-signage' ),
            'edit_cstn_tv_assets',
            'cstn-signage',
            array( $this, 'render_assets_page_redirect' ),
            'dashicons-welcome-view-site',
            56
        );

        add_submenu_page(
            'cstn-signage',
            __( 'Assets', 'cstn-signage' ),
            __( 'Assets', 'cstn-signage' ),
            'edit_cstn_tv_assets',
            'edit.php?post_type=cstn_tv_asset'
        );

        add_submenu_page(
            'cstn-signage',
            __( 'Playlists', 'cstn-signage' ),
            __( 'Playlists', 'cstn-signage' ),
            'edit_cstn_tv_playlists',
            'edit.php?post_type=cstn_tv_playlist'
        );

        add_submenu_page(
            'cstn-signage',
            __( 'Channels', 'cstn-signage' ),
            __( 'Channels', 'cstn-signage' ),
            'edit_cstn_tv_channels',
            'edit.php?post_type=cstn_tv_channel'
        );

        add_submenu_page(
            'cstn-signage',
            __( 'Screens', 'cstn-signage' ),
            __( 'Screens', 'cstn-signage' ),
            'edit_cstn_tv_screens',
            'edit.php?post_type=cstn_tv_screen'
        );

        add_submenu_page(
            'cstn-signage',
            __( 'Settings', 'cstn-signage' ),
            __( 'Settings', 'cstn-signage' ),
            'manage_options',
            'cstn-signage-settings',
            array( $this, 'render_settings_page' )
        );

        remove_submenu_page( 'cstn-signage', 'cstn-signage' );
    }

    /**
     * Redirect handler for the top-level menu click.
     */
    public function render_assets_page_redirect() {
        if ( current_user_can( 'edit_cstn_tv_assets' ) ) {
            wp_safe_redirect( admin_url( 'edit.php?post_type=cstn_tv_asset' ) );
            exit;
        }

        wp_die( esc_html__( 'You do not have permission to access this page.', 'cstn-signage' ) );
    }

    /**
     * Render settings page placeholder.
     */
    public function render_settings_page() {
        echo '<div class="wrap"><h1>' . esc_html__( 'Signage Settings', 'cstn-signage' ) . '</h1><p>' . esc_html__( 'Settings interface coming soon.', 'cstn-signage' ) . '</p></div>';
    }

    /**
     * Register metaboxes.
     */
    public function register_metaboxes() {
        add_meta_box(
            'cstn_tv_asset_details',
            __( 'Asset Details', 'cstn-signage' ),
            array( $this, 'render_asset_metabox' ),
            'cstn_tv_asset'
        );

        add_meta_box(
            'cstn_tv_playlist_items',
            __( 'Playlist Items', 'cstn-signage' ),
            array( $this, 'render_playlist_metabox' ),
            'cstn_tv_playlist'
        );

        add_meta_box(
            'cstn_tv_channel_assignment',
            __( 'Channel Playlist Assignment', 'cstn-signage' ),
            array( $this, 'render_channel_metabox' ),
            'cstn_tv_channel'
        );

        add_meta_box(
            'cstn_tv_screen_details',
            __( 'Screen Details', 'cstn-signage' ),
            array( $this, 'render_screen_metabox' ),
            'cstn_tv_screen'
        );
    }

    /**
     * Render placeholder for asset meta box.
     */
    public function render_asset_metabox() {
        echo '<p>' . esc_html__( 'Select asset type and source. Coming soon.', 'cstn-signage' ) . '</p>';
    }

    /**
     * Render placeholder for playlist meta box.
     */
    public function render_playlist_metabox() {
        echo '<p>' . esc_html__( 'Playlist builder coming soon.', 'cstn-signage' ) . '</p>';
    }

    /**
     * Render placeholder for channel meta box.
     */
    public function render_channel_metabox() {
        echo '<p>' . esc_html__( 'Assign playlists to this channel. Coming soon.', 'cstn-signage' ) . '</p>';
    }

    /**
     * Render placeholder for screen meta box.
     */
    public function render_screen_metabox() {
        echo '<p>' . esc_html__( 'Screen token and assignments coming soon.', 'cstn-signage' ) . '</p>';
    }

    /**
     * Register the asset post type.
     */
    private function register_asset_post_type() {
        $labels = array(
            'name'               => __( 'Assets', 'cstn-signage' ),
            'singular_name'      => __( 'Asset', 'cstn-signage' ),
            'add_new'            => __( 'Add New', 'cstn-signage' ),
            'add_new_item'       => __( 'Add New Asset', 'cstn-signage' ),
            'edit_item'          => __( 'Edit Asset', 'cstn-signage' ),
            'new_item'           => __( 'New Asset', 'cstn-signage' ),
            'view_item'          => __( 'View Asset', 'cstn-signage' ),
            'search_items'       => __( 'Search Assets', 'cstn-signage' ),
            'not_found'          => __( 'No assets found.', 'cstn-signage' ),
            'not_found_in_trash' => __( 'No assets found in Trash.', 'cstn-signage' ),
        );

        register_post_type(
            'cstn_tv_asset',
            array(
                'labels'             => $labels,
                'public'             => false,
                'show_ui'            => true,
                'show_in_menu'       => false,
                'capability_type'    => 'cstn_tv_asset',
                'map_meta_cap'       => true,
                'supports'           => array( 'title', 'thumbnail' ),
                'capabilities'       => $this->capabilities->get( 'asset' ),
                'rewrite'            => false,
                'show_in_rest'       => true,
            )
        );
    }

    /**
     * Register the playlist post type.
     */
    private function register_playlist_post_type() {
        $labels = array(
            'name'               => __( 'Playlists', 'cstn-signage' ),
            'singular_name'      => __( 'Playlist', 'cstn-signage' ),
            'add_new'            => __( 'Add New', 'cstn-signage' ),
            'add_new_item'       => __( 'Add New Playlist', 'cstn-signage' ),
            'edit_item'          => __( 'Edit Playlist', 'cstn-signage' ),
            'new_item'           => __( 'New Playlist', 'cstn-signage' ),
            'view_item'          => __( 'View Playlist', 'cstn-signage' ),
            'search_items'       => __( 'Search Playlists', 'cstn-signage' ),
            'not_found'          => __( 'No playlists found.', 'cstn-signage' ),
            'not_found_in_trash' => __( 'No playlists found in Trash.', 'cstn-signage' ),
        );

        register_post_type(
            'cstn_tv_playlist',
            array(
                'labels'             => $labels,
                'public'             => false,
                'show_ui'            => true,
                'show_in_menu'       => false,
                'capability_type'    => 'cstn_tv_playlist',
                'map_meta_cap'       => true,
                'supports'           => array( 'title' ),
                'capabilities'       => $this->capabilities->get( 'playlist' ),
                'rewrite'            => false,
                'show_in_rest'       => true,
            )
        );
    }

    /**
     * Register the channel post type.
     */
    private function register_channel_post_type() {
        $labels = array(
            'name'               => __( 'Channels', 'cstn-signage' ),
            'singular_name'      => __( 'Channel', 'cstn-signage' ),
            'add_new'            => __( 'Add New', 'cstn-signage' ),
            'add_new_item'       => __( 'Add New Channel', 'cstn-signage' ),
            'edit_item'          => __( 'Edit Channel', 'cstn-signage' ),
            'new_item'           => __( 'New Channel', 'cstn-signage' ),
            'view_item'          => __( 'View Channel', 'cstn-signage' ),
            'search_items'       => __( 'Search Channels', 'cstn-signage' ),
            'not_found'          => __( 'No channels found.', 'cstn-signage' ),
            'not_found_in_trash' => __( 'No channels found in Trash.', 'cstn-signage' ),
        );

        register_post_type(
            'cstn_tv_channel',
            array(
                'labels'             => $labels,
                'public'             => false,
                'show_ui'            => true,
                'show_in_menu'       => false,
                'capability_type'    => 'cstn_tv_channel',
                'map_meta_cap'       => true,
                'supports'           => array( 'title' ),
                'capabilities'       => $this->capabilities->get( 'channel' ),
                'rewrite'            => false,
                'show_in_rest'       => true,
            )
        );
    }

    /**
     * Register the screen post type.
     */
    private function register_screen_post_type() {
        $labels = array(
            'name'               => __( 'Screens', 'cstn-signage' ),
            'singular_name'      => __( 'Screen', 'cstn-signage' ),
            'add_new'            => __( 'Add New', 'cstn-signage' ),
            'add_new_item'       => __( 'Add New Screen', 'cstn-signage' ),
            'edit_item'          => __( 'Edit Screen', 'cstn-signage' ),
            'new_item'           => __( 'New Screen', 'cstn-signage' ),
            'view_item'          => __( 'View Screen', 'cstn-signage' ),
            'search_items'       => __( 'Search Screens', 'cstn-signage' ),
            'not_found'          => __( 'No screens found.', 'cstn-signage' ),
            'not_found_in_trash' => __( 'No screens found in Trash.', 'cstn-signage' ),
        );

        register_post_type(
            'cstn_tv_screen',
            array(
                'labels'             => $labels,
                'public'             => false,
                'show_ui'            => true,
                'show_in_menu'       => false,
                'capability_type'    => 'cstn_tv_screen',
                'map_meta_cap'       => true,
                'supports'           => array( 'title' ),
                'capabilities'       => $this->capabilities->get( 'screen' ),
                'rewrite'            => false,
                'show_in_rest'       => true,
            )
        );
    }

    /**
     * Register the location taxonomy.
     */
    private function register_location_taxonomy() {
        $labels = array(
            'name'          => __( 'Locations', 'cstn-signage' ),
            'singular_name' => __( 'Location', 'cstn-signage' ),
            'search_items'  => __( 'Search Locations', 'cstn-signage' ),
            'all_items'     => __( 'All Locations', 'cstn-signage' ),
            'edit_item'     => __( 'Edit Location', 'cstn-signage' ),
            'update_item'   => __( 'Update Location', 'cstn-signage' ),
            'add_new_item'  => __( 'Add New Location', 'cstn-signage' ),
            'new_item_name' => __( 'New Location Name', 'cstn-signage' ),
            'menu_name'     => __( 'Locations', 'cstn-signage' ),
        );

        register_taxonomy(
            'cstn_location',
            array( 'cstn_tv_channel', 'cstn_tv_screen' ),
            array(
                'labels'            => $labels,
                'public'            => false,
                'show_ui'           => true,
                'show_admin_column' => true,
                'hierarchical'      => true,
                'show_in_rest'      => true,
            )
        );
    }

    /**
     * Register the asset category taxonomy.
     */
    private function register_category_taxonomy() {
        $labels = array(
            'name'          => __( 'Asset Categories', 'cstn-signage' ),
            'singular_name' => __( 'Asset Category', 'cstn-signage' ),
            'search_items'  => __( 'Search Asset Categories', 'cstn-signage' ),
            'all_items'     => __( 'All Asset Categories', 'cstn-signage' ),
            'edit_item'     => __( 'Edit Asset Category', 'cstn-signage' ),
            'update_item'   => __( 'Update Asset Category', 'cstn-signage' ),
            'add_new_item'  => __( 'Add New Asset Category', 'cstn-signage' ),
            'new_item_name' => __( 'New Asset Category', 'cstn-signage' ),
            'menu_name'     => __( 'Asset Categories', 'cstn-signage' ),
        );

        register_taxonomy(
            'cstn_category',
            array( 'cstn_tv_asset' ),
            array(
                'labels'            => $labels,
                'public'            => false,
                'show_ui'           => true,
                'show_admin_column' => true,
                'hierarchical'      => true,
                'show_in_rest'      => true,
            )
        );
    }
}
