<?php

defined( 'ABSPATH' ) or die( 'Not allowed' );

$messageUpdate = 0;
/* Update des paramètres */
if( isset($_POST['action']) && $_POST['action'] == 'update_general' ) {

    if( isset($_POST["wp_maintenance_social_options"]['reset']) && $_POST["wp_maintenance_social_options"]['reset'] ==1 ) {
        unset($_POST["wp_maintenance_social"]);
        $_POST["wp_maintenance_social"] = '';
    }
    update_option('wp_maintenance_social', $_POST["wp_maintenance_social"]);
    update_option('wp_maintenance_social_options', $_POST["wp_maintenance_social_options"]);
    update_option('wp_maintenance_active', $_POST["wp_maintenance_active"]);
    
    $options_saved = wpm_update_settings($_POST["wp_maintenance_settings"]);

    $messageUpdate = 1;
}

// Récupère les paramètres sauvegardés
if(get_option('wp_maintenance_settings')) { extract(get_option('wp_maintenance_settings')); }
$paramMMode = get_option('wp_maintenance_settings');

// Récupère si le status est actif ou non 
$statusActive = get_option('wp_maintenance_active');

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
    .CodeMirror {
      border: 1px solid #eee;
      height: auto;
    }
</style>


<div class="clear"></div>
<div class="wrap">
    <form method="post" action="" id="valide_settings" name="valide_settings">
        <input type="hidden" name="action" value="update_general" />
        
    <!-- HEADER -->
    <?php echo wpm_get_header( __('General', 'wp-maintenance'), 'dashicons-admin-settings', $messageUpdate ) ?>
    <!-- END HEADER -->
    <div style="margin-top: 40px;">
        
        <div id="wpm-column1">

            <div>
                <div style="float:left; width:70%;"><h3><?php _e('Enable maintenance mode:', 'wp-maintenance'); ?></h3></div>
                <div style="float:left; width:30%;text-align:right;">
                    <div class="switch-field">
                        <input class="switch_left" type="radio" id="switch_left" name="wp_maintenance_active" value="1" <?php if( isset($statusActive) && $statusActive==1) { echo ' checked'; } ?>/>
                        <label for="switch_left"><?php _e('Yes', 'wp-maintenance'); ?></label>
                        <input class="switch_right" type="radio" id="switch_right" name="wp_maintenance_active" value="0" <?php if( empty($statusActive) || isset($statusActive) && $statusActive==0) { echo ' checked'; } ?> />
                        <label for="switch_right"><?php _e('No', 'wp-maintenance'); ?></label>
                    </div>
                </div>
                <div class="clear"></div>
            </div>
                
            <div style="margin-top:15px;margin-bottom:15px;"><hr /></div>
            
            <h3><?php _e('Title and text for the maintenance page:', 'wp-maintenance'); ?></h3>
            <?php _e('Title:', 'wp-maintenance'); ?><br /><input class="wpm-form-field" type="text" size="100%" name="wp_maintenance_settings[titre_maintenance]" value="<?php if( isset($paramMMode['titre_maintenance']) && $paramMMode['titre_maintenance']!='' ) { echo stripslashes($paramMMode['titre_maintenance']); } ?>" /><br /><br />
            <?php _e('Text:', 'wp-maintenance'); ?><br />
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
            <br />
            <h3><?php _e('Text in the bottom of maintenance page:', 'wp-maintenance'); ?></h3>
            <?php 
                $settingsTextmaintenance =   array(
                    'wpautop' => true, // use wpautop?
                    'media_buttons' => false, // show insert/upload button(s)
                    'textarea_name' => 'wp_maintenance_settings[text_bt_maintenance]', // set the textarea name to something different, square brackets [] can be used here
                    'textarea_rows' => get_option('default_post_edit_rows', 10), // rows="..."
                    'tabindex' => '',
                    'editor_css' => '', //  extra styles for both visual and HTML editors buttons, 
                    'editor_class' => 'wpm-textbtmaintenance', // add extra class(es) to the editor textarea
                    'teeny' => true, // output the minimal editor config used in Press This
                    'dfw' => false, // replace the default fullscreen with DFW (supported on the front-end in WordPress 3.4)
                    'tinymce' => true, // load TinyMCE, can be used to pass settings directly to TinyMCE using an array()
                    'quicktags' => true // load Quicktags, can be used to pass settings directly to Quicktags using an array()
                );
            $textBt =  '';
            if( isset($paramMMode['text_bt_maintenance']) && $paramMMode['text_bt_maintenance']!='' ) { $textBt = stripslashes($paramMMode['text_bt_maintenance']); } 
            ?>
            <?php wp_editor( nl2br($textBt), 'wpm-textbtmaintenance', $settingsTextmaintenance ); ?>
            <br />
            <div style="margin-top:15px;margin-bottom:15px;"><hr /></div>
            
            <!-- GOOGLE LOGIN -->
            <div>
                <div style="float:left; width:70%;"><h3><?php _e('Enable login access in the bottom ?', 'wp-maintenance'); ?></h3></div>
                <div style="float:left; width:30%;text-align:right;">
                    <div class="switch-field">
                        <input class="switch_left" type="radio" onclick="AfficherTexte('option-wplogin');" id="switch_wplogin" name="wp_maintenance_settings[add_wplogin]" value="1" <?php if( isset($paramMMode['add_wplogin']) && $paramMMode['add_wplogin']==1) { echo ' checked'; } ?>/>
                        <label for="switch_wplogin"><?php _e('Yes', 'wp-maintenance'); ?></label>
                        <input class="switch_right" type="radio" onclick="CacherTexte('option-wplogin');" id="switch_wplogin_no" name="wp_maintenance_settings[add_wplogin]" value="0" <?php if( empty($paramMMode['add_wplogin']) || isset($paramMMode['add_wplogin']) && $paramMMode['add_wplogin']==0) { echo ' checked'; } ?> />
                        <label for="switch_wplogin_no"><?php _e('No', 'wp-maintenance'); ?></label>
                    </div>
                </div>
                <div class="clear"></div>
            </div>
                        
            <div id="option-wplogin" style="<?php if( empty($paramMMode['add_wplogin']) || isset($paramMMode['add_wplogin']) && $paramMMode['add_wplogin']==0) { echo ' display:none;'; } else { echo 'display:block'; } ?>">

                <?php _e('Enter a text to go to the dashboard:', 'wp-maintenance'); ?><br />
                <input type="text" class="wpm-form-field" name="wp_maintenance_settings[add_wplogin_title]" size="60%" value="<?php if( isset($paramMMode['add_wplogin_title']) && $paramMMode['add_wplogin_title']!='' ) { echo stripslashes(trim($paramMMode['add_wplogin_title'])); } ?>" /><br />
                <small><?php _e('Eg: connect to %DASHBOARD% here!', 'wp-maintenance'); ?> <?php _e('(%DASHBOARD% will be replaced with the link to the dashboard and the word "Dashboard")', 'wp-maintenance'); ?></small>
                
            </div>
            
            <div style="margin-top:15px;margin-bottom:15px;"><hr /></div>
            
            <!-- GOOGLE ANALYTICS -->
            <div>
                <div style="float:left; width:70%;"><h3><?php _e('Enable Google Analytics:', 'wp-maintenance'); ?></h3></div>
                <div style="float:left; width:30%;text-align:right;">
                    <div class="switch-field">
                        <input class="switch_left" type="radio" onclick="AfficherTexte('option-analytics');" id="switch_analytics" name="wp_maintenance_settings[analytics]" value="1" <?php if( isset($paramMMode['analytics']) && $paramMMode['analytics']==1) { echo ' checked'; } ?>/>
                        <label for="switch_analytics"><?php _e('Yes', 'wp-maintenance'); ?></label>
                        <input class="switch_right" type="radio" onclick="CacherTexte('option-analytics');" id="switch_analytics_no" name="wp_maintenance_settings[analytics]" value="0" <?php if( empty($paramMMode['analytics']) || isset($paramMMode['analytics']) && $paramMMode['analytics']==0) { echo ' checked'; } ?> />
                        <label for="switch_analytics_no"><?php _e('No', 'wp-maintenance'); ?></label>
                    </div>
                </div>
                <div class="clear"></div>
            </div>
                        
            <div id="option-analytics" style="<?php if( empty($paramMMode['analytics']) || isset($paramMMode['analytics']) && $paramMMode['analytics']==0) { echo ' display:none;'; } else { echo 'display:block'; } ?>">

                <?php _e('Enter your Google analytics tracking ID here:', 'wp-maintenance'); ?><br />
                <input type="text" class="wpm-form-field" name="wp_maintenance_settings[code_analytics]" value="<?php if( isset($paramMMode['code_analytics']) && $paramMMode['code_analytics']!='' ) { echo stripslashes(trim($paramMMode['code_analytics'])); } ?>"><br />
                <?php _e('Enter your domain URL:', 'wp-maintenance'); ?><br />
                <input type="text" class="wpm-form-field" name="wp_maintenance_settings[domain_analytics]" value="<?php if( isset($paramMMode['domain_analytics']) && $paramMMode['domain_analytics']!='' ) { echo stripslashes(trim($paramMMode['domain_analytics'])); } else { echo $_SERVER['SERVER_NAME']; } ?>">
            </div>
            
            <div style="margin-top:15px;margin-bottom:15px;"><hr /></div>
            <!-- ICONS RESEAUX SOCIAUX -->
            <div>
                <div style="float:left; width:70%;"><h3><?php _e('Enable Social Networks:', 'wp-maintenance'); ?></h3></div>
                <div style="float:left; width:30%;text-align:right;">
                    <div class="switch-field">
                        <input class="switch_left" type="radio" onclick="AfficherTexte('option-socials');" id="switch_socials" name="wp_maintenance_social_options[enable]" value="1" <?php if( isset($paramSocialOption['enable']) && $paramSocialOption['enable']==1) { echo ' checked'; } ?>/>
                        <label for="switch_socials"><?php _e('Yes', 'wp-maintenance'); ?></label>
                        <input class="switch_right" type="radio" onclick="CacherTexte('option-socials');" id="switch_socials_no" name="wp_maintenance_social_options[enable]" value="0" <?php if( empty($paramSocialOption['enable']) || isset($paramSocialOption['enable']) && $paramSocialOption['enable']==0) { echo ' checked'; } ?> />
                        <label for="switch_socials_no"><?php _e('No', 'wp-maintenance'); ?></label>
                    </div>
                </div>
                <div class="clear"></div>
            </div>
                        
            <div id="option-socials" style="<?php if( empty($paramSocialOption['enable']) || isset($paramSocialOption['enable']) && $paramSocialOption['enable']==0) { echo ' display:none;'; } else { echo 'display:block'; } ?>">

                <?php _e('Enter text for the title icons:', 'wp-maintenance'); ?>
                <input type="text" class="wpm-form-field" name="wp_maintenance_social_options[texte]" value="<?php if($paramSocialOption['texte']=='' && $paramSocialOption['texte']!='') { _e('Follow me on', 'wp-maintenance'); } else { echo stripslashes(trim($paramSocialOption['texte'])); } ?>" /><br /><br />
                <!-- Liste des réseaux sociaux -->
                <?php _e('Drad and drop the lines to put in the order you want:', 'wp-maintenance'); ?><br /><br />
                <ul class="sortable">
                <?php 
                        $wpmTabSocial = array('facebook', 'twitter', 'linkedin', 'flickr', 'youtube', 'pinterest', 'vimeo', 'instagram', 'google_plus', 'about_me', 'soundcloud', 'skype', 'tumblr', 'blogger', 'paypal');
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
                            echo '<li><span>::</span><img src="'.$linkIcon.'" valign="middle" hspace="3"/>'.ucfirst($iconSocial).' <input type="text" class="wpm-form-field" size="50" name="wp_maintenance_social['.$iconSocial.']" value="'.$entryValue.'" onclick="select()" ><br />';
                        }

                ?>
                </ul>
                <script src="<?php echo WPM_PLUGIN_URL; ?>js/jquery.sortable.js"></script>
                <script>
                 jQuery('.sortable').sortable();
                </script>
                <br />
                <?php _e('Choose icons size:', 'wp-maintenance'); ?>
                <select name="wp_maintenance_social_options[size]" class="wpm-form-field" >
                <?php 
                    $wpm_tabIcon = array(32, 64, 128, 256, 512);
                    foreach($wpm_tabIcon as $wpm_icon) {
                        if($paramSocialOption['size']==$wpm_icon) { $selected = ' selected'; } else { $selected = ''; }
                        echo '<option value="'.$wpm_icon.'" '.$selected.'>'.$wpm_icon.'</option>';
                    }
                ?>
                </select>
                <br />
                <?php _e('Choose icons style:', 'wp-maintenance'); ?>
                <select name="wp_maintenance_social_options[style]" class="image-picker show-html">
                  <option value=""></option>
                    <?php 
                        $styleSocialIcon = array( 'style1' => 'facebook.png', 'style2' => 'twitter.png', 'style3' => 'google_plus.png', 'style4' => 'youtube.png', 'style5' => 'linkedin.png', 'style6' => 'flickr.png');
                        foreach($styleSocialIcon as $stylesi => $pngIcon) {
                            if($stylesi==$styleIcons) { $selected = ' selected'; } else { $selected = ''; }
                            echo '<option data-img-src="'.WPM_ICONS_URL.''.$stylesi.'/64/'.$pngIcon.'" value="'.$stylesi.'" '.$selected.'>'.ucfirst($stylesi).'</option>';
                        } 
                    ?>
                </select>
                
                 <?php _e('Position:', 'wp-maintenance'); ?>
                 <select name="wp_maintenance_social_options[position]" class="wpm-form-field" >
                     <option value="top"<?php if( isset($paramSocialOption['position']) && $paramSocialOption['position']=='top') { echo ' selected'; } ?>><?php _e('Top', 'wp-maintenance'); ?></option>
                     <option value="bottom"<?php if( empty($paramSocialOption['position']) or (isset($paramSocialOption['position']) && $paramSocialOption['position']=='bottom') ) { echo ' selected'; } ?>><?php _e('Bottom', 'wp-maintenance'); ?></option>
                 </select>

                 <?php _e('Align:', 'wp-maintenance'); ?>
                 <select name="wp_maintenance_social_options[align]" class="wpm-form-field">
                     <option value="left"<?php if(isset($paramSocialOption['align']) && $paramSocialOption['align']=='left') { echo ' selected'; } ?>><?php _e('Left', 'wp-maintenance'); ?></option>
                     <option value="center"<?php if( empty($paramSocialOption['align']) or ( isset($paramSocialOption['align']) && $paramSocialOption['align']=='center') ) { echo ' selected'; } ?>><?php _e('Center', 'wp-maintenance'); ?></option>
                     <option value="right"<?php if( isset($paramSocialOption['align']) && $paramSocialOption['align']=='right') { echo ' selected'; } ?>><?php _e('Right', 'wp-maintenance'); ?></option>
                 </select>
                 <br /><br />
                 <?php _e('You have your own icons? Enter the folder name of your theme here:', 'wp-maintenance'); ?><br /><strong><?php echo get_stylesheet_directory_uri(); ?>/</strong><input class="wpm-form-field" type="text" value="<?php if( isset($paramSocialOption['theme']) && $paramSocialOption['theme']!='' ) { echo stripslashes(trim($paramSocialOption['theme'])); } ?>" name="wp_maintenance_social_options[theme]" /><br /><br />

                <div>
                    <div style="float:left; width:70%;"><h3><?php _e('Reset Social Icon?', 'wp-maintenance'); ?></h3></div>
                    <div style="float:left; width:30%;text-align:right;">
                        <div class="switch-field">
                            <input class="switch_left" type="radio" id="switch_reset" name="wp_maintenance_social_options[reset]" value="1" />
                            <label for="switch_reset"><?php _e('Yes', 'wp-maintenance'); ?></label>
                            <input class="switch_right" type="radio" id="switch_reset_no" name="wp_maintenance_social_options[reset]" value="0" checked />
                            <label for="switch_reset_no"><?php _e('No', 'wp-maintenance'); ?></label>
                        </div>
                    </div>
                    <div class="clear"></div>
                </div>
            </div>
            <div style="margin-top:15px;margin-bottom:15px;"><hr /></div>
                
            <!-- Encart Newletter -->
            <a name="newsletter"></a>
            <div>
                <div style="float:left; width:70%;"><h3><?php _e('Enable Newletter:', 'wp-maintenance'); ?></h3></div>
                <div style="float:left; width:30%;text-align:right;">
                    <div class="switch-field">
                        <input class="switch_left" type="radio" onclick="AfficherTexte('option-newletter');" id="switch_newletter" name="wp_maintenance_settings[newletter]" value="1" <?php if( isset($paramMMode['newletter']) && $paramMMode['newletter']==1 ) { echo ' checked'; } ?>/>
                        <label for="switch_newletter"><?php _e('Yes', 'wp-maintenance'); ?></label>
                        <input class="switch_right" type="radio" onclick="CacherTexte('option-newletter');" id="switch_newletter_no" name="wp_maintenance_settings[newletter]" value="0" <?php if( empty($paramMMode['newletter']) || isset($paramMMode['newletter']) && $paramMMode['newletter']==0) { echo ' checked'; } ?> />
                        <label for="switch_newletter_no"><?php _e('No', 'wp-maintenance'); ?></label>
                    </div>
                </div>
                <div class="clear"></div>
            </div>

            <div id="option-newletter" style="<?php if( empty($paramMMode['newletter']) || isset($paramMMode['newletter']) && $paramMMode['newletter']==0) { echo ' display:none;'; } else { echo 'display:block'; } ?>">

                <?php _e('Enter title for the newletter block:', 'wp-maintenance'); ?><br />
                <input type="text" class="wpm-form-field" name="wp_maintenance_settings[title_newletter]" size="60" value="<?php if( isset($paramMMode['title_newletter']) && $paramMMode['title_newletter']!='' ) { echo stripslashes(trim($paramMMode['title_newletter'])); } ?>" /><br /><br />
                <input type="radio" class="wpm-form-field" name="wp_maintenance_settings[type_newletter]" value="shortcode" <?php if( isset($paramMMode['type_newletter']) && $paramMMode['type_newletter']=='shortcode' ) { echo 'checked'; } if( empty($paramMMode['type_newletter']) ) { echo 'checked'; } ?>  /><?php _e('Enter your newletter shortcode here:', 'wp-maintenance'); ?><br />
                <input type="text" class="wpm-form-field" name="wp_maintenance_settings[code_newletter]" value='<?php if( isset($paramMMode['code_newletter']) && $paramMMode['code_newletter']!='' ) { echo stripslashes(trim($paramMMode['code_newletter'])); } ?>' onclick="select()" /><br /><br />
                <input type="radio" class="wpm-form-field" name="wp_maintenance_settings[type_newletter]" value="iframe" <?php if( isset($paramMMode['type_newletter']) && $paramMMode['type_newletter']=='iframe' ) { echo 'checked'; } ?>/> <?php _e('Or enter your newletter iframe code here:', 'wp-maintenance'); ?><br />
                <textarea class="wpm-form-field" id="iframe_newletter" cols="60" rows="10" name="wp_maintenance_settings[iframe_newletter]"><?php if( isset($paramMMode['iframe_newletter']) && $paramMMode['iframe_newletter']!='' ) { echo stripslashes(trim($paramMMode['iframe_newletter'])); } ?></textarea> 

            </div>
            
             <p>
                    <?php submit_button(); ?>
                </p>
            
        </div>
        
        <?php echo wpm_sidebar(); ?>
           
    </div>
  
    </form>  
    
    <?php echo wpm_footer(); ?>

</div>
<script type="text/javascript">

    jQuery("select.image-picker").imagepicker({
      hide_select:  false,
    });

</script>
