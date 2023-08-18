<?php
/**
 * Style and Script Class
 *
 * @since         1.0.0
 * @package       WSC\Plugin\Admin
 */

namespace WSC\Plugin;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Plugin Styles Registering.
 * Register the Required CSS Styles and JS files for both Front end and backend (admin) pages.
 */
class Styles {

	/**
	 * Style instance
	 *
	 * @var \WSC\Plugin\Styles|null Singleton instance.
	 * @since 1.0.0
	 */
	private static $_instance; // phpcs:ignore

	/**
	 * Class Constructor
	 *
	 * @since   1.0.0
	 */
	public function __construct() {

		add_action(
			'admin_enqueue_scripts',
			array(
				$this,
				'admin_scripts',
			)
		);
	}

	/**
	 * Get the class instance.
	 *
	 * @return \WSC\Plugin\Styles
	 * @since 1.0.0
	 */
	public static function getInstance(): Styles { // phpcs:ignore
		if ( is_null( self::$_instance ) ) {
			self::$_instance = new self();
		}
		return self::$_instance;
	}

	/**
	 * Register CSS and JS for the backend end
	 *
	 * @param string $screen screen hook suffix.
	 * @since 1.0.0
	 */
	public function admin_scripts( $screen ) {
		if ( in_array( $screen, array( 'toplevel_page_wsc-admin-page' ) ) ) { // phpcs:ignore
			wp_register_style( 'wsc-admin-style', WSC_INC_URL . '/css/wsc-admin-style.css', array(), WSC_VERSION );
			wp_register_style( 'datatable-style', WSC_INC_URL . '/css/datatable.css', array(), WSC_VERSION );

			wp_enqueue_style( 'datatable-style' );
			wp_enqueue_style( 'wsc-admin-style' );

			wp_register_script( 'datatable-script', WSC_INC_URL . '/js/datatable.js', array( 'jquery' ), WSC_VERSION, true );
			wp_enqueue_script( 'datatable-script' );

			wp_register_script( 'wsc-admin-script', WSC_INC_URL . '/js/wsc-admin-script.js', array( 'jquery', 'datatable-script' ), WSC_VERSION, true );
			wp_enqueue_script( 'wsc-admin-script' );
			wp_localize_script(
				'wsc-admin-script',
				'AdminMyAjax',
				array(
					'ajaxurl'                  => admin_url( 'admin-ajax.php' ),
					'wsc_admin_security_nonce' => wp_create_nonce( 'wsc_admin_ajax_call' ),
				)
			);
		}
	}
}
