<?php

// Template Tags
function wpm_title_seo() {

	// Récupère les paramètres sauvegardés
	if(get_option('wp_maintenance_settings')) { extract(get_option('wp_maintenance_settings')); }
	$o = get_option('wp_maintenance_settings');

	$output = get_bloginfo( 'name', 'display' );

	if ( (isset($o['enable_seo']) && $o['enable_seo']==1) && $o['seo_title']!='' ) {
		$output = esc_attr( $o['seo_title'] );
	}

	return $output;
}

function wpm_title() {
	
	// Récupère les paramètres sauvegardés
	if(get_option('wp_maintenance_settings')) { extract(get_option('wp_maintenance_settings')); }
	$o = get_option('wp_maintenance_settings');

	$output = '';

	if( !empty($o['titre_maintenance']) ) {
		$output = esc_html( stripslashes($o['titre_maintenance']) );
	} 
	return $output;
}

function wpm_metadescription() {
	// Récupère les paramètres sauvegardés
	if(get_option('wp_maintenance_settings')) { extract(get_option('wp_maintenance_settings')); }
	$o = get_option('wp_maintenance_settings');

	$output = '<meta name="description" content="'.get_bloginfo( 'description', 'display' ).'">';

	if ( (isset($o['enable_seo']) && $o['enable_seo']==1) && $o['seo_description']!='' ) {
		$output = '<meta name="description" content="'.esc_attr( $o['seo_description'] ).'">';
	}

	return $output;
}

function wpm_footer_text() {

	// Récupère les paramètres sauvegardés
	if(get_option('wp_maintenance_settings')) { extract(get_option('wp_maintenance_settings')); }
	$o = get_option('wp_maintenance_settings');

	$output = '';

	if( isset($o['enable_footer']) && $o['enable_footer'] == 1 ) {

		$output .= '<footer>';

		if( isset($o['text_bt_maintenance']) && $o['text_bt_maintenance']!='' ) {
			$output .= nl2br(stripslashes($o['text_bt_maintenance']));
		}
		if( (isset($o['add_wplogin']) && $o['add_wplogin']==1) && (isset($o['add_wplogin_title']) && $o['add_wplogin_title']!='') ) {
			$output .= '<br /><br /><a href="'.get_admin_url().'">'.str_replace('%DASHBOARD%', ' '.__('Dashboard', 'wp-maintenance'), esc_html($o['add_wplogin_title'])).'</a>';

		}
		$output .= '</footer>';
	}

	return $output;
}

function wpm_favicon() {
	// Récupère les paramètres sauvegardés
	if(get_option('wp_maintenance_settings')) { extract(get_option('wp_maintenance_settings')); }
	$o = get_option('wp_maintenance_settings');

	$output = '';

	if ( !empty( $o['favicon'] ) ) {
		$output .= "<!-- Favicon -->\n";
		$output .= '<link href="'.esc_attr( $o['favicon'] ).'" rel="shortcut icon" type="image/x-icon" />';
	}

	return $output;
}

function wpm_head() {

	// Récupère les paramètres sauvegardés
	if(get_option('wp_maintenance_settings')) { extract(get_option('wp_maintenance_settings')); }
	$o = get_option('wp_maintenance_settings');

	$output = "<!-- Add Google Fonts -->\n";
	$output .= '<link rel="stylesheet" href="https://fonts.googleapis.com/css?family='.str_replace(' ', '+', $o['font_title']).'|'.str_replace(' ', '+',$o['font_text']).'|'.str_replace(' ', '+',$o['font_text_bottom']).'|'.str_replace(' ', '+',$o['font_cpt']);
	if( isset($o['newletter_font_text']) && $o['newletter_font_text'] != '') {
	$output .= '|'.str_replace(' ', '+',$o['newletter_font_text']);	
	}
	$output .= '">';
	return $output;
}

function wpm_text() {
	
	// Récupère les paramètres sauvegardés
	if(get_option('wp_maintenance_settings')) { extract(get_option('wp_maintenance_settings')); }
	$o = get_option('wp_maintenance_settings');

	$output = '';

	if( !empty($o['text_maintenance']) ) {
		$output = nl2br(stripslashes($o['text_maintenance']));
	}
	return $output;
}

function wpm_logo() {

	// Récupère les paramètres sauvegardés
	if(get_option('wp_maintenance_settings')) { extract(get_option('wp_maintenance_settings')); }
	$o = get_option('wp_maintenance_settings');

	$output = '';

	if ( !empty( $o['image'] ) ) {
		if( empty($o['image_width']) ) { $o['image_width'] = 450; }
        if( empty($o['image_height']) ) { $o['image_height'] = 450; }
		$output .= "<div id='logo'><img id='wpm-image' src='".esc_url($o['image'])."' width='".$o['image_width']."px' height='".$o['image_height']."px' alt='".get_bloginfo( 'name', 'display' )." ".get_bloginfo( 'description', 'display' )."' title='".get_bloginfo( 'name', 'display' )." ".get_bloginfo( 'description', 'display' )."' style='width:".$o['image_width']."px!important;height:".$o['image_height']."px!important;'></div>";
	}

	return  $output;
}

function wpm_customcss() {

	// Récupère les paramètres sauvegardés
	if(get_option('wp_maintenance_settings')) { extract(get_option('wp_maintenance_settings')); }
	$o = get_option('wp_maintenance_settings');
	
	if(get_option('wp_maintenance_slider')) { extract(get_option('wp_maintenance_slider')); }
	$paramSlider = get_option('wp_maintenance_slider');

	if(get_option('wp_maintenance_slider_options')) { extract(get_option('wp_maintenance_slider_options')); }
	$paramSliderOptions = get_option('wp_maintenance_slider_options');

	$oo = get_option('wp_maintenance_social_options');

	// CSS
	$output = '<style type="text/css">';
	ob_start();
	
	$addStyleGeneral = '';

	/* Définition des couleurs par défault */
	if( !isset($o['color_bg']) || $o['color_bg']=="") { $o['color_bg'] = "#f1f1f1"; }
	if( !isset($o['color_txt']) || $o['color_txt']=="") { $o['color_txt'] = "#888888"; }

	/* Traitement de la feuille de style */
	$styleRemplacements = array (
		"#_COLORTXT" => $o['color_txt'],
		"#_COLORBG" => $o['color_bg'],
		"#_COLORCPTBG" => $o['color_cpt_bg'],
		"#_DATESIZE" => $o['date_cpt_size'],
		"#_COLORCPT" => $o['color_cpt'],
		"#_COLOR_BG_BT" => $o['color_bg_bottom'],
		"#_COLOR_TXT_BT" => $o['color_text_bottom'],
		"#_COLORHEAD" => $o['color_bg_header'],
	);
	$remplaceStyle = str_replace(array_keys($styleRemplacements), array_values($styleRemplacements), get_option('wp_maintenance_style'));
	$output .= wpm_compress($remplaceStyle);

	/* Si on a activé un motif */
	if( isset($o['b_enable_image']) && $o['b_enable_image'] == 2 ) {

		$addStyleGeneral .= 'body {
		background-image: url('.esc_url(WP_PLUGIN_URL.'/wp-maintenance/images/pattern'.$o['b_pattern'].'.png').');
		background-repeat: repeat;
		background-color: '.$o['color_bg'].';}';
		
	}

	/* Si on a une image de fond */
	if( isset($o['b_enable_image']) && $o['b_enable_image'] == 1 ) {

		if( isset($o['b_image']) && $o['b_image'] ) {

			if( empty($o['b_repeat_image']) ) { $o['b_repeat_image'] = 'repeat'; }
			if( isset($o['b_fixed_image']) && $o['b_fixed_image']==1 ) {
				$o['b_fixed_image'] = 'fixed;';
			} else {
				$o['b_fixed_image'] = '';
			}
			$addStyleGeneral .= 'body {display: grid!important;background:url('.esc_url($o['b_image']).') '.$o['b_repeat_image'].' '.$o['b_fixed_image'].'top center;background-size: cover;
			-webkit-background-size: cover;-moz-background-size: cover;-o-background-size: cover;background-color: '.$o['color_bg'].';background-position: center;}';

			if( isset($o['b_opacity_image']) ) {
				$addStyleGeneral .= '#main { background-color: rgba(0,0,0,'.esc_html($o['b_opacity_image']).'); }';
			} 
		}

	} 

	if( isset($o['b_enable_image']) && $o['b_enable_image']==0 ) {
		$addStyleGeneral .= 'body {background-color: '.$o['color_bg'].';}';
	} 

	if( isset($o['color_bg_header']) && $o['color_bg_header']!='') {
		$addStyleGeneral .= 'header { background-color:'.$o['color_bg_header'].';}';
	}
	$addStyleGeneral .= '.wpm_social_icon {float:left;width:'.esc_html($oo['size']).'px;margin:0px 5px auto;}.wpm_social ul {margin: 10px 0;max-width: 100%;padding: 0;text-align: '.esc_html($oo['align']).';}';

	/* Si container activé */
	if( isset($o['container_active']) && $o['container_active'] == 1 ) {

		if( empty($o['container_opacity']) ) { $o['container_opacity'] = 0.5; }
		if( empty($o['container_width']) ) { $o['container_width'] = 80; }
		if( empty($o['container_color']) ) { $o['container_color'] = '#ffffff'; }
		if( isset($o['container_color']) ) { $paramRGBColor = wpm_hex2rgb($o['container_color']); }
		$addStyleGeneral .= '#sscontent {background-color: rgba('.esc_html($paramRGBColor['rouge']).','.esc_html($paramRGBColor['vert']).','.esc_html($paramRGBColor['bleu']).', '.esc_html($o['container_opacity']).');padding:0.8em;margin-left:auto;margin-right:auto;width:'.$o['container_width'].'%;}';

	}

	$addStyleGeneral .= '.wpm_newletter {';
    if( isset($o['newletter_size']) ) { $addStyleGeneral .= 'font-size:'.esc_html($o['newletter_size']).'px;'; } 
    if( isset($o['newletter_font_style']) ) { $addStyleGeneral .= 'font-style:'.esc_html($o['newletter_font_style']).';'; } 
    if( isset($o['newletter_font_weigth']) ) { $addStyleGeneral .= 'font-weight:'.esc_html($o['newletter_font_weigth']).';'; } 
	if( isset($o['newletter_font_text']) ) { $addStyleGeneral .= 'font-family:'.wpm_format_font($o['newletter_font_text']).', serif;'; } 
	$addStyleGeneral .= '}
	h3 {';
    if( isset($o['font_title']) ) { $addStyleGeneral .= 'font-family: '.wpm_format_font($o['font_title']).', serif;'; }
    if( isset($o['font_title_size']) ) { $addStyleGeneral .= 'font-size: '.esc_html($o['font_title_size']).'px;'; }
    if( isset($o['font_title_style']) ) { $addStyleGeneral .= 'font-style: '.esc_html($o['font_title_style']).';'; }
    if( isset($o['font_title_weigth']) ) { $addStyleGeneral .= 'font-weight: '.esc_html($o['font_title_weigth']).';'; }
    if( isset($o['color_title']) ) { $addStyleGeneral .= 'color:'.esc_html($o['color_title']).';'; }
	$addStyleGeneral .= 'line-height: 100%;text-align:center;margin:0.5em auto;
	}
	p {';
    if( isset($o['font_text']) ) { $addStyleGeneral .= 'font-family: '.wpm_format_font($o['font_text']).', serif;'; }
    if( isset($o['font_text_size']) ) { $addStyleGeneral .= 'font-size: '.esc_html($o['font_text_size']).'px;'; }
    if( isset($o['font_text_style']) ) { $addStyleGeneral .= 'font-style: '.esc_html($o['font_text_style']).';'; }
    if( isset($o['font_text_weigth']) ) { $addStyleGeneral .= 'font-weight: '.esc_html($o['font_text_weigth']).';'; }
    if( isset($o['color_txt']) ) { $addStyleGeneral .= 'color:'.esc_html($o['color_txt']).';'; }
    $addStyleGeneral .= 'line-height: 100%;text-align:center;margin:0.5em auto;padding-left:2%;padding-right:2%;
	}
	footer  {';
    if( isset($o['font_text_bottom']) ) { $addStyleGeneral .= 'font-family: '.wpm_format_font($o['font_text_bottom']).', serif;'; }
    if( isset($o['font_bottom_style']) ) { $addStyleGeneral .= 'font-style: '.esc_html($o['font_bottom_style']).';'; }
    if( isset($o['font_bottom_size']) ) { $addStyleGeneral .= 'font-size: '.esc_html($o['font_bottom_size']).'px;'; }
    if( isset($o['font_bottom_weigth']) ) { $addStyleGeneral .= 'font-weight: '.esc_html($o['font_bottom_weigth']).';'; }
	if( isset($o['color_text_bottom']) ) { $addStyleGeneral .= 'color: '.esc_html($o['color_text_bottom']).';'; }
	if( isset($o['color_bg_bottom']) ) { $addStyleGeneral .= 'background:'.esc_html($o['color_bg_bottom']).';'; }
    $addStyleGeneral .= 'text-decoration:none;
	}
	footer a:link {';
    if( isset($o['color_text_bottom']) ) { $addStyleGeneral .= 'color:'.esc_html($o['color_text_bottom']).';'; }
    if( isset($o['font_bottom_size']) ) { $addStyleGeneral .= 'font-size: '.esc_html($o['font_bottom_size']).'px;'; }
    $addStyleGeneral .= 'text-decoration:none;
	}
	footer a:visited {';
    if( isset($o['color_text_bottom']) ) { $addStyleGeneral .= 'olor:'.esc_html($o['color_text_bottom']).';'; }
    if( isset($o['font_bottom_size']) ) { $addStyleGeneral .= 'font-size: '.esc_html($o['font_bottom_size']).'px;'; }
    $addStyleGeneral .= 'text-decoration:none;
	}
	footer a:hover {
    text-decoration:underline;';
    if( isset($o['font_bottom_size']) ) { $addStyleGeneral .= 'font-size: '.esc_html($o['font_bottom_size']).'px;'; }
	$addStyleGeneral .= '
	}';

	$output .= wpm_compress($addStyleGeneral);
	$output .= ob_get_clean();

	$output .= '</style>'."\n";

	return $output;
}

function wpm_analytics() {

	// Récupère les paramètres sauvegardés
	if(get_option('wp_maintenance_settings')) { extract(get_option('wp_maintenance_settings')); }
	$o = get_option('wp_maintenance_settings');

	$output = '<!-- analytics ici -->';

	if( isset($o['codeanalytics']) && $o['codeanalytics']!='') {
		$output = stripslashes($o['codeanalytics']);
	}

	return $output;
}

function wpm_headercode() {

	// Récupère les paramètres sauvegardés
	if(get_option('wp_maintenance_settings')) { extract(get_option('wp_maintenance_settings')); }
	$o = get_option('wp_maintenance_settings');

	$output = '';

	if( isset($o['headercode']) && $o['headercode']!='') {
		$output = stripslashes($o['headercode']);
	}

	return $output;
}

function wpm_social_position($position = '') {

	// Récupère les paramètres sauvegardés
	$o = get_option('wp_maintenance_social_options');

	$output = '';
	if( isset($o['enable']) && $o['enable'] == 1 ) { 

		if( isset($o['position']) && $o['position']=='top' && isset($position) && $position=='top' ) {
			$output = '<header id="header" role="banner" class="pam">'.do_shortcode('[wpm_social]').'</header>';
		} 
		if( isset($o['position']) && $o['position']=='bottom' && isset($position) && $position=='bottom') {
			$output = do_shortcode('[wpm_social]');
		}
	}

	return $output;
}


function wpm_stylenewsletter() {

	// Récupère les paramètres sauvegardés
	if(get_option('wp_maintenance_settings')) { extract(get_option('wp_maintenance_settings')); }
	$o = get_option('wp_maintenance_settings');

	$output = '';

	if( empty($o['color_field_text']) ) { $o['color_field_text'] = '#333333'; }
	if( empty($o['color_text_button']) ) { $o['color_text_button']= '#ffffff'; }
	if( empty($o['color_field_background']) ) { $o['color_field_background']= '#F1F1F1'; }
	if( empty($o['color_field_border']) ) { $o['color_field_border']= '#333333'; }
	if( empty($o['color_button_onclick']) ) { $o['color_button_onclick']= '#333333'; }
	if( empty($o['color_button_hover']) ) { $o['color_button_hover']= '#cccccc'; }
	if( empty($o['color_button']) ) { $o['color_button']= '#1e73be'; }

	$wysijaRemplacements = array (
		"#_COLORTXT" => esc_html($o['color_field_text']),
		"#_COLORBG" => esc_html($o['color_field_background']),
		"#_COLORBORDER" => esc_html($o['color_field_border']),
		"#_COLORBUTTON" => esc_html($o['color_button']),
		"#_COLORTEXTBUTTON" => esc_html($o['color_text_button']),
		"#_COLOR_BTN_HOVER" => esc_html($o['color_button_hover']),
		"#_COLOR_BTN_CLICK" => esc_html($o['color_button_onclick'])
	);

	if( isset($o['code_newletter']) && $o['code_newletter']!='' && strpos($o['code_newletter'], 'wysija_form') == 1 ) {

		$output = str_replace(array_keys($wysijaRemplacements), array_values($wysijaRemplacements), wpm_wysija_style() );

	} else if( strpos($o['code_newletter'], 'mc4wp_form') == 1 ) {

		$output = str_replace(array_keys($wysijaRemplacements), array_values($wysijaRemplacements), wpm_mc4wp_style() );

	}

	if( isset($output) && $output!='' ) {
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

	if( isset($o['newletter']) && $o['newletter']==1 ) {

		$output = '<div class="wpm_newletter">';
		if( isset($o['title_newletter']) && $o['title_newletter']!='') {
			$output .= '<div>'.sanitize_text_field(stripslashes($o['title_newletter'])).'</div>';
		}
		if( isset($o['type_newletter']) && isset($o['iframe_newletter']) && $o['iframe_newletter']!='' && $o['type_newletter']=='iframe' ) {
			$output .= stripslashes($o['iframe_newletter']);
		}
		if( isset($o['type_newletter']) && isset($o['code_newletter']) && $o['code_newletter']!='' && $o['type_newletter']=='shortcode'  ) {
			$output .= do_shortcode(stripslashes($o['code_newletter']));
		}
		$output .= '</div>';
	}
	
	return $output;
}