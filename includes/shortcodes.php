<?php

defined( 'ABSPATH' )
	or die( 'No direct load ! ' );

function wpm_analytics_shortcode( $atts ) {

    if(get_option('wp_maintenance_settings')) { extract(get_option('wp_maintenance_settings')); }
    $paramMMode = get_option('wp_maintenance_settings');

    $nameServer = '';
    if( isset($_SERVER['SERVER_NAME']) ) {
        $nameServer = $_SERVER['SERVER_NAME'];
    }
    
    // Attributes
    extract( shortcode_atts(
        array(
            'enable' => 0,
            'code' => $paramMMode['code_analytics'],
            'domain' => ''.$nameServer.''
        ), $atts )
    );

    if( isset($enable) && $enable==1 && $code!='') {
        return "
<script async src=\"https://www.googletagmanager.com/gtag/js?id=".$code."\"></script>
<script>
  window.dataLayer = window.dataLayer || [];
  function gtag(){dataLayer.push(arguments)};
  gtag('js', new Date());

  gtag('config', ".$code.");
</script>";
    } else {
        // Code
        return '<!-- no analytics -->';
    }
}
add_shortcode( 'wpm_analytics', 'wpm_analytics_shortcode' );

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
        $srcIcon = get_stylesheet_directory_uri().'/'.$paramSocialOption['theme'].'/';
        $iconSize = 'width='.$paramSocialOption['size'];
    } else {
        $srcIcon = WP_CONTENT_URL.'/plugins/wp-maintenance/socialicons/'.$paramSocialOption['style'].'/'.$paramSocialOption['size'].'/';
        $iconSize = '';
    }
    if( isset($paramSocialOption['enable']) && $paramSocialOption['enable']==1 && $countSocial>=1) {
         $contentSocial .= '<div id="wpm-social-footer" class="wpm_social"><ul class="wpm_horizontal">';
            foreach($paramSocial as $socialName=>$socialUrl) {
                if($socialUrl!='') {
                    $contentSocial .= '<li><a href="'.$socialUrl.'" target="_blank"><img src="'.$srcIcon.$socialName.'.png" alt="'.$paramSocialOption['texte'].' '.ucfirst($socialName).'" '.$iconSize.' title="'.$paramSocialOption['texte'].' '.ucfirst($socialName).'" /></a></li>';
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