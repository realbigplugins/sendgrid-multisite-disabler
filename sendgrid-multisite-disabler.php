<?php
/*
 * Plugin Name: SendGrid Multisite Disabler - Must-Use Plugin Only!
 * Description: Prevent SendGrid from attempting to send Emails if no API Key for that site is entered. Important on Multisite setups where a sub site may be using another service, like SparkPost.
 * Version: 1.0.0
 * Author: Eric Defore
 * Author URI: https://realbigplugins.com
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

add_filter( 'site_option_active_sitewide_plugins', function( $network_active_plugins ) {
	
	if ( is_network_admin() ) return $network_active_plugins;
	
	// If an appropriate API Key is set, we're good
	if ( get_network_option( null, 'sendgrid_api_key' ) ) return $network_active_plugins;
	
	// Check against whether per-site API Keys are enabled for Sub Sites, thereby ensuring the Menu is available to even enter a API Key
	if ( get_option( 'sendgrid_can_manage_subsite' ) ) return $network_active_plugins;
	
	// Else, unset the Active Plugin for this site
	if ( isset( $network_active_plugins['sendgrid-email-delivery-simplified/wpsendgrid.php'] ) ) {
		unset( $network_active_plugins['sendgrid-email-delivery-simplified/wpsendgrid.php'] );
	}
	
	return $network_active_plugins;
	
} );