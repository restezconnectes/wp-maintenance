<?php

defined( 'ABSPATH' ) or die( 'Not allowed' );

$messageUpdate = 0;
/* Update des paramètres */
if( isset($_POST['action']) && $_POST['action'] == 'update_css' ) {

    update_option('wp_maintenance_style', stripslashes($_POST["wp_maintenance_style"]));
    $options_saved = true;

    $messageUpdate = 1;
}

/* Si on réinitialise les feuille de styles  */
if( isset($_POST['wpm_initcss']) && $_POST['wpm_initcss']==1) {
    update_option( 'wp_maintenance_style', wpm_print_style() );
    $options_saved = true;
    //echo '<div id="message" class="updated fade"><p><strong>'.__('The Style Sheet has been reset!', 'wp-maintenance').'</strong></p></div>';
}

?>
<style>
    .CodeMirror {
      border: 1px solid #eee;
      height: 550px;
    }
    
</style>
<div class="wrap">
    
    <form method="post" action="" name="valide_settings">
        <input type="hidden" name="action" value="update_css" />
        
    <!-- HEADER -->
    <?php echo wpm_get_header( __('CSS Style', 'wp-maintenance'), 'dashicons-media-code', $messageUpdate ) ?>
    <!-- END HEADER -->

    <div style="margin-top: 80px;">
    
        <div style="float:left;width:73%;margin-right:1%;border: 1px solid #ddd;background-color:#fff;padding:10px;">
            
                
                    <!-- UTILISER UNE FEUILLE DE STYLE PERSO -->
                    <?php _e('Edit the CSS sheet of your maintenance page here. Click "Reset" and "Save" to retrieve the default style sheet.', 'wp-maintenance'); ?><br /><br />
                    <div style="float:left;width:100%;margin-right:15px;">
                        <TEXTAREA NAME="wp_maintenance_style" id="wpmaintenancestyle" COLS=70 ROWS=24 style="height:250px;"><?php echo stripslashes(trim(get_option('wp_maintenance_style'))); ?></TEXTAREA>
                    </div>

                    <div class="clear"></div>
                    <br />
                    <div>
                        <div style="float:left; width:70%;"><h4><?php _e('Reset default CSS stylesheet ?', 'wp-maintenance'); ?></h4></div>
                        <div style="float:left; width:30%;text-align:right;">
                            <div class="switch-field">
                                <input class="switch_left" type="radio" id="switch_left" name="wpm_initcss" value="1" />
                                <label for="switch_left"><?php _e('Yes', 'wp-maintenance'); ?></label>
                                <input class="switch_right_no" type="radio" id="switch_right" name="wpm_initcss" value="0" checked />
                                <label for="switch_right"><?php _e('No', 'wp-maintenance'); ?></label>
                            </div>
                        </div>
                        <div class="clear"></div>
                    </div>
                        
                    <table class="wp-list-table widefat fixed" cellspacing="0">
                        <tbody id="the-list">
                            <tr>
                                <td><h3 class="hndle"><span><strong><?php _e('Markers for colors', 'wp-maintenance'); ?></strong></span></h3></td>
                                <td></td>
                            </tr>
                            <tr>
                                <td>#_COLORTXT</td>
                                <td><?php _e('Use this code for text color', 'wp-maintenance'); ?></td>
                            </tr>
                            <tr>
                                <td>#_COLORBG</td>
                                <td><?php _e('Use this code for background text color', 'wp-maintenance'); ?></td>
                            </tr>
                            <tr>
                                <td>#_COLORCPTBG</td>
                                <td><?php _e('Use this code for background color countdown', 'wp-maintenance'); ?></td>
                            </tr>
                            <tr>
                                <td>#_DATESIZE</td>
                                <td><?php _e('Use this code for size countdown', 'wp-maintenance'); ?></td>
                            </tr>
                            <tr>
                                <td>#_COLORCPT</td>
                                <td><?php _e('Use this code for countdown color', 'wp-maintenance'); ?></td>
                            </tr>
                        </tbody>
                    </table>
                    <br />                
                    <a href="" onclick="AfficherCacher('divcss'); return false" ><?php _e('Need CSS code for MailPoet plugin?', 'wp-maintenance'); ?></a>
                    <div id="divcss" style="display:none;"><i><?php _e('Click for select all', 'wp-maintenance'); ?></i><br />
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
                <a href="" onclick="AfficherCacher('divcss2'); return false" ><?php _e('Need CSS code for MailChimp plugin?', 'wp-maintenance'); ?></a>
                <div id="divcss2" style="display:none;"><i><?php _e('Click for select all', 'wp-maintenance'); ?></i><br />
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
                <p>
                    <?php submit_button(); ?>
                </p>
        </div>
        
       <?php echo wpm_sidebar(); ?>
                
    </div>
    </form>
    <script>
        var editor = CodeMirror.fromTextArea(document.getElementById("wpmaintenancestyle"), {
        lineNumbers: true,
        matchBrackets: true,
        textWrapping: true,
        lineWrapping: true,
        mode: "text/x-scss",
        theme:"material"
        });
    </script> 
    
    <?php echo wpm_footer(); ?>
    
</div>