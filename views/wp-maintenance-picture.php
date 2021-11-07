<?php

defined( 'ABSPATH' ) or die( 'Not allowed' );

$messageUpdate = 0;

global $_wp_admin_css_colors;

$admin_color = get_user_option( 'admin_color', get_current_user_id() );
$colors      = $_wp_admin_css_colors[$admin_color]->colors;

/* Update des paramètres */
if( isset($_POST['action']) && $_POST['action'] == 'update_pictures' && wp_verify_nonce($_POST['security-pictures'], 'valid-pictures') ) {

    if( isset($_POST['upload_picture']) && $_POST['upload_picture']!='' ) {
        $_POST["wp_maintenance_settings"]["image"] = sanitize_text_field($_POST['upload_picture']);
    }
    if( isset($_POST['remove_image']) && $_POST['remove_image']==1 ) {
        $_POST["wp_maintenance_settings"]["image"] = '';
    }
    if( isset($_POST['upload_b_image']) && $_POST['upload_b_image']!='' ) {
        $_POST["wp_maintenance_settings"]["b_image"] = sanitize_text_field($_POST['upload_b_image']);
    }
    if( isset($_POST['remove_b_image']) && $_POST['remove_b_image']==1 ) {
        $_POST["wp_maintenance_settings"]["b_image"] = '';
        $_POST["wp_maintenance_settings"]["b_enable_image"] = 0;
    }

    if( isset($_POST["wpm_maintenance_detete"]) && is_array($_POST["wpm_maintenance_detete"]) ) {
        foreach($_POST["wpm_maintenance_detete"] as $delSlideId=>$delSlideTrue) {
            if ( array_key_exists($delSlideId, sanitize_text_field($_POST["wp_maintenance_slider"]["slider_image"]) ) ) {
                unset($_POST["wp_maintenance_slider"]["slider_image"][$delSlideId]);
                unset($_POST["wp_maintenance_slider"]["slider_text"][$delSlideId]);
                unset($_POST["wp_maintenance_slider"]["slider_link"][$delSlideId]);
            }
        }
    }
    
    if( empty($_POST["wp_maintenance_settings"]["b_enable_image"]) ) { $_POST["wp_maintenance_settings"]["b_enable_image"] = 0; }
    if( empty($_POST["wp_maintenance_settings"]["b_fixed_image"]) ) { $_POST["wp_maintenance_settings"]["b_fixed_image"] = 0; }
    if( empty($_POST["wp_maintenance_settings"]["enable_slider"]) ) { $_POST["wp_maintenance_settings"]["enable_slider"] = 0; }
    
    $options_saved = wpm_update_settings($_POST["wp_maintenance_settings"]);
    update_option('wp_maintenance_slider', $_POST["wp_maintenance_slider"]);
    update_option('wp_maintenance_slider_options', $_POST["wp_maintenance_slider_options"]);

    $messageUpdate = 1;
}

// Récupère les paramètres sauvegardés
if(get_option('wp_maintenance_settings')) { extract(get_option('wp_maintenance_settings')); }
$paramMMode = get_option('wp_maintenance_settings');

if(get_option('wp_maintenance_slider')) { extract(get_option('wp_maintenance_slider')); }
$paramSlider = get_option('wp_maintenance_slider');

if(get_option('wp_maintenance_slider_options')) { extract(get_option('wp_maintenance_slider_options')); }
$paramSliderOptions = get_option('wp_maintenance_slider_options');

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
    <?php echo wpm_get_header( $messageUpdate ) ?>
    <!-- END HEADER -->

    <div class="wp-maintenance-wrapper wp-maintenance-flex wp-maintenance-flex-top">
        
        <?php echo wpm_get_nav(); ?>
            
        <div class="wp-maintenance-tab-content wp-maintenance-tab-content-welcome" id="wp-maintenance-tab-content">
            
            <div class="wp-maintenance-tab-content-header"><i class="dashicons dashicons-format-gallery" style="margin-right: 10px;height:50px;width:50px;font-size:50px;padding: 8px 8px 14px 10px;border-radius: 5px;display: inline;float:left;"></i>  <h2 class="wp-maintenance-tc-title"><?php _e('Picture Options', 'wp-maintenance'); ?></h2></div>

            <div class="wp-maintenance-module-options-block" id="block-advanced_options" data-module="welcome">
                
                <form method="post" action="" id="valide_settings" name="valide_settings">
                    <input type="hidden" name="action" value="update_pictures" />
                    <?php wp_nonce_field('valid-pictures', 'security-pictures'); ?>

                    <!-- HEADER PICTURE -->
                    <div class="wp-maintenance-settings-section-header">
                        <h3 class="wp-maintenance-settings-section-title" id="module-import_export"><?php _e('Header picture', 'wp-maintenance'); ?></h3>
                    </div>

                    <h3><?php _e('Choice you picture for header page', 'wp-maintenance'); ?></h3>
                    <div class="wp-maintenance-setting-row">
                        <label for="wp_maintenance_settings[image]" class="wp-maintenance-setting-row-title"><?php _e('Enter a URL or upload an image', 'wp-maintenance'); ?></label>
                        <input id="settings_image"name="wp_maintenance_settings[image]" value="<?php if( isset($paramMMode['image']) && $paramMMode['image']!='' ) { echo esc_url($paramMMode['image']); } ?>" type="hidden" />
                        <input id="upload_image" size="65%" name="upload_picture" value="" type="text" /> <a href="#" id="upload_image_button" class="wp-maintenance-button-primary" OnClick="this.blur();"><?php _e('Media Image Library', 'wp-maintenance'); ?></a><br />
                        <span class="description"><?php _e( 'URL path to image to replace default picture. (You can upload your image with the WordPress media uploader)', 'wp-maintenance' ); ?></span><br />
                        <label for="wp_maintenance_settings[image_width]" class="wp-maintenance-setting-row-title"><?php _e( 'Width:', 'wp-maintenance' ); ?></label> <input type="text" value="<?php if( isset($paramMMode['image_width']) && $paramMMode['image_width']!='' ) { echo esc_html($paramMMode['image_width']); } ?>" size="4"   name="wp_maintenance_settings[image_width]" />px <br />
                        <label for="wp_maintenance_settings[image_height]" class="wp-maintenance-setting-row-title"><?php _e( 'Height:', 'wp-maintenance' ); ?></label> <input type="text" size="4" value="<?php if( isset($paramMMode['image_height']) && $paramMMode['image_height']!='' ) { echo esc_html($paramMMode['image_height']); } ?>" name="wp_maintenance_settings[image_height]" />px<br />
                        <div class="wp-maintenance-encadre">
                            <?php if( isset($paramMMode['image']) && $paramMMode['image']!='' ) { ?>
                            <?php _e('You use this picture:', 'wp-maintenance'); ?><br /> <img src="<?php echo $paramMMode['image']; ?>" width="250" id="image_visuel" style="padding:3px;" /><br /><input type="checkbox" name="remove_image" value="1" /> <?php _e('Remove', 'wp-maintenance'); ?>
                            <?php } ?>
                        </div>
                    </div>
                    
                    <p class="submit"><button type="submit" name="footer_submit" id="footer_submit" class="wp-maintenance-button wp-maintenance-button-primary"><?php _e('Save', 'wp-maintenance'); ?></button></p>

                    <!-- BACKGROUND PICTURE -->
                    <div class="wp-maintenance-settings-section-header">
                        <h3 class="wp-maintenance-settings-section-title" id="module-import_export"><?php _e('Background picture', 'wp-maintenance'); ?></h3>
                    </div>
                    <p class="wp-maintenance-fieldset-item ">
                        <label class="wp-maintenance-container"><span class="wp-maintenance-label-text"><?php _e("Disable background or pattern picture", 'wp-maintenance'); ?></span>
                            <input type="radio" name="wp_maintenance_settings[b_enable_image]" value="0" <?php if( isset($paramMMode['b_enable_image']) && $paramMMode['b_enable_image']==0) { echo ' checked'; } ?>>
                            <span class="wp-maintenance-checkmark"></span>
                      </label>
                    </p>

                    <p class="wp-maintenance-fieldset-item ">
                        <label class="wp-maintenance-container"><span class="wp-maintenance-label-text"><?php _e('Yes, activate picture background', 'wp-maintenance'); ?></span>
                            <input type="radio" name="wp_maintenance_settings[b_enable_image]" value="1" <?php if( isset($paramMMode['b_enable_image']) && $paramMMode['b_enable_image']==1) { echo ' checked'; } ?>>
                            <span class="wp-maintenance-checkmark"></span>
                      </label>
                    </p>
                    <div class="wp-maintenance-setting-row">
                        <input id="settings_image"name="wp_maintenance_settings[b_image]" value="<?php if( isset($paramMMode['b_image']) && $paramMMode['b_image']!='' ) { echo esc_url($paramMMode['b_image']); } ?>" type="hidden" />
                        <label for="wp_maintenance_settings[color_txt]" class="wp-maintenance-setting-row-title"><?php _e('Enter a URL or upload an image', 'wp-maintenance'); ?></label>
                        <input id="upload_b_image" size="65%" name="upload_b_image" value="" type="text" /> <a href="#" id="upload_b_image_button" class="wp-maintenance-button-primary" OnClick="this.blur();"><span> <?php _e('Media Image Library', 'wp-maintenance'); ?> </span></a>
                        
                        <?php if( isset($paramMMode['b_image']) && $paramMMode['b_image']!='' ) { ?>
                        <div style="padding-top:1em;text-align:center;"><?php _e('You use this background picture:', 'wp-maintenance'); ?></div>
                        <div class="wp-maintenance-encadre" style="height:200px;margin-top: 0em;background:url('<?php echo esc_url($paramMMode['b_image']) ?>');top center;background-size: cover;-webkit-background-size: cover;-moz-background-size: cover;-o-background-size: cover;background-position: center;background-color: rgba(0,0,0,<?php echo esc_html($paramMMode['b_opacity_image']); ?>);">
                            
                            
                        </div><div style="text-align:center;"><label class="wpm-container"><input type="checkbox" name="remove_b_image" value="1" /> <?php _e('Remove', 'wp-maintenance'); ?><span class="wpm-checkmark"></span></label></div>
                        <?php } ?>
                        
                    </div>

                    <div class="wp-maintenance-setting-row">
                        <label for="wp_maintenance_settings[image]" class="wp-maintenance-setting-row-title"><?php _e('Background picture options', 'wp-maintenance'); ?></label>
                        <select name="wp_maintenance_settings[b_repeat_image]" style="border: 2px solid #ECF0F1;font-size: 13px;padding: 7px 25px 7px 10px;height: auto;">
                            <option value="repeat"<?php if( (isset($paramMMode['b_repeat_image']) && $paramMMode['b_repeat_image']=='repeat') or empty($paramMMode['b_repeat_image']) ) { echo ' selected'; } ?>>repeat</option>
                            <option value="no-repeat"<?php if( isset($paramMMode['b_repeat_image']) && $paramMMode['b_repeat_image']=='no-repeat') { echo ' selected'; } ?>>no-repeat</option>
                            <option value="repeat-x"<?php if( isset($paramMMode['b_repeat_image']) && $paramMMode['b_repeat_image']=='repeat-x') { echo ' selected'; } ?>>repeat-x</option>
                            <option value="repeat-y"<?php if( isset($paramMMode['b_repeat_image']) && $paramMMode['b_repeat_image']=='repeat-y') { echo ' selected'; } ?>>repeat-y</option>
                        </select>
                        
                        <label for="wp_maintenance_settings[image]" class="wp-maintenance-setting-row-title"><?php _e('Background Opacity', 'wp-maintenance'); ?></label>
                        <input id="fontSize" name="wp_maintenance_settings[b_opacity_image]" value="<?php if( isset($paramMMode['b_opacity_image']) ) { echo $paramMMode['b_opacity_image']; } else { echo '0.2'; } ?>" size="4" readonly="readonly" style="border: 2px solid #ECF0F1;font-size: 13px;padding: 7px 10px;height: auto;"><br /><br /><div id="slider" style="border: 2px solid #ECF0F1;font-size: 13px;padding: 7px 10px;height: auto;"></div>
                    </div>

                    <p class="wp-maintenance-fieldset-item ">
                        <label class="wp-maintenance-container"><span class="wp-maintenance-label-text"><?php _e('Fix the background picture', 'wp-maintenance'); ?></span>
                            <input type="checkbox" name="wp_maintenance_settings[b_fixed_image]" value="1" <?php if( isset($paramMMode['b_fixed_image']) && $paramMMode['b_fixed_image']==1) { echo ' checked'; } ?>>
                            <span class="wp-maintenance-checkmark"></span>
                        </label>
                    </p>

                    <p class="submit"><button type="submit" name="footer_submit" id="footer_submit" class="wp-maintenance-button wp-maintenance-button-primary"><?php _e('Save', 'wp-maintenance'); ?></button></p>

                    <!-- BACKGROUND PATTERN -->
                    <div class="wp-maintenance-settings-section-header">
                        <h3 class="wp-maintenance-settings-section-title" id="module-import_export"><?php _e('Pattern picture', 'wp-maintenance'); ?></h3>
                    </div>

                    <p class="wp-maintenance-fieldset-item ">
                        <label class="wp-maintenance-container"><span class="wp-maintenance-label-text"><?php _e('Yes, activate pattern background', 'wp-maintenance'); ?></span>
                            <input type="radio" name="wp_maintenance_settings[b_enable_image]" value="2" <?php if( isset($paramMMode['b_enable_image']) && $paramMMode['b_enable_image']==2) { echo ' checked'; } ?>>
                            <span class="wp-maintenance-checkmark"></span>
                      </label>
                    </p>

                    <!-- CHOIX PATTERN -->  
                    <div class="wp-maintenance-setting-row">
                        <label for="wp_maintenance_settings[image]" class="wp-maintenance-setting-row-title"><?php _e('Choose a pattern', 'wp-maintenance'); ?></label>
                        <ul id="pattern">
                            <li>
                                <div style="width:50px;height:50px;border:1px solid #333;background-color:#ffffff;font-size:0.8em;margin-bottom:5px;"><?php _e('NO PATTERN', 'wp-maintenance'); ?></div>
                                <label class="wpm-container"><input type="radio" value="0" <?php if( empty($paramMMode['b_pattern']) or $paramMMode['b_pattern']==0) { echo 'checked'; } ?> name="wp_maintenance_settings[b_pattern]" /><span class="wpm-checkmark"></span></label>
                               
                            </li>
                            <?php for ($p = 1; $p <= 12; $p++) { ?>
                                <li>
                                    <div style="width:50px;height:50px;border:2px solid #ECF0F1;background:url('<?php echo WP_PLUGIN_URL ?>/wp-maintenance/images/pattern<?php echo $p ?>.png');margin-bottom:5px;"></div>
                                    <label for="b_pattern_<?php echo $p; ?>" class="wpm-container"><input type="radio" value="<?php echo $p; ?>" <?php if( isset($paramMMode['b_pattern']) && $paramMMode['b_pattern']==$p) { echo 'checked'; } ?> id="b_pattern_<?php echo $p; ?>" name="wp_maintenance_settings[b_pattern]" /><span class="wpm-checkmark"></span></label>
                                </li>
                            <?php } ?>
                        </ul>
                        <?php if( isset($paramMMode['b_pattern']) && $paramMMode['b_pattern']>0) { ?>
                            <div class="wp-maintenance-encadre" style="background: url('<?php echo esc_url(WP_PLUGIN_URL.'/wp-maintenance/images/pattern'.$paramMMode['b_pattern'].'.png'); ?>');<?php if( isset($paramMMode['color_bg']) && $paramMMode['color_bg']!='' ) { echo 'background-color:'.$paramMMode['color_bg'].';'; } ?>height:160px;">
                                &nbsp;<?php _e('You use this pattern', 'wp-maintenance'); ?>&nbsp;
                            </div>
                      <?php } ?>

                    </div>

                    <p class="submit"><button type="submit" name="footer_submit" id="footer_submit" class="wp-maintenance-button wp-maintenance-button-primary"><?php _e('Save', 'wp-maintenance'); ?></button></p>

                    <!-- ENABLE SLIDER -->
                    <div class="wp-maintenance-settings-section-header">
                        <h3 class="wp-maintenance-settings-section-title" id="module-import_export"><?php _e('Slider', 'wp-maintenance'); ?></h3>
                    </div>

                    <p class="wp-maintenance-fieldset-item ">
                        <label class="wp-maintenance-container"><span class="wp-maintenance-label-text"><?php _e('Yes, enable Slider', 'wp-maintenance'); ?></span>
                            <input type="checkbox" name="wp_maintenance_settings[enable_slider]" value="1" <?php if( isset($paramMMode['enable_slider']) && $paramMMode['enable_slider']==1) { echo ' checked'; } ?>>
                            <span class="wp-maintenance-checkmark"></span>
                        </label>
                    </p>
                
                    <h3><?php _e('Slider options', 'wp-maintenance'); ?></h3>
                    <div class="wp-maintenance-setting-row">
                        <label for="wp_maintenance_settings[image]" class="wp-maintenance-setting-row-title"><?php _e('Slider options', 'wp-maintenance'); ?></label>
                        <?php

                            if( $paramSlider!==null ) {

                                if( $paramSlider['slider_image'] ) {
                                    $lastKeySlide = key($paramSlider['slider_image']);
                                    $countSlide = ( $lastKeySlide + 1 );
                                } else {
                                    $countSlide = 1;
                                }
                        ?>
                        <?php _e('Speed:', 'wp-maintenance'); ?> <input type="text" name="wp_maintenance_slider_options[slider_speed]" size="4" value="<?php if( isset($paramSliderOptions['slider_speed']) && $paramSliderOptions['slider_speed'] !='') { echo esc_html($paramSliderOptions['slider_speed']); } else { echo 500; } ?>" />ms<br />
                        <?php _e('Width:', 'wp-maintenance'); ?> <input type="text" name="wp_maintenance_slider_options[slider_width]" size="3" value="<?php if( isset($paramSliderOptions['slider_width']) && $paramSliderOptions['slider_width'] !='') { echo esc_html($paramSliderOptions['slider_width']); } else { echo 50; } ?>" />%
                    </div>

                    <div class="wp-maintenance-setting-row">
                        <label for="wp_maintenance_settings[image]" class="wp-maintenance-setting-row-title"><?php _e('Display Auto Slider', 'wp-maintenance'); ?></label>
                        <label class="wpm-container"><input type="radio" name="wp_maintenance_slider_options[slider_auto]" value="true" <?php if( isset($paramSliderOptions['slider_auto']) && $paramSliderOptions['slider_auto']=='true') { echo ' checked'; } ?>/> <?php _e('Yes', 'wp-maintenance'); ?>
                        <span class="wpm-checkmark"></span></label>
                        <label class="wpm-container"><input type="radio" name="wp_maintenance_slider_options[slider_auto]" value="false" <?php if( empty($paramSliderOptions['slider_auto']) || (isset($paramSliderOptions['slider_auto']) && $paramSliderOptions['slider_auto']=='false')) { echo ' checked'; } ?> /> <?php _e('No', 'wp-maintenance'); ?>
                        <span class="wpm-checkmark"></span></label>
                    </div>

                    <div class="wp-maintenance-setting-row">
                        <label for="wp_maintenance_settings[image]" class="wp-maintenance-setting-row-title"><?php _e('Display button navigation', 'wp-maintenance'); ?></label>
                        <label class="wpm-container"><input type="radio" name="wp_maintenance_slider_options[slider_nav]" value="true" <?php if( isset($paramSliderOptions['slider_nav']) && $paramSliderOptions['slider_nav']=='true') { echo ' checked'; } ?>/> <?php _e('Yes', 'wp-maintenance'); ?><span class="wpm-checkmark"></span></label>
                        <label class="wpm-container"><input type="radio" name="wp_maintenance_slider_options[slider_nav]" value="false" <?php if( empty($paramSliderOptions['slider_nav']) || (isset($paramSliderOptions['slider_nav']) && $paramSliderOptions['slider_nav']=='false')) { echo ' checked'; } ?> /> <?php _e('No', 'wp-maintenance'); ?><span class="wpm-checkmark"></span></label>
                    </div>
                    <div class="wp-maintenance-setting-row">
                        <label for="wp_maintenance_settings[image]" class="wp-maintenance-setting-row-title"><?php _e('Position', 'wp-maintenance'); ?></label>
                        <select name="wp_maintenance_slider_options[slider_position]" style="border: 2px solid #ECF0F1;font-size: 13px;padding: 7px 25px 7px 10px;height: auto;">
                            <option value="abovelogo" <?php if( isset($paramSliderOptions['slider_position']) && $paramSliderOptions['slider_position']=='abovelogo' ) { echo 'selected'; } ?>><?php _e('Above logo', 'wp-maintenance'); ?></option>
                            <option value="belowlogo" <?php if( isset($paramSliderOptions['slider_position']) && $paramSliderOptions['slider_position']=='belowlogo' ) { echo 'selected'; } ?>><?php _e('Below logo', 'wp-maintenance'); ?></option>
                            <option value="belowtext" <?php if( ( isset($paramSliderOptions['slider_position']) && $paramSliderOptions['slider_position']=='belowtext' ) || empty($paramSliderOptions['slider_position']) ) { echo 'selected'; } ?>><?php _e('Below title & text', 'wp-maintenance'); ?></option>
                        </select>
                    </div>

                    <div class="wp-maintenance-setting-row">
                    <label for="wp_maintenance_settings[image]" class="wp-maintenance-setting-row-title"><?php _e('Choose picture', 'wp-maintenance'); ?></label>
                    <input id="upload_slider_image" size="65%" name="wp_maintenance_slider[slider_image][<?php echo $countSlide; ?>][image]" value="" type="text" /> <a href="#" id="upload_slider_image_button" class="wp-maintenance-button-primary" OnClick="this.blur();"><?php _e('Media Image Library', 'wp-maintenance'); ?></a><br /><br />
                    </div>

                    <div style="width:100%">
                        <?php
                            if( !empty($paramSlider['slider_image']) ) {
                                foreach($paramSlider['slider_image'] as $numSlide=>$slide) {

                                    if( $paramSlider['slider_image'][$numSlide]['image'] != '' ) {

                                        $slideImg = '';
                                        if( isset($paramSlider['slider_image'][$numSlide]['image']) ) {
                                            $slideImg = esc_url($paramSlider['slider_image'][$numSlide]['image']);
                                        }
                                        $slideText = '';
                                        if( isset($paramSlider['slider_image'][$numSlide]['text']) ) {
                                            $slideText = esc_html(stripslashes($paramSlider['slider_image'][$numSlide]['text']));
                                        }
                                        $slideLink = '';
                                        if( isset($paramSlider['slider_image'][$numSlide]['link']) ) {
                                            $slideLink = esc_url($paramSlider['slider_image'][$numSlide]['link']);
                                        }
                                        echo '<div style="float:left;width:32%;border: 1px solid #ececec;padding:0.8em;margin-right:1%;margin-bottom:1%">';

                                        echo '<div style="width:100%;text-align:center;">';
                                        echo '<img src="'.$slideImg.'" width="80%" />';
                                        echo '</div>';

                                        echo '<div style="margin-left: auto;margin-right: auto;width: 80%;">';
                                        echo '<input type="hidden" name="wp_maintenance_slider[slider_image]['.$numSlide.'][image]" value="'.$slideImg.'" />';
                                        echo __('Text:', 'wp-maintenance').'<br /> <input type="text" name="wp_maintenance_slider[slider_image]['.$numSlide.'][text]" size="50%" value="'.$slideText.'" /><br />';
                                        echo __('Link:', 'wp-maintenance').'<br /> <input type="text" name="wp_maintenance_slider[slider_image]['.$numSlide.'][link]" size="50%" value="'.$slideLink.'" />';
                                        
                                        echo '<div style="text-align:center;"><small>'.__('Delete this slide', 'wp-maintenance').'</small><br /><label class="wpm-container"><input type="checkbox" name="wpm_maintenance_detete['.$numSlide.']" value="true" /><span class="wpm-checkmark"></span></label></div>';
                                        echo '</div>';
                                        echo '</div>';

                                    }

                                }
                            }
                        }
                        ?>
                        <div class="clear"></div>
                    </div>

                <p class="submit"><button type="submit" name="footer_submit" id="footer_submit" class="wp-maintenance-button wp-maintenance-button-primary"><?php _e('Save', 'wp-maintenance'); ?></button></p>

                </form>

            </div>
        </div>

    </div>
    
    <?php echo wpm_footer(); ?>

</div><!-- END WRAP -->
<script>
jQuery(document).ready(function() {
    jQuery( "#slider" ).slider( {
        disabled: false,
        min: 0,
        max: 1,
        orientation: "horizontal",
        range: false,
        step: 0.1,
        value: <?php if( isset($paramMMode['b_opacity_image']) ) { echo $paramMMode['b_opacity_image']; } else { echo '0.2'; } ?>,
        animate:"slow",
        slide: function( event, ui ) {
            jQuery( "#fontSize" ).val( ui.value );
        }
    } );
});
</script>