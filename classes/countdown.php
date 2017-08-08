<?php


class WPM_Countdown extends WP_maintenance {


    var $errors = array();

    public static function display($date = '') {

        // Récupère les paramètres sauvegardés
        $paramMMode = wp_maintenance::wpm_get_options();
        $Counter = '';
        /*********** AJOUT COMPTEUR SUIVANT LES PARAMETRES *********/
        if( isset($paramMMode['active_cpt']) && $paramMMode['active_cpt']==1 && !empty($date) ) {

            if( isset($paramMMode['cptdate']) && !empty($paramMMode['cptdate']) ) {

                if( !isset($paramMMode['disable']) ) { $paramMMode['disable'] = 0; }
            $Counter = '
            <div id="countdown">
                <script language="JavaScript">
                    TargetDate = "'.$date.'";
                    BackColor = "'.$paramMMode['color_cpt_bg'].'";
                    FontSize = "'.$paramMMode['date_cpt_size'].'";
                    ForeColor = "'.$paramMMode['color_cpt'].'";
                    Disable = "'.$paramMMode['disable'].'";
                    UrlDisable = "'.get_option( 'siteurl').'";
                    FontFamily = "'.$paramMMode['font_cpt'].'";
                    CountActive = true;
                    CountStepper = -1;
                    LeadingZero = true;
            ';
            $Counter .= "   DisplayFormat = '<div id=\"wpm-cpt-day\">%%D%%<br /><span id=\"wpm-cpt-days-span\">".__('Days', 'wp-maintenance')."</span></div><div id=\"wpm-cpt-hours\">%%H%%<br /><span id=\"wpm-cpt-hours-span\">".__('Hours', 'wp-maintenance')."</span></div><div id=\"wpm-cpt-minutes\">%%M%%<br /><span id=\"wpm-cpt-minutes-span\">".__('Minutes', 'wp-maintenance')."</span></div>";
            if( isset($paramMMode['active_cpt_s']) && $paramMMode['active_cpt_s']==1 ) {
                $Counter .= '<div id="wpm-cpt-seconds">%%S%%<br /><span id="wpm-cpt-seconds-span">'.__('Seconds', 'wp-maintenance').'</span></div>';
            }
            $Counter .= "';";
            if( isset($paramMMode['message_cpt_fin']) && $paramMMode['message_cpt_fin']!='' ) {
            $Counter .= '
                FinishMessage = "'.trim( stripslashes( preg_replace("/(\r\n|\n|\r)/", "", $paramMMode['message_cpt_fin']) ) ).'";';
            }
            $Counter .= '
                </script>';
            $Counter .= '
            <script language="JavaScript" src="'.WP_PLUGIN_URL.'/wp-maintenance/js/wpm-cpt-script.js"></script>
            </div>';
            }
        }

        return $Counter;
    }

    public static function css() {

        // Récupère les paramètres sauvegardés
        $paramMMode = wp_maintenance::wpm_get_options();

        return '
#wpm-cpt-day, #wpm-cpt-hours, #wpm-cpt-minutes, #wpm-cpt-seconds {}
.cptR-rec_countdown {';
if( isset($paramMMode['date_cpt_size']) ) { $wpmStyle .= 'font-size:'.$paramMMode['date_cpt_size'].'px;'; }
if( isset($paramMMode['font_cpt']) ) { $wpmStyle .= 'font-family: '.wpm_format_font($paramMMode['font_cpt']).', serif;'; }
$wpmStyle .= '
}

@media screen and (min-width: 200px) and (max-width: 480px) {

    .cptR-rec_countdown {';
        if( isset($paramMMode['date_cpt_size']) ) { $wpmStyle .= 'font-size:'.($paramMMode['date_cpt_size']*0.6).'px;'; }
    $wpmStyle .= '}
    div.bloc {';
        if( isset($paramMMode['font_bottom_size']) ) { $wpmStyle .= 'font-size: '.($paramMMode['font_bottom_size']*0.8).'px;'; }
    $wpmStyle .= '}
    #wpm-cpt-day, #wpm-cpt-hours, #wpm-cpt-minutes, #wpm-cpt-seconds {
        /*width:20%;*/
    }

}
@media (max-width: 640px) {
    .cptR-rec_countdown {';
        if( isset($paramMMode['date_cpt_size']) ) { $wpmStyle .= 'font-size:'.($paramMMode['date_cpt_size']*0.5).'px;'; }
    $wpmStyle .= '
        text-align:center;
    }
    #wpm-cpt-day, #wpm-cpt-hours, #wpm-cpt-minutes, #wpm-cpt-seconds {
        /*width:20%;*/
        text-align:center;
    }
}';
    }

}
