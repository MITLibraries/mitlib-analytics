<?php
/**
 * Plugin Name: MITlib Analytics
 * Plugin URI: https://github.com/MITLibraries/mitlib-analytics
 * Description: This plugin provides a thin implementation of Google Analytics tracking for use across domains.
 * Version: 1.1.0-beta2
 * Author: Matt Bernhardt for MIT Libraries
 * Author URI: https://github.com/MITLibraries
 * License: GPL2
 *
 * @package MITlib Analytics
 * @author Matt Bernhardt
 * @link https://github.com/MITLibraries/mitlib-analytics
 */

/**
 * MITlib Analytics is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 2 of the License, or
 * any later version.
 *
 * MITlib Analytics is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with MITlib Analytics. If not, see https://www.gnu.org/licenses/old-licenses/gpl-2.0.html.
 */

namespace mitlib;

// Don't call the file directly!
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Creates plugin options and settings
 */
function mitlib_analytics_init() {
	// Register a new setting for the GA property.
	register_setting( 'mitlib_analytics', 'mitlib_ga_property' );
	register_setting( 'mitlib_analytics', 'mitlib_ga_domains' );
	register_setting( 'mitlib_analytics', 'mitlib_mit_property' );

	// Register a section for libraries settings on the mitlib-analytics page.
	add_settings_section(
		'mitlib_analytics_general',
		__( 'Libraries settings', 'mitlib_analytics' ),
		'mitlib\mitlib_analytics_section_libraries',
		'mitlib-analytics'
	);

	// Register a section for MIT settings on the mitlib-analytics page.
	add_settings_section(
		'mitlib_analytics_mit',
		__( 'MIT settings', 'mitlib_analytics' ),
		'mitlib\mitlib_analytics_section_mit',
		'mitlib-analytics'
	);

	// Register a new field in the "mitlib_analytics_general" section, inside the "mitlib-analytics" page.
	add_settings_field(
		'mitlib_ga_property',
		__( 'Libraries Analytics Property', 'mitlib_analytics' ),
		'mitlib\mitlib_analytics_property_callback',
		'mitlib-analytics',
		'mitlib_analytics_general',
		array(
			'label_for' => 'mitlib_ga_property',
			'class' => 'mitlib_analytics_row',
		)
	);

	// Register the domain list field in the "mitlib_analytics_general" section, inside the "mitlib-analytics" page.
	add_settings_field(
		'mitlib_ga_domains',
		__( 'Linked Domains', 'mitlib_analytics' ),
		'mitlib\mitlib_analytics_domain_callback',
		'mitlib-analytics',
		'mitlib_analytics_general',
		array(
			'label_for' => 'mitlib_ga_domains',
			'class' => 'mitlib_analytics_row',
		)
	);

	// Register a new field in the "mitlib_analytics_mit" section, inside the "mitlib-analytics" page.
	add_settings_field(
		'mitlib_mit_property',
		__( 'MIT Analytics Property', 'mitlib_analytics' ),
		'mitlib\mitlib_analytics_mit_property_callback',
		'mitlib-analytics',
		'mitlib_analytics_mit',
		array(
			'label_for' => 'mitlib_mit_property',
			'class' => 'mitlib_analytics_row',
		)
	);
}
add_action( 'admin_init', 'mitlib\mitlib_analytics_init' );

/**
 * Create network admin settings page
 */
function mitlib_analytics_menu() {
	add_menu_page(
		'MITlib Analytics Options',
		'MITlib Analytics',
		'manage_options',
		'mitlib-analytics',
		'mitlib\mitlib_analytics_page_html'
	);
}
add_action( 'network_admin_menu', 'mitlib\mitlib_analytics_menu' );

/**
 * Section rendering callback
 */
function mitlib_analytics_section_libraries() {
	?>
	<p>These settings control the Libraries-level analytics information.</p>
	<?php
}

/**
 * Section rendering callback
 */
function mitlib_analytics_section_mit() {
	?>
	<p>This controls whether the MIT-level property is used on this site.</p>
	<?php
}

/**
 * Field rendering callback: Libraries GA Property
 */
function mitlib_analytics_property_callback() {
	// Get the settings value.
	$options = get_site_option( 'mitlib_ga_property' );
	?>
	<input
		type="text"
		name="<?php echo esc_attr( 'mitlib_ga_property' ); ?>"
		value="<?php echo esc_attr( $options ); ?>"
		id="<?php echo esc_attr( 'mitlib_ga_property' ); ?>"
		size="20">
	<p>If you aren't sure what value to use, please contact UX/Web Services.</p>
	<?php
}

/**
 * Field rendering callback: Domain list
 */
function mitlib_analytics_domain_callback() {
	// Get the settings value.
	$options = get_site_option( 'mitlib_ga_domains' );
	?>
	<input
		type="text"
		name="<?php echo esc_attr( 'mitlib_ga_domains' ); ?>"
		value="<?php echo esc_attr( $options ); ?>"
		id="<?php echo esc_attr( 'mitlib_ga_domains' ); ?>"
		size="60">
	<p>This should be the comma-separated list of domains that report together.</p>
	<p>Read more about
		<a href="https://developers.google.com/analytics/devguides/collection/analyticsjs/cross-domain">
			cross-domain tracking
		</a>
		and
		<a href="https://developers.google.com/analytics/devguides/collection/analyticsjs/linker">
			the linker plugin
		</a>.
	</p>
	<?php
}

/**
 * Field rendering callback: MIT GA Property
 */
function mitlib_analytics_mit_property_callback() {
	// Get the settings value.
	$options = get_site_option( 'mitlib_mit_property' );
	?>
	<input
		type="text"
		name="<?php echo esc_attr( 'mitlib_mit_property' ); ?>"
		value="<?php echo esc_attr( $options ); ?>"
		id="<?php echo esc_attr( 'mitlib_mit_property' ); ?>"
		size="20">
	<p>If you aren't sure what value to use, please contact UX/Web Services.</p>
	<?php
}

/**
 * Options page for settings form
 */
function mitlib_analytics_page_html() {
	// Check user capabilities.
	if ( ! current_user_can( 'manage_options' ) ) {
		return;
	}

	// Maybe handle post actions here?
	$action = filter_input( INPUT_POST, 'action' );
	if ( ! empty( $action ) ) {
		// Check for validity.
		echo '<div class="updated"><p>Analytics settings updated.</p></div>';
		check_admin_referer( 'custom_nonce_action', 'custom_nonce_field' );

		// Update settings.
		if ( 'update' == filter_input( INPUT_POST, 'action' ) ) {
			// Set default values.
			$mitlib_ga_property = '';
			$mitlib_ga_domains = '';
			$mitlib_mit_property = '';
			// GA Property.
			if ( filter_input( INPUT_POST, 'mitlib_ga_property' ) ) {
				$mitlib_ga_property = sanitize_text_field(
					wp_unslash( filter_input( INPUT_POST, 'mitlib_ga_property' ) )
				);
			}
			// GA domain list.
			if ( filter_input( INPUT_POST, 'mitlib_ga_domains' ) ) {
				$mitlib_ga_domains = sanitize_text_field(
					wp_unslash( filter_input( INPUT_POST, 'mitlib_ga_domains' ) )
				);
			}
			// MIT Property.
			if ( filter_input( INPUT_POST, 'mitlib_mit_property' ) ) {
				$mitlib_mit_property = sanitize_text_field(
					wp_unslash( filter_input( INPUT_POST, 'mitlib_mit_property' ) )
				);
			}
			update_site_option( 'mitlib_ga_property', $mitlib_ga_property );
			update_site_option( 'mitlib_ga_domains', $mitlib_ga_domains );
			update_site_option( 'mitlib_mit_property', $mitlib_mit_property );
		}
	}

	// Build the form.
	?>
	<div class="wrap">
		<h1><?php echo esc_html( get_admin_page_title() ); ?></h1>
		<form method="post" action="admin.php?page=mitlib-analytics">
			<?php
			// Nonce field, because Wordpress can't manage to do this itself.
			wp_nonce_field( 'custom_nonce_action', 'custom_nonce_field' );
			// Output security fields for this form.
			settings_fields( 'mitlib_analytics' );
			// Output settings sections and their fields.
			do_settings_sections( 'mitlib-analytics' );
			// Output the form submit button.
			submit_button( 'Save Settings' );
			?>
		</form>
	</div>
	<?php
}

/**
 * View function that outputs the GA code on public pages.
 */
function mitlib_analytics_view() {
	$domains = explode( ',', get_site_option( 'mitlib_ga_domains' ) );
	$mit = get_site_option( 'mitlib_mit_property' );
	echo "<script>
	(function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
	(i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
	m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
	})(window,document,'script','//www.google-analytics.com/analytics.js','ga');
	ga('create', '" . esc_html( get_site_option( 'mitlib_ga_property' ) ) . "',  'auto', {'allowLinker': true});
	ga('require', 'linker');
	ga('linker:autoLink', [";
	foreach ( $domains as &$item ) {
		echo "'" . esc_attr( trim( $item ) ) . "',";
	}
	echo "]);\n";
	if ( $mit ) {
		echo "ga('create', '" . esc_html( $mit ) . "', {'name':'mitsitewide'});
		ga('mitsitewide.send','pageview');\n";
	}
	echo "ga('send', 'pageview');
	</script>\n";
}
add_action( 'wp_footer', 'mitlib\mitlib_analytics_view' );
