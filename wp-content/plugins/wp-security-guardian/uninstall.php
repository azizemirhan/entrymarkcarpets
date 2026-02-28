<?php
/**
 * Fired when the plugin is uninstalled.
 *
 * @package WPSecurityGuardian
 */

if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) {
	exit;
}

global $wpdb;
$prefix = $wpdb->prefix . 'wpsg_';

$tables = array(
	$prefix . 'scan_results',
	$prefix . 'blocked_ips',
	$prefix . 'login_logs',
);

foreach ( $tables as $table ) {
	$wpdb->query( "DROP TABLE IF EXISTS {$table}" );
}

delete_option( 'wpsg_options' );
delete_option( 'wpsg_db_version' );

wp_clear_scheduled_hook( 'wpsg_daily_scan' );
