<?php
/**
 * Wsc configuration and global helpers.
 *
 * @since    1.0.0
 * @package  WSC\Plugin
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Wsc logger.
 *
 * @param  mixed            $data      Data for logging.
 * @param  string|int|float $log_name  Additional id for logger.
 *
 * @return void
 * @noinspection ForgottenDebugOutputInspection
 */
function wsc_log( $data, $log_name = 'WSC_LOG' ) {
	// phpcs:disable
	error_log(
		print_r(
			array(
				$log_name => $data,
			),
			1
		)
	);
	// phpcs:enable
}

/**
 * Get WSC plugin main classes list with configs.
 * Array keys are classes names,
 * As a values we are using config subarray with required keys
 * - "path"         - required, plugin includes folder relative path to the class file
 *
 * @return array Autoload classes.
 * @noinspection PhpFullyQualifiedNameUsageInspection
 * @since        1.0.0
 */
function wsc_autoload_classes(): array {
	return array(
		\WSC\Plugin\Admin\Admin::class     => array(
			'path' => '/admin/class-admin.php',
		),
		\WSC\Plugin\Styles::class          => array(
			'path' => '/class-styles.php',
		),
		\WSC\Plugin\Actions\Actions::class => array(
			'path' => '/actions/class-actions.php',
		),
	);
}
