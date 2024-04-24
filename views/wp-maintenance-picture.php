<?php

defined( 'ABSPATH' ) or die( 'Not allowed' );

$messageUpdate = 0;

global $_wp_admin_css_colors;

$admin_color = get_user_option( 'admin_color', get_current_user_id() );
$colors      = $_wp_admin_css_colors[$admin_color]->colors;

/* Update des paramètres */
if( isset($_POST['action']) && $_POST['action'] == 'update_pictures' && wp_verify_nonce($_POST['security-pictures'], 'valid-pictures') ) {

    if( isset($_POST['upload_picture']) && $_POST['upload_picture']!='' ) {
        $_POST["wpmpicture"]["image"] = sanitize_text_field($_POST['upload_picture']);
    }
    if( isset($_POST['remove_image']) && $_POST['remove_image']==1 ) {
        $_POST["wpmpicture"]["image"] = '';
    }
    if( isset($_POST['upload_b_image']) && $_POST['upload_b_image']!='' ) {
        $_POST["wpmpicture"]["b_image"] = sanitize_text_field($_POST['upload_b_image']);
    }
    if( isset($_POST['remove_b_image']) && $_POST['remove_b_image']==1 ) {
        $_POST["wpmpicture"]["b_image"] = '';
        $_POST["wpmpicture"]["b_enable_image"] = 0;
    }
   
    if( empty($_POST["wpmpicture"]["b_enable_image"]) ) { $_POST["wpmpicture"]["b_enable_image"] = 0; }
    if( empty($_POST["wpmpicture"]["b_fixed_image"]) ) { $_POST["wpmpicture"]["b_fixed_image"] = 0; }
    
    $updateSetting = wpm_update_settings( $_POST["wpmpicture"], 'wp_maintenance_settings_picture');
    if( $updateSetting == true ) { $messageUpdate = 1; }

}

// Récupère les paramètres sauvegardés
if(get_option('wp_maintenance_settings_picture')) { extract(get_option('wp_maintenance_settings_picture')); }
$paramsPicture = get_option('wp_maintenance_settings_picture');

// Récupère les paramètres sauvegardés (Besoin de récupérer des couleurs)
if(get_option('wp_maintenance_settings_colors')) { extract(get_option('wp_maintenance_settings_colors')); }
$paramsColors = get_option('wp_maintenance_settings_colors');

?>
<style>
    .ui-state-default { border:3px solid #ccc!important;}
</style>
<script type="text/javascript">
function toggleTable(texte) {
     var elem=document.getElementById(texte);
     var hide = elem.style.display == "none";
     if (hide) {
         elem.style.display="block";
    } 
    else {
       elem.style.display="none";
    }
}
</script>
<div class="wrap">
    
    <!-- HEADER -->
    <h2 class="headerpage"><?php esc_html_e('WP Maintenance - Settings', 'wp-maintenance'); ?> <sup>v.<?php echo esc_html(WPM_VERSION); ?></sup></h2>
    <?php if( isset($message) && $message == 1 ) { ?>
        <div id="message" class="updated fade"><p><strong><?php esc_html_e('Options saved.', 'wp-maintenance'); ?></strong></p></div>
    <?php } ?>
    <!-- END HEADER -->

    <div class="wp-maintenance-wrapper">

    <?php echo wp_kses(wpm_get_nav2(), wpm_autorizeHtml()); ?>
            
        <div class="wp-maintenance-tab-content wp-maintenance-tab-content-welcome" id="wp-maintenance-tab-content">

            <form method="post" action="" id="valide_settings" name="valide_settings">
                <input type="hidden" name="action" value="update_pictures" />
                <?php wp_nonce_field('valid-pictures', 'security-pictures'); ?>

                <!-- HEADER PICTURE -->
                <div class="wp-maintenance-module-options-block">

                    <div class="wp-maintenance-settings-section-header">
                        <h3 class="wp-maintenance-settings-section-title" id="module-import_export"><?php esc_html_e('Header picture', 'wp-maintenance'); ?></h3>
                    </div>

                    <h3><?php esc_html_e('Choice you picture for header page', 'wp-maintenance'); ?></h3>
                    <div class="wp-maintenance-setting-row">
                        <label for="wpmpicture[image]" class="wp-maintenance-setting-row-title"><?php esc_html_e('Enter a URL or upload an image', 'wp-maintenance'); ?></label>
                        <input id="settings_image"name="wpmpicture[image]" value="<?php if( isset($paramsPicture['image']) && $paramsPicture['image']!='' ) { echo esc_url($paramsPicture['image']); } ?>" type="hidden" />
                        <input id="upload_image" size="65%" name="upload_picture" value="" type="text" /> <a href="#" id="upload_image_button" class="wp-maintenance-button-primary" OnClick="this.blur();"><?php esc_html_e('Media Image Library', 'wp-maintenance'); ?></a><br />
                        <span class="description"><?php esc_html_e( 'URL path to image to replace default picture. (You can upload your image with the WordPress media uploader)', 'wp-maintenance' ); ?></span><br />
                        <label for="wpmpicture[image_width]" class="wp-maintenance-setting-row-title"><?php esc_html_e( 'Width:', 'wp-maintenance' ); ?></label> <input type="text" value="<?php if( isset($paramsPicture['image_width']) && $paramsPicture['image_width']!='' ) { echo esc_html($paramsPicture['image_width']); } ?>" size="4"   name="wpmpicture[image_width]" />px <br />
                        <label for="wpmpicture[image_height]" class="wp-maintenance-setting-row-title"><?php esc_html_e( 'Height:', 'wp-maintenance' ); ?></label> <input type="text" size="4" value="<?php if( isset($paramsPicture['image_height']) && $paramsPicture['image_height']!='' ) { echo esc_html($paramsPicture['image_height']); } ?>" name="wpmpicture[image_height]" />px<br />
                        <div class="wp-maintenance-encadre">
                            <?php if( isset($paramsPicture['image']) && $paramsPicture['image']!='' ) { ?>
                            <?php esc_html_e('You use this picture:', 'wp-maintenance'); ?><br /> <img src="<?php echo esc_url($paramsPicture['image']); ?>" width="250" id="image_visuel" style="padding:3px;" /><br /><input type="checkbox" name="remove_image" value="1" /> <?php esc_html_e('Remove', 'wp-maintenance'); ?>
                            <?php } ?>
                        </div>
                    </div>
                    
                    <p class="submit"><button type="submit" name="footer_submit" id="footer_submit" class="wp-maintenance-button wp-maintenance-button-primary"><?php esc_html_e('Save', 'wp-maintenance'); ?></button></p>
                </div>

                <!-- BACKGROUND PICTURE -->
                <div class="wp-maintenance-module-options-block">
                    <div class="wp-maintenance-settings-section-header">
                        <h3 class="wp-maintenance-settings-section-title" id="module-import_export"><?php esc_html_e('Background picture', 'wp-maintenance'); ?></h3>
                    </div>
                    <p>
                        <label class="wp-maintenance-container"><span class="wp-maintenance-label-text"><?php esc_html_e("Disable background or pattern picture", 'wp-maintenance'); ?></span>
                            <input type="radio" name="wpmpicture[b_enable_image]" value="0" <?php if( isset($paramsPicture['b_enable_image']) && $paramsPicture['b_enable_image']==0) { echo ' checked'; } ?>>
                            <span class="wp-maintenance-checkmark"></span>
                      </label>
                    </p>

                    <p>
                        <label class="wp-maintenance-container"><span class="wp-maintenance-label-text"><?php esc_html_e('Yes, activate picture background', 'wp-maintenance'); ?></span>
                            <input type="radio" name="wpmpicture[b_enable_image]" value="1" <?php if( isset($paramsPicture['b_enable_image']) && $paramsPicture['b_enable_image']==1) { echo ' checked'; } ?>>
                            <span class="wp-maintenance-checkmark"></span>
                      </label>
                    </p>
                    <div class="wp-maintenance-setting-row">
                        <input id="settings_image"name="wpmpicture[b_image]" value="<?php if( isset($paramsPicture['b_image']) && $paramsPicture['b_image']!='' ) { echo esc_url($paramsPicture['b_image']); } ?>" type="hidden" />
                        <label class="wp-maintenance-setting-row-title"><?php esc_html_e('Enter a URL or upload an image', 'wp-maintenance'); ?></label>
                        <input id="upload_b_image" size="65%" name="upload_b_image" value="" type="text" /> <a href="#" id="upload_b_image_button" class="wp-maintenance-button-primary" OnClick="this.blur();"><span> <?php esc_html_e('Media Image Library', 'wp-maintenance'); ?> </span></a>
                        
                        <?php if( isset($paramsPicture['b_image']) && $paramsPicture['b_image']!='' ) { ?>
                        <div style="padding-top:1em;text-align:center;"><?php esc_html_e('You use this background picture:', 'wp-maintenance'); ?></div>
                        <div class="wp-maintenance-encadre" style="height:200px;margin-top: 0em;background:url('<?php echo esc_url($paramsPicture['b_image']) ?>');top center;background-size: cover;-webkit-background-size: cover;-moz-background-size: cover;-o-background-size: cover;background-position: center;background-color: rgba(0,0,0,<?php echo esc_html($paramsPicture['b_opacity_image']); ?>);">
                            
                            
                        </div><div style="text-align:center;"><label class="wpm-container"><input type="checkbox" name="remove_b_image" value="1" /> <?php esc_html_e('Remove', 'wp-maintenance'); ?><span class="wpm-checkmark"></span></label></div>
                        <?php } ?>
                        
                    </div>

                    <div class="wp-maintenance-setting-row">
                        <label for="wpmpicture[image]" class="wp-maintenance-setting-row-title"><?php esc_html_e('Background picture options', 'wp-maintenance'); ?></label>
                        <select name="wpmpicture[b_repeat_image]" style="border: 2px solid #ECF0F1;font-size: 13px;padding: 7px 25px 7px 10px;height: auto;">
                            <option value="repeat"<?php if( (isset($paramsPicture['b_repeat_image']) && $paramsPicture['b_repeat_image']=='repeat') or empty($paramsPicture['b_repeat_image']) ) { echo ' selected'; } ?>>repeat</option>
                            <option value="no-repeat"<?php if( isset($paramsPicture['b_repeat_image']) && $paramsPicture['b_repeat_image']=='no-repeat') { echo ' selected'; } ?>>no-repeat</option>
                            <option value="repeat-x"<?php if( isset($paramsPicture['b_repeat_image']) && $paramsPicture['b_repeat_image']=='repeat-x') { echo ' selected'; } ?>>repeat-x</option>
                            <option value="repeat-y"<?php if( isset($paramsPicture['b_repeat_image']) && $paramsPicture['b_repeat_image']=='repeat-y') { echo ' selected'; } ?>>repeat-y</option>
                        </select>
                        
                        <label for="wpmpicture[image]" class="wp-maintenance-setting-row-title"><?php esc_html_e('Background Opacity', 'wp-maintenance'); ?></label>
                        <input id="fontSize" name="wpmpicture[b_opacity_image]" value="<?php if( isset($paramsPicture['b_opacity_image']) ) { echo esc_html($paramsPicture['b_opacity_image']); } else { echo '0.2'; } ?>" size="4" readonly="readonly" style="border: 2px solid #ECF0F1;font-size: 13px;padding: 7px 10px;height: auto;"><br /><br /><div id="opacity_slider" style="border: 2px solid #ECF0F1;font-size: 13px;padding: 7px 10px;height: auto;"></div>
                    </div>

                    <p>
                        <label class="wp-maintenance-container"><span class="wp-maintenance-label-text"><?php esc_html_e('Fix the background picture', 'wp-maintenance'); ?></span>
                            <input type="checkbox" name="wpmpicture[b_fixed_image]" value="1" <?php if( isset($paramsPicture['b_fixed_image']) && $paramsPicture['b_fixed_image']==1) { echo ' checked'; } ?>>
                            <span class="wp-maintenance-checkmark"></span>
                        </label>
                    </p>

                    <p class="submit"><button type="submit" name="footer_submit" id="footer_submit" class="wp-maintenance-button wp-maintenance-button-primary"><?php esc_html_e('Save', 'wp-maintenance'); ?></button></p>
                </div>

                <!-- BACKGROUND PATTERN -->
                <div class="wp-maintenance-module-options-block">
                    <div class="wp-maintenance-settings-section-header">
                        <h3 class="wp-maintenance-settings-section-title" id="module-import_export"><?php esc_html_e('Pattern picture', 'wp-maintenance'); ?></h3>
                    </div>

                    <p>
                        <label class="wp-maintenance-container"><span class="wp-maintenance-label-text"><?php esc_html_e('Yes, activate pattern background', 'wp-maintenance'); ?></span>
                            <input type="radio" name="wpmpicture[b_enable_image]" value="2" <?php if( isset($paramsPicture['b_enable_image']) && $paramsPicture['b_enable_image']==2) { echo ' checked'; } ?>>
                            <span class="wp-maintenance-checkmark"></span>
                      </label>
                    </p>

                    <!-- CHOIX PATTERN -->  
                    <div class="wp-maintenance-setting-row">
                        <label for="wpmpicture[image]" class="wp-maintenance-setting-row-title"><?php esc_html_e('Choose a pattern', 'wp-maintenance'); ?></label>
                        <ul id="pattern">
                            <li>
                                <div style="width:50px;height:50px;border:1px solid #333;background-color:#ffffff;font-size:0.8em;margin-bottom:5px;"><?php esc_html_e('NO PATTERN', 'wp-maintenance'); ?></div>
                                <label class="wpm-container"><input type="radio" value="0" <?php if( empty($paramsPicture['b_pattern']) or $paramsPicture['b_pattern']==0) { echo 'checked'; } ?> name="wpmpicture[b_pattern]" /><span class="wpm-checkmark"></span></label>
                               
                            </li>
                            <?php for ($p = 1; $p <= 12; $p++) { ?>
                                <li>
                                    <div style="width:50px;height:50px;border:2px solid #ECF0F1;background:url('<?php echo esc_url(plugins_url( '../images/pattern'.$p.'.png', __FILE__ )); ?>');margin-bottom:5px;"></div>
                                    <label for="b_pattern_<?php echo esc_html($p); ?>" class="wpm-container"><input type="radio" value="<?php echo esc_html($p); ?>" <?php if( isset($paramsPicture['b_pattern']) && $paramsPicture['b_pattern']==$p) { echo 'checked'; } ?> id="b_pattern_<?php echo esc_html($p); ?>" name="wpmpicture[b_pattern]" /><span class="wpm-checkmark"></span></label>
                                </li>
                            <?php } ?>
                        </ul>
                        <?php if( isset($paramsPicture['b_pattern']) && $paramsPicture['b_pattern']>0) { ?>
                            <div class="wp-maintenance-encadre" style="background: url('<?php echo esc_url(plugins_url( '../images/pattern'.$paramsPicture['b_pattern'].'.png', __FILE__ )); ?>');<?php if( isset($paramsColors['color_bg']) && $paramsColors['color_bg']!='' ) { echo 'background-color:'.esc_html($paramsColors['color_bg']).';'; } ?>height:160px;">
                                &nbsp;<?php esc_html_e('You use this pattern', 'wp-maintenance'); ?>&nbsp;
                            </div>
                      <?php } ?>

                    </div>

                    <p class="submit"><button type="submit" name="footer_submit" id="footer_submit" class="wp-maintenance-button wp-maintenance-button-primary"><?php esc_html_e('Save', 'wp-maintenance'); ?></button></p>
                </div>

            </form>
        </div>

    </div>
    
    <?php echo wp_kses(wpm_footer(), wpm_autorizeHtml()); ?>

</div><!-- END WRAP -->
<script>
jQuery(document).ready(function() {
    jQuery( "#opacity_slider" ).slider( {
        disabled: false,
        min: 0,
        max: 1,
        orientation: "horizontal",
        range: false,
        step: 0.1,
        value: <?php if( isset($paramsPicture['b_opacity_image']) ) { echo esc_html($paramsPicture['b_opacity_image']); } else { echo '0.2'; } ?>,
        animate:"slow",
        slide: function( event, ui ) {
            jQuery( "#fontSize" ).val( ui.value );
        }
    } );
});
</script>