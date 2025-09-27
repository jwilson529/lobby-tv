<?php
/**
 * Fired during plugin deactivation
 *
 * @package    Cstn_Signage
 * @subpackage Cstn_Signage/includes
 */

class Cstn_Signage_Deactivator {

    /**
     * Run tasks on plugin deactivation.
     */
    public static function deactivate() {
        flush_rewrite_rules();
    }
}
