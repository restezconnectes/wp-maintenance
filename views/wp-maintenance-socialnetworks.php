<?php

defined( 'ABSPATH' ) or die( 'Not allowed' );

$messageUpdate = 0;

/* Update des paramètres */
if( isset($_POST['action']) && $_POST['action'] == 'update_footer' && wp_verify_nonce($_POST['security-socialn'], 'valid-socialn') ) {
   
    if( isset($_POST["wp_maintenance_social_options"]['reset']) && $_POST["wp_maintenance_social_options"]['reset'] ==1 ) {
        unset($_POST["wp_maintenance_social"]);
        $_POST["wp_maintenance_social"] = '';
    }
    if( empty($_POST["wp_maintenance_social_options"]["enable"]) ) { $_POST["wp_maintenance_social_options"]["enable"] = 0; }

    //var_dump($_POST["wp_maintenance_social"]);
    $listSocial = $_POST["wp_maintenance_social"];
    $listInsertSocial = array();
    foreach($listSocial as $title => $value ) {
        $find_social_url = strpos($value, $title);
        if ($find_social_url === false) {

        } else {
            $listInsertSocial[$title] = strip_tags( stripslashes( esc_url_raw($value) ) );
        }
    }

    update_option('wp_maintenance_social', $listInsertSocial);
    update_option('wp_maintenance_social_options', esc_html($_POST["wp_maintenance_social_options"]));

    $messageUpdate = 1;
}

// Récupère les Reseaux Sociaux
$paramSocial = get_option('wp_maintenance_social');
if(get_option('wp_maintenance_social_options')) { extract(get_option('wp_maintenance_social_options')); }
$paramSocialOption = get_option('wp_maintenance_social_options');

?>
<style>
    .sortable { list-style-type: none; margin: 0; padding: 0; width: 100%; }
    .sortable li { padding: 0.4em; height: 40px;cursor: pointer; cursor: move;  }
    .sortable li span { font-size: 15px;margin-right: 0.8em;cursor: move; }
    .sortable li:hover { background-color: #d2d2d2; }
    .CodeMirror {border: 1px solid #eee;height: auto;}
</style>
<div class="wrap">

    <!-- HEADER -->
    <h2 class="headerpage"><?php _e('WP Maintenance - Settings', 'wp-maintenance'); ?> <sup>v.<?php _e(WPM_VERSION); ?></sup></h2>
    <?php if( isset($message) && $message == 1 ) { ?>
        <div id="message" class="updated fade"><p><strong><?php _e('Options saved.', 'wp-maintenance'); ?></strong></p></div>
    <?php } ?>
    <!-- END HEADER -->

    <div class="wp-maintenance-wrapper">

        <?php echo wpm_get_nav2(); ?>

        <div class="wp-maintenance-tab-content wp-maintenance-tab-content-welcome" id="wp-maintenance-tab-content">

            <form method="post" action="" id="valide_settings" name="valide_settings">
                <input type="hidden" name="action" value="update_footer" />
                <?php wp_nonce_field('valid-socialn', 'security-socialn'); ?>
                
                <!-- LINK TO LOGIN -->
                <div class="wp-maintenance-module-options-block">
                
                    <div class="wp-maintenance-settings-section-header">
                        <h3 class="wp-maintenance-settings-section-title" id="module-import_export"><?php _e('Enable social networks', 'wp-maintenance'); ?></h3>
                    </div>

                    <p>
                        <label class="wp-maintenance-container"><span class="wp-maintenance-label-text"><?php _e('Yes, enable social networks options', 'wp-maintenance'); ?></span>
                            <input type="checkbox" name="wp_maintenance_social_options[enable]" value="1" <?php if( isset($paramSocialOption['enable']) && $paramSocialOption['enable']==1) { echo ' checked'; } ?>>
                            <span class="wp-maintenance-checkmark"></span>
                        </label>
                    </p>
                        
                    <p class="submit"><button type="submit" name="footer_submit" id="footer_submit" class="wp-maintenance-button wp-maintenance-button-primary"><?php _e('Save', 'wp-maintenance'); ?></button></p>
                </div>

                <div class="wp-maintenance-module-options-block">
                    <div class="wp-maintenance-settings-section-header">
                        <h3 class="wp-maintenance-settings-section-title" id="module-import_export"><?php _e('List of Social networks', 'wp-maintenance'); ?></h3>
                    </div>

                    <div class="wp-maintenance-setting-row">
                        <label for="wp_maintenance_social_options[texte]" class="wp-maintenance-setting-row-title"><?php _e('Enter text for the title icons', 'wp-maintenance'); ?></label>
                        <input type="text" name="wp_maintenance_social_options[texte]" value="<?php if( empty($paramSocialOption['texte']) ) { _e('Follow me on', 'wp-maintenance'); } else { echo esc_html(stripslashes($paramSocialOption['texte'])); } ?>" />
                    </div>

                    <div class="wp-maintenance-setting-row">
                        <label class="wp-maintenance-setting-row-title"><?php _e('Drad and drop the lines to put in the order you want', 'wp-maintenance'); ?></label>
                        <ul class="sortable">
                        <?php 
                                $wpmTabSocial = array('facebook', 'twitter', 'linkedin', 'flickr', 'youtube', 'pinterest', 'vimeo', 'instagram', 'about_me', 'soundcloud', 'skype', 'tumblr', 'blogger', 'paypal');
                                if( isset($paramSocialOption['style']) ) {
                                    $styleIcons = $paramSocialOption['style'];
                                } else {
                                    $styleIcons = 'style1';
                                }
                            
                                foreach ($wpmTabSocial as &$iconSocial) {
                                    
                                    $linkIcon = WPM_ICONS_URL.'not-found.png';
                                    if( file_exists(WPM_DIR.'socialicons/'.$styleIcons.'/32/'.$iconSocial.'.png') ) {
                                        $linkIcon = WPM_ICONS_URL.''.$styleIcons.'/32/'.$iconSocial.'.png';
                                    }
                                
                                    $entryValue = '';
                                    if( isset($paramSocial[$iconSocial]) ) { $entryValue = $paramSocial[$iconSocial]; }
                                    echo '<li><span>::</span><img src="'.esc_url($linkIcon).'" valign="middle" hspace="3" name="'.$iconSocial.'.png" title="'.$iconSocial.'.png"/>'.ucfirst($iconSocial).' <input type="text" size="50" name="wp_maintenance_social['.$iconSocial.']" value="'.esc_url($entryValue).'" onclick="select()" ><br />';
                                }

                        ?>
                        </ul>
                    </div>
                    <p class="submit"><button type="submit" name="footer_submit" id="footer_submit" class="wp-maintenance-button wp-maintenance-button-primary"><?php _e('Save', 'wp-maintenance'); ?></button></p>
                </div>

                <div class="wp-maintenance-module-options-block">
                    <div class="wp-maintenance-settings-section-header">
                        <h3 class="wp-maintenance-settings-section-title" id="module-import_export"><?php _e('Social Networks Style', 'wp-maintenance'); ?></h3>
                    </div>
                    <div class="wp-maintenance-setting-row">
                        <label for="wp_maintenance_social_options[size]" class="wp-maintenance-setting-row-title"><?php _e('Choose icons size', 'wp-maintenance'); ?></label>
                        <select name="wp_maintenance_social_options[size]" >
                        <?php 
                            $wpm_tabIcon = array(32, 64, 128, 256, 512);
                            foreach($wpm_tabIcon as $wpm_icon) {
                                if($paramSocialOption['size']==$wpm_icon) { $selected = ' selected'; } else { $selected = ''; }
                                echo '<option value="'.esc_url($wpm_icon).'" '.$selected.'>'.$wpm_icon.'</option>';
                            }
                        ?>
                        </select>
                    </div>

                    <div class="wp-maintenance-setting-row">
                        <label for="wp_maintenance_social_options[style]" class="wp-maintenance-setting-row-title"><?php _e('Choose icons style', 'wp-maintenance'); ?></label>
                        <ul id="pattern">
                            <?php
                                //if( empty($paramSocialOption['style']) ) { $paramSocialOption['style'] == 'style1'; }
                                for ($ico = 1; $ico <= 6; $ico++) {                                
                                    if( isset($paramSocialOption['style']) && $paramSocialOption['style'] == 'style'.$ico ) { $selected = ' checked'; } else { $selected = ''; } 
                            ?>
                                <li>
                                    <div style="width:64px;height:64px;border:2px solid #ECF0F1;background:url('<?php echo WPM_ICONS_URL.'style'.$ico.'/64/facebook.png'; ?>');margin-bottom:5px;"></div>
                                    <label for="style<?php echo $ico; ?>" class="wpm-container" style="padding-left: 24px!important;margin-right: 0!important;"><input type="radio" value="style<?php echo $ico; ?>" <?php echo $selected; ?> name="wp_maintenance_social_options[style]" id="style<?php echo $ico; ?>" /><span class="wpm-checkmark"></span></label>
                                </li>
                            <?php } ?>
                        </ul>
                    </div>

                    <div class="wp-maintenance-setting-row">
                        <label for="wp_maintenance_social_options[position]" class="wp-maintenance-setting-row-title"><?php _e('Position', 'wp-maintenance'); ?></label>
                        <select name="wp_maintenance_social_options[position]" >
                            <option value="top"<?php if( isset($paramSocialOption['position']) && $paramSocialOption['position']=='top') { echo ' selected'; } ?>><?php _e('Top', 'wp-maintenance'); ?></option>
                            <option value="bottom"<?php if( empty($paramSocialOption['position']) or (isset($paramSocialOption['position']) && $paramSocialOption['position']=='bottom') ) { echo ' selected'; } ?>><?php _e('Bottom', 'wp-maintenance'); ?></option>
                        </select>
                    </div>

                    <div class="wp-maintenance-setting-row">
                        <label for="wp_maintenance_social_options[align]" class="wp-maintenance-setting-row-title"><?php _e('Align', 'wp-maintenance'); ?></label>
                        <select name="wp_maintenance_social_options[align]" class="wpm-form-field">
                            <option value="left"<?php if(isset($paramSocialOption['align']) && $paramSocialOption['align']=='left') { echo ' selected'; } ?>><?php _e('Left', 'wp-maintenance'); ?></option>
                            <option value="center"<?php if( empty($paramSocialOption['align']) or ( isset($paramSocialOption['align']) && $paramSocialOption['align']=='center') ) { echo ' selected'; } ?>><?php _e('Center', 'wp-maintenance'); ?></option>
                            <option value="right"<?php if( isset($paramSocialOption['align']) && $paramSocialOption['align']=='right') { echo ' selected'; } ?>><?php _e('Right', 'wp-maintenance'); ?></option>
                        </select>
                    </div>

                    <div class="wp-maintenance-setting-row">
                        <label for="wp_maintenance_social_options[theme]" class="wp-maintenance-setting-row-title"><?php _e('You have your own icons? Enter the folder name of your theme here', 'wp-maintenance'); ?></label>
                        <strong><?php echo get_stylesheet_directory_uri(); ?>/</strong><input type="text" value="<?php if( isset($paramSocialOption['theme']) && $paramSocialOption['theme']!='' ) { echo esc_html($paramSocialOption['theme']); } ?>" name="wp_maintenance_social_options[theme]" /><strong>/facebook.png</strong><br />
                        <p><i><?php _e("In your icon's folder child theme, you must have the same names like mine, let's mouse over list icons for display picture's name. For example: ", 'wp-maintenance'); ?>'facebook.png'.</i></p><br />
                        <?php if( isset($paramSocialOption['theme']) && $paramSocialOption['theme']!='' ) { ?>
                            <label for="wp_maintenance_social_options[position]" class="wp-maintenance-setting-row-title"><?php _e('You use this picture:', 'wp-maintenance'); ?></label><br />
                            <img src=" <?php echo esc_url(get_stylesheet_directory_uri().'/'.$paramSocialOption['theme'].'/facebook.png'); ?>" width="64"/>
                        <?php } ?>
                    </div>

                    <p class="submit"><button type="submit" name="footer_submit" id="footer_submit" class="wp-maintenance-button wp-maintenance-button-primary"><?php _e('Save', 'wp-maintenance'); ?></button></p>
                </div>

                <div class="wp-maintenance-module-options-block">
                    <div class="wp-maintenance-settings-section-header">
                        <h3 class="wp-maintenance-settings-section-title" id="module-import_export"><?php _e('Reset Social Networks Icons Options', 'wp-maintenance'); ?></h3>
                    </div>
                    <p>
                        <label class="wp-maintenance-container"><span class="wp-maintenance-label-text"><?php _e('Yes, reset Social Networks Icons?', 'wp-maintenance'); ?></span>
                            <input type="checkbox" name="wp_maintenance_social_options[reset]" value="1">
                            <span class="wp-maintenance-checkmark"></span>
                        </label>
                    </p>
                    <p class="submit"><button type="submit" name="footer_submit" id="footer_submit" class="wp-maintenance-button wp-maintenance-button-primary"><?php _e('Save', 'wp-maintenance'); ?></button></p>

                </div>
            </form>
        </div>

    </div>

    <?php echo wpm_footer(); ?>
</div>
<script> jQuery('.sortable').sortable(); </script>