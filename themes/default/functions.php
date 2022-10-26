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
            $output .= nl2br(stripslashes($o['text_bt_maintenance']));
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
        $output .= '<link rel="stylesheet" href="https://fonts.googleapis.com/css?family='.str_replace(' ', '+', $o['font_title']).'|'.str_replace(' ', '+', $o['font_text']).'|'.str_replace(' ', '+', $o['font_text_bottom']).'|'.str_replace(' ', '+', $o['font_cpt']);
        if(isset($o['newletter_font_text']) && $o['newletter_font_text'] != '') {
        $output .= '|'.str_replace(' ', '+', $o['newletter_font_text']);    
        }
        $output .= '">';
    }
    return $output;
}

function wpm_text() {
    
    // Récupère les paramètres sauvegardés
    if(get_option('wp_maintenance_settings')) { extract(get_option('wp_maintenance_settings')); }
    $o = get_option('wp_maintenance_settings');

    $output = '';

    if(!empty($o['text_maintenance']) ) {
        $output = nl2br(stripslashes($o['text_maintenance']));
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
        $output .= "<div id='logo'><img id='wpm-image' src='".esc_url($o['image'])."' width='".$o['image_width']."px' height='".$o['image_height']."px' alt='".get_bloginfo( 'name', 'display' )." ".get_bloginfo( 'description', 'display' )."' title='".get_bloginfo( 'name', 'display' )." ".get_bloginfo( 'description', 'display' )."' style='width:".$o['image_width']."px!important;height:".$o['image_height']."px!important;'></div>";
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

        $addStyleGeneral .= 'body {
        background-image: url('.esc_url(WP_PLUGIN_URL.'/wp-maintenance/images/pattern'.$picture['b_pattern'].'.png').');
        background-repeat: repeat;
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
            $addStyleGeneral .= 'body {display: grid!important;background:url('.esc_url($picture['b_image']).') '.$picture['b_repeat_image'].' '.$picture['b_fixed_image'].'top center;background-size: cover;
            -webkit-background-size: cover;-moz-background-size: cover;-o-background-size: cover;background-color: '.$colors['color_bg'].';background-position: center;}';

           if(isset($picture['b_opacity_image']) ) {
                $addStyleGeneral .= '#main { background-color: rgba(0,0,0,'.esc_html($picture['b_opacity_image']).'); }';
            } 
        }

    } 

   if(isset($picture['b_enable_image']) && $picture['b_enable_image']==0 ) {
        $addStyleGeneral .= 'body {background-color: '.$colors['color_bg'].';}';
    } 

   if(isset($colors['color_bg_header']) && $colors['color_bg_header']!='') {
        $addStyleGeneral .= 'header { background-color:'.$colors['color_bg_header'].';}';
    }
    $addStyleGeneral .= '.wpm_social_icon {float:left;width:'.esc_html($oo['size']).'px;margin:0px 5px auto;}.wpm_social ul {margin: 10px 0;max-width: 100%;padding: 0;text-align: '.esc_html($oo['align']).';}';

    /* Si container activé */
   if(isset($colors['container_active']) && $colors['container_active'] == 1 ) {

       if(empty($colors['container_opacity']) ) { $colors['container_opacity'] = 0.5; }
       if(empty($colors['container_width']) ) { $colors['container_width'] = 80; }
       if(empty($colors['container_color']) ) { $colors['container_color'] = '#ffffff'; }
       if(isset($colors['container_color']) ) { $paramRGBColor = wpm_hex2rgb($colors['container_color']); }
        $addStyleGeneral .= '#sscontent {background-color: rgba('.esc_html($paramRGBColor['rouge']).','.esc_html($paramRGBColor['vert']).','.esc_html($paramRGBColor['bleu']).', '.esc_html($colors['container_opacity']).');padding:0.8em;margin-left:auto;margin-right:auto;width:'.esc_html($colors['container_width']).'%;}';

    }

    $addStyleGeneral .= '.wpm_newletter {';
   if(isset($colors['newletter_size']) ) { $addStyleGeneral .= 'font-size:'.esc_html($colors['newletter_size']).'px;'; } 
   if(isset($colors['newletter_font_style']) ) { $addStyleGeneral .= 'font-style:'.esc_html($colors['newletter_font_style']).';'; } 
   if(isset($colors['newletter_font_weigth']) ) { $addStyleGeneral .= 'font-weight:'.esc_html($colors['newletter_font_weigth']).';'; } 
   if(isset($colors['newletter_font_text']) ) { $addStyleGeneral .= 'font-family:'.wpm_format_font(wpm_fonts($colors['newletter_font_text'])).';'; } 
    $addStyleGeneral .= '}
    h3 {';
   if(isset($colors['font_title']) ) { $addStyleGeneral .= 'font-family: '.wpm_format_font(wpm_fonts($colors['font_title'])).';'; }
   if(isset($colors['font_title_size']) ) { $addStyleGeneral .= 'font-size: '.esc_html($colors['font_title_size']).'px;'; }
   if(isset($colors['font_title_style']) ) { $addStyleGeneral .= 'font-style: '.esc_html($colors['font_title_style']).';'; }
   if(isset($colors['font_title_weigth']) ) { $addStyleGeneral .= 'font-weight: '.esc_html($colors['font_title_weigth']).';'; }
   if(isset($colors['color_title']) ) { $addStyleGeneral .= 'color:'.esc_html($colors['color_title']).';'; }
    $addStyleGeneral .= 'line-height: 100%;text-align:center;margin:0.5em auto;
    }
    p {';
   if(isset($colors['font_text']) ) { $addStyleGeneral .= 'font-family: '.wpm_format_font(wpm_fonts($colors['font_text'])).';'; }
   if(isset($colors['font_text_size']) ) { $addStyleGeneral .= 'font-size: '.esc_html($colors['font_text_size']).'px;'; }
   if(isset($colors['font_text_style']) ) { $addStyleGeneral .= 'font-style: '.esc_html($colors['font_text_style']).';'; }
   if(isset($colors['font_text_weigth']) ) { $addStyleGeneral .= 'font-weight: '.esc_html($colors['font_text_weigth']).';'; }
   if(isset($colors['color_txt']) ) { $addStyleGeneral .= 'color:'.esc_html($colors['color_txt']).';'; }
    $addStyleGeneral .= 'line-height: 100%;text-align:center;margin:0.5em auto;padding-left:2%;padding-right:2%;
    }
    footer  {';
   if(isset($colors['font_text_bottom']) ) { $addStyleGeneral .= 'font-family: '.wpm_format_font(wpm_fonts($colors['font_text_bottom'])).';'; }
   if(isset($colors['font_bottom_style']) ) { $addStyleGeneral .= 'font-style: '.esc_html($colors['font_bottom_style']).';'; }
   if(isset($colors['font_bottom_size']) ) { $addStyleGeneral .= 'font-size: '.esc_html($colors['font_bottom_size']).'px;'; }
   if(isset($colors['font_bottom_weigth']) ) { $addStyleGeneral .= 'font-weight: '.esc_html($colors['font_bottom_weigth']).';'; }
   if(isset($colors['color_text_bottom']) ) { $addStyleGeneral .= 'color: '.esc_html($colors['color_text_bottom']).';'; }
   if(isset($colors['color_bg_bottom']) ) { $addStyleGeneral .= 'background:'.esc_html($colors['color_bg_bottom']).';'; }
    $addStyleGeneral .= 'text-decoration:none;
    }
    footer a:link {';
   if(isset($colors['color_text_bottom']) ) { $addStyleGeneral .= 'color:'.esc_html($colors['color_text_bottom']).';'; }
   if(isset($colors['font_bottom_size']) ) { $addStyleGeneral .= 'font-size: '.esc_html($colors['font_bottom_size']).'px;'; }
    $addStyleGeneral .= 'text-decoration:none;
    }
    footer a:visited {';
   if(isset($colors['color_text_bottom']) ) { $addStyleGeneral .= 'color:'.esc_html($colors['color_text_bottom']).';'; }
   if(isset($colors['font_bottom_size']) ) { $addStyleGeneral .= 'font-size: '.esc_html($colors['font_bottom_size']).'px;'; }
    $addStyleGeneral .= 'text-decoration:none;
    }
    footer a:hover {
    text-decoration:underline;';
   if(isset($colors['font_bottom_size']) ) { $addStyleGeneral .= 'font-size: '.esc_html($colors['font_bottom_size']).'px;'; }
    $addStyleGeneral .= '
    }';

    $output .= wpm_compress($addStyleGeneral);
    $output .= ob_get_clean();

    $output .= '</style>'."\n";

    return $output;
}

function wpm_headercode() {

    // Récupère les paramètres sauvegardés
    if(get_option('wp_maintenance_settings_options')) { extract(get_option('wp_maintenance_settings_options')); }
    $o = get_option('wp_maintenance_settings_options');

    $output = '';

   if(isset($o['headercode']) && $o['headercode']!='') {
        $output = stripslashes($o['headercode']);
    }

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


function wpm_stylenewsletter() {

    // Récupère les paramètres sauvegardés
    if(get_option('wp_maintenance_settings')) { extract(get_option('wp_maintenance_settings')); }
    $o = get_option('wp_maintenance_settings');

    // Récupère les paramètres sauvegardés des couleurs
    if(get_option('wp_maintenance_settings_colors')) { extract(get_option('wp_maintenance_settings_colors')); }
    $colors = get_option('wp_maintenance_settings_colors');


    $output = '';

   if(empty($colors['color_field_text']) ) { $colors['color_field_text'] = '#333333'; }
   if(empty($colors['color_text_button']) ) { $colors['color_text_button']= '#ffffff'; }
   if(empty($colors['color_field_background']) ) { $colors['color_field_background']= '#F1F1F1'; }
   if(empty($colors['color_field_border']) ) { $colors['color_field_border']= '#333333'; }
   if(empty($colors['color_button_onclick']) ) { $colors['color_button_onclick']= '#333333'; }
   if(empty($colors['color_button_hover']) ) { $colors['color_button_hover']= '#cccccc'; }
   if(empty($colors['color_button']) ) { $colors['color_button']= '#1e73be'; }

    $wysijaRemplacements = array (
        "#_COLORTXT" => esc_html($colors['color_field_text']),
        "#_COLORBG" => esc_html($colors['color_field_background']),
        "#_COLORBORDER" => esc_html($colors['color_field_border']),
        "#_COLORBUTTON" => esc_html($colors['color_button']),
        "#_COLORTEXTBUTTON" => esc_html($colors['color_text_button']),
        "#_COLOR_BTN_HOVER" => esc_html($colors['color_button_hover']),
        "#_COLOR_BTN_CLICK" => esc_html($colors['color_button_onclick'])
    );

   if(isset($o['code_newletter']) && $o['code_newletter']!='' && strpos($o['code_newletter'], 'wysija_form') == 1 ) {

        $output = str_replace(array_keys($wysijaRemplacements), array_values($wysijaRemplacements), wpm_wysija_style() );

    } elseif(isset($o['code_newletter']) && strpos($o['code_newletter'], 'mc4wp_form') == 1 ) {

        $output = str_replace(array_keys($wysijaRemplacements), array_values($wysijaRemplacements), wpm_mc4wp_style() );

    }

   if(isset($output) && $output!='' ) {
        return '<style type="text/css">'.wpm_compress(sanitize_text_field($output)).'</style>';
    } else {
        return;
    }
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
            $output .= stripslashes($o['iframe_newletter']);
        }
       if(isset($o['type_newletter']) && isset($o['code_newletter']) && $o['code_newletter']!='' && $o['type_newletter']=='shortcode'  ) {
            $output .= do_shortcode(stripslashes($o['code_newletter']));
        }
        $output .= '</div>';
    }
    
    return $output;
}