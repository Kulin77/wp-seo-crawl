<?php
/**
 * Actions Class
 * Handles all the different features and functions.
 *
 * @since        1.0.0
 * @package      WSC\Plugin\Actions
 */

namespace WSC\Plugin\Actions;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Actions Class
 */
class Actions {


	/**
	 * Actions class instance
	 *
	 * @var \WSC\Plugin\Actions|null Singleton instance.
	 * @since 1.0.0
	 */
	private static $_instance; // phpcs:ignore

	/**
	 * Class Constructor
	 * Add the Hooks.
	 *
	 * @since 1.0.0
	 */
	public function __construct() {
		$this->add_hooks();
	}

	/**
	 * Get the class instance.
	 *
	 * @return \WSC\Plugin\Actions
	 * @since 1.0.0
	 */
	public static function getInstance(): Actions { // phpcs:ignore
		if ( is_null( self::$_instance ) ) {
			self::$_instance = new self();
		}
		return self::$_instance;
	}

	/**
	 * Retrieves internal hyperlinks report.
	 *
	 * @since   1.0.0
	 */
	public function wsc_report_generate_callback() {
		check_ajax_referer( 'wsc_admin_ajax_call', 'security' );

		$repost_response = $this->wsc_process_repost_data();
		if ( isset( $repost_response['success'] ) && ! empty( $repost_response['success'] ) ) {
			update_option( 'wsc_admin_trigger', true );
		}

		wp_send_json( $repost_response );
		exit();
	}

	/**
	 * Retrieves internal hyperlinks report.
	 *
	 * @since   1.0.0
	 */
	public function wsc_process_repost_data() {
		$home_url = get_home_url();
		$response = wp_remote_get( $home_url, array( 'sslverify' => false ) );

		if ( is_wp_error( $response ) ) {
			$msg       = esc_html__( 'Failed to load the website. Please try again later.', 'wsc' );
			$res_array = array(
				'error' => true,
				'msg'   => $msg,
			);
			wsc_log( $res_array );
			return $res_array;
		}

		$body = wp_remote_retrieve_body( $response );
		if ( empty( $body ) ) {
			$msg       = esc_html__( 'Content not found in the website. Please try again later.', 'wsc' );
			$res_array = array(
				'error' => true,
				'msg'   => $msg,
			);
			wsc_log( $res_array );
			return $res_array;
		}

		if ( ! class_exists( 'DOMDocument' ) ) {
			$msg       = esc_html__( 'DOMDocument extension is required. Please check server configuration.', 'wsc' );
			$res_array = array(
				'error' => true,
				'msg'   => $msg,
			);
			wsc_log( $res_array );
			return $res_array;
		}

		$links = $this->wsc_extract_internal_links( $body, $home_url );
		if ( empty( $links ) ) {
			$msg       = esc_html__( 'No internal hyperlinks found. Please try again later.', 'wsc' );
			$res_array = array(
				'error' => true,
				'msg'   => $msg,
			);
			wsc_log( $res_array );
			return $res_array;
		}

		$transient_set = $this->wsc_update_crawl_transient_data( $links );

		if ( $transient_set ) {
			$msg       = esc_html__( 'Data Retrieval Successful. Internal hyperlinks has been successfully fetched.', 'wsc' );
			$res_array = array(
				'success' => true,
				'msg'     => $msg,
				'data'    => $links,
			);
		} else {
			$msg       = esc_html__( 'Oops, Something Went Wrong.', 'wsc' );
			$res_array = array(
				'error' => true,
				'msg'   => $msg,
				'data'  => $links,
			);
		}
		wsc_log( $res_array );
		return $res_array;
	}

	/**
	 * Schedule hourly cron.
	 *
	 * @since   1.0.0
	 */
	public function wsc_schedule_hourly_action() {
		$admin_trigger = get_option( 'wsc_admin_trigger' );

		if ( $admin_trigger ) {
			if ( ! as_next_scheduled_action( 'wsc_process_repost_data' ) ) {
				as_schedule_recurring_action( time(), 3600, 'wsc_process_repost_data' );
			}
		}
	}

	/**
	 * Extract internal hyperlinks report.
	 *
	 * @param string $content post content.
	 * @param string $url urls.
	 * @since   1.0.0
	 */
	public function wsc_extract_internal_links( $content, $url ) {
		$internal_links = array();
		$dom            = new \DOMDocument();

		libxml_use_internal_errors( true );
		$dom->loadHTML( $content );
		libxml_clear_errors();

		foreach ( $dom->getElementsByTagName( 'a' ) as $link ) {
			$href = $link->getAttribute( 'href' );
			if ( strpos( $href, $url ) !== false ) {
				$internal_links[] = $href;
			}
		}

		if ( ! empty( $internal_links ) ) {
			$internal_links = array_values( array_unique( $internal_links ) );
		}
		return $internal_links;
	}

	/**
	 * Set and update transient data.
	 *
	 * @param string $data crawl data.
	 * @since   1.0.0
	 */
	public function wsc_update_crawl_transient_data( $data ) {
		delete_transient( 'wsc_links_data' );

		$this->wsc_create_sitemap( $data );
		$this->wsc_create_homepage();
		$res = set_transient( 'wsc_links_data', $data, 7200 );

		if ( empty( $res ) ) {
			$msg       = esc_html__( 'Data not inserted.', 'wsc' );
			$res_array = array(
				'error' => true,
				'msg'   => $msg,
				'data'  => $data,
			);
			wsc_log( $res_array );
			return $res_array;
		} else {
			return $res;
		}
	}

	/**
	 * Create sitemap.html file.
	 *
	 * @param string $internal_links links.
	 * @since   1.0.0
	 */
	public function wsc_create_sitemap( $internal_links ) {
		$sitemap_file = WP_CONTENT_DIR . '/wsc/sitemap.html';
		if ( file_exists( $sitemap_file ) ) {
			unlink( $sitemap_file );
		}

		$sitemap_content = '<ul>';
		foreach ( $internal_links as $link ) {
			$sitemap_content .= '<li><a href="' . esc_url( $link ) . '">' . esc_html( $link ) . '</a></li>';
		}
		$sitemap_content .= '</ul>';

		$file_created = file_put_contents( $sitemap_file, $sitemap_content ); // phpcs:ignore

		if ( empty( $file_created ) ) {
			$msg       = esc_html__( 'Facing issue while creating Sitemap.html file.', 'wsc' );
			$res_array = array(
				'error'    => true,
				'msg'      => $msg,
				'filePath' => $sitemap_file,
				'fileData' => $internal_links,
			);
			wsc_log( $res_array );
			return $res_array;
		}
	}

	/**
	 * Create homepage.html file.
	 *
	 * @since   1.0.0
	 */
	public function wsc_create_homepage() {
		$homepage_file = WP_CONTENT_DIR . '/wsc/homepage.html';
		if ( file_exists( $homepage_file ) ) {
			unlink( $homepage_file );
		}

		$homepage_content = wp_remote_get( get_home_url(), array( 'sslverify' => false ) );
		$file_created     = file_put_contents( $homepage_file, $homepage_content['body'] ); // phpcs:ignore

		if ( empty( $file_created ) ) {
			$msg       = esc_html__( 'Facing issue while creating homepage.html file.', 'wsc' );
			$res_array = array(
				'error'    => true,
				'msg'      => $msg,
				'filePath' => $homepage_file,
			);
		} else {
			$msg       = esc_html__( 'homepage.html file is created.', 'wsc' );
			$res_array = array(
				'success'  => true,
				'msg'      => $msg,
				'filePath' => $homepage_file,
			);
		}
		wsc_log( $res_array );
		return $res_array;
	}

	/**
	 * Add custom rewrite rules.
	 *
	 * @since   1.0.0
	 */
	public function wsc_add_custom_rewrite_rules() {
		// get registered rewrite rules.
		$rules = get_option( 'rewrite_rules', array() );

		// set the regex.
		$regex = '^sitemap\.html$';

		// add the rewrite rule.
		add_rewrite_rule( $regex, 'index.php?sitemap=1', 'top' );

		// maybe flush rewrite rules if it was not previously in the option.
		if ( ! isset( $rules[ $regex ] ) ) {
			flush_rewrite_rules();
		}
	}

	/**
	 * Add custom query vars.
	 *
	 * @param array $vars query vars array.
	 * @since   1.0.0
	 */
	public function wsc_custom_query_vars( $vars ) {
		$vars[] = 'sitemap';
		return $vars;
	}

	/**
	 * Show sitemap.
	 *
	 * @since   1.0.0
	 */
	public function wsc_custom_pages() {
		if ( get_query_var( 'sitemap' ) ) {
			$file_path = WP_CONTENT_DIR . '/wsc/sitemap.html';
		}

		if ( ! empty( $file_path ) ) {
			if ( file_exists( $file_path ) ) {
				include $file_path;
				exit();
			} else {
				global $wp_query;
				$wp_query->set_404();
				status_header( 404 );
				get_template_part( 404 );
				exit();
			}
		}
	}

	/**
	 * Add Hooks
	 * Adding hooks to handle WordPress functionality.
	 *
	 * @since   1.0.0
	 */
	private function add_hooks() {
		add_action(
			'wp_ajax_wsc_report_generate',
			array(
				$this,
				'wsc_report_generate_callback',
			)
		);
		add_action(
			'admin_init',
			array(
				$this,
				'wsc_schedule_hourly_action',
			)
		);
		add_action(
			'wsc_process_repost_data',
			array(
				$this,
				'wsc_process_repost_data',
			)
		);

		add_action(
			'init',
			array(
				$this,
				'wsc_add_custom_rewrite_rules',
			)
		);
		add_filter(
			'query_vars',
			array(
				$this,
				'wsc_custom_query_vars',
			)
		);
		add_action(
			'template_redirect',
			array(
				$this,
				'wsc_custom_pages',
			)
		);
	}
}
