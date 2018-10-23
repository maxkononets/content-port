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
});