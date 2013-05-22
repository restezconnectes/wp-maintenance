<?php

/* Update si changements */
if($_POST['action'] == 'update') {
    update_option('wp_maintenance_settings', $_POST["wp_maintenance_settings"]);
    $options_saved = true;
    echo '<div id="message" class="updated fade"><p><strong>Options sauvegardées.</strong></p></div>';
}

?>
<div class="wrap">
<div id="icon-themes" class="icon32" ><br></div>
    <h2>Paramètres de personnalisation WP Maintenance</h2>
    <form method="post" action="" name="valide_maintenance">
    <?php settings_fields('wp_maintenance_settings'); ?>
    <?php $paramMMode = get_option('wp_maintenance_settings'); ?>

        <div style="margin-left:25px;">
          <ul>

            <!-- CHOIX ACTIVATION MAINTENANCE -->
            <li><h3>Activer la maintenance :</h3>
                <input type= "radio" name="wp_maintenance_settings[active]" value="1" <?php if($paramMMode['active']==1) { echo ' checked'; } ?>>&nbsp;Oui&nbsp;&nbsp;&nbsp;
                <input type= "radio" name="wp_maintenance_settings[active]" value="0" <?php if($paramMMode['active']==0) { echo ' checked'; } ?>>&nbsp;Non
            </li>
            <li> &nbsp;</li>

            <!-- TEXTE PERSONNEL POUR LA PAGE -->
            <li><h3>Texte de la page maintenance :</h3>
                <TEXTAREA NAME="wp_maintenance_settings[text_maintenance]" COLS=70 ROWS=4><?php echo $paramMMode['text_maintenance'] ?></TEXTAREA>
            </li>
            <li> &nbsp;</li>

            <!-- COULEUR DU FOND DE PAGE -->
            <li><h3>Choix des couleurs :</h3>
                <div id="pmColor" style="position: relative;">
                       <em>Couleur du fond</em> <br /><input type="text" value="<?php echo $paramMMode['color_bg']; ?>" name="wp_maintenance_settings[color_bg]" class="wpm-color-field" data-default-color="#f1f1f1" /> <br />
                       <em>Couleur du texte</em> <br /><input type="text" value="<?php echo $paramMMode['color_txt']; ?>" name="wp_maintenance_settings[color_txt]" class="wpm-color-field" data-default-color="#888888" />
                </div>
            </li>
            <li> &nbsp;</li>

            <!-- UPLOADER UNE IMAGE -->
            <li><h3>Uploader une image :</h3>
            <?php if($paramMMode['image']) { ?>
            Image actuelle : <br /> <img src="<?php echo $paramMMode['image']; ?>" width="300" style="border:1px solid #333;padding:3px;" /><br />
            <?php } ?>
            <input id="upload_image" size="36" name="wp_maintenance_settings[image]" value="<?php echo $paramMMode['image']; ?>" type="text" /> <a href="#" id="upload_image_button" class="button" OnClick="this.blur();"><span> Sélectionnez / Uploader votre image </span></a>
<br /><small>Entrez une url ou uploader une image.</small>
            </li>
            <li> &nbsp;</li>

            <!-- UTILISER UNE PAGE MAINTENANCE.PHP -->
            <li><h3>Page maintenance du thème :</h3>
                <small>Si ce paramètre est sur Oui, c'est la page maintenance.php de votre thème qui sera affichée</small> &nbsp;<br /><br />
                <input type= "radio" name="wp_maintenance_settings[pageperso]" value="1" <?php if($paramMMode['pageperso']==1) { echo ' checked'; } ?>>&nbsp;Oui&nbsp;&nbsp;&nbsp;
                <input type= "radio" name="wp_maintenance_settings[pageperso]" value="0" <?php if(!$paramMMode['pageperso'] or $paramMMode['pageperso']==0) { echo ' checked'; } ?>>&nbsp;Non
            </li>

            <li> &nbsp;</li>
            <li> &nbsp;</li>
            <li><a href="#" id="submitbutton" OnClick="document.forms['valide_maintenance'].submit();this.blur();" name="Save" class="button-primary"><span> Sauvegarder les Options </span></a></li>
          </ul>
        </div>
    </form>
    <!-- FAIRE UN DON SUR PAYPAL -->
    <div style="border:1px solid #F1F1F1;text-align: center;background-color:#F1F1F1;padding: 15px;">Si vous souhaitez me soutenir pour les développements actuels et futurs :
        <form action="https://www.paypal.com/cgi-bin/webscr" method="post">
        <input type="hidden" name="cmd" value="_s-xclick">
        <input type="hidden" name="hosted_button_id" value="ABGJLUXM5VP58">
        <input type="image" src="https://www.paypalobjects.com/fr_FR/FR/i/btn/btn_donate_SM.gif" border="0" name="submit" alt="PayPal - la solution de paiement en ligne la plus simple et la plus sécurisée !">
        <img alt="" border="0" src="https://www.paypalobjects.com/fr_FR/i/scr/pixel.gif" width="1" height="1">
        </form>
    </div>
    <!-- FIN FAIRE UN DON -->
</div>

