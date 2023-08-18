<?php
/**
 * Wsc Plugin initialization.
 *
 * @since    1.0.0
 * @package  WSC\Plugin
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

use WSC\Plugin\Admin\Admin;
use WSC\Plugin\Styles;
use WSC\Plugin\Actions\Actions;

$as_path = WSC_INC_DIR . '/lib/action-scheduler/action-scheduler.php';
if ( file_exists( $as_path ) ) {
	require_once $as_path;
}

foreach ( wsc_autoload_classes() as $class => $configs ) {
	if ( empty( $class ) || empty( $configs['path'] ) ) {
		wsc_log(
			array(
				'class'   => $class,
				'configs' => $configs,
			),
			'WP SEO CRAWL AUTO LOADING ERROR. INVALID CLASS SETUP'
		);
		continue;
	}

	include_once WSC_INC_DIR . $configs['path'];
}

// Instantiate the classes.
Admin::getInstance();
Styles::getInstance();
Actions::getInstance();
