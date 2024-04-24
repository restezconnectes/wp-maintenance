<?php

defined( 'ABSPATH' ) or die( 'Not allowed' );

$messageUpdate = 0;
/* Update des paramètres */
if( isset($_POST['action']) && $_POST['action'] == 'update_footer' && wp_verify_nonce($_POST['security-footer'], 'valid-footer') ) {
   
    if( empty($_POST["wpmseo"]["enable_seo"]) ) { $_POST["wpmseo"]["enable_seo"] = 0; }

    $updateSetting = wpm_update_settings( $_POST["wpmseo"], 'wp_maintenance_settings_seo');
    if( $updateSetting == true ) { $messageUpdate = 1; }
}

// Récupère les paramètres sauvegardés
if(get_option('wp_maintenance_settings_seo')) { extract(get_option('wp_maintenance_settings_seo')); }
$paramSeo= get_option('wp_maintenance_settings_seo');

?>
<style>.CodeMirror {border: 1px solid #eee;height: auto;}</style>
<div class="wrap">

    <!-- HEADER -->
    <h2 class="headerpage"><?php esc_html_e('WP Maintenance - Settings', 'wp-maintenance'); ?> <sup>v.<?php echo esc_html(WPM_VERSION); ?></sup></h2>
    <?php if( isset($messageUpdate) && $messageUpdate == 1 ) { ?>
        <div id="message" class="updated fade"><p><strong><?php esc_html_e('Options saved.', 'wp-maintenance'); ?></strong></p></div>
    <?php } ?>
    <!-- END HEADER -->

    <div class="wp-maintenance-wrapper">
        
        <?php echo wp_kses(wpm_get_nav2(), wpm_autorizeHtml()); ?>
        
        <div class="wp-maintenance-tab-content wp-maintenance-tab-content-welcome" id="wp-maintenance-tab-content">
            
            <form method="post" action="" id="valide_settings" name="valide_settings">
                <input type="hidden" name="action" value="update_footer" />
                <?php wp_nonce_field('valid-footer', 'security-footer'); ?>

                <!-- ENABLE SEO -->
                <div class="wp-maintenance-module-options-block">
                    
                    <div class="wp-maintenance-settings-section-header">
                        <h3 class="wp-maintenance-settings-section-title" id="module-import_export"><?php esc_html_e('Active SEO settings?', 'wp-maintenance'); ?></h3>
                    </div>

                    <p>
                        <label class="wp-maintenance-container"><span class="wp-maintenance-label-text"><?php esc_html_e('Yes, active SEO', 'wp-maintenance'); ?></span>
                            <input type="checkbox" name="wpmseo[enable_seo]" value="1" <?php if( isset($paramSeo['enable_seo']) && $paramSeo['enable_seo']==1) { echo ' checked'; } ?>>
                            <span class="wp-maintenance-checkmark"></span>
                        </label>
                    </p>

                        
                    <p class="submit"><button type="submit" name="footer_submit" id="footer_submit" class="wp-maintenance-button wp-maintenance-button-primary"><?php esc_html_e('Save', 'wp-maintenance'); ?></button></p>

                    <div class="wp-maintenance-settings-section-header"><h3 class="wp-maintenance-settings-section-title" id="module-import_export"><?php esc_html_e('SEO Title & Description', 'wp-maintenance'); ?></h3></div>
                    
                    <div class="wp-maintenance-setting-row">
                        <label for="wpmseo[seo_title]" class="wp-maintenance-setting-row-title"><?php esc_html_e('SEO Meta Title', 'wp-maintenance'); ?></label>
                        <input type="text" name="wpmseo[seo_title]" size="80%" value="<?php if( isset($paramSeo['seo_title']) && $paramSeo['seo_title']!='' ) { echo esc_html(stripslashes(trim($paramSeo['seo_title']))); } ?>">
                    </div>

                    <div class="wp-maintenance-setting-row">
                        <label for="wpmseo[seo_description]" class="wp-maintenance-setting-row-title"><?php esc_html_e('SEO Meta Description', 'wp-maintenance'); ?></label>
                        <input type="text" size="80%" name="wpmseo[seo_description]" value="<?php if( isset($paramSeo['seo_description']) && $paramSeo['seo_description']!='' ) { echo esc_html(stripslashes(trim($paramSeo['seo_description']))); } ?>">
                    </div>
                    
                    <p class="submit"><button type="submit" name="footer_submit" id="footer_submit" class="wp-maintenance-button wp-maintenance-button-primary"><?php esc_html_e('Save', 'wp-maintenance'); ?></button></p>

                </div>

                <!-- UPLOADER UN FAVICON -->
                <div class="wp-maintenance-module-options-block">
                    <div class="wp-maintenance-settings-section-header"><h3 class="wp-maintenance-settings-section-title" id="module-import_export"><?php esc_html_e('Add a favicon', 'wp-maintenance'); ?></h3></div>

                    <div class="wp-maintenance-setting-row">
                        <label for="wpmseo[seo_description]" class="wp-maintenance-setting-row-title"><?php esc_html_e('Enter a URL or upload an image', 'wp-maintenance'); ?></label>
                        <input id="upload_favicon" size="65%" name="wpmseo[favicon]" value="<?php if( isset($paramSeo['favicon']) && $paramSeo['favicon']!='' ) { echo esc_url($paramSeo['favicon']); } ?>" type="text" /> <a href="#" id="upload_favicon_button" class="button button-primary" style="padding-top: 0.1em;padding-bottom: 0.1em;margin-top: 1px;" OnClick="this.blur();"><span> <?php esc_html_e('Media Image Library', 'wp-maintenance'); ?> </span></a><br />
                        <small><?php esc_html_e('Favicons are displayed in a browser tab. Need Help <a href="https://realfavicongenerator.net/" target="_blank">creating a favicon</a>?', 'wp-maintenance'); ?></small>
                        <?php if( isset($paramSeo['favicon']) && $paramSeo['favicon']!='' ) { ?>
                            <div class="wp-maintenance-encadre">
                                <?php esc_html_e('You use this favicon:', 'wp-maintenance'); ?><br />
                                <img src="<?php echo esc_url($paramSeo['favicon']); ?>" width="100" /><br />
                            </div> 
                        <?php } ?>
                    </div>
                    <p class="submit"><button type="submit" name="footer_submit" id="footer_submit" class="wp-maintenance-button wp-maintenance-button-primary"><?php esc_html_e('Save', 'wp-maintenance'); ?></button></p>
                </div>
            </form>
        </div>

    </div>
    <?php echo wp_kses(wpm_footer(), wpm_autorizeHtml()); ?>
</div>
