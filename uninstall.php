<?php
/*****
* Fired during plugin deactivation
* This magic file is run automatically when the users deletes the plugin.
* */

// If uninstall not called from WordPress, then exit.
if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) {
	exit;
}