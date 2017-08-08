<?php 


class WPM_Slider extends WP_maintenance {

    var $errors = array();
    
    public $displaySlider = '';
    public $position = 'belowtext';
    public $slides = '';
    
    public static function slider_css() {
        
        // Récupère les paramètres sauvegardés
        if(get_option('wp_maintenance_settings')) { extract(get_option('wp_maintenance_settings')); }
        $paramMMode = get_option('wp_maintenance_settings');
        
        if( isset($paramSliderOptions['slider_width']) ) { $wpmSliderWidth = $paramSliderOptions['slider_width']; } else { $wpmSliderWidth = 50; }
        $addCssSlider = '
<link rel="stylesheet" href="'.WP_PLUGIN_URL.'/wp-maintenance/css/wpm-slideshow.css">
<link rel="stylesheet" href="'.WP_PLUGIN_URL.'/wp-maintenance/css/wpm-responsiveslides.css">
<style type=\'text/css\'>
    .centered-btns_nav { background: transparent url("'.WP_PLUGIN_URL.'/wp-maintenance/images/themes.gif") no-repeat left top; } 
    .large-btns_nav { background: #000 url("'.WP_PLUGIN_URL.'/wp-maintenance/images/themes.gif") no-repeat left 50%; }
    .callbacks_container { width: '.$wpmSliderWidth.'%; }
    @media (max-width: 640px) {
    .callbacks_container {
    width: 95%;
    }
    .callbacks_nav {
    top: 57%;
    }
    }
    .callbacks_nav { background: transparent url("'.WP_PLUGIN_URL.'/wp-maintenance/images/themes.gif") no-repeat left top; }
</style>

        ';
        
        return $addCssSlider;
    }
    
    public static function slider_scripts() {
    
        $addScriptSlider = '
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.8.3/jquery.min.js"></script>
        <script src="'.WP_PLUGIN_URL.'/wp-maintenance/js/wpm-responsiveslides.min.js"></script>';
        return $addScriptSlider;
        
    }
    public static function slider_functions() {
        
        // Récupère les paramètres sauvegardés
        $paramMMode = wp_maintenance::wpm_get_options();
        
        if(get_option('wp_maintenance_slider')) { extract(get_option('wp_maintenance_slider')); }
        $paramSlider = get_option('wp_maintenance_slider');

        if(get_option('wp_maintenance_slider_options')) { extract(get_option('wp_maintenance_slider_options')); }
        $paramSliderOptions = get_option('wp_maintenance_slider_options');
        
        if( isset($paramSlider['slider_image']) && !empty($paramSlider['slider_image']) ) { 
        $lastKeySlide = key($paramSlider['slider_image']);
        }

        $wpmSliderAuto = 'true';
        if( isset( $paramSliderOptions['slider_auto'] ) && $paramSliderOptions['slider_auto']!='' ) { 
            $wpmSliderAuto = $paramSliderOptions['slider_auto'];
        }
        $wpmSliderSpeed = 500;
        if( isset( $paramSliderOptions['slider_speed'] ) && $paramSliderOptions['slider_speed']!='' ) { 
            $wpmSliderSpeed = $paramSliderOptions['slider_speed'];
        }
        $wpmSliderNav = 'false';
        if( isset( $paramSliderOptions['slider_nav'] ) && $paramSliderOptions['slider_nav']!='' ) { 
            $wpmSliderNav = $paramSliderOptions['slider_nav'];
        }
        
        
        $addScriptSlideshow = '
<script>
    // You can also use "$(window).load(function() {"
    $(function () {';

        $addScriptSlideshow .= '
    $("#wpmslider").responsiveSlides({
    auto: '.$wpmSliderAuto.',
    pager: false,
    nav: '.$wpmSliderNav.',
    speed: '.$wpmSliderSpeed.',
    prevText: "'.__('Previous', 'wp-maintenance').'",
    nextText: "'.__('Next', 'wp-maintenance').'", 
    namespace: "callbacks",';
        $addScriptSlideshow .= "
    before: function () {
      $('.events').append(\"<li>before event fired.</li>\");
    },
    after: function () {
      $('.events').append(\"<li>after event fired.</li>\");
    }
    });";

        $addScriptSlideshow .= '
    });
</script>';
        
        return $addScriptSlideshow;
    }
    
    public static function slidershow($position) {

        // Récupère les paramètres sauvegardés
        $paramMMode = wp_maintenance::wpm_get_options();
        $positionSlider = '';
        
        if( isset($paramMMode['enable_slider']) && $paramMMode['enable_slider']==1 ) {       
                          
            if(get_option('wp_maintenance_slider')) { extract(get_option('wp_maintenance_slider')); }
            $paramSlider = get_option('wp_maintenance_slider');

            if(get_option('wp_maintenance_slider_options')) { extract(get_option('wp_maintenance_slider_options')); }
            $paramSliderOptions = get_option('wp_maintenance_slider_options');
            
            $slides = '

            <!-- Slideshow 4 -->
            <div class="callbacks_container">
              <ul class="rslides" id="wpmslider">';
            foreach($paramSlider['slider_image'] as $numSlide=>$slide) {

                if( $paramSlider['slider_image'][$numSlide]['image'] != '' ) { 
                    $slideImg = '';
                    if( isset($paramSlider['slider_image'][$numSlide]['image']) ) {
                        $slideImg = $paramSlider['slider_image'][$numSlide]['image'];
                    }
                    $slideLink = '';
                    if( isset($paramSlider['slider_image'][$numSlide]['link']) ) {
                        $slideLink = $paramSlider['slider_image'][$numSlide]['link'];
                    }
                    $slideText = '';
                    if( isset($paramSlider['slider_image'][$numSlide]['text']) ) {
                        $slideText = stripslashes($paramSlider['slider_image'][$numSlide]['text']);
                    }
                    $slides .= '
                    <li>';
                    if( $slideLink!='' && filter_var($slideLink, FILTER_VALIDATE_URL) ) {
                    $slides .= '
                      <a href="'.$slideLink.'" target="_bank">';
                    }
                    $slides .= '<img src="'.$slideImg.'" alt="'.$slideText.'" title="'.$slideText.'">';
                    if( $slideText!='' ) {
                    $slides .= '
                      <p class="caption">'.$slideText.'</p>';
                    }
                    if( $slideLink!='' && filter_var($slideLink, FILTER_VALIDATE_URL) ) {
                    $slides .= '</a>';
                    }
                    $slides .= '
                    </li>';
                }
            }
            $slides .= '</ul>
            </div>';
            
            $positionSlider = $paramSliderOptions['slider_position'];
        
            if( isset($position) && $position!='' ) {

                if( $positionSlider == $position ) {
                    $positionSlider = $slides;
                } else {
                    $positionSlider = '';
                }

            }
        
        }
        
        return $positionSlider;
    }

}