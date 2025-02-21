<?php
/*
 * Plugin Name: WP Maintenance
 * Plugin URI: https://fr.wordpress.org/plugins/wp-maintenance/
 * Description: The WP Maintenance plugin allows you to put your website on the waiting time for you to do maintenance or launch your website. Personalize this page with picture, countdown...
 * Author: Florent Maillefaud
 * Author URI: https://madeby.restezconnectes.fr
 * Version: 6.1.9.6
 * Text Domain: wp-maintenance
 * Domain Path: /languages/
 */

/*  Copyright 2007-2024 Florent Maillefaud (email: contact at restezconnectes.fr)

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation; either version 2 of the License, or
    (at your option) any later version.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA
*/

defined( 'ABSPATH' )
	or die( 'No direct load ! ' );

define( 'WPM_DIR', plugin_dir_path( __FILE__ ) );
define( 'WPM_URL', plugins_url('/', __FILE__) );
define( 'WPM_PLUGIN_BASENAME', plugin_basename( __FILE__ ) );
define( 'WPM_PLUGIN_URL', plugins_url().'/'.strtolower('wp-maintenance').'/');
define( 'WPM_ICONS_URL', plugins_url().'/'.strtolower('wp-maintenance').'/socialicons/');
define( 'WPM_ADMIN_URL', admin_url().'admin.php?page=wp-maintenance'); //we assume the admin url is absolute with at least one querystring

if( !defined( 'WPM_VERSION' )) { define( 'WPM_VERSION', '6.1.9.6' ); }

require WPM_DIR . 'classes/wp-maintenance.php';
require WPM_DIR . 'classes/countdown.php';
require WPM_DIR . 'includes/functions.php';
require WPM_DIR . 'includes/shortcodes.php';

add_action( 'plugins_loaded', '_wpm_load' );
function _wpm_load() {
	$wp_maintenance = new WP_maintenance();
	$wp_maintenance->__construct();
}

// Enable localization
add_action( 'init', '_wpm_load_translation' );
function _wpm_load_translation() {
    load_plugin_textdomain( 'wp-maintenance', false, dirname( plugin_basename( __FILE__ ) ) . '/languages/' );
}

register_activation_hook( __FILE__, array( 'WP_maintenance', 'wpm_dashboard_install' ) );
register_deactivation_hook( __FILE__, array( 'WP_maintenance', 'wpm_dashboard_remove' ) );
register_uninstall_hook( __FILE__, array( 'WP_maintenance', 'wpm_dashboard_remove' ) );