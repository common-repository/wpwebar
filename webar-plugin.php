<?php

/**
 * @package WebAR
 */
/*
Plugin Name: WebAR
Plugin URI: https://wpwebar.com/documentation
Description: Use of Augmented Reality functionality on your website!
Version: 1.0.15
Author: WebAR
Author URI: https://wpwebar.com
License: WPWARV1 or later
Text Domain: wpwebar
*/

/*
This program is free software; you can redistribute it and/or
modify it under the terms of the GNU General Public License
as published by the Free Software Foundation; either version 2
of the License, or (at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

Copyright 2020-2021 WebAR
*/

defined('ABSPATH') or die('Hey, you can\t access this file, you silly human!');

define( 'WEBAR_VERSION', '1.0.15' );
define( 'WEBAR__MINIMUM_WP_VERSION', '4.7' );
define( 'WEBAR__PLUGIN_DIR', plugin_dir_path( __FILE__ ) );
define( 'WEBAR_DELETE_LIMIT', 100000 );

// Require needed files
require_once( WEBAR__PLUGIN_DIR . 'class.webar.php' );
require_once( WEBAR__PLUGIN_DIR . 'class.webar-woocommerce.php' );


add_action( 'init', array( 'WebAR', 'init' ) );

/**
 * Check if WooCommerce is active
 **/
if ( in_array( 'woocommerce/woocommerce.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) ) ) {
    // Put your plugin code here
    add_action( 'init', array( 'WebAR_WooCommerce', 'init' ) );
}