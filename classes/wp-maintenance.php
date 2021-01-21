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
    
    /**
     * Display the default template
     */
    function wpm_get_default_template() {
        $file = file_get_contents(WPM_DIR.'/themes/default/index2.php');
        return $file;
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
            'image' => WP_PLUGIN_URL.'/wp-maintenance/images/default2.png',
            'image_width' => 450,
            'image_height' => 450,
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
            'add_wplogin' => 0,
            'b_enable_image' => 0,
            'b_opacity_image' => '0.2',
            'disable' => 0,
            'pageperso' => 0,
            'date_cpt_size' => 6,
            'color_bg_header' => '#ffffff',
            'add_wplogin_title' => '',
            'headercode' => '',
            'message_cpt_fin' => '',
            'b_repeat_image' => '',
            'color_cpt_bg' => '',
            'font_end_cpt' => 'PT Sans',
            'cpt_end_size' => 3,
            'enable_slider' => 0,
            'slider_auto' => 'false',
            'slider_nav' => 'false',
            'container_active' => 0,
            'container_color' => '#ffffff',
            'container_opacity' => '0.5',
            'container_width' => 80,
            'dashboard_delete_db' => 1,
            'error_503' => 1,
            'enable_footer' => 0

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

        if( isset($paramMMode['dashboard_delete_db']) && $paramMMode['dashboard_delete_db'] == 1 ) {

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
        // Add Style for all admin
        echo '
<style>#wpadminbar .wpmbackground-on > .ab-item{ color:#fff;background-color: #f44; }#wpadminbar .wpmbackground-on .ab-icon:before { content: "\f308";top: 2px;color:#fff !important; }#wpadminbar .wpmbackground-on:hover > .ab-item{ background-color: #a30 !important;color:#fff !important; }#wpadminbar .wpmbackground-off > .ab-item{ color:#fff; }#wpadminbar .wpmbackground-off .ab-icon:before { content: "\f308";top: 2px;color:#fff !important; }</style>        
        ';
        if (isset($_GET['page']) && strpos($_GET['page'], 'wp-maintenance') !==false) {
            echo '<link rel="stylesheet" type="text/css" media="all" href="' .WPM_PLUGIN_URL.'css/wpm-admin.css">';

        } else {
            echo '
<style>#maintenance-on{background:#0ed74c;border-radius:50%;width:14px;height:14px;float: left;margin-right: 5px;margin-top: 9px;}#maintenance-off{background:#d70e25;border-radius:50%;width:14px;height:14px;float: left;margin-right: 5px;margin-top: 9px;}</style>';
        }
    }
    /* Ajout Notification admin barre */
    function wpm_add_menu_admin_bar( $wp_admin_bar ) {

        $checkActive = get_option('wp_maintenance_active');
        $textAdmin = '<span class="ab-icon"></span> '.__('WP Maintenance', 'wp-maintenance');
        $classAdminBar = 'off';
        
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
        add_submenu_page( 'wp-maintenance', 'WP Maintenance > '.__('SEO', 'wp-maintenance'), __('SEO', 'wp-maintenance'), 'manage_options', 'wp-maintenance-seo', array( $this, 'wpm_seo_page') );
        add_submenu_page( 'wp-maintenance', 'WP Maintenance > '.__('Social Networks', 'wp-maintenance'), __('Social Networks', 'wp-maintenance'), 'manage_options', 'wp-maintenance-socialnetworks', array( $this, 'wpm_socialnetworks_page') );
        add_submenu_page( 'wp-maintenance', 'WP Maintenance > '.__('Footer', 'wp-maintenance'), __('Footer', 'wp-maintenance'), 'manage_options', 'wp-maintenance-footer', array( $this, 'wpm_footer_page') );
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

    function wpm_seo_page() {

        //must check that the user has the required capability
        if (!current_user_can('manage_options')) {
          wp_die( __("You do not have sufficient privileges to access this page.", 'sponsorpress') );
        }
        include(WPM_DIR."/views/wp-maintenance-seo.php");
    }

    function wpm_socialnetworks_page() {

        //must check that the user has the required capability
        if (!current_user_can('manage_options')) {
          wp_die( __("You do not have sufficient privileges to access this page.", 'sponsorpress') );
        }
        include(WPM_DIR."/views/wp-maintenance-socialnetworks.php");
    }

    function wpm_footer_page() {

        //must check that the user has the required capability
        if (!current_user_can('manage_options')) {
          wp_die( __("You do not have sufficient privileges to access this page.", 'sponsorpress') );
        }
        include(WPM_DIR."/views/wp-maintenance-footer.php");
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
            $ip_autorized = array();
            foreach($lienIpAddress as $ipAutorized) {
                if( $ipAutorized!='' ) {
                    array_push( $ip_autorized, $ipAutorized);
                }
            }          
            if ( array_search(wpm_get_ip(), $ip_autorized) !== FALSE ) {
                $statusActive = 0;
            }
            
        }

        /* Désactive le mode maintenance pour les Roles définis */
        if(get_option('wp_maintenance_limit')) { extract(get_option('wp_maintenance_limit')); }
        $paramLimit = get_option('wp_maintenance_limit');

        if( isset($paramLimit) && !empty($paramLimit) ) {
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
        /*if( isset($paramMMode['id_pages']) && !empty($paramMMode['id_pages']) ) {
            $listPageId = explode(',', $paramMMode['id_pages']);
            foreach($listPageId as $keyPageId => $valPageId) {
                if( $valPageId == get_the_ID() ) {
                    $statusActive = 0;
                }
            }
        }*/
  
        /* On désactive le mode maintenance pour les admins */
        if( current_user_can('administrator') == true ) {
            $statusActive = 0;
        }
        /* Mode Preview */
        if( isset($_GET['wpmpreview']) && $_GET['wpmpreview']=='true' ) { 
            $statusActive = 1;
        }

        return $statusActive;
    }

    /* Mode Maintenance */
    function wpm_maintenance_mode() {

        global $post;

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

        if( isset($paramMMode['pageperso']) && $paramMMode['pageperso']==1 && $urlTpl !== '' ) {
            include_once( $urlTpl );
            die();
        }  
        
        /* Si on désactive le mode maintenance en fin de compte à rebours */
        if( ( isset($paramMMode['disable']) && $paramMMode['disable']==1 ) && $this->wpm_check_active() == 1 ) {

            if( $dateNow > $dateFinCpt ) {
                $ChangeStatus = wpm_change_active();
                $disableCounter = wpm_update_settings( array('disable'=> 0) );
            }
        }

        $statusPageActive = 1;
        /*Désactive le mode maintenance pour les PAGE ID définies */
        if( isset($paramMMode['id_pages']) && !empty($paramMMode['id_pages']) ) {
            $listPageId = explode(',', $paramMMode['id_pages']);
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

            if( isset($paramMMode['error_503']) && $paramMMode['error_503']== 1 ) {
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
                "{Analytics}" => wpm_analytics(),
                "{TopSocialIcon}" => wpm_social_position("top"),
                "{BottomSocialIcon}" => wpm_social_position("bottom"),
                "{FooterText}" => wpm_footer_text(),
                "{AddStyleWysija}" => sanitize_text_field(wpm_stylenewsletter()),
                "{Newsletter}" => wpm_newsletter(),
                "{SliderCSS}" => WPM_Slider::slider_css(),
                "{ScriptSlider}" => WPM_Slider::slider_scripts(),
                "{ScriptSlideshow}" => WPM_Slider::slider_functions(),
                "{Counter}" => WPM_Countdown::display($dateCpt),
                "{SlideshowAL}" => WPM_Slider::slidershow('abovelogo'),
                "{SlideshowBL}" => WPM_Slider::slidershow('belowlogo'),
                "{SlideshowBT}" => WPM_Slider::slidershow('belowtext'),
                "{Url}" => WPM_PLUGIN_URL


            );
            
            echo strtr($template, $template_tags);
            exit();
        } 
    }

}


?>