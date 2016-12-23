<?php

defined( 'ABSPATH' )
	or die( 'No direct load ! ' );
/**
 * Désinstallation du plugin WP Maintenance
 */
function wpm_uninstall() {  
    //if(get_option('wp_maintenance_settings')) { delete_option('wp_maintenance_settings'); }
    //if(get_option('wp_maintenance_version')) { delete_option('wp_maintenance_version'); }
    //if(get_option('wp_maintenance_style')) {  delete_option('wp_maintenance_style'); }
    //delete_option('wp_maintenance_limit');
    delete_option('wp_maintenance_active');
    //if(get_option('wp_maintenance_social')) {  delete_option('wp_maintenance_social'); }
    //if(get_option('wp_maintenance_social_options')) {  delete_option('wp_maintenance_social_options'); }

}
register_deactivation_hook(__FILE__, 'wpm_uninstall');
