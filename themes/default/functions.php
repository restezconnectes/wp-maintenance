<?php

// Template Tags
function wpm_title_seo() {

	// Récupère les paramètres sauvegardés
	if(get_option('wp_maintenance_settings')) { extract(get_option('wp_maintenance_settings')); }
	$o = get_option('wp_maintenance_settings');

	$output = get_bloginfo( 'name', 'display' );

	if ( !empty( $o['seo_title'] ) ) {
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

	if ( !empty( $o['seo_description'] ) ) {
		$output = '<meta name="description" content="'.esc_attr( $o['seo_description'] ).'">';
	}

	return $output;
}

function wpm_copyrights() {
	// Récupère les paramètres sauvegardés
	if(get_option('wp_maintenance_settings')) { extract(get_option('wp_maintenance_settings')); }
	$o = get_option('wp_maintenance_settings');

	$output = '';

	if( isset($o['text_bt_maintenance']) && $o['text_bt_maintenance']!='' ) {
		$output = stripslashes($o['text_bt_maintenance']);
	}
	if( (isset($o['add_wplogin']) && $o['add_wplogin']==1) && (isset($o['add_wplogin_title']) && $o['add_wplogin_title']!='') ) {
		$output .= '<br /><br />'.str_replace('%DASHBOARD%', '<a href="'.get_admin_url().'">'.__('Dashboard', 'wp-maintenance').'</a>', $o['add_wplogin_title']).'';

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
		$output = stripslashes(wpautop($o['text_maintenance']));
	}
	return $output;
}

function wpm_logo() {

	// Récupère les paramètres sauvegardés
	if(get_option('wp_maintenance_settings')) { extract(get_option('wp_maintenance_settings')); }
	$o = get_option('wp_maintenance_settings');

	$output = '';

	if ( !empty( $o['image'] ) ) {
		if( empty($o['image_width']) ) { $o['image_width'] = 310; }
        if( empty($o['image_height']) ) { $o['image_height'] = 185; }
		$output .= "<div id='logo'><img id='wpm-image' src='".$o['image']."' width='".$o['image_width']."' height='".$o['image_height']."'  alt='".get_bloginfo( 'name', 'display' )." ".get_bloginfo( 'description', 'display' )."' title='".get_bloginfo( 'name', 'display' )." ".get_bloginfo( 'description', 'display' )."'></div>";
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

	/* Si container activé */
	if( isset($o['container_active']) && $o['container_active'] == 1 ) {

		if( empty($o['container_opacity']) ) { $o['container_opacity'] = 0.5; }
		if( empty($o['container_width']) ) { $o['container_width'] = 80; }
		if( empty($o['container_color']) ) { $o['container_color'] = '#ffffff'; }
		if( isset($o['container_color']) ) { $paramRGBColor = wpm_hex2rgb($o['container_color']); }
	?>

#sscontent {background-color: rgba(<?php echo $paramRGBColor['rouge']; ?>,<?php echo $paramRGBColor['vert']; ?>,<?php echo $paramRGBColor['bleu']; ?>, <?php echo $o['container_opacity']; ?>);padding:0.8em;margin-left:auto;margin-right:auto;width:<?php echo $o['container_width']; ?>%;}
';

	<?php	
	}
	
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
	$output .= str_replace(array_keys($styleRemplacements), array_values($styleRemplacements), get_option('wp_maintenance_style'));

	/* Si on a activé une image ou pattern */
	if( isset($o['b_enable_image']) && $o['b_enable_image']==1 ) {

		/* Si on a une image de fond */
		$optionBackground = '';
		if( isset($o['b_pattern']) && $o['b_pattern']>0 ) { ?>
body {
background-image: url(<?php echo WP_PLUGIN_URL.'/wp-maintenance/images/pattern'.$o['b_pattern']; ?>.png);
background-repeat: repeat;
<?php echo $optionBackground; ?>   
}
<?php 

		/* Si on a motif */
		} elseif( isset($o['b_image']) && $o['b_image'] ) {

			if( empty($o['b_repeat_image']) ) { $o['b_repeat_image'] = 'repeat'; }
			if( isset($o['b_fixed_image']) && $o['b_fixed_image']==1 ) {
				$o['b_fixed_image'] = 'fixed;';
			} else {
				$o['b_fixed_image'] = '';
			}
?>
body {
background:url(<?php echo $o['b_image']; ?>) <?php echo $o['b_repeat_image']; ?> <?php echo $o['b_fixed_image']; ?>top center;
background-size: cover;
-webkit-background-size: cover;
-moz-background-size: cover;
-o-background-size: cover;
}
<?php 	
			if( isset($o['b_opacity_image']) ) {
?>
				#wrapper { background-color: rgba(0,0,0,<?php echo $o['b_opacity_image']; ?>); }
<?php 
			} 
		/* Sinon on garde une couleur de fond */
		} else {
?>
body { background-color: <?php echo $o['color_bg']; ?> }
<?php	}

	}
	?>

.wpm_social_icon {
    float:left;
    width:<?php echo $oo['size']; ?>px;
    margin:0px 5px auto;
}
.wpm_social ul {
    margin: 10px 0;
    max-width: 100%;
    padding: 0;
    text-align: <?php echo $oo['align']; ?>;
}

	<?php /* Si container activé */
	if( isset($o['container_active']) && $o['container_active'] == 1 ) {

		if( empty($o['container_opacity']) ) { $o['container_opacity'] = 0.5; }
		if( empty($o['container_width']) ) { $o['container_width'] = 80; }
		if( empty($o['container_color']) ) { $o['container_color'] = '#ffffff'; }
		if( isset($o['container_color']) ) { $paramRGBColor = wpm_hex2rgb($o['container_color']); }
	?>
#sscontent {
background-color: rgba(<?php echo $paramRGBColor['rouge']; ?>,<?php echo $paramRGBColor['vert']; ?>,<?php echo $paramRGBColor['bleu']; ?>, <?php echo $o['container_opacity']; ?>);
padding:0.8em;
margin-left:auto;
margin-right:auto;
width:<?php echo $o['container_width']; ?>%;
}

<?php	} ?>

.wpm_newletter {
    <?php if( isset($o['newletter_size']) ) { ?>font-size: <?php echo $o['newletter_size']; ?>px; <?php } ?>
    <?php if( isset($o['newletter_font_style']) ) { ?>font-style: <?php echo $o['newletter_font_style']; ?>; <?php } ?>
    <?php if( isset($o['newletter_font_weigth']) ) { ?>font-weight: <?php echo $o['newletter_font_weigth']; ?>; <?php } ?>
    <?php if( isset($o['newletter_font_text']) ) { ?>font-family: <?php echo wpm_format_font($o['newletter_font_text']); ?>, serif; <?php } ?>
}

h3 {
    <?php if( isset($o['font_title']) ) { ?>font-family: <?php echo wpm_format_font($o['font_title']); ?>, serif; <?php } ?>
    <?php if( isset($o['font_title_size']) ) { ?>font-size: <?php echo $o['font_title_size']; ?>px; <?php } ?>
    <?php if( isset($o['font_title_style']) ) { ?>font-style: <?php echo $o['font_title_style']; ?>; <?php } ?>
    <?php if( isset($o['font_title_weigth']) ) { ?>font-weight: <?php echo $o['font_title_weigth']; ?>; <?php } ?>
    <?php if( isset($o['color_title']) ) { ?>color:<?php echo $o['color_title']; ?>; <?php } ?>
    line-height: 100%;
    text-align:center;
    margin:0.5em auto;
}
p {
    <?php if( isset($o['font_text']) ) { ?>font-family: <?php echo wpm_format_font($o['font_text']); ?>, serif;<?php } ?>
    <?php if( isset($o['font_text_size']) ) { ?>font-size: <?php echo $o['font_text_size']; ?>px;<?php } ?>
    <?php if( isset($o['font_text_style']) ) { ?>font-style: <?php echo $o['font_text_style']; ?>;<?php } ?>
    <?php if( isset($o['font_text_weigth']) ) { ?>font-weight: <?php echo $o['font_text_weigth']; ?>;<?php } ?>
    <?php if( isset($o['color_txt']) ) { ?>color:<?php echo $o['color_txt']; ?>;<?php } ?>
    line-height: 100%;
    text-align:center;
    margin:0.5em auto;
    padding-left:2%;
    padding-right:2%;

}
<?php if( (isset($o['text_bt_maintenance']) && $o['text_bt_maintenance']!='') or ( (isset($o['add_wplogin']) && $o['add_wplogin']==1) && (isset($o['add_wplogin_title']) && $o['add_wplogin_title']!='') ) ) { ?>
#footer {
    <?php if( isset($o['color_bg_bottom']) ) { ?>background:<?php echo $o['color_bg_bottom']; ?>;<?php } ?>
}
<?php } ?>
div.bloc {
    <?php if( isset($o['font_text_bottom']) ) { ?>font-family: <?php echo wpm_format_font($o['font_text_bottom']); ?>, serif;<?php } ?>
    <?php if( isset($o['font_bottom_style']) ) { ?>font-style: <?php echo $o['font_bottom_style']; ?>;<?php } ?>
    <?php if( isset($o['font_bottom_size']) ) { ?>font-size: <?php echo $o['font_bottom_size']; ?>px;<?php } ?>
    <?php if( isset($o['font_bottom_weigth']) ) { ?>font-weight: <?php echo $o['font_bottom_weigth']; ?>;<?php } ?>
    <?php if( isset($o['color_text_bottom']) ) { ?>color: <?php echo $o['color_text_bottom']; ?>;<?php } ?>
    text-decoration:none;
}
div.bloc a:link {
    <?php if( isset($o['color_text_bottom']) ) { ?>color:<?php echo $o['color_text_bottom']; ?>;<?php } ?>
    <?php if( isset($o['font_bottom_size']) ) { ?>font-size: <?php echo $o['font_bottom_size']; ?>px;<?php } ?>
    text-decoration:none;
}
div.bloc a:visited {
    <?php if( isset($o['color_text_bottom']) ) { ?>color:<?php echo $o['color_text_bottom']; ?>;<?php } ?>
    <?php if( isset($o['font_bottom_size']) ) { ?>font-size: <?php echo $o['font_bottom_size']; ?>px;<?php } ?>
    text-decoration:none;
}
div.bloc a:hover {
    text-decoration:underline;
    <?php if( isset($o['font_bottom_size']) ) { ?>font-size: <?php echo $o['font_bottom_size']; ?>px;<?php } ?>

}
<?php
	$output .= ob_get_clean();

	$output .= '</style>'."\n";

	return $output;
}

function wpm_analytics() {

	// Récupère les paramètres sauvegardés
	if(get_option('wp_maintenance_settings')) { extract(get_option('wp_maintenance_settings')); }
	$o = get_option('wp_maintenance_settings');

	$output = '';

	if( isset($o['analytics']) && $o['analytics']!='') {
		$output = do_shortcode('[wpm_analytics enable="'.$o['analytics'].'"]');
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
	if( isset($o['position']) && $o['position']=='top' && isset($position) && $position=='top' ) {
		$output = '<div id="header">'.do_shortcode('[wpm_social]').'</div>';
	} 
	if( isset($o['position']) && $o['position']=='bottom' && isset($position) && $position=='bottom') {
		$output = do_shortcode('[wpm_social]');
	}

	return $output;
}


function wpm_stylenewsletter() {

	// Récupère les paramètres sauvegardés
	if(get_option('wp_maintenance_settings')) { extract(get_option('wp_maintenance_settings')); }
	$o = get_option('wp_maintenance_settings');

	$output = '';

	$output = " /* no NEWLETTER Style */ ";

	if( empty($o['color_field_text']) ) { $o['color_field_text'] = '#333333'; }
	if( empty($o['color_text_button']) ) { $o['color_text_button']= '#ffffff'; }
	if( empty($o['color_field_background']) ) { $o['color_field_background']= '#F1F1F1'; }
	if( empty($o['color_field_border']) ) { $o['color_field_border']= '#333333'; }
	if( empty($o['color_button_onclick']) ) { $o['color_button_onclick']= '#333333'; }
	if( empty($o['color_button_hover']) ) { $o['color_button_hover']= '#cccccc'; }
	if( empty($o['color_button']) ) { $o['color_button']= '#1e73be'; }

	$wysijaRemplacements = array (
		"#_COLORTXT" => $o['color_field_text'],
		"#_COLORBG" => $o['color_field_background'],
		"#_COLORBORDER" => $o['color_field_border'],
		"#_COLORBUTTON" => $o['color_button'],
		"#_COLORTEXTBUTTON" => $o['color_text_button'],
		"#_COLOR_BTN_HOVER" => $o['color_button_hover'],
		"#_COLOR_BTN_CLICK" => $o['color_button_onclick']
	);

	if( isset($o['code_newletter']) && $o['code_newletter']!='' && strpos($o['code_newletter'], 'wysija_form') == 1 ) {

		$output = str_replace(array_keys($wysijaRemplacements), array_values($wysijaRemplacements), wpm_wysija_style() );

	} else if( strpos($o['code_newletter'], 'mc4wp_form') == 1 ) {

		$output = str_replace(array_keys($wysijaRemplacements), array_values($wysijaRemplacements), wpm_mc4wp_style() );

	}

	return '<style type="text/css">'.$output.'</style>';
}

function wpm_newsletter() {

	// Récupère les paramètres sauvegardés
	if(get_option('wp_maintenance_settings')) { extract(get_option('wp_maintenance_settings')); }
	$o = get_option('wp_maintenance_settings');

	$output = '';

	if( isset($o['newletter']) && $o['newletter']==1 ) {

		$output = '<div class="wpm_newletter">';
		if( isset($o['title_newletter']) && $o['title_newletter']!='') {
			$output .= '<div>'.stripslashes($o['title_newletter']).'</div>';
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