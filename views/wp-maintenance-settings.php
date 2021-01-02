<?php

defined( 'ABSPATH' ) or die( 'Not allowed' );

$messageUpdate = 0;
/* Update des paramètres */
if( isset($_POST['action']) && $_POST['action'] == 'update_settings' && wp_verify_nonce($_POST['security-settings'], 'valid-settings') ) {

    if( empty($_POST["wp_maintenance_settings"]["pageperso"]) ) { $_POST["wp_maintenance_settings"]["pageperso"] = 0; }
    if( empty($_POST["wp_maintenance_settings"]["dashboard_delete_db"]) ) { $_POST["wp_maintenance_settings"]["dashboard_delete_db"] = 0; }
    if( empty($_POST["wp_maintenance_settings"]["error_503"]) ) { $_POST["wp_maintenance_settings"]["error_503"] = 0; }

    update_option('wp_maintenance_limit', $_POST["wp_maintenance_limit"]);
    update_option('wp_maintenance_ipaddresses', $_POST["wp_maintenance_ipaddresses"]);
    
    $options_saved = wpm_update_settings($_POST["wp_maintenance_settings"]);
    $messageUpdate = 1;
}

// Récupère les paramètres sauvegardés
if(get_option('wp_maintenance_settings')) { extract(get_option('wp_maintenance_settings')); }
$paramMMode = get_option('wp_maintenance_settings');

// Récupère les Rôles et capabilités
if(get_option('wp_maintenance_limit')) { extract(get_option('wp_maintenance_limit')); }
$paramLimit = get_option('wp_maintenance_limit');

// Récupère les ip autorisee
$paramIpAddress = get_option('wp_maintenance_ipaddresses');

?>
<script type="text/javascript">

jQuery(document).ready(function() {

  jQuery( ".postbox .hndle" ).on( "mouseover", function() {
    jQuery( this ).css( "cursor", "pointer" );
  });
  /* Sliding the panels */
  jQuery(".postbox").on('click', '.handlediv', function(){
    jQuery(this).siblings(".inside").slideToggle();
  });
  jQuery(".postbox").on('click', '.hndle', function(){
    jQuery(this).siblings(".inside").slideToggle();
  });
    
});
</script>
<style>
    .CodeMirror {
      border: 1px solid #eee;
      height: auto;
    }
</style>
<div class="wrap">
    
    <!-- HEADER -->
    <?php echo wpm_get_header( $messageUpdate ) ?>
    <!-- END HEADER -->

    <div class="wp-maintenance-wrapper wp-maintenance-flex wp-maintenance-flex-top">
        
        <?php echo wpm_get_nav(); ?>
        
        <div class="wp-maintenance-tab-content wp-maintenance-tab-content-welcome" id="wp-maintenance-tab-content">
            
            <div class="wp-maintenance-tab-content-header"><i class="dashicons dashicons-admin-generic" style="margin-right: 10px;height:50px;width:50px;font-size:50px;padding: 8px 8px 14px 10px;border-radius: 5px;display: inline;float:left;"></i>  <h2 class="wp-maintenance-tc-title"><?php _e('Generals Settings', 'wp-maintenance'); ?></h2></div>

            <div class="wp-maintenance-module-options-block" id="block-advanced_options" data-module="welcome">
                <form method="post" action="" name="valide_settings">
                    <input type="hidden" name="action" value="update_settings" />
                    <?php wp_nonce_field('valid-settings', 'security-settings'); ?>


                    <h3><?php _e('Theme maintenance page', 'wp-maintenance'); ?></h3>
                    <p class="wp-maintenance-fieldset-item ">
                        <label class="wp-maintenance-container"><span class="wp-maintenance-label-text"><?php _e('Yes, I use a theme maintenance page', 'wp-maintenance'); ?></span>
                            <input type="checkbox" name="wp_maintenance_settings[pageperso]" value="1" <?php if( isset($paramMMode['pageperso']) && $paramMMode['pageperso']==1) { echo ' checked'; } ?>>
                            <span class="wp-maintenance-checkmark"></span>
                        </label>
                        
                    </p>
                    <div class="wp-maintenance-setting-row">
                    <?php _e('You can use this shortcode to include Google Analytics code:', 'wp-maintenance'); ?> <input type="text" value="do_shortcode('[wpm_analytics']);" onclick="select()" style="width:250px;" /><br /><?php _e('You can use this shortcode to include Social Networks icons:', 'wp-maintenance'); ?> <input type="text" value="do_shortcode('[wpm_social]');" onclick="select()" style="width:250px;" />
                    </div>

                    <!-- DELETE OPTION IF DEACTIVATED -->
                    <h3><?php _e('Delete custom settings upon plugin deactivation?', 'wp-maintenance'); ?></h3>
                    <p class="wp-maintenance-fieldset-item ">
                        <label class="wp-maintenance-container"><span class="wp-maintenance-label-text"><?php _e('Yes, all custom settings will be deleted from database upon plugin deactivation', 'wp-maintenance'); ?></span>
                            <input type="checkbox" name="wp_maintenance_settings[dashboard_delete_db]" value="1" <?php if( isset($paramMMode['dashboard_delete_db']) && $paramMMode['dashboard_delete_db']==1) { echo ' checked'; } ?>>
                            <span class="wp-maintenance-checkmark"></span>
                        </label>
                    </p>

                    <!-- DISPLAY 503 ERROR? -->
                    <h3><?php _e('Display code HTTP Error 503?', 'wp-maintenance'); ?></h3>
                    <p class="wp-maintenance-fieldset-item ">
                        <label class="wp-maintenance-container"><span class="wp-maintenance-label-text"><?php _e('Yes, all custom settings will be deleted from database upon plugin deactivation', 'wp-maintenance'); ?></span>
                            <input type="checkbox" name="wp_maintenance_settings[error_503]" value="1" <?php if( isset($paramMMode['error_503']) && $paramMMode['error_503']==1) { echo ' checked'; } ?>>
                            <span class="wp-maintenance-checkmark"></span>
                        </label>
                    </p>
                    <p class="submit"><button type="submit" name="footer_submit" id="footer_submit" class="wp-maintenance-button wp-maintenance-button-primary"><?php _e('Save', 'wp-maintenance'); ?></button></p>

                    <!-- Roles and Capabilities -->
                    <div class="wp-maintenance-settings-section-header">
                        <h3 class="wp-maintenance-settings-section-title" id="module-import_export"><?php _e('Roles and Capabilities', 'wp-maintenance'); ?></h3>
                    </div>
                    <h3><?php _e('Allow the site to display these roles', 'wp-maintenance'); ?></h3>
                    <p class="wp-maintenance-fieldset-item ">
                        <input type="hidden" name="wp_maintenance_limit[administrator]" value="administrator" />                        
                        <?php
                        $roles = wpm_get_roles();
                        foreach($roles as $role=>$name) {
                            $limitCheck = '';
                            if( isset($paramLimit[$role]) && $paramLimit[$role]==$role) { $limitCheck = ' checked'; }
                            if( $role !='administrator') {
                            
                    ?>
                        <label class="wp-maintenance-container"><span class="wp-maintenance-label-text"><?php echo $name; ?></span>
                            <input type="checkbox" class="switch-field" name="wp_maintenance_limit[<?php echo $role; ?>]" value="<?php echo $role; ?>"<?php echo $limitCheck; ?> />
                            <span class="wp-maintenance-checkmark"></span>
                        </label><br />
                        
                    <?php } }//end foreach ?>
                    </p>
                    <p class="submit"><button type="submit" name="footer_submit" id="footer_submit" class="wp-maintenance-button wp-maintenance-button-primary"><?php _e('Save', 'wp-maintenance'); ?></button></p>

                    <!-- IP addresses autorized -->
                    <div class="wp-maintenance-settings-section-header">
                        <h3 class="wp-maintenance-settings-section-title" id="module-import_export"><?php _e('IP autorized', 'wp-maintenance'); ?></h3>
                    </div>
                    <div class="wp-maintenance-setting-row">
                        <label for="wp_maintenance_ipaddresses" class="wp-maintenance-setting-row-title"><?php _e('Allow the site to display these IP addresses. Please, enter one IP address by line', 'wp-maintenance'); ?></label>
                        <textarea name="wp_maintenance_ipaddresses" class="wp-maintenance-input" ROWS="5" style="width:80%;"><?php if( isset($paramIpAddress) && $paramIpAddress!='' ) { echo esc_textarea($paramIpAddress); } ?></textarea>
                    </div>

                    <p class="submit"><button type="submit" name="footer_submit" id="footer_submit" class="wp-maintenance-button wp-maintenance-button-primary"><?php _e('Save', 'wp-maintenance'); ?></button></p>

                    <!-- ID pages autorized -->
                    <div class="wp-maintenance-settings-section-header">
                        <h3 class="wp-maintenance-settings-section-title" id="module-import_export"><?php _e('ID pages autorized', 'wp-maintenance'); ?></h3>
                    </div>
                    <div class="wp-maintenance-setting-row">
                        <label for="wp_maintenance_settings[id_pages]" class="wp-maintenance-setting-row-title"><?php _e('Allow the site to display these ID pages. Please, enter the ID pages separate with comma', 'wp-maintenance'); ?></label>
                        <input name="wp_maintenance_settings[id_pages]" size="80%" class="wp-maintenance-input" value="<?php if( isset($paramMMode['id_pages']) && $paramMMode['id_pages']!='' ) { echo esc_html($paramMMode['id_pages']); } ?>" />
                    </div>

                    <p class="submit"><button type="submit" name="footer_submit" id="footer_submit" class="wp-maintenance-button wp-maintenance-button-primary"><?php _e('Save', 'wp-maintenance'); ?></button></p>

                    <!-- Header Code -->
                    <div class="wp-maintenance-settings-section-header">
                        <h3 class="wp-maintenance-settings-section-title" id="module-import_export"><?php _e('Header Code', 'wp-maintenance'); ?></h3>
                </div>
                    <div class="wp-maintenance-setting-row">
                        <label for="wp_maintenance_settings[id_pages]" class="wp-maintenance-setting-row-title"><?php _e('The following code will add to the <head> tag. Useful if you need to add additional scripts such as CSS or JS', 'wp-maintenance'); ?></label>
                        <textarea id="headercode" name="wp_maintenance_settings[headercode]" class="wp-maintenance-input" COLS=50 ROWS=2><?php if( isset($paramMMode['headercode']) && $paramMMode['headercode']!='' ) { echo esc_textarea(stripslashes($paramMMode['headercode'])); }  ?></textarea>
                    </div>

                    <p class="submit"><button type="submit" name="footer_submit" id="footer_submit" class="wp-maintenance-button wp-maintenance-button-primary"><?php _e('Save', 'wp-maintenance'); ?></button></p>
                </form>
                <div class="wp-maintenance-settings-section-header">
                    <h3 class="wp-maintenance-settings-section-title" id="module-import_export"><?php _e('Export / Import Settings', 'wp-maintenance'); ?></h3>
                </div>
                <div class="wp-maintenance-setting-row">
                    <label for="wp_maintenance_settings[id_pages]" class="wp-maintenance-setting-row-title"><?php _e('Export Settings', 'wp-maintenance'); ?></label>
                    <form method="post">
                        <input type="hidden" name="wpm_action" value="export_settings" />
                        <?php wp_nonce_field( 'wpm_export_nonce', 'wpm_export_nonce' ); ?>
                        <?php submit_button( __( 'Export', 'wp-maintenance' ), 'wp-maintenance-button wp-maintenance-button-secondary', 'submit', false ); ?>
                    </form>
                </div>
                
                <div class="wp-maintenance-setting-row">
                    <label for="wp_maintenance_settings[id_pages]" class="wp-maintenance-setting-row-title"><?php _e('Import the plugin settings from a .json file. This file can be obtained by exporting the settings on another site using the form above', 'wp-maintenance'); ?></label>
                    <form method="post" enctype="multipart/form-data">
                        <input type="hidden" name="wpm_action" value="import_settings" />
                        <input type="file" name="wpm_import_file"/>                            
                        <?php wp_nonce_field( 'wpm_import_nonce', 'wpm_import_nonce' ); ?><p>
                        <?php submit_button( __( 'Import', 'wp-maintenance' ), 'wp-maintenance-button wp-maintenance-button-secondary', 'submit', false ); ?></p>
                    </form>
                </div>

            </div>
        </div>
    </div>    
    
    <?php echo wpm_footer(); ?>
    
</div>
<script>
    var editor = CodeMirror.fromTextArea(document.getElementById("headercode"), {
    lineNumbers: true,
    matchBrackets: true,
    textWrapping: true,
    lineWrapping: true,
    mode: "text/x-scss",
    theme:"material"
    });
</script>