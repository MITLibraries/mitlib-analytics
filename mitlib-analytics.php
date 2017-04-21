<?php
/**
 * Plugin Name: MITlib Analytics
 * Plugin URI: https://github.com/MITLibraries/mitlib-analytics
 * Description: This plugin provides a thin implementation of Google Analytics tracking for use across domains.
 * Version: 0.1.0
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

	// Register a new section on the mitlib-analytics page.
	add_settings_section(
		'mitlib_analytics_general',
		__( 'General settings', 'mitlib_analytics' ),
		'mitlib\mitlib_analytics_section',
		'mitlib-analytics'
	);

	// Register a new field in the "mitlib_analytics_general" section, inside the "mitlib-analytics" page.
	add_settings_field(
		'mitlib_ga_property',
		__( 'Google Analytics Property', 'mitlib_analytics' ),
		'mitlib\mitlib_analytics_field_cb',
		'mitlib-analytics',
		'mitlib_analytics_general',
		array(
			'label_for' => 'mitlib_ga_property',
			'class' => 'mitlib_analytics_row',
		)
	);
}
add_action( 'admin_init', 'mitlib\mitlib_analytics_init' );

/**
 * Create options page
 */
function mitlib_analytics_settings() {
	add_options_page(
		'MITlib Analytics Options',
		'MITlib Analytics',
		'manage_options',
		'mitlib-analytics',
		'mitlib\mitlib_analytics_page_html'
	);
}
add_action( 'admin_menu', 'mitlib\mitlib_analytics_settings' );

/**
 * Section rendering callback
 */
function mitlib_analytics_section() {
	?>
	<p>These settings allow Google Analytics to work correctly for this server.</p>
	<?php
}

/**
 * Field rendering callback
 */
function mitlib_analytics_field_cb() {
	// Get the settings value.
	$options = get_option( 'mitlib_ga_property' );
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
 * Options page for settings form
 */
function mitlib_analytics_page_html() {
	// Check user capabilities.
	if ( ! current_user_can( 'manage_options' ) ) {
		return;
	}

	// Build the form.
	?>
	<div class="wrap">
		<h1><?php echo esc_html( get_admin_page_title() ); ?></h1>
		<form method="post" action="options.php">
			<?php
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
	echo "<script>
	(function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
	(i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
	m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
	})(window,document,'script','//www.google-analytics.com/analytics.js','ga');
	ga('create', '" . esc_html( get_option( 'mitlib_ga_property' ) ) . "',  'auto', {'allowLinker': true});
	ga('require', 'linker');
	ga('linker:autoLink', ['libguides.mit.edu', 'libcal.mit.edu', 'libanswers.mit.edu'] );
	ga('send', 'pageview');
</script>";
}
add_action( 'wp_footer', 'mitlib\mitlib_analytics_view' );
