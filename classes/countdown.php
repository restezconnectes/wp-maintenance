<?php


class WPM_Countdown extends WP_maintenance {


    var $errors = array();

    public static function display($date = '') {

        // Récupère les paramètres sauvegardés COUNTDOWN
        if(get_option('wp_maintenance_settings_countdown')) { extract(get_option('wp_maintenance_settings_countdown')); }
        $paramsCountdown = get_option('wp_maintenance_settings_countdown');

        // Récupère les paramètres sauvegardés COLORS
        if(get_option('wp_maintenance_settings_colors')) { extract(get_option('wp_maintenance_settings_colors')); }
        $paramsColors = get_option('wp_maintenance_settings_colors');

        // Récupère si le status est actif ou non 
        $statusActive = get_option('wp_maintenance_active');
        $Counter = '';
        
        /*********** AJOUT COMPTEUR SUIVANT LES PARAMETRES *********/
        if( isset($paramsCountdown['active_cpt']) && $paramsCountdown['active_cpt']==1 && !empty($date) ) {

            if( isset($paramsCountdown['cptdate']) && !empty($paramsCountdown['cptdate']) ) {

                if( !isset($paramsCountdown['disable']) || $statusActive==0 ) { $paramsCountdown['disable'] = 0; }
                
                $Counter = '
                <div id="countdown">
                    <script language="JavaScript">
                        TargetDate = "'.$date.'";
                        BackColor = "'.$paramsColors['color_cpt_bg'].'";
                        FontSize = "'.$paramsColors['date_cpt_size'].'";
                        ForeColor = "'.$paramsColors['color_cpt'].'";
                        Disable = "'.$paramsCountdown['disable'].'";
                        UrlDisable = "'.get_option( 'siteurl').'";
                        FontFamily = "'.wpm_format_font($paramsColors['font_cpt']).'";
                        FontTextSize = "'.$paramsColors['cpt_end_size'].'";
                        CountActive = true;
                        CountStepper = -1;
                        LeadingZero = true;
                ';
                $Counter .= "   DisplayFormat = '<div id=\"wpm-cpt-day\">%%D%%<br /><span id=\"wpm-cpt-days-span\">".__('Days', 'wp-maintenance')."</span></div><div class=\"wpm_ctp_sep\" style=\"float:left;\">:</div><div id=\"wpm-cpt-hours\">%%H%%<br /><span id=\"wpm-cpt-hours-span\">".__('Hours', 'wp-maintenance')."</span></div><div class=\"wpm_ctp_sep\" style=\"float:left;\">:</div><div id=\"wpm-cpt-minutes\">%%M%%<br /><span id=\"wpm-cpt-minutes-span\">".__('Minutes', 'wp-maintenance')."</span></div>";
                if( isset($paramsCountdown['active_cpt_s']) && $paramsCountdown['active_cpt_s']==1 ) {
                    $Counter .= '<div class="wpm_ctp_sep" style="float:left;">:</div><div id="wpm-cpt-seconds">%%S%%<br /><span id="wpm-cpt-seconds-span">'.__('Seconds', 'wp-maintenance').'</span></div>';
                }
                $Counter .= "';";
                if( isset($paramsCountdown['message_cpt_fin']) && $paramsCountdown['message_cpt_fin']!='' ) {
                    $Counter .= '
                    FinishMessage = "'.trim( stripslashes( preg_replace("/(\r\n|\n|\r)/", "", $paramsCountdown['message_cpt_fin']) ) ).'";';
                } else {
                    $Counter .= '
                    FinishMessage = "&nbsp;";';
                }
                $Counter .= '
                    </script>';
                $Counter .= '
                <script language="JavaScript" src="'.plugins_url( '../js/wpm-cpt-script.js', __FILE__ ).'"></script>
                </div>';
            }
        }

        return $Counter;
    }

    public static function css() {

        // Récupère les paramètres sauvegardés
        $paramsCountdown = wp_maintenance::wpm_get_options();
        $wpmStyle = '';
        if( isset($paramsCountdown['active_cpt']) && $paramsCountdown['active_cpt']==1) {
        $wpmStyle .= '
#wpm-cpt-day, #wpm-cpt-hours, #wpm-cpt-minutes, #wpm-cpt-seconds {}
.cptR-rec_countdown {';
if( isset($paramsCountdown['date_cpt_size']) ) { $wpmStyle .= 'font-size:'.$paramsCountdown['date_cpt_size'].'px;'; }
if( isset($paramsCountdown['font_cpt']) ) { $wpmStyle .= 'font-family: '.wpm_format_font($paramsCountdown['font_cpt']).', serif;'; }
$wpmStyle .= '
}

@media screen and (min-width: 200px) and (max-width: 480px) {

    .cptR-rec_countdown {';
        if( isset($paramsCountdown['date_cpt_size']) ) { $wpmStyle .= 'font-size:'.($paramsCountdown['date_cpt_size']*0.6).'px;'; }
    $wpmStyle .= '}
    div.bloc {';
        if( isset($paramsCountdown['font_bottom_size']) ) { $wpmStyle .= 'font-size: '.($paramsCountdown['font_bottom_size']*0.8).'px;'; }
    $wpmStyle .= '}
    #wpm-cpt-day, #wpm-cpt-hours, #wpm-cpt-minutes, #wpm-cpt-seconds { }

}
@media (max-width: 640px) {
    .cptR-rec_countdown {';
        if( isset($paramsCountdown['date_cpt_size']) ) { $wpmStyle .= 'font-size:'.($paramsCountdown['date_cpt_size']*0.5).'px;'; }
    $wpmStyle .= 'text-align:center;}
    #wpm-cpt-day, #wpm-cpt-hours, #wpm-cpt-minutes, #wpm-cpt-seconds {text-align:center;}
}';
        }
        return $wpmStyle;
    }
    

}
