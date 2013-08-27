<?php

/* Update des paramètres */
if($_POST['action'] == 'update' && $_POST["wp_maintenance_settings"]!='') {
    update_option('wp_maintenance_settings', $_POST["wp_maintenance_settings"]);
    $options_saved = true;
    echo '<div id="message" class="updated fade"><p><strong>'.__('Options saved.', 'wp-maintenance').'</strong></p></div>';
}

// Récupère les paramètres sauvegardés
if(get_option('wp_maintenance_settings')) { extract(get_option('wp_maintenance_settings')); }
$paramMMode = get_option('wp_maintenance_settings');

?>
<style type="text/css">.postbox h3 { cursor:pointer; }</style>
<div class="wrap">

    <!-- TABS OPTIONS -->
    <div id="icon-options-general" class="icon32"><br></div>
        <h2 class="nav-tab-wrapper">
            <a id="wpm-menu-general" class="nav-tab nav-tab-active" href="#general" onfocus="this.blur();"><?php echo __('General', 'wp-maintenance'); ?></a>
            <a id="wpm-menu-couleurs" class="nav-tab" href="#couleurs" onfocus="this.blur();"><?php echo __('Colors', 'wp-maintenance'); ?></a>
            <a id="wpm-menu-image" class="nav-tab" href="#image" onfocus="this.blur();"><?php echo __('Picture', 'wp-maintenance'); ?></a>
            <a id="wpm-menu-compte" class="nav-tab" href="#compte" onfocus="this.blur();"><?php echo __('CountDown', 'wp-maintenance'); ?></a>
            <a id="wpm-menu-options" class="nav-tab" href="#options" onfocus="this.blur();"><?php echo __('Settings', 'wp-maintenance'); ?></a>
            <a id="wpm-menu-apropos" class="nav-tab" href="#apropos" onfocus="this.blur();"><?php echo __('About', 'wp-maintenance'); ?></a>
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
                            <h3><?php echo __('Enable maintenance mode :', 'wp-maintenance'); ?></h3>
                            <input type= "radio" name="wp_maintenance_settings[active]" value="1" <?php if($paramMMode['active']==1) { echo ' checked'; } ?>>&nbsp;<?php echo __('Yes', 'wp-maintenance'); ?>&nbsp;&nbsp;&nbsp;
                            <input type= "radio" name="wp_maintenance_settings[active]" value="0" <?php if($paramMMode['active']==0) { echo ' checked'; } ?>>&nbsp;<?php echo __('No', 'wp-maintenance'); ?>
                        </li>
                        <!-- TEXTE PERSONNEL POUR LA PAGE -->
                        <li>
                            <h3><?php echo __('Title and text for the maintenance page :', 'wp-maintenance'); ?></h3>
                            <?php echo __('Title :', 'wp-maintenance'); ?><br /><input type="text" name="wp_maintenance_settings[titre_maintenance]" value="<?php echo $paramMMode['titre_maintenance'] ?>" /><br />
                            <?php echo __('Texte :', 'wp-maintenance'); ?><br /><TEXTAREA NAME="wp_maintenance_settings[text_maintenance]" COLS=70 ROWS=4><?php echo $paramMMode['text_maintenance'] ?></TEXTAREA>
                        </li>
                        <li> &nbsp;</li>

                        <li>
                            <a href="#general" id="submitbutton" OnClick="document.forms['valide_maintenance'].submit();this.blur();" name="Save" class="button-primary"><span> <?php echo __('Save this settings', 'wp-maintenance'); ?> </span></a>
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
                        <li><h3><?php echo __('Choice texts colors :', 'wp-maintenance'); ?></h3>
                            <div id="pmColor" style="position: relative;">
                                   <em><?php echo __('Background page color :', 'wp-maintenance'); ?></em> <br /><input type="text" value="<?php echo $paramMMode['color_bg']; ?>" name="wp_maintenance_settings[color_bg]" class="wpm-color-field" data-default-color="#f1f1f1" /> <br />
                                   <em><?php echo __('Text color :', 'wp-maintenance'); ?></em> <br /><input type="text" value="<?php echo $paramMMode['color_txt']; ?>" name="wp_maintenance_settings[color_txt]" class="wpm-color-field" data-default-color="#888888" /> <br /> <br />
                           <h3><?php echo __('Choice countdown colors :', 'wp-maintenance'); ?></h3>
                           <em><?php echo __('Countdown text color :', 'wp-maintenance'); ?></em> <br /><input type="text" value="<?php echo $paramMMode['color_cpt']; ?>" name="wp_maintenance_settings[color_cpt]" class="wpm-color-field" data-default-color="#FFFFFF" />
                           <br />
                           <em><?php echo __('Countdown background color :', 'wp-maintenance'); ?></em> <br /><input type="text" value="<?php echo $paramMMode['color_cpt_bg']; ?>" name="wp_maintenance_settings[color_cpt_bg]" class="wpm-color-field" data-default-color="#888888" />
                            </div>
                        </li>
                        <li> &nbsp;</li>

                        <li>
                            <a href="#couleurs" id="submitbutton" OnClick="document.forms['valide_maintenance'].submit();this.blur();" name="Save" class="button-primary"><span> <?php echo __('Save this settings', 'wp-maintenance'); ?> </span></a>
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
                            <li><h3><?php echo __('Upload a picture', 'wp-maintenance'); ?></h3>
                            <?php if($paramMMode['image']) { ?>
                            <?php echo __('You use this picture :', 'wp-maintenance'); ?><br /> <img src="<?php echo $paramMMode['image']; ?>" width="300" style="border:1px solid #333;padding:3px;" /><br />
                            <?php } ?>
                            <input id="upload_image" size="36" name="wp_maintenance_settings[image]" value="<?php echo $paramMMode['image']; ?>" type="text" /> <a href="#" id="upload_image_button" class="button" OnClick="this.blur();"><span> <?php echo __('Select or Upload your picture', 'wp-maintenance'); ?> </span></a>
                            <br /><small><?php echo __('Enter a URL or upload an image.', 'wp-maintenance'); ?></small>
                            </li>
                            <li> &nbsp;</li>

                            <li>
                                <a href="#image" id="submitbutton" OnClick="document.forms['valide_maintenance'].submit();this.blur();" name="Save" class="button-primary"><span> <?php echo __('Save this settings', 'wp-maintenance'); ?> </span></a>
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
                            <li><h3><?php echo __('Enable a countdown ?', 'wp-maintenance'); ?></h3>
                                <input type= "checkbox" name="wp_maintenance_settings[active_cpt]" value="1" <?php if($paramMMode['active_cpt']==1) { echo ' checked'; } ?>>&nbsp;Oui<br /><br />
                                <small><?php echo __('Enter the launch date', 'wp-maintenance'); ?></small><br /> <input type="text" name="wp_maintenance_settings[date_cpt_jj]" value="<?php if($paramMMode['date_cpt_jj']!='') { echo $paramMMode['date_cpt_jj']; } else { echo date('d'); } ?>" size="2" maxlength="2" autocomplete="off" />&nbsp;
                                <select name=wp_maintenance_settings[date_cpt_mm]">
                                    <?php
                                            $ctpDate = array(
                                                '01 '=> 'jan',
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
                                <input type="hidden" name=wp_maintenance_settings[date_cpt_ss]" value="<?php if($paramMMode['date_cpt_ss']!='') { echo $paramMMode['date_cpt_ss']; } else { echo date('s'); } ?>" />
                                <br /><br />
                                <input type= "checkbox" name="wp_maintenance_settings[active_cpt_s]" value="1" <?php if($paramMMode['active_cpt_s']==1) { echo ' checked'; } ?>>&nbsp;<?php echo __('Enable seconds ?', 'wp-maintenance'); ?><br /><br />
                                 <?php echo __('End message :', 'wp-maintenance'); ?><br /><TEXTAREA NAME="wp_maintenance_settings[message_cpt_fin]" COLS=70 ROWS=4><?php echo $paramMMode['message_cpt_fin'] ?></TEXTAREA><br /><?php echo __('Font size :', 'wp-maintenance'); ?>  <select name=wp_maintenance_settings[date_cpt_size]">
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
                            <li> &nbsp;</li>
                            <li>
                                <a href="#4" id="submitbutton" OnClick="document.forms['valide_maintenance'].submit();this.blur();" name="Save" class="button-primary"><span> <?php echo __('Save this settings', 'wp-maintenance'); ?> </span></a>
                            </li>
                        </ul>
                 </div>
             </div>
             <!-- fin options 4 -->

             <!-- Onglet options 5 -->
             <div class="wpm-menu-options wpm-menu-group" style="display: none;">
                 <div id="wpm-opt-options"  >
                         <ul>
                            <!-- UTILISER UNE PAGE MAINTENANCE.PHP -->
                            <li><h3><?php echo __('Theme maintenance page :', 'wp-maintenance'); ?></h3>
                                <?php echo __('Theme maintenance page :', 'wp-maintenance'); ?>&nbsp;<br /><br />
                                <input type= "radio" name="wp_maintenance_settings[pageperso]" value="1" <?php if($paramMMode['pageperso']==1) { echo ' checked'; } ?>>&nbsp;<?php echo __('Yes', 'wp-maintenance'); ?>&nbsp;&nbsp;&nbsp;
                                <input type= "radio" name="wp_maintenance_settings[pageperso]" value="0" <?php if(!$paramMMode['pageperso'] or $paramMMode['pageperso']==0) { echo ' checked'; } ?>>&nbsp;<?php echo __('No', 'wp-maintenance'); ?>
                            </li>
                            <li> &nbsp;</li>

                            <li>
                                <a href="#options" id="submitbutton" OnClick="document.forms['valide_maintenance'].submit();this.blur();" name="Save" class="button-primary"><span> <?php echo __('Save this settings', 'wp-maintenance'); ?> </span></a>
                            </li>
                        </ul>
                 </div>
             </div>
             <!-- fin options 5 -->

         </form>

          <!-- Onglet options 6 -->
          <div class="wpm-menu-apropos wpm-menu-group" style="display: none;">
                <div id="wpm-opt-apropos"  >
                     <ul>

                        <li>
                            <?php echo __('This plugin has been developed for you for free by <a href="http://www.restezconnectes.fr" target="_blank">Florent Maillefaud</ a>. It is royalty free, you can take it, modify it, distribute it as you see fit. <br /> <br />It would be desirable that I can get feedback on your potential changes to improve this plugin for all.', 'wp-maintenance'); ?>
                        </li>
                        <li> &nbsp;</li>
                        <li>
                            <!-- FAIRE UN DON SUR PAYPAL -->
                            <div><?php echo __('If you want Donate (French Paypal) for my current and future developments:', 'wp-maintenance'); ?><br />
                                <form action="https://www.paypal.com/cgi-bin/webscr" method="post" target="_blank">
                                <input type="hidden" name="cmd" value="_s-xclick">
                                <input type="hidden" name="hosted_button_id" value="ABGJLUXM5VP58">
                                <input type="image" src="https://www.paypalobjects.com/fr_FR/FR/i/btn/btn_donate_SM.gif" border="0" name="submit" alt="PayPal - la solution de paiement en ligne la plus simple et la plus sécurisée !">
                                <img alt="" border="0" src="https://www.paypalobjects.com/fr_FR/i/scr/pixel.gif" width="1" height="1">
                                </form>
                            </div>
                            <!-- FIN FAIRE UN DON -->
                        </li>
                        <li> &nbsp;</li>
                    </ul>
                </div>
           </div>
           <!-- fin options 6 -->

     </div><!-- -->
    
</div><!-- wrap -->

