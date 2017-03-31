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
 * Registers function to output analytics.
 */
function mitlib_analytics() {
	echo "<script>
  (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
  (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
  m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
  })(window,document,'script','//www.google-analytics.com/analytics.js','ga');
  ga('create', 'UA-1760176-1',  'auto', {'allowLinker': true});
  ga('require', 'linker');
  ga('linker:autoLink', ['libguides.mit.edu', 'libcal.mit.edu', 'libanswers.mit.edu'] );
  ga('send', 'pageview');
</script>";
}
add_action( 'wp_footer', 'mitlib\mitlib_analytics' );
