<?php

defined( 'ABSPATH' ) or die( 'Not allowed' );

$messageUpdate = 0;
/* Update des paramètres */
if( isset($_POST['action']) && $_POST['action'] == 'update_settings' ) {

    update_option('wp_maintenance_limit', $_POST["wp_maintenance_limit"]);
    update_option('wp_maintenance_ipaddresses', $_POST["wp_maintenance_ipaddresses"]);
    
    $options_saved = wpm_update_settings($_POST["wp_maintenance_settings"]);
    $messageUpdate = 1;
}

// Récupère les paramètres sauvegardés
if(get_option('wp_maintenance_settings')) { extract(get_option('wp_maintenance_settings')); }
$paramMMode = get_option('wp_maintenance_settings');

// Récupère les Rôles et capabilités
if(get_option('wp_maintenance_limit')) { extract(get_option('wp_maintenance_limit')); }
$paramLimit = get_option('wp_maintenance_limit');

// Récupère les ip autorisee
$paramIpAddress = get_option('wp_maintenance_ipaddresses');

?>
<script type="text/javascript">

jQuery(document).ready(function() {

  jQuery( ".postbox .hndle" ).on( "mouseover", function() {
    jQuery( this ).css( "cursor", "pointer" );
  });
  /* Sliding the panels */
  jQuery(".postbox").on('click', '.handlediv', function(){
    jQuery(this).siblings(".inside").slideToggle();
  });
  jQuery(".postbox").on('click', '.hndle', function(){
    jQuery(this).siblings(".inside").slideToggle();
  });
    
});
</script>
<style>
    .CodeMirror {
      border: 1px solid #eee;
      height: auto;
    }
</style>
<div class="wrap">
    
    <form method="post" action="" name="valide_settings">
        <input type="hidden" name="action" value="update_settings" />
        
    <!-- HEADER -->
    <?php echo wpm_get_header( __('Settings', 'wp-maintenance'), 'dashicons-admin-generic', $messageUpdate ) ?>
    <!-- END HEADER -->
        
    <div style="margin-top:80px;">
        
        <div style="float:left;width:73%;margin-right:1%;border: 1px solid #ddd;background-color:#fff;padding:10px;">
            
                <div>
                    <div style="float:left; width:70%;"><h3><?php _e('Theme maintenance page', 'wp-maintenance'); ?></h3><p><?php _e('If you would use your maintenance.php page in your theme folder, click Yes.', 'wp-maintenance'); ?></p></div>
                    <div style="float:left; width:30%;margin-top:25px;text-align:right;">
                        <div class="switch-field">
                            <input class="switch_left" onclick="AfficherTexte('option-pageperso');" type="radio" id="switch_pageperso" name="wp_maintenance_settings[pageperso]" value="1" <?php if( isset($paramMMode['pageperso']) && $paramMMode['pageperso']==1 ) { echo ' checked'; } ?>/>
                            <label for="switch_pageperso"><?php _e('Yes', 'wp-maintenance'); ?></label>
                            <input class="switch_right" onclick="CacherTexte('option-pageperso');" type="radio" id="switch_pageperso_no" name="wp_maintenance_settings[pageperso]" value="0" <?php if( empty($paramMMode['pageperso']) || (isset($paramMMode['pageperso']) && $paramMMode['pageperso']==0) ) { echo ' checked'; } ?> />
                            <label for="switch_pageperso_no"><?php _e('No', 'wp-maintenance'); ?></label>
                        </div>
                    </div>
                    <div class="clear"></div>
                </div>
                
                <div id="option-pageperso" style="<?php if( empty($paramMMode['pageperso']) || isset($paramMMode['pageperso']) && $paramMMode['pageperso']==0) { echo ' display:none;'; } else { echo 'display:block'; } ?>">    
                    <?php _e('You can use this shortcode to include Google Analytics code:', 'wp-maintenance'); ?> <input type="text" value="do_shortcode('[wpm_analytics']);" onclick="select()" style="width:250px;" /><br /><?php _e('You can use this shortcode to include Social Networks icons:', 'wp-maintenance'); ?> <input type="text" value="do_shortcode('[wpm_social]');" onclick="select()" style="width:250px;" /><br />
                </div>
            
                <div style="margin-top:15px;margin-bottom:15px;"><hr /></div>
            
                <div>
                    <div style="float:left; width:70%;"><h3><?php _e('Delete custom settings upon plugin deactivation?', 'wp-maintenance'); ?></h3><span class="description"><?php _e( 'If you set "Yes" all custom settings will be deleted from database upon plugin deactivation', 'wp-maintenance' ); ?></span></div>
                    <div style="float:left; width:30%;margin-top:25px;text-align:right;">
                        <div class="switch-field">
                            <input class="switch_left" type="radio" id="switch_deletedb" name="wp_maintenance_settings[dashboard_delete_db]" value="Yes" <?php if( isset($paramMMode['dashboard_delete_db']) && $paramMMode['dashboard_delete_db']=='Yes' ) { echo ' checked'; } ?>/>
                            <label for="switch_deletedb"><?php _e('Yes', 'wp-maintenance'); ?></label>
                            <input class="switch_right" type="radio" id="switch_deletedb_no" name="wp_maintenance_settings[dashboard_delete_db]" value="No" <?php if( empty($paramMMode['dashboard_delete_db']) || (isset($paramMMode['dashboard_delete_db']) && $paramMMode['dashboard_delete_db']=='No') ) { echo ' checked'; } ?> />
                            <label for="switch_deletedb_no"><?php _e('No', 'wp-maintenance'); ?></label>
                        </div>
                    </div>
                    <div class="clear"></div>
                </div>
                <div style="margin-top:15px;margin-bottom:15px;"><hr /></div>
            
                <!-- Roles and Capabilities -->
                <h3><?php _e('Roles and Capabilities:', 'wp-maintenance'); ?></h3>
                <?php _e('Allow the site to display these roles:', 'wp-maintenance'); ?>&nbsp;<br /><br />
                <input type="hidden" name="wp_maintenance_limit[administrator]" value="administrator" />
                <div style="text-align:left;">
                    <?php
                        $roles = wpm_get_roles();
                        foreach($roles as $role=>$name) {
                            $limitCheck = '';
                            if( isset($paramLimit[$role]) && $paramLimit[$role]==$role) { $limitCheck = ' checked'; }
                            if( $role=='administrator') {
                                $limitCheck = 'checked disabled="disabled"';
                            }
                    ?>
                        <input type="checkbox" class="switch-field" name="wp_maintenance_limit[<?php echo $role; ?>]" value="<?php echo $role; ?>"<?php echo $limitCheck; ?> /><?php echo $name; ?>&nbsp;
                    <?php }//end foreach ?>
                </div>
            
                <div style="margin-top:15px;margin-bottom:15px;"><hr /></div>
            
                <!-- IP addresses autorized -->
                <h3><?php _e('IP autorized:', 'wp-maintenance'); ?></h3>
                <?php _e('Allow the site to display these IP addresses. Please, enter one IP address by line:', 'wp-maintenance'); ?>&nbsp;<br /><br />
                <textarea name="wp_maintenance_ipaddresses" class="wpm-form-field" ROWS="5" style="width:80%;"><?php if( isset($paramIpAddress) && $paramIpAddress!='' ) { echo $paramIpAddress; } ?></textarea>
                
                <div style="margin-top:15px;margin-bottom:15px;"><hr /></div>
            
                <!-- ID pages autorized -->
                <h3><?php _e('ID pages autorized:', 'wp-maintenance'); ?></h3>
                <?php _e('Allow the site to display these ID pages. Please, enter the ID pages separate with comma :', 'wp-maintenance'); ?>&nbsp;<br /><br />
                <input name="wp_maintenance_settings[id_pages]" class="wpm-form-field" size="70" value="<?php if( isset($paramMMode['id_pages']) && $paramMMode['id_pages']!='' ) { echo $paramMMode['id_pages']; } ?>" />
                
                <div style="margin-top:15px;margin-bottom:15px;"><hr /></div>
            
                <!-- Header Code -->
                <h3><?php _e('Header Code:', 'wp-maintenance'); ?></h3>
                <?php _e('The following code will add to the <head> tag. Useful if you need to add additional scripts such as CSS or JS.', 'wp-maintenance'); ?>&nbsp;<br /><br />
                <textarea id="headercode" name="wp_maintenance_settings[headercode]" COLS=50 ROWS=2><?php if( isset($paramMMode['headercode']) && $paramMMode['headercode']!='' ) { echo stripslashes($paramMMode['headercode']); }  ?></textarea><br />
                
                
                
                
                <?php submit_button(); ?>
            
            
        </div>

        <?php echo wpm_sidebar(); ?>
        
    </div>
    </form>
    <script>
        var editor = CodeMirror.fromTextArea(document.getElementById("headercode"), {
        lineNumbers: true,
        matchBrackets: true,
        textWrapping: true,
        lineWrapping: true,
        mode: "text/x-scss",
        theme:"material"
        });
    </script>
    <div style="margin-top:15px;margin-bottom:15px;"></div>
    <div class="postbox">
        <div class="handlediv" title="<?php _e('Click to toggle', 'wp-maintenance'); ?>"><br></div>
        <h3 class="hndle" title="<?php _e('Click to toggle', 'wp-maintenance'); ?>"><span class="dashicons dashicons-download"></span> <?php _e( 'Export Settings', 'wp-maintenance' ); ?></h3>
        <div class="inside">
            <form method="post">
                <p>
                  <input type="hidden" name="wpm_action" value="export_settings" />
                </p>
                <p>
                    <?php wp_nonce_field( 'wpm_export_nonce', 'wpm_export_nonce' ); ?>
                    <?php submit_button( __( 'Export', 'wp-maintenance' ), 'secondary', 'submit', false ); ?>
                </p>
            </form>
        </div>
    </div>
    <div class="postbox">
        <div class="handlediv" title="<?php _e('Click to toggle', 'wp-maintenance'); ?>"><br></div>
        <h3 class="hndle" title="<?php _e('Click to toggle', 'wp-maintenance'); ?>"><span class="dashicons dashicons-upload"></span> <?php _e( 'Import Settings', 'wp-maintenance' ); ?></h3>
        <div class="inside">
          <p><?php _e( 'Import the plugin settings from a .json file. This file can be obtained by exporting the settings on another site using the form above.', 'wp-maintenance' ); ?></p>
          <form method="post" enctype="multipart/form-data">
              <p>
                  <input type="file" name="wpm_import_file"/>
              </p>
              <p>
                  <input type="hidden" name="wpm_action" value="import_settings" />
                  <?php wp_nonce_field( 'wpm_import_nonce', 'wpm_import_nonce' ); ?>
                  <?php submit_button( __( 'Import', 'wp-maintenance' ), 'secondary', 'submit', false ); ?>
              </p>
          </form>
        </div>
    </div>
    
    <?php echo wpm_footer(); ?>
    
</div>