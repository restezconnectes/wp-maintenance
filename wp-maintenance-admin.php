<?php

    
if(!defined('WPM_PLUGIN_URL')) { define('WPM_PLUGIN_URL', WP_CONTENT_URL.'/plugins/wp-maintenance/'); }
if(!defined('WPM_ICONS_URL')) { define('WPM_ICONS_URL', WP_CONTENT_URL.'/plugins/wp-maintenance/socialicons/'); }

/* Update des paramètres */
if($_POST['action'] == 'update' && $_POST["wp_maintenance_settings"]!='') {
    update_option('wp_maintenance_settings', $_POST["wp_maintenance_settings"]);
    update_option('wp_maintenance_style', $_POST["wp_maintenance_style"]);
    update_option('wp_maintenance_limit', $_POST["wp_maintenance_limit"]);
    update_option('wp_maintenance_active', $_POST["wp_maintenance_active"]);
    update_option('wp_maintenance_social', $_POST["wp_maintenance_social"]);
    update_option('wp_maintenance_social_options', $_POST["wp_maintenance_social_options"]);
    $options_saved = true;
    echo '<div id="message" class="updated fade"><p><strong>'.__('Options saved.', 'wp-maintenance').'</strong></p></div>';
}

// Récupère les paramètres sauvegardés
if(get_option('wp_maintenance_settings')) { extract(get_option('wp_maintenance_settings')); }
$paramMMode = get_option('wp_maintenance_settings');

if($paramMMode['font_title_size']=='') { $paramMMode['font_title_size'] = 40; }
if($paramMMode['font_title_style']=='') { $paramMMode['font_title_style'] = 'normal'; }
if($paramMMode['font_title_weigth']=='') { $paramMMode['font_title_weigth'] = 'normal'; }
if($paramMMode['font_text_size']=='') { $paramMMode['font_text_size'] = 40; }
if($paramMMode['font_text_style']=='') { $paramMMode['font_text_style'] = 'normal'; }
if($paramMMode['font_text_weigth']=='') { $paramMMode['font_text_weigth'] = 'normal'; }
if($paramMMode['font_text_bottom']=='') { $paramMMode['font_text_bottom'] = 'normal'; }
if($paramMMode['font_bottom_size']=='') { $paramMMode['font_bottom_size'] = 12; }
if($paramMMode['font_bottom_weigth']=='') { $paramMMode['font_bottom_weigth'] = 'normal'; }
if($paramMMode['font_bottom_style']=='') { $paramMMode['font_bottom_style'] = 'normal'; }
if($paramMMode['font_cpt']=='') { $paramMMode['font_cpt'] = 'Acme'; }
if($paramMMode['date_cpt_size']=='') { $paramMMode['date_cpt_size'] = 72; }

// Récupère les Rôles et capabilités
if(get_option('wp_maintenance_limit')) { extract(get_option('wp_maintenance_limit')); }
$paramLimit = get_option('wp_maintenance_limit');

// Récupère si le status est actif ou non 
$statusActive = get_option('wp_maintenance_active');

// Récupère les Reseaux Sociaux
$paramSocial = get_option('wp_maintenance_social');
if(get_option('wp_maintenance_social_options')) { extract(get_option('wp_maintenance_social_options')); }
$paramSocialOption = get_option('wp_maintenance_social_options');


/* Feuille de style par défault */
$wpm_style_defaut = '
h1 {
    margin-left:auto;
    margin-right:auto;
    width: 700px;
    padding: 10px;
    text-align:center;
    color: #_COLORTXT;
}

body {
    background: none repeat scroll 0 0 #_COLORBG;
    color: #_COLORTXT;
    font: 12px/1.5em Arial,Helvetica,Sans-serif;
    margin:0;
    padding:0;
}
#header {
    clear: both;
    padding: 5px 0 10px;
    position: relative;
}
.full {
    margin: 0 auto;
    width: 720px;
}
#logo {
    text-align: center;
}
#main {
    padding: 0px 50px;
}
#main .block {
    font-size: 13px;
    margin-bottom: 30px;
}
#main .block h3 {
    line-height: 60px;
    margin-bottom: 40px;
    text-align: center;
}
#main #intro h3 {
    font-size: 40px;
}
#main #intro p {
    font-size: 16px;
    line-height: 22px;
    text-align: center;
    word-wrap: break-word;
}

a:link {color: #_COLORTXT;text-decoration: underline;}
a:visited {color: #_COLORTXT;text-decoration: underline;}
a:hover, a:focus, a:active {color: #_COLORTXT;text-decoration: underline;}

#maintenance {
    text-align:center;
    margin-top:25px;
}

.cptR-rec_countdown {
    position: relative;
    background: #_COLORCPTBG;
    display: inline-block;
    line-height: #_DATESIZE px;
    min-width: 160px;
    min-height: 60px;
    padding: 30px 20px 5px 20px;
    text-transform: uppercase;
    text-align:center;
}

#cptR-day, #cptR-hours, #cptR-minutes, #cptR-seconds {
    color: #_COLORCPT;
    display: block;
    font-size: #_DATESIZE;
    height: 40px;
    line-height: 18px;
    text-align: center;
    float:left;
}
#cptR-days-span, #cptR-hours-span, #cptR-minutes-span, #cptR-seconds-span {
    color: #_COLORCPT;
    font-size: 10px;
    padding: 25px 5px 0 2px;
}

.wpm_horizontal li {
    display: inline-block;
    list-style: none;
    margin:5px;
    opacity:1;
}
.wpm_horizontal li:hover {
    opacity:0.5;
}
#wpm_footer {
    width: 100%;
    clear: both;
    height: 150px;
    text-align:center;
    background-color: #_COLOR_BG_BT;
    color:#_COLOR_TXT_BT;
    padding-top:10px;
    margin-top: 40px;
    font-size: 12px;
    position:relative;
    bottom:0;
}
.wpm_copyright {
    color:#_COLOR_TXT_BT;
    font-size: 12px;
}
.wpm_copyright a, a:hover, a:visited {
    color:#_COLOR_TXT_BT;
    text-decoration:none;
    font-size: 12px;
}
.wpm_social {
    padding: 0 45px;
    text-align: center;
}
.wpm_newletter {
    text-align:center;
}
@media screen and (min-width: 200px) and (max-width: 480px) {
    .full {
        max-width:300px;
    }
    #header {
        padding: 0;
    }
    #main {
        padding: 0;
    }
    .wpm_social {
        padding: 0 15px;
    }
    .cptR-rec_countdown {
        padding:0;
    }
    #main .block h3 {
        line-height: 0px;
    }
    #main .block {
        margin-bottom: 0;
    }
    #cptR-days-span, #cptR-hours-span, #cptR-minutes-span, #cptR-seconds-span {
        font-size: 8px;
    }
    #main #intro h3 {
        font-size: 6vw;
    }
}   

@media screen and (min-width: 480px) and (max-width: 767px) {
    .full {
        max-width:342px;
    }
}
';

/* Si on réinitialise les feuille de styles  */
if($_POST['wpm_initcss']==1) {
    update_option('wp_maintenance_style', $wpm_style_defaut);
    $options_saved = true;
    echo '<div id="message" class="updated fade"><p><strong>Feuillez de style réinitialisée !</strong></p></div>';
}

?>
<style>
    .sortable { list-style-type: none; margin: 0; padding: 0; width: 35%; }
    .sortable li { padding: 0.4em; padding-left: 1.5em; height: 30px;cursor: pointer; cursor: move;  }
    .sortable li span { font-size: 15px;margin-right: 0.8em;cursor: move; }
    .sortable li:hover { background-color: #d2d2d2; }
    #pattern { text-align: left; margin: 5px 0; word-spacing: -1em;list-style-type: none; }
    #pattern li { display: inline-block; list-style: none;margin-right:15px;text-align:center;  }
    #pattern li.current { background: #66CC00; color: #fff; }
</style>
<style type="text/css">.postbox h3 { cursor:pointer; }</style>
<div class="wrap">

    <!-- TABS OPTIONS -->
    <div id="icon-options-general" class="icon32"><br></div>
        <h2 class="nav-tab-wrapper">
            <a id="wpm-menu-general" class="nav-tab nav-tab-active" href="#general" onfocus="this.blur();"><?php _e('General', 'wp-maintenance'); ?></a>
            <a id="wpm-menu-couleurs" class="nav-tab" href="#couleurs" onfocus="this.blur();"><?php _e('Colors & Fonts', 'wp-maintenance'); ?></a>
            <a id="wpm-menu-image" class="nav-tab" href="#image" onfocus="this.blur();"><?php _e('Picture', 'wp-maintenance'); ?></a>
            <a id="wpm-menu-compte" class="nav-tab" href="#compte" onfocus="this.blur();"><?php _e('CountDown', 'wp-maintenance'); ?></a>
            <a id="wpm-menu-styles" class="nav-tab" href="#styles" onfocus="this.blur();"><?php _e('CSS Style', 'wp-maintenance'); ?></a>
            <a id="wpm-menu-options" class="nav-tab" href="#options" onfocus="this.blur();"><?php _e('Settings', 'wp-maintenance'); ?></a>
            <a id="wpm-menu-apropos" class="nav-tab" href="#apropos" onfocus="this.blur();"><?php _e('About', 'wp-maintenance'); ?></a>
        </h2>
 
 
    <div style="margin-left:25px;margin-top: 15px;">
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
                            <?php _e('Title:', 'wp-maintenance'); ?><br /><input type="text" name="wp_maintenance_settings[titre_maintenance]" value="<?php echo stripslashes($paramMMode['titre_maintenance']); ?>" /><br />
                            <?php _e('Text:', 'wp-maintenance'); ?><br /><TEXTAREA NAME="wp_maintenance_settings[text_maintenance]" COLS=70 ROWS=4><?php echo stripslashes($paramMMode['text_maintenance']); ?></TEXTAREA>
                            <h3><?php _e('Text in the bottom of maintenance page:', 'wp-maintenance'); ?></h3>
                            <?php _e('Text:', 'wp-maintenance'); ?><br /><TEXTAREA NAME="wp_maintenance_settings[text_bt_maintenance]" COLS=70 ROWS=4><?php echo stripslashes($paramMMode['text_bt_maintenance']); ?></TEXTAREA>
                        </li>
                         
                         <li>
                             <h3><?php _e('Enable Google Analytics:', 'wp-maintenance'); ?></h3>
                                <input type= "checkbox" name="wp_maintenance_settings[analytics]" value="1" <?php if($paramMMode['analytics']==1) { echo ' checked'; } ?>><?php _e('Yes', 'wp-maintenance'); ?><br /><br />
                                <?php _e('Enter your Google analytics tracking ID here:', 'wp-maintenance'); ?><br />
                                <input type="text" name="wp_maintenance_settings[code_analytics]" value="<?php echo stripslashes(trim($paramMMode['code_analytics'])); ?>"><br />
                             <?php _e('Enter your domain URL:', 'wp-maintenance'); ?><br />
                             <input type="text" name="wp_maintenance_settings[domain_analytics]" value="<?php if($paramMMode['domain_analytics']=='') { echo $_SERVER['SERVER_NAME']; } else { echo stripslashes(trim($paramMMode['domain_analytics'])); } ?>">
                        </li>
                        <li>&nbsp;</li>

                         <li>
                             <h3><?php _e('Enable Social Networks:', 'wp-maintenance'); ?></h3>
                             <input type= "checkbox" name="wp_maintenance_social_options[enable]" value="1" <?php if($paramSocialOption['enable']==1) { echo ' checked'; } ?>><?php _e('Yes', 'wp-maintenance'); ?><br /><br />
                             <?php _e('Enter text for the title icons:', 'wp-maintenance'); ?>
                             <input type="text" name="wp_maintenance_social_options[texte]" value="<?php if($paramSocialOption['texte']=='') { _e('Follow me on', 'wp-maintenance'); } else { echo stripslashes(trim($paramSocialOption['texte'])); } ?>" /><br /><br />
                             <!-- Liste des réseaux sociaux -->
                             <?php _e('Drad and drop the lines to put in the order you want:', 'wp-maintenance'); ?><br /><br />
                             <ul class="sortable">
                             <?php 
                                    if($paramSocial) { 
                                            foreach($paramSocial as $socialName=>$socialUrl) {
                                         ?>
                                      <li><span>::</span><img src="<?php echo WPM_ICONS_URL; ?>24x24/<?php echo $socialName; ?>.png" hspace="3" valign="middle" /><?php echo ucfirst($socialName); ?> <input type= "text" name="wp_maintenance_social[<?php echo $socialName; ?>]" value="<?php echo $socialUrl; ?>" onclick="select()" /></li>
                                         <?php } ?>
                             <?php 
                                    } else { 
                                        $arr = array('facebook', 'twitter', 'linkedin', 'flickr', 'youtube', 'pinterest', 'vimeo', 'instagram', 'google_plus', 'about_me');
                                        foreach ($arr as &$value) {
                                            echo '<li><span>::</span><img src="'.WPM_ICONS_URL.'24x24/'.$value.'.png" valign="middle" hspace="3"/>'.ucfirst($value).' <input type= "text" name="wp_maintenance_social['.$value.']" value="'.$paramSocial[$value].'" onclick="select()" ><br />';
                                        }
                                    }
                             ?>
                             </ul>
                             <script src="<?php echo WPM_PLUGIN_URL; ?>jquery.sortable.js"></script>
                             <script>
                                 jQuery('.sortable').sortable();
                             </script>
                             <br />
                             <?php _e('Choose icons size:', 'wp-maintenance'); ?>
                             <select name="wp_maintenance_social_options[size]">
                                 <option value="16"<?php if($paramSocialOption['size']==16) { echo ' selected'; } ?>>16</option>
                                 <option value="24"<?php if($paramSocialOption['size']==24) { echo ' selected'; } ?>>24</option>
                                 <option value="32"<?php if($paramSocialOption['size']==32) { echo ' selected'; } ?>>32</option>
                                 <option value="32"<?php if($paramSocialOption['size']==48 or $paramSocialOption=='') { echo ' selected'; } ?>>48</option>
                                 <option value="64"<?php if($paramSocialOption['size']==64) { echo ' selected'; } ?>>64</option>
                                 <option value="128"<?php if($paramSocialOption['size']==128) { echo ' selected'; } ?>>128</option>
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
                             <?php _e('You have your own icons? Enter the folder name of your theme here:', 'wp-maintenance'); ?><br /><strong><?php echo get_stylesheet_directory_uri(); ?>/</strong><input type="text" value="<?php echo stripslashes(trim($paramSocialOption['theme'])); ?>" name="wp_maintenance_social_options[theme]" />

                        </li>
                        <li>&nbsp;</li>

                         <li>
                             <h3><?php _e('Enable Newletter:', 'wp-maintenance'); ?></h3>
                                <input type= "checkbox" name="wp_maintenance_settings[newletter]" value="1" <?php if($paramMMode['newletter']==1) { echo ' checked'; } ?>><?php _e('Yes', 'wp-maintenance'); ?><br /><br />
                                <?php _e('Enter your newletter shortcode here:', 'wp-maintenance'); ?><br />
                                <input type="text" name="wp_maintenance_settings[code_newletter]" value='<?php echo stripslashes(trim($paramMMode['code_newletter'])); ?>' onclick="select()" />
                            </li>
                        <li>&nbsp;</li>

                        <li>
                            <a href="#general" id="submitbutton" OnClick="document.forms['valide_maintenance'].submit();this.blur();" name="Save" class="button-primary"><span> <?php _e('Save this settings', 'wp-maintenance'); ?> </span></a>
                        </li>
                    </ul>
                </div>
            </div>
            <!-- fin options 1 -->


            <!-- Couleurs -->
            <div class="wpm-menu-couleurs wpm-menu-group" style="display: none;">
                <div id="wpm-opt-couleurs"  >
                     <ul>
                        <!-- COULEUR DU FOND DE PAGE -->
                        <li><h3><?php _e('Choice texts colors:', 'wp-maintenance'); ?></h3>
                        <div id="pmColor" style="position: relative;">
                               <em><?php _e('Background page color:', 'wp-maintenance'); ?></em> <br /><input type="text" value="<?php echo $paramMMode['color_bg']; ?>" name="wp_maintenance_settings[color_bg]" class="wpm-color-field" data-default-color="#f1f1f1" /> <br />
                               <em><?php _e('Text color:', 'wp-maintenance'); ?></em> <br /><input type="text" value="<?php echo $paramMMode['color_txt']; ?>" name="wp_maintenance_settings[color_txt]" class="wpm-color-field" data-default-color="#888888" /> <br /> <br />
                                
                                <!-- POLICE DU TITRE -->
                                <em><stong><?php _e('Title font settings', 'wp-maintenance'); ?></stong></em>
                                <div>
                                    <table cellspacing="10">
                                        <tr>
                                            <td valign="top" align="left"><?php echo wpm_getFontsList('wp_maintenance_settings[font_title]', $paramMMode['font_title']); ?></td>
                                            <td>
                                                <?php _e('Size:', 'wp-maintenance'); ?>
                                                <input type="text" size="3" name="wp_maintenance_settings[font_title_size]" value="<?php echo stripslashes($paramMMode['font_title_size']); ?>" />px

                                            </td>
                                        </tr>
                                        <tr>
                                            <td rowspan="2">
                                                <input type="radio" name="wp_maintenance_settings[font_title_weigth]" value="normal" <?php if($paramMMode['font_title_weigth']=='normal') { echo 'checked'; } ?> >Normal
                                                <input type="radio" name="wp_maintenance_settings[font_title_weigth]" value="bold" <?php if($paramMMode['font_title_weigth']=='bold') { echo 'checked'; } ?>>Bold
                                                <input type="checkbox" name="wp_maintenance_settings[font_title_style]" value="italic" <?php if($paramMMode['font_title_style']=='italic') { echo 'checked'; } ?>>Italic
                                            </td>
                                        </tr>
                                    </table>   
                                </div><br />
                                <!-- FIN POLICE DU TITRE-->
                            
                                <!-- POLICE DU TEXTE -->
                                <em><?php _e('Text font settings', 'wp-maintenance'); ?></em>
                                <div>
                                    <table cellspacing="10">
                                        <tr>
                                            <td valign="top" align="left"><?php echo wpm_getFontsList('wp_maintenance_settings[font_text]', $paramMMode['font_text']); ?></td>
                                            <td>
                                                <?php _e('Size:', 'wp-maintenance'); ?>
                                                <input type="text" size="3" name="wp_maintenance_settings[font_text_size]" value="<?php echo stripslashes($paramMMode['font_text_size']); ?>" />px

                                            </td>
                                        </tr>
                                        <tr>
                                            <td rowspan="2">
                                                <input type="radio" name="wp_maintenance_settings[font_text_weigth]" value="normal" <?php if($paramMMode['font_text_weigth']=='normal') { echo 'checked'; } ?> >Normal
                                                <input type="radio" name="wp_maintenance_settings[font_text_weigth]" value="bold" <?php if($paramMMode['font_text_weigth']=='bold') { echo 'checked'; } ?>>Bold
                                                <input type="checkbox" name="wp_maintenance_settings[font_text_style]" value="italic" <?php if($paramMMode['font_text_style']=='italic') { echo 'checked'; } ?>>Italic
                                            </td>
                                        </tr>
                                    </table>   
                                </div>
                                <!-- FIN POLICE DU TEXTE -->
                                
                           <h3><?php _e('Choice countdown colors:', 'wp-maintenance'); ?></h3>
                           <em><?php _e('Countdown text color:', 'wp-maintenance'); ?></em> <br /><input type="text" value="<?php echo $paramMMode['color_cpt']; ?>" name="wp_maintenance_settings[color_cpt]" class="wpm-color-field" data-default-color="#FFFFFF" />
                           <br />
                           <em><?php _e('Countdown background color:', 'wp-maintenance'); ?></em> <br /><input type="text" value="<?php echo $paramMMode['color_cpt_bg']; ?>" name="wp_maintenance_settings[color_cpt_bg]" class="wpm-color-field" data-default-color="#888888" /><br /><br />
                                
                                <!-- POLICE DU COMPTEUR -->
                                <em><?php _e('Countdown font settings', 'wp-maintenance'); ?></em>
                                <div>
                                    <table cellspacing="10">
                                        <tr>
                                            <td valign="top" align="left"><?php echo wpm_getFontsList('wp_maintenance_settings[font_cpt]', $paramMMode['font_cpt']); ?></td>
                                            <td>
                                                <?php _e('Size:', 'wp-maintenance'); ?>
                                                <input type="text" size="3" name="wp_maintenance_settings[date_cpt_size]" value="<?php echo stripslashes($paramMMode['date_cpt_size']); ?>" />px

                                            </td>
                                        </tr>
                                    </table>
                                </div>
                                <!-- FIN POLICE DU COMPTEUR -->
                                <br />
                            
                            </div>
                            <h3><?php _e('Choice texts bottom colors:', 'wp-maintenance'); ?></h3>
                            <div id="pmColor" style="position: relative;">
                                   <em><?php _e('Bottom color:', 'wp-maintenance'); ?></em> <br /><input type="text" value="<?php echo $paramMMode['color_bg_bottom']; ?>" name="wp_maintenance_settings[color_bg_bottom]" class="wpm-color-field" data-default-color="#333333" /> <br />
                                   <em><?php _e('Text bottom color:', 'wp-maintenance'); ?></em> <br /><input type="text" value="<?php echo $paramMMode['color_text_bottom']; ?>" name="wp_maintenance_settings[color_text_bottom]" class="wpm-color-field" data-default-color="#ffffff" /> <br /> <br />                                

                                <!-- POLICE DU TEXTE BAS DE PAGE -->
                                <em><?php _e('Text font on the bottom page:', 'wp-maintenance'); ?></em>
                                <div>
                                    <table cellspacing="10">
                                        <tr>
                                            <td valign="top" align="left"><?php echo wpm_getFontsList('wp_maintenance_settings[font_text_bottom]', $paramMMode['font_text_bottom']); ?></td>
                                            <td>
                                                <?php _e('Size:', 'wp-maintenance'); ?>
                                                <input type="text" size="3" name="wp_maintenance_settings[font_bottom_size]" value="<?php echo stripslashes($paramMMode['font_bottom_size']); ?>" />px

                                            </td>
                                        </tr>
                                        <tr>
                                            <td rowspan="2">
                                                <input type="radio" name="wp_maintenance_settings[font_bottom_weigth]" value="normal" <?php if($paramMMode['font_bottom_weigth']=='normal') { echo 'checked'; } ?> >Normal
                                                <input type="radio" name="wp_maintenance_settings[font_bottom_weigth]" value="bold" <?php if($paramMMode['font_bottom_weigth']=='bold') { echo 'checked'; } ?>>Bold
                                                <input type="checkbox" name="wp_maintenance_settings[font_bottom_style]" value="italic" <?php if($paramMMode['font_bottom_style']=='italic') { echo 'checked'; } ?>>Italic
                                            </td>
                                        </tr>
                                    </table>   
                                </div>
                                <br />
                                <!-- FIN POLICE DU TEXTE BAS DE PAGE -->
                                <br /><br />
                        </li>
                        <li>&nbsp;</li>

                        <li>
                            <a href="#couleurs" id="submitbutton" OnClick="document.forms['valide_maintenance'].submit();this.blur();" name="Save" class="button-primary"><span> <?php _e('Save this settings', 'wp-maintenance'); ?> </span></a>
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
                            <li><h3><?php _e('Upload a picture', 'wp-maintenance'); ?></h3>
                            <?php if($paramMMode['image']) { ?>
                            <?php _e('You use this picture:', 'wp-maintenance'); ?><br /> <img src="<?php echo $paramMMode['image']; ?>" width="300" style="border:1px solid #333;padding:3px;" /><br />
                            <?php } ?>
                            <input id="upload_image" size="36" name="wp_maintenance_settings[image]" value="<?php echo $paramMMode['image']; ?>" type="text" /> <a href="#" id="upload_image_button" class="button" OnClick="this.blur();"><span> <?php _e('Select or Upload your picture', 'wp-maintenance'); ?> </span></a>
                            <br /><small><?php _e('Enter a URL or upload an image.', 'wp-maintenance'); ?></small>
                            </li>
                            <li>&nbsp;</li>
                             
                            <!-- UPLOADER UNE IMAGE DE FOND -->
                            <li><h3><?php _e('Upload a background picture', 'wp-maintenance'); ?></h3>
                                <input type= "checkbox" name="wp_maintenance_settings[b_enable_image]" value="1" <?php if($paramMMode['b_enable_image']==1) { echo ' checked'; } ?>> <?php _e('Enable image background', 'wp-maintenance'); ?><br /><br />
                            <?php if($paramMMode['b_image']!='' && (!$paramMMode['b_pattern'] or $paramMMode['b_pattern']==0) ) { ?>
                                <?php _e('You use this background picture:', 'wp-maintenance'); ?><br />
                                <img src="<?php echo $paramMMode['b_image']; ?>" width="300" style="border:1px solid #333;padding:3px;background: url('<?php echo $paramMMode['b_image']; ?>');" /><br />
                            <?php } ?>
                            <?php if($paramMMode['b_pattern']>0) { ?>
                                <?php _e('You use this pattern:', 'wp-maintenance'); ?><br />
                                <div style="background: url('<?php echo WP_PLUGIN_URL ?>/wp-maintenance/images/pattern<?php echo $paramMMode['b_pattern']; ?>.png');width:250px;height:250px;border:1px solid #333;"></div>
                            <?php } ?>
                            <input id="upload_b_image" size="36" name="wp_maintenance_settings[b_image]" value="<?php echo $paramMMode['b_image']; ?>" type="text" /> <a href="#" id="upload_b_image_button" class="button" OnClick="this.blur();"><span> <?php _e('Select or Upload your picture', 'wp-maintenance'); ?> </span></a>
                            <br /><small><?php _e('Enter a URL or upload an image.', 'wp-maintenance'); ?></small><br /><br />
                                <?php _e('Or choose a pattern:', 'wp-maintenance'); ?>
                                
                                    <ul id="pattern">
                                        <li>
                                            <div style="width:50px;height:50px;border:1px solid #333;background-color:#ffffff;"></div>
                                            <input type="radio" value="0" <?php if(!$paramMMode['b_pattern'] or $paramMMode['b_pattern']==0) { echo 'checked'; } ?> name="wp_maintenance_settings[b_pattern]" />
                                        </li>
                                    <?php for ($p = 1; $p <= 12; $p++) { ?>
                                        <li>
                                            <div style="width:50px;height:50px;border:1px solid #333;background:url('<?php echo WP_PLUGIN_URL ?>/wp-maintenance/images/pattern<?php echo $p ?>.png');"></div>
                                            <input type="radio" value="<?php echo $p; ?>" <?php if($paramMMode['b_pattern']==$p) { echo 'checked'; } ?> name="wp_maintenance_settings[b_pattern]" />
                                        </li>
                                    <?php } ?>
                                    </ul>
                            </li>
                             
                            <li><h3><?php _e('Background picture options', 'wp-maintenance'); ?></h3>
                            <select name="wp_maintenance_settings[b_repeat_image]" >
                                <option value="repeat"<?php if($paramMMode['b_repeat_image']=='repeat' or $paramMMode['b_repeat_image']=='') { echo ' selected'; } ?>>repeat</option>
                                <option value="no-repeat"<?php if($paramMMode['b_repeat_image']=='no-repeat') { echo ' selected'; } ?>>no-repeat</option>
                                <option value="repeat-x"<?php if($paramMMode['b_repeat_image']=='repeat-x') { echo ' selected'; } ?>>repeat-x</option>
                                <option value="repeat-y"<?php if($paramMMode['b_repeat_image']=='repeat-y') { echo ' selected'; } ?>>repeat-y</option>
                            </select><br /><br />
                             <input type= "checkbox" name="wp_maintenance_settings[b_fixed_image]" value="1" <?php if($paramMMode['b_fixed_image']==1) { echo ' checked'; } ?>>&nbsp;<?php _e('Fixed', 'wp-maintenance'); ?><br />
                            </li>
                            <li>&nbsp;</li>

                            <li>
                                <a href="#image" id="submitbutton" OnClick="document.forms['valide_maintenance'].submit();this.blur();" name="Save" class="button-primary"><span> <?php _e('Save this settings', 'wp-maintenance'); ?> </span></a>
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
                            <li><h3><?php _e('Enable a countdown ?', 'wp-maintenance'); ?></h3>
                                <input type= "checkbox" name="wp_maintenance_settings[active_cpt]" value="1" <?php if($paramMMode['active_cpt']==1) { echo ' checked'; } ?>>&nbsp;<?php _e('Yes', 'wp-maintenance'); ?><br /><br />
                                <small><?php _e('Enter the launch date', 'wp-maintenance'); ?></small><br /> <input type="text" name="wp_maintenance_settings[date_cpt_jj]" value="<?php if($paramMMode['date_cpt_jj']!='') { echo $paramMMode['date_cpt_jj']; } else { echo date('d'); } ?>" size="2" maxlength="2" autocomplete="off" />&nbsp;
                                <select name="wp_maintenance_settings[date_cpt_mm]">
                                    <?php
                                            $ctpDate = array(
                                                '01'=> 'jan',
                                                '02' => 'fév',
                                                '03' => 'mar',
                                                '04' => 'avr',
                                                '05' => 'mai',
                                                '06' => 'juin',
                                                '07' => 'juil',
                                                '08' => 'août',
                                                '09' => 'sept',
                                                '10' => 'oct',
                                                '11' => 'nov',
                                                '12' => 'déc'
                                            );
                                            foreach($ctpDate as $a => $b) {
                                                if($paramMMode['date_cpt_mm']=='' && $a==date('m')) {
                                                    $addSelected = 'selected';
                                                } elseif($paramMMode['date_cpt_mm']!='' && $paramMMode['date_cpt_mm']==$a) {
                                                    $addSelected = 'selected';
                                                } else {
                                                    $addSelected = '';
                                                }
                                                echo '<option value="'.$a.'" '.$addSelected.'>'.$a.' - '.$b.'</option>';
                                            }
                                    ?>
                                </select>&nbsp;
                                <input type="text" name="wp_maintenance_settings[date_cpt_aa]" value="<?php if($paramMMode['date_cpt_aa']!='') { echo $paramMMode['date_cpt_aa']; } else { echo date('Y'); } ?>" size="4" maxlength="4" autocomplete="off" />&nbsp;à&nbsp;
                                <input type="text" name="wp_maintenance_settings[date_cpt_hh]" value="<?php if($paramMMode['date_cpt_hh']!='') { echo $paramMMode['date_cpt_hh']; } else { echo date('H'); } ?>" size="2" maxlength="2" autocomplete="off" />&nbsp;h&nbsp;<input type="text" name="wp_maintenance_settings[date_cpt_mn]" value="<?php if($paramMMode['date_cpt_mn']!='') { echo $paramMMode['date_cpt_mn']; } else { echo date('i'); } ?>" size="2" maxlength="2" autocomplete="off" />&nbsp;min&nbsp;
                                <input type="hidden" name="wp_maintenance_settings[date_cpt_ss]" value="00" />
                                <br /><br />
                                <input type= "checkbox" name="wp_maintenance_settings[active_cpt_s]" value="1" <?php if($paramMMode['active_cpt_s']==1) { echo ' checked'; } ?>>&nbsp;<?php _e('Enable seconds ?', 'wp-maintenance'); ?><br /><br />
                                 <input type= "checkbox" name="wp_maintenance_settings[disable]" value="1" <?php if($paramMMode['disable']==1) { echo ' checked'; } ?>>&nbsp;<?php _e('Disable maintenance mode at the end of the countdown?', 'wp-maintenance'); ?><br /><br />
                                 <?php _e('End message:', 'wp-maintenance'); ?><br /><TEXTAREA NAME="wp_maintenance_settings[message_cpt_fin]" COLS=70 ROWS=4><?php echo stripslashes($paramMMode['message_cpt_fin']); ?></TEXTAREA><br /><?php _e('Font size:', 'wp-maintenance'); ?>  <select name="wp_maintenance_settings[date_cpt_size]">
                                            <?php
                                                $ctpSize = array('18', '24', '36', '48', '52', '56', '60', '64', '68', '72', '76');
                                                foreach($ctpSize as $c) {
                                                    if($paramMMode['date_cpt_size']==$c) {
                                                        $addsizeSelected = 'selected';
                                                    } else {
                                                        $addsizeSelected = '';
                                                    }
                                                    echo '<option value="'.$c.'" '.$addsizeSelected.'>'.$c.'px</option>';
                                                }
                                            ?>
                                      </select>
                            </li>
                            <li>&nbsp;</li>
                            <li>
                                <a href="#compte" id="submitbutton" OnClick="document.forms['valide_maintenance'].submit();this.blur();" name="Save" class="button-primary"><span> <?php _e('Save this settings', 'wp-maintenance'); ?> </span></a>
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
                                </div>
                                <div class="clear"></div>
                                <br />
                            </li>
                            <li>
                                <input type= "checkbox" name="wpm_initcss" value="1" id="initcss" >&nbsp;<label for="wpm_initcss"><?php _e('Reset default CSS stylesheet ?', 'wp-maintenance'); ?></label><br />
                            </li>
                            <li>&nbsp;</li>

                            <li>
                                <a href="#styles" id="submitbutton" OnClick="document.forms['valide_maintenance'].submit();this.blur();" name="Save" class="button-primary"><span> <?php _e('Save this settings', 'wp-maintenance'); ?> </span></a>
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

                            <li><h3><?php _e('Roles and Capabilities:', 'wp-maintenance'); ?></h3>
                                    <?php _e('Allow the site to display these roles:', 'wp-maintenance'); ?>&nbsp;<br /><br />
                                    <input type="hidden" name="wp_maintenance_limit[administrator]" value="administrator" />
                                    <?php
                                        $roles = wpm_get_roles();
                                        foreach($roles as $role=>$name) {
                                            $limitCheck = '';
                                            if($paramLimit[$role]==$role) { $limitCheck = ' checked'; }
                                            if($role=='administrator') {
                                                $limitCheck = 'checked disabled="disabled"';
                                            }
                                    ?>
                                        <input type="checkbox" name="wp_maintenance_limit[<?php echo $role; ?>]" value="<?php echo $role; ?>"<?php echo $limitCheck; ?> /><?php echo $name; ?>&nbsp;
                                    <?php }//end foreach ?>
                                </li>
                            <li>&nbsp;</li>
                             
                            <li>
                                <a href="#options" id="submitbutton" OnClick="document.forms['valide_maintenance'].submit();this.blur();" name="Save" class="button-primary"><span> <?php _e('Save this settings', 'wp-maintenance'); ?> </span></a>
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
                            <?php _e('This plugin has been developed for you for free by <a href="http://www.restezconnectes.fr" target="_blank">Florent Maillefaud</ a>. It is royalty free, you can take it, modify it, distribute it as you see fit. <br /> <br />It would be desirable that I can get feedback on your potential changes to improve this plugin for all.', 'wp-maintenance'); ?>
                        </li>
                        <li>&nbsp;</li>
                        <li>
                            <!-- FAIRE UN DON SUR PAYPAL -->
                            <div><?php _e('If you want Donate (French Paypal) for my current and future developments:', 'wp-maintenance'); ?><br />
                                <form action="https://www.paypal.com/cgi-bin/webscr" method="post" target="_blank">
                                <input type="hidden" name="cmd" value="_s-xclick">
                                <input type="hidden" name="hosted_button_id" value="ABGJLUXM5VP58">
                                <input type="image" src="https://www.paypalobjects.com/fr_FR/FR/i/btn/btn_donate_SM.gif" border="0" name="submit" alt="PayPal - la solution de paiement en ligne la plus simple et la plus sécurisée !">
                                <img alt="" border="0" src="https://www.paypalobjects.com/fr_FR/i/scr/pixel.gif" width="1" height="1">
                                </form>
                            </div>
                            <!-- FIN FAIRE UN DON -->
                        </li>
                        <li>&nbsp;</li>
                    </ul>
                </div>
           </div>
           <!-- fin options 7 -->

     </div><!-- -->
    
</div><!-- wrap -->

