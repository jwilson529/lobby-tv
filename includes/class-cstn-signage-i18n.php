<?php
/**
 * Define internationalization functionality.
 *
 * @package    Cstn_Signage
 * @subpackage Cstn_Signage/includes
 */

class Cstn_Signage_I18n {

    /**
     * Load the plugin text domain for translation.
     */
    public function load_plugin_textdomain() {
        load_plugin_textdomain( 'cstn-signage', false, basename( CSTN_SIGNAGE_DIR ) . '/languages/' );
    }
}
