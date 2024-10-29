<?php
/*
Plugin Name: Auto Tagger for Amazon
Description: Set your Amazon Affiliate Tracking ID (example-20) for your site just once and you'll never have to manually create an Amazon affiliate link again. Any time you link to Amazon from now on, regardless of country, it'll automatically tag the link with your ID so that you can receive commissions.
Version: 0.1
Requires at least: 5.0
Author: Bryan Hadaway
Author URI: https://calmestghost.com/
License: GPL
License URI: https://www.gnu.org/licenses/gpl.html
Text Domain: auto-tagger-for-amazon
*/

if ( !defined( 'ABSPATH' ) ) {
	http_response_code( 404 );
	die();
}

add_action( 'admin_menu', 'atfa_menu_link' );
function atfa_menu_link() {
	add_options_page( __( 'Amazon Tracking ID', 'auto-tagger-for-amazon' ), __( 'Amazon Tracking ID', 'auto-tagger-for-amazon' ), 'manage_options', 'auto-tagger-for-amazon', 'atfa_options_page' );
}

add_action( 'admin_init', 'atfa_admin_init' );
function atfa_admin_init() {
	add_settings_section( 'atfa-section', __( '', 'auto-tagger-for-amazon' ), 'atfa_section_callback', 'auto-tagger-for-amazon' );
	add_settings_field( 'atfa-field', __( '', 'auto-tagger-for-amazon' ), 'atfa_field_callback', 'auto-tagger-for-amazon', 'atfa-section' );
	register_setting( 'atfa-options', 'atfa_aff_id', 'sanitize_text_field' );
}

function atfa_section_callback() {
	echo __( '', 'auto-tagger-for-amazon' );
}

function atfa_field_callback() {
	$affid = get_option( 'atfa_aff_id' );
	echo '<input type="text" size="15" name="atfa_aff_id" value="' . esc_attr( $affid ) . '" placeholder="example-20" />';
}

function atfa_options_page() {
	?>
	<div id="atfa" class="wrap">
		<style>
		#atfa th{display:none}
		#atfa td{padding:15px 0}
		</style>
		<h1><?php _e( 'Amazon Tracking ID', 'auto-tagger-for-amazon' ); ?></h1>
		<form action="options.php" method="post">
			<?php settings_fields( 'atfa-options' ); ?>
			<?php do_settings_sections( 'auto-tagger-for-amazon' ); ?>
			<?php submit_button(); ?>
		</form>
	</div>
	<?php
}

add_action( 'wp_footer', 'atfa_tag_links', 100 );
function atfa_tag_links() {
	wp_enqueue_script( 'jquery' );
	$affid = get_option( 'atfa_aff_id' );
	?>
	<script>
	jQuery(document).ready(function ($) {
		$(function() {
			$("[href*=\"amazon\"]").attr("href", function(i, h) {
				return h + (h.indexOf("?") != -1 ? "&tag=<?php echo esc_html( $affid ); ?>" : "?tag=<?php echo esc_html( $affid ); ?>");
			});
		});
	});
	</script>
	<?php
}