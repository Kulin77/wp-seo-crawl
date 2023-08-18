<?php
/**
 * Plugin Name: WP Seo Crawl
 * Description: A crawl plugin to Extract all internal hyperlinks.
 * Author: Riddhish
 * Author URI: https://github.com/rids1207
 * Version: 1.0.0
 * Text Domain: wsc
 * Tested up to: 6.3
 * Requires at least: 5.0
 * Requires PHP: 7.2
 *
 * @package WSC\Plugin
 */

/**
 * Register plugin translations.
 *
 * @since 1.0.0
 */

add_action(
	'plugins_loaded',
	function () {
		load_plugin_textdomain( 'wsc', false, __DIR__ . '/languages/' );
	}
);

/**
 * Register plugin activation hooks.
 *
 * @since 1.0.0
 */
register_activation_hook(
	__FILE__,
	function () {
		$new_folder_name = 'wsc';
		$new_folder_path = WP_CONTENT_DIR . '/' . $new_folder_name;
		if ( ! is_dir( $new_folder_path ) ) {
			mkdir( $new_folder_path );
		}
	}
);

/**
 * Require build files.
 *
 * @since 1.0.0
 */

require_once __DIR__ . '/wsc-constants.php';
require_once WSC_DIR . '/wsc-register.php';
require_once WSC_DIR . '/wsc-init.php';
