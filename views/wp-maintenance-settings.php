<?php

defined( 'ABSPATH' ) or die( 'Not allowed' );

$messageUpdate = 0;
/* Update des paramètres */
if(isset($_POST['action']) && $_POST['action'] == 'update_settings' && wp_verify_nonce($_POST['security-settings'], 'valid-settings') ) {

    if(empty($_POST["wpoptions"]["pageperso"]) ) { $_POST["wpoptions"]["pageperso"] = 0; }
    if(empty($_POST["wpoptions"]["dashboard_delete_db"]) ) { $_POST["wpoptions"]["dashboard_delete_db"] = 0; }
    if(empty($_POST["wpoptions"]["error_503"]) ) { $_POST["wpoptions"]["error_503"] = 0; }
   
    $updateLimitSettings = wpm_update_settings($_POST["wp_maintenance_limit"], 'wp_maintenance_limit');
    $updateIpAdressSettings = wpm_update_settings($_POST["wp_maintenance_ipaddresses"], 'wp_maintenance_ipaddresses');

    $updateSetting = wpm_update_settings($_POST["wpoptions"], 'wp_maintenance_settings_options');
    if($updateSetting == true ) { $messageUpdate = 1; }

}

// Récupère les paramètres sauvegardés
if(get_option('wp_maintenance_settings_options')) { extract(get_option('wp_maintenance_settings_options')); }
$wpoptions = get_option('wp_maintenance_settings_options');

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
    <h2 class="headerpage"><?php esc_html_e('WP Maintenance - Settings', 'wp-maintenance'); ?> <sup>v.<?php echo esc_html(WPM_VERSION); ?></sup></h2>
    <?php if(isset($messageUpdate) && $messageUpdate == 1 ) { ?>
        <div id="message" class="updated fade"><p><strong><?php esc_html_e('Options saved.', 'wp-maintenance'); ?></strong></p></div>
    <?php } ?>
    <!-- END HEADER -->

    <div class="wp-maintenance-wrapper">
        
        <?php echo wp_kses(wpm_get_nav2(), wpm_autorizeHtml()); ?>

        <div class="wp-maintenance-tab-content wp-maintenance-tab-content-welcome" id="wp-maintenance-tab-content">

            <form method="post" action="" name="valide_settings">
                <input type="hidden" name="action" value="update_settings" />
                <?php wp_nonce_field('valid-settings', 'security-settings'); ?>
            
                <div class="wp-maintenance-module-options-block">
                
                    <h3><?php esc_html_e('Theme maintenance page', 'wp-maintenance'); ?></h3>
                    <p>
                        <label class="wp-maintenance-container"><span class="wp-maintenance-label-text"><?php esc_html_e('Yes, I use a theme maintenance page', 'wp-maintenance'); ?></span>
                            <input type="checkbox" name="wpoptions[pageperso]" value="1" <?php if(isset($wpoptions['pageperso']) && $wpoptions['pageperso']==1) { echo ' checked'; } ?>>
                            <span class="wp-maintenance-checkmark"></span>
                        </label>
                        
                    </p>
                    <div class="wp-maintenance-setting-row">
                    <?php esc_html_e('You can use this shortcode to include Social Networks icons:', 'wp-maintenance'); ?> <input type="text" value="do_shortcode('[wpm_social]');" onclick="select()" style="width:250px;" />
                    </div>

                    <!-- DELETE OPTION IF DEACTIVATED -->
                    <h3><?php esc_html_e('Delete custom settings upon plugin deactivation?', 'wp-maintenance'); ?></h3>
                    <p>
                        <label class="wp-maintenance-container"><span class="wp-maintenance-label-text"><?php esc_html_e('Yes, all custom settings will be deleted from database upon plugin deactivation', 'wp-maintenance'); ?></span>
                            <input type="checkbox" name="wpoptions[dashboard_delete_db]" value="1" <?php if(isset($wpoptions['dashboard_delete_db']) && $wpoptions['dashboard_delete_db']==1) { echo ' checked'; } ?>>
                            <span class="wp-maintenance-checkmark"></span>
                        </label>
                    </p>

                    <!-- DISPLAY 503 ERROR? -->
                    <h3><?php esc_html_e('Display code HTTP Error 503?', 'wp-maintenance'); ?></h3>
                    <p>
                        <label class="wp-maintenance-container"><span class="wp-maintenance-label-text"><?php esc_html_e('Yes, inform visitors and search engines that my site is temporarily unavailable.', 'wp-maintenance'); ?></span>
                            <input type="checkbox" name="wpoptions[error_503]" value="1" <?php if(isset($wpoptions['error_503']) && $wpoptions['error_503']==1) { echo ' checked'; } ?>>
                            <span class="wp-maintenance-checkmark"></span>
                        </label>
                    </p>

                    <!-- Remove Google Font -->
                    <h3><?php esc_html_e('Remove Google Fonts', 'wp-maintenance'); ?></h3>
                    <p>
                        <label class="wp-maintenance-container"><span class="wp-maintenance-label-text"><?php esc_html_e('Yes, remove Google Font option.', 'wp-maintenance'); ?></span>
                            <input type="checkbox" name="wpoptions[remove_googlefonts]" value="1" <?php if(isset($wpoptions['remove_googlefonts']) && $wpoptions['remove_googlefonts']==1) { echo ' checked'; } ?>>
                            <span class="wp-maintenance-checkmark"></span>
                        </label>
                    </p>

                    <!-- Remove Admin BAR -->
                    <h3><?php esc_html_e('Display in Admin bar?', 'wp-maintenance'); ?></h3>
                    <p>
                        <label class="wp-maintenance-container"><span class="wp-maintenance-label-text"><?php esc_html_e('Yes, remove display in admin bar.', 'wp-maintenance'); ?></span>
                            <input type="checkbox" name="wpoptions[remove_adminbar]" value="1" <?php if(isset($wpoptions['remove_adminbar']) && $wpoptions['remove_adminbar']==1) { echo ' checked'; } ?>>
                            <span class="wp-maintenance-checkmark"></span>
                        </label>
                    </p>
                    <p class="submit"><button type="submit" name="footer_submit" id="footer_submit" class="wp-maintenance-button wp-maintenance-button-primary"><?php esc_html_e('Save', 'wp-maintenance'); ?></button></p>

                </div>

                <!-- Roles and Capabilities -->
                <div class="wp-maintenance-module-options-block">
                    
                    <div class="wp-maintenance-settings-section-header">
                        <h3 class="wp-maintenance-settings-section-title" id="module-import_export"><?php esc_html_e('Roles and Capabilities', 'wp-maintenance'); ?></h3>
                    </div>
                    <h3><?php esc_html_e('Allow the site to display these roles', 'wp-maintenance'); ?></h3>
                    <p>
                        <input type="hidden" name="wp_maintenance_limit[administrator]" value="administrator" />                        
                        <?php
                        $roles = wpm_get_roles();
                        foreach($roles as $role=>$name) {
                            $limitCheck = '';
                            if(isset($paramLimit[$role]) && $paramLimit[$role]==$role) { $limitCheck = ' checked'; }
                            if($role !='administrator') {
                            
                    ?>
                        <label class="wp-maintenance-container"><span class="wp-maintenance-label-text"><?php echo esc_html($name); ?></span>
                            <input type="checkbox" class="switch-field" name="wp_maintenance_limit[<?php echo esc_html($role); ?>]" value="<?php echo esc_html($role); ?>"<?php echo esc_html($limitCheck); ?> />
                            <span class="wp-maintenance-checkmark"></span>
                        </label><br />
                        
                    <?php } }//end foreach ?>
                    </p>
                    <p class="submit"><button type="submit" name="footer_submit" id="footer_submit" class="wp-maintenance-button wp-maintenance-button-primary"><?php esc_html_e('Save', 'wp-maintenance'); ?></button></p>
                </div>

                <!-- IP addresses autorized -->
                <div class="wp-maintenance-module-options-block">
                    
                    <div class="wp-maintenance-settings-section-header">
                        <h3 class="wp-maintenance-settings-section-title" id="module-import_export"><?php esc_html_e('IP autorized', 'wp-maintenance'); ?></h3>
                    </div>
                    <div class="wp-maintenance-setting-row">
                        <label for="wp_maintenance_ipaddresses" class="wp-maintenance-setting-row-title"><?php esc_html_e('Allow the site to display these IP addresses. Please, enter one IP address by line', 'wp-maintenance'); ?></label>
                        <?php
                            if(isset($paramIpAddress) && $paramIpAddress!='' ) { 

                                foreach($paramIpAddress as $ipAutorized) {
                                    if($ipAutorized!='') { 
                                        echo '<input type="text" name="wp_maintenance_ipaddresses[]" size="80%" placeholder="'.esc_html__('Enter one IP addresses here', 'wp-maintenance').'" class="wp-maintenance-input" value="'.esc_html($ipAutorized).'"><br />';
                                    }
                                }
                            }
                        ?>
                        <input type="text" name="wp_maintenance_ipaddresses[]" size="80%" placeholder='<?php esc_html_e('Enter one IP addresses here', 'wp-maintenance'); ?>' class="wp-maintenance-input" value="">
                    </div>

                    <p class="submit"><button type="submit" name="footer_submit" id="footer_submit" class="wp-maintenance-button wp-maintenance-button-primary"><?php esc_html_e('Save', 'wp-maintenance'); ?></button></p>
                </div>

                <!-- ID pages autorized -->
                <div class="wp-maintenance-module-options-block">
                    
                    <div class="wp-maintenance-settings-section-header">
                        <h3 class="wp-maintenance-settings-section-title" id="module-import_export"><?php esc_html_e('ID pages autorized', 'wp-maintenance'); ?></h3>
                    </div>
                    <div class="wp-maintenance-setting-row">
                        <label for="wpoptions[id_pages]" class="wp-maintenance-setting-row-title"><?php esc_html_e('Allow the site to display these ID pages. Please, enter the ID pages separate with comma', 'wp-maintenance'); ?></label>
                        <input name="wpoptions[id_pages]" size="80%" class="wp-maintenance-input" value="<?php if(isset($wpoptions['id_pages']) && $wpoptions['id_pages']!='' ) { echo esc_html($wpoptions['id_pages']); } ?>" />
                    </div>

                    <p class="submit"><button type="submit" name="footer_submit" id="footer_submit" class="wp-maintenance-button wp-maintenance-button-primary"><?php esc_html_e('Save', 'wp-maintenance'); ?></button></p>
                </div>

                <!-- Header Code -->
                <div class="wp-maintenance-module-options-block">
                    
                    <div class="wp-maintenance-settings-section-header">
                        <h3 class="wp-maintenance-settings-section-title" id="module-import_export"><?php esc_html_e('Header Code', 'wp-maintenance'); ?></h3>
                    </div>
                    <div class="wp-maintenance-setting-row">
                        <label for="wpoptions[headercode]" class="wp-maintenance-setting-row-title"><?php esc_html_e('The following code will add to the <head> tag. Useful if you need to add additional scripts such as JS', 'wp-maintenance'); ?></label>
                        <textarea id="headercode" name="wpoptions[headercode]" class="wp-maintenance-input" COLS=50 ROWS=2><?php if(isset($wpoptions['headercode']) && $wpoptions['headercode']!='' ) { echo esc_textarea(stripslashes($wpoptions['headercode'])); }  ?></textarea>
                    </div>

                    <p class="submit"><button type="submit" name="footer_submit" id="footer_submit" class="wp-maintenance-button wp-maintenance-button-primary"><?php esc_html_e('Save', 'wp-maintenance'); ?></button></p>
                </form>
            </div>
            
            <div class="wp-maintenance-module-options-block">
                <div class="wp-maintenance-settings-section-header">
                    <h3 class="wp-maintenance-settings-section-title" id="module-import_export"><?php esc_html_e('Export / Import Settings', 'wp-maintenance'); ?></h3>
                </div>
                <div class="wp-maintenance-setting-row">
                    <label class="wp-maintenance-setting-row-title"><?php esc_html_e('Export Settings', 'wp-maintenance'); ?></label>
                    <form method="post">
                        <input type="hidden" name="wpm_action" value="export_settings" />
                        <?php wp_nonce_field( 'go_export_nonce', 'wpm_export_nonce' ); ?>
                        <?php submit_button( __( 'Export', 'wp-maintenance' ), 'wp-maintenance-button wp-maintenance-button-secondary', 'submit', false ); ?>
                    </form>
                </div>
                
                <div class="wp-maintenance-setting-row">
                    <label class="wp-maintenance-setting-row-title"><?php esc_html_e('Import the plugin settings from a .json file. This file can be obtained by exporting the settings on another site using the form above', 'wp-maintenance'); ?></label>
                    <form method="post" enctype="multipart/form-data">
                        <input type="hidden" name="wpm_action" value="import_settings" />
                        <input type="file" name="wpm_import_file"/>                            
                        <?php wp_nonce_field( 'go_import_nonce', 'wpm_import_nonce' ); ?><p>
                        <?php submit_button( __( 'Import', 'wp-maintenance' ), 'wp-maintenance-button wp-maintenance-button-secondary', 'submit', false ); ?></p>
                    </form>
                </div>
            </div>

         </div>
    </div>    
    
    <?php echo wp_kses(wpm_footer(), wpm_autorizeHtml()); ?>
    
</div>
<script>
    jQuery(document).ready(function($) {
        var editorSettings = wp.codeEditor.defaultSettings ? _.clone( wp.codeEditor.defaultSettings ) : {};
        editorSettings.codemirror = _.extend(
            {},
            editorSettings.codemirror,
            {
                indentUnit: 2,
                tabSize: 2,
                mode: "htmlmixed",
                htmlMode: true,
                lineNumbers: true,
                matchBrackets: true,
                smartIndent: true,
            }
        );
        var editor = wp.codeEditor.initialize( $('#headercode'), editorSettings );
    });
</script>