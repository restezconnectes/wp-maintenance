<?php

defined( 'ABSPATH' ) or die( 'Not allowed' );

$messageUpdate = 0;
/* Update des paramètres */
if( isset($_POST['action']) && $_POST['action'] == 'update_general' && wp_verify_nonce($_POST['security-general'], 'valid-general') ) {

    if( isset($_POST["wp_maintenance_active"]) && $_POST["wp_maintenance_active"] == 1 ) { $wp_maintenance_active = 1; } else { $wp_maintenance_active = 0; }
    if( empty($_POST["wp_maintenance_settings"]["newletter"]) ) { $_POST["wp_maintenance_settings"]["newletter"] = 0; }
    update_option('wp_maintenance_active', $wp_maintenance_active);
    $options_saved = wpm_update_settings($_POST["wp_maintenance_settings"]);

    $messageUpdate = 1;
}

// Récupère les paramètres sauvegardés
if(get_option('wp_maintenance_settings')) { extract(get_option('wp_maintenance_settings')); }
$paramMMode = get_option('wp_maintenance_settings');

// Récupère si le status est actif ou non 
$statusActive = get_option('wp_maintenance_active');



?>

<div class="wrap">

    <!-- HEADER -->
    <?php echo wpm_get_header( __('General', 'wp-maintenance'), 'dashicons-admin-settings', $messageUpdate ) ?>
    <!-- END HEADER -->

    <div class="wp-maintenance-wrapper wp-maintenance-flex wp-maintenance-flex-top">

        <?php echo wpm_get_nav(); ?>

        <div class="wp-maintenance-tab-content wp-maintenance-tab-content-welcome" id="wp-maintenance-tab-content">
            
            <div class="wp-maintenance-tab-content-header"><i class="dashicons dashicons-admin-settings" style="margin-right: 10px;height:50px;width:50px;font-size:50px;padding: 8px 8px 14px 10px;border-radius: 5px;display: inline;float:left;"></i>  <h2 class="wp-maintenance-tc-title"><?php _e('Dashboard', 'wp-maintenance'); ?></h2></div>

            <div class="wp-maintenance-module-options-block" id="block-advanced_options" data-module="welcome">
                
                <div class="wp-maintenance-settings-section-header"><h3 class="wp-maintenance-settings-section-title" id="module-import_export"><?php _e('Activate maintenance mode', 'wp-maintenance'); ?></h3></div>

                <form method="post" action="" id="valide_settings" name="valide_settings">
                    <input type="hidden" name="action" value="update_general" />
                    <?php wp_nonce_field('valid-general', 'security-general'); ?>
                    
                    <!-- ACTIVER WP MAINTENANCE -->
                    <p class="wp-maintenance-fieldset-item ">
                        <label class="wp-maintenance-container"><span class="wp-maintenance-label-text"><?php _e('Yes, enable maintenance mode', 'wp-maintenance'); ?></span>
                            <input type="checkbox" name="wp_maintenance_active" value="1" <?php if( isset($statusActive) && $statusActive==1) { echo ' checked'; } ?>>
                            <span class="wp-maintenance-checkmark"></span>
                        </label>
                    </p>
                    <p class="submit"><button type="submit" name="footer_submit" id="footer_submit" class="wp-maintenance-button wp-maintenance-button-primary"><?php _e('Save', 'wp-maintenance'); ?></button></p>

                    <!-- TITRE ET TEXTE  -->
                    <div class="wp-maintenance-settings-section-header"><h3 class="wp-maintenance-settings-section-title" id="module-import_export"><?php _e('Title and text', 'wp-maintenance'); ?></h3></div>
                    <input type="text" size="100%" class="wp-maintenance-input" name="wp_maintenance_settings[titre_maintenance]" value="<?php if( isset($paramMMode['titre_maintenance']) && $paramMMode['titre_maintenance']!='' ) { echo esc_html(stripslashes($paramMMode['titre_maintenance'])); } ?>" /><br />
                    <?php 
                        $settingsTextmaintenance =   array(
                            'wpautop' => true, // use wpautop?
                            'media_buttons' => false, // show insert/upload button(s)
                            'textarea_name' => 'wp_maintenance_settings[text_maintenance]', // set the textarea name to something different, square brackets [] can be used here
                            'textarea_rows' => get_option('default_post_edit_rows', 10), // rows="..."
                            'tabindex' => '',
                            'editor_css' => '', //  extra styles for both visual and HTML editors buttons, 
                            'editor_class' => 'wpm-textmaintenance', // add extra class(es) to the editor textarea
                            'teeny' => true, // output the minimal editor config used in Press This
                            'dfw' => false, // replace the default fullscreen with DFW (supported on the front-end in WordPress 3.4)
                            'tinymce' => true, // load TinyMCE, can be used to pass settings directly to TinyMCE using an array()
                            'quicktags' => true // load Quicktags, can be used to pass settings directly to Quicktags using an array()
                        );
                    $textWpm = '';
                    if( isset($paramMMode['text_maintenance']) && $paramMMode['text_maintenance']!='' ) { $textWpm = stripslashes($paramMMode['text_maintenance']); }
                    ?>
                    <?php wp_editor( nl2br($textWpm), 'wpm-textmaintenance', $settingsTextmaintenance ); ?>
                    <p class="submit"><button type="submit" name="footer_submit" id="footer_submit" class="wp-maintenance-button wp-maintenance-button-primary"><?php _e('Save', 'wp-maintenance'); ?></button></p>

                    <!-- Encart Newletter -->
                    <div class="wp-maintenance-settings-section-header"><h3 class="wp-maintenance-settings-section-title" id="module-import_export"><?php _e('Activate newsletter block', 'wp-maintenance'); ?></h3></div>
                    <p class="wp-maintenance-fieldset-item ">
                        <label class="wp-maintenance-container"><span class="wp-maintenance-label-text"><?php _e('Yes, enable newsletter block', 'wp-maintenance'); ?></span>
                            <input type="checkbox" name="wp_maintenance_settings[newletter]" value="1" <?php if( isset($paramMMode['newletter']) && $paramMMode['newletter']==1 ) { echo ' checked'; } ?>>
                            <span class="wp-maintenance-checkmark"></span>
                        </label>
                    </p>

                    <div class="wp-maintenance-setting-row">
                        <label for="wp_maintenance_settings[title_newletter]" class="wp-maintenance-setting-row-title"><?php _e('Enter title for the newletter block', 'wp-maintenance'); ?></label>
                        <input type="text" name="wp_maintenance_settings[title_newletter]" class="wp-maintenance-input" size="60%" value="<?php if( isset($paramMMode['title_newletter']) && $paramMMode['title_newletter']!='' ) { echo esc_html(stripslashes(trim($paramMMode['title_newletter']))); } ?>" />
                    </div>

                    <div class="wp-maintenance-setting-row">
                        <label for="wp_maintenance_settings[type-newletter]" class="wp-maintenance-setting-row-title"><?php _e('Type of the newletter block', 'wp-maintenance'); ?></label>
                        <label class="wpm-container"><input type="radio" name="wp_maintenance_settings[type_newletter]" size="60%" value="shortcode" <?php if( isset($paramMMode['type_newletter']) && $paramMMode['type_newletter']=='shortcode' ) { echo 'checked'; } if( empty($paramMMode['type_newletter']) ) { echo 'checked'; } ?>  /><?php _e('Enter your newletter shortcode here:', 'wp-maintenance'); ?><span class="wpm-checkmark"></span></label><br /><br />
                        <input type="text" name="wp_maintenance_settings[code_newletter]" size="60%" class="wp-maintenance-input" value='<?php if( isset($paramMMode['code_newletter']) && $paramMMode['code_newletter']!='' ) { echo esc_attr(stripslashes(trim($paramMMode['code_newletter']))); } ?>' onclick="select()" /><br /><br />
                        <label class="wpm-container"><input type="radio" name="wp_maintenance_settings[type_newletter]" value="iframe" <?php if( isset($paramMMode['type_newletter']) && $paramMMode['type_newletter']=='iframe' ) { echo 'checked'; } ?>/> <?php _e('Or enter your newletter iframe code here:', 'wp-maintenance'); ?><span class="wpm-checkmark"></span></label><br /><br />
                        <textarea id="iframe_newletter" cols="60" rows="10" class="wp-maintenance-input" name="wp_maintenance_settings[iframe_newletter]"><?php if( isset($paramMMode['iframe_newletter']) && $paramMMode['iframe_newletter']!='' ) { echo esc_attr(stripslashes(trim($paramMMode['iframe_newletter']))); } ?></textarea> 
                    </div>
                    

                    <p class="submit"><button type="submit" name="footer_submit" id="footer_submit" class="wp-maintenance-button wp-maintenance-button-primary"><?php _e('Save', 'wp-maintenance'); ?></button></p>

                </form>

            </div>
        </div>

        <!--<div class="wp-maintenance-sideads">
            
            <div class="wp-maintenance-bordered wp-maintenance-mail-ad">
                <div class="wp-maintenance-ad-header wp-maintenance-flex">
                    <span><i class="dashicons dashicons-plus-alt wp-maintenance-primary" aria-hidden="true"></i></span>
                    <p>Site piraté&nbsp;?</p>
                </div>
                <div class="wp-maintenance-ad-content-padded wp-maintenance-ad-content">pub ici</div>   
            </div>
            <p></p>
            </hr>
            <p></p>
        </div>-->

    </div>
   
    <?php echo wpm_footer(); ?>

</div>
<script type="text/javascript">

    jQuery("select.image-picker").imagepicker({
      hide_select:  false,
    });

</script>
