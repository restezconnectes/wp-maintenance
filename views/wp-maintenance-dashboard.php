<?php

defined( 'ABSPATH' ) or die( 'Not allowed' );

$messageUpdate = 0;
/* Update des paramètres */
if( isset($_POST['action']) && $_POST['action'] == 'update_general' && wp_verify_nonce($_POST['security-general'], 'valid-general') ) {

    if( isset($_POST["wp_maintenance_active"]) && $_POST["wp_maintenance_active"] == 1 ) { $wp_maintenance_active = 1; } else { $wp_maintenance_active = 0; }
    if( empty($_POST["wpsettings"]["newletter"]) ) { $_POST["wpsettings"]["newletter"] = 0; }
    update_option('wp_maintenance_active', $wp_maintenance_active);

    $updateSetting = wpm_update_settings( $_POST["wpsettings"], 'wp_maintenance_settings');
    if( $updateSetting == true ) { $messageUpdate = 1; }
}

// Récupère les paramètres sauvegardés
if(get_option('wp_maintenance_settings')) { extract(get_option('wp_maintenance_settings')); }
$paramsSettings = get_option('wp_maintenance_settings');

// Récupère si le status est actif ou non 
$statusActive = get_option('wp_maintenance_active');

?>
<style>
    .CodeMirror {
      border: 1px solid #eee;
      height: 50px;
    }
    
</style>
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
                <input type="hidden" name="action" value="update_general" />
                <?php wp_nonce_field('valid-general', 'security-general'); ?>

                <!-- ACTIVER WP MAINTENANCE -->
                <div class="wp-maintenance-module-options-block">
                
                    <div class="wp-maintenance-settings-section-header"><h3 class="wp-maintenance-settings-section-title" ><?php esc_html_e('Activate maintenance mode', 'wp-maintenance'); ?></h3></div>
                    
                    <p>
                        <label class="wp-maintenance-container"><span class="wp-maintenance-label-text"><?php esc_html_e('Yes, enable maintenance mode', 'wp-maintenance'); ?></span>
                            <input type="checkbox" name="wp_maintenance_active" value="1" <?php if( isset($statusActive) && $statusActive==1) { echo ' checked'; } ?>>
                            <span class="wp-maintenance-checkmark"></span>
                        </label>
                    </p>
                    <p class="submit"><button type="submit" name="footer_submit" id="footer_submit" class="wp-maintenance-button wp-maintenance-button-primary"><?php esc_html_e('Save', 'wp-maintenance'); ?></button></p>

                </div>
                <!-- TITRE ET TEXTE  -->
                <div class="wp-maintenance-module-options-block">
                    <div class="wp-maintenance-settings-section-header"><h3 class="wp-maintenance-settings-section-title" ><?php esc_html_e('Title and text', 'wp-maintenance'); ?></h3></div>
                    <input type="text" size="80%" class="wp-maintenance-input" name="wpsettings[titre_maintenance]" value="<?php if( isset($paramsSettings['titre_maintenance']) && $paramsSettings['titre_maintenance']!='' ) { echo esc_html(stripslashes($paramsSettings['titre_maintenance'])); } ?>" /><br />
                    <?php 
                        $settingsTextmaintenance =   array(
                            'wpautop' => true, // use wpautop?
                            'media_buttons' => false, // show insert/upload button(s)
                            'textarea_name' => 'wpsettings[text_maintenance]', // set the textarea name to something different, square brackets [] can be used here
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
                    if( isset($paramsSettings['text_maintenance']) && $paramsSettings['text_maintenance']!='' ) { $textWpm = stripslashes($paramsSettings['text_maintenance']); }
                    ?>
                    <?php wp_editor( nl2br($textWpm), 'wpm-textmaintenance', $settingsTextmaintenance ); ?>
                    <p class="submit"><button type="submit" name="footer_submit" id="footer_submit" class="wp-maintenance-button wp-maintenance-button-primary"><?php esc_html_e('Save', 'wp-maintenance'); ?></button></p>

                </div>
                
                <!-- Encart Newletter -->
                <div class="wp-maintenance-module-options-block">
                    <div class="wp-maintenance-settings-section-header"><h3 class="wp-maintenance-settings-section-title"><?php esc_html_e('Activate newsletter block', 'wp-maintenance'); ?></h3></div>
                    <p>
                        <label class="wp-maintenance-container"><span class="wp-maintenance-label-text"><?php esc_html_e('Yes, enable newsletter block', 'wp-maintenance'); ?></span>
                            <input type="checkbox" name="wpsettings[newletter]" value="1" <?php if( isset($paramsSettings['newletter']) && $paramsSettings['newletter']==1 ) { echo ' checked'; } ?>>
                            <span class="wp-maintenance-checkmark"></span>
                        </label>
                    </p>

                    <div class="wp-maintenance-setting-row">
                        <label for="wpsettings[title_newletter]" class="wp-maintenance-setting-row-title"><?php esc_html_e('Enter title for the newletter block', 'wp-maintenance'); ?></label>
                        <input type="text" name="wpsettings[title_newletter]" class="wp-maintenance-input" size="60%" value="<?php if( isset($paramsSettings['title_newletter']) && $paramsSettings['title_newletter']!='' ) { echo esc_html(stripslashes(trim($paramsSettings['title_newletter']))); } ?>" />
                    </div>

                    <div class="wp-maintenance-setting-row">
                        <label for="wpsettings[type-newletter]" class="wp-maintenance-setting-row-title"><?php esc_html_e('Type of the newletter block', 'wp-maintenance'); ?></label>
                        <label class="wpm-container"><input type="radio" name="wpsettings[type_newletter]" size="60%" value="shortcode" <?php if( isset($paramsSettings['type_newletter']) && $paramsSettings['type_newletter']=='shortcode' ) { echo 'checked'; } if( empty($paramsSettings['type_newletter']) ) { echo 'checked'; } ?>  /><?php esc_html_e('Enter your newletter shortcode here:', 'wp-maintenance'); ?><span class="wpm-checkmark"></span></label><br /><br />
                        <input type="text" name="wpsettings[code_newletter]" size="60%" class="wp-maintenance-input" value='<?php if( isset($paramsSettings['code_newletter']) && $paramsSettings['code_newletter']!='' ) { echo esc_attr(stripslashes(trim($paramsSettings['code_newletter']))); } ?>' onclick="select()" /><br /><br />
                        <label class="wpm-container"><input type="radio" name="wpsettings[type_newletter]" value="iframe" <?php if( isset($paramsSettings['type_newletter']) && $paramsSettings['type_newletter']=='iframe' ) { echo 'checked'; } ?>/> <?php esc_html_e('Or enter your newletter iframe code here:', 'wp-maintenance'); ?><span class="wpm-checkmark"></span></label><br /><br />
                        <?php esc_html_e('Iframe Width', 'wp-maintenance'); ?><br />
                        <input type="text" size="10" class="wp-maintenance-input" name="wpsettings[iframe_newletter_width]" value="<?php if( isset($paramsSettings['iframe_newletter_width']) && $paramsSettings['iframe_newletter_width']!='' ) { echo esc_html(stripslashes($paramsSettings['iframe_newletter_width'])); } else { echo "540"; } ?>" /><br />
                        <?php esc_html_e('Iframe Height', 'wp-maintenance'); ?><br />
                        <input type="text" size="10" class="wp-maintenance-input" name="wpsettings[iframe_newletter_height]" value="<?php if( isset($paramsSettings['iframe_newletter_height']) && $paramsSettings['iframe_newletter_height']!='' ) { echo esc_html(stripslashes($paramsSettings['iframe_newletter_height'])); } else { echo "305"; } ?>" /><br />
                        <?php esc_html_e('Iframe Source', 'wp-maintenance'); ?><br />
                        <input type="text" size="80%" class="wp-maintenance-input" name="wpsettings[iframe_newletter_src]" value="<?php if( isset($paramsSettings['iframe_newletter_src']) && $paramsSettings['iframe_newletter_src']!='' ) { echo esc_url(stripslashes($paramsSettings['iframe_newletter_src'])); } ?>" /><br />
                        <?php esc_html_e('Iframe FrameBorder', 'wp-maintenance'); ?><br />
                        <select name="wpsettings[iframe_newletter_frameborder]" class="wp-maintenance-input">
                            <option value="0" <?php if( isset($paramsSettings['iframe_newletter_frameborder']) && $paramsSettings['iframe_newletter_frameborder']=='0' ) { echo 'selected'; } ?>>0</option>
                            <option value="1" <?php if( isset($paramsSettings['iframe_newletter_frameborder']) && $paramsSettings['iframe_newletter_frameborder']=='1' ) { echo 'selected'; } ?>>1</option>
                        </select><br />
                        <?php esc_html_e('Iframe Scrolling', 'wp-maintenance'); ?><br />
                        <select name="wpsettings[iframe_newletter_scrolling]" class="wp-maintenance-input">
                            <option value="auto" <?php if( isset($paramsSettings['iframe_newletter_scrolling']) && $paramsSettings['iframe_newletter_scrolling']=='auto' ) { echo 'selected'; } ?>>Auto</option>
                            <option value="yes" <?php if( isset($paramsSettings['iframe_newletter_scrolling']) && $paramsSettings['iframe_newletter_scrolling']=='yes' ) { echo 'selected'; } ?>>Yes</option>
                            <option value="no" <?php if( isset($paramsSettings['iframe_newletter_scrolling']) && $paramsSettings['iframe_newletter_scrolling']=='no' ) { echo 'selected'; } ?>>No</option>
                        </select><br />
                        <?php esc_html_e('Iframe Style', 'wp-maintenance'); ?><br />
                        <TEXTAREA NAME="wpsettings[iframe_newletter_style]" id="iframenewletterstyle" COLS=5 ROWS=4 style="height:50px;"><?php if( isset($paramsSettings['iframe_newletter_style']) ) { echo esc_textarea(stripslashes(trim($paramsSettings['iframe_newletter_style']))); } else { echo esc_textarea("display: block;margin-left: auto;margin-right: auto;max-width: 100%;"); } ?></TEXTAREA><br />
                        
                    </div>
                    

                    <p class="submit"><button type="submit" name="footer_submit" id="footer_submit" class="wp-maintenance-button wp-maintenance-button-primary"><?php esc_html_e('Save', 'wp-maintenance'); ?></button></p>

                </div>
            </form>
        </div>

    </div>
   
    <?php echo wp_kses(wpm_footer(), wpm_autorizeHtml()); ?>

</div>
<script type="text/javascript">

    /*jQuery("select.image-picker").imagepicker({
      hide_select:  false,
    });*/

    jQuery(document).ready(function($) {
        //wp.codeEditor.initialize($('#wpmaintenancestyle'), cm_settings);
        var editorSettings = wp.codeEditor.defaultSettings ? _.clone( wp.codeEditor.defaultSettings ) : {};
        editorSettings.codemirror = _.extend(
            {},
            editorSettings.codemirror,
            {
                indentUnit: 2,
                tabSize: 2,
                mode: 'txt',
            }
        );
        var editor = wp.codeEditor.initialize( $('#iframenewletterstyle'), editorSettings );
    });

</script>
