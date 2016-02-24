jQuery(document).ready(function() {
    
    var $input = jQuery( '.datepicker' ).pickadate({
        formatSubmit: 'dd/mm/yyyy',
        // min: [2015, 7, 14],
        container: '#container',
        // editable: true,
        closeOnSelect: false,
        closeOnClear: false,
        first_day: 1,
        date_min: [ 2015, 12, 12 ],
    })

    var picker = $input.pickadate('picker')

    // picker.set('select', '14 October, 2014')
    // picker.open()

    // $('button').on('click', function() {
    //     picker.set('disable', true);
    // });
    /*var $input = jQuery( '.datepicker' ).pickatime({
    })
    var picker = $input.pickatime('picker')
    picker.open()*/
    
});