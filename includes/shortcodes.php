<?php

defined( 'ABSPATH' )
	or die( 'No direct load ! ' );

function wpm_social_shortcode( $atts ) {

    if(get_option('wp_maintenance_social')) { extract(get_option('wp_maintenance_social')); }
    $paramSocial = get_option('wp_maintenance_social');
    $paramSocialOption = get_option('wp_maintenance_social_options');
    $countSocial = wpm_array_value_count($paramSocial);
    $contentSocial = '';
    // Si on est en mobile on réduit les icones
    if ( wp_is_mobile() ) {
        $paramSocialOption['size'] = 32;
    }
        
	// Attributes
	extract( shortcode_atts(
		array(
			'size' => 64,
            'enable' => 0
		), $atts )
	);
    if($paramSocialOption['theme']!='') {
        $srcIcon = get_stylesheet_directory_uri().'/'.esc_html($paramSocialOption['theme']).'/';
        $iconSize = 'width='.$paramSocialOption['size'];
    } else {
        $srcIcon = WP_CONTENT_URL.'/plugins/wp-maintenance/socialicons/'.$paramSocialOption['style'].'/'.$paramSocialOption['size'].'/';
        $iconSize = '';
    }
    if( isset($paramSocialOption['enable']) && $paramSocialOption['enable']==1 && $countSocial>=1) {
         $contentSocial .= '<div id="wpm-social-footer" class="wpm_social"><ul class="wpm_horizontal">';
            foreach($paramSocial as $socialName=>$socialUrl) {
                if($socialUrl!='') {
                    $contentSocial .= '<li><a href="'.esc_url($socialUrl).'" target="_blank"><img src="'.esc_url($srcIcon.$socialName).'.png" alt="'.esc_html($paramSocialOption['texte']).' '.ucfirst(esc_html($socialName)).'" '.$iconSize.' title="'.esc_html($paramSocialOption['texte']).' '.ucfirst(esc_html($socialName)).'" /></a></li>';
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