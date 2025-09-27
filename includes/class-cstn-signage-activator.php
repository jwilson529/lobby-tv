<?php
/**
 * Fired during plugin activation
 *
 * @package    Cstn_Signage
 * @subpackage Cstn_Signage/includes
 */

class Cstn_Signage_Activator {

    /**
     * Run tasks on plugin activation.
     */
    public static function activate() {
        require_once CSTN_SIGNAGE_DIR . 'includes/class-cstn-signage-capabilities.php';
        require_once CSTN_SIGNAGE_DIR . 'admin/class-cstn-signage-admin.php';

        $admin = new Cstn_Signage_Admin( 'cstn-signage', CSTN_SIGNAGE_VERSION );
        $admin->register_post_types();
        $admin->register_taxonomies();

        self::add_roles();
        flush_rewrite_rules();
    }

    /**
     * Create the Digital Signage Manager role with required capabilities.
     */
    private static function add_roles() {
        $capability_manager = new Cstn_Signage_Capabilities();
        $manager_caps       = array_fill_keys( $capability_manager->all_caps(), true );
        $manager_caps['read']         = true;
        $manager_caps['upload_files'] = true;

        $role_cap_sets = array(
            'administrator'           => $capability_manager->all_caps(),
            'editor'                  => $capability_manager->get_capabilities_for( array( 'asset', 'playlist' ) ),
            'digital_signage_manager' => $capability_manager->all_caps(),
        );

        foreach ( $role_cap_sets as $role_key => $caps ) {
            $role = get_role( $role_key );

            if ( ! $role && 'digital_signage_manager' === $role_key ) {
                $role = add_role( 'digital_signage_manager', __( 'Digital Signage Manager', 'cstn-signage' ), array() );
            }

            if ( ! $role ) {
                continue;
            }

            foreach ( $caps as $cap ) {
                $role->add_cap( $cap );
            }
        }

        $manager_role = get_role( 'digital_signage_manager' );
        if ( $manager_role ) {
            foreach ( $manager_caps as $cap => $grant ) {
                $manager_role->add_cap( $cap, $grant );
            }
        }
    }
}
