<?php

defined( 'ABSPATH' ) or die( 'Not allowed' );

$messageUpdate = 0;
/* Update des paramètres */
if( isset($_POST['action']) && $_POST['action'] == 'update_pictures' ) {

    if( isset($_POST["wpm_maintenance_detete"]) && is_array($_POST["wpm_maintenance_detete"]) ) {
        foreach($_POST["wpm_maintenance_detete"] as $delSlideId=>$delSlideTrue) {
            if (array_key_exists($delSlideId, $_POST["wp_maintenance_slider"]["slider_image"])) {
                unset($_POST["wp_maintenance_slider"]["slider_image"][$delSlideId]);
                unset($_POST["wp_maintenance_slider"]["slider_text"][$delSlideId]);
                unset($_POST["wp_maintenance_slider"]["slider_link"][$delSlideId]);
            }
        }
    }
    
    $options_saved = wpm_update_settings($_POST["wp_maintenance_settings"]);
    update_option('wp_maintenance_slider', $_POST["wp_maintenance_slider"]);    
    update_option('wp_maintenance_slider_options', $_POST["wp_maintenance_slider_options"]);

    $messageUpdate = 1;
}

// Récupère les paramètres sauvegardés
if(get_option('wp_maintenance_settings')) { extract(get_option('wp_maintenance_settings')); }
$paramMMode = get_option('wp_maintenance_settings');

if(get_option('wp_maintenance_slider')) { extract(get_option('wp_maintenance_slider')); }
$paramSlider = get_option('wp_maintenance_slider');

if(get_option('wp_maintenance_slider_options')) { extract(get_option('wp_maintenance_slider_options')); }
$paramSliderOptions = get_option('wp_maintenance_slider_options');

?>
<style>
    #pattern { text-align: left; margin: 5px 0; word-spacing: -1em;list-style-type: none; }
    #pattern li { display: inline-block; list-style: none;margin-right:15px;text-align:center;  }
    #pattern li.current { background: #66CC00; color: #fff; }
    </style>
<script type="text/javascript">
function toggleTable(texte) {
     var elem=document.getElementById(texte);
     var hide = elem.style.display == "none";
     if (hide) {
         elem.style.display="block";
    } 
    else {
       elem.style.display="none";
    }
}
</script>
<div class="wrap">
    
    <form method="post" action="" name="valide_settings">
            <input type="hidden" name="action" value="update_pictures" />

    <!-- HEADER -->
    <?php echo wpm_get_header( __('Picture', 'wp-maintenance'), 'dashicons-format-gallery', $messageUpdate ) ?>
    <!-- END HEADER -->

    <div style="margin-top: 40px;">
        <div id="wpm-column1">

            <div id="encart-option-logo">
                <div style="float:left;width:68%;margin-right:10px;">

                    <h3><?php _e('Header picture', 'wp-maintenance'); ?></h3>                
                    <small><?php _e('Enter a URL or upload an image.', 'wp-maintenance'); ?></small><br />
                    <input id="upload_image" size="80%" name="wp_maintenance_settings[image]" value="<?php if( isset($paramMMode['image']) && $paramMMode['image']!='' ) { echo $paramMMode['image']; } ?>" type="text" class="wpm-form-field" /><br /><a href="#" id="upload_image_button" class="button" OnClick="this.blur();"><span> <?php _e('Select or Upload your picture', 'wp-maintenance'); ?> </span></a><br />
                    <span class="description"><?php _e( 'URL path to image to replace default WordPress Logo. (You can upload your image with the WordPress media uploader)', 'wp-maintenance' ); ?></span><br /><br />
                        <span class="description"><?php _e( 'Your Logo width (Enter in pixels). Default: 310px', 'wp-maintenance' ); ?></span> <input type="text" value="<?php if( isset($paramMMode['image_width']) && $paramMMode['image_width']!='' ) { echo $paramMMode['image_width']; } ?>" name="wp_maintenance_settings[image_width]" /> <br />
                        <span class="description"><?php _e( 'Your Logo Height (Enter in pixels). Default: 185px', 'wp-maintenance' ); ?></span> <input type="text" value="<?php if( isset($paramMMode['image_height']) && $paramMMode['image_height']!='' ) { echo $paramMMode['image_height']; } ?>" name="wp_maintenance_settings[image_height]" /><br />

                </div>
                <div style="float:left;width:30%;text-align:center;">
                    <?php if( isset($paramMMode['image']) && $paramMMode['image']!='' ) { ?>
                    <?php _e('You use this picture:', 'wp-maintenance'); ?><br /> <img src="<?php echo $paramMMode['image']; ?>" width="250" id="image_visuel" style="padding:3px;" />
                    <?php } ?>
                </div>
                <div class="clear"></div>
            </div>
            <div style="margin-top:15px;margin-bottom:15px;"><hr /></div>
            <div id="encart-option-background">
                <div style="float:left; width:70%;"><h3><?php _e('Background picture or pattern', 'wp-maintenance'); ?></h3></div>
                <div style="float:left; width:30%;text-align:right;">
                    <div class="switch-field">
                        <input class="switch_left" onclick="AfficherTexte('option-background');" type="radio" id="switch_background" name="wp_maintenance_settings[b_enable_image]" value="1" <?php if( isset($paramMMode['b_enable_image']) && $paramMMode['b_enable_image']==1) { echo ' checked'; } ?>/>
                        <label for="switch_background" ><?php _e('Yes', 'wp-maintenance'); ?></label>
                        <input class="switch_right" onclick="CacherTexte('option-background');" type="radio" id="switch_background_no" name="wp_maintenance_settings[b_enable_image]" value="0" <?php if( empty($paramMMode['b_enable_image']) || (isset($paramMMode['b_enable_image']) && $paramMMode['b_enable_image']==0) ) { echo ' checked'; } ?> />
                        <label for="switch_background_no"><?php _e('No', 'wp-maintenance'); ?></label>
                    </div>
                </div>
                <div class="clear"></div>
            </div>
                        
            <div id="option-background" style="<?php if( empty($paramMMode['b_enable_image']) || (isset($paramMMode['b_enable_image']) && $paramMMode['b_enable_image']==0) ) { echo ' display:none;'; } else { echo 'display:block'; } ?>">
                
                <!-- UPLOADER UNE IMAGE DE FOND -->
                <div style="float:left;width:68%;margin-right:10px;">
                    <small><?php _e('Enter a URL or upload an image.', 'wp-maintenance'); ?></small><br />
                    <input id="upload_b_image" class="wpm-form-field" size="80%" name="wp_maintenance_settings[b_image]" value="<?php if( isset($paramMMode['b_image']) && $paramMMode['b_image']!='' ) { echo $paramMMode['b_image']; } ?>" type="text" /><br /><a href="#" id="upload_b_image_button" class="button" OnClick="this.blur();"><span> <?php _e('Select or Upload your picture', 'wp-maintenance'); ?> </span></a>
                    <h4><?php _e('Background picture options', 'wp-maintenance'); ?></h4>
                    <select name="wp_maintenance_settings[b_repeat_image]" class="wpm-form-field" >
                        <option value="repeat"<?php if( (isset($paramMMode['b_repeat_image']) && $paramMMode['b_repeat_image']=='repeat') or empty($paramMMode['b_repeat_image']) ) { echo ' selected'; } ?>>repeat</option>
                        <option value="no-repeat"<?php if( isset($paramMMode['b_repeat_image']) && $paramMMode['b_repeat_image']=='no-repeat') { echo ' selected'; } ?>>no-repeat</option>
                        <option value="repeat-x"<?php if( isset($paramMMode['b_repeat_image']) && $paramMMode['b_repeat_image']=='repeat-x') { echo ' selected'; } ?>>repeat-x</option>
                        <option value="repeat-y"<?php if( isset($paramMMode['b_repeat_image']) && $paramMMode['b_repeat_image']=='repeat-y') { echo ' selected'; } ?>>repeat-y</option>
                    </select> <input type= "checkbox" name="wp_maintenance_settings[b_fixed_image]" value="1" <?php if( isset($paramMMode['b_fixed_image']) && $paramMMode['b_fixed_image']==1) { echo ' checked'; } ?>>&nbsp;<?php _e('Fixed', 'wp-maintenance'); ?>
                </div>
                <div style="float:left;width:30%;text-align:center;">
                    <?php if( isset($paramMMode['b_image']) && $paramMMode['b_image']!='' && (!$paramMMode['b_pattern'] or $paramMMode['b_pattern']==0) ) { ?>
                        <?php _e('You use this background picture:', 'wp-maintenance'); ?><br />
                        <img src="<?php echo $paramMMode['b_image']; ?>" width="200" /><br />
                    <?php } ?>
                </div>
                <div class="clear"></div>

                <!-- CHOIX PATTERN -->  
                <div style="float:left;width:68%;margin-right:10px;">
                    <h4><?php _e('Or choose a pattern:', 'wp-maintenance'); ?></h4>
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
                </div>
                <div style="float:left;width:30%;text-align:center;">
                    <?php if( isset($paramMMode['b_pattern']) && $paramMMode['b_pattern']>0) { ?>
                    <?php _e('You use this pattern:', 'wp-maintenance'); ?><br />
                    <div style="background: url('<?php echo WP_PLUGIN_URL ?>/wp-maintenance/images/pattern<?php echo $paramMMode['b_pattern']; ?>.png');width:200px;height:200px;border:1px solid #ddd;margin-left:auto;margin-right:auto;"></div>
                    <?php } ?>
                </div>
                <div class="clear"></div>
            </div>
            <div style="margin-top:15px;margin-bottom:15px;"><hr></div>
        
            <div>
                <div style="float:left; width:70%;"><h3><?php _e('Enable Slider', 'wp-maintenance'); ?></h3></div>
                <div style="float:left; width:30%;text-align:right;">
                    <div class="switch-field">
                        <input type="radio" onclick="AfficherTexte('option-diaporama');" id="switch_diaporama" name="wp_maintenance_settings[enable_slider]" value="1" <?php if( isset($paramMMode['enable_slider']) && $paramMMode['enable_slider']==1 ) { echo ' checked'; } ?>/>
                        <label for="switch_diaporama"><?php _e('Yes', 'wp-maintenance'); ?></label>
                        <input type="radio" onclick="CacherTexte('option-diaporama');" id="switch_diaporama_no" name="wp_maintenance_settings[enable_slider]" value="0" <?php if( empty($paramMMode['enable_slider']) || (isset($paramMMode['enable_slider']) && $paramMMode['enable_slider']==0) ) { echo ' checked'; } ?> />
                        <label for="switch_diaporama_no"><?php _e('No', 'wp-maintenance'); ?></label>
                    </div>
                </div>
                <div class="clear"></div>
            </div>
                        
            <div id="option-diaporama" style="<?php if( empty($paramMMode['enable_slider']) || (isset($paramMMode['enable_slider']) && $paramMMode['enable_slider']==0) ) { echo ' display:none;'; } else { echo 'display:block'; } ?>">
                <h4><?php _e('Slider image options', 'wp-maintenance'); ?></h4>
                <?php

                    if( $paramSlider!==null ) {

                        if( $paramSlider['slider_image'] ) {
                            $lastKeySlide = key($paramSlider['slider_image']);
                            $countSlide = ( $lastKeySlide + 1 );
                        } else {
                            $countSlide = 1;
                        }
                ?>
                <div style="margin-bottom:15px;width:100%;">
                    <div style="width:30%;float:left;">
                        <?php _e('Speed:', 'wp-maintenance'); ?> <input type="text" name="wp_maintenance_slider_options[slider_speed]" class="wpm-form-field" size="4" value="<?php if( isset($paramSliderOptions['slider_speed']) && $paramSliderOptions['slider_speed'] !='') { echo $paramSliderOptions['slider_speed']; } else { echo 500; } ?>" />ms<br />
                        <?php _e('Width:', 'wp-maintenance'); ?> <input type="text" name="wp_maintenance_slider_options[slider_width]" class="wpm-form-field" size="3" value="<?php if( isset($paramSliderOptions['slider_width']) && $paramSliderOptions['slider_width'] !='') { echo $paramSliderOptions['slider_width']; } else { echo 50; } ?>" />%
                    </div>
                    <div style="width:30%;float:left;padding-left:5px;">
                        
                        <div id="encart-option-sliderauto">
                            <?php _e('Display Auto Slider:', 'wp-maintenance'); ?><br />
                            <div class="switch-field" style="margin-left: 0px;">
                                <input class="switch_left" type="radio" id="switch_sliderauto" name="wp_maintenance_slider_options[slider_auto]" value="1" <?php if( isset($paramSliderOptions['slider_auto']) && $paramSliderOptions['slider_auto']=='true') { echo ' checked'; } ?>/>
                                <label for="switch_sliderauto" ><?php _e('Yes', 'wp-maintenance'); ?></label>
                                <input class="switch_right" type="radio" id="switch_sliderauto_no" name="wp_maintenance_slider_options[slider_auto]" value="0" <?php if( empty($paramSliderOptions['slider_auto']) || (isset($paramSliderOptions['slider_auto']) && $paramSliderOptions['slider_auto']=='false') ) { echo ' checked'; } ?> />
                                <label for="switch_sliderauto_no"><?php _e('No', 'wp-maintenance'); ?></label>
                            </div>
                            <?php _e('Display button navigation:', 'wp-maintenance'); ?><br />
                            <div class="switch-field" style="margin-left: 0px;">
                                <input class="switch_left" type="radio" id="switch_slidernav" name="wp_maintenance_slider_options[slider_nav]" value="1" <?php if( isset($paramSliderOptions['slider_nav']) && $paramSliderOptions['slider_nav']=='true') { echo ' checked'; } ?>/>
                                <label for="switch_slidernav" ><?php _e('Yes', 'wp-maintenance'); ?></label>
                                <input class="switch_right" type="radio" id="switch_slidernav_no" name="wp_maintenance_slider_options[slider_nav]" value="0" <?php if( empty($paramSliderOptions['slider_nav']) || (isset($paramSliderOptions['slider_nav']) && $paramSliderOptions['slider_nav']=='false') ) { echo ' checked'; } ?> />
                                <label for="switch_slidernav_no"><?php _e('No', 'wp-maintenance'); ?></label>
                            </div>
                        </div>

                    </div>
                    <div style="width:30%;float:left;padding-left:5px;">
                        <?php _e('Position:', 'wp-maintenance'); ?>
                        <select name="wp_maintenance_slider_options[slider_position]" class="wpm-form-field" >
                            <option value="abovelogo" <?php if( isset($paramSliderOptions['slider_position']) && $paramSliderOptions['slider_position']=='abovelogo' ) { echo 'selected'; } ?>><?php _e('Above logo', 'wp-maintenance'); ?></option>
                            <option value="belowlogo" <?php if( isset($paramSliderOptions['slider_position']) && $paramSliderOptions['slider_position']=='belowlogo' ) { echo 'selected'; } ?>><?php _e('Below logo', 'wp-maintenance'); ?></option>
                            <option value="belowtext" <?php if( ( isset($paramSliderOptions['slider_position']) && $paramSliderOptions['slider_position']=='belowtext' ) || empty($paramSliderOptions['slider_position']) ) { echo 'selected'; } ?>><?php _e('Below title & text', 'wp-maintenance'); ?></option>
                        </select>
                    </div>
                    <div class="clear"></div>
                </div>

                <input id="upload_slider_image" size="80%" class="wpm-form-field" name="wp_maintenance_slider[slider_image][<?php echo $countSlide; ?>][image]" value="" type="text" /><br /><a href="#" id="upload_slider_image_button" class="button" OnClick="this.blur();"><span> <?php _e('Select or Upload your picture', 'wp-maintenance'); ?> </span></a><br /><br />

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
                                    echo '<img src="'.$slideImg.'" width="360" />';
                                    echo '</div>';

                                    echo '<div style="float:left;">';
                                    echo '<input class="wpm-form-field" type="hidden" name="wp_maintenance_slider[slider_image]['.$numSlide.'][image]" value="'.$slideImg.'" />';
                                    echo __('Text:', 'wp-maintenance').'<br /> <input type="text" name="wp_maintenance_slider[slider_image]['.$numSlide.'][text]" class="wpm-form-field" size="50%" value="'.$slideText.'" /><br />';
                                    echo __('Link:', 'wp-maintenance').'<br /> <input type="text" name="wp_maintenance_slider[slider_image]['.$numSlide.'][link]" class="wpm-form-field" size="50%" value="'.$slideLink.'" />';
                                    echo '</div>';
                                    echo '<div class="clear"></div>';
                                    echo '<div style="text-align:right;"><input type="checkbox" name="wpm_maintenance_detete['.$numSlide.']" value="true" /><small>'.__('Delete this slide', 'wp-maintenance').'</small></div>';
                                    echo '</div>';

                                }

                            }
                        }
                    }
                    ?>
                </div>
            </div>
            <div class="clear"></div>
            
            <?php submit_button(); ?>

    </div>
    
    <?php echo wpm_sidebar(); ?>
    </div>
    </form> 
    <?php echo wpm_footer(); ?>

</div>