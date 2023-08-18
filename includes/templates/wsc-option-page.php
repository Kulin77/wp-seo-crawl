<?php
/**
 * Template File for option page.
 *
 * @since    1.0.0
 * @package  WSC\Plugin
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

?>

<div class="wrap wsc-wrap">
	<h1><?php esc_html_e( 'WP Seo Crawl', 'wsc' ); ?></h1>
	<div class="msg-wrap"></div>
	<div class="table-content-wrap">
		<div class="report-generate-wrap">
			<table class="form-table">
				<tbody>
					<tr>
						<th><?php esc_html_e( 'Generate repost for internal hyperlinks', 'wsc' ); ?></th>
						<td class="d-flex">
							<input type="button" id="wsc-generate" class="button button-primary wsc-generate" value="<?php esc_attr_e( 'Generate', 'wsc' ); ?>">
							<p class="loader"></p>
						</td>
					</tr>
				</tbody>
			</table>
		</div>
		<div class="hyperlinks-table-wrap">
			<table id="hyperlinks-data-table" class="cell-border hyperlinks-data-table" style="width:100%">
				<thead>
					<tr>
						<th><?php esc_html_e( 'No.', 'wsc' ); ?></th>
						<th><?php esc_html_e( 'Internal hyperlinks', 'wsc' ); ?></th>
					</tr>
				</thead>
				<tbody>
					<?php $links_data = get_transient( 'wsc_links_data' ); ?>
					<?php if ( ! empty( $links_data ) ) { ?>
						<?php foreach ( $links_data as $link ) {  // phpcs:ignore ?>
							<tr>
								<td></td>
								<td><a href="<?php echo esc_url( $link ); ?>" target="_blank"><?php echo esc_url( $link ); ?></a></td>
							</tr>
						<?php } ?>
					<?php } ?>
				</tbody>
			</table>
		</div>
	</div>
</div>
