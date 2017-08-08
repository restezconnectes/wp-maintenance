<?php

defined( 'ABSPATH' ) or die( 'Not allowed' );

$messageUpdate = 0;
/* Update des paramètres */
if( isset($_POST['action']) && $_POST['action'] == 'update_countdown' ) {

    $options_saved = wpm_update_settings($_POST["wp_maintenance_settings"]);
    $messageUpdate = 1;
}

// Récupère les paramètres sauvegardés
if(get_option('wp_maintenance_settings')) { extract(get_option('wp_maintenance_settings')); }
$paramMMode = get_option('wp_maintenance_settings');
?>

<div class="wrap">

    <form method="post" action="" name="valide_settings">
        <input type="hidden" name="action" value="update_countdown" />
        
    <!-- HEADER -->
    <?php echo wpm_get_header( __('Countdown', 'wp-maintenance'), 'dashicons-clock', $messageUpdate ) ?>
    <!-- END HEADER -->

    <div style="margin-top: 80px;">
        
        <div style="float:left;width:73%;margin-right:1%;border: 1px solid #ddd;background-color:#fff;padding:10px;">
            
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
            <div>
                <div style="float:left; width:70%;">
                    <h3><?php _e('Enable a countdown ?', 'wp-maintenance'); ?>
                    <?php if( isset($paramMMode['active_cpt']) && $paramMMode['active_cpt']==1 ) { ?>
                    <?php } ?>
                    </h3>
                </div>
                <div style="float:left; width:30%;text-align:right;margin-top:5px;">
                    <div class="switch-field">
                        <input class="switch_left" type="radio" onclick="AfficherTexte('option-countdown');" id="switch_countdown" name="wp_maintenance_settings[active_cpt]" value="1" <?php if( isset($paramMMode['active_cpt']) && $paramMMode['active_cpt']==1) { echo ' checked'; } ?>/>
                        <label for="switch_countdown"><?php _e('Yes', 'wp-maintenance'); ?></label>
                        <input class="switch_right" type="radio" onclick="CacherTexte('option-countdown');" id="switch_countdown_no" name="wp_maintenance_settings[active_cpt]" value="0" <?php if( empty($paramMMode['active_cpt']) || isset($paramMMode['active_cpt']) && $paramMMode['active_cpt']==0) { echo ' checked'; } ?> />
                        <label for="switch_countdown_no"><?php _e('No', 'wp-maintenance'); ?></label>
                    </div>
                </div>
                <div class="clear"></div>
            </div>
            
            
            <div id="option-countdown" style="<?php if( empty($paramMMode['active_cpt']) || isset($paramMMode['active_cpt']) && $paramMMode['active_cpt']==0) { echo ' display:none;'; } else { echo 'display:block'; } ?>">
                
                <div style="margin-top:15px;margin-bottom:15px;"><hr /></div>
                <div style="text-align:right;"><a href="<?php echo admin_url() ?>admin.php?page=wp-maintenance-colors#countdown" title="<?php _e('Personnalize Colors and Fonts', 'wp-maintenance'); ?>" alt="<?php _e('Personnalize Colors and Fonts', 'wp-maintenance'); ?>" class="wpmadashicons"><span class="dashicons dashicons-art"></span> <?php _e('Personnalize Colors and Fonts', 'wp-maintenance'); ?></a></div>
                
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
                    <small><?php _e('Select the launch date/time', 'wp-maintenance'); ?></small><br /><img src="<?php echo WPM_PLUGIN_URL.'images/schedule_clock.png'; ?>" class="datepicker" width="48" height="48" style="vertical-align: middle;margin-right:5px;">&nbsp;<input id="cptdate" class="datepicker" name="wp_maintenance_settings[cptdate]" type="text" autofocuss data-value="<?php echo $startDate; ?>"> <?php _e('at', 'wp-maintenance'); ?> <input id="cpttime" class="timepicker" type="time" name="wp_maintenance_settings[cpttime]" value="<?php echo $startHour; ?>" size="4" autofocuss>                                
                    <div id="wpmdatecontainer"></div>
                    <br /><br />
                    <div>
                        <div style="float:left;width:190px;">
                            <div class="switch-field" style="margin-left:0px!important;">
                                <input class="switch_left" type="radio" id="switch_left" name="wp_maintenance_settings[active_cpt_s]" value="1" <?php if( isset($paramMMode['active_cpt_s']) && $paramMMode['active_cpt_s']==1) { echo ' checked'; } ?>/>
                                <label for="switch_left"><?php _e('Yes', 'wp-maintenance'); ?></label>
                                <input class="switch_right" type="radio" id="switch_right" name="wp_maintenance_settings[active_cpt_s]" value="0" <?php if( empty($paramMMode['active_cpt_s']) || isset($paramMMode['active_cpt_s']) && $paramMMode['active_cpt_s']==0) { echo ' checked'; } ?> />
                                <label for="switch_right"><?php _e('No', 'wp-maintenance'); ?></label>
                            </div>
                        </div>
                        <div style="float:left;margin-top:12px;"><?php _e('Enable seconds ?', 'wp-maintenance'); ?></div>
                        <div class="clear"></div>
                    </div>
                    <div>
                        <div style="float:left;width:190px;">
                            <div class="switch-field" style="margin-left:0px!important;">
                                <input class="switch_left" type="radio" id="switch_disable" name="wp_maintenance_settings[disable]" value="1" <?php if( isset($paramMMode['disable']) && $paramMMode['disable']==1) { echo ' checked'; } ?>/>
                                <label for="switch_disable"><?php _e('Yes', 'wp-maintenance'); ?></label>
                                <input class="switch_right" type="radio" id="switch_disable_no" name="wp_maintenance_settings[disable]" value="0" <?php if( empty($paramMMode['disable']) || isset($paramMMode['disable']) && $paramMMode['disable']==0) { echo ' checked'; } ?> />
                                <label for="switch_disable_no"><?php _e('No', 'wp-maintenance'); ?></label>
                            </div>
                        </div>
                        <div style="float:left;margin-top:12px;"><?php _e('Disable maintenance mode at the end of the countdown?', 'wp-maintenance'); ?></div>
                        <div class="clear"></div>
                    </div>
                    <br />
                    <?php _e('End message:', 'wp-maintenance'); ?><br />                
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
                    if( isset($paramMMode['message_cpt_fin']) ) { $textCpt_fin = stripslashes($paramMMode['message_cpt_fin']); } 
                    ?>
                    <?php wp_editor( nl2br($textCpt_fin), 'message_cpt_fin', $settingsCountdown ); ?><br />

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
                <?php submit_button(); ?>
                </div>
        </div>

        <?php echo wpm_sidebar(); ?>

    </div>
    </form>
            
    <?php echo wpm_footer(); ?>
    
</div>