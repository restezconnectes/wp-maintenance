<?php

defined( 'ABSPATH' ) or die( 'Not allowed' );

$messageUpdate = 0;
/* Update des paramètres */
if( isset($_POST['action']) && $_POST['action'] == 'update_footer' && wp_verify_nonce($_POST['security-footer'], 'valid-footer') ) {
   
    if( empty($_POST["wp_maintenance_settings"]["enable_seo"]) ) { $_POST["wp_maintenance_settings"]["enable_seo"] = 0; }

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

    <div class="wp-maintenance-wrapper wp-maintenance-flex wp-maintenance-flex-top">
        
        <?php echo wpm_get_nav(); ?>
          
        <div class="wp-maintenance-tab-content wp-maintenance-tab-content-welcome" id="wp-maintenance-tab-content">
            
            <div class="wp-maintenance-tab-content-header"><i class="dashicons dashicons-admin-site-alt" style="margin-right: 10px;height:50px;width:50px;font-size:50px;padding: 8px 8px 14px 10px;border-radius: 5px;display: inline;float:left;"></i>  <h2 class="wp-maintenance-tc-title"><?php _e('SEO options', 'wp-maintenance'); ?></h2></div>

            <div class="wp-maintenance-module-options-block" id="block-advanced_options" data-module="welcome">
                
                <form method="post" action="" id="valide_settings" name="valide_settings">
                    <input type="hidden" name="action" value="update_footer" />
                    <?php wp_nonce_field('valid-footer', 'security-footer'); ?>

                    <!-- ENABLE SEO -->
                    <div class="wp-maintenance-settings-section-header">
                        <h3 class="wp-maintenance-settings-section-title" id="module-import_export"><?php _e('Active SEO settings?', 'wp-maintenance'); ?></h3>
                    </div>

                    <p class="wp-maintenance-fieldset-item ">
                        <label class="wp-maintenance-container"><span class="wp-maintenance-label-text"><?php _e('Yes, active SEO', 'wp-maintenance'); ?></span>
                            <input type="checkbox" name="wp_maintenance_settings[enable_seo]" value="1" <?php if( isset($paramMMode['enable_seo']) && $paramMMode['enable_seo']==1) { echo ' checked'; } ?>>
                            <span class="wp-maintenance-checkmark"></span>
                        </label>
                    </p>

                        
                    <p class="submit"><button type="submit" name="footer_submit" id="footer_submit" class="wp-maintenance-button wp-maintenance-button-primary"><?php _e('Save', 'wp-maintenance'); ?></button></p>

                    <div class="wp-maintenance-settings-section-header"><h3 class="wp-maintenance-settings-section-title" id="module-import_export"><?php _e('SEO Title & Description', 'wp-maintenance'); ?></h3></div>
                    
                    <div class="wp-maintenance-setting-row">
                        <label for="wp_maintenance_settings[seo_title]" class="wp-maintenance-setting-row-title"><?php _e('SEO Meta Title', 'wp-maintenance'); ?></label>
                        <input type="text" name="wp_maintenance_settings[seo_title]" size="80%" value="<?php if( isset($paramMMode['seo_title']) && $paramMMode['seo_title']!='' ) { echo esc_html(stripslashes(trim($paramMMode['seo_title']))); } ?>">
                    </div>

                    <div class="wp-maintenance-setting-row">
                        <label for="wp_maintenance_settings[seo_description]" class="wp-maintenance-setting-row-title"><?php _e('SEO Meta Description', 'wp-maintenance'); ?></label>
                        <input type="text" size="80%" name="wp_maintenance_settings[seo_description]" value="<?php if( isset($paramMMode['seo_description']) && $paramMMode['seo_description']!='' ) { echo esc_html(stripslashes(trim($paramMMode['seo_description']))); } ?>">
                    </div>
                    
                    <p class="submit"><button type="submit" name="footer_submit" id="footer_submit" class="wp-maintenance-button wp-maintenance-button-primary"><?php _e('Save', 'wp-maintenance'); ?></button></p>

                    <!-- UPLOADER UN FAVICON -->
                    <div class="wp-maintenance-settings-section-header"><h3 class="wp-maintenance-settings-section-title" id="module-import_export"><?php _e('Add a favicon', 'wp-maintenance'); ?></h3></div>

                    <div class="wp-maintenance-setting-row">
                        <label for="wp_maintenance_settings[seo_description]" class="wp-maintenance-setting-row-title"><?php _e('Enter a URL or upload an image', 'wp-maintenance'); ?></label>
                        <input id="upload_favicon" size="65%" name="wp_maintenance_settings[favicon]" value="<?php if( isset($paramMMode['favicon']) && $paramMMode['favicon']!='' ) { echo esc_url($paramMMode['favicon']); } ?>" type="text" /> <a href="#" id="upload_favicon_button" class="button button-primary" style="padding-top: 0.1em;padding-bottom: 0.1em;margin-top: 1px;" OnClick="this.blur();"><span> <?php _e('Media Image Library', 'wp-maintenance'); ?> </span></a><br />
                        <small><?php _e('Favicons are displayed in a browser tab. Need Help <a href="https://realfavicongenerator.net/" target="_blank">creating a favicon</a>?', 'wp-maintenance'); ?></small>
                        <?php if( isset($paramMMode['favicon']) && $paramMMode['favicon']!='' ) { ?>
                            <div class="wp-maintenance-encadre">
                                <?php _e('You use this favicon:', 'wp-maintenance'); ?><br />
                                <img src="<?php echo $paramMMode['favicon']; ?>" width="100" /><br />
                            </div> 
                        <?php } ?>
                    </div>
                    <p class="submit"><button type="submit" name="footer_submit" id="footer_submit" class="wp-maintenance-button wp-maintenance-button-primary"><?php _e('Save', 'wp-maintenance'); ?></button></p>

                    <!-- GOOGLE ANALYTICS -->
                    <div class="wp-maintenance-settings-section-header"><h3 class="wp-maintenance-settings-section-title" id="module-import_export"><?php _e('Analytics Code', 'wp-maintenance'); ?></h3></div>
                    <div class="wp-maintenance-setting-row">
                        <label for="wp_maintenance_settings[codeanalytics]" class="wp-maintenance-setting-row-title"><?php _e('Enter your analytics tracking code here:', 'wp-maintenance'); ?></label>
                        <textarea name="wp_maintenance_settings[codeanalytics]" wrap="off" class="wp-maintenance-input" rows="5%" cols="80%"><?php if( isset($paramMMode['codeanalytics']) && $paramMMode['codeanalytics']!='' ) { echo esc_html($paramMMode['codeanalytics']); } ?></textarea>
                    </div>
                    <p class="submit"><button type="submit" name="footer_submit" id="footer_submit" class="wp-maintenance-button wp-maintenance-button-primary"><?php _e('Save', 'wp-maintenance'); ?></button></p>

                </form>

            </div>
        </div>

    </div>
    <?php echo wpm_footer(); ?>
</div>
