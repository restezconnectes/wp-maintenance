<?php

/*
 * Plugin Name: WP Maintenance
 * Plugin URI: http://wordpress.org/extend/plugins/wp-maintenance/
 * Description: Le plugin WP Maintenance vous permet de mettre votre site en attente le temps pour vous de faire une maintenance ou du lancement de votre site. Personnalisez cette page de maintenance avec une image, un compte à rebours, etc... / The WP Maintenance plugin allows you to put your website on the waiting time for you to do maintenance or launch your website. Personalize this page with picture, countdown...
 * Author: Florent Maillefaud
 * Author URI: http://www.restezconnectes.fr/
 * Version: 2.3
 * Text Domain: wp-maintenance
 * Domain Path: /languages/
 */


/*
Change Log
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

// multilingue
add_action( 'init', 'wpm_make_multilang' );
function wpm_make_multilang() {
    load_plugin_textdomain('wp-maintenance', false, dirname( plugin_basename( __FILE__ ) ).'/languages');
}

/* Ajoute la version dans les options */
define('WPM_VERSION', '2.3');
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
function wpm_add_menu_admin_bar() {
    global $wp_admin_bar;
    
    $checkActive = get_option('wp_maintenance_active');
    if(isset($checkActive) && $checkActive==1) {
        $textAdmin = '<img src="'.WP_PLUGIN_URL.'/wp-maintenance/images/lock.png" style="padding: 6px 0;float:left;margin-right: 6px;">Mode maintenance activé';
    
        $wp_admin_bar->add_menu(array(
            'title' => $textAdmin, // Titre du menu
            'href' => WPM_SETTINGS, // Lien du menu
            'meta' => array(
                'class' => 'wpmbackground'
            )
            /*'parent' => "wp-logo", // Parent du menu*/
        ));
    } 
}
add_action('admin_bar_menu', 'wpm_add_menu_admin_bar', 999);

/* Liste les différents Rôles */
function wpm_get_roles() {

    $wp_roles = new WP_Roles();
    $roles = $wp_roles->get_names();
    $roles = array_map( 'translate_user_role', $roles );

    return $roles;
}

function wpm_add_admin() {
    $hook = add_options_page("Options pour l'affichage de la page maintenance", "WP Maintenance",  'manage_options', __FILE__, "wpm_admin_panel");
    
    $wp_maintenanceAdminOptions = array(
        'color_bg' => "#f1f1f1",
        'color_txt' => '#888888',
        'color_bg_bottom' => '#333333',
        'color_text_bottom' => '#FFFFFF',
        'text_maintenance' => __('This site is down for maintenance', 'wp-maintenance'),
        'userlimit' => 'administrator',
        'image' => WP_PLUGIN_URL.'/wp-maintenance/images/default.png',
        'font_title' => 'Acme',
        'font_title_size' => 40,
        'font_text' => 'Acme',
        'font_text_size' => 40,
        'font_text_bottom' => 'Acme',
        'font_bottom_size' => 12,
        'font_cpt' => 'Acme',
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
}

function wpm_admin_scripts() {
    wp_enqueue_script('media-upload');
    wp_enqueue_script('thickbox');
    wp_register_script('wpm-my-upload', WP_PLUGIN_URL.'/wp-maintenance/wpm-script.js', array('jquery','media-upload','thickbox'));
    wp_enqueue_script('wpm-my-upload');
    
    // If you're not including an image upload then you can leave this function call out
    wp_enqueue_media();
    
    // Now we can localize the script with our data.
    wp_localize_script( 'wpm-my-upload', 'Data', array(
      'textebutton'  =>  __( 'Choose This Image', 'wp-maintenance' ),
      'title'  => __( 'Choose Image', 'wp-maintenance' ),
    ) );
    
    wp_register_script('wpm-admin-settings', WP_PLUGIN_URL.'/wp-maintenance/wpm-admin-settings.js');
    wp_enqueue_script('wpm-admin-settings');
}

add_action( 'admin_enqueue_scripts', 'wpm_enqueue_color_picker' );
function wpm_enqueue_color_picker( $hook_suffix ) {
    // first check that $hook_suffix is appropriate for your admin page
    wp_enqueue_style( 'wp-color-picker' );
    wp_enqueue_script( 'my-script-handle', plugins_url('wpm-color-options.js', __FILE__ ), array( 'wp-color-picker' ), false, true );
}

function wpm_admin_styles() {
    wp_enqueue_style('thickbox');
}

if (isset($_GET['page']) && $_GET['page'] == 'wp-maintenance/wp-maintenance.php') {
    add_action('admin_print_scripts', 'wpm_admin_scripts');
    add_action('admin_print_styles', 'wpm_admin_styles');
    add_action('admin_print_scripts', 'wpm_admin_scripts');
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

    if($enable==1 && $code!='') {
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
        
	// Attributes
	extract( shortcode_atts(
		array(
			'size' => 48,
            'enable' => 0
		), $atts )
	);
    if($paramSocialOption['theme']!='') {
        $srcIcon = get_stylesheet_directory_uri().'/'.$paramSocialOption['theme'];
    } else {
        $srcIcon = WP_CONTENT_URL.'/plugins/wp-maintenance/socialicons/'.$paramSocialOption['size'].'x'.$paramSocialOption['size'].'/';
    }
    if($paramSocialOption['enable']==1 && $countSocial>=1) {
         $content .= '<div id="wpm-social-footer" class="wpm_social"><ul class="wpm_horizontal">';
            foreach($paramSocial as $socialName=>$socialUrl) {
                if($socialUrl!='') {
                    $content .= '<li><a href="'.$socialUrl.'" target="_blank"><img src="'.$srcIcon.$socialName.'.png" alt="'.$paramSocialOption['texte'].' '.ucfirst($socialName).'" title="'.$paramSocialOption['texte'].' '.ucfirst($socialName).'" /></a></li>';
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
    
    if(get_option('wp_maintenance_limit')) { extract(get_option('wp_maintenance_limit')); }
    $paramLimit = get_option('wp_maintenance_limit');
    $statusActive = get_option('wp_maintenance_active');

    get_currentuserinfo();

    if( !isset($paramMMode['active']) ) { $paramMMode['active'] = 0 ; }
    if( !isset($statusActive) ) { update_option('wp_maintenance_active', $paramMMode['active']); }

    $paramSocialOption = get_option('wp_maintenance_social_options');
    
    /* Désactive le mode maintenance pour les Roles définis */
    if($paramLimit) {
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

    /* Si on désactive le mode maintenance en fin de compte à rebours */
    if($paramMMode['disable']==1 && $statusActive == 1) {

        $dateNow = strtotime(date("d-m-Y H:i:s")) + 3600 * get_option('gmt_offset');
        $dateFinCpt = strtotime(date($paramMMode['date_cpt_jj'].'-'.$paramMMode['date_cpt_mm'].'-'.$paramMMode['date_cpt_aa'].' '.$paramMMode['date_cpt_hh'].':'.$paramMMode['date_cpt_mn'].':'.$paramMMode['date_cpt_ss']));
        
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
            if($paramMMode['color_bg']=="") { $paramMMode['color_bg'] = "#f1f1f1"; }
            if($paramMMode['color_txt']=="") { $paramMMode['color_txt'] = "#888888"; }

            /* Paramètres par défaut */
            //if($paramMMode['text_maintenance']=="") { $paramMMode['text_maintenance'] = 'Ce site est en maintenance'; }
            //if($paramMMode['image']=="") { $paramMMode['image'] = WP_PLUGIN_URL.'/wp-maintenance/images/default.png'; }

            /* On récupère les tailles de l'image */
            list($width, $height, $type, $attr) = getimagesize($paramMMode['image']);

            /* Date compte à rebours / Convertie en format US */
            $timestamp = strtotime($paramMMode['date_cpt_aa'].'/'.$paramMMode['date_cpt_mm'].'/'.$paramMMode['date_cpt_jj'].' '.$paramMMode['date_cpt_hh'].':'.$paramMMode['date_cpt_mn']);
            $dateCpt = date('m/d/Y h:i A', $timestamp);

            /* Traitement de la feuille de style */
            $styleRemplacements = array (
                "#_COLORTXT" => $paramMMode['color_txt'],
                "#_COLORBG" => $paramMMode['color_bg'],
                "#_COLORCPTBG" => $paramMMode['color_cpt_bg'],
                "#_DATESIZE" => $paramMMode['date_cpt_size'],
                "#_COLORCPT" => $paramMMode['color_cpt'],
                "#_COLOR_BG_BT" => $paramMMode['color_bg_bottom'],
                "#_COLOR_TXT_BT" => $paramMMode['color_text_bottom']
            );
            $wpmStyle = str_replace(array_keys($styleRemplacements), array_values($styleRemplacements), get_option('wp_maintenance_style'));
            if($paramMMode['message_cpt_fin']=='') { $paramMMode['message_cpt_fin'] = '&nbsp;'; }

            if($paramMMode['b_image'] && $paramMMode['b_enable_image']==1) {
                if($paramMMode['b_repeat_image']=='') { $paramMMode['b_repeat_image'] = 'repeat'; }
                $optionBackground = '';
                if(isset($paramMMode['b_fixed_image']) && $paramMMode['b_fixed_image']==1) { 
                    $optionBackground = 'background-attachment:fixed;';
                }
            $addBImage = '
div#wrapper {
    background:url('.$paramMMode['b_image'].') '.$paramMMode['b_repeat_image'].';'.$optionBackground.'padding:0;margin:0;
    background-size: cover;
}';
            }
            if($paramMMode['b_pattern']>0 && $paramMMode['b_enable_image']==1) {
            $addBImage = '
div#wrapper {
    background:url('.WP_PLUGIN_URL.'/wp-maintenance/images/pattern'.$paramMMode['b_pattern'].'.png) '.$paramMMode['b_repeat_image'].'  '.$paramMMode['color_bg'].';padding:0;margin:0;
}';
            }
            if($paramSocialOption['align']=='') { $paramSocialOption['align'] = 'center'; }
            if($paramMMode['font_title_size']=='') { $paramMMode['font_title_size'] = 40; }
            if($paramMMode['font_title_style']=='') { $paramMMode['font_title_style'] = 'normal'; }
            if($paramMMode['font_title_weigth']=='') { $paramMMode['font_title_weigth'] = 'normal'; }
            if($paramMMode['font_text_size']=='') { $paramMMode['font_text_size'] = 40; }
            if($paramMMode['font_text_style']=='') { $paramMMode['font_text_style'] = 'normal'; }
            if($paramMMode['font_text_weigth']=='') { $paramMMode['font_text_weigth'] = 'normal'; }
            if($paramMMode['font_text_bottom']=='') { $paramMMode['font_text_bottom'] = 'normal'; }
            if($paramMMode['font_bottom_size']=='') { $paramMMode['font_bottom_size'] = 12; }
            if($paramMMode['font_bottom_weigth']=='') { $paramMMode['font_bottom_weigth'] = 'normal'; }
            if($paramMMode['font_bottom_style']=='') { $paramMMode['font_bottom_style'] = 'normal'; }
            if($paramMMode['font_cpt']=='') { $paramMMode['font_cpt'] = 'Acme'; }
            if($paramMMode['date_cpt_size']=='') { $paramMMode['date_cpt_size'] = 72; }
            
            $addStylesheet = '';
            if($paramMMode['newletter']==1 && $paramMMode['code_newletter']!='') {
                $nameNl = strpos($paramMMode['code_newletter'], 'wysija_form');
                $addStylesheet = "<link rel='stylesheet' id='validate-engine-css-css'  href='".WP_PLUGIN_URL."/wysija-newsletters/css/validationEngine.jquery.css' type='text/css' media='all' />
<style type='text/css'>
.widget_wysija_cont .wysija-submit {
    margin-left: auto;
    margin-right: auto;
}
</style>";
            }    
                
            $content = '
<!DOCTYPE html>
<html lang="fr">
    <head>
        <title>'.$site_title." - ".$site_description.'</title>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
        <meta name="viewport" content="width=device-width, user-scalable=yes" />
        <meta name="description" content="'.__('This site is down for maintenance', 'wp-maintenance').'" />
        '.$addStylesheet.'
        <style type="text/css">
@import url(http://fonts.googleapis.com/css?family='.str_replace(' ', '+', $paramMMode['font_title']).'|'.str_replace(' ', '+',$paramMMode['font_text']).'|'.str_replace(' ', '+',$paramMMode['font_text_bottom']).'|'.str_replace(' ', '+',$paramMMode['font_cpt']).');
            '.$wpmStyle.'
            '.$addBImage.'
            '.$addCSSnl.'
body {
  -webkit-background-size: cover;
  -moz-background-size: cover;
  -o-background-size: cover;
  background-size: cover;
  top: 0;
}
.wpm_newletter {
    margin-left: auto;
    margin-right: auto;
    max-width:100%
}
.wpm_social_icon {
    float:left;
    width:'.$paramSocialOption['size'].'px;
    margin:0px 5px auto;
}
.wpm_social ul {
    /*float: left;*/
    margin: 10px 0;
    max-width: 100%;
    padding: 0;
    text-align: '.$paramSocialOption['align'].';
}
#cptR-day, #cptR-hours, #cptR-minutes, #cptR-seconds {
    width:'.($paramMMode['date_cpt_size']*1.4).'px;
}
.cptR-rec_countdown {
    font-size:'.$paramMMode['date_cpt_size'].'px;
    font-family: '.$paramMMode['font_cpt'].', serif;
}
#main #intro h3 {
    font-size: '.$paramMMode['font_title_size'].'px;
    font-style: '.$paramMMode['font_title_style'].';
    font-weight: '.$paramMMode['font_title_weigth'].';
    font-family: '.$paramMMode['font_title'].', serif;
}
#main #intro p {
    font-family: '.$paramMMode['font_text'].', serif;
    font-size: '.$paramMMode['font_text_size'].'px;
    font-style: '.$paramMMode['font_text_style'].';
    font-weight: '.$paramMMode['font_text_weigth'].';
    line-height: '.($paramMMode['font_text_size']*0.9).'px;
}
.wpm_copyright {
    font-family: '.$paramMMode['font_text_bottom'].', serif;
    font-size: '.$paramMMode['font_bottom_size'].'px;
    font-style: '.$paramMMode['font_bottom_style'].';
    font-weight: '.$paramMMode['font_bottom_weigth'].';
}

#logo img {
    max-width: 100%;
    height: auto;
}

@media screen and (min-width: 200px) and (max-width: 480px) {
    #cptR-day, #cptR-hours, #cptR-minutes, #cptR-seconds {
        width:'.($paramMMode['date_cpt_size']*0.9).'px;
    }
    .cptR-rec_countdown {
        font-size:'.($paramMMode['date_cpt_size']*0.6).'px;
    }
    #cptR-days-span, #cptR-hours-span, #cptR-minutes-span, #cptR-seconds-span {
        font-size: 8px;
    }
}
        </style>
        '.do_shortcode('[wpm_analytics enable="'.$paramMMode['analytics'].'"]').'
    </head>
    <body>';
        if($paramSocialOption['position']=='top') {
            $content .= do_shortcode('[wpm_social]');
        }
        $content .= '<div id="wrapper">';
         if($paramMMode['image']) {
            $content .= '
            <div id="header" class="full">
                <div id="logo"><img src="'.$paramMMode['image'].'" '.$attr.' /></div>
            </div>
            ';
         }
         $content .= '
             <div id="content" class="full">
                 <div id="main">';
                     $content .= '
                    <div id="intro" class="block"><h3>'.stripslashes($paramMMode['titre_maintenance']).'</h3><p>'.stripslashes($paramMMode['text_maintenance']).'</p></div>';
                     if( isset($paramMMode['message_cpt_fin']) && $paramMMode['message_cpt_fin']!='' && $paramMMode['date_cpt_aa']!='' && $paramMMode['active_cpt']==1) {
                     $content .='
                    <div style="margin-left:auto;margin-right:auto;text-align: center;margin-top:30px;">
                         <script language="JavaScript">
                            TargetDate = "'.$dateCpt.'";
                            BackColor = "'.$paramMMode['color_cpt_bg'].'";
                            FontSize = "'.$paramMMode['date_cpt_size'].'";
                            ForeColor = "'.$paramMMode['color_cpt'].'";
                            Disable = "'.$paramMMode['disable'].'";
                            UrlDisable = "'.get_option( 'siteurl').'";
                            CountActive = true;
                            CountStepper = -1;
                            LeadingZero = true;
                     ';
                     $content .= "   DisplayFormat = '<div id=\"cptR-day\">%%D%%<br /><span id=\"cptR-days-span\">".__('Days', 'wp-maintenance')."</span></div><div id=\"cptR-hours\">%%H%%<br /><span id=\"cptR-hours-span\">".__('Hours', 'wp-maintenance')."</span></div><div id=\"cptR-minutes\">%%M%%<br /><span id=\"cptR-minutes-span\">".__('Minutes', 'wp-maintenance')."</span></div>";
                     if($paramMMode['active_cpt_s']==1) {
                        $content .= '<div id="cptR-seconds">%%S%%<br /><span id="cptR-seconds-span">'.__('Seconds', 'wp-maintenance').'</span></div>';
                     }
                     $content .= "';
                            FinishMessage = '".stripslashes($paramMMode['message_cpt_fin'])."';
                        </script>";
                     $content .= '
                        <script language="JavaScript" src="'.WP_PLUGIN_URL.'/wp-maintenance/wpm-cpt-script.js"></script>
                    </div>';
                        }
                    if($paramMMode['newletter']==1 && $paramMMode['code_newletter']!='') {
                        $content .= '<div class="wpm_newletter">'.do_shortcode(stripslashes($paramMMode['code_newletter'])).'</div>';
                    }
        
                     
                     $content .= '
                     </div><!-- div main -->
            </div><!-- div content -->
        ';
                    if($paramSocialOption['position']=='bottom') {
                        $content .= do_shortcode('[wpm_social]');
                    }
                    if($paramMMode['text_bt_maintenance']!='') {
                        $content .= '<div id="wpm_footer"><div class="clear"><p class="wpm_copyright">'.stripslashes($paramMMode['text_bt_maintenance']).'</p></div></div>';
                    }
            $content .='</div><!-- div wrapper -->
    </body>
</html>';
        }
        die($content);
    }

}
add_action('get_header', 'wpm_maintenance_mode');

if(function_exists('register_deactivation_hook')) {
    register_deactivation_hook(__FILE__, 'wpm_uninstall');
}

//intègre le tout aux pages Admin de Wordpress
add_action("admin_menu", "wpm_add_admin");

?>
