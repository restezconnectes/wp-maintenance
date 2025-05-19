<?php

defined( 'ABSPATH' ) or die( 'Not allowed' );

$messageUpdate = 0;

/* Update des paramètres */
if( isset($_POST['action']) && $_POST['action'] == 'update_socialnetworks' && wp_verify_nonce($_POST['security-socialn'], 'valid-socialn') ) {

    if( isset($_POST["wpso"]['reset']) && $_POST["wpso"]['reset'] ==1 ) {
        unset($_POST["wp_maintenance_list_socialnetworks"]);
        $_POST["wp_maintenance_list_socialnetworks"] = '';
    }
    if( empty($_POST["wpso"]["enable"]) ) { $_POST["wpso"]["enable"] = 0; }

    $updateSetting = wpm_update_settings( $_POST["wp_maintenance_list_socialnetworks"], 'wp_maintenance_list_socialnetworks', 3 );
    $updateSetting = wpm_update_settings( $_POST["wpso"], 'wp_maintenance_settings_socialnetworks' );
    if( $updateSetting == true ) { $messageUpdate = 1; }

}

// Récupère les Reseaux Sociaux
$paramSocial = get_option('wp_maintenance_list_socialnetworks');
if(get_option('wp_maintenance_settings_socialnetworks')) { extract(get_option('wp_maintenance_settings_socialnetworks')); }
$paramSocialOption = get_option('wp_maintenance_settings_socialnetworks');
if( array_key_exists('tiktok', $paramSocial) ) {
    
} else {
    $paramSocial = array_merge($paramSocial, array('tiktok' => ''));
}
?>
<script>
  jQuery( function() {
    jQuery( "#sortable" ).sortable({
        cursor: "move",
        placeholder: "highlight",
    });
  } );
</script>
<style>
    .sortable { list-style-type: none; margin: 0; padding: 0; width: 100%; }
    .sortable li { padding: 0.4em; height: 40px;cursor: move;height: 25px;  }
    .sortable li span { font-size: 15px;margin-right: 0.8em;cursor: move;height: 25px; }
    .sortable li:hover { background-color: #d2d2d2;height: 25px; }
    .highlight {border: 1px solid #848838;font-weight: bold;font-size: 45px;background-color: #848838;height: 25px;}
    .CodeMirror {border: 1px solid #eee;height: auto;}
</style>
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
                <input type="hidden" name="action" value="update_socialnetworks" />
                <?php wp_nonce_field('valid-socialn', 'security-socialn'); ?>
                
                <!-- LINK TO LOGIN -->
                <div class="wp-maintenance-module-options-block">
                
                    <div class="wp-maintenance-settings-section-header">
                        <h3 class="wp-maintenance-settings-section-title" id="module-import_export"><?php esc_html_e('Enable social networks', 'wp-maintenance'); ?></h3>
                    </div>

                    <p>
                        <label class="wp-maintenance-container"><span class="wp-maintenance-label-text"><?php esc_html_e('Yes, enable social networks options', 'wp-maintenance'); ?></span>
                            <input type="checkbox" name="wpso[enable]" value="1" <?php if( isset($paramSocialOption['enable']) && $paramSocialOption['enable']==1) { echo ' checked'; } ?>>
                            <span class="wp-maintenance-checkmark"></span>
                        </label>
                    </p>
                        
                    <p class="submit"><button type="submit" name="footer_submit" id="footer_submit" class="wp-maintenance-button wp-maintenance-button-primary"><?php esc_html_e('Save', 'wp-maintenance'); ?></button></p>
                </div>

                <div class="wp-maintenance-module-options-block">
                    <div class="wp-maintenance-settings-section-header">
                        <h3 class="wp-maintenance-settings-section-title" id="module-import_export"><?php esc_html_e('List of Social networks', 'wp-maintenance'); ?></h3>
                    </div>

                    <div class="wp-maintenance-setting-row">
                        <label for="wpso[texte]" class="wp-maintenance-setting-row-title"><?php esc_html_e('Enter text for the title icons', 'wp-maintenance'); ?></label>
                        <input type="text" name="wpso[texte]" value="<?php if( empty($paramSocialOption['texte']) ) { esc_html_e('Follow me on', 'wp-maintenance'); } else { echo esc_html(stripslashes($paramSocialOption['texte'])); } ?>" />
                    </div>

                    <div class="wp-maintenance-setting-row">
                        <label class="wp-maintenance-setting-row-title"><?php esc_html_e('Drad and drop the lines to put in the order you want', 'wp-maintenance'); ?></label>
                        <ul id="sortable">
                        <?php 

                                if( isset($paramSocialOption['style']) ) {
                                    $styleIcons = $paramSocialOption['style'];
                                } else {
                                    $styleIcons = 'style1';
                                }

                                foreach($paramSocial as $nameSocial => $valueSocial) {

                                    $linkIcon = WPM_ICONS_URL.'not-found.png';
                                    if( file_exists(WPM_DIR.'socialicons/'.$styleIcons.'/32/'.$nameSocial.'.png') ) {
                                        $linkIcon = WPM_ICONS_URL.''.$styleIcons.'/32/'.$nameSocial.'.png';
                                    }
                                
                                    $entryValue = '';
                                    if( isset($paramSocial[$nameSocial]) ) { 
                                        if( $nameSocial == 'email' && ( isset($paramSocial['email']) && $paramSocial['email'] != '') ) { 
                                            $entryValue = esc_html($paramSocial[$nameSocial]); 
                                        } else {
                                            $entryValue = esc_url($paramSocial[$nameSocial]);
                                        }
                                    }

                                    //echo ''.$nameSocial.' => '.$valueSocial.'<br />';
                                    echo '<li><span style="font-size: large;font-weight: bold;padding: 0.5em;">::</span><img src="'.esc_url($linkIcon).'" valign="middle" hspace="3" name="'.esc_html($nameSocial).'.png" title="'.esc_html($nameSocial).'.png"/>'.esc_html(ucfirst($nameSocial)).' <input type="text" size="50" name="wp_maintenance_list_socialnetworks['.esc_html($nameSocial).']" value="'.esc_url($entryValue).'" onclick="select()" ><br />';

                                }

                        ?>
                        </ul>
                    </div>
                    <p class="submit"><button type="submit" name="footer_submit" id="footer_submit" class="wp-maintenance-button wp-maintenance-button-primary"><?php esc_html_e('Save', 'wp-maintenance'); ?></button></p>
                </div>

                <div class="wp-maintenance-module-options-block">
                    <div class="wp-maintenance-settings-section-header">
                        <h3 class="wp-maintenance-settings-section-title" id="module-import_export"><?php esc_html_e('Social Networks Style', 'wp-maintenance'); ?></h3>
                    </div>
                    <div class="wp-maintenance-setting-row">
                        <label for="wpso[size]" class="wp-maintenance-setting-row-title"><?php esc_html_e('Choose icons size', 'wp-maintenance'); ?></label>
                        <select name="wpso[size]" >
                        <?php 
                            $wpm_tabIcon = array(32, 64, 128, 256, 512);
                            foreach($wpm_tabIcon as $wpm_icon) {
                                if($paramSocialOption['size']==$wpm_icon) { $selected = ' selected'; } else { $selected = ''; }
                                echo '<option value="'.esc_html($wpm_icon).'" '.esc_html($selected).'>'.esc_html($wpm_icon).'</option>';
                            }
                        ?>
                        </select>
                    </div>

                    <div class="wp-maintenance-setting-row">
                        <label for="wpso[style]" class="wp-maintenance-setting-row-title"><?php esc_html_e('Choose icons style', 'wp-maintenance'); ?></label>
                        <ul id="pattern">
                            <?php
                                //if( empty($paramSocialOption['style']) ) { $paramSocialOption['style'] == 'style1'; }
                                for ($ico = 1; $ico <= 6; $ico++) {                                
                                    if( isset($paramSocialOption['style']) && $paramSocialOption['style'] == 'style'.esc_html($ico) ) { $selected = ' checked'; } else { $selected = ''; } 
                            ?>
                                <li>
                                    <div style="width:64px;height:64px;border:2px solid #ECF0F1;background:url('<?php echo esc_url(WPM_ICONS_URL.'style'.esc_html($ico).'/64/facebook.png'); ?>');margin-bottom:5px;"></div>
                                    <label for="style<?php echo esc_html($ico); ?>" class="wpm-container" style="padding-left: 24px!important;margin-right: 0!important;"><input type="radio" value="style<?php echo esc_html($ico); ?>" <?php echo esc_html($selected); ?> name="wpso[style]" id="style<?php echo esc_html($ico); ?>" /><span class="wpm-checkmark"></span></label>
                                </li>
                            <?php } ?>
                        </ul>
                    </div>

                    <div class="wp-maintenance-setting-row">
                        <label for="wpso[position]" class="wp-maintenance-setting-row-title"><?php esc_html_e('Position', 'wp-maintenance'); ?></label>
                        <select name="wpso[position]" >
                            <option value="top"<?php if( isset($paramSocialOption['position']) && $paramSocialOption['position']=='top') { echo ' selected'; } ?>><?php esc_html_e('Top', 'wp-maintenance'); ?></option>
                            <option value="bottom"<?php if( empty($paramSocialOption['position']) or (isset($paramSocialOption['position']) && $paramSocialOption['position']=='bottom') ) { echo ' selected'; } ?>><?php esc_html_e('Bottom', 'wp-maintenance'); ?></option>
                        </select>
                    </div>

                    <div class="wp-maintenance-setting-row">
                        <label for="wpso[align]" class="wp-maintenance-setting-row-title"><?php esc_html_e('Align', 'wp-maintenance'); ?></label>
                        <select name="wpso[align]" class="wpm-form-field">
                            <option value="left"<?php if(isset($paramSocialOption['align']) && $paramSocialOption['align']=='left') { echo ' selected'; } ?>><?php esc_html_e('Left', 'wp-maintenance'); ?></option>
                            <option value="center"<?php if( empty($paramSocialOption['align']) or ( isset($paramSocialOption['align']) && $paramSocialOption['align']=='center') ) { echo ' selected'; } ?>><?php esc_html_e('Center', 'wp-maintenance'); ?></option>
                            <option value="right"<?php if( isset($paramSocialOption['align']) && $paramSocialOption['align']=='right') { echo ' selected'; } ?>><?php esc_html_e('Right', 'wp-maintenance'); ?></option>
                        </select>
                    </div>

                    <div class="wp-maintenance-setting-row">
                        <label for="wpso[theme]" class="wp-maintenance-setting-row-title"><?php esc_html_e('You have your own icons? Enter the folder name of your theme here', 'wp-maintenance'); ?></label>
                        <strong><?php echo esc_url(get_stylesheet_directory_uri()); ?>/</strong><input type="text" value="<?php if( isset($paramSocialOption['theme']) && $paramSocialOption['theme']!='' ) { echo esc_html($paramSocialOption['theme']); } ?>" name="wpso[theme]" /><strong>/facebook.png</strong><br />
                        <p><i><?php esc_html_e("In your icon's folder child theme, you must have the same names like mine, let's mouse over list icons for display picture's name. For example: ", 'wp-maintenance'); ?>'facebook.png'.</i></p><br />
                        <?php if( isset($paramSocialOption['theme']) && $paramSocialOption['theme']!='' ) { ?>
                            <label class="wp-maintenance-setting-row-title"><?php esc_html_e('You use this picture:', 'wp-maintenance'); ?></label><br />
                            <img src=" <?php echo esc_url(get_stylesheet_directory_uri().'/'.$paramSocialOption['theme'].'/facebook.png'); ?>" width="64"/>
                        <?php } ?>
                    </div>

                    <p class="submit"><button type="submit" name="footer_submit" id="footer_submit" class="wp-maintenance-button wp-maintenance-button-primary"><?php esc_html_e('Save', 'wp-maintenance'); ?></button></p>
                </div>

                <div class="wp-maintenance-module-options-block">
                    <div class="wp-maintenance-settings-section-header">
                        <h3 class="wp-maintenance-settings-section-title" id="module-import_export"><?php esc_html_e('Reset Social Networks Icons Options', 'wp-maintenance'); ?></h3>
                    </div>
                    <p>
                        <label class="wp-maintenance-container"><span class="wp-maintenance-label-text"><?php esc_html_e('Yes, reset Social Networks Icons?', 'wp-maintenance'); ?></span>
                            <input type="checkbox" name="wpso[reset]" value="1">
                            <span class="wp-maintenance-checkmark"></span>
                        </label>
                    </p>
                    <p class="submit"><button type="submit" name="footer_submit" id="footer_submit" class="wp-maintenance-button wp-maintenance-button-primary"><?php esc_html_e('Save', 'wp-maintenance'); ?></button></p>

                </div>
            </form>
        </div>

    </div>

    <?php echo wp_kses(wpm_footer(), wpm_autorizeHtml()); ?>
    
</div>