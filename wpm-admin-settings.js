jQuery(document).ready(function($){

    //Navigation Tabs
    $('.nav-tab-wrapper .nav-tab').click(function(){
        $('.nav-tab-wrapper .nav-tab').removeClass('nav-tab-active');
        el = $(this);
        elid = el.attr('id');
        $('.wpm-menu-group').hide(); 
        $('.'+elid).show();
        el.addClass('nav-tab-active');
    });
    var navUrl = document.location.toString();
    if (navUrl.match('#')) { //anchor-based navigation
        var nav_tab = navUrl.split('#')[1].split(':');
        var current_tab = 'a#wpm-menu-' + nav_tab[0];
        $(current_tab).trigger('click');
        if( nav_tab.length > 1 ){
            section = $("#wpm-opt-"+nav_tab[1]);
            if( section.length > 0 ){
                section.children('h3').trigger('click');
                //$('html, body').animate({ scrollTop: section.offset().top - 30 }); //sends user back to top of form
            }
        }
    }else{
        //set to general tab by default, so we can also add clicked subsections
        document.location = navUrl+"#general";
    }
    $('.nav-tab-link').click(function(){ $($(this).attr('rel')).trigger('click'); }); //links to mimick tabs

});
