<?php
/**
 * Capabilities helper for the CSTN Signage plugin.
 *
 * @package    Cstn_Signage
 * @subpackage Cstn_Signage/includes
 */

class Cstn_Signage_Capabilities {

    /**
     * Map of entity keys to capability sets.
     *
     * @var array
     */
    private $map = array();

    /**
     * Constructor.
     */
    public function __construct() {
        $this->map = array(
            'asset'    => $this->generate_capabilities( 'cstn_tv_asset' ),
            'playlist' => $this->generate_capabilities( 'cstn_tv_playlist' ),
            'channel'  => $this->generate_capabilities( 'cstn_tv_channel' ),
            'screen'   => $this->generate_capabilities( 'cstn_tv_screen' ),
        );
    }

    /**
     * Retrieve the capability map for a given entity key.
     *
     * @param string $key Entity key.
     * @return array
     */
    public function get( $key ) {
        return isset( $this->map[ $key ] ) ? $this->map[ $key ] : array();
    }

    /**
     * Retrieve capabilities for a set of entity keys.
     *
     * @param array $keys Entity keys.
     * @return array
     */
    public function get_capabilities_for( $keys ) {
        $caps = array();
        foreach ( $keys as $key ) {
            $caps = array_merge( $caps, array_values( $this->get( $key ) ) );
        }

        return array_unique( $caps );
    }

    /**
     * Get all custom capability strings registered by the plugin.
     *
     * @return array
     */
    public function all_caps() {
        return $this->get_capabilities_for( array( 'asset', 'playlist', 'channel', 'screen' ) );
    }

    /**
     * Generate capability map for a post type slug.
     *
     * @param string $type Post type slug.
     * @return array
     */
    private function generate_capabilities( $type ) {
        return array(
            'edit_post'              => "edit_{$type}",
            'read_post'              => "read_{$type}",
            'delete_post'            => "delete_{$type}",
            'edit_posts'             => "edit_{$type}s",
            'edit_others_posts'      => "edit_others_{$type}s",
            'publish_posts'          => "publish_{$type}s",
            'read_private_posts'     => "read_private_{$type}s",
            'delete_posts'           => "delete_{$type}s",
            'delete_private_posts'   => "delete_private_{$type}s",
            'delete_published_posts' => "delete_published_{$type}s",
            'delete_others_posts'    => "delete_others_{$type}s",
            'edit_private_posts'     => "edit_private_{$type}s",
            'edit_published_posts'   => "edit_published_{$type}s",
            'create_posts'           => "edit_{$type}s",
        );
    }
}
