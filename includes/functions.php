<?php

defined( 'ABSPATH' )
	or die( 'No direct load ! ' );

function wpm_get_header( $text = 'Settings', $dashicon = 'dashicons-admin-settings', $update = 0 ) {
    
    global $current_user;
    global $_wp_admin_css_colors;
    
    $getHeader = '
     <div id="wpmcontainer" role="navigation" class="stickywrapper">
        <div id="divwpmcontainer">
            <div style="float:left;width:67%;margin-left: 20px;">
                <div style="float:left;width:35%;">
                    <h2 style="font-size: 23px;font-weight: 400;line-height: 29px;margin-bottom:15px;">
                        <span class="dashicons '.$dashicon.'" style="font-size:35px;margin-right:10px;"></span> '.$text.'
                    </h2>
                </div>';
    $getHeader .= '<div style="float:left;width: 20%;padding-top: 8px;">
        <div style="text-align:center;border-bottom: 1px solid #ddd;margin-bottom: 5px;">MENU</div><div style="text-align:center;">'.wpm_list_dashicons().'</div>
    </div>';
    $getHeader .= '<div style="float:left;width: 45%;text-align: center;padding-top: 10px;">';
    if( $update == 1 ) {
    $getHeader .= '<div id="wpmNotifications"><strong>'.__('Options saved.', 'wp-maintenance').'</strong></div>';
    $getHeader .= '<script>
        jQuery("#wpmNotifications").delay(4000).fadeOut(2000, function(){ 
            jQuery(this).remove();
        });
        
        </script>';
    }
    $getHeader .= '</div>';
    $getHeader .= '</div>
            <div style="float:left;width:18%;text-align: right;margin-top: 25px;">
                '.get_submit_button( '', 'primary', 'valide_settings', false ).'
            </div><div class="clear"></div>
        </div>
    </div><div class="clear"></div>';
    
    return $getHeader;
}

function wpm_list_dashicons() {
    
    global $current_user;
    global $_wp_admin_css_colors;
    
    $admin_color = get_user_option( 'admin_color', get_current_user_id() );
    $colors      = $_wp_admin_css_colors[$admin_color]->colors;
    
    $tabOptions = array(
        
        'wp-maintenance' => array(
            'dashicons' => 'dashicons-admin-settings',
            'link' => 'wp-maintenance',
            'text' => __('Go to Generals options', 'wp-maintenance')
            ),
        'wp-maintenance-colors' => array(
            'dashicons' => 'dashicons-art',
            'link' => 'wp-maintenance-colors',
            'text' => __('Go to Colors and Fonts options', 'wp-maintenance')
            ),
        'wp-maintenance-picture' => array(
            'dashicons' => 'dashicons-format-gallery',
            'link' => 'wp-maintenance-picture',
            'text' => __('Go to Pictures options', 'wp-maintenance')
            ),
        'wp-maintenance-countdown' => array(
            'dashicons' => 'dashicons-clock',
            'link' => 'wp-maintenance-countdown',
            'text' => __('Go to Countdown options', 'wp-maintenance')
            ),
        'wp-maintenance-css' => array(
            'dashicons' => 'dashicons-media-code',
            'link' => 'wp-maintenance-css',
            'text' => __('Go to CSS options', 'wp-maintenance')
            ),
        'wp-maintenance-settings' => array(
            'dashicons' => 'dashicons-admin-generic',
            'link' => 'wp-maintenance-settings',
            'text' => __('Go to Settings options', 'wp-maintenance')
            )
    );
    
    $getDashicons = '';
        
    foreach( $tabOptions as $page=>$values) {
        
        if (isset($_GET['page']) && $_GET['page']!=$page ) {
        
            $getDashicons .= '<a href="'.admin_url().'admin.php?page='.$page.'" alt="'.$tabOptions[$page]['text'].'" title="'.$tabOptions[$page]['text'].'" class="wpmadashicons" onFocus="this.blur()"><span class="dashicons '.$tabOptions[$page]['dashicons'].'" class="wpmdashicons" style="font-size:25px;margin-right:15px;"></span></a>';

        } 
    }
    
    return $getDashicons;
}

function wpm_update_settings($tabPost) {
    
    // Récupère les paramètres sauvegardés Temporairement
    if(get_option('wp_maintenance_settings')) { extract(get_option('wp_maintenance_settings')); }
    $paramTemp = get_option('wp_maintenance_settings');
    
    // Je recupère le tableau temporaire des données
    if( isset($paramTemp) && !empty($paramTemp) ) {
        foreach($paramTemp as $variable=>$value) {
            // pour chaque clé du tableau de regarde si elle existe déjà
            if ( array_key_exists($variable, $paramTemp) ) {
                // Si la clé est la même que venant du post je la change
                if( isset($tabPost[$variable]) ) { 
                    array_fill_keys($paramTemp, $tabPost[$variable]);
                }
            }
        }
    }
   
    if( isset($paramTemp) && is_array($paramTemp) ) {
         // Si le tableau temporaire existe je le fusionne avec les données $_POST["wp_maintenance_settings"]
        $paramData = array_merge($paramTemp, $tabPost);
    } else {
        // Sinon je garde le $_POST["wp_maintenance_settings"] en cours
        $paramData = $tabPost;
    }
    if(update_option('wp_maintenance_settings', $paramData)) {
        return true;
    }
}

function wpm_sidebar() {
    
    $wpmSidebar = '<div style="float:left;width:24%;">';
    
    /* ABOUT */
    $wpmSidebar .= '<div style="border: 1px solid #ddd;background-color:#fff;padding:10px;text-align:center;margin-bottom:5px;">'.__('ABOUT', 'wp-maintenance').'</div>';
    
     $wpmSidebar .= '<div style="text-align:justify;background-color:#fff;padding:10px;margin-bottom: 10px;">'.__('This plugin has been developed for you for free by <a href="https://restezconnectes.fr" target="_blank">Florent Maillefaud</a>. It is royalty free, you can take it, modify it, distribute it as you see fit.', 'wp-maintenance').'<br /><br />';
    $wpmSidebar .= ''.__('Visit', 'wp-maintenance').' <a href="https://wpmaintenance.info" target="_blank">WP Maintenance</a>, '.__('try the demo of the plugin, talk about this plugin to your surroundings!', 'wp-maintenance').'<br /><br />';
    /* FAIRE UN DON SUR PAYPAL */
    $wpmSidebar .= ''.__('Support this extension and my other developments (French Paypal):', 'wp-maintenance').'<br /><br />
        <div style="width:220px;margin-left:auto;margin-right:auto;padding:5px;">
            <a href="https://paypal.me/RestezConnectes/10" target="_blank" class="wpmclassname">
                <img src="'.WPM_PLUGIN_URL.'images/donate.png" valign="bottom" width="64" /> Donate now!
            </a>
        </div>
    </div>';
    /* FIN FAIRE UN DON */
    
    /* END ABOUT */
    
    /* DISCOVER*/
    $wpmSidebar .= '<div style="border: 1px solid #ddd;background-color:#fff;padding:10px;text-align:center;margin-bottom:5px;">'.__('DISCOVER', 'wp-maintenance').'</div>';
    
    $imagePub = array(
        'extension-send-pdf-for-cf7.png' => 'https://wordpress.org/plugins/send-pdf-for-contact-form-7/'
    );
    
    foreach( $imagePub as $img=>$link ) {
        $wpmSidebar .= '<a href="'.$link.'" target="_blank"><img src="'.WPM_PLUGIN_URL.'images/'.$img.'" /></a>';
    }
    /* END DISCOVER */
    
    $wpmSidebar .= '</div>'; // Div general
    $wpmSidebar .= '<div class="clear"></div>';
    
    return $wpmSidebar;
}

function wpm_footer() {
    
    $wpmFooter = '
    <div style="margin-top:40px;">
        <a href="https://wpmaintenance.info/" target="_blank">'.__('WP Maintenance','wp-maintenance').'</a> '.__('is brought to you by','wp-maintenance').' <a href="https://restezconnectes.fr/" target="_blank">Restez Connectés</a> - '.__('If you found this plugin useful','wp-maintenance').' <a href="https://wordpress.org/support/view/plugin-reviews/wp-maintenance" target="_blank">'.__('give it 5 &#9733; on WordPress.org','wp-maintenance').'</a>
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
    return (isset($_SERVER['HTTP_X_FORWARDED_FOR'])) ? $_SERVER['HTTP_X_FORWARDED_FOR'] : $_SERVER['REMOTE_ADDR'];
}

function wpm_change_active($value = 0) {

    update_option('wp_maintenance_active', $value);
    $statusActive = get_option('wp_maintenance_active');
    if( isset($statusActive)  ) {
        return $statusActive;
    }
}

function wpm_array_value_count ($array) {
    $count = 0;
   
    foreach ($array as $key => $value)
    {
            if($value) { $count++; }
    }
   
    return $count;
} 

function wpm_get_template() {
    
    return '
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta name="viewport" content="width=device-width, user-scalable=yes" />
	<title>%TITLE%</title>
    
	<style type=\'text/css\'>
        /* VERSION %VERSION% */
        %ADDFONTS%
        html,
        body {
            margin:0;
            padding:0;
            height:100%;
            font-size:100%;
        }
        #wrapper {
            min-height:100%;
            position:relative;
        }
        #header {
            padding:10px;
        }
        #content {
            padding-bottom:100px; /* Height of the footer element */
        }
        #footer {
            width:100%;
            height:60px;
            line-height:60px;
            position:absolute;
            bottom:0;
            left:0;
            text-align: center;
        }
        #logo {
            max-width: 100%;
            height: auto;
            text-align: center;
        }
        img, object, embed, canvas, video, audio, picture {
            max-width: 100%;
            height: auto;
        } 
        div.bloc {
            width:80%; /* largeur du bloc */
            padding:10px; /* aération interne du bloc */
            vertical-align:middle;
            display:inline-block;
            line-height:1.2; /* on rétablit le line-height */
            text-align:center; /* ... et l\'alignement du texte */ 
        }
        .wpm_social {
            padding: 0 45px;
            text-align: center;
        }
        @media (max-width: 640px) {
          body {
            font-size:1.2rem;
          }
        }
        @media (min-width: 640px) {
          body {
            font-size:1rem;
          }
        }
        @media (min-width:960px) {
          body {
            font-size:1.2rem;
          }
        }
        @media (min-width:1100px) {
          body {
            font-size:1.5rem;
          }
        }
        /* On ajoute les styles */
        %ADDSTYLEWYSIJA%
        %ADDSTYLE%
        
    </style>

	<!--[if lt IE 7]>
		<style type="text/css">
			#wrapper { height:100%; }
            div.bloc { display:inline; /* correctif inline-block*/ }
            div.conteneur > span { zoom:1; /* layout */ }
		</style>
	<![endif]-->
	%ANALYTICS%
    %HEADERCODE%
    %CSSSLIDER%
    %SCRIPTSLIDER%
    %SCRIPTSLIDESHOW%
</head>

<body>

	<div id="wrapper">
		
        %TOPSOCIALICON%
        <!-- #header -->
		
		<div id="content">
            %SLIDESHOWAL%
            %LOGOIMAGE%
            %SLIDESHOWBL%
            <div id="sscontent">
                <h3>%TITRE%</h3>
                <p>%TEXTE%</p>
                %SLIDESHOWBT%
                %COUNTER%
                %NEWSLETTER%
            </div>
            %BOTTOMSOCIALICON%
		</div><!-- #content -->
		
		<div id="footer">
            <div class="bloc">%COPYRIGHT%</div>
            <span></span>
		</div><!-- #footer -->
		
	</div><!-- #wrapper -->
	
</body>

</html>
';
    
}

function wpm_getFontsTab() {
    return array("Abel", "Abril Fatface", "Aclonica", "Acme", "Actor", "Adamina", "Advent Pro",
			"Aguafina Script", "Aladin", "Aldrich", "Alegreya", "Alegreya SC", "Alex Brush", "Alfa Slab One", "Alice",
			"Alike", "Alike Angular", "Allan", "Allerta", "Allerta Stencil", "Allura", "Almendra", "Almendra SC", "Amaranth",
			"Amatic SC", "Amethysta", "Andada", "Andika", "Angkor", "Annie Use Your Telescope", "Anonymous Pro", "Antic",
			"Antic Didone", "Antic Slab", "Anton", "Arapey", "Arbutus", "Architects Daughter", "Arimo", "Arizonia", "Armata",
			"Artifika", "Arvo", "Asap", "Asset", "Astloch", "Asul", "Atomic Age", "Aubrey", "Audiowide", "Average",
			"Averia Gruesa Libre", "Averia Libre", "Averia Sans Libre", "Averia Serif Libre", "Bad Script", "Balthazar",
			"Bangers", "Basic", "Battambang", "Baumans", "Bayon", "Belgrano", "Belleza", "Bentham", "Berkshire Swash",
			"Bevan", "Bigshot One", "Bilbo", "Bilbo Swash Caps", "Bitter", "Black Ops One", "Bokor", "Bonbon", "Boogaloo",
			"Bowlby One", "Bowlby One SC", "Brawler", "Bree Serif", "Bubblegum Sans", "Buda", "Buenard", "Butcherman",
			"Butterfly Kids", "Cabin", "Cabin Condensed", "Cabin Sketch", "Caesar Dressing", "Cagliostro", "Calligraffitti",
			"Cambo", "Candal", "Cantarell", "Cantata One", "Cardo", "Carme", "Carter One", "Caudex", "Cedarville Cursive",
			"Ceviche One", "Changa One", "Chango", "Chau Philomene One", "Chelsea Market", "Chenla", "Cherry Cream Soda",
			"Chewy", "Chicle", "Chivo", "Coda", "Coda Caption", "Codystar", "Comfortaa", "Coming Soon", "Concert One",
			"Condiment", "Content", "Contrail One", "Convergence", "Cookie", "Copse", "Corben", "Cousine", "Coustard",
			"Covered By Your Grace", "Crafty Girls", "Creepster", "Crete Round", "Crimson Text", "Crushed", "Cuprum", "Cutive",
			"Damion", "Dancing Script", "Dangrek", "Dawning of a New Day", "Days One", "Delius", "Delius Swash Caps", 
			"Delius Unicase", "Della Respira", "Devonshire", "Didact Gothic", "Diplomata", "Diplomata SC", "Doppio One", 
			"Dorsa", "Dosis", "Dr Sugiyama", "Droid Sans", "Droid Sans Mono", "Droid Serif", "Duru Sans", "Dynalight",
			"EB Garamond", "Eater", "Economica", "Electrolize", "Emblema One", "Emilys Candy", "Engagement", "Enriqueta",
			"Erica One", "Esteban", "Euphoria Script", "Ewert", "Exo", "Expletus Sans", "Fanwood Text", "Fascinate", "Fascinate Inline",
			"Federant", "Federo", "Felipa", "Fjord One", "Flamenco", "Flavors", "Fondamento", "Fontdiner Swanky", "Forum",
			"Francois One", "Fredericka the Great", "Fredoka One", "Freehand", "Fresca", "Frijole", "Fugaz One", "GFS Didot",
			"GFS Neohellenic", "Galdeano", "Gentium Basic", "Gentium Book Basic", "Geo", "Geostar", "Geostar Fill", "Germania One",
			"Give You Glory", "Glass Antiqua", "Glegoo", "Gloria Hallelujah", "Goblin One", "Gochi Hand", "Gorditas",
			"Goudy Bookletter 1911", "Graduate", "Gravitas One", "Great Vibes", "Gruppo", "Gudea", "Habibi", "Hammersmith One",
			"Handlee", "Hanuman", "Happy Monkey", "Henny Penny", "Herr Von Muellerhoff", "Holtwood One SC", "Homemade Apple",
			"Homenaje", "IM Fell DW Pica", "IM Fell DW Pica SC", "IM Fell Double Pica", "IM Fell Double Pica SC",
			"IM Fell English", "IM Fell English SC", "IM Fell French Canon", "IM Fell French Canon SC", "IM Fell Great Primer",
			"IM Fell Great Primer SC", "Iceberg", "Iceland", "Imprima", "Inconsolata", "Inder", "Indie Flower", "Inika",
			"Irish Grover", "Istok Web", "Italiana", "Italianno", "Jim Nightshade", "Jockey One", "Jolly Lodger", "Josefin Sans",
			"Josefin Slab", "Judson", "Julee", "Junge", "Jura", "Just Another Hand", "Just Me Again Down Here", "Kameron",
			"Karla", "Kaushan Script", "Kelly Slab", "Kenia", "Khmer", "Knewave", "Kotta One", "Koulen", "Kranky", "Kreon",
			"Kristi", "Krona One", "La Belle Aurore", "Lancelot", "Lato", "League Script", "Leckerli One", "Ledger", "Lekton",
			"Lemon", "Lilita One", "Limelight", "Linden Hill", "Lobster", "Lobster Two", "Londrina Outline", "Londrina Shadow",
			"Londrina Sketch", "Londrina Solid", "Lora", "Love Ya Like A Sister", "Loved by the King", "Lovers Quarrel",
			"Luckiest Guy", "Lusitana", "Lustria", "Macondo", "Macondo Swash Caps", "Magra", "Maiden Orange", "Mako", "Marck Script",
			"Marko One", "Marmelad", "Marvel", "Mate", "Mate SC", "Maven Pro", "Meddon", "MedievalSharp", "Medula One", "Merriweather",
			"Metal", "Metamorphous", "Michroma", "Miltonian", "Miltonian Tattoo", "Miniver", "Miss Fajardose", "Modern Antiqua",
			"Molengo", "Monofett", "Monoton", "Monsieur La Doulaise", "Montaga", "Montez", "Montserrat", "Moul", "Moulpali",
			"Mountains of Christmas", "Mr Bedfort", "Mr Dafoe", "Mr De Haviland", "Mrs Saint Delafield", "Mrs Sheppards",
			"Muli", "Mystery Quest", "Neucha", "Neuton", "News Cycle", "Niconne", "Nixie One", "Nobile", "Nokora", "Norican",
			"Nosifer", "Nothing You Could Do", "Noticia Text", "Nova Cut", "Nova Flat", "Nova Mono", "Nova Oval", "Nova Round",
			"Nova Script", "Nova Slim", "Nova Square", "Numans", "Nunito", "Odor Mean Chey", "Old Standard TT", "Oldenburg",
			"Oleo Script", "Open Sans", "Open Sans Condensed", "Orbitron", "Original Surfer", "Oswald", "Over the Rainbow",
			"Overlock", "Overlock SC", "Ovo", "Oxygen", "PT Mono", "PT Sans", "PT Sans Caption", "PT Sans Narrow", "PT Serif",
			"PT Serif Caption", "Pacifico", "Parisienne", "Passero One", "Passion One", "Patrick Hand", "Patua One", "Paytone One",
			"Permanent Marker", "Petrona", "Philosopher", "Piedra", "Pinyon Script", "Plaster", "Play", "Playball", "Playfair Display",
			"Podkova", "Poiret One", "Poller One", "Poly", "Pompiere", "Pontano Sans", "Port Lligat Sans", "Port Lligat Slab",
			"Prata", "Preahvihear", "Press Start 2P", "Princess Sofia", "Prociono", "Prosto One", "Puritan", "Quantico",
			"Quattrocento", "Quattrocento Sans", "Questrial", "Quicksand", "Qwigley", "Radley", "Raleway", "Rammetto One",
			"Rancho", "Rationale", "Redressed", "Reenie Beanie", "Revalia", "Ribeye", "Ribeye Marrow", "Righteous", "Rochester",
			"Rock Salt", "Rokkitt", "Ropa Sans", "Rosario", "Rosarivo", "Rouge Script", "Ruda", "Ruge Boogie", "Ruluko",
			"Ruslan Display", "Russo One", "Ruthie", "Sail", "Salsa", "Sancreek", "Sansita One", "Sarina", "Satisfy", "Schoolbell",
			"Seaweed Script", "Sevillana", "Shadows Into Light", "Shadows Into Light Two", "Shanti", "Share", "Shojumaru",
			"Short Stack", "Siemreap", "Sigmar One", "Signika", "Signika Negative", "Simonetta", "Sirin Stencil", "Six Caps",
			"Slackey", "Smokum", "Smythe", "Sniglet", "Snippet", "Sofia", "Sonsie One", "Sorts Mill Goudy", "Special Elite",
			"Spicy Rice", "Spinnaker", "Spirax", "Squada One", "Stardos Stencil", "Stint Ultra Condensed", "Stint Ultra Expanded",
			"Stoke", "Sue Ellen Francisco", "Sunshiney", "Supermercado One", "Suwannaphum", "Swanky and Moo Moo", "Syncopate",
			"Tangerine", "Taprom", "Telex", "Tenor Sans", "The Girl Next Door", "Tienne", "Tinos", "Titan One", "Trade Winds",
			"Trocchi", "Trochut", "Trykker", "Tulpen One", "Ubuntu", "Ubuntu Condensed", "Ubuntu Mono", "Ultra", "Uncial Antiqua",
			"UnifrakturCook", "UnifrakturMaguntia", "Unkempt", "Unlock", "Unna", "VT323", "Varela", "Varela Round", "Vast Shadow",
			"Vibur", "Vidaloka", "Viga", "Voces", "Volkhov", "Vollkorn", "Voltaire", "Waiting for the Sunrise", "Wallpoet",
			"Walter Turncoat", "Wellfleet", "Wire One", "Yanone Kaffeesatz", "Yellowtail", "Yeseva One", "Yesteryear", "Zeyada"
		);
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
    foreach($fonts as &$namefont) {
        $addOption = '';
        if($value == $namefont) { $addOption = 'selected'; }
        $tab_fonts .= '<option value="'.$namefont.'" '.$addOption.' style="font-family: '.$namefont.'">'.$namefont.'</option>';
    }
    $tab_fonts .= "</select>";

    return $tab_fonts;
}
/* Formatte la police sélectionnée */
function wpm_format_font($font) {
    return str_replace('+', ' ', $font);
}

/* Feuille de style par défault */
function wpm_print_style() {
    
    return '

#logo {
    text-align: center;
}

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
#header {
    background: #_COLORHEAD;
}

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

.wpm_horizontal li {
    display: inline-block;
    list-style: none;
    margin:5px;
    opacity:1;
}
.wpm_horizontal li:hover {
    opacity:0.5;
}

.wpm_social {
    padding: 0 45px;
    text-align: center;
}
.wpm_newletter {
    text-align:center;
}
#countdown {
    clear:both;
    margin-left:auto;
    margin-right:auto;
    text-align: center;
}

    ';   
}

/* Feuille de style pour wysija */
function wpm_wysija_style() {
    
    return '
/* FORM */
.wpm_newletter {
    color: #_COLORTXT
}
.abs-req { display: none; }
.widget_wysija_cont .wysija-submit {
    margin-left: auto;
    margin-right: auto;
    background-color: #_COLORBUTTON;
    border: 1px solid #_COLORBUTTON;
    cursor:pointer;
    color:#_COLORTEXTBUTTON;
}
.widget_wysija input {
   border: 1px solid #_COLORBORDER;
   background: #_COLORBG;
   -webkit-border-radius: 4px;
   -moz-border-radius: 4px;
   border-radius: 4px;
   color: #_COLORTXT;
   -webkit-box-shadow: rgba(255,255,255,0.4) 0 1px 0, inset rgba(000,000,000,0.7) 0 0px 0px;
   -moz-box-shadow: rgba(255,255,255,0.4) 0 1px 0, inset rgba(000,000,000,0.7) 0 0px 0px;
   box-shadow: rgba(255,255,255,0.4) 0 1px 0, inset rgba(000,000,000,0.7) 0 0px 0px;
   padding:8px;
   margin-bottom:20px;
   width:280px;
}

.wysija-submit-field {
   border: 1px solid #_COLORBORDER;
   background: #_COLORBUTTON;
   -webkit-border-radius: 4px;
   -moz-border-radius: 4px;
   border-radius: 4px;
   -webkit-box-shadow: rgba(255,255,255,0.4) 0 1px 0, inset rgba(255,255,255,0.4) 0 1px 0;
   -moz-box-shadow: rgba(255,255,255,0.4) 0 1px 0, inset rgba(255,255,255,0.4) 0 1px 0;
   box-shadow: rgba(255,255,255,0.4) 0 1px 0, inset rgba(255,255,255,0.4) 0 1px 0;
   text-shadow: none;
   color: #_COLORTXT;
   font-family: helvetica, serif;
   padding: 8.5px 18px;
   font-size: 14px;
   text-decoration: none;
   vertical-align: baseline;
   cursor:pointer;
   margin-top:0 !important;
}
.wysija-submit-field:hover {
   text-shadow: #_COLORBORDER 0 1px 0;
   border: 1px solid #_COLORBORDER;
   background: #_COLOR_BTN_HOVER;
   /*color: #_COLORTXT;*/
   cursor:pointer;
}
.widget_wysija input:focus {
   text-shadow: none;
   border: 1px solid #_COLORBORDER;
   background: #_COLORBG;
   color: #_COLORTXT;
   cursor:pointer;
}
.wysija-submit-field:active {
   text-shadow: #_COLORBORDER 0 1px 0;
   border: 1px solid #_COLORBORDER;
   background: #_COLOR_BTN_CLICK;
   color: #_COLORTXT;
   cursor:pointer;
}
.widget_wysija .wysija-submit, .widget_wysija .wysija-paragraph { display: inline; }
.wysija-submit-field { margin-top:0 !important; }
   
    ';
}

/* Feuille de style pour MailChimp for WP */
function wpm_mc4wp_style() {
return '
/* FORM */
.mc4wp-form {  } /* the form element */
.mc4wp-form p {  display: inline;padding-left:0;padding-right:0; } /* form paragraphs */
.mc4wp-form label {  } /* labels */

/* input fields */
.mc4wp-form input { 
   border: 1px solid #_COLORBORDER;
   background: #_COLORBG;
   -webkit-border-radius: 4px;
   -moz-border-radius: 4px;
   border-radius: 4px;
   -webkit-box-shadow: rgba(255,255,255,0.4) 0 1px 0, inset rgba(255,255,255,0.4) 0 1px 0;
   -moz-box-shadow: rgba(255,255,255,0.4) 0 1px 0, inset rgba(255,255,255,0.4) 0 1px 0;
   box-shadow: rgba(255,255,255,0.4) 0 1px 0, inset rgba(255,255,255,0.4) 0 1px 0;
   text-shadow: none;
   color: #_COLORTXT;
   font-family: helvetica, serif;
   padding: 8.5px 18px;
   font-size: 14px;
   text-decoration: none;
   vertical-align: baseline;
   cursor:pointer;
   margin-top:0 !important;
} 
/* checkboxes */
.mc4wp-form input[type="checkbox"] {  

} 
/* submit button */
.mc4wp-form input[type="submit"] { 
    background-color: #_COLORBUTTON;
    border: 1px solid #_COLORBUTTON;
    cursor:pointer;
    color:#_COLORTEXTBUTTON;
} 
.mc4wp-form input[type="submit"]:hover { 
   text-shadow: #_COLORBORDER 0 1px 0;
   border: 1px solid #_COLORBORDER;
   background: #_COLOR_BTN_HOVER;
   /*color: #_COLORTXT;*/
   cursor:pointer;
} 
.mc4wp-form input[type="submit"]:active {
   text-shadow: #_COLORBORDER 0 1px 0;
   border: 1px solid #_COLORBORDER;
   background: #_COLOR_BTN_CLICK;
   color: #_COLORTXT;
   cursor:pointer;
}
.mc4wp-alert {  } /* success & error messages */
.mc4wp-success {  } /* success message */
.mc4wp-error {  background: #cc0000; } /* error messages */
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

