<?php

/*
 * Plugin Name: WP Maintenance
 * Plugin URI: https://fr.wordpress.org/plugins/wp-maintenance/
 * Description: Le plugin WP Maintenance vous permet de mettre votre site en attente le temps pour vous de faire une maintenance ou du lancement de votre site. Personnalisez cette page de maintenance avec une image, un compte à rebours, etc... / The WP Maintenance plugin allows you to put your website on the waiting time for you to do maintenance or launch your website. Personalize this page with picture, countdown...
 * Author: Florent Maillefaud
 * Author URI: https://wpmaintenance.info
 * Version: 2.8.4
 * Text Domain: wp-maintenance
 * Domain Path: /languages/
 */

/*  Copyright 2007-2015 Florent Maillefaud (email: contact at restezconnectes.fr)

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

if(!defined('WP_CONTENT_URL')) { define('WP_CONTENT_URL', get_option( 'siteurl') . '/wp-content'); }
if(!defined('WP_CONTENT_DIR')) { define('WP_CONTENT_DIR', ABSPATH . 'wp-content'); }
if(!defined('WP_PLUGIN_URL')) { define('WP_PLUGIN_URL', WP_CONTENT_URL.'/plugins'); }
if(!defined('WP_PLUGIN_DIR')) { define('WP_PLUGIN_DIR', WP_CONTENT_DIR.'/plugins'); }
if(!defined( 'WPM_BASENAME')) { define( 'WPM_BASENAME', plugin_basename(__FILE__) ); }

/* Ajout réglages au plugin */
$wpmaintenance_dashboard = ( is_admin() ) ? 'options-general.php?page=wp-maintenance/wp-maintenance.php' : '';
define( 'WPM_SETTINGS', $wpmaintenance_dashboard);

include("uninstall.php");
include("wpm_fonctions.php");

// Add "Réglages" link on plugins page
add_filter( 'plugin_action_links_' . WPM_BASENAME, 'wpm_plugin_actions' );
function wpm_plugin_actions ( $links ) {
    $settings_link = '<a href="'.WPM_SETTINGS.'">'.__('Settings', 'wp-maintenance').'</a>';
    array_unshift ( $links, $settings_link );
    return $links;
}

/* DATEPICKER */
add_action( 'init', 'wpm_date_picker' );
function wpm_date_picker() {
    if (isset($_GET['page']) && $_GET['page'] == 'wp-maintenance') {
        wp_enqueue_script( 'jquery' );
        wp_enqueue_script('jquery-ui-datepicker');
        wp_enqueue_style('jquery-style', 'https://ajax.googleapis.com/ajax/libs/jqueryui/1.8.2/themes/smoothness/jquery-ui.css');
    }
    
}

// multilingue
add_action( 'init', 'wpm_make_multilang' );
function wpm_make_multilang() {
    load_plugin_textdomain('wp-maintenance', false, dirname( plugin_basename( __FILE__ ) ).'/languages');
}

/* Ajoute la version dans les options */
define('WPM_VERSION', '2.8.4');
$option['wp_maintenance_version'] = WPM_VERSION;
if( !get_option('wp_maintenance_version') ) {
    add_option('wp_maintenance_version', $option);
} else if ( get_option('wp_maintenance_version') != WPM_VERSION ) {
    update_option('wp_maintenance_version', WPM_VERSION);
}

//récupère le formulaire d'administration du plugin
function wpm_admin_panel() {
    include("wp-maintenance-admin.php");
}

/* Ajout feuille CSS pour l'admin barre */
function wpm_admin_head() {
    echo '<link rel="stylesheet" type="text/css" media="all" href="' .plugins_url('css/wpm-admin.css', __FILE__). '">';
}
add_action('admin_head', 'wpm_admin_head');

/* Ajout Notification admin barre */
add_action( 'admin_bar_menu', 'wpm_add_menu_admin_bar', 999 );
function wpm_add_menu_admin_bar( $wp_admin_bar ) {
    
    $checkActive = get_option('wp_maintenance_active');
    if( isset($checkActive) && $checkActive==1 && !is_network_admin() ) {
        $textAdmin = '<img src="'.WP_PLUGIN_URL.'/wp-maintenance/images/lock.png" style="padding: 6px 0;float:left;margin-right: 6px;">'.__('Maintenance mode activated!', 'wp-maintenance');
        $args = array(
            'id'     => 'wpm-info',     // id of the existing child node (New > Post)
            'title'  => $textAdmin, // alter the title of existing node
            'href' => WPM_SETTINGS, // Lien du menu
            'parent' => false,          // set parent to false to make it a top level (parent) node
            'meta' => array(
                'class' => 'wpmbackground'
            )
        );
        $wp_admin_bar->add_node( $args );
    }
}

/* Liste les différents Rôles */
function wpm_get_roles() {

    $wp_roles = new WP_Roles();
    $roles = $wp_roles->get_names();
    $roles = array_map( 'translate_user_role', $roles );

    return $roles;
}

/* Retourne la vraie adresse IP */
function wpm_get_ip() {
    return (isset($_SERVER['HTTP_X_FORWARDED_FOR'])) ? $_SERVER['HTTP_X_FORWARDED_FOR'] : $_SERVER['REMOTE_ADDR'];
}

function wpm_add_admin() {
    
    $wp_maintenanceAdminOptions = array(
        'enable' => 0,
        'color_bg' => "#f1f1f1",
        'color_txt' => '#888888',
        'color_bg_bottom' => '#333333',
        'color_text_bottom' => '#FFFFFF',
        'titre_maintenance' => __('This site is down for maintenance', 'wp-maintenance'),
        'text_maintenance' => __('Come back quickly !', 'wp-maintenance'),
        'userlimit' => 'administrator',
        'image' => WP_PLUGIN_URL.'/wp-maintenance/images/default.png',
        'font_title' => 'Pacifico',
        'font_title_size' => 40,
        'font_title_weigth' => 'normal',
        'font_title_style' => '',
        'font_text_style' => '',
        'font_text' => 'Metrophobic',
        'font_text_size' => 18,
        'font_text_bottom' => 'Pacifico',
        'font_text_weigth' => 'normal',
        'font_bottom_size' => 12,
        'font_bottom_weigth' => 'normal',        
        'font_bottom_style' => '',
        'font_cpt' => 'Pacifico',
        'color_cpt' => '#333333',
        'enable_demo' => 0,
        'color_field_text' => '#333333',
        'color_text_button' => '#ffffff',
        'color_field_background' => '#F1F1F1',
        'color_field_border' => '#333333',
        'color_button_onclick' => '#333333',
        'color_button_hover' => '#cccccc',
        'color_button' => '#1e73be',
        'image_width' => 250,
        'image_height' => 100,
        'newletter' => 0,
        'active_cpt' => 0,
        'newletter_font_text' => 'Pacifico',
        'newletter_size' => 18,
        'newletter_font_style' => '',
        'newletter_font_weigth' => 'normal',
        'type_newletter' => 'shortcode',
        'title_newletter' => '',
        'code_newletter' => '',
        'code_analytics' => '',
        'domain_analytics' => $_SERVER['SERVER_NAME'],
        'text_bt_maintenance' => '',
        'add_wplogin' => '',
        'b_enable_image' => 0,
        'disable' => 0,
        'pageperso' => 0,
        'date_cpt_size' => 40,
        'color_bg_header' => '#f1f1f1',
        'add_wplogin_title' => '',
        'headercode' => '',
        'message_cpt_fin' => '',
        'b_repeat_image' => '',
        'color_cpt_bg' => '',
        'enable_slider' => 0,
        'container_active' => 0,
        'container_color' => '#ffffff',
        'container_opacity' => '0.5',
        'container_width' => 80
        
    );
    $getMaintenanceSettings = get_option('wp_maintenance_settings');
    if (!empty($getMaintenanceSettings)) {
        foreach ($getMaintenanceSettings as $key => $option) {
            $wp_maintenanceAdminOptions[$key] = $option;
        }
    }
    update_option('wp_maintenance_settings', $wp_maintenanceAdminOptions);
    if(!get_option('wp_maintenance_active')) { update_option('wp_maintenance_active', 0); }

    if(!get_option('wp_maintenance_style') or get_option('wp_maintenance_style')=='') { 
        update_option('wp_maintenance_style', wpm_print_style());
    }
    
    $getMaintenanceSettings = extract(get_option('wp_maintenance_settings'));
    if( $getMaintenanceSettings['enable_demo']==1 ) {
        $hook = add_options_page(__( 'Options for the maintenance page', 'wp-maintenance' ), "WP Maintenance",  'read', __FILE__, "wpm_admin_panel");
    } else {
        $hook = add_options_page(__( 'Options for the maintenance page', 'wp-maintenance' ), "WP Maintenance",  'manage_options', __FILE__, "wpm_admin_panel");
    }
    
}
/* recupere le protocole */
function wpm_protocole() {
    return $_SERVER['HTTPS'];
}

function wpm_admin_scripts() {
    
    wp_enqueue_script('media-upload');
    wp_enqueue_script('thickbox');
    
    wp_register_script('wpm-my-upload', WP_PLUGIN_URL.'/wp-maintenance/js/wpm-script.js', array('jquery','media-upload','thickbox'));
    wp_enqueue_script('wpm-my-upload');
    
    wp_enqueue_style('jquery-defaut-style', WP_PLUGIN_URL.'/wp-maintenance/js/lib/themes/default.css');
    wp_enqueue_style('jquery-date-style', WP_PLUGIN_URL.'/wp-maintenance/js/lib/themes/default.date.css');
    wp_enqueue_style('jquery-time-style', WP_PLUGIN_URL.'/wp-maintenance/js/lib/themes/default.time.css');
    wp_enqueue_style('jquery-fontselect-style', WP_PLUGIN_URL.'/wp-maintenance/js/fontselect/fontselect.css');
    // If you're not including an image upload then you can leave this function call out
    wp_enqueue_media();
    
    // Now we can localize the script with our data.
    wp_localize_script( 'wpm-my-upload', 'Data', array(
      'textebutton'  =>  __( 'Choose This Image', 'wp-maintenance' ),
      'title'  => __( 'Choose Image', 'wp-maintenance' ),
    ) );
    
    wp_register_script('wpm-admin-fontselect', WP_PLUGIN_URL.'/wp-maintenance/js/fontselect/jquery.fontselect.min.js');
    wp_enqueue_script('wpm-admin-fontselect');
    
    //if( wpm_protocole()=='' ) {
    wp_register_script('wpm-admin-settings', WP_PLUGIN_URL.'/wp-maintenance/js/wpm-admin-settings.js');
    wp_enqueue_script('wpm-admin-settings');
    //}
}

//}
function wpm_print_footer_scripts() {
    wp_register_script('wpm-picker', WP_PLUGIN_URL.'/wp-maintenance/js/lib/picker.js');
    wp_enqueue_script('wpm-picker');
    wp_register_script('wpm-datepicker', WP_PLUGIN_URL.'/wp-maintenance/js/lib/picker.date.js');
    wp_enqueue_script('wpm-datepicker');
    wp_register_script('wpm-timepicker', WP_PLUGIN_URL.'/wp-maintenance/js/lib/picker.time.js');
    wp_enqueue_script('wpm-timepicker');
    wp_register_script('wpm-legacy', WP_PLUGIN_URL.'/wp-maintenance/js/lib/legacy.js');
    wp_enqueue_script('wpm-legacy');
}

add_action( 'admin_enqueue_scripts', 'wpm_enqueue_color_picker' );
function wpm_enqueue_color_picker( $hook_suffix ) {
    // first check that $hook_suffix is appropriate for your admin page
    wp_enqueue_style( 'wp-color-picker' );
    wp_enqueue_script( 'my-script-handle', plugins_url('js/wpm-color-options.js', __FILE__ ), array( 'wp-color-picker' ), false, true );
}

function wpm_admin_styles() {
    wp_enqueue_style('thickbox');
    wp_enqueue_style('jquery-style', 'https://ajax.googleapis.com/ajax/libs/jqueryui/1.8.2/themes/smoothness/jquery-ui.css');
}

if (isset($_GET['page']) && $_GET['page'] == 'wp-maintenance/wp-maintenance.php') {
    add_action('admin_footer', 'wpm_print_footer_scripts');
    add_action('admin_print_scripts', 'wpm_admin_scripts');
    add_action('admin_print_styles', 'wpm_admin_styles');
    add_action('admin_print_scripts', 'wpm_admin_scripts');
}

function wpm_change_active($value = 0) {

    update_option('wp_maintenance_active', $value);
    $statusActive = get_option('wp_maintenance_active');
    if( isset($statusActive)  ) {
        return $statusActive;
    }
}

function wpm_array_value_count ($array) {
    $count = 0;
   
    foreach ($array as $key => $value)
    {
            if($value) { $count++; }
    }
   
    return $count;
} 

function wpm_analytics_shortcode( $atts ) {

    if(get_option('wp_maintenance_settings')) { extract(get_option('wp_maintenance_settings')); }
    $paramMMode = get_option('wp_maintenance_settings');
    
	// Attributes
	extract( shortcode_atts(
		array(
			'enable' => 0,
            'code' => $paramMMode['code_analytics'],
            'domain' => ''.$_SERVER['SERVER_NAME'].''
		), $atts )
	);

    if( isset($enable) && $enable==1 && $code!='') {
        return "<script>
                  (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
                  (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
                  m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
                  })(window,document,'script','//www.google-analytics.com/analytics.js','ga');

                  ga('create', '".$code."', '".$domain."');
                  ga('send', 'pageview');

                </script>";
    } else {
        // Code
        return '';
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
        $paramSocialOption['size'] = 24;
    }
        
	// Attributes
	extract( shortcode_atts(
		array(
			'size' => 48,
            'enable' => 0
		), $atts )
	);
    if($paramSocialOption['theme']!='') {
        $srcIcon = get_stylesheet_directory_uri().'/'.$paramSocialOption['theme'].'/';
        $iconSize = 'width='.$paramSocialOption['size'];
    } else {
        $srcIcon = WP_CONTENT_URL.'/plugins/wp-maintenance/socialicons/'.$paramSocialOption['size'].'x'.$paramSocialOption['size'].'/';
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

function wpm_get_template() {
    
    return '
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta name="viewport" content="width=device-width, user-scalable=yes" />
	<title>%TITLE%</title>
    
	<style type=\'text/css\'>
        /* VERSION %VERSION% */
        %ADDFONTS%
        html,
        body {
            margin:0;
            padding:0;
            height:100%;
            font-size:100%;
        }
        #wrapper {
            min-height:100%;
            position:relative;
        }
        #header {
            padding:10px;
        }
        #content {
            padding-bottom:100px; /* Height of the footer element */
        }
        #footer {
            width:100%;
            height:60px;
            line-height:60px;
            position:absolute;
            bottom:0;
            left:0;
            text-align: center;
        }
        #logo {
            max-width: 100%;
            height: auto;
            text-align: center;
        }
        img, object, embed, canvas, video, audio, picture {
            max-width: 100%;
            height: auto;
        } 
        div.bloc {
            width:80%; /* largeur du bloc */
            padding:10px; /* aération interne du bloc */
            vertical-align:middle;
            display:inline-block;
            line-height:1.2; /* on rétablit le line-height */
            text-align:center; /* ... et l\'alignement du texte */ 
        }
        .wpm_social {
            padding: 0 45px;
            text-align: center;
        }
        @media (max-width: 640px) {
          body {
            font-size:1.2rem;
          }
        }
        @media (min-width: 640px) {
          body {
            font-size:1rem;
          }
        }
        @media (min-width:960px) {
          body {
            font-size:1.2rem;
          }
        }
        @media (min-width:1100px) {
          body {
            font-size:1.5rem;
          }
        }
        /* On ajoute les styles */
        %ADDSTYLEWYSIJA%
        %ADDSTYLE%
        
    </style>

	<!--[if lt IE 7]>
		<style type="text/css">
			#wrapper { height:100%; }
            div.bloc { display:inline; /* correctif inline-block*/ }
            div.conteneur > span { zoom:1; /* layout */ }
		</style>
	<![endif]-->
	%ANALYTICS%
    %HEADERCODE%
    %CSSSLIDER%
    %SCRIPTSLIDER%
    %SCRIPTSLIDESHOW%
</head>

<body>

	<div id="wrapper">
		
        %TOPSOCIALICON%
        <!-- #header -->
		
		<div id="content">
            %SLIDESHOWAL%
            %LOGOIMAGE%
            %SLIDESHOWBL%
            <div id="sscontent">
                <h3>%TITRE%</h3>
                <p>%TEXTE%</p>
                %SLIDESHOWBT%
                %COUNTER%
                %NEWSLETTER%
            </div>
            %BOTTOMSOCIALICON%
		</div><!-- #content -->
		
		<div id="footer">
            <div class="bloc">%COPYRIGHT%</div>
            <span></span>
		</div><!-- #footer -->
		
	</div><!-- #wrapper -->
	
</body>

</html>
';
    
}

/* Mode Maintenance */
function wpm_maintenance_mode() {

    global $current_user;

    if(get_option('wp_maintenance_settings')) { extract(get_option('wp_maintenance_settings')); }
    $paramMMode = get_option('wp_maintenance_settings');
    
    if( isset($paramMMode) && !empty($paramMMode)  ) {
        foreach($paramMMode as $var =>$value) {
            $paramMMode[$var] = ''.$value.'';
        }
    }
    
    if(get_option('wp_maintenance_slider')) { extract(get_option('wp_maintenance_slider')); }
    $paramSlider = get_option('wp_maintenance_slider');
    
    if(get_option('wp_maintenance_slider_options')) { extract(get_option('wp_maintenance_slider_options')); }
    $paramSliderOptions = get_option('wp_maintenance_slider_options');
    
    if(get_option('wp_maintenance_limit')) { extract(get_option('wp_maintenance_limit')); }
    $paramLimit = get_option('wp_maintenance_limit');
    $statusActive = get_option('wp_maintenance_active');

    // Récupère les ip autorisee
    $paramIpAddress = get_option('wp_maintenance_ipaddresses');
    
    if( !isset($paramMMode['active']) ) { $paramMMode['active'] = 0 ; }
    if( !isset($statusActive) ) { update_option('wp_maintenance_active', $paramMMode['active']); }

    $paramSocialOption = get_option('wp_maintenance_social_options');
    
    
    /* Désactive le mode maintenance pour les IP définies */
    if( isset($paramIpAddress) ) { 
        //$paramIpAddress = explode("\n", $paramIpAddress);
        if( strpos($paramIpAddress, wpm_get_ip())!== false ) {
            $statusActive = 0; 
        }
    }
    
    /* Désactive le mode maintenance pour les Roles définis */
    if( isset($paramLimit) && count($paramLimit)>1 ) {
        foreach($paramLimit as $limitrole) {
            if( current_user_can($limitrole) == true ) {
                $statusActive = 0;
            }
        }
    }

    /* Désactive le mode maintenance pour les PAGE ID définies */
    if( isset($paramMMode['id_pages']) ) { 
        $listPageId = explode(',', $paramMMode['id_pages']);
        foreach($listPageId as $keyPageId => $valPageId) {
            if( $valPageId == get_the_ID() ) {
                $statusActive = 0; 
            } 
            //echo 'Status: '.$statusActive.' - Page: '.$valPageId.' - ID:'.get_the_ID().'<br />';
        } 
    }
    
    /* On désactive le mode maintenance pour les admins */
    if( current_user_can('administrator') == true ) {
        $statusActive = 0;
    }
    
    /* on doit retourner 12/31/2020 5:00 AM */
    $dateNow = strtotime(date("Y-m-d H:i:s")) + 3600 * get_option('gmt_offset');
    if( get_option('wp_maintenance_version') <= '2.7.0') {
        $dateFinCpt = strtotime( date($paramMMode['date_cpt_jj'].'-'.$paramMMode['date_cpt_mm'].'-'.$paramMMode['date_cpt_aa'].' '.$paramMMode['date_cpt_hh'].':'.$paramMMode['date_cpt_mn'].':'.$paramMMode['date_cpt_ss']) );
    } else if( isset($paramMMode['cptdate']) && !empty($paramMMode['cptdate']) ) {
        $dateFinCpt = strtotime( date( str_replace('/', '-', $paramMMode['cptdate']).' '.$paramMMode['cpttime'].':00') );
        $dateCpt = date( 'm/d/Y h:i A', strtotime( $paramMMode['cptdate'].' '.$paramMMode['cpttime'] ) );
    }
    
    /* Si on désactive le mode maintenance en fin de compte à rebours */
    if( ( isset($paramMMode['disable']) && $paramMMode['disable']==1 ) && $statusActive == 1 ) {

        if( $dateNow > $dateFinCpt ) {
            $ChangeStatus = wpm_change_active();
            $statusActive = 0;
        }
        
    }

    if ($statusActive == 1) {
        
        if ( file_exists( get_stylesheet_directory() ) ) {
            $urlTpl = get_stylesheet_directory();
        } else {
            $urlTpl = get_template_directory();
        }
        
        if( $paramMMode['pageperso']==1 && file_exists($urlTpl.'/maintenance.php') ) {

            include_once( $urlTpl.'/maintenance.php' );
            die();
            
        } else {

            $wpmStyle = '';
            
            $site_title = get_bloginfo( 'name', 'display' );
            $site_description = get_bloginfo( 'description', 'display' );

            /* Si container activé */
            if( isset($paramMMode['container_active']) && $paramMMode['container_active'] == 1 ) {
                
                if( empty($paramMMode['container_opacity']) ) { $paramMMode['container_opacity'] = 0.5; }
                if( empty($paramMMode['container_width']) ) { $paramMMode['container_width'] = 80; }
                if( empty($paramMMode['container_color']) ) { $paramMMode['container_color'] = '#ffffff'; }
                if( isset($paramMMode['container_color']) ) { 
                    $paramRGBColor = wpm_hex2rgb($paramMMode['container_color']); 
                }

                $wpmStyle .= '
#sscontent {
    background-color: rgba('.$paramRGBColor['rouge'].','.$paramRGBColor['vert'].','.$paramRGBColor['bleu'].', '.$paramMMode['container_opacity'].');
    padding:0.8em;
    margin-left:auto;
    margin-right:auto;
    width:'.$paramMMode['container_width'].'%;
}
';
            }
            
            /* Défninition des couleurs par défault */
            if( !isset($paramMMode['color_bg']) || $paramMMode['color_bg']=="") { $paramMMode['color_bg'] = "#f1f1f1"; }
            if( !isset($paramMMode['color_txt']) || $paramMMode['color_txt']=="") { $paramMMode['color_txt'] = "#888888"; }        

            /* Traitement de la feuille de style */
            $styleRemplacements = array (
                "#_COLORTXT" => $paramMMode['color_txt'],
                "#_COLORBG" => $paramMMode['color_bg'],
                "#_COLORCPTBG" => $paramMMode['color_cpt_bg'],
                "#_DATESIZE" => $paramMMode['date_cpt_size'],
                "#_COLORCPT" => $paramMMode['color_cpt'],
                "#_COLOR_BG_BT" => $paramMMode['color_bg_bottom'],
                "#_COLOR_TXT_BT" => $paramMMode['color_text_bottom'],
                "#_COLORHEAD" => $paramMMode['color_bg_header'],
            );
            $wpmStyle .= str_replace(array_keys($styleRemplacements), array_values($styleRemplacements), get_option('wp_maintenance_style'));
            if($paramMMode['message_cpt_fin']=='') { $paramMMode['message_cpt_fin'] = '&nbsp;'; }

  
            $template_page = wpm_get_template();
            
            $Counter = '';
            $addFormLogin = '';
            $newLetter = '';
            
            if( isset($paramMMode['analytics']) && $paramMMode['analytics']!='') { 
                $CodeAnalytics = do_shortcode('[wpm_analytics enable="'.$paramMMode['analytics'].'"]');
            } else {
                $CodeAnalytics = '';
            }
            if( isset($paramMMode['headercode']) && $paramMMode['headercode']!='') { 
                $HeaderCode = stripslashes($paramMMode['headercode']);
            } else {
                $HeaderCode = '';
            }
            if( isset($paramSocialOption['position']) && $paramSocialOption['position']=='top') { 
                $TopSocialIcons = '<div id="header">'.do_shortcode('[wpm_social]').'</div>';
            } else {
                $TopSocialIcons = '';
            }
            if( isset($paramSocialOption['position']) && $paramSocialOption['position']=='bottom') { 
                $BottomSocialIcons = do_shortcode('[wpm_social]');
            } else {
                $BottomSocialIcons = '';
            }
            if( isset($paramMMode['image']) && $paramMMode['image'] ) { 
                if( ini_get('allow_url_fopen')==1) {
                    $image_path = str_replace(get_bloginfo('url'), ABSPATH, $paramMMode['image']);
                    list($logoWidth, $logoHeight, $logoType, $logoAttr) = getimagesize($image_path);
                } else {
                    $width = 150;
                    $height = 80;
                }
                $LogoImage = '<div id="logo"><img src="'.$paramMMode['image'].'" width="'.$logoWidth.'" height="'.$logoHeight.'" alt="'.get_bloginfo( 'name', 'display' ).' '.get_bloginfo( 'description', 'display' ).'" title="'.get_bloginfo( 'name', 'display' ).' '.get_bloginfo( 'description', 'display' ).'" /></div>';
            } else {
                $LogoImage = '';
            }
            
            if( isset($paramMMode['text_bt_maintenance']) && $paramMMode['text_bt_maintenance']!='' ) { 
                $TextCopyright = stripslashes($paramMMode['text_bt_maintenance']);
            } else {
                $TextCopyright = '';
            }
            if( (isset($paramMMode['add_wplogin']) && $paramMMode['add_wplogin']==1) && (isset($paramMMode['add_wplogin_title']) && $paramMMode['add_wplogin_title']!='') ) {
                $textLogin = str_replace('%DASHBOARD%', '<a href="'.get_admin_url().'">'.__('Dashboard', 'wp-maintenance').'</a>', $paramMMode['add_wplogin_title']);
                $TextCopyright .= '<br />'.$textLogin;
                
            }
            if( isset($paramMMode['titre_maintenance']) && $paramMMode['titre_maintenance']!='' ) { 
                $Titre = stripslashes($paramMMode['titre_maintenance']);
            } else {
                $Titre = '';
            }
            if( isset($paramMMode['text_maintenance']) && $paramMMode['text_maintenance']!='' ) { 
                $Texte = stripslashes($paramMMode['text_maintenance']);
            } else {
                $Texte = '';
            }
            $wysijaStyle = '/* no NEWLETTER Style */';
            if( isset($paramMMode['newletter']) && $paramMMode['newletter']==1 && isset($paramMMode['code_newletter']) && $paramMMode['code_newletter']!='' ) {
                
                if( empty($paramMMode['color_field_text']) ) { $paramMMode['color_field_text'] = '#333333'; }
                    if( empty($paramMMode['color_text_button']) ) { $paramMMode['color_text_button']= '#ffffff'; }
                    if( empty($paramMMode['color_field_background']) ) { $paramMMode['color_field_background']= '#F1F1F1'; }
                    if( empty($paramMMode['color_field_border']) ) { $paramMMode['color_field_border']= '#333333'; }
                    if( empty($paramMMode['color_button_onclick']) ) { $paramMMode['color_button_onclick']= '#333333'; }
                    if( empty($paramMMode['color_button_hover']) ) { $paramMMode['color_button_hover']= '#cccccc'; }
                    if( empty($paramMMode['color_button']) ) { $paramMMode['color_button']= '#1e73be'; }
                    
                    $wysijaRemplacements = array (
                        "#_COLORTXT" => $paramMMode['color_field_text'],
                        "#_COLORBG" => $paramMMode['color_field_background'],
                        "#_COLORBORDER" => $paramMMode['color_field_border'],
                        "#_COLORBUTTON" => $paramMMode['color_button'],
                        "#_COLORTEXTBUTTON" => $paramMMode['color_text_button'],
                        "#_COLOR_BTN_HOVER" => $paramMMode['color_button_hover'],
                        "#_COLOR_BTN_CLICK" => $paramMMode['color_button_onclick']
                    );
                
                if( strpos($paramMMode['code_newletter'], 'wysija_form') == 1 ) {
                    
                    $wysijaStyle = str_replace(array_keys($wysijaRemplacements), array_values($wysijaRemplacements), wpm_wysija_style() );
                    
                } else if( strpos($paramMMode['code_newletter'], 'mc4wp_form') == 1 ) {
                    
                    $wysijaStyle = str_replace(array_keys($wysijaRemplacements), array_values($wysijaRemplacements), wpm_mc4wp_style() );
                    
                }
                $newLetter = '<div class="wpm_newletter">'.stripslashes($paramMMode['title_newletter']);
                if( isset($paramMMode['type_newletter']) && isset($paramMMode['iframe_newletter']) && $paramMMode['iframe_newletter']!='' && $paramMMode['type_newletter']=='iframe' ) {
                    $newLetter .= stripslashes($paramMMode['iframe_newletter']);                    
                }
                if( isset($paramMMode['type_newletter']) && isset($paramMMode['code_newletter']) && $paramMMode['code_newletter']!='' && $paramMMode['type_newletter']=='shortcode'  ) {
                    $newLetter .= do_shortcode(stripslashes($paramMMode['code_newletter']));
                }
                $newLetter .= '</div>';
            }
               
            $optionBackground = '';
            $addBImage = '';
            if( (isset($paramMMode['b_image']) && $paramMMode['b_image']) && (isset($paramMMode['b_enable_image']) && $paramMMode['b_enable_image']==1) ) {
                if( isset($paramMMode['b_repeat_image']) || $paramMMode['b_repeat_image']=='') { $paramMMode['b_repeat_image'] = 'repeat'; }
                if( isset($paramMMode['b_fixed_image']) && $paramMMode['b_fixed_image']==1 ) { 
                    $optionBackground = 'background-attachment:fixed;';
                }
            $addBImage = '
body {
    background-image:url('.$paramMMode['b_image'].');
    background-size: cover;
    -webkit-background-size: cover;
    -moz-background-size: cover;
    -o-background-size: cover;
    background-repeat: '.$paramMMode['b_repeat_image'].';
    '.$optionBackground.'
}';
            }
            if( isset($paramMMode['b_pattern']) && $paramMMode['b_pattern']>0 && $paramMMode['b_enable_image']==1) {
            $addBImage = '
body {
	background-image: url('.WP_PLUGIN_URL.'/wp-maintenance/images/pattern'.$paramMMode['b_pattern'].'.png);
    background-repeat: '.$paramMMode['b_repeat_image'].';
    '.$optionBackground.'
}';        }

            /*********** AJOUT COMPTEUR SUIVANT LES PARAMETRES *********/
            if( isset($paramMMode['active_cpt']) && $paramMMode['active_cpt']==1) {
                
                if( isset($paramMMode['message_cpt_fin']) && $paramMMode['message_cpt_fin']!='' && (isset($paramMMode['cptdate']) && !empty($paramMMode['cptdate'])) ) {
                    if( !isset($paramMMode['disable']) ) { $paramMMode['disable'] = 0; }
                $Counter = '
                <div id="countdown">
                    <script language="JavaScript">
                        TargetDate = "'.$dateCpt.'";
                        BackColor = "'.$paramMMode['color_cpt_bg'].'";
                        FontSize = "'.$paramMMode['date_cpt_size'].'";
                        ForeColor = "'.$paramMMode['color_cpt'].'";
                        Disable = "'.$paramMMode['disable'].'";
                        UrlDisable = "'.get_option( 'siteurl').'";
                        FontFamily = "'.$paramMMode['font_cpt'].'";
                        CountActive = true;
                        CountStepper = -1;
                        LeadingZero = true;
                ';
                $Counter .= "   DisplayFormat = '<div id=\"wpm-cpt-day\">%%D%%<br /><span id=\"wpm-cpt-days-span\">".__('Days', 'wp-maintenance')."</span></div><div id=\"wpm-cpt-hours\">%%H%%<br /><span id=\"wpm-cpt-hours-span\">".__('Hours', 'wp-maintenance')."</span></div><div id=\"wpm-cpt-minutes\">%%M%%<br /><span id=\"wpm-cpt-minutes-span\">".__('Minutes', 'wp-maintenance')."</span></div>";
                if( isset($paramMMode['active_cpt_s']) && $paramMMode['active_cpt_s']==1 ) {
                    $Counter .= '<div id="wpm-cpt-seconds">%%S%%<br /><span id="wpm-cpt-seconds-span">'.__('Seconds', 'wp-maintenance').'</span></div>';
                }
                $Counter .= "';";
                $Counter .= '
                    FinishMessage = "'.trim( stripslashes( preg_replace("/(\r\n|\n|\r)/", "", $paramMMode['message_cpt_fin']) ) ).'";
                    </script>';
                $Counter .= '
                <script language="JavaScript" src="'.WP_PLUGIN_URL.'/wp-maintenance/js/wpm-cpt-script.js"></script>
                </div>';
                } 
            }
            
            /*********** AJOUT DU STYLE SUIVANT LES PARAMETRES *********/
            $wpmFonts = '
            @import url(https://fonts.googleapis.com/css?family='.str_replace(' ', '+', $paramMMode['font_title']).'|'.str_replace(' ', '+',$paramMMode['font_text']).'|'.str_replace(' ', '+',$paramMMode['font_text_bottom']).'|'.str_replace(' ', '+',$paramMMode['font_cpt']);
            if( isset($paramMMode['newletter_font_text']) && $paramMMode['newletter_font_text'] != '') {
                $wpmFonts .= '|'.str_replace(' ', '+',$paramMMode['newletter_font_text']);
            }
            $wpmFonts .= ');';
            
            /* Si on a une couleur de fond */
            if( isset($paramMMode['color_bg'])  && $paramMMode['color_bg']!='' ) {
                $wpmStyle .= 'body { background-color: '.$paramMMode['color_bg'].'; }';
            }
            
            $wpmStyle .= ''.$addBImage.'
.wpm_social_icon {
    float:left;
    width:'.$paramSocialOption['size'].'px;
    margin:0px 5px auto;
}
.wpm_social ul {
    margin: 10px 0;
    max-width: 100%;
    padding: 0;
    text-align: '.$paramSocialOption['align'].';
}
';
$wpmStyle .= '         
.wpm_newletter {';
    if( isset($paramMMode['newletter_size']) ) { $wpmStyle .= 'font-size: '.$paramMMode['newletter_size'].'px;'; }
    if( isset($paramMMode['newletter_font_style']) ) { $wpmStyle .= 'font-style: '.$paramMMode['newletter_font_style'].';'; }
    if( isset($paramMMode['newletter_font_weigth']) ) { $wpmStyle .= 'font-weight: '.$paramMMode['newletter_font_weigth'].';'; }
    if( isset($paramMMode['newletter_font_text']) ) { $wpmStyle .= 'font-family: '.wpm_format_font($paramMMode['newletter_font_text']).', serif;'; }
$wpmStyle .= '}';

$wpmStyle .= '
h3 {';
    if( isset($paramMMode['font_title']) ) { $wpmStyle .= 'font-family: '.wpm_format_font($paramMMode['font_title']).', serif;'; }
    if( isset($paramMMode['font_title_size']) ) { $wpmStyle .= 'font-size: '.$paramMMode['font_title_size'].'px;'; }
    if( isset($paramMMode['font_title_style']) ) { $wpmStyle .= 'font-style: '.$paramMMode['font_title_style'].';'; }
    if( isset($paramMMode['font_title_weigth']) ) { $wpmStyle .= 'font-weight: '.$paramMMode['font_title_weigth'].';'; }
    if( isset($paramMMode['color_txt']) ) { $wpmStyle .= 'color:'.$paramMMode['color_txt'].';'; }
$wpmStyle .= '
    line-height: 100%;
    text-align:center;
    margin:0.5em auto;
}
p {';        
    if( isset($paramMMode['font_text']) ) { $wpmStyle .= 'font-family: '.wpm_format_font($paramMMode['font_text']).', serif;'; }
    if( isset($paramMMode['font_text_size']) ) { $wpmStyle .= 'font-size: '.$paramMMode['font_text_size'].'px;'; }
    if( isset($paramMMode['font_text_style']) ) { $wpmStyle .= 'font-style: '.$paramMMode['font_text_style'].';'; }
    if( isset($paramMMode['font_text_weigth']) ) { $wpmStyle .= 'font-weight: '.$paramMMode['font_text_weigth'].';'; }
    if( isset($paramMMode['color_txt']) ) { $wpmStyle .= 'color:'.$paramMMode['color_txt'].';'; }
$wpmStyle .= '            
    line-height: 100%;
    text-align:center;
    margin:0.5em auto;
    padding-left:2%;
    padding-right:2%;
    
}';
if( (isset($paramMMode['text_bt_maintenance']) && $paramMMode['text_bt_maintenance']!='') or ( (isset($paramMMode['add_wplogin']) && $paramMMode['add_wplogin']==1) && (isset($paramMMode['add_wplogin_title']) && $paramMMode['add_wplogin_title']!='') ) ) {
$wpmStyle .= '#footer {';
    if( isset($paramMMode['color_bg_bottom']) ) { $wpmStyle .= 'background:'.$paramMMode['color_bg_bottom'].';'; }
$wpmStyle .= '}';
}
$wpmStyle .= '            
div.bloc {';
    if( isset($paramMMode['font_text_bottom']) ) { $wpmStyle .= 'font-family: '.wpm_format_font($paramMMode['font_text_bottom']).', serif;'; }
    if( isset($paramMMode['font_bottom_style']) ) { $wpmStyle .= 'font-style: '.$paramMMode['font_bottom_style'].';'; }
    if( isset($paramMMode['font_bottom_size']) ) { $wpmStyle .= 'font-size: '.$paramMMode['font_bottom_size'].'px;'; }
    if( isset($paramMMode['font_bottom_weigth']) ) { $wpmStyle .= 'font-weight: '.$paramMMode['font_bottom_weigth'].';'; }
    if( isset($paramMMode['color_text_bottom']) ) { $wpmStyle .= 'color: '.$paramMMode['color_text_bottom'].';'; }
$wpmStyle .= '
    text-decoration:none;
}
div.bloc a:link {';
    if( isset($paramMMode['color_text_bottom']) ) { $wpmStyle .= 'color:'.$paramMMode['color_text_bottom'].';'; }
    if( isset($paramMMode['font_bottom_size']) ) { $wpmStyle .= 'font-size: '.$paramMMode['font_bottom_size'].'px;'; }
 $wpmStyle .= '   
    text-decoration:none;
}
div.bloc a:visited {';
    if( isset($paramMMode['color_text_bottom']) ) { $wpmStyle .= 'color:'.$paramMMode['color_text_bottom'].';'; }
    if( isset($paramMMode['font_bottom_size']) ) { $wpmStyle .= 'font-size: '.$paramMMode['font_bottom_size'].'px;'; }
 $wpmStyle .= ' 
    text-decoration:none;
}
div.bloc a:hover {
    text-decoration:underline;';
    if( isset($paramMMode['font_bottom_size']) ) { $wpmStyle .= 'font-size: '.$paramMMode['font_bottom_size'].'px;'; }
$wpmStyle .= '
}
#wpm-cpt-day, #wpm-cpt-hours, #wpm-cpt-minutes, #wpm-cpt-seconds {}
.cptR-rec_countdown {';
if( isset($paramMMode['date_cpt_size']) ) { $wpmStyle .= 'font-size:'.$paramMMode['date_cpt_size'].'px;'; }
if( isset($paramMMode['font_cpt']) ) { $wpmStyle .= 'font-family: '.wpm_format_font($paramMMode['font_cpt']).', serif;'; }
$wpmStyle .= '
}

@media screen and (min-width: 200px) and (max-width: 480px) {

    .cptR-rec_countdown {';
        if( isset($paramMMode['date_cpt_size']) ) { $wpmStyle .= 'font-size:'.($paramMMode['date_cpt_size']*0.6).'px;'; }
    $wpmStyle .= '}
    div.bloc {';
        if( isset($paramMMode['font_bottom_size']) ) { $wpmStyle .= 'font-size: '.($paramMMode['font_bottom_size']*0.8).'px;'; }
    $wpmStyle .= '}
    #wpm-cpt-day, #wpm-cpt-hours, #wpm-cpt-minutes, #wpm-cpt-seconds {
        /*width:20%;*/
    }

}
@media (max-width: 640px) {
    .cptR-rec_countdown {';
        if( isset($paramMMode['date_cpt_size']) ) { $wpmStyle .= 'font-size:'.($paramMMode['date_cpt_size']*0.5).'px;'; }
    $wpmStyle .= '
        text-align:center;
    } 
    #wpm-cpt-day, #wpm-cpt-hours, #wpm-cpt-minutes, #wpm-cpt-seconds {
        /*width:20%;*/
        text-align:center;
    }
}

                
            ';
            $addScriptSlideshow = '';
            $addCssSlider = '';
            $addScriptSlider = '';
            $slides = '';
            if( (isset($paramMMode['enable_slider']) && $paramMMode['enable_slider']==1) ) {
                
                $lastKeySlide = key($paramSlider['slider_image']); 
                
                $wpmSliderAuto = 'true';
                if( isset( $paramSliderOptions['slider_auto'] ) && $paramSliderOptions['slider_auto']!='' ) { 
                    $wpmSliderAuto = $paramSliderOptions['slider_auto'];
                }
                $wpmSliderSpeed = 500;
                if( isset( $paramSliderOptions['slider_speed'] ) && $paramSliderOptions['slider_speed']!='' ) { 
                    $wpmSliderSpeed = $paramSliderOptions['slider_speed'];
                }
                $wpmSliderNav = 'false';
                if( isset( $paramSliderOptions['slider_nav'] ) && $paramSliderOptions['slider_nav']!='' ) { 
                    $wpmSliderNav = $paramSliderOptions['slider_nav'];
                }
                if( isset($paramSliderOptions['slider_width']) ) { $wpmSliderWidth = $paramSliderOptions['slider_width']; } else { $wpmSliderWidth = 50; }
                $addCssSlider = '
<link rel="stylesheet" href="'.WP_PLUGIN_URL.'/wp-maintenance/css/wpm-slideshow.css">
<link rel="stylesheet" href="'.WP_PLUGIN_URL.'/wp-maintenance/css/wpm-responsiveslides.css">
<style type=\'text/css\'>
.centered-btns_nav { background: transparent url("'.WP_PLUGIN_URL.'/wp-maintenance/images/themes.gif") no-repeat left top; } 
.large-btns_nav { background: #000 url("'.WP_PLUGIN_URL.'/wp-maintenance/images/themes.gif") no-repeat left 50%; }
.callbacks_container { width: '.$wpmSliderWidth.'%; }
@media (max-width: 640px) {
    .callbacks_container {
    width: 95%;
    }
    .callbacks_nav {
    top: 57%;
    }
}
.callbacks_nav { background: transparent url("'.WP_PLUGIN_URL.'/wp-maintenance/images/themes.gif") no-repeat left top; }
</style>

';
                $addScriptSlider = '
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.8.3/jquery.min.js"></script>
<script src="'.WP_PLUGIN_URL.'/wp-maintenance/js/wpm-responsiveslides.min.js"></script>';
                $addScriptSlideshow = '
<script>
    // You can also use "$(window).load(function() {"
    $(function () {';

                $addScriptSlideshow .= '
        $("#wpmslider").responsiveSlides({
            auto: '.$wpmSliderAuto.',
            pager: false,
            nav: '.$wpmSliderNav.',
            speed: '.$wpmSliderSpeed.',
            prevText: "'.__('Previous', 'wp-maintenance').'",
            nextText: "'.__('Next', 'wp-maintenance').'", 
            namespace: "callbacks",';
                $addScriptSlideshow .= "
            before: function () {
                  $('.events').append(\"<li>before event fired.</li>\");
                },
                after: function () {
                  $('.events').append(\"<li>after event fired.</li>\");
                }
        });";

                $addScriptSlideshow .= '
    });
</script>';

                $slides = '

                <!-- Slideshow 4 -->
                <div class="callbacks_container">
                  <ul class="rslides" id="wpmslider">';
                foreach($paramSlider['slider_image'] as $numSlide=>$slide) {
                    
                    if( $paramSlider['slider_image'][$numSlide]['image'] != '' ) { 
                        $slideImg = '';
                        if( isset($paramSlider['slider_image'][$numSlide]['image']) ) {
                            $slideImg = $paramSlider['slider_image'][$numSlide]['image'];
                        }
                        $slideLink = '';
                        if( isset($paramSlider['slider_image'][$numSlide]['link']) ) {
                            $slideLink = $paramSlider['slider_image'][$numSlide]['link'];
                        }
                        $slideText = '';
                        if( isset($paramSlider['slider_image'][$numSlide]['text']) ) {
                            $slideText = stripslashes($paramSlider['slider_image'][$numSlide]['text']);
                        }
                        $slides .= '
                        <li>';
                        if( $slideLink!='' && filter_var($slideLink, FILTER_VALIDATE_URL) ) {
                        $slides .= '
                          <a href="'.$slideLink.'" target="_bank">';
                        }
                        $slides .= '<img src="'.$slideImg.'" alt="'.$slideText.'" title="'.$slideText.'">';
                        if( $slideText!='' ) {
                        $slides .= '
                          <p class="caption">'.$slideText.'</p>';
                        }
                        if( $slideLink!='' && filter_var($slideLink, FILTER_VALIDATE_URL) ) {
                        $slides .= '</a>';
                        }
                        $slides .= '
                        </li>';
                    }
                }
                $slides .= '</ul>
                </div>';
            }
            
            $positionSliderAL = '';
            $positionSliderBL = '';
            $positionSliderBT = '';
            if( isset($paramSliderOptions['slider_position']) ) {
                  
                if( $paramSliderOptions['slider_position'] == 'abovelogo' ) {
                    $positionSliderAL = $slides;
                } else if( $paramSliderOptions['slider_position'] == 'belowlogo' ) {
                    $positionSliderBL = $slides;
                } else if( $paramSliderOptions['slider_position'] == 'belowtext' ) {
                    $positionSliderBT = $slides;
                }
                
            }
            
            $tplRemplacements = array (
                "%TITLE%" => get_bloginfo( 'name', 'display' ).' '.get_bloginfo( 'description', 'display' ),
                "%ANALYTICS%" => $CodeAnalytics,
                "%HEADERCODE%" => $paramMMode['headercode'],
                "%ADDSTYLE%" => $wpmStyle,
                "%ADDSTYLEWYSIJA%" => $wysijaStyle,
                "%ADDFONTS%" => $wpmFonts,
                "%TOPSOCIALICON%" => $TopSocialIcons,
                "%BOTTOMSOCIALICON%" => $BottomSocialIcons,
                "%LOGOIMAGE%" => $LogoImage,
                "%COPYRIGHT%" => $TextCopyright,
                "%COUNTER%" =>$Counter,
                "%VERSION%" => WPM_VERSION,
                "%TITRE%" => $Titre,
                "%TEXTE%" => $Texte,
                "%LOGINFORM%" => $addFormLogin,
                "%NEWSLETTER%" => $newLetter,
                "%CSSSLIDER%" => $addCssSlider,
                "%SCRIPTSLIDER%" => $addScriptSlider,
                "%SCRIPTSLIDESHOW%" => $addScriptSlideshow,
                "%SLIDESHOWAL%" => $positionSliderAL,
                "%SLIDESHOWBL%" => $positionSliderBL,
                "%SLIDESHOWBT%" => $positionSliderBT
            );
            $template_page = str_replace(array_keys($tplRemplacements), array_values($tplRemplacements), $template_page );
            
            $content = $template_page;
        }
        die($content);
    }

}
add_action('template_redirect', 'wpm_maintenance_mode');
//add_action('get_header', 'wpm_maintenance_mode');

if(function_exists('register_deactivation_hook')) {
    register_deactivation_hook(__FILE__, 'wpm_uninstall');
}

//intègre le tout aux pages Admin de Wordpress
add_action("admin_menu", "wpm_add_admin");

?>