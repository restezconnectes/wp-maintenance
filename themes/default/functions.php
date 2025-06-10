<?php

// Template Tags
function wpm_title_seo() {

    // Récupère les paramètres sauvegardés
    if(get_option('wp_maintenance_settings_seo')) { extract(get_option('wp_maintenance_settings_seo')); }
    $o = get_option('wp_maintenance_settings_seo');

    $output = get_bloginfo( 'name', 'display' );

    if ( (isset($o['enable_seo']) && $o['enable_seo']==1) && $o['seo_title']!='' ) {
        $output = esc_html( $o['seo_title'] );
    }

    return $output;
}

function wpm_title() {
    
    // Récupère les paramètres sauvegardés
    if(get_option('wp_maintenance_settings')) { extract(get_option('wp_maintenance_settings')); }
    $o = get_option('wp_maintenance_settings');

    $output = '';

   if(!empty($o['titre_maintenance']) ) {
        $output = esc_html( stripslashes($o['titre_maintenance']) );
    } 
    return $output;
}

function wpm_metadescription() {
    // Récupère les paramètres sauvegardés
    if(get_option('wp_maintenance_settings_seo')) { extract(get_option('wp_maintenance_settings_seo')); }
    $o = get_option('wp_maintenance_settings_seo');

    $output = '<meta name="description" content="'.get_bloginfo( 'description', 'display' ).'">';

    if ( (isset($o['enable_seo']) && $o['enable_seo']==1) && $o['seo_description']!='' ) {
        $output = '<meta name="description" content="'.esc_attr( $o['seo_description'] ).'">';
    }

    return $output;
}

function wpm_footer_text() {

    // Récupère les paramètres sauvegardés
    if(get_option('wp_maintenance_settings_footer')) { extract(get_option('wp_maintenance_settings_footer')); }
    $o = get_option('wp_maintenance_settings_footer');

    $output = '';

    if(isset($o['enable_footer']) && $o['enable_footer'] == 1) {

        $output .= '<div class="footer-basic">
    <footer>
        <p class="copyright">';

       if(isset($o['text_bt_maintenance']) && $o['text_bt_maintenance']!='') {
            // SÉCURITÉ : Application de wp_kses pour filtrer le HTML dangereux
            $output .= wp_kses(nl2br(stripslashes($o['text_bt_maintenance'])), wpm_autorizeHtml());
        }
       if((isset($o['add_wplogin']) && $o['add_wplogin']==1) && (isset($o['add_wplogin_title']) && $o['add_wplogin_title']!='') ) {
            $output .= '<br /><br /><a href="'.get_admin_url().'">'.str_replace('#DASHBOARD', ' '.__('Dashboard', 'wp-maintenance'), esc_html($o['add_wplogin_title'])).'</a>';

        }

        $output .= '</p>
        </footer>
    </div>';

    }

    return $output;
}

function wpm_favicon() {
    // Récupère les paramètres sauvegardés
    if(get_option('wp_maintenance_settings_seo')) { extract(get_option('wp_maintenance_settings_seo')); }
    $o = get_option('wp_maintenance_settings_seo');

    $output = '';

    if (!empty( $o['favicon']) ) {
        $output .= "<!-- Favicon -->\n";
        $output .= '<link href="'.esc_attr($o['favicon']).'" rel="shortcut icon" type="image/x-icon" />';
    }

    return $output;
}

function wpm_head() {

    // Récupère les paramètres sauvegardés
    if(get_option('wp_maintenance_settings_colors')) { extract(get_option('wp_maintenance_settings_colors')); }
    $o = get_option('wp_maintenance_settings_colors');
    
    // Récupère les paramètres sauvegardés
    if(get_option('wp_maintenance_settings_options')) { extract(get_option('wp_maintenance_settings_options')); }
    $wpoptions = get_option('wp_maintenance_settings_options');

    if(isset($wpoptions['remove_googlefonts']) && $wpoptions['remove_googlefonts']==1) {
        $output = '';
    } else {
        $output = "<!-- Add Google Fonts -->\n";
        $output .= '<link rel="preconnect" href="https://fonts.googleapis.com">';
        $output .= '<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>';
        $arrayfonts = array($o['font_title'], $o['font_text'], $o['font_text_bottom'], $o['font_cpt']);
        $uniqueArrayfonts = array_unique($arrayfonts);        
        $addFont = '';
        foreach ($uniqueArrayfonts as $keyFont => $valFont) {

            $listFonts = wpm_getFontsTab();
            $searchFont = str_replace('+', ' ', $valFont);
            if ( array_key_exists($searchFont, $listFonts) ) {
                $valFont = str_replace(' ', '+', $valFont);
                $addFont .= $valFont.'|';
            }
        }
        /*$output .= '<style>
        @import url(\'https://fonts.googleapis.com/css?'.substr($addFont, 0,-1).'\');
        </style>';*/
        $output .= '<link rel="stylesheet" href="https://fonts.googleapis.com/css?family='.substr($addFont, 0, -1).'&display=swap">';
    }
    return $output;
}

function wpm_text() {
    
    // Récupère les paramètres sauvegardés
    if(get_option('wp_maintenance_settings')) { extract(get_option('wp_maintenance_settings')); }
    $o = get_option('wp_maintenance_settings');

    $output = '';

    if(!empty($o['text_maintenance']) ) {
        //$text = nl2br(stripslashes($o['text_maintenance']));
        $text = str_replace("\r\n", "<br />", stripslashes($o['text_maintenance']));
        $output = wp_kses(trim($text), wpm_autorizeHtml());
    }
    return $output;
}

function wpm_logo() {

    // Récupère les paramètres sauvegardés
    if(get_option('wp_maintenance_settings_picture')) { extract(get_option('wp_maintenance_settings_picture')); }
    $o = get_option('wp_maintenance_settings_picture');

    $output = '';

    if ( !empty( $o['image'] ) ) {
       if(empty($o['image_width']) ) { $o['image_width'] = 450; }
       if(empty($o['image_height']) ) { $o['image_height'] = 450; }
        // Échapper les valeurs pour éviter XSS et s'assurer qu'elles sont numériques
        $width = intval($o['image_width']);
        $height = intval($o['image_height']);
        
        // Validation supplémentaire des dimensions
        if ($width <= 0 || $width > 5000) $width = 450;
        if ($height <= 0 || $height > 5000) $height = 450;
        
        $output .= "<div id='logo'><img id='wpm-image' src='".esc_url($o['image'])."' width='".esc_attr($width)."px' height='".esc_attr($height)."px' alt='".get_bloginfo( 'name', 'display' )." ".get_bloginfo( 'description', 'display' )."' title='".get_bloginfo( 'name', 'display' )." ".get_bloginfo( 'description', 'display' )."'></div>";
    }

    return  $output;
}

function wpm_customcss() {

    // Récupère les paramètres sauvegardés
    if(get_option('wp_maintenance_settings')) { extract(get_option('wp_maintenance_settings')); }
    $o = get_option('wp_maintenance_settings');
    
    // Récupère les paramètres sauvegardés des couleurs
    if(get_option('wp_maintenance_settings_colors')) { extract(get_option('wp_maintenance_settings_colors')); }
    $colors = get_option('wp_maintenance_settings_colors');

    // Récupère les paramètres sauvegardés des images
    if(get_option('wp_maintenance_settings_picture')) { extract(get_option('wp_maintenance_settings_picture')); }
    $picture = get_option('wp_maintenance_settings_picture');

    $oo = get_option('wp_maintenance_settings_socialnetworks');

    // CSS
    $output = '<style type="text/css">';
    ob_start();
    
    $addStyleGeneral = '';

    /* Définition des couleurs par défault */
   if(!isset($colors['color_bg']) || $colors['color_bg']=="") { $colors['color_bg'] = "#f1f1f1"; }
   if(!isset($colors['color_txt']) || $colors['color_txt']=="") { $colors['color_txt'] = "#888888"; }

    /* Traitement de la feuille de style */
    $styleRemplacements = array (
        "#_COLORTXT" => $colors['color_txt'],
        "#_COLORBG" => $colors['color_bg'],
        "#_COLORCPTBG" => $colors['color_cpt_bg'],
        "#_DATESIZE" => $colors['date_cpt_size'],
        "#_COLORCPT" => $colors['color_cpt'],
        "#_COLOR_BG_BT" => $colors['color_bg_bottom'],
        "#_COLOR_TXT_BT" => $colors['color_text_bottom'],
        "#_COLORHEAD" => $colors['color_bg_header'],
    );
    $remplaceStyle = str_replace(array_keys($styleRemplacements), array_values($styleRemplacements), get_option('wp_maintenance_settings_css'));
    $output .= wpm_compress($remplaceStyle);
    

    /* Si on a activé un motif */
    if(isset($picture['b_enable_image']) && $picture['b_enable_image'] == 2 ) {

        $addStyleGeneral .= 'body {';
        if( isset($picture['b_pattern']) && $picture['b_pattern']>=1) {
            // SÉCURITÉ : Validation que b_pattern est bien numérique
            $pattern_value = intval($picture['b_pattern']);
            if ($pattern_value > 0 && $pattern_value <= 12) {
                $pattern_url = get_template_directory_uri().'/'.get_option('template').'/assets/images/pattern/pattern'.$pattern_value.'.png';
                $addStyleGeneral .= 'background-image: url('.esc_url($pattern_url).');';
            }
        }
        $addStyleGeneral .= 'background-repeat: repeat;
        background-color: '.$colors['color_bg'].';}';
        
    }

    /* Si on a une image de fond */
   if(isset($picture['b_enable_image']) && $picture['b_enable_image'] == 1 ) {

       if(isset($picture['b_image']) && $picture['b_image'] ) {

           if(empty($picture['b_repeat_image']) ) { $picture['b_repeat_image'] = 'repeat'; }
           if(isset($picture['b_fixed_image']) && $picture['b_fixed_image']==1 ) {
                $picture['b_fixed_image'] = 'fixed;';
            } else {
                $picture['b_fixed_image'] = '';
            }
            $addStyleGeneral .= 'body {display: grid!important;background:url('.esc_url($picture['b_image']).') '.esc_attr($picture['b_repeat_image']).' '.esc_attr($picture['b_fixed_image']).'top center;background-size: cover;
            -webkit-background-size: cover;-moz-background-size: cover;-o-background-size: cover;background-color: '.esc_attr($colors['color_bg']).';background-position: center;}';

           if(isset($picture['b_opacity_image']) ) {
                $addStyleGeneral .= '#main { background-color: rgba(0,0,0,'.esc_attr($picture['b_opacity_image']).'); }';
            } 
        }

    } 

   if(isset($picture['b_enable_image']) && $picture['b_enable_image']==0 ) {
        $addStyleGeneral .= 'body {background-color: '.$colors['color_bg'].';}';
    } 

   if(isset($colors['color_bg_header']) && $colors['color_bg_header']!='') {
        $addStyleGeneral .= 'header { background-color:'.$colors['color_bg_header'].';}';
    }
    $addStyleGeneral .= '.wpm_social_icon {float:left;width:'.esc_attr($oo['size']).'px;margin:0px 5px auto;}.wpm_social ul {margin: 10px 0;max-width: 100%;padding: 0;text-align: '.esc_attr($oo['align']).';}';

    /* Si container activé */
   if(isset($colors['container_active']) && $colors['container_active'] == 1 ) {

       if(empty($colors['container_opacity']) ) { $colors['container_opacity'] = 0.5; }
       if(empty($colors['container_width']) ) { $colors['container_width'] = 80; }
       if(empty($colors['container_color']) ) { $colors['container_color'] = '#ffffff'; }
       if(isset($colors['container_color']) ) { $paramRGBColor = wpm_hex2rgb($colors['container_color']); }
        $addStyleGeneral .= '#sscontent {background-color: rgba('.esc_attr($paramRGBColor['rouge']).','.esc_attr($paramRGBColor['vert']).','.esc_attr($paramRGBColor['bleu']).', '.esc_attr($colors['container_opacity']).');padding:0.8em;margin-left:auto;margin-right:auto;width:'.esc_attr($colors['container_width']).'%;}';

    }

    $addStyleGeneral .= '.wpm_newletter {';
   if(isset($colors['newletter_size']) ) { $addStyleGeneral .= 'font-size:'.esc_attr($colors['newletter_size']).'px;'; } 
   if(isset($colors['newletter_font_style']) ) { $addStyleGeneral .= 'font-style:'.esc_attr($colors['newletter_font_style']).';'; } 
   if(isset($colors['newletter_font_weigth']) ) { $addStyleGeneral .= 'font-weight:'.esc_attr($colors['newletter_font_weigth']).';'; } 
   if(isset($colors['newletter_font_text']) ) { $addStyleGeneral .= 'font-family:'.wpm_format_font(wpm_fonts($colors['newletter_font_text'])).';'; } 
    $addStyleGeneral .= '}
    h3 {';
   if(isset($colors['font_title']) ) { $addStyleGeneral .= 'font-family: '.wpm_format_font(wpm_fonts($colors['font_title'])).';'; }
   if(isset($colors['font_title_size']) ) { $addStyleGeneral .= 'font-size: '.esc_attr($colors['font_title_size']).'px;'; }
   if(isset($colors['font_title_style']) ) { $addStyleGeneral .= 'font-style: '.esc_attr($colors['font_title_style']).';'; }
   if(isset($colors['font_title_weigth']) ) { $addStyleGeneral .= 'font-weight: '.esc_attr($colors['font_title_weigth']).';'; }
   if(isset($colors['color_title']) ) { $addStyleGeneral .= 'color:'.esc_attr($colors['color_title']).';'; }
    $addStyleGeneral .= 'line-height: 100%;text-align:center;margin:0.5em auto;
    }
    p {';
   if(isset($colors['font_text']) ) { $addStyleGeneral .= 'font-family: '.wpm_format_font(wpm_fonts($colors['font_text'])).';'; }
   if(isset($colors['font_text_size']) ) { $addStyleGeneral .= 'font-size: '.esc_attr($colors['font_text_size']).'px;'; }
   if(isset($colors['font_text_style']) ) { $addStyleGeneral .= 'font-style: '.esc_attr($colors['font_text_style']).';'; }
   if(isset($colors['font_text_weigth']) ) { $addStyleGeneral .= 'font-weight: '.esc_attr($colors['font_text_weigth']).';'; }
   if(isset($colors['color_txt']) ) { $addStyleGeneral .= 'color:'.esc_attr($colors['color_txt']).';'; }
    $addStyleGeneral .= 'line-height: 100%;text-align:center;margin:0.5em auto;padding-left:2%;padding-right:2%;
    }
    footer  {';
   if(isset($colors['font_text_bottom']) ) { $addStyleGeneral .= 'font-family: '.wpm_format_font(wpm_fonts($colors['font_text_bottom'])).';'; }
   if(isset($colors['font_bottom_style']) ) { $addStyleGeneral .= 'font-style: '.esc_attr($colors['font_bottom_style']).';'; }
   if(isset($colors['font_bottom_size']) ) { $addStyleGeneral .= 'font-size: '.esc_attr($colors['font_bottom_size']).'px;'; }
   if(isset($colors['font_bottom_weigth']) ) { $addStyleGeneral .= 'font-weight: '.esc_attr($colors['font_bottom_weigth']).';'; }
   if(isset($colors['color_text_bottom']) ) { $addStyleGeneral .= 'color: '.esc_attr($colors['color_text_bottom']).';'; }
   if(isset($colors['color_bg_bottom']) ) { $addStyleGeneral .= 'background:'.esc_attr($colors['color_bg_bottom']).';'; }
    $addStyleGeneral .= 'text-decoration:none;
    }
    footer a:link {';
   if(isset($colors['color_text_bottom']) ) { $addStyleGeneral .= 'color:'.esc_attr($colors['color_text_bottom']).';'; }
   if(isset($colors['font_bottom_size']) ) { $addStyleGeneral .= 'font-size: '.esc_attr($colors['font_bottom_size']).'px;'; }
    $addStyleGeneral .= 'text-decoration:none;
    }
    footer a:visited {';
   if(isset($colors['color_text_bottom']) ) { $addStyleGeneral .= 'color:'.esc_attr($colors['color_text_bottom']).';'; }
   if(isset($colors['font_bottom_size']) ) { $addStyleGeneral .= 'font-size: '.esc_attr($colors['font_bottom_size']).'px;'; }
    $addStyleGeneral .= 'text-decoration:none;
    }
    footer a:hover {
    text-decoration:underline;';
   if(isset($colors['font_bottom_size']) ) { $addStyleGeneral .= 'font-size: '.esc_attr($colors['font_bottom_size']).'px;'; }
    $addStyleGeneral .= '
    }';

    $output .= wpm_compress($addStyleGeneral);
    ob_get_clean();

    $output .= '</style>'."\n";

    return $output;
}

function wpm_headercode() {

    // Récupère les paramètres sauvegardés
    if(get_option('wp_maintenance_settings_options')) { extract(get_option('wp_maintenance_settings_options')); }
    $o = get_option('wp_maintenance_settings_options');

    $output = '';

    // SÉCURITÉ : Suppression complète de l'injection JavaScript directe
    // Cette fonctionnalité est trop dangereuse et permet l'injection de code arbitraire
    // if(isset($o['headercode']) && $o['headercode']!='') {
    //     $output = '<script>'.stripslashes($o['headercode']).'</script>';
    // }

    return $output;
}

function wpm_social_position($position = '') {

    // Récupère les paramètres sauvegardés
    $o = get_option('wp_maintenance_settings_socialnetworks');

    $output = '';
    if(isset($o['enable']) && $o['enable'] == 1 ) { 

       if(isset($o['position']) && $o['position']=='top' && isset($position) && $position=='top' ) {
            $output = '<header id="header" role="banner" class="pam">'.do_shortcode('[wpm_social]').'</header>';
        } 
       if(isset($o['position']) && $o['position']=='bottom' && isset($position) && $position=='bottom') {
            $output = do_shortcode('[wpm_social]');
        }
    }

    return $output;
}


function wpm_newsletter() {

    // Récupère les paramètres sauvegardés
    if(get_option('wp_maintenance_settings')) { extract(get_option('wp_maintenance_settings')); }
    $o = get_option('wp_maintenance_settings');

    $output = '';

   if(isset($o['newletter']) && $o['newletter']==1 ) {

        $output = '<div class="wpm_newletter">';
       if(isset($o['title_newletter']) && $o['title_newletter']!='') {
            $output .= '<div>'.sanitize_text_field(stripslashes($o['title_newletter'])).'</div>';
        }
       if(isset($o['type_newletter']) && isset($o['iframe_newletter']) && $o['iframe_newletter']!='' && $o['type_newletter']=='iframe' ) {
            // SÉCURITÉ : Filtrage strict du contenu iframe pour éviter XSS
            $allowed_iframe = array(
                'iframe' => array(
                    'src' => array(),
                    'width' => array(),
                    'height' => array(),
                    'frameborder' => array(),
                    'allowfullscreen' => array(),
                    'style' => array()
                )
            );
            $output .= wp_kses(stripslashes($o['iframe_newletter']), $allowed_iframe);
        }
       if(isset($o['type_newletter']) && isset($o['code_newletter']) && $o['code_newletter']!='' && $o['type_newletter']=='shortcode'  ) {
            $output .= do_shortcode(stripslashes($o['code_newletter']));
        }
        $output .= '</div>';
    }
    
    return $output;
}