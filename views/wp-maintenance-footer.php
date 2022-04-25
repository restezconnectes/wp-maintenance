<?php

defined( 'ABSPATH' ) or die( 'Not allowed' );

$messageUpdate = 0;
/* Update des paramètres */
if( isset($_POST['action']) && $_POST['action'] == 'update_footer' && wp_verify_nonce($_POST['security-footer'], 'valid-footer') ) {
   
    if( empty($_POST["wp_maintenance_settings"]["add_wplogin"]) ) { $_POST["wp_maintenance_settings"]["add_wplogin"] = 0; }
    if( empty($_POST["wp_maintenance_settings"]["enable_footer"]) ) { $_POST["wp_maintenance_settings"]["enable_footer"] = 0; }
    $options_saved = wpm_update_settings($_POST["wp_maintenance_settings"]);
    $messageUpdate = 1;
}

// Récupère les paramètres sauvegardés
if(get_option('wp_maintenance_settings')) { extract(get_option('wp_maintenance_settings')); }
$paramMMode = get_option('wp_maintenance_settings');

?>
<style>.CodeMirror {border: 1px solid #eee;height: auto;}</style>
<div class="wrap">

    <!-- HEADER -->
    <?php echo wpm_get_header( $messageUpdate ) ?>
    <!-- END HEADER -->

    <div class="wp-maintenance-wrapper">

        <?php echo wpm_get_nav2(); ?>
        
        <div class="wp-maintenance-tab-content wp-maintenance-tab-content-welcome" id="wp-maintenance-tab-content">

            <form method="post" action="" id="valide_settings" name="valide_settings">
                <input type="hidden" name="action" value="update_footer" />
                <?php wp_nonce_field('valid-footer', 'security-footer'); ?>

                <!-- ENABLE FOOTER -->
                <div class="wp-maintenance-module-options-block">
                
                    <div class="wp-maintenance-settings-section-header">
                        <h3 class="wp-maintenance-settings-section-title" id="module-import_export"><?php _e('Enable Footer?', 'wp-maintenance'); ?></h3>
                    </div>

                    <p>
                        <label class="wp-maintenance-container"><span class="wp-maintenance-label-text"><?php _e('Yes, enable footer', 'wp-maintenance'); ?></span>
                            <input type="checkbox" name="wp_maintenance_settings[enable_footer]" value="1" <?php if( isset($paramMMode['enable_footer']) && $paramMMode['enable_footer']==1) { echo ' checked'; } ?>>
                            <span class="wp-maintenance-checkmark"></span>
                        </label>
                    </p>

                    <!-- PIED DE PAGE  -->
                    <div class="wp-maintenance-settings-section-header"><h3 class="wp-maintenance-settings-section-title" id="module-import_export"><?php _e('Text in the footer', 'wp-maintenance'); ?></h3></div>
                    <?php 
                        $settingsTextmaintenance =   array(
                            'wpautop' => true, // use wpautop?
                            'media_buttons' => false, // show insert/upload button(s)
                            'textarea_name' => 'wp_maintenance_settings[text_bt_maintenance]', // set the textarea name to something different, square brackets [] can be used here
                            'textarea_rows' => get_option('default_post_edit_rows', 10), // rows="..."
                            'tabindex' => '',
                            'editor_css' => '', //  extra styles for both visual and HTML editors buttons, 
                            'editor_class' => 'wpm-textbtmaintenance', // add extra class(es) to the editor textarea
                            'teeny' => true, // output the minimal editor config used in Press This
                            'dfw' => false, // replace the default fullscreen with DFW (supported on the front-end in WordPress 3.4)
                            'tinymce' => true, // load TinyMCE, can be used to pass settings directly to TinyMCE using an array()
                            'quicktags' => true // load Quicktags, can be used to pass settings directly to Quicktags using an array()
                        );
                    $textBt =  '';
                    if( isset($paramMMode['text_bt_maintenance']) && $paramMMode['text_bt_maintenance']!='' ) { $textBt = stripslashes($paramMMode['text_bt_maintenance']); } 
                    ?>
                    <?php wp_editor( nl2br($textBt), 'wpm-textbtmaintenance', $settingsTextmaintenance ); ?>
                    <p class="submit"><button type="submit" name="footer_submit" id="footer_submit" class="wp-maintenance-button wp-maintenance-button-primary"><?php _e('Save', 'wp-maintenance'); ?></button></p>
                </div>
                
                <!-- LINK TO LOGIN -->
                <div class="wp-maintenance-module-options-block">
                    
                    <div class="wp-maintenance-settings-section-header">
                        <h3 class="wp-maintenance-settings-section-title" id="module-import_export"><?php _e('Add a link to dashboard in the footer?', 'wp-maintenance'); ?></h3>
                    </div>

                    <p>
                        <label class="wp-maintenance-container"><span class="wp-maintenance-label-text"><?php _e('Yes, show text and link to go to the dashboard', 'wp-maintenance'); ?></span>
                            <input type="checkbox" name="wp_maintenance_settings[add_wplogin]" value="1" <?php if( isset($paramMMode['add_wplogin']) && $paramMMode['add_wplogin'] == 1 ) { echo ' checked'; }?>>
                            <span class="wp-maintenance-checkmark"></span>
                        </label>
                    </p>

                    <div class="wp-maintenance-setting-row">
                        <label for="wp_maintenance_ipaddresses" class="wp-maintenance-setting-row-title"><?php _e('Enter your text and %DASHBOARD% shortcode', 'wp-maintenance'); ?></label>
                        <input type="text" name="wp_maintenance_settings[add_wplogin_title]" class="wp-maintenance-input" size="60%" value="<?php if( isset($paramMMode['add_wplogin_title']) && $paramMMode['add_wplogin_title']!='' ) { echo esc_html(stripslashes(trim($paramMMode['add_wplogin_title']))); } ?>" /><br />
                    <small><?php _e('Eg: connect to %DASHBOARD% here!', 'wp-maintenance'); ?> <?php _e('(%DASHBOARD% will be replaced with the link to the dashboard and the word "Dashboard")', 'wp-maintenance'); ?></small>
                    </div>
                        
                    <p class="submit"><button type="submit" name="footer_submit" id="footer_submit" class="wp-maintenance-button wp-maintenance-button-primary"><?php _e('Save', 'wp-maintenance'); ?></button></p>

                </div>
            </form>
        </div>

    </div>

</div>
