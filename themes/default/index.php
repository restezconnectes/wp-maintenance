<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <meta name="viewport" content="width=device-width, user-scalable=yes" />
        <title>{TitleSEO}</title>
        {MetaDescription}
        {Favicon}
        {Head}
        <style type='text/css'>
            /* VERSION {Version} */
            html,
            body {margin:0;padding:0;height:100%;font-size:100%;}
            #wrapper {min-height:100%;position:relative;}
            #header {padding:10px;}
            #content {padding-bottom:100px; /* Height of the footer element */}
            #footer {width:100%;line-height:60px;position:absolute;bottom:0;left:0;text-align: center;}
            #logo {max-width: 100%;height: auto;text-align: center;}
            img, object, embed, canvas, video, audio, picture {max-width: 100%;height: auto;} 
            div.bloc {width:80%;padding:10px;vertical-align:middle;display:inline-block;line-height:1.2;text-align:center;}
            .wpm_social {padding: 0 45px;text-align: center;}
            @media (max-width: 640px) {body {font-size:1.2rem;}}
            @media (min-width: 640px) {body {font-size:1rem;}}
            @media (min-width:960px) {body {font-size:1.2rem;}}
            @media (min-width:1100px) {body {font-size:1.5rem;}}
        </style>
        {CustomCSS}
        {AddStyleWysija}
        <!--[if lt IE 7]>
        <style type="text/css">
            #wrapper { height:100%; }
            div.bloc { display:inline; /* correctif inline-block*/ }
            div.conteneur > span { zoom:1; /* layout */ }
        </style>
        <![endif]-->
        {HeaderCode}
        {SliderCSS}
        {ScriptSlider}
        {ScriptSlideshow}
    </head>

    <body>

        <div id="wrapper">
            
            {TopSocialIcon}
            <!-- #header -->
            
            <div id="content">
                {SlideshowAL}
                {Logo}
                {SlideshowBL}
                <div id="sscontent">
                    <h3>{Title}</h3>
                    <p>{Text}</p>
                    {SlideshowBT}
                    {Counter}
                    {Newsletter}
                </div>
                {BottomSocialIcon}
            </div><!-- #content -->
            
            <div id="footer">
                <div class="bloc">{Copyrights}</div>
                <span></span>
            </div><!-- #footer -->
            
        </div><!-- #wrapper -->
        
    </body>

</html>
<!-- WP Maintenance - VERSION {Version} - By RestezConnectés. Learn more: https://madeby.restezconnectes.fr/project/wp-maintenance/ -->