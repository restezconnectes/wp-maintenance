<?php

defined( 'ABSPATH' ) or die( 'Not allowed' );

$messageUpdate = 0;
/* Update des paramètres */
if( isset($_POST['action']) && $_POST['action'] == 'update_css' && wp_verify_nonce($_POST['security-css'], 'valid-css') ) {

    update_option('wp_maintenance_settings_css', sanitize_textarea_field(stripslashes($_POST["wp_maintenance_settings_css"])));
    $options_saved = true;
    $messageUpdate = 1;
}

/* Si on réinitialise les feuille de styles  */
if( isset($_POST['wpm_initcss']) && $_POST['wpm_initcss']==1) {
    update_option( 'wp_maintenance_settings_css', wpm_print_style() );
    //$options_saved = true;
    echo '<div id="message" class="updated fade"><p><strong>'.esc_html__('The Style Sheet has been reset!', 'wp-maintenance').'</strong></p></div>';
}

?>
<style>
    .CodeMirror {
      border: 1px solid #eee;
      height: 750px;
    }
    
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
                <input type="hidden" name="action" value="update_css" />
                <?php wp_nonce_field('valid-css', 'security-css'); ?>

                <div class="wp-maintenance-module-options-block">                

                    <!-- PIED DE PAGE  -->
                    <div class="wp-maintenance-settings-section-header"><h3 class="wp-maintenance-settings-section-title" id="module-import_export"><?php esc_html_e('Edit the CSS sheet of your maintenance page here.', 'wp-maintenance'); ?></h3></div>
                    <h3><?php esc_html_e('Click "Reset" and "Save" to retrieve the default style sheet.', 'wp-maintenance'); ?></h3>
                    <TEXTAREA NAME="wp_maintenance_settings_css" id="wpmaintenancestyle" COLS=70 ROWS=24 style="height:350px;"><?php echo esc_textarea(stripslashes(trim(get_option('wp_maintenance_settings_css')))); ?></TEXTAREA>                    
                    <p>
                        <label class="wp-maintenance-container"><span class="wp-maintenance-label-text"><?php esc_html_e('Yes, reset style sheet', 'wp-maintenance'); ?></span>
                            <input type="checkbox" name="wpm_initcss" value="1">
                            <span class="wp-maintenance-checkmark"></span>
                        </label>
                    </p>

                    <p class="submit"><button type="submit" name="footer_submit" id="footer_submit" class="wp-maintenance-button wp-maintenance-button-primary"><?php esc_html_e('Save', 'wp-maintenance'); ?></button></p>
                </div>

                <div class="wp-maintenance-module-options-block"> 
                    <div class="wp-maintenance-settings-section-header"><h3 class="wp-maintenance-settings-section-title" id="module-import_export"><?php esc_html_e('Markers for colors', 'wp-maintenance'); ?></h3></div>
                    <div class="wp-maintenance-setting-row">
                        <label class="wp-maintenance-setting-row-title">#_COLORTXT</label> <?php esc_html_e('Use this code for text color', 'wp-maintenance'); ?>
                        <label class="wp-maintenance-setting-row-title">#_COLORBG</label> <?php esc_html_e('Use this code for background text color', 'wp-maintenance'); ?>
                        <label class="wp-maintenance-setting-row-title">#_COLORCPTBG</label> <?php esc_html_e('Use this code for background color countdown', 'wp-maintenance'); ?>
                        <label class="wp-maintenance-setting-row-title">#_DATESIZE</label> <?php esc_html_e('Use this code for size countdown', 'wp-maintenance'); ?>
                        <label class="wp-maintenance-setting-row-title">#_COLORCPT</label> <?php esc_html_e('Use this code for countdown color', 'wp-maintenance'); ?>
                        <label class="wp-maintenance-setting-row-title">#_COLOR_TXT_BT</label> <?php esc_html_e('Use this code for bottom text color', 'wp-maintenance'); ?>
                    </div>
                    
                    <br />                
                    <a href="" onclick="AfficherCacher('divcss'); return false" ><?php esc_html_e('Need CSS code for MailPoet plugin?', 'wp-maintenance'); ?></a>
                    <div id="divcss" style="display:none;"><i><?php esc_html_e('Click for select all', 'wp-maintenance'); ?></i><br />
                        <textarea id="css-mailpoet" onclick="select()" rows="15" cols="50%">
.abs-req { display: none; }
.widget_wysija_cont .wysija-submit { }
.widget_wysija input { }
.wysija-submit-field { }
.wysija-submit-field:hover { }
.widget_wysija input:focus { }
.wysija-submit-field:active { }
.widget_wysija .wysija-submit, .widget_wysija .wysija-paragraph { }
.wysija-submit-field { }
                    </textarea>
                </div>
                <br />
                <a href="" onclick="AfficherCacher('divcss2'); return false" ><?php esc_html_e('Need CSS code for MailChimp plugin?', 'wp-maintenance'); ?></a>
                <div id="divcss2" style="display:none;"><i><?php esc_html_e('Click for select all', 'wp-maintenance'); ?></i><br />
                    <textarea id="css-mailchimp" onclick="select()" rows="15" cols="50%">
.mc4wp-form {  } /* the form element */
.mc4wp-form p { } /* form paragraphs */
.mc4wp-form label {  } /* labels */
.mc4wp-form input { } /* input fields */
.mc4wp-form input[type="checkbox"] {  } /* checkboxes */
.mc4wp-form input[type="submit"] { } /* submit button */
.mc4wp-form input[type="submit"]:hover { } 
.mc4wp-form input[type="submit"]:active { }
.mc4wp-alert {  } /* success & error messages */
.mc4wp-success {  } /* success message */
.mc4wp-error {  } /* error messages */
                    </textarea>
                </div>

            </div>
            </form>
        </div>

    </div>
    <script>
        jQuery(document).ready(function($) {
            //wp.codeEditor.initialize($('#wpmaintenancestyle'), cm_settings);
            var editorSettings = wp.codeEditor.defaultSettings ? _.clone( wp.codeEditor.defaultSettings ) : {};
            editorSettings.codemirror = _.extend(
                {},
                editorSettings.codemirror,
                {
                    indentUnit: 2,
                    tabSize: 2,
                    mode: 'css',
                }
            );
            var editor = wp.codeEditor.initialize( $('#wpmaintenancestyle'), editorSettings );
        });
    </script> 
    
    <?php echo wp_kses(wpm_footer(), wpm_autorizeHtml()); ?>
    
</div>