<?php

defined( 'ABSPATH' )
	or die( 'No direct load ! ' );
    
if(!defined('WPM_PLUGIN_URL')) { define('WPM_PLUGIN_URL', WP_CONTENT_URL.'/plugins/wp-maintenance/'); }
if(!defined('WPM_ICONS_URL')) { define('WPM_ICONS_URL', WP_CONTENT_URL.'/plugins/wp-maintenance/socialicons/'); }

/* Update des paramètres */
if( isset($_POST['action']) && $_POST['action'] == 'update' && $_POST["wp_maintenance_settings"]!='') {
    
    if( isset($_POST["wpm_maintenance_detete"]) && is_array($_POST["wpm_maintenance_detete"]) ) {
        foreach($_POST["wpm_maintenance_detete"] as $delSlideId=>$delSlideTrue) {
            if (array_key_exists($delSlideId, $_POST["wp_maintenance_slider"]["slider_image"])) {
                unset($_POST["wp_maintenance_slider"]["slider_image"][$delSlideId]);
                unset($_POST["wp_maintenance_slider"]["slider_text"][$delSlideId]);
                unset($_POST["wp_maintenance_slider"]["slider_link"][$delSlideId]);
            }
        }
    }
    if( isset($_POST["wp_maintenance_social_options"]['reset']) && $_POST["wp_maintenance_social_options"]['reset'] ==1 ) {
        unset($_POST["wp_maintenance_social"]);
    }
    
    update_option('wp_maintenance_slider', $_POST["wp_maintenance_slider"]);    
    update_option('wp_maintenance_settings', $_POST["wp_maintenance_settings"]);
    update_option('wp_maintenance_slider_options', $_POST["wp_maintenance_slider_options"]);
    update_option('wp_maintenance_style', stripslashes($_POST["wp_maintenance_style"]));
    update_option('wp_maintenance_limit', $_POST["wp_maintenance_limit"]);
    update_option('wp_maintenance_ipaddresses', $_POST["wp_maintenance_ipaddresses"]);
    update_option('wp_maintenance_active', $_POST["wp_maintenance_active"]);
    update_option('wp_maintenance_social', $_POST["wp_maintenance_social"]);
    update_option('wp_maintenance_social_options', $_POST["wp_maintenance_social_options"]);
    $options_saved = true;
    echo '<div id="message" class="updated fade"><p><strong>'.__('Options saved.', 'wp-maintenance').'</strong></p></div>';
}

// Récupère les paramètres sauvegardés
if(get_option('wp_maintenance_settings')) { extract(get_option('wp_maintenance_settings')); }
$paramMMode = get_option('wp_maintenance_settings');

if( isset($paramMMode) && !empty($paramMMode) ) {
    foreach($paramMMode as $variable=>$value) {
        if( !isset($paramMMode[$variable]) ) { $paramMMode[$variable] = $value; }
    }
}  
    
if(get_option('wp_maintenance_slider')) { extract(get_option('wp_maintenance_slider')); }
$paramSlider = get_option('wp_maintenance_slider');

if(get_option('wp_maintenance_slider_options')) { extract(get_option('wp_maintenance_slider_options')); }
$paramSliderOptions = get_option('wp_maintenance_slider_options');

// Récupère les Rôles et capabilités
if(get_option('wp_maintenance_limit')) { extract(get_option('wp_maintenance_limit')); }
$paramLimit = get_option('wp_maintenance_limit');

// Récupère les ip autorisee
$paramIpAddress = get_option('wp_maintenance_ipaddresses');

// Récupère si le status est actif ou non 
$statusActive = get_option('wp_maintenance_active');

// Récupère les Reseaux Sociaux
$paramSocial = get_option('wp_maintenance_social');
if(get_option('wp_maintenance_social_options')) { extract(get_option('wp_maintenance_social_options')); }
$paramSocialOption = get_option('wp_maintenance_social_options');


/* Si on réinitialise les feuille de styles  */
if( isset($_POST['wpm_initcss']) && $_POST['wpm_initcss']==1) {
    update_option( 'wp_maintenance_style', wpm_print_style() );
    $options_saved = true;
    echo '<div id="message" class="updated fade"><p><strong>'.__('The Style Sheet has been reset!', 'wp-maintenance').'</strong></p></div>';
}

?>
<style>
    .sortable { list-style-type: none; margin: 0; padding: 0; width: 60%; }
    .sortable li { padding: 0.4em; padding-left: 1.5em; height: 30px;cursor: pointer; cursor: move;  }
    .sortable li span { font-size: 15px;margin-right: 0.8em;cursor: move; }
    .sortable li:hover { background-color: #d2d2d2; }
    #pattern { text-align: left; margin: 5px 0; word-spacing: -1em;list-style-type: none; }
    #pattern li { display: inline-block; list-style: none;margin-right:15px;text-align:center;  }
    #pattern li.current { background: #66CC00; color: #fff; }
</style>
<style type="text/css">.postbox h3 { cursor:pointer; }</style>
<!--<script src="<?php //echo WP_PLUGIN_URL; ?>/wp-maintenance/js/jquery-ui-timepicker-addon.js"></script>-->
<script type="text/javascript">
    jQuery(document).ready(function() {
        jQuery('#font_title').fontselect();
        jQuery('#font_text').fontselect();
        jQuery('#font_text_bottom').fontselect();
        jQuery('#font_text_cpt').fontselect();
        jQuery('#font_text_newletter').fontselect();
    });

    function AfficherCacher(texte) {
        var test = document.getElementById(texte).style.display;
        if (test == "block") 
        {
            document.getElementById(texte).style.display = "none";
        }
        else 
        {
            document.getElementById(texte).style.display = "block";
        }
    }
		
	
</script>
<div class="wrap">
    
    <h2 style="font-size: 23px;font-weight: 400;padding: 9px 15px 4px 0px;line-height: 29px;">
        <?php echo __('WP Maintenance Settings', 'wp-maintenance'); ?>
    </h2>
    
    <!-- TABS OPTIONS -->
    <h2 class="nav-tab-wrapper">
        <a id="wpm-menu-general" class="nav-tab nav-tab-active" href="#general" onfocus="this.blur();"><?php _e('General', 'wp-maintenance'); ?></a>
        <a id="wpm-menu-couleurs" class="nav-tab" href="#couleurs" onfocus="this.blur();"><?php _e('Colors & Fonts', 'wp-maintenance'); ?></a>
        <a id="wpm-menu-image" class="nav-tab" href="#image" onfocus="this.blur();"><?php _e('Picture', 'wp-maintenance'); ?></a>
        <a id="wpm-menu-compte" class="nav-tab" href="#compte" onfocus="this.blur();"><?php _e('CountDown', 'wp-maintenance'); ?></a>
        <a id="wpm-menu-styles" class="nav-tab" href="#styles" onfocus="this.blur();"><?php _e('CSS Style', 'wp-maintenance'); ?></a>
        <a id="wpm-menu-options" class="nav-tab" href="#options" onfocus="this.blur();"><?php _e('Settings', 'wp-maintenance'); ?></a>
        <a id="wpm-menu-apropos" class="nav-tab" href="#apropos" onfocus="this.blur();"><?php _e('About', 'wp-maintenance'); ?></a>
    </h2>
 
    <div style="margin-left: 0px;margin-top: 5px;background-color: #ffffff;border: 1px solid #cccccc;padding: 10px;">
        <form method="post" action="" name="valide_maintenance">
            <input type="hidden" name="action" value="update" />

            <!-- GENERAL -->
            <div class="wpm-menu-general wpm-menu-group">
                <div id="wpm-opt-general"  >
                     <ul>
                        <!-- CHOIX ACTIVATION MAINTENANCE -->
                        <li>
                            <h3><?php _e('Enable maintenance mode:', 'wp-maintenance'); ?></h3>
                            <input type= "radio" name="wp_maintenance_active" value="1" <?php if($statusActive==1) { echo ' checked'; } ?>>&nbsp;<?php _e('Yes', 'wp-maintenance'); ?>&nbsp;&nbsp;&nbsp;
                            <input type= "radio" name="wp_maintenance_active" value="0" <?php if($statusActive==0) { echo ' checked'; } ?>>&nbsp;<?php _e('No', 'wp-maintenance'); ?>
                        </li>
                        <!-- TEXTE PERSONNEL POUR LA PAGE -->
                        <li>
                            <h3><?php _e('Title and text for the maintenance page:', 'wp-maintenance'); ?></h3>
                            <?php _e('Title:', 'wp-maintenance'); ?><br /><input type="text" name="wp_maintenance_settings[titre_maintenance]" value="<?php if( isset($paramMMode['titre_maintenance']) ) { echo stripslashes($paramMMode['titre_maintenance']); } ?>" size="70" /><br />
                            <?php _e('Text:', 'wp-maintenance'); ?><br /><TEXTAREA NAME="wp_maintenance_settings[text_maintenance]" COLS=70 ROWS=4><?php echo stripslashes($paramMMode['text_maintenance']); ?></TEXTAREA>
                            <h3><?php _e('Text in the bottom of maintenance page:', 'wp-maintenance'); ?></h3>
                            <?php _e('Text:', 'wp-maintenance'); ?><br /><TEXTAREA NAME="wp_maintenance_settings[text_bt_maintenance]" COLS=70 ROWS=4><?php if( isset($paramMMode['text_bt_maintenance']) ) { echo stripslashes($paramMMode['text_bt_maintenance']); } ?></TEXTAREA><br /><br />
                            <input type= "checkbox" name="wp_maintenance_settings[add_wplogin]" value="1" <?php if( isset($paramMMode['add_wplogin']) && $paramMMode['add_wplogin']==1 ) { echo ' checked'; } ?>> <?php _e('Enable login access in the bottom ?', 'wp-maintenance'); ?><br /><br />
                            <?php _e('Enter a text to go to the dashboard:', 'wp-maintenance'); ?><br />
                            <input type="text" name="wp_maintenance_settings[add_wplogin_title]" size="60" value="<?php echo stripslashes(trim($paramMMode['add_wplogin_title'])); ?>" /><br />
                            <small><?php _e('Eg: connect to %DASHBOARD% here!', 'wp-maintenance'); ?> <?php _e('(%DASHBOARD% will be replaced with the link to the dashboard and the word "Dashboard")', 'wp-maintenance'); ?></small>
                        </li>
                         
                         <li>
                            <h3><?php _e('Enable Google Analytics:', 'wp-maintenance'); ?></h3>
                            <input type= "checkbox" name="wp_maintenance_settings[analytics]" value="1" <?php if( isset($paramMMode['analytics']) && $paramMMode['analytics'] ==1) { echo ' checked'; } ?>><?php _e('Yes', 'wp-maintenance'); ?><br /><br />
                            <?php _e('Enter your Google analytics tracking ID here:', 'wp-maintenance'); ?><br />
                            <input type="text" name="wp_maintenance_settings[code_analytics]" value="<?php echo stripslashes(trim($paramMMode['code_analytics'])); ?>"><br />
                         <?php _e('Enter your domain URL:', 'wp-maintenance'); ?><br />
                         <input type="text" size="40" name="wp_maintenance_settings[domain_analytics]" value="<?php echo stripslashes(trim($paramMMode['domain_analytics'])); ?>">
                        </li>
                        <li>&nbsp;</li>

                         <li>
                             <h3><?php _e('Enable Social Networks:', 'wp-maintenance'); ?></h3>
                             <input type= "checkbox" name="wp_maintenance_social_options[enable]" value="1" <?php if( isset($paramSocialOption['enable']) && $paramSocialOption['enable']==1) { echo ' checked'; } ?>><?php _e('Yes', 'wp-maintenance'); ?><br /><br />
                             <?php _e('Enter text for the title icons:', 'wp-maintenance'); ?>
                             <input type="text" name="wp_maintenance_social_options[texte]" value="<?php if($paramSocialOption['texte']=='') { _e('Follow me on', 'wp-maintenance'); } else { echo stripslashes(trim($paramSocialOption['texte'])); } ?>" /><br /><br />
                             <!-- Liste des réseaux sociaux -->
                             <?php _e('Drad and drop the lines to put in the order you want:', 'wp-maintenance'); ?><br /><br />
                             <ul class="sortable">
                             <?php 
                                    if( isset($paramSocial) && !empty($paramSocial) ) {
                                        
                                        foreach($paramSocial as $socialName=>$socialUrl) {
                             ?>
                                            <li><span>::</span><img src="<?php echo WPM_ICONS_URL; ?>24x24/<?php echo $socialName; ?>.png" hspace="3" valign="middle" /><?php echo ucfirst($socialName); ?> <input type= "text" name="wp_maintenance_social[<?php echo $socialName; ?>]" value="<?php echo $socialUrl; ?>" size="50" onclick="select()" /></li>
                             <?php 
                                        }
                                        
                                    } else { 
                                        
                                        $wpmTabSocial = array('facebook', 'twitter', 'linkedin', 'flickr', 'youtube', 'pinterest', 'vimeo', 'instagram', 'google_plus', 'about_me', 'soundcloud');
                                        
                                        foreach ($wpmTabSocial as &$iconSocial) {
                                            echo '<li><span>::</span><img src="'.WPM_ICONS_URL.'24x24/'.$iconSocial.'.png" valign="middle" hspace="3"/>'.ucfirst($iconSocial).' <input type="text" size="50" name="wp_maintenance_social['.$iconSocial.']" value="" onclick="select()" ><br />';
                                        }
                                        
                                    }
                             ?>
                             </ul>
                             <script src="<?php echo WPM_PLUGIN_URL; ?>js/jquery.sortable.js"></script>
                             <script>
                                 jQuery('.sortable').sortable();
                             </script>
                             <br />
                             <?php _e('Choose icons size:', 'wp-maintenance'); ?>
                             <select name="wp_maintenance_social_options[size]">
                             <?php 
                                    $wpm_tabIcon = array(16, 24, 32, 48, 64, 128);
                                    foreach($wpm_tabIcon as $wpm_icon) {
                                        if($paramSocialOption['size']==$wpm_icon) { $selected = ' selected'; } else { $selected = ''; }
                                        echo '<option value="'.$wpm_icon.'" '.$selected.'>'.$wpm_icon.'</option>';
                                    }
                             ?>
                             </select>
                         </li>
                         <li>
                             <?php _e('Position:', 'wp-maintenance'); ?>
                             <select name="wp_maintenance_social_options[position]">
                                 <option value="top"<?php if($paramSocialOption['position']=='top') { echo ' selected'; } ?>><?php _e('Top', 'wp-maintenance'); ?></option>
                                 <option value="bottom"<?php if(!$paramSocialOption['position'] or $paramSocialOption['position']=='bottom') { echo ' selected'; } ?>><?php _e('Bottom', 'wp-maintenance'); ?></option>
                             </select>
                        </li>
                        <li>
                             <?php _e('Align:', 'wp-maintenance'); ?>
                             <select name="wp_maintenance_social_options[align]">
                                 <option value="left"<?php if($paramSocialOption['align']=='left') { echo ' selected'; } ?>><?php _e('Left', 'wp-maintenance'); ?></option>
                                 <option value="center"<?php if($paramSocialOption['align']=='' or $paramSocialOption['align']=='center') { echo ' selected'; } ?>><?php _e('Center', 'wp-maintenance'); ?></option>
                                 <option value="right"<?php if($paramSocialOption['align']=='right') { echo ' selected'; } ?>><?php _e('Right', 'wp-maintenance'); ?></option>
                             </select>
                             <br /><br />
                             <?php _e('You have your own icons? Enter the folder name of your theme here:', 'wp-maintenance'); ?><br /><strong><?php echo get_stylesheet_directory_uri(); ?>/</strong><input type="text" value="<?php echo stripslashes(trim($paramSocialOption['theme'])); ?>" name="wp_maintenance_social_options[theme]" /><br /><br />
                        </li>
                        <li>
                            <input type="checkbox" name="wp_maintenance_social_options[reset]" value="1" /> <i><?php _e('Reset Social Icon?', 'wp-maintenance'); ?></i>
                        </li>
                        <li>&nbsp;</li>

                        <!-- Encart Newletter -->
                        <li>
                            <h3><?php _e('Enable Newletter:', 'wp-maintenance'); ?></h3>
                            <input type= "checkbox" name="wp_maintenance_settings[newletter]" value="1" <?php if( isset($paramMMode['newletter']) && $paramMMode['newletter']==1) { echo ' checked'; } ?>><?php _e('Yes', 'wp-maintenance'); ?><br /><br />
                            <?php _e('Enter title for the newletter block:', 'wp-maintenance'); ?><br />
                            <input type="text" name="wp_maintenance_settings[title_newletter]" size="60" value="<?php echo stripslashes(trim($paramMMode['title_newletter'])); ?>" /><br /><br />
                            <input type="radio" name="wp_maintenance_settings[type_newletter]" value="shortcode" <?php if( isset($paramMMode['type_newletter']) && $paramMMode['type_newletter']=='shortcode' ) { echo 'checked'; } if( empty($paramMMode['type_newletter']) ) { echo 'checked'; } ?>  /><?php _e('Enter your newletter shortcode here:', 'wp-maintenance'); ?><br />
                            <input type="text" name="wp_maintenance_settings[code_newletter]" value='<?php echo stripslashes(trim($paramMMode['code_newletter'])); ?>' onclick="select()" /><br /><br />
                            <input type="radio" name="wp_maintenance_settings[type_newletter]" value="iframe" <?php if( isset($paramMMode['type_newletter']) && $paramMMode['type_newletter']=='iframe' ) { echo 'checked'; } ?>/> <?php _e('Or enter your newletter iframe code here:', 'wp-maintenance'); ?><br />
                            <textarea cols="60" rows="10" name="wp_maintenance_settings[iframe_newletter]"><?php if( isset($paramMMode['iframe_newletter'])) { echo stripslashes(trim($paramMMode['iframe_newletter'])); } ?></textarea> 
                        </li>
                        <li>&nbsp;</li>
                         
                        <li>
                            <p>
                                <?php submit_button(); ?>
                            </p>
                        </li>
                    </ul>
                </div>
            </div>
            <!-- fin options 1 -->


            <!-- Couleurs -->
            <div class="wpm-menu-couleurs wpm-menu-group" style="display: none;">
                <div id="wpm-opt-couleurs">
                    <ul> 
                        <!-- COULEUR DU FOND DE PAGE -->
                        <li>
                            <h3><?php _e('Choice general colors:', 'wp-maintenance'); ?></h3>
                            <em><?php _e('Background page color:', 'wp-maintenance'); ?></em> <br /><input type="text" value="<?php echo $paramMMode['color_bg']; ?>" name="wp_maintenance_settings[color_bg]" class="wpm-color-field" data-default-color="#f1f1f1" /> <br />
                            <em><?php _e('Header color:', 'wp-maintenance'); ?></em> <br /><input type="text" value="<?php echo $paramMMode['color_bg_header']; ?>" name="wp_maintenance_settings[color_bg_header]" class="wpm-color-field" data-default-color="#333333" />
                        </li>
                        
                        <li>
                            <h3><?php _e('Choice texts fonts and colors:', 'wp-maintenance'); ?></h3>
                            <em><?php _e('Text color:', 'wp-maintenance'); ?></em> <br /><input type="text" value="<?php echo $paramMMode['color_txt']; ?>" name="wp_maintenance_settings[color_txt]" class="wpm-color-field" data-default-color="#888888" /><br /><br />
                            <!-- POLICE DU TITRE -->
                            <em><stong><?php _e('Title font settings', 'wp-maintenance'); ?></stong></em>
                            <table cellspacing="10">
                                <tr>
                                    <td valign="top" align="left"><input name="wp_maintenance_settings[font_title]" id="font_title" type="text" value="<?php echo $paramMMode['font_title']; ?>" />
                                    
                                    <div id="fontSelect" class="fontSelect">
                                        <div class="arrow-down"></div>
                                    </div>

                                    <div id="fontSelect2" class="fontSelect">
                                        <div class="arrow-down"></div>
                                    </div>
                                    </td>
                                    <td>
                                        <?php _e('Size:', 'wp-maintenance'); ?>
                                        <input type="text" size="3" name="wp_maintenance_settings[font_title_size]" value="<?php echo stripslashes($paramMMode['font_title_size']); ?>" />px

                                    </td>
                                </tr>
                                <tr>
                                    <td rowspan="2">
                                        <input type="radio" name="wp_maintenance_settings[font_title_weigth]" value="normal" <?php if( isset($paramMMode['font_title_weigth']) && $paramMMode['font_title_weigth']=='normal') { echo 'checked'; } ?> >Normal
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
                                    <td valign="top" align="left"><input name="wp_maintenance_settings[font_text]" id="font_text" type="text" value="<?php echo $paramMMode['font_text']; ?>" /></td>
                                    <td>
                                        <?php _e('Size:', 'wp-maintenance'); ?>
                                        <input type="text" size="3" name="wp_maintenance_settings[font_text_size]" value="<?php echo stripslashes($paramMMode['font_text_size']); ?>" />px

                                    </td>
                                </tr>
                                <tr>
                                    <td rowspan="2">
                                        <input type="radio" name="wp_maintenance_settings[font_text_weigth]" value="normal" <?php if( isset($paramMMode['font_text_weigth']) && $paramMMode['font_text_weigth']=='normal') { echo 'checked'; } ?> >Normal
                                        <input type="radio" name="wp_maintenance_settings[font_text_weigth]" value="bold" <?php if( isset($paramMMode['font_text_weigth']) && $paramMMode['font_text_weigth']=='bold') { echo 'checked'; } ?>>Bold
                                        <input type="checkbox" name="wp_maintenance_settings[font_text_style]" value="italic" <?php if( isset($paramMMode['font_text_style']) && $paramMMode['font_text_style']=='italic') { echo 'checked'; } ?>>Italic
                                    </td>
                                </tr>
                            </table>   
                            <!-- FIN POLICE DU TEXTE -->
                            
                            <!-- CADRE -->
                            <br /><em><?php _e('Frame settings', 'wp-maintenance'); ?></em><br /><br />
                            
                            <input type="checkbox" name="wp_maintenance_settings[container_active]" value="1" <?php if( isset($paramMMode['container_active']) && $paramMMode['container_active']==1) { echo 'checked'; } ?>> <?php _e('Activate', 'wp-maintenance'); ?><br /><br /><?php _e('Color:', 'wp-maintenance'); ?><br /> <input type="text" value="<?php if( isset($paramMMode['container_color'])) { echo $paramMMode['container_color']; } else { echo '#ffffff'; }?>" name="wp_maintenance_settings[container_color]" class="wpm-color-field" data-default-color="#ffffff" /><br />
                            <?php _e('Opacity:', 'wp-maintenance'); ?>
                            <input type="text" size="3" name="wp_maintenance_settings[container_opacity]" value="<?php if( isset($paramMMode['container_opacity']) ) { echo $paramMMode['container_opacity']; } else { echo '0.5'; } ?>" />
                           <?php _e('Width:', 'wp-maintenance'); ?>
                            <input type="text" size="2" name="wp_maintenance_settings[container_width]" value="<?php if( isset($paramMMode['container_width']) ) { echo $paramMMode['container_width']; } else { echo '80'; } ?>" />%  
                            <!-- FIN CADRE -->
                            
                        </li>
                        
                        <!-- BOTTOM PAGE -->
                        <li>
                            <h3><?php _e('Choice fonts and colors bottom page:', 'wp-maintenance'); ?></h3>
                            <em><?php _e('Bottom color:', 'wp-maintenance'); ?></em> <br /><input type="text" value="<?php echo $paramMMode['color_bg_bottom']; ?>" name="wp_maintenance_settings[color_bg_bottom]" class="wpm-color-field" data-default-color="#333333" /> <br />
                            <em><?php _e('Text bottom color:', 'wp-maintenance'); ?></em> <br /><input type="text" value="<?php echo $paramMMode['color_text_bottom']; ?>" name="wp_maintenance_settings[color_text_bottom]" class="wpm-color-field" data-default-color="#ffffff" /><br /><br />
                            
                            <!-- POLICE DU TEXTE BAS DE PAGE -->
                            <em><?php _e('Text font settings', 'wp-maintenance'); ?></em>
                            <table cellspacing="10">
                                <tr>
                                    <td valign="top" align="left"><input name="wp_maintenance_settings[font_text_bottom]" id="font_text_bottom" type="text" value="<?php echo $paramMMode['font_text_bottom']; ?>" /></td>
                                    <td>
                                        <?php _e('Size:', 'wp-maintenance'); ?>
                                        <input type="text" size="3" name="wp_maintenance_settings[font_bottom_size]" value="<?php echo stripslashes($paramMMode['font_bottom_size']); ?>" />px

                                    </td>
                                </tr>
                                <tr>
                                    <td rowspan="2">
                                        <input type="radio" name="wp_maintenance_settings[font_bottom_weigth]" value="normal" <?php if( isset($paramMMode['font_bottom_weigth']) && $paramMMode['font_bottom_weigth']=='normal') { echo 'checked'; } ?> >Normal
                                        <input type="radio" name="wp_maintenance_settings[font_bottom_weigth]" value="bold" <?php if( isset($paramMMode['font_bottom_weigth']) && $paramMMode['font_bottom_weigth']=='bold') { echo 'checked'; } ?>>Bold
                                        <input type="checkbox" name="wp_maintenance_settings[font_bottom_style]" value="italic" <?php if( isset($paramMMode['font_bottom_style']) && $paramMMode['font_bottom_style']=='italic') { echo 'checked'; } ?>>Italic
                                    </td>
                                </tr>
                            </table>   
                            <br />
                            <!-- FIN POLICE DU TEXTE BAS DE PAGE -->
                        
                        </li>
                        
                        <li>
                            <h3><?php _e('Choice countdown fonts and colors:', 'wp-maintenance'); ?></h3>
                            <em><?php _e('Countdown text color:', 'wp-maintenance'); ?></em> <br /><input type="text" value="<?php echo $paramMMode['color_cpt']; ?>" name="wp_maintenance_settings[color_cpt]" class="wpm-color-field" data-default-color="#888888" /><br />
                            <em><?php _e('Countdown background color:', 'wp-maintenance'); ?></em> <br /><input type="text" value="<?php echo $paramMMode['color_cpt_bg']; ?>" name="wp_maintenance_settings[color_cpt_bg]" class="wpm-color-field" data-default-color="#888888" /><br /><br />
                        </li>
                        
                        <li>
                            <!-- POLICE DU COMPTEUR -->
                            <em><?php _e('Countdown font settings', 'wp-maintenance'); ?></em>
                            <table cellspacing="10">
                                <tr>
                                    <td valign="top" align="left"><input name="wp_maintenance_settings[font_cpt]" id="font_text_cpt" type="text" value="<?php echo $paramMMode['font_cpt']; ?>" /></td>
                                    <td>
                                        <?php _e('Size:', 'wp-maintenance'); ?>
                                        <input type="text" size="3" id="date_cpt_size" name="wp_maintenance_settings[date_cpt_size]" value="<?php echo trim($paramMMode['date_cpt_size']); ?>" />px

                                    </td>
                                </tr>
                            </table>
                            <!-- FIN POLICE DU COMPTEUR -->
                        </li>
                        <?php 
                            if( (strpos($paramMMode['code_newletter'], 'wysija_form')!=false || strpos($paramMMode['code_newletter'], 'mc4wp_form')!=false ) && (is_plugin_active( 'wysija-newsletters/index.php' ) || is_plugin_active( 'mailchimp-for-wp/mailchimp-for-wp.php' ) ) ) { 
                        ?>
                        <li>
                            <h3><?php _e('Choice form color:', 'wp-maintenance'); ?></h3>
                            
                            <!-- COULEUR WYJIYA -->
                            <table cellspacing="10">
                                <tr>
                                    <td valign="top" align="left"><input name="wp_maintenance_settings[newletter_font_text]" id="font_text_newletter" type="text" value="<?php if( isset($paramMMode['newletter_font_text']) ) { echo $paramMMode['newletter_font_text']; } ?>" /></td>
                                    <td>
                                        <?php _e('Size:', 'wp-maintenance'); ?>
                                        <input type="text" size="3" name="wp_maintenance_settings[newletter_size]" value="<?php if( isset($paramMMode['newletter_size']) && $paramMMode['newletter_size']) { echo stripslashes($paramMMode['newletter_size']); } else { echo 14; } ?>" />px

                                    </td>
                                </tr>
                                <tr>
                                    <td rowspan="2">
                                        <input type="radio" name="wp_maintenance_settings[newletter_font_weigth]" value="normal" <?php if( isset($paramMMode['newletter_font_weigth']) && $paramMMode['newletter_font_weigth']=='normal') { echo 'checked'; } ?> >Normal
                                        <input type="radio" name="wp_maintenance_settings[newletter_font_weigth]" value="bold" <?php if( isset($paramMMode['newletter_font_weigth']) && $paramMMode['newletter_font_weigth']=='bold') { echo 'checked'; } ?>>Bold
                                        <input type="checkbox" name="wp_maintenance_settings[newletter_font_style]" value="italic" <?php if( isset($paramMMode['newletter_font_style']) && $paramMMode['newletter_font_style']=='italic') { echo 'checked'; } ?>>Italic
                                    </td>
                                </tr>
                            </table>  
                            <br />
                             <em><?php _e('Field text color:', 'wp-maintenance'); ?></em> <br />
                            <input type="text" value="<?php if( isset($paramMMode['color_field_text']) ) { echo $paramMMode['color_field_text']; } else { echo '#333333'; } ?>" name="wp_maintenance_settings[color_field_text]" class="wpm-color-field" data-default-color="#333333" /><br />
                            <em><?php _e('Field border color:', 'wp-maintenance'); ?></em> <br />
                            <input type="text" value="<?php if( isset($paramMMode['color_field_border']) ) { echo $paramMMode['color_field_border']; } else { echo '#333333'; } ?>" name="wp_maintenance_settings[color_field_border]" class="wpm-color-field" data-default-color="#333333" /><br />
                            <em><?php _e('Field background color:', 'wp-maintenance'); ?></em> <br />
                            <input type="text" value="<?php if( isset($paramMMode['color_field_background']) ) { echo $paramMMode['color_field_background']; } else { echo '#cccccc'; } ?>" name="wp_maintenance_settings[color_field_background]" class="wpm-color-field" data-default-color="#cccccc" />
                            <br />
                            <em><?php _e('Button text color:', 'wp-maintenance'); ?></em> <br />
                            <input type="text" value="<?php if( isset($paramMMode['color_text_button']) ) { echo $paramMMode['color_text_button'];} else { echo '#ffffff'; } ?>" name="wp_maintenance_settings[color_text_button]" class="wpm-color-field" data-default-color="#ffffff" />
                            <br />
                            <em><?php _e('Button color:', 'wp-maintenance'); ?></em> <br />
                            <input type="text" value="<?php if( isset($paramMMode['color_button']) ) { echo $paramMMode['color_button']; } else { echo '#1e73be'; } ?>" name="wp_maintenance_settings[color_button]" class="wpm-color-field" data-default-color="#1e73be" />
                            <br />
                            <em><?php _e('Button color hover:', 'wp-maintenance'); ?></em> <br />
                            <input type="text" value="<?php if( isset($paramMMode['color_button_hover']) ) { echo $paramMMode['color_button_hover']; } else { echo '#ffffff'; }  ?>" name="wp_maintenance_settings[color_button_hover]" class="wpm-color-field" data-default-color="#ffffff" /><br />
                            <em><?php _e('Button color onclick:', 'wp-maintenance'); ?></em> <br />
                            <input type="text" value="<?php if( isset($paramMMode['color_button_onclick']) ) { echo $paramMMode['color_button_onclick']; } else { echo '#ffffff'; } ?>" name="wp_maintenance_settings[color_button_onclick]" class="wpm-color-field" data-default-color="#ffffff" />
                            
                        </li>
                        <?php } ?>
                        
                        <li>&nbsp;</li>
                         
                         <li>
                            <p>
                                <?php submit_button(); ?>
                            </p>
                        </li>
                         
                    </ul>
                 </div>
            </div>
            <!-- fin options 2 -->

             <!-- Onglet options 3 -->
             <div class="wpm-menu-image wpm-menu-group" style="display: none;">
                <div id="wpm-opt-image"  >
                    <ul>
                        <!-- UPLOADER UNE IMAGE -->
                        <?php 
                            if( isset($paramMMode['image']) && $paramMMode['image']!='' && ini_get('allow_url_fopen')==1 ) {
                                list($logoWidth, $logoHeight, $logoType, $logoAttr) = getimagesize($paramMMode['image']);
                            } else {
                                $logoWidth = 250;                              
                            }
                        ?>
                        <li>
                            <h3><?php _e('Upload a picture', 'wp-maintenance'); ?></h3>
                            <?php if($paramMMode['image']) { ?>
                            <?php _e('You use this picture:', 'wp-maintenance'); ?><br /> <img src="<?php echo $paramMMode['image']; ?>" width="<?php echo $logoWidth; ?>" height="<?php echo $logoHeight; ?>" id="image_visuel" style="border:1px solid #333;padding:3px;" /><br />
                            <?php } ?>
                            <br /><small><?php _e('Enter a URL or upload an image.', 'wp-maintenance'); ?></small><br />
                            <input id="upload_image" size="36" name="wp_maintenance_settings[image]" value="<?php echo $paramMMode['image']; ?>" type="text" /> <a href="#" id="upload_image_button" class="button" OnClick="this.blur();"><span> <?php _e('Select or Upload your picture', 'wp-maintenance'); ?> </span></a>                            
                        </li>
                        <li>&nbsp;</li>

                        <!-- UPLOADER UNE IMAGE DE FOND -->
                        <li>
                            <h3><?php _e('Upload a background picture', 'wp-maintenance'); ?></h3>
                            <input type= "checkbox" name="wp_maintenance_settings[b_enable_image]" value="1" <?php if( isset($paramMMode['b_enable_image']) && $paramMMode['b_enable_image']==1) { echo ' checked'; } ?>> <?php _e('Enable image background', 'wp-maintenance'); ?><br /><br />
                        <?php if( isset($paramMMode['b_image']) && $paramMMode['b_image']!='' && (!$paramMMode['b_pattern'] or $paramMMode['b_pattern']==0) ) { ?>
                            <?php _e('You use this background picture:', 'wp-maintenance'); ?><br />
                            <img src="<?php echo $paramMMode['b_image']; ?>" width="300" style="border:1px solid #333;padding:3px;background: url('<?php echo $paramMMode['b_image']; ?>');" /><br />
                        <?php } ?>
                        <?php if( isset($paramMMode['b_pattern']) && $paramMMode['b_pattern']>0) { ?>
                            <?php _e('You use this pattern:', 'wp-maintenance'); ?><br />
                            <div style="background: url('<?php echo WP_PLUGIN_URL ?>/wp-maintenance/images/pattern<?php echo $paramMMode['b_pattern']; ?>.png');width:250px;height:250px;border:1px solid #333;"></div>
                        <?php } ?>
                        <input id="upload_b_image" size="36" name="wp_maintenance_settings[b_image]" value="<?php if( isset($paramMMode['b_image']) && !empty($paramMMode['b_image']) ) { echo $paramMMode['b_image']; } ?>" type="text" /> <a href="#" id="upload_b_image_button" class="button" OnClick="this.blur();"><span> <?php _e('Select or Upload your picture', 'wp-maintenance'); ?> </span></a>
                        <br /><small><?php _e('Enter a URL or upload an image.', 'wp-maintenance'); ?></small><br /><br />
                            <?php _e('Or choose a pattern:', 'wp-maintenance'); ?>

                            <ul id="pattern">
                                <li>
                                    <div style="width:50px;height:50px;border:1px solid #333;background-color:#ffffff;font-size:0.8em;"><?php _e('NO PATTERN', 'wp-maintenance'); ?></div>
                                    <input type="radio" value="0" <?php if( empty($paramMMode['b_pattern']) or $paramMMode['b_pattern']==0) { echo 'checked'; } ?> name="wp_maintenance_settings[b_pattern]" />
                                </li>
                                <?php for ($p = 1; $p <= 12; $p++) { ?>
                                    <li>
                                        <div style="width:50px;height:50px;border:1px solid #333;background:url('<?php echo WP_PLUGIN_URL ?>/wp-maintenance/images/pattern<?php echo $p ?>.png');"></div>
                                        <input type="radio" value="<?php echo $p; ?>" <?php if( isset($paramMMode['b_pattern']) && $paramMMode['b_pattern']==$p) { echo 'checked'; } ?> name="wp_maintenance_settings[b_pattern]" />
                                    </li>
                                <?php } ?>
                            </ul>
                        </li>

                        <li>
                            <h3><?php _e('Background picture options', 'wp-maintenance'); ?></h3>
                            <select name="wp_maintenance_settings[b_repeat_image]" >
                                <option value="repeat"<?php if( isset($paramMMode['b_repeat_image']) && $paramMMode['b_repeat_image']=='repeat' or $paramMMode['b_repeat_image']=='') { echo ' selected'; } ?>>repeat</option>
                                <option value="no-repeat"<?php if( isset($paramMMode['b_repeat_image']) && $paramMMode['b_repeat_image']=='no-repeat') { echo ' selected'; } ?>>no-repeat</option>
                                <option value="repeat-x"<?php if( isset($paramMMode['b_repeat_image']) && $paramMMode['b_repeat_image']=='repeat-x') { echo ' selected'; } ?>>repeat-x</option>
                                <option value="repeat-y"<?php if( isset($paramMMode['b_repeat_image']) && $paramMMode['b_repeat_image']=='repeat-y') { echo ' selected'; } ?>>repeat-y</option>
                            </select><br /><br />
                             <input type= "checkbox" name="wp_maintenance_settings[b_fixed_image]" value="1" <?php if( isset($paramMMode['b_fixed_image']) && $paramMMode['b_fixed_image']==1) { echo ' checked'; } ?>>&nbsp;<?php _e('Fixed', 'wp-maintenance'); ?><br />
                        </li>
                        <li>&nbsp;</li>
                        
                        <li>
                            <h3><?php _e('Slider image options', 'wp-maintenance'); ?></h3> 
                            <input type= "checkbox" name="wp_maintenance_settings[enable_slider]" value="1" <?php if( isset($paramMMode['enable_slider']) && $paramMMode['enable_slider']==1) { echo ' checked'; } ?>> <?php _e('Enable Slider', 'wp-maintenance'); ?><br /><br />
                            
                            <?php
                            
                                if( $paramSlider!==null ) {

                                    if( $paramSlider['slider_image'] ) {
                                        $lastKeySlide = key($paramSlider['slider_image']);                                    $countSlide = ( $lastKeySlide + 1 );
                                    } else {
                                        $countSlide = 1;
                                    }
                               
                            ?>
                                    <div style="margin-bottom:15px;width:70%;">
                                        <div style="width:30%;float:left;">
                                            <?php _e('Speed:', 'wp-maintenance'); ?> <input type="text" name="wp_maintenance_slider_options[slider_speed]" size="4" value="<?php if( isset($paramSliderOptions['slider_speed']) && $paramSliderOptions['slider_speed'] !='') { echo $paramSliderOptions['slider_speed']; } else { echo 500; } ?>" />ms<br />
                                            <?php _e('Width:', 'wp-maintenance'); ?> <input type="text" name="wp_maintenance_slider_options[slider_width]" size="3" value="<?php if( isset($paramSliderOptions['slider_width']) && $paramSliderOptions['slider_width'] !='') { echo $paramSliderOptions['slider_width']; } else { echo 50; } ?>" />%
                                        </div>
                                        <div style="width:30%;float:left;padding-left:5px;">
                                            <?php _e('Display Auto Slider:', 'wp-maintenance'); ?><br /> 
                                            <input type= "radio" name="wp_maintenance_slider_options[slider_auto]" value="true" <?php if( (isset($paramSliderOptions['slider_auto']) && $paramSliderOptions['slider_auto'] == 'true') || empty($paramSliderOptions['slider_auto']) ) { echo "checked"; } ?>>&nbsp;<?php _e('Yes', 'wp-maintenance'); ?>&nbsp;&nbsp;&nbsp;
                                            <input type= "radio" name="wp_maintenance_slider_options[slider_auto]" value="false" <?php if( isset($paramSliderOptions['slider_auto']) && $paramSliderOptions['slider_auto'] == 'false' ) { echo "checked"; } ?>>&nbsp;<?php _e('No', 'wp-maintenance'); ?><br />

                                            <?php _e('Display button navigation:', 'wp-maintenance'); ?><br />
                                            <input type= "radio" name="wp_maintenance_slider_options[slider_nav]" value="true" <?php if( (isset($paramSliderOptions['slider_nav']) && $paramSliderOptions['slider_nav'] == 'true') || empty($paramSliderOptions['slider_nav']) ) { echo "checked"; } ?>>&nbsp;<?php _e('Yes', 'wp-maintenance'); ?>&nbsp;&nbsp;&nbsp;
                                            <input type= "radio" name="wp_maintenance_slider_options[slider_nav]" value="false" <?php if( isset($paramSliderOptions['slider_nav']) && $paramSliderOptions['slider_nav'] == 'false' ) { echo "checked"; } ?>>&nbsp;<?php _e('No', 'wp-maintenance'); ?>
                                        </div>
                                        <div style="width:30%;float:left;padding-left:5px;">
                                            <?php _e('Position:', 'wp-maintenance'); ?>
                                            <select name="wp_maintenance_slider_options[slider_position]">
                                                <option value="abovelogo" <?php if( isset($paramSliderOptions['slider_position']) && $paramSliderOptions['slider_position']=='abovelogo' ) { echo 'selected'; } ?></option><?php _e('Above logo', 'wp-maintenance'); ?></option>
                                                <option value="belowlogo" <?php if( isset($paramSliderOptions['slider_position']) && $paramSliderOptions['slider_position']=='belowlogo' ) { echo 'selected'; } ?>><?php _e('Below logo', 'wp-maintenance'); ?></option>
                                                <option value="belowtext" <?php if( ( isset($paramSliderOptions['slider_position']) && $paramSliderOptions['slider_position']=='belowtext' ) || empty($paramSliderOptions['slider_position']) ) { echo 'selected'; } ?>><?php _e('Below title & text', 'wp-maintenance'); ?></option>
                                            </select>
                                        </div>
                                        <div class="clear"></div>
                                    </div>
                    
                                    <input id="upload_slider_image" size="36" name="wp_maintenance_slider[slider_image][<?php echo $countSlide; ?>][image]" value="" type="text" /> <a href="#" id="upload_slider_image_button" class="button" OnClick="this.blur();"><span> <?php _e('Select or Upload your picture', 'wp-maintenance'); ?> </span></a><br /><br />
                    
                                    <div style="width:100%">
                                        <?php
                                            if( !empty($paramSlider['slider_image']) ) {
                                                foreach($paramSlider['slider_image'] as $numSlide=>$slide) {

                                                    if( $paramSlider['slider_image'][$numSlide]['image'] != '' ) {

                                                        $slideImg = '';
                                                        if( isset($paramSlider['slider_image'][$numSlide]['image']) ) {
                                                            $slideImg = $paramSlider['slider_image'][$numSlide]['image'];
                                                        }
                                                        $slideText = '';
                                                        if( isset($paramSlider['slider_image'][$numSlide]['text']) ) {
                                                            $slideText = stripslashes($paramSlider['slider_image'][$numSlide]['text']);
                                                        }
                                                        $slideLink = '';
                                                        if( isset($paramSlider['slider_image'][$numSlide]['link']) ) {
                                                            $slideLink = $paramSlider['slider_image'][$numSlide]['link'];
                                                        }
                                                        echo '<div style="float:left;width:45%;border: 1px solid #ececec;padding:0.8em;margin-right:1%;margin-bottom:1%">';

                                                        echo '<div style="float:left;margin-right:0.8em;">';
                                                        echo '<img src="'.$slideImg.'" width="200" />';
                                                        echo '</div>';

                                                        echo '<div style="float:left;">';
                                                        echo '<input type="hidden" name="wp_maintenance_slider[slider_image]['.$numSlide.'][image]" value="'.$slideImg.'" />';
                                                        echo __('Text:', 'wp-maintenance').'<br /> <input type="text" name="wp_maintenance_slider[slider_image]['.$numSlide.'][text]" value="'.$slideText.'" /><br />';
                                                        echo __('Link:', 'wp-maintenance').'<br /> <input type="text" name="wp_maintenance_slider[slider_image]['.$numSlide.'][link]" value="'.$slideLink.'" />';
                                                        echo '</div>';
                                                        echo '<div class="clear"></div>';
                                                        echo '<div style="text-align:right;"><input type="checkbox" name="wpm_maintenance_detete['.$numSlide.']" value="true" /><small>'.__('Delete this slide', 'wp-maintenance').'</small></div>';
                                                        echo '</div>';

                                                    }

                                                }
                                            }
                                    
                                        ?>
                                    </div>
                                    <div class="clear"></div>
                            <?php } ?>                            
                        </li>

                        <li>
                            <p>
                                <?php submit_button(); ?>
                            </p>
                        </li>

                 </ul>
                </div>
             </div>
             <!-- fin options 3 -->

             <!-- Onglet options 4 -->
             <div class="wpm-menu-compte wpm-menu-group" style="display: none;">
                 <div id="wpm-opt-compte"  >
                         <ul>
                            <!-- ACTIVER COMPTEUR -->
                            <?php
                             
                                 // Old version compte à rebours
                                if( isset($paramMMode['date_cpt_jj']) && empty($paramMMode['cptdate']) ) {
                                    $paramMMode['cptdate'] = $paramMMode['date_cpt_aa'].'/'.$paramMMode['date_cpt_mm'].'/'.$paramMMode['date_cpt_jj'];
                                } else if ( empty($paramMMode['cptdate']) ) {
                                    $paramMMode['cptdate'] = date('d').'/'.date('m').'/'.date('Y');
                                }
                                
                                if( isset($paramMMode['date_cpt_hh']) && empty($paramMMode['cpttime']) ) {
                                    $paramMMode['cpttime'] = $paramMMode['date_cpt_hh'].':'.$paramMMode['date_cpt_mn'];
                                } else if ( empty($paramMMode['cpttime']) ) {
                                    $paramMMode['cpttime'] = date( 'H:i', (time()+3600) );                                    
                                }
                                
                            ?>
                            <li><h3><?php _e('Enable a countdown ?', 'wp-maintenance'); ?></h3>
                                <input type= "checkbox" name="wp_maintenance_settings[active_cpt]" value="1" <?php if( isset($paramMMode['active_cpt']) && $paramMMode['active_cpt']==1 ) { echo ' checked'; } ?>>&nbsp;<?php _e('Yes', 'wp-maintenance'); ?><br /><br />
                                <?php 
                                    if( isset($paramMMode['cptdate']) && !empty($paramMMode['cptdate']) ) { 
                                        $startDate = $paramMMode['cptdate']; 
                                    }
                                    if( isset($paramMMode['cpttime']) && !empty($paramMMode['cpttime']) ) { 
                                        $startHour = $paramMMode['cpttime'];
                                    }
                                    if( (isset($paramMMode['active_cpt']) && $paramMMode['active_cpt']==0) || empty($paramMMode['active_cpt']) ) {
                                        $startDate = date_i18n( date("Y").'/'.date("m").'/'.date("d") );
                                        $timeFormats = array_unique( apply_filters( 'time_formats', array( 'H:i' ) ) );
                                        foreach ( $timeFormats as $format ) {
                                            $startHour = date_i18n( $format );
                                        }
                                        $newMin = explode(':', $startHour);
                                        $startHour = $newMin[0].':'.ceil($newMin[1]/5)*5;
                                    }                                
                                ?>
                                <small><?php _e('Select the launch date/time', 'wp-maintenance'); ?></small><br /><img src="<?php echo WP_PLUGIN_URL.'/wp-maintenance/images/schedule_clock.png'; ?>" class="datepicker" width="48" height="48" style="vertical-align: middle;margin-right:5px;">&nbsp;<input id="cptdate" class="datepicker" name="wp_maintenance_settings[cptdate]" type="text" autofocuss data-value="<?php echo $startDate; ?>"> à <input id="cpttime" class="timepicker" type="time" name="wp_maintenance_settings[cpttime]" value="<?php echo $startHour; ?>" size="4" autofocuss>                                
                                <div id="wpmdatecontainer"></div>
                                <br /><br />
                                <input type= "checkbox" name="wp_maintenance_settings[active_cpt_s]" value="1" <?php if( isset($paramMMode['active_cpt_s']) && $paramMMode['active_cpt_s']==1) { echo ' checked'; } ?>>&nbsp;<?php _e('Enable seconds ?', 'wp-maintenance'); ?><br /><br />
                                 <input type= "checkbox" name="wp_maintenance_settings[disable]" value="1" <?php if( isset($paramMMode['disable']) && $paramMMode['disable']==1) { echo ' checked'; } ?>>&nbsp;<?php _e('Disable maintenance mode at the end of the countdown?', 'wp-maintenance'); ?><br /><br />
                                 <?php _e('End message:', 'wp-maintenance'); ?><br /><TEXTAREA NAME="wp_maintenance_settings[message_cpt_fin]" COLS=70 ROWS=4><?php echo stripslashes($paramMMode['message_cpt_fin']); ?></TEXTAREA><br />
   
                                <script type="text/javascript">                                    

                                    jQuery(document).ready(function() {
                                        
                                        var $input = jQuery( '.datepicker' ).pickadate({
                                            formatSubmit: 'yyyy/mm/dd',
                                            container: '#wpmdatecontainer',
                                            closeOnSelect: true,
                                            closeOnClear: false,
                                            firstDay: 1,
                                            min: new Date(<?php echo date('Y').','.(date('m')-1).','.date('d'); ?>),
                                            monthsFull: [ 'Janvier', 'Février', 'Mars', 'Avril', 'Mai', 'Juin', 'Juillet', 'Août', 'Septembre', 'Octobre', 'Novembre', 'Décembre' ],
                                            monthsShort: [ 'Jan', 'Fev', 'Mar', 'Avr', 'Mai', 'Juin', 'Juil', 'Aou', 'Sep', 'Oct', 'Nov', 'Dec' ],
                                            weekdaysShort: [ 'Dim', 'Lun', 'Mar', 'Mer', 'Jeu', 'Ven', 'Sam' ],
                                            today: "<?php _e('Today', 'wp-maintenance'); ?>",
                                            clear: '<?php _e('Delete', 'wp-maintenance'); ?>',
                                            close: '<?php _e('Close', 'wp-maintenance'); ?>',
                                            
                                            // Accessibility labels
                                            labelMonthNext: '<?php _e('Next month', 'wp-maintenance'); ?>',
                                            labelMonthPrev: '<?php _e('Previous month', 'wp-maintenance'); ?>',
                                            labelMonthSelect: '<?php _e('Select a month', 'wp-maintenance'); ?>',
                                            labelYearSelect: '<?php _e('Select a year', 'wp-maintenance'); ?>',
                                            
                                            selectMonths: true,
                                            selectYears: true,
                                            
                                            
                                        })

                                        var picker = $input.pickadate('picker')

                                        
                                        var $input = jQuery( '.timepicker' ).pickatime({
                                            //container: '#wpmtimecontainer',
                                            clear: '<?php _e('Close', 'wp-maintenance'); ?>',
                                            interval: 5,
                                            editable: undefined,
                                            format: 'HH:i', // retour ce format dans le input
                                            formatSubmit: 'HH:i', // return ce format en post
                                            formatLabel: '<b>HH</b>:i', // Affichage

                                        })
                                        var picker = $input.pickatime('picker')
                                    
                                    });

                                </script>
                            </li>
                            <li>&nbsp;</li>
                            <li>
                                <p>
                                <?php submit_button(); ?>
                                </p>
                            </li>
                        </ul>
                 </div>
             </div>
             <!-- fin options 4 -->

            <!-- Onglet options 5 -->
             <div class="wpm-menu-styles wpm-menu-group" style="display: none;">
                 <div id="wpm-opt-styles"  >
                         <ul>
                            <!-- UTILISER UNE FEUILLE DE STYLE PERSO -->
                            <li><h3><?php _e('CSS style sheet code:', 'wp-maintenance'); ?></h3>
                                <?php _e('Edit the CSS sheet of your maintenance page here. Click "Reset" and "Save" to retrieve the default style sheet.', 'wp-maintenance'); ?><br /><br />
                                <div style="float:left;width:55%;margin-right:15px;">
                                    <TEXTAREA NAME="wp_maintenance_style" COLS=70 ROWS=24 style="width:100%;"><?php echo stripslashes(trim(get_option('wp_maintenance_style'))); ?></TEXTAREA>
                                </div>
                                <div style="float:left;position:relative;width:40%;">
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
                                    <div id="divcss" style="display:none;"><i><?php _e('Click for select all', 'wp-maintenance'); ?></i>
                                        <textarea onclick="select()" rows="15" cols="50%">
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
                                    <div id="divcss2" style="display:none;"><i><?php _e('Click for select all', 'wp-maintenance'); ?></i>
                                        <textarea onclick="select()" rows="15" cols="50%">
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
                                
                                <div class="clear"></div>
                                <br />
                            </li>
                            <li>
                                <input type= "checkbox" name="wpm_initcss" value="1" id="initcss" >&nbsp;<label for="wpm_initcss"><?php _e('Reset default CSS stylesheet ?', 'wp-maintenance'); ?></label><br />
                            </li>
                            <li>&nbsp;</li>

                            <li>
                                <p>
                                <?php submit_button(); ?>
                                </p>
                            </li>
                        </ul>
                 </div>
             </div>
             <!-- fin options 5 -->

             <!-- Onglet options 6 -->
             <div class="wpm-menu-options wpm-menu-group" style="display: none;">
                 <div id="wpm-opt-options"  >
                         <ul>
                             <!-- UTILISER UNE PAGE MAINTENANCE.PHP -->
                             <li><h3><?php _e('Theme maintenance page:', 'wp-maintenance'); ?></h3>
                                <?php _e('If you would use your maintenance.php page in your theme folder, click Yes.', 'wp-maintenance'); ?>&nbsp;<br /><br />
                                <input type= "radio" name="wp_maintenance_settings[pageperso]" value="1" <?php if($paramMMode['pageperso']==1) { echo ' checked'; } ?>>&nbsp;<?php _e('Yes', 'wp-maintenance'); ?>&nbsp;&nbsp;&nbsp;
                                <input type= "radio" name="wp_maintenance_settings[pageperso]" value="0" <?php if(!$paramMMode['pageperso'] or $paramMMode['pageperso']==0) { echo ' checked'; } ?>>&nbsp;<?php _e('No', 'wp-maintenance'); ?><br /><br />
                                <?php _e('You can use this shortcode to include Google Analytics code:', 'wp-maintenance'); ?> <input type="text" value="do_shortcode('[wpm_analytics']);" onclick="select()" style="width:250px;" /><br /><?php _e('You can use this shortcode to include Social Networks icons:', 'wp-maintenance'); ?> <input type="text" value="do_shortcode('[wpm_social]');" onclick="select()" style="width:250px;" /><br />
                            </li>
                            <li>&nbsp;</li>
                             
                             <?php 
                                /* Secure for demo mode */
                                if ( current_user_can( 'manage_options' ) ) { 
                             ?>
                            <li>
                                <h3><?php _e('Roles and Capabilities:', 'wp-maintenance'); ?></h3>
                                    <?php _e('Allow the site to display these roles:', 'wp-maintenance'); ?>&nbsp;<br /><br />
                                    <input type="hidden" name="wp_maintenance_limit[administrator]" value="administrator" />
                                    <?php
                                        $roles = wpm_get_roles();
                                        foreach($roles as $role=>$name) {
                                            $limitCheck = '';
                                            if( isset($paramLimit[$role]) && $paramLimit[$role]==$role) { $limitCheck = ' checked'; }
                                            if( $role=='administrator') {
                                                $limitCheck = 'checked disabled="disabled"';
                                            }
                                    ?>
                                        <input type="checkbox" name="wp_maintenance_limit[<?php echo $role; ?>]" value="<?php echo $role; ?>"<?php echo $limitCheck; ?> /><?php echo $name; ?>&nbsp;
                                    <?php }//end foreach ?>
                            </li>
                            <li>&nbsp;</li>
                             
                            <li>
                                <h3><?php _e('IP autorized:', 'wp-maintenance'); ?></h3>
                                <?php _e('Allow the site to display these IP addresses. Please, enter one IP address by line:', 'wp-maintenance'); ?>&nbsp;<br /><br />
                                <textarea name="wp_maintenance_ipaddresses" ROWS="5" style="width:80%;"><?php if( isset($paramIpAddress) ) { echo $paramIpAddress; } ?></textarea>
                            </li>
                            <li>&nbsp;</li>
                             
                            <li>
                                <h3><?php _e('ID pages autorized:', 'wp-maintenance'); ?></h3>
                                <?php _e('Allow the site to display these ID pages. Please, enter the ID pages separate with comma :', 'wp-maintenance'); ?>&nbsp;<br /><br />
                                <input name="wp_maintenance_settings[id_pages]" size="70" value="<?php if( isset($paramMMode['id_pages']) ) { echo $paramMMode['id_pages']; } ?>" />
                            </li>
                            <li>&nbsp;</li>
                             
                            <li><h3><?php _e('Header Code:', 'wp-maintenance'); ?></h3>
                                    <?php _e('The following code will add to the <head> tag. Useful if you need to add additional scripts such as CSS or JS.', 'wp-maintenance'); ?>&nbsp;<br /><br />
                                    <TEXTAREA NAME="wp_maintenance_settings[headercode]" COLS=70 ROWS=14 style="width:80%;"><?php if( isset($paramMMode['headercode']) ) { echo stripslashes($paramMMode['headercode']); }  ?></TEXTAREA>
                                </li>
                            <li>&nbsp;</li>
                            
                             <!--<li><h3><?php //_e('Demo mode:', 'wp-maintenance'); ?></h3>
                                 <p><?php //_e('Be careful, in demo mode, the plugin can be modified by all users', 'wp-maintenance'); ?></p>
                                    <input type= "checkbox" name="wp_maintenance_settings[enable_demo]" value="1" <?php //if($paramMMode['enable_demo']==1) { echo ' checked'; } ?>> <?php //_e('Active this plugin in demo mode?', 'wp-maintenance'); ?>&nbsp;<br />
                                    
                                </li>
                            <li>&nbsp;</li>-->
                            <?php } // End secure for demo mode ?>
                             
                            <li>
                                <p>
                                <?php submit_button(); ?>
                                </p>
                            </li>
                             
                        </ul>
                 </div>
             </div>
             <!-- fin options 6 -->

         </form>

          <!-- Onglet options 7 -->
          <div class="wpm-menu-apropos wpm-menu-group" style="display: none;">
                <div id="wpm-opt-apropos"  >
                     <ul>

                        <li>
                            <?php _e('This plugin has been developed for you for free by <a href="https://restezconnectes.fr" target="_blank">Florent Maillefaud</a>. It is royalty free, you can take it, modify it, distribute it as you see fit.<br /><br />It would be desirable that I can get feedback on your potential changes to improve this plugin for all.', 'wp-maintenance'); ?>
                        </li>
                        <li>&nbsp;</li>
                        <li>
                            <?php _e('Visit', 'wp-maintenance'); ?> <a href="https://wpmaintenance.info" target="_blank">WP Maintenance</a>, <?php _e('try the demo of the plugin, talk about this plugin to your surroundings!', 'wp-maintenance'); ?>
                        </li>
                        <li>&nbsp;</li>
                        <li>
                            <!-- FAIRE UN DON SUR PAYPAL -->
                            <div><?php _e('If you want Donate (French Paypal) for my current and future developments:', 'wp-maintenance'); ?><br /><br />
                                <div style="width:350px;margin-left:auto;margin-right:auto;padding:5px;">
                                    <a href="https://paypal.me/RestezConnectes/10" target="_blank" class="wpmclassname">
                                        <img src="<?php echo WP_PLUGIN_URL.'/wp-maintenance/images/donate.png'; ?>" valign="bottom" width="64" /> Donate now!
                                    </a>
                                </div>
                            </div>
                            <!-- FIN FAIRE UN DON -->
                        </li>
                        <li>&nbsp;</li>
                    </ul>
                </div>
           </div>
           <!-- fin options 7 -->

     </div><!-- -->
    
    <div style="margin-top:40px;">

        <a href="https://wpmaintenance.info/" target="_blank"><?php _e('WP Maintenance','wp-maintenance'); ?></a> <?php _e('is brought to you by','wp-maintenance'); ?> <a href="https://restezconnectes.fr/" target="_blank">Restez Connectés</a> - <?php _e('If you found this plugin useful','wp-maintenance'); ?> <a href="https://wordpress.org/support/view/plugin-reviews/wp-maintenance" target="_blank"><?php _e('give it 5 &#9733; on WordPress.org','wp-maintenance'); ?></a>

    </div>
    
</div><!-- wrap -->