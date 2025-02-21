<?php

class WP_maintenance {

    protected static $instance;

    public static function init() {
        is_null( self::$instance ) AND self::$instance = new self;
        return self::$instance;
    }

	public function __construct() {

        /* Récupère le status */
        $statusActive = $this->wpm_check_active();
        if ( isset($statusActive) && $statusActive == 1) {
            add_action( 'template_redirect', array( &$this, 'wpm_maintenance_mode'), 2 );
        }
        add_action( 'init', array( &$this, 'wpm_dashboard_install') );

        /* Version du plugin */
        $option['wp_maintenance_version'] = WPM_VERSION;
        if( !get_option('wp_maintenance_version') ) {
            add_option('wp_maintenance_version', $option);
        } else if ( get_option('wp_maintenance_version') != WPM_VERSION ) {
            update_option('wp_maintenance_version', WPM_VERSION);
        }

        if( is_admin() ) {
            add_action( 'admin_menu', array( &$this, 'wpm_add_admin') );
            add_filter( 'plugin_action_links_wp-maintenance/wp-maintenance.php', array( &$this, 'wpm_plugin_action_links'), 10, 3 );
            add_action( 'admin_head', array( &$this, 'wpm_admin_head') );
            add_action( 'admin_bar_menu', array( &$this, 'wpm_add_menu_admin_bar'), 999 );
            add_action( 'admin_footer', array( &$this, 'wpm_print_footer_scripts') );
            add_action( 'admin_init', array( &$this, 'wpm_process_settings_import') );
            add_action( 'admin_init', array( &$this, 'wpm_process_settings_export') );     
        }
        // disabled XMLRPC
        add_filter('xmlrpc_enabled', '__return_false');
        /** Disable REST API **/
        $checkActive = get_option('wp_maintenance_active');
        add_filter( 'rest_authentication_errors', function( $result ) {
            // If a previous authentication check was applied,
            // pass that result along without modification.
            if ( true === $result || is_wp_error( $result ) ) {
                return $result;
            }
        
            // No authentication has been performed yet.
            // Return an error if user is not logged in.
            if ( ! is_user_logged_in() && (isset($checkActive) && $checkActive == 1) ) {
                return new WP_Error(
                    'rest_not_logged_in',
                    __( 'You are not currently logged in.' ),
                    array( 'status' => 401 )
                );
            }
        
            // Our custom authentication check should have no effect
            // on logged-in requests
            return $result;
        });
    
    }
    
    /**
     * Display the default template
     */
    function wpm_get_default_template() {
        $filesystem  = wpm_get_filesystem();
        $file        = $filesystem->get_contents( WPM_DIR.'/themes/default/index2.php' );
        return $file;
    }

    public static function wpm_dashboard_install() {
        

        if ( get_option('wp_maintenance_active', false) == false or get_option('wp_maintenance_active')=='' ) {
            add_option('wp_maintenance_active', 0);
        }

        if ( get_option('wp_maintenance_settings_css', false) == false or get_option('wp_maintenance_settings_css')=='' ) {
            add_option('wp_maintenance_settings_css', wpm_print_style());
        }

        $wpmAdminOptions = array(            
            'titre_maintenance' => __('This site is down for maintenance', 'wp-maintenance'),
            'text_maintenance' => __('Come back quickly!', 'wp-maintenance'),
            'newletter' => 0,
            'title_newletter' => '',
            'code_newletter' => '',
            'type_newletter' => 'shortcode',
            'iframe_newletter' => ''
        );
        if ( get_option('wp_maintenance_settings', false) == false or get_option('wp_maintenance_settings')=='' ) {
            foreach ($wpmAdminOptions as $keyAdminOptions => $optionAdminOptions) {
                $wpmAdminOptions[$keyAdminOptions] = $optionAdminOptions;
            }
            add_option('wp_maintenance_settings', $wpmAdminOptions);
        }

        /* DEFINITION PARAMS COLORS */
        $wpmSetsColorsOptions = array(
            'color_bg' => "#f1f1f1",
            'color_bg_header' => '#ffffff',
            'color_title' => '#333333',
            'font_title' => 'Anton',
            'font_title_size' => '16',
            'font_title_weigth' => '',
            'font_title_style' => '',
            'color_txt' => '#333333',
            'font_text' => 'Anton',
            'font_text_size' => '16',
            'font_text_weigth' => '',
            'font_text_style' => '',
            'container_active' => 0,
            'container_colorcontainer_opacity' => '0.5',
            'container_width' => '80',
            'color_cpt' => '#333333',
            'color_cpt_bg' => '#ffffff',
            'font_cpt' => 'Pacifico',
            'date_cpt_size' => '6',
            'font_end_cpt' => 'Pacifico',
            'cpt_end_size' => '2',
            'color_text_bottom' => '#ffffff',
            'color_bg_bottom' => '#333333',
            'font_text_bottom' => 'Helvetica',
            'font_bottom_size' => '12',
            'font_bottom_weigth' => '',
            'font_bottom_style' => '',
            'newletter' => 0,
            'newletter_font_text' => 'Helvetica',
            'newletter_size' => '14',
            'newletter_font_weigth' => '',
            'newletter_font_style' => '',
            'color_field_text' => '#333333',
            'color_field_border' => '#333333',
            'color_field_background' => '#CCCCCC',
            'color_text_button' => '#ffffff',
            'color_button' => '#1e73be',
            'color_button_hover' => '#ffffff',
            'color_button_onclick' => '#ffffff',
            'remove_googlefonts' => 0,
        );

        if ( get_option('wp_maintenance_settings_colors', false) == false or get_option('wp_maintenance_settings_colors')=='' ) {
            foreach ($wpmSetsColorsOptions as $keyColorsOptions => $optionColorsOptions) {
                $wpmSetsColorsOptions[$keyColorsOptions] = $optionColorsOptions;
            }
            add_option('wp_maintenance_settings_colors', $wpmSetsColorsOptions);
        }

        /* DEFINITION PARAMS PICTURE */
        $wpmSetsPicturesOptions = array(

            'image' => plugin_dir_url( __DIR__ ).'images/default2.png',
            'image_width' => 450,
            'image_height' => 450,            
            'b_enable_image' => 0,
            'b_image' => '',
            'b_opacity_image' => '0.2',
            'b_repeat_image' => 'repeat',
            'b_fixed_image' => 1,
            'b_pattern' => 0
        );

        if ( get_option('wp_maintenance_settings_picture', false) == false or get_option('wp_maintenance_settings_picture')=='' ) {
            foreach ($wpmSetsPicturesOptions as $keyPicturesOptions => $optionPicturesOptions) {
                $wpmSetsPicturesOptions[$keyPicturesOptions] = $optionPicturesOptions;
            }
            add_option('wp_maintenance_settings_picture', $wpmSetsPicturesOptions);
        }

        /* DEFINITION PARAMS COUNTDOWN */
        $wpmSetsCountdownOptions = array(
            'active_cpt' => 0,
            'date_cpt_hh' => '',
            'cptdate' => '',
            'cpttime' => '',
            'active_cpt_s' => '',
            'disable' => '',
            'message_cpt_fin' => ''
        );
        if ( get_option('wp_maintenance_settings_countdown', false) == false or get_option('wp_maintenance_settings_countdown')=='' ) {
            foreach ($wpmSetsCountdownOptions as $keyCountdownOptions => $optionCountdownOptions) {
                $wpmSetsCountdownOptions[$keyCountdownOptions] = $optionCountdownOptions;
            }
            add_option('wp_maintenance_settings_countdown', $wpmSetsCountdownOptions);
        }

        /* DEFINITION PARAMS CSS */
        if ( get_option('wp_maintenance_settings_css', false) == false or get_option('wp_maintenance_settings_css')=='' ) {
            add_option('wp_maintenance_settings_css', wpm_print_style() );
        }

        /* DEFINITION PARAMS SEO */
        $wpmSetsSeoOptions = array(
            'enable_seo' => 0,
            'seo_title' => '',
            'seo_description' => '',
            'favicon' => ''
        );
        if ( get_option('wp_maintenance_settings_seo', false) == false or get_option('wp_maintenance_settings_seo')=='' ) {
            foreach ($wpmSetsSeoOptions as $keySeoOptions => $optionSeoOptions) {
                $wpmSetsSeoOptions[$keySeoOptions] = $optionSeoOptions;
            }
            add_option('wp_maintenance_settings_seo', $wpmSetsSeoOptions);
        }

        /* DEFINITION PARAMS SOCIAL NETWORKS */
        $wpmSocialsNetworksOptions = array(
            'enable' => 0,
            'texte' => __('Follow me on', 'wp-maintenance'),
            'size' => 64,
            'style' => 'style1',
            'position' => 'bottom',
            'align' => 'center',
            'theme' => ''
        );
        if ( get_option('wp_maintenance_settings_socialnetworks', false) == false or get_option('wp_maintenance_settings_socialnetworks')=='' ) {
            foreach ($wpmSocialsNetworksOptions as $keyNetworksOptions => $optionNetworksOptions) {
                $wpmSocialsNetworksOptions[$keyNetworksOptions] = $optionNetworksOptions;
            }
            add_option('wp_maintenance_settings_socialnetworks', $wpmSocialsNetworksOptions);
        }

        /* DEFINITION PARAMS LIST SOCIAL NETWORKS */
        $wpmListSocialsNetworksOptions = array(
            'facebook' => '',
            'twitter' => '',
            'linkedin' => '', 
            'flickr' => '', 
            'youtube' => '', 
            'pinterest' => '', 
            'vimeo' => '', 
            'instagram' => '', 
            'about_me' => '', 
            'soundcloud' => '', 
            'skype' => '', 
            'tumblr' => '', 
            'blogger' => '', 
            'paypal' => '', 
            'email' => '',
            'tiktok' => '',
        );
        if ( get_option('wp_maintenance_list_socialnetworks', false) == false or get_option('wp_maintenance_list_socialnetworks')=='' ) {
            foreach ($wpmListSocialsNetworksOptions as $keyListNetworksOptions => $optionListNetworksOptions) {
                $wpmListSocialsNetworksOptions[$keyListNetworksOptions] = $optionListNetworksOptions;
            }
            add_option('wp_maintenance_list_socialnetworks', $wpmListSocialsNetworksOptions);
        }

        /* DEFINITION PARAMS FOOTER */
        $wpmSetsFootersOptions = array(
            'enable_footer' => 0,
            'text_bt_maintenance' => '',
            'add_wplogin' => 0,
            'add_wplogin_title' => ''
        );
        if ( get_option('wp_maintenance_settings_footer', false) == false or get_option('wp_maintenance_settings_footer')=='' ) {
            foreach ($wpmSetsFootersOptions as $keyFootersOptions => $optionFootersOptions) {
                $wpmSetsFootersOptions[$keyFootersOptions] = $optionFootersOptions;
            }
            add_option('wp_maintenance_settings_footer', $wpmSetsFootersOptions);
        }

        /* DEFINITION PARAMS SETTINGS */
        $wpmSetsOptions = array(
            'pageperso' => 0,
            'dashboard_delete_db' => 0,
            'error_503' => 1,
            'id_pages' => '',
            'headercode' => '',
            'headercodecss' => ''
        );
        if ( get_option('wp_maintenance_settings_options', false) == false or get_option('wp_maintenance_settings_options')=='' ) {
            foreach ($wpmSetsOptions as $keySetsOptions => $optionSetsOptions) {
                $wpmSetsOptions[$keySetsOptions] = $optionSetsOptions;
            }
            add_option('wp_maintenance_settings_options', $wpmSetsOptions);
        }       

    }

    public static function wpm_dashboard_remove() {

        if(get_option('wp_maintenance_settings')) { extract(get_option('wp_maintenance_settings')); }
        $paramMMode = get_option('wp_maintenance_settings');

        delete_option('wp_maintenance_active');

        if( isset($paramMMode['dashboard_delete_db']) && $paramMMode['dashboard_delete_db'] == 1 ) {

            delete_option('wp_maintenance_settings');
            delete_option('wp_maintenance_version');
            delete_option('wp_maintenance_settings_colors');
            delete_option('wp_maintenance_settings_countdown');
            delete_option('wp_maintenance_settings_seo');
            delete_option('wp_maintenance_settings_socialnetworks');
            delete_option('wp_maintenance_settings_footer');
            delete_option('wp_maintenance_settings_options');
            delete_option('wp_maintenance_limit'); 
            delete_option('wp_maintenance_social_options');
            delete_option('wp_maintenance_ipaddresses');
        }

    }


    function wpm_plugin_action_links($actions, $file, $plugin_data) {
        $new_actions = array();
        $new_actions[] = sprintf( '<a href="'.WPM_ADMIN_URL.'">%s</a>', __('Settings', 'wp-maintenance') );
        $new_actions = array_merge($new_actions, $actions);
        $uninstall_url = WPM_ADMIN_URL.'&amp;action=uninstall&amp;_wpnonce='.wp_create_nonce('wpm_uninstall_'.get_current_user_id().'_wpnonce');
        $new_actions[] = '<span class="delete"><a href="'.$uninstall_url.'" class="delete">'.__('Uninstall','=wp-maintenance').'</a></span>';
        return $new_actions;
    }

    // Add "Réglages" link on plugins page
    function wpm_plugin_actions( $links, $file ) {

        if ( $file != WPM_PLUGIN_BASENAME ) {
		  return $links;
        } else {
            $wpm_settings_link = '<a href="admin.php?page=wp-maintenance">'
                . esc_html( __( 'Settings', 'wp-maintenance' ) ) . '</a>';

            array_unshift( $links, $wpm_settings_link );

            return $links;
        }
    }

    /* Ajout feuille CSS pour l'admin barre */
    function wpm_admin_head() {

        // Add Style for all admin
        echo '
<style>#wpadminbar .wpmbackground-on > .ab-item{ color:#fff;background-color: #f44; }#wpadminbar .wpmbackground-on .ab-icon:before { content: "\f107";top: 2px;color:#fff !important; }#wpadminbar .wpmbackground-on:hover > .ab-item{ background-color: #a30 !important;color:#fff !important; }#wpadminbar .wpmbackground-off > .ab-item{ color:#fff; }#wpadminbar .wpmbackground-off .ab-icon:before { content: "\f107";top: 2px;color:#fff !important; }#maintenance-on{background:#0ed74c;border-radius:50%;width:14px;height:14px;float: left;margin-right: 5px;margin-top: 9px;}#maintenance-off{background:#d70e25;border-radius:50%;width:14px;height:14px;float: left;margin-right: 5px;margin-top: 9px;}</style>';

    }

    /* Ajout Notification admin barre */
    function wpm_add_menu_admin_bar( $wp_admin_bar ) {

        $checkActive = get_option('wp_maintenance_active');
        $textAdmin = '<span class="ab-icon"></span> '.__('WP Maintenance', 'wp-maintenance');
        $classAdminBar = 'off';
        
        // Récupère les paramètres sauvegardés
        if(get_option('wp_maintenance_settings_options')) { extract(get_option('wp_maintenance_settings_options')); }
        $wpoptions = get_option('wp_maintenance_settings_options');
        $remove_adminbar = 0;
        if( isset($wpoptions['remove_adminbar']) && $wpoptions['remove_adminbar']==1) {
            $remove_adminbar = 1; // If remove bar option is active
        }

        if( $remove_adminbar == 0 ) {

            if( isset($checkActive) && !is_network_admin() ) {
                
                if( $checkActive==1 ) {
                    $classAdminBar = 'on';
                }
                $args = array(
                    'id'     => 'wpm-info', // id of the existing child node (New > Post)
                    'title'  => $textAdmin, // alter the title of existing node
                    'href' => 'admin.php?page=wp-maintenance', // Lien du menu
                    'parent' => 'top-secondary', // set parent to false to make it a top level (parent) node
                    'meta' => array(
                        'class' => 'wpmbackground-'.$classAdminBar
                    )
                );
                $wp_admin_bar->add_node( $args );
                
                // add a child item to our parent item 
                $args = array(
                    'parent' => 'wpm-info',
                    'id'     => 'wp-maintenance-colors',
                    'title'  => __('Colors', 'wp-maintenance'),
                    'href'   => admin_url().'admin.php?page=wp-maintenance-colors',
                    'meta'   => false        
                );
                $wp_admin_bar->add_node( $args );
                
                // add a child item to our parent item 
                $args = array(
                    'parent' => 'wpm-info',
                    'id'     => 'wp-maintenance-picture',
                    'title'  => __('Pictures', 'wp-maintenance'),
                    'href'   =>  admin_url().'admin.php?page=wp-maintenance-picture',
                    'meta'   => false        
                );
                $wp_admin_bar->add_node( $args );
                
                // add a child item to our parent item 
                $args = array(
                    'parent' => 'wpm-info',
                    'id'     => 'wp-maintenance-countdown',
                    'title'  => __('Countdown', 'wp-maintenance'),
                    'href'   =>  admin_url().'admin.php?page=wp-maintenance-countdown',
                    'meta'   => false        
                );
                $wp_admin_bar->add_node( $args );  
                
                // add a child item to our parent item 
                $args = array(
                    'parent' => 'wpm-info',
                    'id'     => 'wp-maintenance-css',
                    'title'  => __('CSS', 'wp-maintenance'),
                    'href'   =>  admin_url().'admin.php?page=wp-maintenance-css',
                    'meta'   => false        
                );
                $wp_admin_bar->add_node( $args );
                
                // add a child item to our parent item 
                $args = array(
                    'parent' => 'wpm-info',
                    'id'     => 'wp-maintenance-footer',
                    'title'  => __('Footer', 'wp-maintenance'),
                    'href'   =>  admin_url().'admin.php?page=wp-maintenance-footer',
                    'meta'   => false        
                );
                $wp_admin_bar->add_node( $args );  

                // add a child item to our parent item 
                $args = array(
                    'parent' => 'wpm-info',
                    'id'     => 'wp-maintenance-seo',
                    'title'  => __('SEO', 'wp-maintenance'),
                    'href'   =>  admin_url().'admin.php?page=wp-maintenance-seo',
                    'meta'   => false        
                );
                $wp_admin_bar->add_node( $args );  

                // add a child item to our parent item 
                $args = array(
                    'parent' => 'wpm-info',
                    'id'     => 'wp-maintenance-socialnetworks',
                    'title'  => __('Social Networks', 'wp-maintenance'),
                    'href'   =>  admin_url().'admin.php?page=wp-maintenance-socialnetworks',
                    'meta'   => false        
                );
                $wp_admin_bar->add_node( $args );  

                // add a child item to our parent item 
                $args = array(
                    'parent' => 'wpm-info',
                    'id'     => 'wp-maintenance-settings',
                    'title'  => __('Settings', 'wp-maintenance'),
                    'href'   =>  admin_url().'admin.php?page=wp-maintenance-settings',
                    'meta'   => false        
                );
                $wp_admin_bar->add_node( $args );  
                
            }
        }
    }    

    function wpm_init_wysiwyg() {
        wp_enqueue_script('editor');
        add_thickbox();
        wp_enqueue_script('media-upload');
        add_action('admin_print_footer_scripts', 'wp_tiny_mce', 25);
        wp_enqueue_script('quicktags');
    }

    function wpm_add_admin() {

        add_menu_page( 'WP Maintenance Settings', 'WP Maintenance', 'manage_options', 'wp-maintenance', array( $this, 'wpm_dashboard_page'),  WPM_URL.'images/wpm-menu-icon.png' );
        add_submenu_page( 'wp-maintenance', 'WP Maintenance > '.__('General', 'wp-maintenance'), __('General', 'wp-maintenance'), 'manage_options', 'wp-maintenance', array( $this, 'wpm_dashboard_page') );
        add_submenu_page( 'wp-maintenance', 'WP Maintenance > '.__('Colors & Fonts', 'wp-maintenance'), __('Colors & Fonts', 'wp-maintenance'), 'manage_options', 'wp-maintenance-colors', array( $this, 'wpm_colors_page') );
        add_submenu_page( 'wp-maintenance', 'WP Maintenance > '.__('Pictures', 'wp-maintenance'), __('Pictures', 'wp-maintenance'), 'manage_options', 'wp-maintenance-picture', array( $this, 'wpm_picture_page') );
        add_submenu_page( 'wp-maintenance', 'WP Maintenance > '.__('CountDown', 'wp-maintenance'), __('CountDown', 'wp-maintenance'), 'manage_options', 'wp-maintenance-countdown', array( $this, 'wpm_countdown_page') );
        add_submenu_page( 'wp-maintenance', 'WP Maintenance > '.__('CSS Style', 'wp-maintenance'), __('CSS Style', 'wp-maintenance'), 'manage_options', 'wp-maintenance-css', array( $this, 'wpm_css_page') );
        add_submenu_page( 'wp-maintenance', 'WP Maintenance > '.__('SEO', 'wp-maintenance'), __('SEO', 'wp-maintenance'), 'manage_options', 'wp-maintenance-seo', array( $this, 'wpm_seo_page') );
        add_submenu_page( 'wp-maintenance', 'WP Maintenance > '.__('Social Networks', 'wp-maintenance'), __('Social Networks', 'wp-maintenance'), 'manage_options', 'wp-maintenance-socialnetworks', array( $this, 'wpm_socialnetworks_page') );
        add_submenu_page( 'wp-maintenance', 'WP Maintenance > '.__('Footer', 'wp-maintenance'), __('Footer', 'wp-maintenance'), 'manage_options', 'wp-maintenance-footer', array( $this, 'wpm_footer_page') );
        add_submenu_page( 'wp-maintenance', 'WP Maintenance > '.__('Settings', 'wp-maintenance'), __('Settings', 'wp-maintenance'), 'manage_options', 'wp-maintenance-settings', array( $this, 'wpm_settings_page') );

        // If you're not including an image upload then you can leave this function call out
        if (isset($_GET['page']) && strpos($_GET['page'], 'wp-maintenance') !==false ) { // phpcs:ignore

            wp_enqueue_script('media-upload');
            wp_enqueue_script('thickbox');

            wp_register_script('wpm-my-upload', WPM_URL.'js/wpm-script.js', array('jquery','media-upload','thickbox'), WPM_VERSION, true);
            wp_enqueue_script('wpm-my-upload');

            wp_enqueue_style('jquery-defaut-style', WPM_URL.'js/lib/themes/default.css', array(), WPM_VERSION);
            wp_enqueue_style('jquery-date-style', WPM_URL.'js/lib/themes/default.date.css', array(), WPM_VERSION);
            wp_enqueue_style('jquery-time-style', WPM_URL.'js/lib/themes/default.time.css', array(), WPM_VERSION);
            wp_enqueue_style('jquery-fontselect-style', WPM_URL.'js/fontselect/fontselect.css', array(), WPM_VERSION);

            wp_enqueue_style( 'wp-color-picker' );
            wp_enqueue_script( 'my-script-handle', WPM_URL.'js/wpm-color-options.js', array( 'wp-color-picker' ), WPM_VERSION, true );

            wp_enqueue_style('thickbox');
            
            /* Image picker */
            wp_enqueue_style('imagepicker');
            wp_enqueue_style('imagepicker', WPM_URL.'css/image-picker.css', array(), WPM_VERSION);
            
            wp_register_script('imagepickerjs', WPM_URL.'js/image-picker.min.js', 'jquery', WPM_VERSION, true);
            wp_enqueue_script('imagepickerjs');

            $wpm_settings['codeEditor'] = wp_enqueue_code_editor(array('type' => 'text/css'));
            wp_localize_script('jquery', 'cm_settings', $wpm_settings);
            
            wp_enqueue_script('wp-theme-plugin-editor');
            wp_enqueue_style('wp-codemirror');

            wp_register_script('wpm_sticky', WPM_URL.'js/jquery.sticky.js', 'jquery', WPM_VERSION, true);
            wp_enqueue_script('wpm_sticky');

            // If you're not including an image upload then you can leave this function call out
            wp_enqueue_media();

            // Now we can localize the script with our data.
            wp_localize_script( 'wpm-my-upload', 'Data', array(
              'textebutton'  =>  __( 'Choose This Image', 'wp-maintenance' ),
              'title'  => __( 'Choose Image', 'wp-maintenance' ),
            ) );

            wp_register_script('wpm-admin-fontselect', WPM_URL.'js/fontselect/jquery.fontselect.min.js', 'jquery', WPM_VERSION, true );
            wp_enqueue_script('wpm-admin-fontselect');

            wp_enqueue_style('admincss');
            wp_enqueue_style('admincss', WPM_URL.'css/wpm-admin.css', array(), WPM_VERSION);           

        }
        
    }

    function wpm_dashboard_page() {

        //must check that the user has the required capability
        if (!current_user_can('manage_options')) {
          wp_die( esc_html__("You do not have sufficient privileges to access this page.", 'sponsorpress') );
        }
        include(WPM_DIR."/views/wp-maintenance-dashboard.php");
    }
    function wpm_colors_page() {

        //must check that the user has the required capability
        if (!current_user_can('manage_options')) {
          wp_die( esc_html__("You do not have sufficient privileges to access this page.", 'sponsorpress') );
        }
        include(WPM_DIR."/views/wp-maintenance-colors.php");
    }
    function wpm_css_page() {

        //must check that the user has the required capability
        if (!current_user_can('manage_options')) {
          wp_die( esc_html__("You do not have sufficient privileges to access this page.", 'sponsorpress') );
        }
        include(WPM_DIR."/views/wp-maintenance-css.php");
    }

    function wpm_picture_page() {

        //must check that the user has the required capability
        if (!current_user_can('manage_options')) {
          wp_die( esc_html__("You do not have sufficient privileges to access this page.", 'sponsorpress') );
        }
        include(WPM_DIR."/views/wp-maintenance-picture.php");
    }

    function wpm_countdown_page() {

        //must check that the user has the required capability
        if (!current_user_can('manage_options')) {
          wp_die( esc_html__("You do not have sufficient privileges to access this page.", 'sponsorpress') );
        }
        include(WPM_DIR."/views/wp-maintenance-countdown.php");
    }

    function wpm_seo_page() {

        //must check that the user has the required capability
        if (!current_user_can('manage_options')) {
          wp_die( esc_html__("You do not have sufficient privileges to access this page.", 'sponsorpress') );
        }
        include(WPM_DIR."/views/wp-maintenance-seo.php");
    }

    function wpm_socialnetworks_page() {

        //must check that the user has the required capability
        if (!current_user_can('manage_options')) {
          wp_die( esc_html__("You do not have sufficient privileges to access this page.", 'sponsorpress') );
        }
        include(WPM_DIR."/views/wp-maintenance-socialnetworks.php");
    }

    function wpm_footer_page() {

        //must check that the user has the required capability
        if (!current_user_can('manage_options')) {
          wp_die( esc_html__("You do not have sufficient privileges to access this page.", 'sponsorpress') );
        }
        include(WPM_DIR."/views/wp-maintenance-footer.php");
    }

    function wpm_settings_page() {

        //must check that the user has the required capability
        if (!current_user_can('manage_options')) {
          wp_die( esc_html__("You do not have sufficient privileges to access this page.", 'sponsorpress') );
        }
        include(WPM_DIR."/views/wp-maintenance-settings.php");
    }

    function wpm_print_footer_scripts() {

       if (isset($_GET['page']) && strpos($_GET['page'], 'wp-maintenance') !==false ) { // phpcs:ignore

            wp_register_script('wpm-picker', WPM_URL.'js/lib/picker.js', 'jquery', WPM_VERSION, true );
            wp_enqueue_script('wpm-picker');
            wp_register_script('wpm-datepicker', WPM_URL.'js/lib/picker.date.js', 'jquery', WPM_VERSION, true );
            wp_enqueue_script('wpm-datepicker');
            wp_register_script('wpm-timepicker', WPM_URL.'js/lib/picker.time.js', 'jquery', WPM_VERSION, true );
            wp_enqueue_script('wpm-timepicker');
            wp_register_script('wpm-legacy', WPM_URL.'js/lib/legacy.js', 'jquery', WPM_VERSION, true );
            wp_enqueue_script('wpm-legacy');
        }
    }

    /**
     * Process a settings export that generates a .json file of the erident settings
     */
    function wpm_process_settings_export() {

        if(empty($_POST['wpm_action']) || 'export_settings'!=$_POST['wpm_action'])
            return;

        if(!wp_verify_nonce($_POST['wpm_export_nonce'], 'go_export_nonce'))
            return;

        if(!current_user_can('manage_options'))
            return;

        $settingsJson = array(           
            'active' => get_option('wp_maintenance_active'),
            'settings' => get_option('wp_maintenance_settings'),
            'settings_colors' => get_option('wp_maintenance_settings_colors'),
            'settings_countdown' => get_option('wp_maintenance_settings_countdown'),
            'settings_picture' => get_option('wp_maintenance_settings_picture'),
            'settings_seo' => get_option('wp_maintenance_settings_seo'),
            'settings_socialnetworks' => get_option('wp_maintenance_settings_socialnetworks'),
            'settings_footer' => get_option('wp_maintenance_settings_footer'),
            'settings_options' => get_option('wp_maintenance_settings_options'),
            'limit' => get_option('wp_maintenance_limit'),
            'social_options' => get_option('wp_maintenance_social_options'),
            'ipaddresses' => get_option('wp_maintenance_ipaddresses')
        );
        
        ignore_user_abort(true);

        nocache_headers();
        header('Content-Type: application/json; charset=utf-8');
        header('Content-Disposition: attachment; filename=wp-maintenance-'.parse_url(get_site_url(), PHP_URL_HOST).'-'.gmdate('m-d-Y').'.json');
        header("Expires: 0");

        echo json_encode($settingsJson);
        exit;
    }

    static function wpm_admin_url( $page, $module = '' ) {
    
        $module = $module ? '&module=' . $module : '';
        $page   = str_replace( '&', '_', $page );
        $url    = 'admin.php?page=wp-maintenance-' . $page . $module;
    
        return is_multisite() ? network_admin_url( $url ) : admin_url( $url );
    }

    /**
     * Process a settings import from a json file
     */
    function wpm_process_settings_import() {

        if(empty($_POST['wpm_action']) || 'import_settings'!=$_POST['wpm_action'])
            return;

        if(!wp_verify_nonce( $_POST['wpm_import_nonce'], 'go_import_nonce'))
            return;

        if(!current_user_can('manage_options'))
            return;

        $extension = strtolower(pathinfo($_FILES['wpm_import_file']['name'], PATHINFO_EXTENSION));
        if($extension != 'json') {
            wp_die( esc_html__( 'Please upload a valid .json file', 'send-pdf-for-contact-form-7' ) );
        }

        $import_file = $_FILES['wpm_import_file']['tmp_name'];
        if(empty($import_file)) {
            wp_die( esc_html__( 'Please upload a file to import', 'wp-maintenance' ) );
        }

        $import = ! empty( $_FILES['wpm_import_file'] ) && is_array( $_FILES['wpm_import_file'] ) && isset( $_FILES['wpm_import_file']['type'], $_FILES['wpm_import_file']['name'] ) ? $_FILES['wpm_import_file'] : array();

        $_post_action    = $_POST['action'];
        $_POST['action'] = 'wp_handle_sideload';
        $file            = wp_handle_sideload( $import, array( 'mimes' => array( 'json' => 'application/json' ) ) );
        $_POST['action'] = $_post_action;
        if ( ! isset( $file['file'] ) ) {
            return;
        }
        $filesystem      = wpm_get_filesystem();
        $settings        = $filesystem->get_contents( $file['file'] );
	    $settings        = maybe_unserialize( $settings );

        // Retrieve the settings from the file and convert the json object to an array.
        $importTabSettings = (array) json_decode($settings, true);
        if( isset($importTabSettings) ) {

            foreach($importTabSettings as $tabName=>$tabValue) {

                if($tabName=='active') {
                    update_option('wp_maintenance_active', sanitize_text_field($tabValue));
                } else {
                    $updateSetting = wpm_update_settings($tabValue, 'wp_maintenance_'.$tabName);
                    echo '<div id="message" class="updated fade"><p><strong>'.esc_html__('New settings imported successfully!', 'wp-maintenance').' - '.esc_html($tabName).'</strong></p></div>';
                }
            }

            //echo '<div id="message" class="updated fade"><p><strong>'.__('New settings imported successfully!', 'wp-maintenance').'</strong></p></div>';
        }

    }

    /* Check le Mode Maintenance si on doit l'activer ou non */
    function wpm_check_active() {

        if(get_option('wp_maintenance_settings')) { extract(get_option('wp_maintenance_settings')); }

        /* Récupère le status */
        $statusActive = get_option('wp_maintenance_active');

        // Récupère les ip autorisee
        $paramIpAddress = get_option('wp_maintenance_ipaddresses');

        /* Désactive le mode maintenance pour les IP définies */
        if(isset($paramIpAddress) && $paramIpAddress!='' && is_array($paramIpAddress)) {

            foreach($paramIpAddress as $ipAutorized) {
                if($ipAutorized!='' && wpm_get_ip() == $ipAutorized) {    
                    $statusActive = 0;
                }
            }
            
        }

        /* Désactive le mode maintenance pour les Roles définis */
        if(get_option('wp_maintenance_limit')) { extract(get_option('wp_maintenance_limit')); }
        $paramLimit = get_option('wp_maintenance_limit');

        if(isset($paramLimit) && !empty($paramLimit) && is_array($paramLimit)) {
            foreach($paramLimit as $limitrole) {

                if( is_user_logged_in() ) {
                    $user_id = get_current_user_id(); 
                    $user_info = get_userdata($user_id);
                    if( in_array($limitrole, $user_info->roles)) {
                        $statusActive = 0;
                    }
                }
            }
        }
  
        /* On désactive le mode maintenance pour les admins */
        if( current_user_can('administrator') == true ) {
            $statusActive = 0;
        }
        /* Mode Preview */
        if( isset($_GET['wpmpreview']) && $_GET['wpmpreview']=='true' ) {// phpcs:ignore
            $statusActive = 1;
        }

        return $statusActive;
    }

    /* Mode Maintenance */
    function wpm_maintenance_mode() {

        global $post;

        if(get_option('wp_maintenance_settings')) { extract(get_option('wp_maintenance_settings')); }
        $paramMMode = get_option('wp_maintenance_settings');

        $paramSocialOption = get_option('wp_maintenance_social_options');

        // Récupère les paramètres sauvegardés Options
        if(get_option('wp_maintenance_settings_options')) { extract(get_option('wp_maintenance_settings_options')); }
        $paramsOptions = get_option('wp_maintenance_settings_options');

        // Récupère les paramètres sauvegardés compte à rebours
        if(get_option('wp_maintenance_settings_countdown')) { extract(get_option('wp_maintenance_settings_countdown')); }
        $paramsCountdown = get_option('wp_maintenance_settings_countdown');

        /* on doit retourner 12/31/2020 5:00 AM */
        $dateNow = strtotime(gmdate("Y-m-d H:i:s")) + 3600 * get_option('gmt_offset');
        if( isset($paramsCountdown['cptdate']) && !empty($paramsCountdown['cptdate']) ) {
            $dateFinCpt = strtotime( gmdate( str_replace('/', '-', $paramsCountdown['cptdate']).' '.$paramsCountdown['cpttime'].':00') );
            $dateCpt = gmdate( 'm/d/Y h:i A', strtotime( $paramsCountdown['cptdate'].' '.$paramsCountdown['cpttime'] ) );
        } else {
            $dateCpt = '';
        }

        /* Si on a un epage maintenance.php dans le theme */
        if ( file_exists( get_stylesheet_directory() ) && file_exists( get_stylesheet_directory() . '/maintenance.php')) {
            $urlTpl = get_stylesheet_directory() . '/maintenance.php';
        } 
        /* Si on a une page maintenance.php dans le theme parent */
        elseif( file_exists( get_template_directory() ) && file_exists( get_template_directory() . '/maintenance.php') ) {
            $urlTpl = get_template_directory() . '/maintenance.php';
        } else {
            $urlTpl = '';
        }

        if( isset($paramsOptions['pageperso']) && $paramsOptions['pageperso']==1 && $urlTpl !== '' ) {
            include_once( $urlTpl );
            die();
        }  
        
        /* Si on désactive le mode maintenance en fin de compte à rebours */
        if( ( isset($paramsCountdown['disable']) && $paramsCountdown['disable']==1 ) && $this->wpm_check_active() == 1 && $paramsCountdown['active_cpt']==1 ) {

            if( $dateNow > $dateFinCpt ) {
                $ChangeStatus = wpm_change_active();
                $disableCounter = wpm_update_settings( array('disable'=> 0) );
            }
        }

        $statusPageActive = 1;
        /*Désactive le mode maintenance pour les PAGE ID définies */
        if( isset($paramsOptions['id_pages']) && !empty($paramsOptions['id_pages']) ) {
            $listPageId = explode(',', $paramsOptions['id_pages']);
            foreach($listPageId as $keyPageId => $valPageId) {
                if( isset($post->ID) && trim($valPageId) == $post->ID ) {
                    $statusPageActive = 0;
                }
            }
        }

        // Prevetn Plugins from caching
        // Disable caching plugins. This should take care of:
        //   - W3 Total Cache
        //   - WP Super Cache
        //   - ZenCache (Previously QuickCache)
        if(!defined('DONOTCACHEPAGE')) {
            define('DONOTCACHEPAGE', true);
        }
        if(!defined('DONOTCDN')) {
            define('DONOTCDN', true);
        }
        if(!defined('DONOTCACHEDB')) {
            define('DONOTCACHEDB', true);
        }
        if(!defined('DONOTMINIFY')) {
            define('DONOTMINIFY', true);
        }
        if(!defined('DONOTCACHEOBJECT')) {
            define('DONOTCACHEOBJECT', true);
        }
        nocache_headers();
        if ($statusPageActive == 1) {

            if( isset($paramsOptions['error_503']) && $paramsOptions['error_503']== 1 ) {
                header('HTTP/1.1 503 Service Temporarily Unavailable');
                header('Status: 503 Service Temporarily Unavailable');
                header('Retry-After: 86400'); // retry in a day
            }
            
            $template = $this->wpm_get_default_template();
            require_once( WPM_DIR.'/themes/default/functions.php' );

            $template_tags = array (
                "{TitleSEO}" => sanitize_text_field(wpm_title_seo()),
                "{MetaDescription}" => sanitize_text_field(wpm_metadescription()),
                "{HeaderCode}" => wpm_headercode(),
                "{Head}" => wpm_head(),
                "{Logo}" => wpm_logo(),
                "{Version}" => WPM_VERSION,
                "{Title}" => sanitize_text_field(wpm_title()),
                "{Text}" => wpm_text(),
                "{Favicon}" => wpm_favicon(), 
                "{CustomCSS}" => wpm_customcss(),
                "{TopSocialIcon}" => wpm_social_position("top"),
                "{BottomSocialIcon}" => wpm_social_position("bottom"),
                "{FooterText}" => wpm_footer_text(),
                "{Newsletter}" => wpm_newsletter(),
                "{Counter}" => WPM_Countdown::display($dateCpt),
                "{Url}" => WPM_PLUGIN_URL
            );
            
            echo strtr($template, $template_tags); /* phpcs:ignore */
            exit();
        } 
    }

}


?>