<?php

defined( 'ABSPATH' ) or die( 'Not allowed' );

$messageUpdate = 0;
/* Update des paramètres */
if( isset($_POST['action']) && $_POST['action'] == 'update_colors' && wp_verify_nonce($_POST['security-colors'], 'valid-colors') ) {

    if( empty($_POST["wp_maintenance_settings"]["container_active"]) ) { $_POST["wp_maintenance_settings"]["container_active"] = 0; }
    $options_saved = wpm_update_settings($_POST["wp_maintenance_settings"]);
    //var_dump($_POST["wp_maintenance_settings"]);
    //exit();
    $options_saved = true;
    $messageUpdate = 1;
}

// Récupère les paramètres sauvegardés
if(get_option('wp_maintenance_settings')) { extract(get_option('wp_maintenance_settings')); }
$paramMMode = get_option('wp_maintenance_settings');

?>
<script type="text/javascript">
    jQuery(document).ready(function() {
        jQuery('#select_font_title').fontselect();
        jQuery('#select_font_text').fontselect();        
        jQuery('#select_font_text_cpt').fontselect();
        jQuery('#select_font_text_bottom').fontselect();
        jQuery('#select_font_text_newletter').fontselect();
    });
    jQuery(document).ready(function() {

        jQuery('input.selectfont').fontselect({
          placeholder: 'Select a font',
        });
        
    });
</script>
<div class="wrap">
    
    <!-- HEADER -->
    <?php echo wpm_get_header( $messageUpdate ) ?>
    <!-- END HEADER -->

    <div class="wp-maintenance-wrapper">
        
        <?php echo wpm_get_nav2(); ?>
        
        <div class="wp-maintenance-tab-content wp-maintenance-tab-content-welcome" id="wp-maintenance-tab-content">
            
            <form method="post" action="" id="valide_settings" name="valide_settings">
                <input type="hidden" name="action" value="update_colors" />
                <?php wp_nonce_field('valid-colors', 'security-colors'); ?>
                
                <!-- COULEUR DU FOND DE PAGE -->
                <div class="wp-maintenance-module-options-block">

                    <div class="wp-maintenance-settings-section-header">
                        <h3 class="wp-maintenance-settings-section-title" id="module-import_export"><?php _e('Choice general colors', 'wp-maintenance'); ?></h3>
                    </div>
                    <div class="wp-maintenance-setting-row">
                        <label for="wp_maintenance_settings[color_bg]" class="wp-maintenance-setting-row-title"><?php _e('Background page color', 'wp-maintenance'); ?></label>
                        <input type="text" value="<?php if( isset($paramMMode['color_bg']) && $paramMMode['color_bg']!='' ) { echo $paramMMode['color_bg']; } ?>" name="wp_maintenance_settings[color_bg]" class="wpm-color-field" data-default-color="#f1f1f1" />
                        <label for="wp_maintenance_settings[color_bg_header]" class="wp-maintenance-setting-row-title"><?php _e('Header color', 'wp-maintenance'); ?></label>
                        <input type="text" value="<?php if( isset($paramMMode['color_bg_header']) && $paramMMode['color_bg_header']!='' ) { echo $paramMMode['color_bg_header']; } ?>" name="wp_maintenance_settings[color_bg_header]" class="wpm-color-field" data-default-color="#ffffff" />
                    </div>

                    <p class="submit"><button type="submit" name="footer_submit" id="footer_submit" class="wp-maintenance-button wp-maintenance-button-primary"><?php _e('Save', 'wp-maintenance'); ?></button></p> 
                </div>

                <!-- POLICE DU TITRE -->
                <div class="wp-maintenance-module-options-block">
                    
                    <div class="wp-maintenance-settings-section-header">
                        <h3 class="wp-maintenance-settings-section-title" id="module-import_export"><?php _e('Title Settings', 'wp-maintenance'); ?></h3>
                    </div>

                    <h3><?php _e('Choice title font and color', 'wp-maintenance'); ?></h3>
                    <em><?php _e('Set the color and font of the title', 'wp-maintenance'); ?></em> <br /><br />

                    <div class="wp-maintenance-setting-row">
                        <label for="wp_maintenance_settings[color_title]" class="wp-maintenance-setting-row-title"><?php _e('Choose font color', 'wp-maintenance'); ?></label>
                        <input type="text" value="<?php if( isset($paramMMode['color_title']) && $paramMMode['color_title']!='' ) { echo $paramMMode['color_title']; } else { echo '#333333'; } ?>" name="wp_maintenance_settings[color_title]" class="wpm-color-field" data-default-color="#333333" />
                    </div>
                    
                    <div class="wp-maintenance-setting-row">
                        <label for="wp_maintenance_settings[font_title]" class="wp-maintenance-setting-row-title"><?php _e('Choose Size and Style', 'wp-maintenance'); ?></label>
                      <table cellspacing="10">
                            <tr>
                                <td valign="middle"style="text-align:left;">
                                    <input name="wp_maintenance_settings[font_title]" class="selectfont" type="text" value="<?php if( isset($paramMMode['font_title']) && $paramMMode['font_title']!='' ) { echo str_replace(' ', '+', $paramMMode['font_title']); } else { echo 'Anton'; } ?>" />
                                </td>
                                <td><input type="text" size="3" name="wp_maintenance_settings[font_title_size]" value="<?php if( isset($paramMMode['font_title_size']) && $paramMMode['font_title_size']!='' ) { echo stripslashes($paramMMode['font_title_size']); } else { echo '16'; } ?>" />px</td>
                            </tr>
                            <tr>
                                <td>
                                    <label class="wp-maintenance-container"><span class="wp-maintenance-label-text"><?php _e('bold', 'wp-maintenance'); ?></span>
                                    <input type="radio" name="wp_maintenance_settings[font_title_weigth]" value="bold" <?php if( isset($paramMMode['font_title_weigth']) && $paramMMode['font_title_weigth']=='bold') { echo ' checked'; } ?>>
                                    <span class="wp-maintenance-checkmark"></span></label>
                                </td>
                                <td>
                                <label class="wp-maintenance-container"><span class="wp-maintenance-label-text"><?php _e('Italic', 'wp-maintenance'); ?></span>
                                    <input type="radio" name="wp_maintenance_settings[font_title_style]" value="italic" <?php if( isset($paramMMode['font_title_style']) && $paramMMode['font_title_style']=='italic') { echo ' checked'; } ?>>
                                    <span class="wp-maintenance-checkmark"></span></label>
                                    
                                </td>
                            </tr>                            
                        </table>                 
                    </div>
                    <p class="submit"><button type="submit" name="footer_submit" id="footer_submit" class="wp-maintenance-button wp-maintenance-button-primary"><?php _e('Save', 'wp-maintenance'); ?></button></p>   
                    <!-- FIN POLICE DU TITRE-->
                </div>

                <!-- POLICE DU TEXTE -->
                <div class="wp-maintenance-module-options-block">
                    <div class="wp-maintenance-settings-section-header">
                        <h3 class="wp-maintenance-settings-section-title" id="module-import_export"><?php _e('Text Settings', 'wp-maintenance'); ?></h3>
                    </div>

                    <h3><?php _e('Choice text font and color', 'wp-maintenance'); ?></h3>
                    <em><?php _e('Set the color and font of the text', 'wp-maintenance'); ?></em> <br /><br />

                    <div class="wp-maintenance-setting-row">
                        <label for="wp_maintenance_settings[color_txt]" class="wp-maintenance-setting-row-title"><?php _e('Choose font color', 'wp-maintenance'); ?></label>
                      <input type="text" value="<?php if( isset($paramMMode['color_txt']) && $paramMMode['color_txt']!='' ) { echo $paramMMode['color_txt']; } else { echo '#333333'; } ?>" name="wp_maintenance_settings[color_txt]" class="wpm-color-field" data-default-color="#333333" />
                    </div>

                    <div class="wp-maintenance-setting-row">
                        <label for="wp_maintenance_settings[font_text]" class="wp-maintenance-setting-row-title"><?php _e('Choose Size and Style', 'wp-maintenance'); ?></label>
                        <table cellspacing="10">
                            <tr>
                              <td valign="middle"style="text-align:left;">
                                <input name="wp_maintenance_settings[font_text]" class="selectfont" type="text" value="<?php if( isset($paramMMode['font_text']) && $paramMMode['font_text']!='' ) { echo str_replace(' ', '+', $paramMMode['font_text']); } else { echo 'Anton'; } ?>" />
                                </td>
                                <td><input type="text" size="3" name="wp_maintenance_settings[font_text_size]" value="<?php if( isset($paramMMode['font_text_size']) && $paramMMode['font_text_size']!='' ) { echo stripslashes($paramMMode['font_text_size']); } else { echo '16'; } ?>" />px</td>
                            </tr>
                            <tr>
                                <td>
                                    <label class="wp-maintenance-container"><span class="wp-maintenance-label-text"><?php _e('bold', 'wp-maintenance'); ?></span>
                                    <input type="radio" name="wp_maintenance_settings[font_text_weigth]" value="bold" <?php if( isset($paramMMode['font_text_weigth']) && $paramMMode['font_text_weigth']=='bold') { echo ' checked'; } ?>>
                                    <span class="wp-maintenance-checkmark"></span></label>
                                </td>
                                <td>
                                    <label class="wp-maintenance-container"><span class="wp-maintenance-label-text"><?php _e('Italic', 'wp-maintenance'); ?></span>
                                    <input type="radio" name="wp_maintenance_settings[font_text_style]" value="italic" <?php if( isset($paramMMode['font_text_style']) && $paramMMode['font_text_style']=='italic') { echo ' checked'; } ?>>
                                    <span class="wp-maintenance-checkmark"></span></label>
                                    
                                </td>
                            </tr>                            
                        </table>                 
                    </div> 
                    <p class="submit"><button type="submit" name="footer_submit" id="footer_submit" class="wp-maintenance-button wp-maintenance-button-primary"><?php _e('Save', 'wp-maintenance'); ?></button></p>
                </div>

                <!-- CADRE -->
                <div class="wp-maintenance-module-options-block">
                    <div class="wp-maintenance-settings-section-header">
                        <h3 class="wp-maintenance-settings-section-title" id="module-import_export"><?php _e('Frame Settings', 'wp-maintenance'); ?></h3>
                    </div>

                    <p>
                        <label class="wp-maintenance-container"><span class="wp-maintenance-label-text"><?php _e('Yes, enable frame', 'wp-maintenance'); ?></span>
                            <input type="checkbox" name="wp_maintenance_settings[container_active]" value="1" <?php if( isset($paramMMode['container_active']) && $paramMMode['container_active']==1) { echo ' checked'; } ?>>
                            <span class="wp-maintenance-checkmark"></span>
                        </label>
                    </p>

                    <h3><?php _e('Choice frame color and style', 'wp-maintenance'); ?></h3>

                    <div class="wp-maintenance-setting-row">
                        <label for="wp_maintenance_settings[container_color]" class="wp-maintenance-setting-row-title"><?php _e('Choose frame color', 'wp-maintenance'); ?></label>
                        <input type="text" value="<?php if( isset($paramMMode['container_color']) && $paramMMode['container_color']!='' ) { echo $paramMMode['container_color']; } else { echo '#333333'; } ?>" name="wp_maintenance_settings[container_color]" class="wpm-color-field" data-default-color="#333333" />
                    </div>

                    <div class="wp-maintenance-setting-row">
                        <label for="wp_maintenance_settings[container_opacity]" class="wp-maintenance-setting-row-title"><?php _e('Choose Style', 'wp-maintenance'); ?></label>
                        <span class="wp-maintenance-label-text"><?php _e('Opacity', 'wp-maintenance'); ?> <input type="text" size="5%" name="wp_maintenance_settings[container_opacity]" value="<?php if( isset($paramMMode['container_opacity']) && $paramMMode['container_opacity']!='' ) { echo $paramMMode['container_opacity']; } else { echo '0.5'; } ?>" />
                        <span class="wp-maintenance-label-text"><?php _e('Width', 'wp-maintenance'); ?></span>
                      <input type="text" name="wp_maintenance_settings[container_width]" size="5%" value="<?php if( isset($paramMMode['container_width']) && $paramMMode['container_width']!='' ) { echo $paramMMode['container_width']; } else { echo '80'; } ?>" />%      
                    </div>
                    <p class="submit"><button type="submit" name="footer_submit" id="footer_submit" class="wp-maintenance-button wp-maintenance-button-primary"><?php _e('Save', 'wp-maintenance'); ?></button></p>
                    <!-- FIN CADRE -->
                
                    <a name="countdown"></a>
                </div>

                <!-- COMPTE A REBOURS -->
                <div class="wp-maintenance-module-options-block">
                    <div class="wp-maintenance-settings-section-header">
                        <h3 class="wp-maintenance-settings-section-title" id="module-import_export"><?php _e('Countdown Settings', 'wp-maintenance'); ?></h3>
                    </div>

                    <h3><?php _e('Choice Countdown font, colors and style', 'wp-maintenance'); ?></h3>

                    <div class="wp-maintenance-setting-row">
                        <label for="wp_maintenance_settings[color_cpt]" class="wp-maintenance-setting-row-title"><?php _e('Countdown text color', 'wp-maintenance'); ?></label>
                        <input type="text" value="<?php if( isset($paramMMode['color_cpt']) && $paramMMode['color_cpt']!='' ) { echo $paramMMode['color_cpt']; } else { echo '#333333'; } ?>" name="wp_maintenance_settings[color_cpt]" class="wpm-color-field" data-default-color="#333333" />                        
                    </div>

                    <div class="wp-maintenance-setting-row">
                        <label for="wp_maintenance_settings[color_cpt_bg]" class="wp-maintenance-setting-row-title"><?php _e('Countdown background color', 'wp-maintenance'); ?></label>
                        <input type="text" value="<?php if( isset($paramMMode['color_cpt_bg']) && $paramMMode['color_cpt_bg']!='' ) { echo $paramMMode['color_cpt_bg']; } ?>" name="wp_maintenance_settings[color_cpt_bg]" class="wpm-color-field" data-default-color="#ffffff" />                      
                    </div>
                    <!-- POLICE DU COMPTEUR -->
                    <div class="wp-maintenance-setting-row">
                        <label for="wp_maintenance_settings[font_cpt]" class="wp-maintenance-setting-row-title"><?php _e('Choose font', 'wp-maintenance'); ?></label>
                        <table cellspacing="10">
                            <tr>
                                <td valign="middle"style="text-align:left;">
                                <input name="wp_maintenance_settings[font_cpt]" class="selectfont" type="text" value="<?php if( isset($paramMMode['font_cpt']) &&  $paramMMode['font_cpt']!='' ) { echo str_replace(' ', '+', $paramMMode['font_cpt']); } else { echo 'Pacifico'; } ?>" />
                                </td>
                                <td><input type="text" size="3" id="date_cpt_size" name="wp_maintenance_settings[date_cpt_size]" value="<?php if( isset($paramMMode['date_cpt_size']) && $paramMMode['date_cpt_size']!='' ) { echo trim($paramMMode['date_cpt_size']); } else { echo '6'; } ?>" />vw</td>
                            </tr>                
                        </table>                 
                    </div>
                    <!-- POLICE DU TEXT de FIN -->
                    <div class="wp-maintenance-setting-row">
                        <label for="wp_maintenance_settings[cpt_end_size]" class="wp-maintenance-setting-row-title"><?php _e('Choose font', 'wp-maintenance'); ?></label>
                        <table cellspacing="10">
                            <tr>
                                <td valign="middle"style="text-align:left;">
                                <input name="wp_maintenance_settings[font_end_cpt]" class="selectfont" type="text" value="<?php if( isset($paramMMode['font_end_cpt']) &&  $paramMMode['font_end_cpt']!='' ) { echo str_replace(' ', '+', $paramMMode['font_end_cpt']); } else { echo 'Pacifico'; } ?>" />
                                </td>
                                <td><input type="text" size="3" id="date_cpt_size" name="wp_maintenance_settings[cpt_end_size]" value="<?php if( isset($paramMMode['cpt_end_size']) && $paramMMode['cpt_end_size']!='' ) { echo trim($paramMMode['cpt_end_size']); } else { echo '2'; } ?>" />vw</td>
                            </tr>                
                        </table>                 
                    </div>
                    <p class="submit"><button type="submit" name="footer_submit" id="footer_submit" class="wp-maintenance-button wp-maintenance-button-primary"><?php _e('Save', 'wp-maintenance'); ?></button></p>
                    <!-- FIN POLICE DU COMPTEUR -->
                </div>

                <!-- BOTTOM PAGE -->
                <div class="wp-maintenance-module-options-block">
                    <div class="wp-maintenance-settings-section-header">
                        <h3 class="wp-maintenance-settings-section-title" id="module-import_export"><?php _e('Footer settings', 'wp-maintenance'); ?></h3>
                    </div>
                    <h3><?php _e('Choice footer font, colors and style', 'wp-maintenance'); ?></h3>

                    <div class="wp-maintenance-setting-row">
                        <label for="wp_maintenance_settings[color_text_bottom]" class="wp-maintenance-setting-row-title"><?php _e('Text color', 'wp-maintenance'); ?></label>
                        <input type="text" value="<?php if( isset($paramMMode['color_text_bottom']) && $paramMMode['color_text_bottom']!='' ) { echo $paramMMode['color_text_bottom']; } else { echo '#FFFFFF'; } ?>" name="wp_maintenance_settings[color_text_bottom]" class="wpm-color-field" data-default-color="#ffffff" />                   
                    </div>
                    
                    <div class="wp-maintenance-setting-row">
                        <label for="wp_maintenance_settings[color_bg_bottom]" class="wp-maintenance-setting-row-title"><?php _e('Background color', 'wp-maintenance'); ?></label>
                        <input type="text" value="<?php if( isset($paramMMode['color_bg_bottom']) && $paramMMode['color_bg_bottom']!='' ) { echo $paramMMode['color_bg_bottom']; } else { echo '#333333'; } ?>" name="wp_maintenance_settings[color_bg_bottom]" class="wpm-color-field" data-default-color="#333333" />                                           
                    </div>

                    <div class="wp-maintenance-setting-row">
                        <label for="wp_maintenance_settings[font_text]" class="wp-maintenance-setting-row-title"><?php _e('Choose Size and Style', 'wp-maintenance'); ?></label>
                        <table cellspacing="10">
                            <tr>
                              <td valign="middle"style="text-align:left;">
                              <input name="wp_maintenance_settings[font_text_bottom]" class="selectfont" type="text" value="<?php if( isset($paramMMode['font_text_bottom']) && $paramMMode['font_text_bottom']!='' ) { echo str_replace(' ', '+', $paramMMode['font_text_bottom']); } else { echo 'PT+Sans'; } ?>" />
                                </td>
                                <td><input type="text" size="3" name="wp_maintenance_settings[font_bottom_size]" value="<?php if( isset($paramMMode['font_bottom_size']) && $paramMMode['font_bottom_size']!='' ) { echo stripslashes($paramMMode['font_bottom_size']); } else { echo '12'; } ?>" />px</td>
                            </tr>
                            <tr>
                                <td>
                                    <label class="wp-maintenance-container"><span class="wp-maintenance-label-text"><?php _e('bold', 'wp-maintenance'); ?></span>
                                    <input type="radio" name="wp_maintenance_settings[font_bottom_weigth]" value="bold" <?php if( isset($paramMMode['font_bottom_weigth']) && $paramMMode['font_bottom_weigth']=='bold') { echo ' checked'; } ?>>
                                    <span class="wp-maintenance-checkmark"></span></label>
                                </td>
                                <td>
                                    <label class="wp-maintenance-container"><span class="wp-maintenance-label-text"><?php _e('Italic', 'wp-maintenance'); ?></span>
                                    <input type="radio" name="wp_maintenance_settings[font_bottom_style]" value="italic" <?php if( isset($paramMMode['font_bottom_style']) && $paramMMode['font_bottom_style']=='italic') { echo ' checked'; } ?>>
                                    <span class="wp-maintenance-checkmark"></span></label>
                                    
                                </td>
                            </tr>                            
                        </table>                 
                    </div>
                    <p class="submit"><button type="submit" name="footer_submit" id="footer_submit" class="wp-maintenance-button wp-maintenance-button-primary"><?php _e('Save', 'wp-maintenance'); ?></button></p>
                    <!-- FIN POLICE DU TEXTE BAS DE PAGE -->
                    
                </div>

                <div class="wp-maintenance-module-options-block">
                    <div class="wp-maintenance-settings-section-header">
                        <h3 class="wp-maintenance-settings-section-title" id="module-import_export"><?php _e('Form color settings', 'wp-maintenance'); ?></h3>
                    </div>
                    <h3><?php _e('Choice form colors', 'wp-maintenance'); ?></h3>
                    <?php 
                    
                        if( is_admin() ) {
                        if ( is_plugin_active( 'wysija-newsletters/index.php' ) || is_plugin_active( 'mailpoet/mailpoet.php' ) || is_plugin_active( 'mailchimp-for-wp/mailchimp-for-wp.php' ) ) {
                            
                            if ( isset($paramMMode['newletter']) && $paramMMode['newletter']==1 ) {
                            //if( isset($paramMMode['code_newletter']) && (strpos($paramMMode['code_newletter'], 'wysija_form')!=false || strpos($paramMMode['code_newletter'], 'mc4wp_form')!=false ) && (
                    ?>
                    <!-- COULEUR WYJIYA -->
                    <div class="wp-maintenance-setting-row">
                        <label for="wp_maintenance_settings[font_text]" class="wp-maintenance-setting-row-title"><?php _e('Choose Size and Style', 'wp-maintenance'); ?></label>
                        <table cellspacing="10">
                            <tr>
                                <td valign="middle"style="text-align:left;">
                                    <input name="wp_maintenance_settings[newletter_font_text]" class="selectfont" type="text" value="<?php if( isset($paramMMode['newletter_font_text']) && $paramMMode['newletter_font_text']!='' ) { echo str_replace(' ', '+', $paramMMode['newletter_font_text']); } else { echo 'PT+Sans'; } ?>" />
                                </td>
                                <td><input type="text" size="3" name="wp_maintenance_settings[newletter_size]" value="<?php if( isset($paramMMode['newletter_size']) && $paramMMode['newletter_size']!='') { echo stripslashes($paramMMode['newletter_size']); } else { echo '14'; } ?>" />px</td>
                            </tr>
                            <tr>
                                <td>
                                    <label class="wp-maintenance-container"><span class="wp-maintenance-label-text"><?php _e('bold', 'wp-maintenance'); ?></span>
                                    <input type="radio" name="wp_maintenance_settings[newletter_font_weigth]" value="bold" <?php if( isset($paramMMode['newletter_font_weigth']) && $paramMMode['newletter_font_weigth']=='bold') { echo ' checked'; } ?>>
                                    <span class="wp-maintenance-checkmark"></span></label>
                                </td>
                                <td>
                                    <label class="wp-maintenance-container"><span class="wp-maintenance-label-text"><?php _e('Italic', 'wp-maintenance'); ?></span>
                                    <input type="radio" name="wp_maintenance_settings[newletter_font_style]" value="italic" <?php if( isset($paramMMode['newletter_font_style']) && $paramMMode['newletter_font_style']=='italic') { echo ' checked'; } ?>>
                                    <span class="wp-maintenance-checkmark"></span></label>
                                    
                                </td>
                            </tr>                            
                        </table>                 
                    </div>
                    <div class="wp-maintenance-setting-row">
                        
                        <table width="80%">
                            <tr>
                                <td width="40%">
                                    <label for="wp_maintenance_settings[color_field_text]" class="wp-maintenance-setting-row-title"><?php _e('Field text color', 'wp-maintenance'); ?></label>
                                    <input type="text" value="<?php if( isset($paramMMode['color_field_text']) && $paramMMode['color_field_text']!='' ) { echo $paramMMode['color_field_text']; } else { echo '#333333'; } ?>" name="wp_maintenance_settings[color_field_text]" class="wpm-color-field" data-default-color="#333333" /><br />
                                    <label for="wp_maintenance_settings[color_field_border]" class="wp-maintenance-setting-row-title"><?php _e('Field border color', 'wp-maintenance'); ?></label>
                                    <input type="text" value="<?php if( isset($paramMMode['color_field_border']) && $paramMMode['color_field_border']!='' ) { echo $paramMMode['color_field_border']; } else { echo '#333333'; } ?>" name="wp_maintenance_settings[color_field_border]" class="wpm-color-field" data-default-color="#333333" /><br />
                                    <label for="wp_maintenance_settings[color_field_background]" class="wp-maintenance-setting-row-title"><?php _e('Field background color', 'wp-maintenance'); ?></label>
                                    <input type="text" value="<?php if( isset($paramMMode['color_field_background']) && $paramMMode['color_field_background']!='' ) { echo $paramMMode['color_field_background']; } else { echo '#cccccc'; } ?>" name="wp_maintenance_settings[color_field_background]" class="wpm-color-field" data-default-color="#cccccc" />
                                    <label for="wp_maintenance_settings[color_text_button]" class="wp-maintenance-setting-row-title"><?php _e('Button text color', 'wp-maintenance'); ?></label>
                                    <input type="text" value="<?php if( isset($paramMMode['color_text_button']) && $paramMMode['color_text_button']!='' ) { echo $paramMMode['color_text_button']; } else { echo '#ffffff'; } ?>" name="wp_maintenance_settings[color_text_button]" class="wpm-color-field" data-default-color="#ffffff" />
                                <td>
                                <td style="vertical-align:top;">                       
                                    <label for="wp_maintenance_settings[color_button]" class="wp-maintenance-setting-row-title"><?php _e('Button color', 'wp-maintenance'); ?></label>
                                    <input type="text" value="<?php if( isset($paramMMode['color_button']) && $paramMMode['color_button']!='' ) { echo $paramMMode['color_button']; } else { echo '#1e73be'; } ?>" name="wp_maintenance_settings[color_button]" class="wpm-color-field" data-default-color="#1e73be" />
                                    <label for="wp_maintenance_settings[color_button_hover]" class="wp-maintenance-setting-row-title"><?php _e('Button color hover', 'wp-maintenance'); ?></label>
                                    <input type="text" value="<?php if( isset($paramMMode['color_button_hover']) && $paramMMode['color_button_hover']!='' ) { echo $paramMMode['color_button_hover']; } else { echo '#ffffff'; }  ?>" name="wp_maintenance_settings[color_button_hover]" class="wpm-color-field" data-default-color="#ffffff" /><br />
                                    <label for="wp_maintenance_settings[color_button_onclick]" class="wp-maintenance-setting-row-title"><?php _e('Button color onclick', 'wp-maintenance'); ?></label>
                                    <input type="text" value="<?php if( isset($paramMMode['color_button_onclick']) && $paramMMode['color_button_onclick']!=''  ) { echo $paramMMode['color_button_onclick']; } else { echo '#ffffff'; } ?>" name="wp_maintenance_settings[color_button_onclick]" class="wpm-color-field" data-default-color="#ffffff" />
                                </td>
                            </tr>
                        </table>
                        
                    </div>
                    <p class="submit"><button type="submit" name="footer_submit" id="footer_submit" class="wp-maintenance-button wp-maintenance-button-primary"><?php _e('Save', 'wp-maintenance'); ?></button></p>
                    
                    <?php
                            } else {
                                printf( __('Enable %s to customize the forms', 'wp-maintenance'), '<a href="'.admin_url().'?page=wp-maintenance#newsletter">'.__('newsletter option', 'wp-maintenance').'</a>' ); 
                            }
                            
                        } else {
                            _e('Enable Mailpoet or MailChimp extensions to customize the forms', 'wp-maintenance'); 
                        
                        }
                        }
                    ?>
                </div>
            </form>
        </div>
    </div>

    <?php echo wpm_footer(); ?>
</div>