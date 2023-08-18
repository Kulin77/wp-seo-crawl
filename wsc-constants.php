<?php
/**
 * Wsc constants.
 *
 * @since    1.0.0
 * @package  WSC\Plugin
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Plugin directory path.
 *
 * @since 1.0.0
 */
if ( ! defined( 'WSC_DIR' ) ) {
	define( 'WSC_DIR', __DIR__ );
}

/**
 * Plugin version.
 *
 * @since 1.0.0
 */
if ( ! defined( 'WSC_VERSION' ) ) {
	define( 'WSC_VERSION', '1.0.0' );
}

/**
 * Plugin url.
 *
 * @since 1.0.0
 */
if ( ! defined( 'WSC_URL' ) ) {
	define( 'WSC_URL', plugin_dir_url( __FILE__ ) );
}

/**
 * Plugin "include" directory path.
 *
 * @since 1.0.0
 */
if ( ! defined( 'WSC_INC_DIR' ) ) {
	define( 'WSC_INC_DIR', WSC_DIR . '/includes' );
}

/**
 * Plugin "include" directory url.
 *
 * @since 1.0.0
 */
if ( ! defined( 'WSC_INC_URL' ) ) {
	define( 'WSC_INC_URL', WSC_URL . 'includes' );
}

/**
 * Plugin Prefix.
 *
 * @since 1.0.0
 */
if ( ! defined( 'WSC_PREFIX' ) ) {
	define( 'WSC_PREFIX', '_WSC_' );
}
