<?php

defined( 'ABSPATH' )
	or die( 'No direct load ! ' );

function wpm_update_settings($tabSettings, $nameOption = '', $type = 1) {

    if(empty($nameOption) || $nameOption=='') { return false; }

    if(isset($tabSettings) && is_array($tabSettings)) {
        $newTabSettings = array();
        foreach($tabSettings as $nameSettings => $valueSettings) {
            if($type==3) {
                $newTabSettings[$nameSettings] = wp_strip_all_tags(stripslashes(esc_url_raw($valueSettings)));
            } 
            if($nameOption == 'wp_maintenance_ipaddresses') {
                if( rest_is_ip_address($valueSettings)!=false){
                    $newTabSettings[] = sanitize_text_field($valueSettings);
                }
            }
            if(filter_var($valueSettings, FILTER_VALIDATE_EMAIL)) {
                $newTabSettings[$nameSettings] = sanitize_email($valueSettings);            
            } elseif(filter_var($valueSettings, FILTER_VALIDATE_URL)) {
                $newTabSettings[$nameSettings] = sanitize_url($valueSettings);
            } elseif($nameSettings == 'headercode' || $nameSettings == 'text_bt_maintenance' || $nameSettings == 'text_maintenance') {
                $arr = wpm_autorizeHtml();
                $newTabSettings[$nameSettings] = wp_kses($valueSettings, $arr);
            } elseif($nameSettings == 'id_pages') {
                $idPages = explode(',', $valueSettings);
                $nb=count($idPages);
                $getValueIdPages = '';
                for($i=0;$i<$nb;$i++) {
                    $getPage = get_post($idPages[$i]);
                    if( isset($getPage->ID) ) {
                        $getValueIdPages .= $idPages[$i].',';
                    }
                }
                $newTabSettings[$nameSettings] = sanitize_text_field(substr($getValueIdPages, 0, -1));
            } else {
                $newTabSettings[$nameSettings] = sanitize_textarea_field($valueSettings);
            }
        }
        update_option($nameOption, $newTabSettings);

        return true;
    } else {
        return false;
    }
    
}

function wpm_get_filesystem() {

    static $filesystem;

    if ( $filesystem ) {
        return $filesystem;
    }

    require_once( ABSPATH . 'wp-admin/includes/class-wp-filesystem-base.php' );
    require_once( ABSPATH . 'wp-admin/includes/class-wp-filesystem-direct.php' );

    $filesystem = new WP_Filesystem_Direct( new StdClass() ); // WPCS: override ok.

    // Set the permission constants if not already set.
    if ( ! defined( 'FS_CHMOD_DIR' ) ) {
        define( 'FS_CHMOD_DIR', ( @fileperms( ABSPATH ) & 0777 | 0755 ) );
    }
    if ( ! defined( 'FS_CHMOD_FILE' ) ) {
        define( 'FS_CHMOD_FILE', ( @fileperms( ABSPATH . 'index.php' ) & 0777 | 0644 ) );
    }

    return $filesystem;
}

function wpm_autorizeHtml() {

    return array(
        'a' => array(
            'href' => array(),
            'title' => array(),
            'target' => array(),
            'alt' => array(),
            'onFocus' => array(),
            'style' => array(),
            'id'    => array(),
            'name' => array()
            ),
        'br' => array(),
        'p' => array(
            'style' => array(),
            'class' => array()
            ),
        'h1' => array(),
        'h2' => array(), 
        'h3' => array(), 
        'h4' => array(),
        'h5' => array(), 
        'h6' => array(),             
        'em' => array(),
        'i' => array(
            'style' => array(),
            'class' => array()
            ),
        'font-awesome-icon' => array(
            'icon' => array(),
            'class' => array()
            ),
        'strong' => array(),
        'img' => array(
            'src' => array(),
            'title' => array(),
            'width' => array(),
            'height' => array(),
            'style' => array(),
            'class' => array()
            ),
        'div' => array(
            'style' => array(),
            'class' => array()
            ),
        'span' => array(
            'style' => array(),
            'class' => array()
            ),
        'table' => array(
            'style' => array(),
            'class' => array()
            ),
        'td' => array(
            'style' => array(),
            'class' => array()
            ),
        'tr' => array(
            'style' => array(),
            'class' => array()
            ),
        'th' => array(
            'style' => array(),
            'class' => array()
            ),
        'tbody' => array(
            'style' => array(),
            'class' => array()
            ),
        'thead' => array(
            'style' => array(),
            'class' => array()
        ),
        'ul' => array(
            'style' => array(),
            'class' => array()
        ),
        'li' => array(
            'style' => array(),
            'class' => array()
        ),
        'input' => array(
            'type' => array(),
            'id' => array(),
            'role' => array()
        ),
        'nav' => array(),
        'label' => array(
            'for' => array()
        ),
        'select' => array(
            'name' => array()
        ),
        'option' => array(
            'value' => array(),
            'style' => array()
        ),
        'style' => array(),
        );

}

function wpm_get_nav2() {
    
    global $current_user;
    global $_wp_admin_css_colors;
    
    $admin_color = get_user_option( 'admin_color', get_current_user_id() );
    $colors      = $_wp_admin_css_colors[$admin_color]->colors;
    
    $tabOptions = array(
        
        'wp-maintenance' => array(
            'dashicons' => 'dashicons-admin-settings',
            'link' => 'wp-maintenance',
            'text' => esc_html__('Dashboard', 'wp-maintenance'),
            'desc' => esc_html__('Here, the beginning...', 'wp-maintenance')
            ),
        'wp-maintenance-colors' => array(
            'dashicons' => 'dashicons-art',
            'link' => 'wp-maintenance-colors',
            'text' => esc_html__('Colors and Fonts', 'wp-maintenance'),
            'desc' => esc_html__('Have a creative mind', 'wp-maintenance')
            ),
        'wp-maintenance-picture' => array(
            'dashicons' => 'dashicons-format-gallery',
            'link' => 'wp-maintenance-picture',
            'text' => esc_html__('Pictures', 'wp-maintenance'),
            'desc' => esc_html__('Are we playing with the images?', 'wp-maintenance')
            ),
        'wp-maintenance-countdown' => array(
            'dashicons' => 'dashicons-clock',
            'link' => 'wp-maintenance-countdown',
            'text' => esc_html__('Countdown', 'wp-maintenance'),
            'desc' => esc_html__('Stop the time... or not!', 'wp-maintenance')
            ),
        'wp-maintenance-css' => array(
            'dashicons' => 'dashicons-media-code',
            'link' => 'wp-maintenance-css',
            'text' => esc_html__('CSS', 'wp-maintenance'),
            'desc' => esc_html__('Customize the style sheet', 'wp-maintenance')
            ),
        'wp-maintenance-seo' => array(
            'dashicons' => 'dashicons-admin-site-alt',
            'link' => 'wp-maintenance-seo',
            'text' => esc_html__('SEO', 'wp-maintenance'),
            'desc' => esc_html__('Keep your site optimized', 'wp-maintenance')
            ),
        'wp-maintenance-socialnetworks' => array(
            'dashicons' => 'dashicons-format-status',
            'link' => 'wp-maintenance-socialnetworks',
            'text' => esc_html__('Social Networks', 'wp-maintenance'),
            'desc' => esc_html__('Adding social networks icons', 'wp-maintenance')
            ),
        'wp-maintenance-footer' => array(
            'dashicons' => 'dashicons-table-row-before',
            'link' => 'wp-maintenance-footer',
            'text' => esc_html__('Footer', 'wp-maintenance'),
            'desc' => esc_html__('Here, we are talking about the footer', 'wp-maintenance')
            ),
        'wp-maintenance-settings' => array(
            'dashicons' => 'dashicons-admin-generic',
            'link' => 'wp-maintenance-settings',
            'text' => esc_html__('Settings', 'wp-maintenance'),
            'desc' => esc_html__('A few additional options', 'wp-maintenance')
            )
    );
    $getDashicons = '<nav>
    <div class="conteneur-nav">
    <label for="mobile">Afficher / Cacher le menu</label>
    <input type="checkbox" id="mobile" role="button">
      <ul>';
    foreach( $tabOptions as $page=>$values) {
        
        if (isset($_GET['page']) && $_GET['page']!=$page ) { // phpcs:ignore WordPress.Security.NonceVerification.Recommended
            $active = ''; $classOnglet = 'width: 5%;'; 
        } else { 
            $active = 'color:#ffffff!important;'; $classOnglet = 'width: 5%;background-color: #848838!important;border: 1px solid #848838 !important;';
        }
        
        $getDashicons .= '<li style="'.$classOnglet.'"><a href="'.admin_url().'admin.php?page='.$page.'" alt="'.$tabOptions[$page]['text'].'" title="'.$tabOptions[$page]['text'].'" class="module-'.$page.'" onFocus="this.blur()" style="'.$active.'"><span class="dashicons '.$tabOptions[$page]['dashicons'].'"></span><br />'.$tabOptions[$page]['text'].'</a></li>';
        
    }
    $getDashicons .= '<a href="'.site_url().'/?wpmpreview=true" target="_blank" alt="'.esc_html__('Preview page', 'wp-maintenance').'" title="'.esc_html__('Preview page', 'wp-maintenance').'" class="wpmadashicons" onFocus="this.blur()" style="color:#23282d;text-decoration:none;"><div style="background:#D6D5AA;color:#23282d;padding:1em 1em;margin: 1em 1em;text-align:center;"><span class="dashicons dashicons-external" style="font-size:20px;" ></span> '.esc_html__('Preview page', 'wp-maintenance').'</div></a>';
    $getDashicons .= '</ul></div></nav>';
    
    return $getDashicons;
}

function wpm_footer() {
    
    $wpmFooter = '
    <div style="margin-top:40px;">
        <a href="https://madeby.restezconnectes.fr" target="_blank">'.esc_html__('WP Maintenance','wp-maintenance').' (v.'.WPM_VERSION.')</a> '.esc_html__('is brought to you by','wp-maintenance').' <a href="https://restezconnectes.fr/" target="_blank">Restez Connectés</a> - '.esc_html__('If you found this plugin useful','wp-maintenance').' <a href="https://wordpress.org/support/view/plugin-reviews/wp-maintenance" target="_blank">'.esc_html__('give it 5 &#9733; on WordPress.org','wp-maintenance').'</a>
    </div>
    ';
    
    return $wpmFooter;
    
}

/* Liste les différents Rôles */
function wpm_get_roles() {

    $wp_roles = new WP_Roles();
    $roles = $wp_roles->get_names();
    $roles = array_map( 'translate_user_role', $roles );

    return $roles;
}

/* Retourne la vraie adresse IP */
function wpm_get_ip() {

	return (isset($_SERVER['REMOTE_ADDR']) ? esc_html($_SERVER['REMOTE_ADDR']) : '');

}

function wpm_change_active($value = 0) {

    update_option('wp_maintenance_active', $value);
    $statusActive = get_option('wp_maintenance_active');
    if( isset($statusActive)  ) {
        return $statusActive;
    }
}

function wpm_array_value_count($array) {
    
    $count = 0;
    foreach ($array as $key => $value) {
            if( isset($value) && $value!='') { $count++; }
    }
    return $count;
} 

function wpm_getFontsTab($font = '') {

    $listGoogleFonts = array(
        "Aclonica" => "sans-serif", "Allan" => "serif", "Annie Use Your Telescope" => "cursive", "Anonymous Pro" => "monospace", "Allerta Stencil" => "sans-serif", "Allerta" => "sans-serif", "Amaranth" => "sans-serif", "Anton" => "sans-serif", "Architects Daughter" => "cursive", "Arimo" => "sans-serif", "Artifika" => "serif", "Arvo" => "serif", "Asset" => "serif", "Astloch" => "system-ui", "Bangers" => "Bangers", "Barrio" => "system-ui", "Bentham" => "serif", "Bevan" => "Bevan", "Bigshot One" => "serif", "Bowlby One" => "sans-serif", "Bowlby One SC" => "sans-serif", "Brawler" => "serif", "Buda" => "serif", "Cabin" => "sans-serif", "Calligraffitti" => "cursive", "Candal" => "sans-serif", "Cantarell" => "sans-serif", "Cardo" => "serif", "Carter One" => "system-ui", "Caudex" => "serif", "Cedarville Cursive" => "cursive", "Cherry Cream Soda" => "system-ui", "Chewy" => "system-ui", "Coda" => "system-ui", "Coming Soon" => "cursive", "Copse" => "serif", "Corben" => "serif", "Cousine" => "monospace", "Covered By Your Grace" => "cursive", "Crafty Girls" => "cursive", "Crimson Text" => "serif", "Crushed" => "sans-serif", "Cuprum" => "sans-serif", "Damion" => "cursive", "Dancing Script" => "cursive", "Dawning of a New Day" => "cursive", "Didact Gothic" => "sans-serif", "EB Garamond" => "serif", "Expletus Sans" => "sans-serif", "Fontdiner Swanky" => "serif", "Forum" => "serif", "Francois One" => "sans-serif", "Geo" => "sans-serif", "Give You Glory" => "cursive", "Goblin One" => "serif", "Goudy Bookletter 1911" => "serif", "Gravitas One" => "serif", "Gruppo" => "sans-serif", "Hammersmith One" => "sans-serif", "Holtwood One SC" => "serif", "Homemade Apple" => "cursive", "Inconsolata" => "monospace", "Indie Flower" => "cursive", "IM Fell DW Pica" => "serif", "IM Fell DW Pica SC" => "serif", "IM Fell Double Pica" => "serif", "IM Fell Double Pica SC" => "serif", "IM Fell English" => "serif", "IM Fell English SC" => "serif", "IM Fell French Canon" => "serif", "IM Fell French Canon SC" => "serif", "IM Fell Great Primer" => "serif", "IM Fell Great Primer SC" => "serif", "Irish Grover" => "system-ui", "Istok Web" => "sans-serif", "Josefin Sans" => "sans-serif", "Josefin Slab" => "serif", "Judson" => "serif", "Jura" => "sans-serif", "Just Another Hand" => "cursive", "Just Me Again Down Here" => "cursive", "Kameron" => "serif", "Kenia" => "sans-serif", "Kranky" => "serif", "Kreon" => "serif", "Kristi" => "cursive", "La Belle Aurore" => "cursive", "Lato" => "sans-serif", "League Script" => "cursive", "Lekton" => "sans-serif", "Life Savers" => "serif", "Limelight" => "sans-serif", "Lobster" => "sans-serif", "Lobster Two" => "sans-serif", "Lora" => "serif", "Love Ya Like A Sister" => "serif", "Loved by the King" => "cursive", "Luckiest Guy" => "cursive", "Maiden Orange" => "serif", "Mako" => "sans-serif", "Maven Pro" => "sans-serif", "Meddon" => "cursive", "MedievalSharp" => "cursive", "Megrim" => "system-ui", "Merriweather" => "serif", "Metrophobic" => "sans-serif", "Michroma" => "sans-serif", "Miltonian Tattoo" => "serif", "Miltonian" => "serif", "Modern Antiqua" => "serif", "Monofett" => "monospace", "Molengo" => "sans-serif", "Mountains of Christmas" => "serif", "Muli" => "sans-serif", "Neucha" => "cursive", "Neuton" => "serif", "News Cycle" => "sans-serif", "Nixie One" => "system-ui", "Nobile" => "sans-serif", "Nova Cut" => "system-ui", "Nova Flat" => "system-ui", "Nova Mono" => "monospace", "Nova Oval" => "system-ui", "Nova Round" => "system-ui", "Nova Script" => "system-ui", "Nova Slim" => "system-ui", "Nova Square" => "sans-serif", "Nunito" => "sans-serif", "Old Standard TT" => "serif", "Open Sans" => "sans-serif", "Orbitron" => "sans-serif", "Oswald" => "sans-serif", "Over the Rainbow" => "cursive", "Reenie Beanie" => "cursive", "Pacifico" => "cursive", "Patrick Hand" => "cursive", "Paytone One" => "sans-serif", "Permanent Marker" => "cursive", "Philosopher" => "sans-serif", "Play" => "sans-serif", "Playfair Display" => "serif", "Podkova" => "serif", "PT Sans" => "sans-serif", "PT Sans Narrow" => "sans-serif", "PT Sans Narrow:regular,bold" => "sans-serif", "PT Serif" => "serif", "PT Serif Caption" => "serif", "Puritan" => "sans-serif", "Quattrocento" => "serif", "Quattrocento Sans" => "sans-serif", "Radley" => "serif", "Raleway" => "sans-serif", "Ravi Prakash" => "sans-serif", "Redressed" => "cursive", "Roboto" => "sans-serif", "Roboto Condensed" => "sans-serif", "Roboto Mono" => "monospace", "Roboto Slab" => "serif", "Rock Salt" => "cursive", "Rokkitt" => "serif", "Ruslan Display" => "sans-serif", "Schoolbell" => "cursive", "Shadows Into Light" => "cursive", "Shanti" => "sans-serif", "Sigmar One" => "sans-serif", "Six Caps" => "sans-serif", "Slackey" => "sans-serif", "Smythe" => "system-ui", "Sniglet" => "system-ui", "Special Elite" => "system-ui", "Stardos Stencil" => "system-ui", "Sue Ellen Francisco" => "cursive", "Sunshiney" => "cursive", "Swanky and Moo Moo" => "cursive", "Syncopate" => "sans-serif", "Tangerine" => "cursive", "Tenor Sans" => "sans-serif", "The Girl Next Door" => "cursive", "Tinos" => "serif", "Ubuntu" => "sans-serif", "Ultra" => "serif", "Unkempt" => "cursive", "UnifrakturCook:bold" => "cursive", "UnifrakturMaguntia" => "cursive", "Varela" => "sans-serif", "Varela Round" => "sans-serif", "Vibur" => "cursive", "Vollkorn" => "serif", "VT323" => "monospace", "Waiting for the Sunrise" => "cursive", "Wallpoet" => "sans-serif", "Walter Turncoat" => "cursive", "Wire One" => "sans-serif", "Yanone Kaffeesatz" => "sans-serif", "Yeseva One" => "serif", "Zeyada" => "cursive"
    );

    if( isset($font) && $font !='') {
        return $listGoogleFonts[$font];
    } else {
        return $listGoogleFonts;
    }
}

/**
 * Returns a select list of Google fonts
 * Feel free to edit this, update the fallbacks, etc.
 */
function wpm_options_typography_get_google_fonts() {
	// Google Font Defaults
	$google_faces = array(
		'Arvo, serif' => 'Arvo',
		'Copse, sans-serif' => 'Copse',
		'Droid Sans, sans-serif' => 'Droid Sans',
		'Droid Serif, serif' => 'Droid Serif',
		'Lobster, cursive' => 'Lobster',
		'Nobile, sans-serif' => 'Nobile',
		'Open Sans, sans-serif' => 'Open Sans',
		'Oswald, sans-serif' => 'Oswald',
		'Pacifico, cursive' => 'Pacifico',
		'Rokkitt, serif' => 'Rokkit',
		'PT Sans, sans-serif' => 'PT Sans',
		'Quattrocento, serif' => 'Quattrocento',
		'Raleway, cursive' => 'Raleway',
		'Ubuntu, sans-serif' => 'Ubuntu',
		'Yanone Kaffeesatz, sans-serif' => 'Yanone Kaffeesatz'
	);
	return $google_faces;
}


function wpm_getFontsList($name = "", $value = "") {
        
    $fonts = wpm_getFontsTab();
    if($value == '') { $value = 'Acme'; }
    $tab_fonts = "<select name='".$name."'>";
    foreach($fonts as $namefont => $type) {
        $addOption = '';
        if($value == $namefont) { $addOption = 'selected'; }
        $tab_fonts .= '<option value="'.$namefont.'" '.$addOption.' style="font-family: "'.$namefont.'", '.$type.';">'.$namefont.'</option>';
    }
    $tab_fonts .= "</select>";

    return $tab_fonts;
}
/* Formatte la police sélectionnée */
function wpm_format_font($font, $displayStyle = 1) {
    $listFonts = wpm_getFontsTab();
    $font = str_replace('+', ' ', $font);
    $styleFont = '';
    if( isset($listFonts[$font]) && $listFonts[$font]!='' ) {  
        $styleFont = $listFonts[$font];
        if( isset($displayStyle) && $displayStyle == 1 ) {
            $font = '"'.$font.'", '.$styleFont;
        } else {
            $font = $font.', '.$styleFont;
        }
    }

    return $font;
}

function wpm_compress($buffer) {

    // Récupère les paramètres sauvegardés
	if(get_option('wp_maintenance_settings_colors')) { extract(get_option('wp_maintenance_settings_colors')); }
    $colors = get_option('wp_maintenance_settings_colors');
    
    $variables_css = array (
        "#_COLORTXT" => $colors['color_txt'],
        "#_COLORBG" => $colors['color_bg'],
        "#_COLORCPTBG" => $colors['color_cpt_bg'],
        "#_DATESIZE" => $colors['date_cpt_size'],
        "#_COLORCPT" => $colors['color_cpt'],
        "#_COLOR_BG_BT" => $colors['color_bg_bottom'],
        "#_COLOR_TXT_BT" => $colors['color_text_bottom'],
        "#_COLORHEAD" => $colors['color_bg_header'],
    );
    // On remplace les variables par leur valeur
    foreach($variables_css as $code_variable => $valeur) {
        $buffer = str_replace('{'.$code_variable.'}', $valeur, $buffer);
    
        // Suppression des commentaires
        $buffer = preg_replace('!/\*[^*]*\*+([^/][^*]*\*+)*/!', '', $buffer);
    
        // Suppression des tabulations, espaces multiples, retours à la ligne, etc.
        $buffer = str_replace(array("\r\n", "\r", "\n", "\t", '  ', '	 ', '	 '), '', $buffer);
    }
    // Suppression des derniers espaces inutiles
    $buffer = str_replace(array(' { ',' {','{ '), '{', $buffer);
    $buffer = str_replace(array(' } ',' }','} '), '}', $buffer);
    $buffer = str_replace(array(' : ',' :',': '), ':', $buffer);

    return $buffer;
}

function wpm_fonts($font, $name='') {

    // Récupère les paramètres sauvegardés
    if(get_option('wp_maintenance_settings_options')) { extract(get_option('wp_maintenance_settings_options')); }
    $wpoptions = get_option('wp_maintenance_settings_options');

    if( isset($wpoptions['remove_googlefonts']) && $wpoptions['remove_googlefonts']==1 ) {

        if( empty($font) || $font == '' ) { $font = 'verdana'; }

        // Liste des fonts par default
        $arrayFont = array(
            'arial' => array('Arial (sans-serif)' => 'Arial, sans-serif'),
            'arial-black' => array('Arial Black (sans-serif)' => 'Arial Black'),
            'arial-narrow' => array('Arial narrow (sans-serif)' => 'Arial narrow'),
            'arial-rounded' => array('Arial Rounded MT Bold (sans-serif)' => 'Arial Rounded MT Bold'),
            'helvetica' => array('Helvetica (sans-serif)' => 'Helvetica'),
            'verdana' => array('Verdana (sans-serif)' => 'Verdana, sans-serif'),
            'Calibri' => array('Verdana (sans-serif)' => 'Verdana, sans-serif'),
            'noto' => array('Noto (sans-serif)' => 'Noto'),
            'lucida-sans' => array('Lucida Sans (sans-serif)' => 'Lucida Sans'),
            'gill-sans' => array('Gill Sans (sans-serif)' => 'Gill Sans'),
            'century-gothic' => array('Century Gothic (sans-serif)' => 'Century Gothic'),
            'Candara' => array('Candara (sans-serif)' => 'Candara'),
            'futara' => array('Futara (sans-serif)' => 'Futara'),
            'franklin-gothic-medium' => array('Franklin Gothic Medium (sans-serif)' => 'Franklin Gothic Medium'),
            'tahoma' => array('Tahoma (sans-serif)' => 'Tahoma, sans-serif'),
            'trebuchet' => array('Trebuchet MS (sans-serif)' => 'Trebuchet MS, sans-serif'),
            'geneva' => array('Geneva (sans-serif)' => 'Geneva, sans-serif'),
            'times' => array('Times New Roman (serif)' => 'Times New Roman, Times, serif'),
            'georgia' => array('Georgia (serif)' => 'Georgia, serif'),
            'garamond' => array('Garamond (serif)' => 'Garamond, serif'),
            'courrier' => array('Courier New (monospace)' => 'Courier New, Courier, monospace'),
            'brush' => array('Brush Script MT (cursive)' => 'Brush Script MT, cursive'),
            'copperplate' => array('Copperplate, Papyrus, fantasy' => 'Copperplate, Papyrus, fantasy'),
            'segoe-ui' => array('Segoe UI (sans-serif)' => 'Segoe UI'),
            'optima' => array('Optima (sans-serif)' => 'Optima'),
            'avanta-garde' => array('Avanta Garde (sans-serif)' => 'Avanta Garde'),
            'bug-caslon' => array('Big Caslon (serif)' => 'Big Caslon'),
            'bodoni-mt' => array('Bodoni MT (serif)' => 'Bodoni MT'),
            'book-antiqua' => array('Book Antiqua (serif)' => 'Book Antiqua'),
            'bookman' => array('Bookman (serif)' => 'Bookman'),
            'new-century-schoolbook' => array('New Century Schoolbook (serif)' => 'New Century Schoolbook'),
            'calisto-mt' => array('Calisto MT (serif)' => 'Calisto MT'),
            'cambria' => array('Cambria (serif)' => 'Cambria'),
            'didot' => array('Didot (serif)' => 'Didot'),
            'garamond' => array('Garamond (serif)' => 'Garamond'),
            'goudy-old-style' => array('Goudy Old Style (serif)' => 'Goudy Old Style'),
            'hoefler-text' => array('Hoefler Text (serif)' => 'Hoefler Text'),
            'lucida-bright' => array('Lucida Bright (serif)' => 'Lucida Bright'),
            'palatino' => array('Palatino (serif)' => 'Palatino'),
            'perpetua' => array('Perpetua (serif)' => 'Perpetua'),
            'rockwell' => array('Rockwell (serif)' => 'Rockwell'),
            'rockwell-extra-bold' => array('Rockwell Extra Bold (serif)' => 'Rockwell Extra Bold'),
            'baskerville' => array('Baskerville (serif)' => 'Baskerville'),
            'consolas' => array('Consolas (monospace)' => 'Consolas'),
            'courier-new' => array('Courier New (monospace)' => 'Courier New'),
            'lucida-console' => array('Lucida Console (monospace)' => 'Lucida Console'),
            'lucidatypewriter' => array('Lucidatypewriter (monospace)' => 'Lucidatypewriter'),
            'lucida-sans-typewriter' => array('Lucida Sans Typewriter (monospace)' => 'Lucida Sans Typewriter'),
            'monaco' => array('Monaco (monospace)' => 'Monaco'),
            'andale-mono' => array('Andale Mono (monospace)' => 'Andale Mono'),
            'comic-sans' => array('Comic Sans (cursive)' => 'Comic Sans'),
            'comic-sans-ms' => array('Comic Sans MS (cursive)' => 'Comic Sans MS'),
            'apple-chancery' => array('Apple Chancery (cursive)' => 'Apple Chancery'),
            'zapf-chancery' => array('Zapf Chancery (cursive)' => 'Zapf Chancery'),
            'bradley-hand' => array('Bradley Hand (cursive)' => 'Bradley Hand'),
            'brush-script-std' => array('Brush Script Std (cursive)' => 'Brush Script Std'),
            'snell-roundhan' => array('Snell Roundhan (cursive)' => 'Snell Roundhan'),
            'urw-chancery' => array('URW Chancery (cursive)' => 'URW Chancery'),
            'coronet-script' => array('Coronet script (cursive)' => 'Coronet script'),
            'florence' => array('Florence (cursive)' => 'Florence'),
            'parkavenue' => array('Parkavenue (cursive)' => 'Parkavenue'),
            'impact' => array('Impact (fantasy)' => 'Impact'),
            'brushstroke' => array('Brushstroke (fantasy)' => 'Brushstroke'),
            'luminari' => array('Luminari (fantasy)' => 'Luminari'),
            'chalkduster' => array('Chalkduster (fantasy)' => 'Chalkduster'),
            'jazz-let' => array('Jazz LET (fantasy)' => 'Jazz LET'),
            'blippo' => array('Blippo (fantasy)' => 'Blippo'),
            'stencil-std' => array('Stencil Std (fantasy)' => 'Stencil Std'),
            'marker-felt' => array('Marker Felt (fantasy)' => 'Marker Felt'),
            'trattatello' => array('Trattatello (fantasy)' => 'Trattatello'),
            'arnoldboecklin' => array('Arnoldboecklin (fantasy)' => 'Arnoldboecklin'),
            'oldtown' => array('Oldtown (fantasy)' => 'Oldtown'),
            'papyrus' => array('Papyrus (fantasy)' => 'papyrus'),
            'ink-free' => array('Ink Free (fantasy)' => 'Ink Free'),
            'lucida-handwriting' => array('Lucida Handwriting (fantasy)' => 'Lucida Handwriting'),
            'segoe-print' => array('Segoe Print (fantasy)' => 'Segoe Print'),
            'segoe-script' => array('Segoe Script (fantasy)' => 'Segoe Script'),
            'webdings' => array('Webdings (fantasy)' => 'Webdings'),
            'wingdings' => array('Wingdings (fantasy)' => 'Wingdings')
        );
        ksort($arrayFont);
        // Si il y a un nom pour le select
        if( isset($name) && $name!='') {

            $selectFont = '<select class="wp-maintenance-select" name="wpmcolors['.$name.']">';
            foreach($arrayFont as $nameFont => $valueFont) {
                $select = '';
                foreach($valueFont as $printFont => $printValueFont) {
                    if( $nameFont ==  $font) { $select = 'selected'; }
                    $selectFont .= '<option value="'.$nameFont.'" '.$select.' style="font-family:'.$nameFont.'!important;color:#333333!important;">'.$printFont.'</option>';
                }
            }
            $selectFont .= '</select>';

        } else {

            if( empty($font) || $font == '' ) { $font = 'verdana'; }
            // Si pas de nom on retourne la value de la font
            foreach($arrayFont[$font] as $printFont => $printValueFont) {
                $selectFont = $printValueFont;
            }

        }

        return $selectFont; 
        
    } else {

        return $font;

    }

}

/* Feuille de style par défault */
function wpm_print_style() {
    
    return '#logo {text-align: center; max-width: 100%; height: auto;}

a:link {color: #_COLORTXT;text-decoration: none;}
a:visited {color: #_COLORTXT;text-decoration: none;}
a:hover, a:focus, a:active {color: #_COLORTXT;text-decoration: none;}

.cptR-rec_countdown {
    position: relative;
    background: #_COLORCPTBG;
    display: inline-block;
    line-height: 100%;
    min-height: 60px;
    text-transform: uppercase;
    text-align:center;
    margin: 0.5em auto;
}
.wpm_ctp_sep {margin-top:1vw;}

header {background: #_COLORHEAD;}

#wpm-cpt-day, #wpm-cpt-hours, #wpm-cpt-minutes, #wpm-cpt-seconds {
    color: #_COLORCPT;
    display: block;
    font-size: #_DATESIZE;
    height: 40px;
    line-height: 18px;
    text-align: center;
    float:left;
    margin:0.3em;
    padding:0px;
}
#wpm-cpt-days-span, #wpm-cpt-hours-span, #wpm-cpt-minutes-span, #wpm-cpt-seconds-span {
    color: #_COLORCPT;
    font-size: 10px;
    padding: 25px 5px 0 2px;
}
.wpm_social ul, li { background:none!important; }
.wpm_horizontal li {display: inline-block;list-style: none;margin:5px;opacity:1;}
.wpm_horizontal li:hover {opacity:0.5;}

.wpm_social {padding: 0 45px;text-align: center;}
.wpm_newletter {text-align:center;}
#countdown {clear:both;margin-left:auto;margin-right:auto;text-align: center;}

.footer-basic p a {
color:#_COLOR_TXT_BT;
text-decoration:none;
}
.footer-basic .copyright {
margin-top:15px;
text-align:center;
font-size:13px;
color:#aaa;
margin-bottom:0;
}
    ';   
}

function wpm_hex2rgb($color) {
    
    
	if(strlen($color) > 1)
		if($color[0] == '#')
			$color = substr($color, 1);
 
	if(strlen($color) == 6)
		list($r, $g, $b) = array(
					$color[0].$color[1],
					$color[2].$color[3],
					$color[4].$color[5]
					);
	elseif(strlen($color) == 3)
		list($r, $g, $b) = array(
					$color[0].$color[0],
					$color[1].$color[1],
					$color[2].$color[2]
					);
	else
		return false;
 
	return array(
		'rouge' => hexdec($r),
		'vert' => hexdec($g),
		'bleu' => hexdec($b)
		);
    
}

