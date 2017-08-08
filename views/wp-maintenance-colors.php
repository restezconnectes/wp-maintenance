<?php

defined( 'ABSPATH' ) or die( 'Not allowed' );

$messageUpdate = 0;
/* Update des paramètres */
if( isset($_POST['action']) && $_POST['action'] == 'update_colors' ) {

    $options_saved = wpm_update_settings($_POST["wp_maintenance_settings"]);
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
    
    <form method="post" action="" name="valide_settings">
        <input type="hidden" name="action" value="update_colors" />
        
    <!-- HEADER -->
    <?php echo wpm_get_header( __('Colors & Fonts', 'wp-maintenance'), 'dashicons-art', $messageUpdate ) ?>
    <!-- END HEADER -->

    <div style="margin-top: 80px;">
        
        <div style="float:left;width:73%;margin-right:1%;border: 1px solid #ddd;background-color:#fff;padding:10px;">
            
            
                
            <!-- COULEUR DU FOND DE PAGE -->
            <h3><?php _e('Choice general colors:', 'wp-maintenance'); ?></h3>
            <em><?php _e('Background page color:', 'wp-maintenance'); ?></em> <br /><input type="text" value="<?php if( isset($paramMMode['color_bg']) && $paramMMode['color_bg']!='' ) { echo $paramMMode['color_bg']; } ?>" name="wp_maintenance_settings[color_bg]" class="wpm-color-field" data-default-color="#f1f1f1" /> <br />
            <em><?php _e('Header color:', 'wp-maintenance'); ?></em> <br /><input type="text" value="<?php if( isset($paramMMode['color_bg_header']) && $paramMMode['color_bg_header']!='' ) { echo $paramMMode['color_bg_header']; } ?>" name="wp_maintenance_settings[color_bg_header]" class="wpm-color-field" data-default-color="#333333" />
            <div style="margin-top:15px;margin-bottom:15px;"><hr /></div>

            <h3><?php _e('Choice texts fonts and colors:', 'wp-maintenance'); ?></h3>
            <em><?php _e('Text color:', 'wp-maintenance'); ?></em> <br /><input type="text" value="<?php if( isset($paramMMode['color_txt']) && $paramMMode['color_txt']!='' ) { echo $paramMMode['color_txt']; } else { echo '#333333'; } ?>" name="wp_maintenance_settings[color_txt]" class="wpm-color-field" data-default-color="#333333" /><br /><br />
            <!-- POLICE DU TITRE -->
            <em><stong><?php _e('Title font settings', 'wp-maintenance'); ?></stong></em>
            <table cellspacing="10">
                <tr>
                    <td valign="top" align="left">
                        <input name="wp_maintenance_settings[font_title]" class="selectfont" type="text" value="<?php if( isset($paramMMode['font_title']) && $paramMMode['font_title']!='' ) { echo str_replace(' ', '+', $paramMMode['font_title']); } else { echo 'Anton'; } ?>" />
                    </td>
                    <td>
                        <?php _e('Size:', 'wp-maintenance'); ?>
                        <input type="text" class="wpm-form-field" size="3" name="wp_maintenance_settings[font_title_size]" value="<?php if( isset($paramMMode['font_title_size']) && $paramMMode['font_title_size']!='' ) { echo stripslashes($paramMMode['font_title_size']); } else { echo '16'; } ?>" />px

                    </td>
                </tr>
                <tr>
                    <td rowspan="2">
                        <input type="radio" name="wp_maintenance_settings[font_title_weigth]" value="normal" <?php if( (isset($paramMMode['font_title_weigth']) && $paramMMode['font_title_weigth']=='normal') || empty($paramMMode['font_title_weigth']) ) { echo 'checked'; } ?> >Normal
                        <input type="radio" name="wp_maintenance_settings[font_title_weigth]" value="bold" <?php if( isset($paramMMode['font_title_weigth']) && $paramMMode['font_title_weigth']=='bold') { echo 'checked'; } ?>>Bold
                        <input type="checkbox" name="wp_maintenance_settings[font_title_style]" value="italic" <?php if( isset($paramMMode['font_title_style']) && $paramMMode['font_title_style']=='italic') { echo 'checked'; } ?>>Italic
                    </td>
                </tr>
            </table>                    
            <!-- FIN POLICE DU TITRE-->
                    
            <!-- POLICE DU TEXTE -->
            <br /><em><?php _e('Text font settings', 'wp-maintenance'); ?></em>
            <table cellspacing="10">
                <tr>
                    <td valign="top" align="left">
                        <input name="wp_maintenance_settings[font_text]" class="selectfont" type="text" value="<?php if( isset($paramMMode['font_text']) && $paramMMode['font_text']!='' ) { echo str_replace(' ', '+', $paramMMode['font_text']); } else { echo 'PT+Sans'; } ?>" />
                    </td>
                    <td>
                        <?php _e('Size:', 'wp-maintenance'); ?>
                        <input type="text" class="wpm-form-field" size="3" name="wp_maintenance_settings[font_text_size]" value="<?php if( isset($paramMMode['font_text_size']) && $paramMMode['font_text_size']!='' ) { echo stripslashes($paramMMode['font_text_size']); } else { echo '14'; }?>" />px

                    </td>
                </tr>
                <tr>
                    <td rowspan="2">
                        <input type="radio" name="wp_maintenance_settings[font_text_weigth]" value="normal" <?php if( (isset($paramMMode['font_text_weigth']) && $paramMMode['font_text_weigth']=='normal') || empty($paramMMode['font_text_weigth']) ) { echo 'checked'; } ?> >Normal
                        <input type="radio" name="wp_maintenance_settings[font_text_weigth]" value="bold" <?php if( isset($paramMMode['font_text_weigth']) && $paramMMode['font_text_weigth']=='bold') { echo 'checked'; } ?>>Bold
                        <input type="checkbox" name="wp_maintenance_settings[font_text_style]" value="italic" <?php if( isset($paramMMode['font_text_style']) && $paramMMode['font_text_style']=='italic') { echo 'checked'; } ?>>Italic
                    </td>
                </tr>
            </table>   
            <!-- FIN POLICE DU TEXTE -->
            <div style="margin-top:15px;margin-bottom:15px;"><hr /></div>
                
             <!-- CADRE -->
            <div>
                <div style="float:left; width:70%;"><h3><?php _e('Activate Frame', 'wp-maintenance'); ?></h3></div>
                <div style="float:left; width:30%;text-align:right;padding-top: 5px;">
                    <div class="switch-field">
                        <input class="switch_left" type="radio" onclick="AfficherTexte('option-container');" id="switch_container" name="wp_maintenance_settings[container_active]" value="1" <?php if( isset($paramMMode['container_active']) && $paramMMode['container_active']==1) { echo ' checked'; } ?>/>
                        <label for="switch_container"><?php _e('Yes', 'wp-maintenance'); ?></label>
                        <input class="switch_right" type="radio" onclick="CacherTexte('option-container');" id="switch_container_no" name="wp_maintenance_settings[container_active]" value="0" <?php if( empty($paramMMode['container_active']) || isset($paramMMode['container_active']) && $paramMMode['container_active']==0) { echo ' checked'; } ?> />
                        <label for="switch_container_no"><?php _e('No', 'wp-maintenance'); ?></label>
                    </div>
                </div>
                <div class="clear"></div>
                <a name="countdown"></a>
            </div>
            <div id="option-container" style="<?php if( empty($paramMMode['container_active']) || isset($paramMMode['container_active']) && $paramMMode['container_active']==0) { echo ' display:none;'; } else { echo 'display:block'; } ?>">
                <?php _e('Color:', 'wp-maintenance'); ?><br /> <input type="text" value="<?php if( isset($paramMMode['container_color']) && $paramMMode['container_color']!='' ) { echo $paramMMode['container_color']; } else { echo '#ffffff'; }?>" name="wp_maintenance_settings[container_color]" class="wpm-color-field" data-default-color="#ffffff" /><br />
                <?php _e('Opacity:', 'wp-maintenance'); ?>
                <input type="text" class="wpm-form-field" size="3" name="wp_maintenance_settings[container_opacity]" value="<?php if( isset($paramMMode['container_opacity']) && $paramMMode['container_opacity']!='' ) { echo $paramMMode['container_opacity']; } else { echo '0.5'; } ?>" />
               <?php _e('Width:', 'wp-maintenance'); ?>
                <input type="text" class="wpm-form-field" size="2" name="wp_maintenance_settings[container_width]" value="<?php if( isset($paramMMode['container_width']) && $paramMMode['container_width']!='' ) { echo $paramMMode['container_width']; } else { echo '80'; } ?>" />%
            </div>
            <!-- FIN CADRE -->
            
            <div style="margin-top:15px;margin-bottom:15px;"><hr /></div>
            <a name="countdown"></a>
            <!-- COMPTE A REBOURS -->
            <h3><?php _e('Choice countdown fonts and colors:', 'wp-maintenance'); ?></h3>
            <em><?php _e('Countdown text color:', 'wp-maintenance'); ?></em> <br /><input type="text" value="<?php if( isset($paramMMode['color_cpt']) && $paramMMode['color_cpt']!='' ) { echo $paramMMode['color_cpt']; } else { echo '#333333'; } ?>" name="wp_maintenance_settings[color_cpt]" class="wpm-color-field" data-default-color="#333333" /><br />
            <em><?php _e('Countdown background color:', 'wp-maintenance'); ?></em> <br /><input type="text" value="<?php if( isset($paramMMode['color_cpt_bg']) && $paramMMode['color_cpt_bg']!='' ) { echo $paramMMode['color_cpt_bg']; } ?>" name="wp_maintenance_settings[color_cpt_bg]" class="wpm-color-field" data-default-color="#ffffff" /><br /><br />
            
            <!-- POLICE DU COMPTEUR -->
            <em><?php _e('Countdown font settings', 'wp-maintenance'); ?></em>
            <table cellspacing="10">
                <tr>
                    <td valign="top" align="left">
                        <input name="wp_maintenance_settings[font_cpt]" class="selectfont" type="text" value="<?php if( isset($paramMMode['font_cpt']) &&  $paramMMode['font_cpt']!='' ) { echo str_replace(' ', '+', $paramMMode['font_cpt']); } else { echo 'Pacifico'; } ?>" />
                    </td>
                    <td>
                        <?php _e('Size:', 'wp-maintenance'); ?>
                        <input type="text" size="3" class="wpm-form-field" id="date_cpt_size" name="wp_maintenance_settings[date_cpt_size]" value="<?php if( isset($paramMMode['date_cpt_size']) && $paramMMode['date_cpt_size']!='' ) { echo trim($paramMMode['date_cpt_size']); } else { echo '16'; } ?>" />px

                    </td>
                </tr>
            </table>
            <!-- FIN POLICE DU COMPTEUR -->
            
            <!-- BOTTOM PAGE -->
            <h3><?php _e('Choice fonts and colors bottom page:', 'wp-maintenance'); ?></h3>
            <em><?php _e('Bottom color:', 'wp-maintenance'); ?></em> <br /><input type="text" value="<?php if( isset($paramMMode['color_bg_bottom']) && $paramMMode['color_bg_bottom']!='' ) { echo $paramMMode['color_bg_bottom']; } else { echo '#333333'; } ?>" name="wp_maintenance_settings[color_bg_bottom]" class="wpm-color-field" data-default-color="#333333" /> <br />
            <em><?php _e('Text bottom color:', 'wp-maintenance'); ?></em> <br /><input type="text" value="<?php if( isset($paramMMode['color_text_bottom']) && $paramMMode['color_text_bottom']!='' ) { echo $paramMMode['color_text_bottom']; } else { echo '#FFFFFF'; } ?>" name="wp_maintenance_settings[color_text_bottom]" class="wpm-color-field" data-default-color="#ffffff" /><br /><br />

            <!-- POLICE DU TEXTE BAS DE PAGE -->
            <em><?php _e('Text font settings', 'wp-maintenance'); ?></em>
            <table cellspacing="10">
                <tr>
                    <td valign="top" align="left">
                        <input name="wp_maintenance_settings[font_text_bottom]" class="selectfont" type="text" value="<?php if( isset($paramMMode['font_text_bottom']) && $paramMMode['font_text_bottom']!='' ) { echo str_replace(' ', '+', $paramMMode['font_text_bottom']); } else { echo 'PT+Sans'; } ?>" />
                    </td>
                    <td>
                        <?php _e('Size:', 'wp-maintenance'); ?>
                        <input type="text" class="wpm-form-field" size="3" name="wp_maintenance_settings[font_bottom_size]" value="<?php if( isset($paramMMode['font_bottom_size']) && $paramMMode['font_bottom_size']!='' ) { echo stripslashes($paramMMode['font_bottom_size']); } else { echo '12'; } ?>" />px

                    </td>
                </tr>
                <tr>
                    <td rowspan="2">
                        <input type="radio" name="wp_maintenance_settings[font_bottom_weigth]" value="normal" <?php if( (isset($paramMMode['font_bottom_weigth']) && $paramMMode['font_bottom_weigth']=='normal') || empty($paramMMode['font_bottom_weigth']) ) { echo 'checked'; } ?> >Normal
                        <input type="radio" name="wp_maintenance_settings[font_bottom_weigth]" value="bold" <?php if( isset($paramMMode['font_bottom_weigth']) && $paramMMode['font_bottom_weigth']=='bold') { echo 'checked'; } ?>>Bold
                        <input type="checkbox" name="wp_maintenance_settings[font_bottom_style]" value="italic" <?php if( isset($paramMMode['font_bottom_style']) && $paramMMode['font_bottom_style']=='italic') { echo 'checked'; } ?>>Italic
                    </td>
                </tr>
            </table>
            <!-- FIN POLICE DU TEXTE BAS DE PAGE -->
            
            <div style="margin-top:15px;margin-bottom:15px;"><hr /></div>
                
            <h3><?php _e('Choice form color:', 'wp-maintenance'); ?></h3>
            <?php 
                if ( 
                    is_plugin_active( 'wysija-newsletters/index.php' ) || is_plugin_active( 'mailchimp-for-wp/mailchimp-for-wp.php' )  
                    
                ) {
                    
                    if ( isset($paramMMode['newletter']) && $paramMMode['newletter']==1 ) {
                    //if( isset($paramMMode['code_newletter']) && (strpos($paramMMode['code_newletter'], 'wysija_form')!=false || strpos($paramMMode['code_newletter'], 'mc4wp_form')!=false ) && (
            ?>
            <!-- COULEUR WYJIYA -->
            <table cellspacing="10">
                <tr>
                    <td valign="top" align="left">
                        <input name="wp_maintenance_settings[newletter_font_text]" class="selectfont" type="text" value="<?php if( isset($paramMMode['newletter_font_text']) && $paramMMode['newletter_font_text']!='' ) { echo str_replace(' ', '+', $paramMMode['newletter_font_text']); } else { echo 'PT+Sans'; } ?>" />
                    </td>
                    <td>
                        <?php _e('Size:', 'wp-maintenance'); ?>
                        <input type="text" class="wpm-form-field" size="3" name="wp_maintenance_settings[newletter_size]" value="<?php if( isset($paramMMode['newletter_size']) && $paramMMode['newletter_size']!='') { echo stripslashes($paramMMode['newletter_size']); } else { echo '14'; } ?>" />px

                    </td>
                </tr>
                <tr>
                    <td rowspan="2">
                        <input type="radio" name="wp_maintenance_settings[newletter_font_weigth]" value="normal" <?php if( (isset($paramMMode['newletter_font_weigth']) && $paramMMode['newletter_font_weigth']=='normal') || empty($paramMMode['newletter_font_weigth']) ) { echo 'checked'; } ?> >Normal
                        <input type="radio" name="wp_maintenance_settings[newletter_font_weigth]" value="bold" <?php if( isset($paramMMode['newletter_font_weigth']) && $paramMMode['newletter_font_weigth']=='bold') { echo 'checked'; } ?>>Bold
                        <input type="checkbox" name="wp_maintenance_settings[newletter_font_style]" value="italic" <?php if( isset($paramMMode['newletter_font_style']) && $paramMMode['newletter_font_style']=='italic') { echo 'checked'; } ?>>Italic
                    </td>
                </tr>
            </table>  
            <br />
            <em><?php _e('Field text color:', 'wp-maintenance'); ?></em> <br />
            <input type="text" value="<?php if( isset($paramMMode['color_field_text']) && $paramMMode['color_field_text']!='' ) { echo $paramMMode['color_field_text']; } else { echo '#333333'; } ?>" name="wp_maintenance_settings[color_field_text]" class="wpm-color-field" data-default-color="#333333" /><br />
            <em><?php _e('Field border color:', 'wp-maintenance'); ?></em> <br />
            <input type="text" value="<?php if( isset($paramMMode['color_field_border']) && $paramMMode['color_field_border']!='' ) { echo $paramMMode['color_field_border']; } else { echo '#333333'; } ?>" name="wp_maintenance_settings[color_field_border]" class="wpm-color-field" data-default-color="#333333" /><br />
            <em><?php _e('Field background color:', 'wp-maintenance'); ?></em> <br />
            <input type="text" value="<?php if( isset($paramMMode['color_field_background']) && $paramMMode['color_field_background']!='' ) { echo $paramMMode['color_field_background']; } else { echo '#cccccc'; } ?>" name="wp_maintenance_settings[color_field_background]" class="wpm-color-field" data-default-color="#cccccc" />
            <br />
            <em><?php _e('Button text color:', 'wp-maintenance'); ?></em> <br />
            <input type="text" value="<?php if( isset($paramMMode['color_text_button']) && $paramMMode['color_text_button']!='' ) { echo $paramMMode['color_text_button']; } else { echo '#ffffff'; } ?>" name="wp_maintenance_settings[color_text_button]" class="wpm-color-field" data-default-color="#ffffff" />
            <br />
            <em><?php _e('Button color:', 'wp-maintenance'); ?></em> <br />
            <input type="text" value="<?php if( isset($paramMMode['color_button']) && $paramMMode['color_button']!='' ) { echo $paramMMode['color_button']; } else { echo '#1e73be'; } ?>" name="wp_maintenance_settings[color_button]" class="wpm-color-field" data-default-color="#1e73be" />
            <br />
            <em><?php _e('Button color hover:', 'wp-maintenance'); ?></em> <br />
            <input type="text" value="<?php if( isset($paramMMode['color_button_hover']) && $paramMMode['color_button_hover']!='' ) { echo $paramMMode['color_button_hover']; } else { echo '#ffffff'; }  ?>" name="wp_maintenance_settings[color_button_hover]" class="wpm-color-field" data-default-color="#ffffff" /><br />
            <em><?php _e('Button color onclick:', 'wp-maintenance'); ?></em> <br />
            <input type="text" value="<?php if( isset($paramMMode['color_button_onclick']) && $paramMMode['color_button_onclick']!=''  ) { echo $paramMMode['color_button_onclick']; } else { echo '#ffffff'; } ?>" name="wp_maintenance_settings[color_button_onclick]" class="wpm-color-field" data-default-color="#ffffff" />
            <?php
                    } else {
                        printf( __('Enable %s to customize the forms', 'wp-maintenance'), '<a href="'.admin_url().'?page=wp-maintenance#newsletter">'.__('newsletter option', 'wp-maintenance').'</a>' ); 
                    }
                    
                } else {
                    _e('Enable Mailpoet or MailChimp extensions to customize the forms', 'wp-maintenance'); 
                
                } ?>
                
            <p>
                <?php submit_button(); ?>
            </p>
               
        </div>
            
        <?php echo wpm_sidebar(); ?>
        
    </div>
    </form>
    <?php echo wpm_footer(); ?>
</div>