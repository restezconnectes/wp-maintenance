<?php

class WP_maintenance {

    protected static $instance;

	public function __construct() {

        /* Récupère le status */
        $statusActive = $this->wpm_check_active();
        //exit('Status'.$statusActive);
        if ( isset($statusActive) && $statusActive == 1) {
            add_action( 'template_redirect', array( &$this, 'wpm_maintenance_mode'), 2 );
        }
        
        /* Version du plugin */
        $option['wp_maintenance_version'] = WPM_VERSION;
        if( !get_option('wp_maintenance_version') ) {
            add_option('wp_maintenance_version', $option);
        } else if ( get_option('wp_maintenance_version') != WPM_VERSION ) {
            update_option('wp_maintenance_version', WPM_VERSION);
        }
        add_action( 'init', array( &$this, 'wpm_dashboard_install') );
        
         if( is_admin() ) {
            add_action( 'admin_menu', array( &$this, 'wpm_add_admin') );
            add_filter( 'plugin_action_links', array( &$this, 'wpm_plugin_actions'), 10, 2 );
            add_action( 'admin_head', array( &$this, 'wpm_admin_head') );
            add_action( 'init', array( &$this, 'wpm_date_picker') );
            add_action( 'admin_bar_menu', array( &$this, 'wpm_add_menu_admin_bar'), 999 );
            add_action( 'admin_footer', array( &$this, 'wpm_print_footer_scripts') );        
            add_action( 'admin_init', array( &$this, 'wpm_process_settings_import') );
            add_action( 'admin_init', array( &$this, 'wpm_process_settings_export') );
            add_action( 'after_setup_theme', array( &$this, 'wpm_theme_add_editor_styles') );
        }
    }

    function wpm_theme_add_editor_styles() {
        add_editor_style( plugins_url('../css/custom-editor-style.css', __FILE__ ) );
    }
    public static function wpm_dashboard_install() {

        $nameServer = '';
        if( isset($_SERVER['SERVER_NAME']) ) {
            $nameServer = $_SERVER['SERVER_NAME'];
        }
        
        $wpMaintenanceAdminOptions = array(
            'color_bg' => "#f1f1f1",
            'color_txt' => '#888888',
            'color_bg_bottom' => '#333333',
            'color_text_bottom' => '#FFFFFF',
            'titre_maintenance' => __('This site is down for maintenance', 'wp-maintenance'),
            'text_maintenance' => __('Come back quickly!', 'wp-maintenance'),
            'userlimit' => 'administrator',
            'image' => WP_PLUGIN_URL.'/wp-maintenance/images/default.png',
            'font_title' => 'PT Sans',
            'font_title_size' => 40,
            'font_title_weigth' => 'normal',
            'font_title_style' => '',
            'font_text_style' => '',
            'font_text' => 'Metrophobic',
            'font_text_size' => 18,
            'font_text_bottom' => 'PT Sans',
            'font_text_weigth' => 'normal',
            'font_bottom_size' => 12,
            'font_bottom_weigth' => 'normal',
            'font_bottom_style' => '',
            'font_cpt' => 'PT Sans',
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
            'newletter_font_text' => 'PT Sans',
            'newletter_size' => 18,
            'newletter_font_style' => '',
            'newletter_font_weigth' => 'normal',
            'type_newletter' => 'shortcode',
            'title_newletter' => '',
            'code_newletter' => '',
            'code_analytics' => '',
            'domain_analytics' => $nameServer,
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
            'container_width' => 80,
            'dashboard_delete_db' => 'Yes',
            'error_503' => 'Yes'

        );
        $getMaintenanceSettings = get_option('wp_maintenance_settings');
        if ( empty($getMaintenanceSettings) ) {
            foreach ($wpMaintenanceAdminOptions as $key => $option) {
                $wpMaintenanceAdminOptions[$key] = $option;
            }
            add_option('wp_maintenance_settings', $wpMaintenanceAdminOptions);
        }

        if(!get_option('wp_maintenance_active')) { add_option('wp_maintenance_active', 0); }

        if(!get_option('wp_maintenance_style') or get_option('wp_maintenance_style')=='') {
            add_option('wp_maintenance_style', wpm_print_style());
        }

    }

    public static function wpm_dashboard_remove() {

        if(get_option('wp_maintenance_settings')) { extract(get_option('wp_maintenance_settings')); }
        $paramMMode = get_option('wp_maintenance_settings');

        delete_option('wp_maintenance_active');

        if( isset($paramMMode['dashboard_delete_db']) && $paramMMode['dashboard_delete_db'] == 'Yes' ) {

            delete_option('wp_maintenance_settings');
            delete_option('wp_maintenance_version');
            delete_option('wp_maintenance_style');
            delete_option('wp_maintenance_limit');            
            delete_option('wp_maintenance_social');
            delete_option('wp_maintenance_social_options');
            delete_option('wp_maintenance_social');
            delete_option('wp_maintenance_slider_options');
            delete_option('wp_maintenance_slider');
            delete_option('wp_maintenance_ipaddresses');
            
        }

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

        global $current_user;
        global $_wp_admin_css_colors;

        if (isset($_GET['page']) && strpos($_GET['page'], 'wp-maintenance') !==false) {
            echo '<link rel="stylesheet" type="text/css" media="all" href="' .WPM_PLUGIN_URL.'css/wpm-admin.css">';

            $admin_color = get_user_option( 'admin_color', get_current_user_id() );
            $colors      = $_wp_admin_css_colors[$admin_color]->colors;

            echo '<style>
a.wpmadashicons:link { text-decoration:none;color: '.$colors[0].'!important; }
a.wpmadashicons:hover { text-decoration:none;color: '.$colors[2].'!important; }
.wpmadashicons { color: '.$colors[0].'!important; }
.wpmadashicons:hover { color: '.$colors[2].'!important; }
.switch-field input:checked + label { background-color: '.$colors[2].'; }
.wpm-form-field {
    border: 1px solid '.$colors[2].'!important;
    background: #fff;
    -webkit-border-radius: 4px;
    -moz-border-radius: 4px;
    border-radius: 4px;
    color: '.$colors[2].'!important;
    -webkit-box-shadow: rgba(255,255,255,0.4) 0 1px 0, inset rgba(000,000,000,0.7) 0 0px 0px;
    -moz-box-shadow: rgba(255,255,255,0.4) 0 1px 0, inset rgba(000,000,000,0.7) 0 0px 0px;
    box-shadow: rgba(255,255,255,0.4) 0 1px 0, inset rgba(000,000,000,0.7) 0 0px 0px;
    padding:8px;
    /*margin-bottom:20px;*/
}
.wpm-form-field:focus {
    background: #fff!important;
    color: '.$colors[0].'!important;
}
.switch-field input:checked + label:last-of-type {
    background-color: '.$colors[0].'!important;
    color:#e4e4e4!important;
}
.switch-field-mini input:checked + label { background-color: '.$colors[2].'; }
.switch-field-mini input:checked + label:last-of-type {
    background-color: '.$colors[0].'!important;
    color:#e4e4e4!important;
}
</style>';
        } else {
            echo '<style>
        #maintenance-on{background:#0ed74c;border-radius:50%;width:14px;height:14px;float: left;margin-right: 5px;margin-top: 9px;}#maintenance-off{background:#d70e25;border-radius:50%;width:14px;height:14px;float: left;margin-right: 5px;margin-top: 9px;}
        </style>';
        }
    }
    /* Ajout Notification admin barre */
    function wpm_add_menu_admin_bar( $wp_admin_bar ) {

        $checkActive = get_option('wp_maintenance_active');
        $textAdmin = '<div id="maintenance-off"></div>'.__('WP Maintenance', 'wp-maintenance');
        
        if( isset($checkActive) && !is_network_admin() ) {
            
            if( $checkActive==1 ) {
                $textAdmin = '<div id="maintenance-on"></div>'.__('WP Maintenance', 'wp-maintenance');
            }
            $args = array(
                'id'     => 'wpm-info',     // id of the existing child node (New > Post)
                'title'  => $textAdmin, // alter the title of existing node
                'href' => 'admin.php?page=wp-maintenance', // Lien du menu
                'parent' => false,          // set parent to false to make it a top level (parent) node
                'meta' => array(
                    'class' => 'wpmbackground'
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
                'id'     => 'wp-maintenance-settings',
                'title'  => __('Settings', 'wp-maintenance'),
                'href'   =>  admin_url().'admin.php?page=wp-maintenance-settings',
                'meta'   => false        
            );
            $wp_admin_bar->add_node( $args );  
            
        }
    }

    /* DATEPICKER */
    function wpm_date_picker() {

        if (isset($_GET['page']) && strpos($_GET['page'], 'wp-maintenance') !==false) {
            wp_enqueue_script( 'jquery' );
            wp_enqueue_script('jquery-ui-datepicker');
            wp_enqueue_style('jquery-style', 'https://ajax.googleapis.com/ajax/libs/jqueryui/1.8.2/themes/smoothness/jquery-ui.css');
        }

    }

    function wpm_init_wysiwyg() {
        wp_enqueue_script('editor');
        add_thickbox();
        wp_enqueue_script('media-upload');
        add_action('admin_print_footer_scripts', 'wp_tiny_mce', 25);
        wp_enqueue_script('quicktags');
    }

    public static function wpm_get_options() {

        // Récupère les paramètres sauvegardés
        if(get_option('wp_maintenance_settings')) { extract(get_option('wp_maintenance_settings')); }
        $paramMMode = get_option('wp_maintenance_settings');

        return $paramMMode;
    }

    function wpm_add_admin() {

        add_menu_page( 'WP Maintenance Settings', 'WP Maintenance', 'manage_options', 'wp-maintenance', array( $this, 'wpm_dashboard_page'), plugins_url( '/wp-maintenance/images/wpm-menu-icon.png' ) );

        add_submenu_page( 'wp-maintenance', 'WP Maintenance > '.__('General', 'wp-maintenance'), __('General', 'wp-maintenance'), 'manage_options', 'wp-maintenance', array( $this, 'wpm_dashboard_page') );

        add_submenu_page( 'wp-maintenance', 'WP Maintenance > '.__('Colors & Fonts', 'wp-maintenance'), __('Colors & Fonts', 'wp-maintenance'), 'manage_options', 'wp-maintenance-colors', array( $this, 'wpm_colors_page') );

        add_submenu_page( 'wp-maintenance', 'WP Maintenance > '.__('Pictures', 'wp-maintenance'), __('Pictures', 'wp-maintenance'), 'manage_options', 'wp-maintenance-picture', array( $this, 'wpm_picture_page') );

        add_submenu_page( 'wp-maintenance', 'WP Maintenance > '.__('CountDown', 'wp-maintenance'), __('CountDown', 'wp-maintenance'), 'manage_options', 'wp-maintenance-countdown', array( $this, 'wpm_countdown_page') );

        add_submenu_page( 'wp-maintenance', 'WP Maintenance > '.__('CSS Style', 'wp-maintenance'), __('CSS Style', 'wp-maintenance'), 'manage_options', 'wp-maintenance-css', array( $this, 'wpm_css_page') );

        add_submenu_page( 'wp-maintenance', 'WP Maintenance > '.__('Settings', 'wp-maintenance'), __('Settings', 'wp-maintenance'), 'manage_options', 'wp-maintenance-settings', array( $this, 'wpm_settings_page') );

        /*add_submenu_page( 'wp-maintenance', 'WP Maintenance > '.__('About', 'wp-maintenance'), __('About', 'wp-maintenance'), 'manage_options', 'wp-maintenance-about', array( $this, 'wpm_about_page') );*/

        // If you're not including an image upload then you can leave this function call out
        if (isset($_GET['page']) && strpos($_GET['page'], 'wp-maintenance') !==false) {

            wp_enqueue_script('media-upload');
            wp_enqueue_script('thickbox');

            wp_register_script('wpm-my-upload', WPM_PLUGIN_URL.'js/wpm-script.js', array('jquery','media-upload','thickbox'));
            wp_enqueue_script('wpm-my-upload');

            wp_enqueue_style('jquery-defaut-style', WPM_PLUGIN_URL.'js/lib/themes/default.css');
            wp_enqueue_style('jquery-date-style', WPM_PLUGIN_URL.'js/lib/themes/default.date.css' );
            wp_enqueue_style('jquery-time-style', WPM_PLUGIN_URL.'js/lib/themes/default.time.css');
            wp_enqueue_style('jquery-fontselect-style', WPM_PLUGIN_URL.'js/fontselect/fontselect.css' );

            wp_enqueue_style( 'wp-color-picker' );
            wp_enqueue_script( 'my-script-handle', WPM_PLUGIN_URL.'js/wpm-color-options.js', array( 'wp-color-picker' ), false, true );

            wp_enqueue_style('thickbox');
            wp_enqueue_style('jquery-style', 'https://ajax.googleapis.com/ajax/libs/jqueryui/1.8.2/themes/smoothness/jquery-ui.css');
            
            /* Image picker */
            wp_enqueue_style('imagepicker');
            wp_enqueue_style('imagepicker', WPM_PLUGIN_URL.'css/image-picker.css');
            
            wp_register_script('imagepickerjs', WPM_PLUGIN_URL.'js/image-picker.min.js', 'jquery', '1.0');
            wp_enqueue_script('imagepickerjs');

            //wp_enqueue_script('nomikos_my_plugin_js_comment', site_url('wp-admin/js/comment.js'));

            /* ****************************************
             * Create a simple CodeMirror instance
             * ****************************************
             */
            // Mode http://codemirror.net/mode/php/index.html
            wp_register_style( 'wpm_codemirror_css', WPM_PLUGIN_URL.'js/codemirror/codemirror.css', false, '1.0.0' );
            wp_enqueue_style( 'wpm_codemirror_css' );

            wp_register_style( 'wpm_codemirror_theme_css', WPM_PLUGIN_URL.'js/codemirror/theme/material.css', false, '1.0.0' );
            wp_enqueue_style( 'wpm_codemirror_theme_css' );

            wp_register_script('wpm_codemirror', WPM_PLUGIN_URL.'js/codemirror/codemirror.js', 'jquery', '1.0');
            wp_enqueue_script('wpm_codemirror');
            wp_register_script('wpm_codemirror_css', WPM_PLUGIN_URL.'js/codemirror/css.js', 'jquery', '1.0');
            wp_enqueue_script('wpm_codemirror_css');

            /* END CODE MIRROR */

            wp_register_script('wpm_sticky', WPM_PLUGIN_URL.'js/jquery.sticky.js', 'jquery', '1.0');
            wp_enqueue_script('wpm_sticky');

            // If you're not including an image upload then you can leave this function call out
            wp_enqueue_media();

            // Now we can localize the script with our data.
            wp_localize_script( 'wpm-my-upload', 'Data', array(
              'textebutton'  =>  __( 'Choose This Image', 'wp-maintenance' ),
              'title'  => __( 'Choose Image', 'wp-maintenance' ),
            ) );

            wp_register_script('wpm-admin-fontselect', WPM_PLUGIN_URL.'js/fontselect/jquery.fontselect.min.js' );
            wp_enqueue_script('wpm-admin-fontselect');
            
            

        }
        
    }

    function wpm_dashboard_page() {

        //must check that the user has the required capability
        if (!current_user_can('manage_options')) {
          wp_die( __("You do not have sufficient privileges to access this page.", 'sponsorpress') );
        }
        include(WPM_DIR."/views/wp-maintenance-dashboard.php");
    }
    function wpm_colors_page() {

        //must check that the user has the required capability
        if (!current_user_can('manage_options')) {
          wp_die( __("You do not have sufficient privileges to access this page.", 'sponsorpress') );
        }
        include(WPM_DIR."/views/wp-maintenance-colors.php");
    }
    function wpm_css_page() {

        //must check that the user has the required capability
        if (!current_user_can('manage_options')) {
          wp_die( __("You do not have sufficient privileges to access this page.", 'sponsorpress') );
        }
        include(WPM_DIR."/views/wp-maintenance-css.php");
    }

    function wpm_picture_page() {

        //must check that the user has the required capability
        if (!current_user_can('manage_options')) {
          wp_die( __("You do not have sufficient privileges to access this page.", 'sponsorpress') );
        }
        include(WPM_DIR."/views/wp-maintenance-picture.php");
    }

    function wpm_countdown_page() {

        //must check that the user has the required capability
        if (!current_user_can('manage_options')) {
          wp_die( __("You do not have sufficient privileges to access this page.", 'sponsorpress') );
        }
        include(WPM_DIR."/views/wp-maintenance-countdown.php");
    }

    function wpm_settings_page() {

        //must check that the user has the required capability
        if (!current_user_can('manage_options')) {
          wp_die( __("You do not have sufficient privileges to access this page.", 'sponsorpress') );
        }
        include(WPM_DIR."/views/wp-maintenance-settings.php");
    }

    function wpm_print_footer_scripts() {

       if (isset($_GET['page']) && strpos($_GET['page'], 'wp-maintenance') !==false) {
            wp_register_script('wpm-picker', WPM_PLUGIN_URL.'js/lib/picker.js' );
            wp_enqueue_script('wpm-picker');
            wp_register_script('wpm-datepicker', WPM_PLUGIN_URL.'js/lib/picker.date.js' );
            wp_enqueue_script('wpm-datepicker');
            wp_register_script('wpm-timepicker', WPM_PLUGIN_URL.'js/lib/picker.time.js' );
            wp_enqueue_script('wpm-timepicker');
            wp_register_script('wpm-legacy', WPM_PLUGIN_URL.'js/lib/legacy.js' );
            wp_enqueue_script('wpm-legacy');
        }
    }

    /**
     * Process a settings export that generates a .json file of the erident settings
     */
    function wpm_process_settings_export() {

        if( empty( $_POST['wpm_action'] ) || 'export_settings' != $_POST['wpm_action'] )
            return;

        if( ! wp_verify_nonce( $_POST['wpm_export_nonce'], 'wpm_export_nonce' ) )
            return;

        if( ! current_user_can( 'manage_options' ) )
            return;

        $settingsJson = array(
            'settings' => get_option('wp_maintenance_settings'),
            'social' => get_option('wp_maintenance_social'),
            'social_options' => get_option('wp_maintenance_social_options'),
            'slider' => get_option('wp_maintenance_slider'),            
            'slider_options' => get_option('wp_maintenance_slider_options'),
            'limit' => get_option('wp_maintenance_limit'),
            'ipaddresses' => get_option('wp_maintenance_ipaddresses'),
            'style' => get_option('wp_maintenance_style')
            );
        
        ignore_user_abort( true );

        nocache_headers();
        header( 'Content-Type: application/json; charset=utf-8' );
        header( 'Content-Disposition: attachment; filename=wp-maintenance-' . date( 'm-d-Y' ) . '.json' );
        header( "Expires: 0" );

        echo json_encode( $settingsJson );
        exit;
    }


    /**
     * Process a settings import from a json file
     */
    function wpm_process_settings_import() {

        if( empty( $_POST['wpm_action'] ) || 'import_settings' != $_POST['wpm_action'] )
            return;

        if( ! wp_verify_nonce( $_POST['wpm_import_nonce'], 'wpm_import_nonce' ) )
            return;

        if( ! current_user_can( 'manage_options' ) )
            return;

        $extensionExploded = explode('.', $_FILES['wpm_import_file']['name']);
        $extension = strtolower(end($extensionExploded));

        if( $extension != 'json' ) {
            wp_die( __( 'Please upload a valid .json file' ) );
        }

        $import_file = $_FILES['wpm_import_file']['tmp_name'];

        if( empty( $import_file ) ) {
            wp_die( __( 'Please upload a file to import', 'wp-maintenance' ) );
        }

        // Retrieve the settings from the file and convert the json object to an array.
        $settings = (array) json_decode( file_get_contents( $import_file ), true);

        foreach($settings as $name=>$value) {
            update_option('wp_maintenance_'.$name, $value);
        }

      echo '<div id="message" class="updated fade"><p><strong>' . __('New settings imported successfully!', 'wp-maintenance') . '</strong></p></div>';

    }

    /* Check le Mode Maintenance si on doit l'activer ou non */
    function wpm_check_active() {

        if(get_option('wp_maintenance_settings')) { extract(get_option('wp_maintenance_settings')); }
        $paramMMode = get_option('wp_maintenance_settings');

        /* Récupère le status */
        $statusActive = get_option('wp_maintenance_active');

        // Récupère les ip autorisee
        $paramIpAddress = get_option('wp_maintenance_ipaddresses');
        /* Désactive le mode maintenance pour les IP définies */
        if( isset($paramIpAddress) ) {
            $lienIpAddress = explode("\n", $paramIpAddress);
            foreach($lienIpAddress as $ipAutorized) {
                if( strpos($ipAutorized, wpm_get_ip())!== false ) {
                    $statusActive = 0;
                }
            }
            
        }
        
        /* Désactive le mode maintenance pour les Roles définis */
        if(get_option('wp_maintenance_limit')) { extract(get_option('wp_maintenance_limit')); }
        $paramLimit = get_option('wp_maintenance_limit');

        if( isset($paramLimit) && count($paramLimit)>1 ) {
            foreach($paramLimit as $limitrole) {
                if( is_user_logged_in() ) {
                    $user_id = get_current_user_id(); 
                    $user_info = get_userdata($user_id);
                    $user_role = implode(', ', $user_info->roles);
                    if( $limitrole == $user_role ) {
                        $statusActive = 0;
                    }
                }
            }
        }
        
        /* Désactive le mode maintenance pour les PAGE ID définies */
        if( isset($paramMMode['id_pages']) && !empty($paramMMode['id_pages']) ) {
            $listPageId = explode(',', $paramMMode['id_pages']);
            foreach($listPageId as $keyPageId => $valPageId) {
                if( $valPageId == get_the_ID() ) {
                    $statusActive = 'page'.$valPageId;
                }
            }
        }

        /* On désactive le mode maintenance pour les admins */
        if( current_user_can('administrator') == true ) {
            $statusActive = 0;
        }
        
        return $statusActive;
    }

    /* Mode Maintenance */
    function wpm_maintenance_mode() {

        if(get_option('wp_maintenance_settings')) { extract(get_option('wp_maintenance_settings')); }
        $paramMMode = get_option('wp_maintenance_settings');

        if(get_option('wp_maintenance_slider')) { extract(get_option('wp_maintenance_slider')); }
        $paramSlider = get_option('wp_maintenance_slider');

        if(get_option('wp_maintenance_slider_options')) { extract(get_option('wp_maintenance_slider_options')); }
        $paramSliderOptions = get_option('wp_maintenance_slider_options');

        $paramSocialOption = get_option('wp_maintenance_social_options');

        /* on doit retourner 12/31/2020 5:00 AM */
        $dateNow = strtotime(date("Y-m-d H:i:s")) + 3600 * get_option('gmt_offset');
        if( get_option('wp_maintenance_version') <= '2.7.0') {
            $dateFinCpt = strtotime( date($paramMMode['date_cpt_jj'].'-'.$paramMMode['date_cpt_mm'].'-'.$paramMMode['date_cpt_aa'].' '.$paramMMode['date_cpt_hh'].':'.$paramMMode['date_cpt_mn'].':'.$paramMMode['date_cpt_ss']) );
        } else if( isset($paramMMode['cptdate']) && !empty($paramMMode['cptdate']) ) {
            $dateFinCpt = strtotime( date( str_replace('/', '-', $paramMMode['cptdate']).' '.$paramMMode['cpttime'].':00') );
            $dateCpt = date( 'm/d/Y h:i A', strtotime( $paramMMode['cptdate'].' '.$paramMMode['cpttime'] ) );
        } else {
            $dateCpt = '';
        }

        /* Si on désactive le mode maintenance en fin de compte à rebours */
        if( ( isset($paramMMode['disable']) && $paramMMode['disable']==1 ) && $statusActive == 1 ) {

            if( $dateNow > $dateFinCpt ) {
                $ChangeStatus = wpm_change_active();
            }
        }

     

            if ( file_exists( get_stylesheet_directory() ) ) {
                $urlTpl = get_stylesheet_directory();
            } else {
                $urlTpl = get_template_directory();
            }

            if( isset($paramMMode['pageperso']) && $paramMMode['pageperso']==1 && file_exists($urlTpl.'/maintenance.php') ) {

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
            if( isset($paramMMode['message_cpt_fin']) && $paramMMode['message_cpt_fin']=='') { $paramMMode['message_cpt_fin'] = '&nbsp;'; }


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
                if( empty($paramMMode['image_width']) ) { $paramMMode['image_width'] = 310; }
                if( empty($paramMMode['image_height']) ) { $paramMMode['image_height'] = 185; }
                $LogoImage = '<div id="logo"><img src="'.$paramMMode['image'].'" width="'.$paramMMode['image_width'].'" height="'.$paramMMode['image_height'].'" alt="'.get_bloginfo( 'name', 'display' ).' '.get_bloginfo( 'description', 'display' ).'" title="'.get_bloginfo( 'name', 'display' ).' '.get_bloginfo( 'description', 'display' ).'" /></div>';
                
            } else {
                $LogoImage = '';
            }

            if( isset($paramMMode['text_bt_maintenance']) && $paramMMode['text_bt_maintenance']!='' ) {
                $TextCopyright = stripslashes($paramMMode['text_bt_maintenance']);
            } else {
                $TextCopyright = '';
            }
            if( (isset($paramMMode['add_wplogin']) && $paramMMode['add_wplogin']==1) && (isset($paramMMode['add_wplogin_title']) && $paramMMode['add_wplogin_title']!='') ) {
                $TextCopyright .= '<br /><br />'.str_replace('%DASHBOARD%', '<a href="'.get_admin_url().'">'.__('Dashboard', 'wp-maintenance').'</a>', $paramMMode['add_wplogin_title']).'';

            }
            if( isset($paramMMode['titre_maintenance']) && $paramMMode['titre_maintenance']!='' ) {
                $Titre = stripslashes($paramMMode['titre_maintenance']);
            } else {
                $Titre = '';
            }
            if( isset($paramMMode['text_maintenance']) && $paramMMode['text_maintenance']!='' ) {
                $Texte = stripslashes(wpautop($paramMMode['text_maintenance']));
            } else {
                $Texte = '';
            }
            $wysijaStyle = '/* no NEWLETTER Style */';
            if( isset($paramMMode['newletter']) && $paramMMode['newletter']==1 ) {

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

                if( isset($paramMMode['code_newletter']) && $paramMMode['code_newletter']!='' && strpos($paramMMode['code_newletter'], 'wysija_form') == 1 ) {

                    $wysijaStyle = str_replace(array_keys($wysijaRemplacements), array_values($wysijaRemplacements), wpm_wysija_style() );

                } else if( strpos($paramMMode['code_newletter'], 'mc4wp_form') == 1 ) {

                    $wysijaStyle = str_replace(array_keys($wysijaRemplacements), array_values($wysijaRemplacements), wpm_mc4wp_style() );

                }
                $newLetter = '<div class="wpm_newletter">';
                if( isset($paramMMode['title_newletter']) && $paramMMode['title_newletter']!='') {
                    $newLetter .= '<div>'.stripslashes($paramMMode['title_newletter']).'</div>';
                }
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



            ';

            $addCssSlider = WPM_Slider::slider_css();
            $addScriptSlider = WPM_Slider::slider_scripts();
            $addScriptSlideshow = WPM_Slider::slider_functions();
            $Counter = WPM_Countdown::display($dateCpt);

            $wpmStyle = $wpmStyle.WPM_Countdown::css();

            $tplRemplacements = array (
                "%TITLE%" => get_bloginfo( 'name', 'display' ).' '.get_bloginfo( 'description', 'display' ),
                "%ANALYTICS%" => $CodeAnalytics,
                "%HEADERCODE%" => $HeaderCode,
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
                "%SLIDESHOWAL%" => WPM_Slider::slidershow('abovelogo'),
                "%SLIDESHOWBL%" => WPM_Slider::slidershow('belowlogo'),
                "%SLIDESHOWBT%" => WPM_Slider::slidershow('belowtext')
            );
            $template_page = str_replace(array_keys($tplRemplacements), array_values($tplRemplacements), $template_page );

            $content = $template_page;
        
        if( isset($paramMMode['error_503']) && $paramMMode['error_503']=='Yes' ) {
            header('HTTP/1.1 503 Service Temporarily Unavailable');
            header('Status: 503 Service Temporarily Unavailable');
            //header('Retry-After: 3600');//300 seconds*/
        }
        echo $content;
        die();
    }

    }

}


?>