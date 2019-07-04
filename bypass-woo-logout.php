<?php
/**
 * Plugin Name:     Bypass Woo Logout
 * Plugin URI:      https://github.com/tarosky/bypass-woo-logout
 * Description:     This plugin bypasses the WooCommerce logout process.
 * Author:          tarosky, ko31
 * Author URI:      https://tarosky.co.jp
 * Text Domain:     bypass-woo-logout
 * Domain Path:     /languages
 * Version:         0.1.0
 *
 * @package         Bypass_Woo_Logout
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Bypass logout endpoint.
 *
 * @see wc_template_redirect()
 * @see {wp-login.php}
 */
add_action( 'template_redirect', function () {
	global $wp;
	if ( isset( $wp->query_vars['customer-logout'] ) && ! empty( $_REQUEST['_wpnonce'] ) && wp_verify_nonce( sanitize_key( $_REQUEST['_wpnonce'] ), 'customer-logout' ) ) {
		$user = wp_get_current_user();
		// Logout.
		wp_logout();
		// Set redirect to.
		if ( ! empty( $_REQUEST['redirect_to'] ) ) {
			$redirect_to = $requested_redirect_to = $_REQUEST['redirect_to'];
		} else {
			$redirect_to           = wc_get_page_permalink( 'myaccount' );
			$requested_redirect_to = '';
		}
		/**
		 * Filters the log out redirect URL.
		 * @see ll.544 of wp-login.php
		 */
		$redirect_to = apply_filters( 'logout_redirect', $redirect_to, $requested_redirect_to, $user );
		wp_safe_redirect( $redirect_to );
		exit();
	}
}, 9 );
