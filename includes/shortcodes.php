<?php

defined( 'ABSPATH' )
	or die( 'No direct load ! ' );

function wpm_social_shortcode( $atts ) {

    if(get_option('wp_maintenance_settings_socialnetworks')) { extract(get_option('wp_maintenance_settings_socialnetworks')); }
    $paramSocial = get_option('wp_maintenance_settings_socialnetworks');

    $paramList = get_option('wp_maintenance_list_socialnetworks');
    $countSocial = wpm_array_value_count($paramList);
    
    // Si on est en mobile on réduit les icones
    if ( wp_is_mobile() ) {
        $paramSocial['size'] = 32;
    }

	// Attributes
	extract( shortcode_atts(
		array(
			'size' => 64,
            'enable' => 0
		), $atts )
	);
    if($paramSocial['theme']!='') {
        $srcIcon = get_stylesheet_directory_uri().'/'.esc_html($paramSocial['theme']).'/';
        $iconSize = 'width='.$paramSocial['size'];
    } else {
        $srcIcon = plugin_dir_url( __DIR__ ).'socialicons/'.$paramSocial['style'].'/'.$paramSocial['size'].'/';
        $iconSize = '';
    }
    $contentSocial = '';
    if( isset($paramSocial['enable']) && $paramSocial['enable']==1 && $countSocial>=1) {
         $contentSocial .= '<div id="wpm-social-footer" class="wpm_social"><ul class="wpm_horizontal">';
            foreach($paramList as $socialName=>$socialUrl) {
                if($socialUrl!='') {
                    $contentSocial .= '<li><a href="'.esc_url($socialUrl).'" target="_blank"><img src="'.esc_url($srcIcon.$socialName).'.png" alt="'.esc_html($paramSocial['texte']).' '.ucfirst(esc_html($socialName)).'" '.$iconSize.' title="'.esc_html($paramSocial['texte']).' '.ucfirst(esc_html($socialName)).'" /></a></li>';
                }
            }
         $contentSocial .='</ul></div>';
        return $contentSocial;
     } else {
        // Code
        return '';
    }
}
add_shortcode( 'wpm_social', 'wpm_social_shortcode' );