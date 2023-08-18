<?php
/**
 * Admin Class
 * Adds functionality to the backend/Admin page
 *
 * @since         1.0.0
 * @package       WSC\Plugin\Admin
 */

namespace WSC\Plugin\Admin;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Admin Class
 */
class Admin {

	/**
	 * Instance of class
	 *
	 * @var \WSC\Plugin\Admin|null Singleton instance.
	 * @since 1.0.0
	 */
	private static $_instance; // phpcs:ignore

	/**
	 * Class Constructor
	 * Add the Hooks
	 *
	 * @since 1.0.0
	 */
	public function __construct() {
		$this->add_hooks();
	}

	/**
	 * Get the class instance.
	 *
	 * @return \WSC\Plugin\Admin
	 * @since 1.0.0
	 */
	public static function getInstance(): Admin { // phpcs:ignore
		if ( is_null( self::$_instance ) ) {
			self::$_instance = new self();
		}
		return self::$_instance;
	}

	/**
	 * Register menu pages.
	 *
	 * @return void
	 * @since 1.0.0
	 */
	public function register_menu() {
		$capability = 'manage_options';

		add_menu_page(
			esc_html__( 'Seo Crawl', 'wsc' ),
			esc_html__( 'Seo Crawl', 'wsc' ),
			$capability,
			'wsc-admin-page',
			array(
				$this,
				'wsc_settings_page_callback',
			),
			'dashicons-hourglass',
		);
	}

	/**
	 * Menu pages Html.
	 *
	 * @return void
	 * @since 1.0.0
	 */
	public function wsc_settings_page_callback() {
		require_once WSC_INC_DIR . '/templates/wsc-option-page.php';
	}

	/**
	 * Add Hooks
	 * Adding hooks to handle WordPress admin functionality
	 *
	 * @since   1.0.0
	 */
	private function add_hooks() {
		add_action(
			'admin_menu',
			array(
				$this,
				'register_menu',
			)
		);
	}
}
