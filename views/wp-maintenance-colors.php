<?php

defined( 'ABSPATH' ) or die( 'Not allowed' );

$messageUpdate = 0;
/* Update des paramètres */
if( isset($_POST['action']) && $_POST['action'] == 'update_colors' && wp_verify_nonce($_POST['security-colors'], 'valid-colors') ) {

    if( empty($_POST["wpmcolors"]["container_active"]) ) { $_POST["wpmcolors"]["container_active"] = 0; }

    $updateSetting = wpm_update_settings( $_POST["wpmcolors"], 'wp_maintenance_settings_colors');
    if( $updateSetting == true ) { $messageUpdate = 1; }
}

// Récupère les paramètres sauvegardés
if(get_option('wp_maintenance_settings_colors')) { extract(get_option('wp_maintenance_settings_colors')); }
$paramsColors = get_option('wp_maintenance_settings_colors');

// Récupère les paramètres sauvegardés
if(get_option('wp_maintenance_settings_options')) { extract(get_option('wp_maintenance_settings_options')); }
$wpoptions = get_option('wp_maintenance_settings_options');

// Récupère les paramètres sauvegardés
if(get_option('wp_maintenance_settings')) { extract(get_option('wp_maintenance_settings')); }
$paramsSettings = get_option('wp_maintenance_settings');

?>
<script type="text/javascript">
    jQuery(document).ready(function() {
        jQuery('#select_font_title').fontselect();
        jQuery('#select_font_text').fontselect();        
        jQuery('#select_font_text_cpt').fontselect();
        jQuery('#select_font_text_bottom').fontselect();
    });

    jQuery(document).ready(function() {

        jQuery('input.selectfont').fontselect({
          placeholder: 'Select a font',
        });
        
    });
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
                <input type="hidden" name="action" value="update_colors" />
                <?php wp_nonce_field('valid-colors', 'security-colors'); ?>
                
                <!-- COULEUR DU FOND DE PAGE -->
                <div class="wp-maintenance-module-options-block">

                    <div class="wp-maintenance-settings-section-header">
                        <h3 class="wp-maintenance-settings-section-title" id="module-import_export"><?php esc_html_e('Choice general colors', 'wp-maintenance'); ?></h3>
                    </div>
                    <div class="wp-maintenance-setting-row">
                        <label for="wpmcolors[color_bg]" class="wp-maintenance-setting-row-title"><?php esc_html_e('Background page color', 'wp-maintenance'); ?></label>
                        <input type="text" value="<?php if( isset($paramsColors['color_bg']) && $paramsColors['color_bg']!='' ) { echo esc_html($paramsColors['color_bg']); } ?>" name="wpmcolors[color_bg]" class="wpm-color-field" data-default-color="#f1f1f1" />
                        <label for="wpmcolors[color_bg_header]" class="wp-maintenance-setting-row-title"><?php esc_html_e('Header color', 'wp-maintenance'); ?></label>
                        <input type="text" value="<?php if( isset($paramsColors['color_bg_header']) && $paramsColors['color_bg_header']!='' ) { echo esc_html($paramsColors['color_bg_header']); } ?>" name="wpmcolors[color_bg_header]" class="wpm-color-field" data-default-color="#ffffff" />
                    </div>

                    <p class="submit"><button type="submit" name="footer_submit" id="footer_submit" class="wp-maintenance-button wp-maintenance-button-primary"><?php esc_html_e('Save', 'wp-maintenance'); ?></button></p> 
                </div>

                <!-- POLICE DU TITRE -->
                <div class="wp-maintenance-module-options-block">
                    
                    <div class="wp-maintenance-settings-section-header">
                        <h3 class="wp-maintenance-settings-section-title" id="module-import_export"><?php esc_html_e('Title Settings', 'wp-maintenance'); ?></h3>
                    </div>

                    <h3><?php esc_html_e('Choice title font and color', 'wp-maintenance'); ?></h3>
                    <em><?php esc_html_e('Set the color and font of the title', 'wp-maintenance'); ?></em> <br /><br />

                    <div class="wp-maintenance-setting-row">
                        <label for="wpmcolors[color_title]" class="wp-maintenance-setting-row-title"><?php esc_html_e('Choose font color', 'wp-maintenance'); ?></label>
                        <input type="text" value="<?php if( isset($paramsColors['color_title']) && $paramsColors['color_title']!='' ) { echo esc_html($paramsColors['color_title']); } else { echo '#333333'; } ?>" name="wpmcolors[color_title]" class="wpm-color-field" data-default-color="#333333" />
                    </div>
                    
                    <div class="wp-maintenance-setting-row">
                        <label for="wpmcolors[font_title]" class="wp-maintenance-setting-row-title"><?php esc_html_e('Choose Size and Style', 'wp-maintenance'); ?></label>
                      <table cellspacing="10">
                            <tr>
                                <td valign="middle"style="text-align:left;">
                                    <?php if(isset($wpoptions['remove_googlefonts']) && $wpoptions['remove_googlefonts']==1) { ?>
                                        <?php echo wp_kses(wpm_fonts($paramsColors['font_title'], 'font_title'), wpm_autorizeHtml()); ?>
                                    <?php } else { ?>
                                        <input name="wpmcolors[font_title]" class="selectfont" type="text" value="<?php if( isset($paramsColors['font_title']) && $paramsColors['font_title']!='' ) { echo esc_html(str_replace(' ', '+', $paramsColors['font_title'])); } else { echo 'Anton'; } ?>" />
                                    <?php } ?>
                                
                                </td>
                                <td><input type="text" size="3" name="wpmcolors[font_title_size]" value="<?php if( isset($paramsColors['font_title_size']) && $paramsColors['font_title_size']!='' ) { echo esc_html(stripslashes($paramsColors['font_title_size'])); } else { echo '16'; } ?>" />px</td>
                            </tr>
                            <tr>
                                <td>
                                    <label class="wp-maintenance-container"><span class="wp-maintenance-label-text"><?php esc_html_e('bold', 'wp-maintenance'); ?></span>
                                    <input type="checkbox" name="wpmcolors[font_title_weigth]" value="bold" <?php if( isset($paramsColors['font_title_weigth']) && $paramsColors['font_title_weigth']=='bold') { echo ' checked'; } ?>>
                                    <span class="wp-maintenance-checkmark"></span></label>
                                </td>
                                <td>
                                <label class="wp-maintenance-container"><span class="wp-maintenance-label-text"><?php esc_html_e('Italic', 'wp-maintenance'); ?></span>
                                    <input type="checkbox" name="wpmcolors[font_title_style]" value="italic" <?php if( isset($paramsColors['font_title_style']) && $paramsColors['font_title_style']=='italic') { echo ' checked'; } ?>>
                                    <span class="wp-maintenance-checkmark"></span></label>
                                    
                                </td>
                            </tr>                            
                        </table>                 
                    </div>
                    <p class="submit"><button type="submit" name="footer_submit" id="footer_submit" class="wp-maintenance-button wp-maintenance-button-primary"><?php esc_html_e('Save', 'wp-maintenance'); ?></button></p>   
                    <!-- FIN POLICE DU TITRE-->
                </div>

                <!-- POLICE DU TEXTE -->
                <div class="wp-maintenance-module-options-block">
                    <div class="wp-maintenance-settings-section-header">
                        <h3 class="wp-maintenance-settings-section-title" id="module-import_export"><?php esc_html_e('Text Settings', 'wp-maintenance'); ?></h3>
                    </div>

                    <h3><?php esc_html_e('Choice text font and color', 'wp-maintenance'); ?></h3>
                    <em><?php esc_html_e('Set the color and font of the text', 'wp-maintenance'); ?></em> <br /><br />

                    <div class="wp-maintenance-setting-row">
                        <label for="wpmcolors[color_txt]" class="wp-maintenance-setting-row-title"><?php esc_html_e('Choose font color', 'wp-maintenance'); ?></label>
                      <input type="text" value="<?php if( isset($paramsColors['color_txt']) && $paramsColors['color_txt']!='' ) { echo esc_html($paramsColors['color_txt']); } else { echo '#333333'; } ?>" name="wpmcolors[color_txt]" class="wpm-color-field" data-default-color="#333333" />
                    </div>

                    <div class="wp-maintenance-setting-row">
                        <label for="wpmcolors[font_text]" class="wp-maintenance-setting-row-title"><?php esc_html_e('Choose Size and Style', 'wp-maintenance'); ?></label>
                        <table cellspacing="10">
                            <tr>
                                <td valign="middle"style="text-align:left;">
                                    <?php if(isset($wpoptions['remove_googlefonts']) && $wpoptions['remove_googlefonts']==1) { ?>
                                        <?php echo wp_kses(wpm_fonts($paramsColors['font_text'], 'font_title'), wpm_autorizeHtml()); ?>
                                    <?php } else { ?>
                                        <input name="wpmcolors[font_text]" class="selectfont" type="text" value="<?php if( isset($paramsColors['font_text']) && $paramsColors['font_text']!='' ) { echo esc_html(str_replace(' ', '+', $paramsColors['font_text'])); } else { echo 'Anton'; } ?>" />
                                    <?php } ?>
                                </td>
                                <td><input type="text" size="3" name="wpmcolors[font_text_size]" value="<?php if( isset($paramsColors['font_text_size']) && $paramsColors['font_text_size']!='' ) { echo esc_html(stripslashes($paramsColors['font_text_size'])); } else { echo '16'; } ?>" />px</td>
                            </tr>
                            <tr>
                                <td>
                                    <label class="wp-maintenance-container"><span class="wp-maintenance-label-text"><?php esc_html_e('bold', 'wp-maintenance'); ?></span>
                                    <input type="checkbox" name="wpmcolors[font_text_weigth]" value="bold" <?php if( isset($paramsColors['font_text_weigth']) && $paramsColors['font_text_weigth']=='bold') { echo ' checked'; } ?>>
                                    <span class="wp-maintenance-checkmark"></span></label>
                                </td>
                                <td>
                                    <label class="wp-maintenance-container"><span class="wp-maintenance-label-text"><?php esc_html_e('Italic', 'wp-maintenance'); ?></span>
                                    <input type="checkbox" name="wpmcolors[font_text_style]" value="italic" <?php if( isset($paramsColors['font_text_style']) && $paramsColors['font_text_style']=='italic') { echo ' checked'; } ?>>
                                    <span class="wp-maintenance-checkmark"></span></label>
                                    
                                </td>
                            </tr>                            
                        </table>                 
                    </div> 
                    <p class="submit"><button type="submit" name="footer_submit" id="footer_submit" class="wp-maintenance-button wp-maintenance-button-primary"><?php esc_html_e('Save', 'wp-maintenance'); ?></button></p>
                </div>

                <!-- CADRE -->
                <div class="wp-maintenance-module-options-block">
                    <div class="wp-maintenance-settings-section-header">
                        <h3 class="wp-maintenance-settings-section-title" id="module-import_export"><?php esc_html_e('Frame Settings', 'wp-maintenance'); ?></h3>
                    </div>

                    <p>
                        <label class="wp-maintenance-container"><span class="wp-maintenance-label-text"><?php esc_html_e('Yes, enable frame', 'wp-maintenance'); ?></span>
                            <input type="checkbox" name="wpmcolors[container_active]" value="1" <?php if( isset($paramsColors['container_active']) && $paramsColors['container_active']==1) { echo ' checked'; } ?>>
                            <span class="wp-maintenance-checkmark"></span>
                        </label>
                    </p>

                    <h3><?php esc_html_e('Choice frame color and style', 'wp-maintenance'); ?></h3>

                    <div class="wp-maintenance-setting-row">
                        <label for="wpmcolors[container_color]" class="wp-maintenance-setting-row-title"><?php esc_html_e('Choose frame color', 'wp-maintenance'); ?></label>
                        <input type="text" value="<?php if( isset($paramsColors['container_color']) && $paramsColors['container_color']!='' ) { echo esc_html($paramsColors['container_color']); } else { echo '#333333'; } ?>" name="wpmcolors[container_color]" class="wpm-color-field" data-default-color="#333333" />
                    </div>

                    <div class="wp-maintenance-setting-row">
                        <label for="wpmcolors[container_opacity]" class="wp-maintenance-setting-row-title"><?php esc_html_e('Choose Style', 'wp-maintenance'); ?></label>
                        <span class="wp-maintenance-label-text"><?php esc_html_e('Opacity', 'wp-maintenance'); ?> <input type="text" size="5%" name="wpmcolors[container_opacity]" value="<?php if( isset($paramsColors['container_opacity']) && $paramsColors['container_opacity']!='' ) { echo esc_html($paramsColors['container_opacity']); } else { echo '0.5'; } ?>" />
                        <span class="wp-maintenance-label-text"><?php esc_html_e('Width', 'wp-maintenance'); ?></span>
                      <input type="text" name="wpmcolors[container_width]" size="5%" value="<?php if( isset($paramsColors['container_width']) && $paramsColors['container_width']!='' ) { echo esc_html($paramsColors['container_width']); } else { echo '80'; } ?>" />%      
                    </div>
                    <p class="submit"><button type="submit" name="footer_submit" id="footer_submit" class="wp-maintenance-button wp-maintenance-button-primary"><?php esc_html_e('Save', 'wp-maintenance'); ?></button></p>
                    <!-- FIN CADRE -->
                
                    <a name="countdown"></a>
                </div>

                <!-- COMPTE A REBOURS -->
                <div class="wp-maintenance-module-options-block">
                    <div class="wp-maintenance-settings-section-header">
                        <h3 class="wp-maintenance-settings-section-title" id="module-import_export"><?php esc_html_e('Countdown Settings', 'wp-maintenance'); ?></h3>
                    </div>

                    <h3><?php esc_html_e('Choice Countdown font, colors and style', 'wp-maintenance'); ?></h3>

                    <div class="wp-maintenance-setting-row">
                        <label for="wpmcolors[color_cpt]" class="wp-maintenance-setting-row-title"><?php esc_html_e('Countdown text color', 'wp-maintenance'); ?></label>
                        <input type="text" value="<?php if( isset($paramsColors['color_cpt']) && $paramsColors['color_cpt']!='' ) { echo esc_html($paramsColors['color_cpt']); } else { echo '#333333'; } ?>" name="wpmcolors[color_cpt]" class="wpm-color-field" data-default-color="#333333" />                        
                    </div>

                    <div class="wp-maintenance-setting-row">
                        <label for="wpmcolors[color_cpt_bg]" class="wp-maintenance-setting-row-title"><?php esc_html_e('Countdown background color', 'wp-maintenance'); ?></label>
                        <input type="text" value="<?php if( isset($paramsColors['color_cpt_bg']) && $paramsColors['color_cpt_bg']!='' ) { echo esc_html($paramsColors['color_cpt_bg']); } ?>" name="wpmcolors[color_cpt_bg]" class="wpm-color-field" data-default-color="#ffffff" />                      
                    </div>
                    <!-- POLICE DU COMPTEUR -->
                    <div class="wp-maintenance-setting-row">
                        <label for="wpmcolors[font_cpt]" class="wp-maintenance-setting-row-title"><?php esc_html_e('Choose font', 'wp-maintenance'); ?></label>
                        <table cellspacing="10">
                            <tr>
                                <td valign="middle"style="text-align:left;">
                                    <?php if(isset($wpoptions['remove_googlefonts']) && $wpoptions['remove_googlefonts']==1) { ?>
                                        <?php echo wp_kses(wpm_fonts($paramsColors['font_cpt'], 'font_title'), wpm_autorizeHtml()); ?>
                                    <?php } else { ?>
                                        <input name="wpmcolors[font_cpt]" class="selectfont" type="text" value="<?php if( isset($paramsColors['font_cpt']) && $paramsColors['font_cpt']!='' ) { echo esc_html(str_replace(' ', '+', $paramsColors['font_cpt'])); } else { echo 'Pacifico'; } ?>" />
                                    <?php } ?>
                                </td>
                                <td>
                                    <input type="text" size="3" id="date_cpt_size" name="wpmcolors[date_cpt_size]" value="<?php if( isset($paramsColors['date_cpt_size']) && $paramsColors['date_cpt_size']!='' ) { echo esc_html(trim($paramsColors['date_cpt_size'])); } else { echo '6'; } ?>" />vw
                                </td>
                            </tr>                
                        </table>                 
                    </div>
                    <!-- POLICE DU TEXT de FIN -->
                    <div class="wp-maintenance-setting-row">
                        <label for="wpmcolors[cpt_end_size]" class="wp-maintenance-setting-row-title"><?php esc_html_e('Choose font for end text', 'wp-maintenance'); ?></label>
                        <table cellspacing="10">
                            <tr>
                                <td valign="middle"style="text-align:left;">
                                    <?php if(isset($wpoptions['remove_googlefonts']) && $wpoptions['remove_googlefonts']==1) { ?>
                                        <?php echo wp_kses(wpm_fonts($paramsColors['font_end_cpt'], 'font_title'), wpm_autorizeHtml()); ?>
                                    <?php } else { ?>
                                        <input name="wpmcolors[font_end_cpt]" class="selectfont" type="text" value="<?php if( isset($paramsColors['font_end_cpt']) && $paramsColors['font_end_cpt']!='' ) { echo esc_html(str_replace(' ', '+', $paramsColors['font_end_cpt'])); } else { echo 'Pacifico'; } ?>" />
                                    <?php } ?>
                                </td>
                                <td><input type="text" size="3" id="date_cpt_size" name="wpmcolors[cpt_end_size]" value="<?php if( isset($paramsColors['cpt_end_size']) && $paramsColors['cpt_end_size']!='' ) { echo esc_html(trim($paramsColors['cpt_end_size'])); } else { echo '2'; } ?>" />vw</td>
                            </tr>                
                        </table>                 
                    </div>
                    <p class="submit"><button type="submit" name="footer_submit" id="footer_submit" class="wp-maintenance-button wp-maintenance-button-primary"><?php esc_html_e('Save', 'wp-maintenance'); ?></button></p>
                    <!-- FIN POLICE DU COMPTEUR -->
                </div>

                <!-- BOTTOM PAGE -->
                <div class="wp-maintenance-module-options-block">
                    <div class="wp-maintenance-settings-section-header">
                        <h3 class="wp-maintenance-settings-section-title" id="module-import_export"><?php esc_html_e('Footer settings', 'wp-maintenance'); ?></h3>
                    </div>
                    <h3><?php esc_html_e('Choice footer font, colors and style', 'wp-maintenance'); ?></h3>

                    <div class="wp-maintenance-setting-row">
                        <label for="wpmcolors[color_text_bottom]" class="wp-maintenance-setting-row-title"><?php esc_html_e('Text color', 'wp-maintenance'); ?></label>
                        <input type="text" value="<?php if( isset($paramsColors['color_text_bottom']) && $paramsColors['color_text_bottom']!='' ) { echo esc_html($paramsColors['color_text_bottom']); } else { echo '#FFFFFF'; } ?>" name="wpmcolors[color_text_bottom]" class="wpm-color-field" data-default-color="#ffffff" />                   
                    </div>
                    
                    <div class="wp-maintenance-setting-row">
                        <label for="wpmcolors[color_bg_bottom]" class="wp-maintenance-setting-row-title"><?php esc_html_e('Background color', 'wp-maintenance'); ?></label>
                        <input type="text" value="<?php if( isset($paramsColors['color_bg_bottom']) && $paramsColors['color_bg_bottom']!='' ) { echo esc_html($paramsColors['color_bg_bottom']); } else { echo '#333333'; } ?>" name="wpmcolors[color_bg_bottom]" class="wpm-color-field" data-default-color="#333333" />                                           
                    </div>

                    <div class="wp-maintenance-setting-row">
                        <label for="wpmcolors[font_text]" class="wp-maintenance-setting-row-title"><?php esc_html_e('Choose Size and Style', 'wp-maintenance'); ?></label>
                        <table cellspacing="10">
                            <tr>
                              <td valign="middle"style="text-align:left;">
                                    <?php if(isset($wpoptions['remove_googlefonts']) && $wpoptions['remove_googlefonts']==1) { ?>
                                        <?php echo wp_kses(wpm_fonts($paramsColors['font_text_bottom'], 'font_title'), wpm_autorizeHtml()); ?>
                                    <?php } else { ?>
                                        <input name="wpmcolors[font_text_bottom]" class="selectfont" type="text" value="<?php if( isset($paramsColors['font_text_bottom']) && $paramsColors['font_text_bottom']!='' ) { echo esc_html(str_replace(' ', '+', $paramsColors['font_text_bottom'])); } else { echo 'Open Sans'; } ?>" />
                                    <?php } ?>
                                </td>
                                <td><input type="text" size="3" name="wpmcolors[font_bottom_size]" value="<?php if( isset($paramsColors['font_bottom_size']) && $paramsColors['font_bottom_size']!='' ) { echo esc_html(stripslashes($paramsColors['font_bottom_size'])); } else { echo '12'; } ?>" />px</td>
                            </tr>
                            <tr>
                                <td>
                                    <label class="wp-maintenance-container"><span class="wp-maintenance-label-text"><?php esc_html_e('bold', 'wp-maintenance'); ?></span>
                                    <input type="checkbox" name="wpmcolors[font_bottom_weigth]" value="bold" <?php if( isset($paramsColors['font_bottom_weigth']) && $paramsColors['font_bottom_weigth']=='bold') { echo ' checked'; } ?>>
                                    <span class="wp-maintenance-checkmark"></span></label>
                                </td>
                                <td>
                                    <label class="wp-maintenance-container"><span class="wp-maintenance-label-text"><?php esc_html_e('Italic', 'wp-maintenance'); ?></span>
                                    <input type="checkbox" name="wpmcolors[font_bottom_style]" value="italic" <?php if( isset($paramsColors['font_bottom_style']) && $paramsColors['font_bottom_style']=='italic') { echo ' checked'; } ?>>
                                    <span class="wp-maintenance-checkmark"></span></label>
                                    
                                </td>
                            </tr>                            
                        </table>                 
                    </div>
                    <p class="submit"><button type="submit" name="footer_submit" id="footer_submit" class="wp-maintenance-button wp-maintenance-button-primary"><?php esc_html_e('Save', 'wp-maintenance'); ?></button></p>
                    <!-- FIN POLICE DU TEXTE BAS DE PAGE -->
                    
                </div>

            </form>
        </div>
    </div>

    <?php echo wp_kses(wpm_footer(), wpm_autorizeHtml()); ?>
</div>