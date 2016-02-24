<?php

/*
 * Plugin Name: WP Maintenance
 * Plugin URI: http://wordpress.org/extend/plugins/wp-maintenance/
 * Description: Le plugin WP Maintenance vous permet de mettre votre site en attente le temps pour vous de faire une maintenance ou du lancement de votre site. Personnalisez cette page de maintenance avec une image, un compte à rebours, etc... / The WP Maintenance plugin allows you to put your website on the waiting time for you to do maintenance or launch your website. Personalize this page with picture, countdown...
 * Author: Florent Maillefaud
 * Author URI: https://wpmaintenance.shost.ca
 * Version: 2.7.2
 * Text Domain: wp-maintenance
 * Domain Path: /languages/
 */


/*
Change Log
27/01/2016 - Corrige le bug compteur, ajout selection google font
11/12/2015 - Corrige le bug couleur de fond. Ajout DatePicker pour compteur
06/12/2015 - Autorise certaines IP
04/12/2015 - Correction notice php (undefined index)
17/09/2015 - Ajout accès au tableau de bord
11/09/2015 - Correction bug CSS Responsive
02/09/2015 - Correction notice php (undefined index)
07/08/2015 - Nouvelle version du plugin
18/04/2015 - Fixed a bug on the end of message counter
16/04/2015 - Résolution de divers bug CSS
28/03/2015 - Résolution de divers bug CSS Responsive
25/03/2015 - Résolution de divers bug CSS
19/03/2015 - Résolution de divers bugs CSS, ajout d'un titre encart newsletter, ajout champs code header
07/03/2015 - Résolution de divers bug CSS
04/12/2014 - Ajout d'une notification dans la barre d'admin / Résolution de divers bug CSS
03/12/2014 - Correction d'une notice sur un argument déprécié
09/08/2014 - Ajout de Fonts et Styles
17/07/2014 - Correction bug feuille de style
20/05/2014 - Correction bug upload d'image
04/05/2014 - Correction bug date fin compte à rebours
03/05/2014 - Correction bug drag&drop Réseaux Sociaux
01/05/2014 - Modifs countdown et icones réseaux sociaux..
30/04/2014 - Ajout code analytics, icones réseaux sociaux, newletter, image de fond...
31/12/2013 - Ajout des couleurs des liens et d'options supplémentaires
24/12/2013 - Bugs ajout de lien dans les textes
06/11/2013 - Bugs sur le compte à rebours
03/10/2013 - Bugs sur les couleurs
11/09/2013 - Conflits javascript résolus
30/08/2013 - CSS personnalisable
27/08/2013 - Ajout du multilangue
23/08/2013 - Refonte de l'admin et ajout d'un compte à rebours
16/02/2013 - Ajout ColorPicker
12/02/2013 - Ajout fonctionnalité et débugage
11/02/2013 - Modification nom de fonctions
24/01/2013 - Création du Plugin
*/

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
    wp_enqueue_script( 'jquery' );
    //wp_enqueue_script( 'jquery-ui-core' );
    wp_enqueue_script('jquery-ui-datepicker');
    //wp_enqueue_script( 'jquery-datepicker', WP_PLUGIN_URL.'/'.WPSPO_NAME_DIR.'/wpspo-js/jquery.ui.datepicker.min.js', array('jquery', 'jquery-ui-core' ) );
    //wp_enqueue_script('jquery-ui-fr-datepicker', WP_PLUGIN_URL.'/'.WPSPO_NAME_DIR.'/wpspo-js/jquery.ui.datepicker-fr.js', array('jquery-ui-datepicker'));
    wp_enqueue_style('jquery-style', 'http://ajax.googleapis.com/ajax/libs/jqueryui/1.8.2/themes/smoothness/jquery-ui.css');
    
}

// multilingue
add_action( 'init', 'wpm_make_multilang' );
function wpm_make_multilang() {
    load_plugin_textdomain('wp-maintenance', false, dirname( plugin_basename( __FILE__ ) ).'/languages');
}

/* Ajoute la version dans les options */
define('WPM_VERSION', '2.7.2');
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
    echo '<link rel="stylesheet" type="text/css" media="all" href="' .plugins_url('wpm-admin.css', __FILE__). '">';
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
        'color_text_button' => '#FFFFFF',
        'color_button' => '#1e73be',
        'image_width' => 250,
        'image_height' => 100,
        'newletter' => 0,
        'active_cpt' => 0,
        'newletter_font_text' => 'Pacifico',
        'newletter_size' => 18,
        'newletter_font_style' => '',
        'newletter_font_weigth' => 'normal',
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
add_action('admin_footer', 'wpm_print_footer_scripts');
function wpm_print_footer_scripts() {
    wp_register_script('wpm-picker', WP_PLUGIN_URL.'/wp-maintenance/js/lib/picker.js');
    wp_enqueue_script('wpm-picker');
    wp_register_script('wpm-datepicker', WP_PLUGIN_URL.'/wp-maintenance/js/lib/picker.date.js');
    wp_enqueue_script('wpm-datepicker');
    wp_register_script('wpm-timepicker', WP_PLUGIN_URL.'/wp-maintenance/js/lib/picker.time.js');
    wp_enqueue_script('wpm-timepicker');
    wp_register_script('wpm-legacy', WP_PLUGIN_URL.'/wp-maintenance/js/lib/legacy.js');
    wp_enqueue_script('wpm-legacy');
    //wp_register_script('wpm-footerscripts', WP_PLUGIN_URL.'/wp-maintenance/js/wpm-footer-scripts.js');
    //wp_enqueue_script('wpm-footerscripts');
    //$url = WP_PLUGIN_URL.'/wp-maintenance/js/wpm-footer-scripts.js';
    //echo '"<script type="text/javascript" src="'. $url . '"></script>"';
}

add_action( 'admin_enqueue_scripts', 'wpm_enqueue_color_picker' );
function wpm_enqueue_color_picker( $hook_suffix ) {
    // first check that $hook_suffix is appropriate for your admin page
    wp_enqueue_style( 'wp-color-picker' );
    wp_enqueue_script( 'my-script-handle', plugins_url('js/wpm-color-options.js', __FILE__ ), array( 'wp-color-picker' ), false, true );
}

function wpm_admin_styles() {
    wp_enqueue_style('thickbox');
    wp_enqueue_style('jquery-style', 'http://ajax.googleapis.com/ajax/libs/jqueryui/1.8.2/themes/smoothness/jquery-ui.css');
}

if (isset($_GET['page']) && $_GET['page'] == 'wp-maintenance/wp-maintenance.php') {
    add_action('admin_print_scripts', 'wpm_admin_scripts');
    add_action('admin_print_styles', 'wpm_admin_styles');
    add_action('admin_print_scripts', 'wpm_admin_scripts');
    //add_action('admin_footer', 'wpm_print_scripts');
}

function wpm_change_active($value = 0) {
    if($value>=0) {
        update_option('wp_maintenance_active', $value);
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
    $content = '';
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
         $content .= '<div id="wpm-social-footer" class="wpm_social"><ul class="wpm_horizontal">';
            foreach($paramSocial as $socialName=>$socialUrl) {
                if($socialUrl!='') {
                    $content .= '<li><a href="'.$socialUrl.'" target="_blank"><img src="'.$srcIcon.$socialName.'.png" alt="'.$paramSocialOption['texte'].' '.ucfirst($socialName).'" '.$iconSize.' title="'.$paramSocialOption['texte'].' '.ucfirst($socialName).'" /></a></li>';
                }
            }
         $content .='</ul></div>';
        return $content;
     } else {
        // Code
        return '';
    }
}
add_shortcode( 'wpm_social', 'wpm_social_shortcode' );

/* Mode Maintenance */
function wpm_maintenance_mode() {

    global $current_user;

    if(get_option('wp_maintenance_settings')) { extract(get_option('wp_maintenance_settings')); }
    $paramMMode = get_option('wp_maintenance_settings');
    
    if( isset($paramMMode) ) {
        foreach($paramMMode as $var =>$value) {
            $paramMMode[$var] = ''.$value.'';
        }
    }
    
    if(get_option('wp_maintenance_limit')) { extract(get_option('wp_maintenance_limit')); }
    $paramLimit = get_option('wp_maintenance_limit');
    $statusActive = get_option('wp_maintenance_active');

    // Récupère les ip autorisee
    $paramIpAddress = get_option('wp_maintenance_ipaddresses');
    
    get_currentuserinfo();

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
            $paramMMode['disable'] = 0;

            $wpm_options = array(
                'active_cpt' => 0,
                'disable' => 0,
            );
            update_option('wp_maintenance_settings', $wpm_options);
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

            $site_title = get_bloginfo( 'name', 'display' );
            $site_description = get_bloginfo( 'description', 'display' );

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
            $wpmStyle = str_replace(array_keys($styleRemplacements), array_values($styleRemplacements), get_option('wp_maintenance_style'));
            if($paramMMode['message_cpt_fin']=='') { $paramMMode['message_cpt_fin'] = '&nbsp;'; }

  
            $template_page = file_get_contents( WP_PLUGIN_URL.'/wp-maintenance/template/index.html' );
            
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
                $TopSocialIcons = do_shortcode('[wpm_social]');
            } else {
                $TopSocialIcons = '';
            }
            if( isset($paramSocialOption['position']) && $paramSocialOption['position']=='bottom') { 
                $BottomSocialIcons = do_shortcode('[wpm_social]');
            } else {
                $BottomSocialIcons = '';
            }
            if( isset($paramMMode['image']) && $paramMMode['image'] ) { 
                list($logoWidth, $logoHeight, $logoType, $logoAttr) = getimagesize($paramMMode['image']);
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
            $wysijaStyle = '/* no WYSIJA Style */';
            if( isset($paramMMode['newletter']) && $paramMMode['newletter']==1 && isset($paramMMode['code_newletter']) && $paramMMode['code_newletter']!='' ) {
                $nameNl = strpos($paramMMode['code_newletter'], 'wysija_form');
                if($nameNl==1) {
                    //$wysijaStyle = 'STYLEOK';
                    $wysijaRemplacements = array (
                        "#_COLORTXT" => $paramMMode['color_field_text'],
                        "#_COLORBG" => $paramMMode['color_field_background'],
                        "#_COLORBORDER" => $paramMMode['color_field_border'],
                        "#_COLORBUTTON" => $paramMMode['color_button'],
                        "#_COLORTEXTBUTTON" => $paramMMode['color_text_button'],
                        "#_COLOR_BTN_HOVER" => $paramMMode['color_button_hover'],
                        "#_COLOR_BTN_CLICK" => $paramMMode['color_button_onclick']
                    );
                    $wysijaStyle = str_replace(array_keys($wysijaRemplacements), array_values($wysijaRemplacements), wpm_wysija_style() );
                }
                $newLetter = '<div class="wpm_newletter">'.stripslashes($paramMMode['title_newletter']).''.do_shortcode(stripslashes($paramMMode['code_newletter'])).'</div>';
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
                if($paramMMode['active_cpt_s']==1) {
                    $Counter .= '<div id="wpm-cpt-seconds">%%S%%<br /><span id="wpm-cpt-seconds-span">'.__('Seconds', 'wp-maintenance').'</span></div>';
                }
                $Counter .= "';";
                $Counter .= '
                    FinishMessage = "'.trim( stripslashes($paramMMode['message_cpt_fin']) ).'";
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
    if( isset($paramMMode['newletter_font_text']) ) { $wpmStyle .= 'font-family: '.$paramMMode['newletter_font_text'].', serif;'; }
$wpmStyle .= '}';

$wpmStyle .= '
h3 {';
    if( isset($paramMMode['font_title']) ) { $wpmStyle .= 'font-family: '.$paramMMode['font_title'].', serif;'; }
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
    if( isset($paramMMode['font_text']) ) { $wpmStyle .= 'font-family: '.$paramMMode['font_text'].', serif;'; }
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
    if( isset($paramMMode['font_text_bottom']) ) { $wpmStyle .= 'font-family: '.$paramMMode['font_text_bottom'].', serif;'; }
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
if( isset($paramMMode['font_cpt']) ) { $wpmStyle .= 'font-family: '.$paramMMode['font_cpt'].', serif;'; }
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
                "%NEWSLETTER%" => $newLetter
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