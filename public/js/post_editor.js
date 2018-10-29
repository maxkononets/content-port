jQuery(function ($) {
    $('.image-destroy-btn').css({'color':'red'}).click(function () {
        $( this ).html('<strong>+</strong>').css({
            'color':'green'
        });
    });

    $('.video-destroy-btn').css({'color':'red'}).click(function () {
        $( this ).html('<strong>+</strong>').css({
            'color':'green'
        });
    });

    $('.attachment-checkbox').css({
        'aligin' : 'center  '
    }).click(function() {
        $( this ).css({
            'background':'#56f442',
            'padding' : '6px'
        })
    });
});