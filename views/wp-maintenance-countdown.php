<?php

defined( 'ABSPATH' ) or die( 'Not allowed' );

$messageUpdate = 0;
/* Update des paramètres */
if( isset($_POST['action']) && $_POST['action'] == 'update_countdown' && wp_verify_nonce($_POST['security-countdown'], 'valid-countdown') ) {

    if( empty($_POST["wp_maintenance_settings"]["active_cpt"]) ) { $_POST["wp_maintenance_settings"]["active_cpt"] = 0; }
    if( empty($_POST["wp_maintenance_settings"]["active_cpt_s"]) ) { $_POST["wp_maintenance_settings"]["active_cpt_s"] = 0; }
    if( empty($_POST["wp_maintenance_settings"]["disable"]) ) { $_POST["wp_maintenance_settings"]["disable"] = 0; }
    
    $options_saved = wpm_update_settings($_POST["wp_maintenance_settings"]);
    $messageUpdate = 1;
}

// Récupère les paramètres sauvegardés
if(get_option('wp_maintenance_settings')) { extract(get_option('wp_maintenance_settings')); }
$paramMMode = get_option('wp_maintenance_settings');
?>

<div class="wrap">
    
    <!-- HEADER -->
    <?php echo wpm_get_header( $messageUpdate ) ?>
    <!-- END HEADER -->

    <div class="wp-maintenance-wrapper">

        <?php echo wpm_get_nav2(); ?>
        
        <div class="wp-maintenance-tab-content wp-maintenance-tab-content-welcome" id="wp-maintenance-tab-content">

            <form method="post" action="" id="valide_settings" name="valide_settings">
                <input type="hidden" name="action" value="update_countdown" />
                <?php wp_nonce_field('valid-countdown', 'security-countdown'); ?>

                <!-- ACTIVER COMPTEUR -->
                <div class="wp-maintenance-module-options-block">
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
                    <div class="wp-maintenance-settings-section-header"><h3 class="wp-maintenance-settings-section-title" id="module-import_export"><?php _e('Enable a countdown', 'wp-maintenance'); ?></h3></div>
                    <p>
                        <label class="wp-maintenance-container"><span class="wp-maintenance-label-text"><?php _e('Yes, enable a countdown', 'wp-maintenance'); ?></span>
                            <input type="checkbox" name="wp_maintenance_settings[active_cpt]" value="1" <?php if( isset($paramMMode['active_cpt']) && $paramMMode['active_cpt']==1) { echo ' checked'; } ?>>
                            <span class="wp-maintenance-checkmark"></span>
                        </label>
                    </p>
                    <p class="submit"><button type="submit" name="footer_submit" id="footer_submit" class="wp-maintenance-button wp-maintenance-button-primary"><?php _e('Save', 'wp-maintenance'); ?></button></p>
                </div>
                
                <div class="wp-maintenance-module-options-block">
                    <div class="wp-maintenance-settings-section-header">
                        <h3 class="wp-maintenance-settings-section-title" id="module-import_export"><?php _e('Date/time Launch', 'wp-maintenance'); ?></h3>
                    </div>

                    <h3><?php _e('Select the launch date/time', 'wp-maintenance'); ?></h3>

                    <div class="wp-maintenance-setting-row">
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
                                //$startHour = $newMin[0].':'.ceil($newMin[1]/5)*5;
                            }                                
                        ?>
                        <img src="<?php echo WPM_PLUGIN_URL.'images/schedule_clock.png'; ?>" class="datepicker" width="48" height="48" style="vertical-align: middle;margin-right:5px;">&nbsp;<input id="cptdate" class="datepicker" name="wp_maintenance_settings[cptdate]" type="text" autofocuss data-value="<?php echo $startDate; ?>"> <?php _e('at', 'wp-maintenance'); ?> <input id="cpttime" class="timepicker" type="time" name="wp_maintenance_settings[cpttime]" value="<?php echo $startHour; ?>" size="6" autofocuss>                                
                        <div id="wpmdatecontainer"></div>
                    </div>
                    <p class="submit"><button type="submit" name="footer_submit" id="footer_submit" class="wp-maintenance-button wp-maintenance-button-primary"><?php _e('Save', 'wp-maintenance'); ?></button></p>
                </div>
                <div class="wp-maintenance-module-options-block">

                    <div class="wp-maintenance-settings-section-header">
                        <h3 class="wp-maintenance-settings-section-title" id="module-import_export"><?php _e('Others Settings', 'wp-maintenance'); ?></h3>
                    </div>

                    <h3><?php _e('Enable seconds ?', 'wp-maintenance'); ?></h3>

                    <p>
                        <label class="wp-maintenance-container"><span class="wp-maintenance-label-text"><?php _e('Yes, enable seconds', 'wp-maintenance'); ?></span>
                            <input type="checkbox" name="wp_maintenance_settings[active_cpt_s]" value="1" <?php if( isset($paramMMode['active_cpt_s']) && $paramMMode['active_cpt_s']==1) { echo ' checked'; } ?>>
                            <span class="wp-maintenance-checkmark"></span>
                        </label>
                    </p>
                    <h3><?php _e('Disable maintenance mode at the end of the countdown?', 'wp-maintenance'); ?></h3>
                    <p>
                        <label class="wp-maintenance-container"><span class="wp-maintenance-label-text"><?php _e('Yes, disable maintenance mode at the end of countdown', 'wp-maintenance'); ?></span>
                            <input type="checkbox" name="wp_maintenance_settings[disable]" value="1" <?php if( isset($paramMMode['disable']) && $paramMMode['disable']==1) { echo ' checked'; } ?>>
                            <span class="wp-maintenance-checkmark"></span>
                        </label>
                    </p>
                    <h3><?php _e('End message after end countdown', 'wp-maintenance'); ?></h3>           
                    <?php 
                        $settingsCountdown =   array(
                            'wpautop' => true, // use wpautop?
                            'media_buttons' => false, // show insert/upload button(s)
                            'textarea_name' => 'wp_maintenance_settings[message_cpt_fin]', // set the textarea name to something different, square brackets [] can be used here
                            'textarea_rows' => get_option('default_post_edit_rows', 10), // rows="..."
                            'tabindex' => '',
                            'editor_css' => '', //  extra styles for both visual and HTML editors buttons, 
                            'editor_class' => 'message_cpt_fin', // add extra class(es) to the editor textarea
                            'teeny' => true, // output the minimal editor config used in Press This
                            'dfw' => false, // replace the default fullscreen with DFW (supported on the front-end in WordPress 3.4)
                            'tinymce' => true, // load TinyMCE, can be used to pass settings directly to TinyMCE using an array()
                            'quicktags' => true // load Quicktags, can be used to pass settings directly to Quicktags using an array()
                        );
                    $textCpt_fin =  '';
                    /*if( isset($paramMMode['message_cpt_fin']) && $paramMMode['message_cpt_fin']!='' ) { $textCpt_fin = esc_textarea(stripslashes($paramMMode['message_cpt_fin'])); } else { $textCpt_fin = ' '; }*/
                    ?>
                    <?php wp_editor( nl2br($textCpt_fin), 'message_cpt_fin', $settingsCountdown ); ?>

                    <p class="submit"><button type="submit" name="footer_submit" id="footer_submit" class="wp-maintenance-button wp-maintenance-button-primary"><?php _e('Save', 'wp-maintenance'); ?></button></p>

                </div>
            </form>
        </div>

    </div>

    <?php echo wpm_footer(); ?>
    
</div>
<script type="text/javascript">                                    

    jQuery(document).ready(function() {

        var $input = jQuery( '.datepicker' ).pickadate({
            formatSubmit: 'yyyy/mm/dd',
            container: '#wpmdatecontainer',
            closeOnSelect: true,
            closeOnClear: false,
            firstDay: 1,
            min: new Date(<?php echo date('Y').','.(date('m')-1).','.date('d'); ?>),
            monthsFull: [ '<?php _e('January', 'wp-maintenance'); ?>', '<?php _e('February', 'wp-maintenance'); ?>', '<?php _e('March', 'wp-maintenance'); ?>', '<?php _e('April', 'wp-maintenance'); ?>', '<?php _e('May', 'wp-maintenance'); ?>', '<?php _e('June', 'wp-maintenance'); ?>', '<?php _e('July', 'wp-maintenance'); ?>', '<?php _e('August', 'wp-maintenance'); ?>', '<?php _e('September', 'wp-maintenance'); ?>', '<?php _e('October', 'wp-maintenance'); ?>', '<?php _e('November', 'wp-maintenance'); ?>', '<?php _e('December', 'wp-maintenance'); ?>' ],
            monthsShort: [ 'Jan', 'Fev', 'Mar', 'Avr', 'Mai', 'Juin', 'Juil', 'Aou', 'Sep', 'Oct', 'Nov', 'Dec' ],
            weekdaysShort: [ '<?php _e('Sunday', 'wp-maintenance'); ?>', '<?php _e('Monday', 'wp-maintenance'); ?>', '<?php _e('Tuesday', 'wp-maintenance'); ?>', '<?php _e('Wednesday', 'wp-maintenance'); ?>', '<?php _e('Thurday', 'wp-maintenance'); ?>', '<?php _e('Friday', 'wp-maintenance'); ?>', '<?php _e('Saturday', 'wp-maintenance'); ?>' ],
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