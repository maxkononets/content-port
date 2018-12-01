jQuery(function ($) {

    $(document).ready(function () {
        galleryItemClick();
        datetimeIsChecked();
    });

    function datetimeIsChecked(){
        var checkbox = $("#datetime-checkbox");
            if (checkbox.is(":checked")) {
                $("#datetime-block").show();
            } else {
                $("#datetime-block").hide();
                $('.datetime-select').val('');
            }
    }

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

    $("#datetime-checkbox").change(function () {
        if ($(this).is(":checked")) {
            $("#datetime-block").show();
        } else {
            $("#datetime-block").hide();
            $('.datetime-select').val('');
        }
    });

    $('.attachment').change(function () {
        var entityId = $(this).attr('id');
        sendAttachmentToStorage(entityId);
    });

    function sendAttachmentToStorage(entity){
        var data = new FormData();
        var attachmentStoreUrl = $('#store-url').val();
        var csrf = $('#token').val();
        data.append('_token', csrf);

        $.each($('#' + entity)[0].files, function(i, file) {
            data.append(entity + '[]', file);
        });

        $.ajax({
            url: attachmentStoreUrl,
            data: data,
            cache: false,
            contentType: false,
            processData: false,
            method: 'POST',
            type: 'POST', // For jQuery < 1.9
            success: function(data){
                $('#' + entity).val('');
                appendAttachments(JSON.parse(data));
                // console.log(JSON.parse(data))
            }
        });
    }

    function appendAttachments(attachments){
        var form = $('#edit-post-form');
        attachments.forEach(function (item, i, arr) {
            if (item.type == 'video') {
                var video = '<div class="col-md-3 col-sm-3 col-lg-3 col-xs-4 text-center ' + item.type + item.id + '" id="' + item.type + item.id + '">' +
                    '<video height="70" width="70" class="attachment-item"  src="' + item.route + '"></video>' +
                    '</div>';
                $('.videos-block').append(video);

                $('<input>').attr({
                    type: 'hidden',
                    id: item.type + item.id,
                    name: 'videos[]',
                    value: item.id
                }).appendTo(form);

                $('.videos-block-shows').show();
            }
            if (item.type == 'image') {
                var image = '<div class="col-md-3 col-sm-3 col-lg-3 col-xs-4 ' + item.type + item.id + '" id="' + item.type + item.id + '">' +
                    '<img class="img-rounded attachment-item" height="80" width="80" src="' + item.route + '" alt="...">' +
                    '</div>';
                $('.images-block').append(image);

                $('<input>').attr({
                    type: 'hidden',
                    id: item.type + item.id,
                    name: 'images[]',
                    value: item.id
                }).appendTo(form);

                $('.images-block-shows').show();
            }
            $('.form-attachments-block').show();

        });

        $('.attachment-item').click(function () {
            $('input#' + $(this).parent().attr('id')).remove();
            $('.' + $(this).parent().attr('id')).remove();
            $('#' + $(this).parent().attr('id')).show();
            if ($('.images-block').is(':empty')){
                $('.images-block-shows').hide();
            }
            if ($('.videos-block').is(':empty')){
                $('.videos-block-shows').hide();
            }
            if ($('.images-block').is(':empty') && $('.videos-block').is(':empty')){
                $('.form-attachments-block').hide();
            }

        });
    }

    function galleryItemClick() {
        $('.gallery-item').click(function () {
            var arr = [
                {
                    id: $(this).attr('id'),
                    route: $(this).attr('src'),
                    type: $(this).attr('type')
                }
            ];
            appendAttachments(arr);
            $(this).parent().hide();
        });
    }

    $('.gallery-prev').click(function (e) {
        e.preventDefault();
        var page = $(this).attr('value').split('page=')[1];
        var type = $(this).attr('type');
        if (+page <= 0 || page == undefined) return;
        var url = $(this).attr('value').split(page)[0];
        var prevUrl = url + (+page -1);
        $('#gallery-next-' + type).attr('value', url + (+page + 1));
        pagination(e, this, page, prevUrl);
    });

    $('.gallery-next').click(function(e){
        e.preventDefault();
        var page = $(this).attr('value').split('page=')[1];
        var type = $(this).attr('type');
        var lastPage = $('#last-page-' + type).val();
        console.log(lastPage);
        if (+page > lastPage) return;
        var url = $(this).attr('value').split(page)[0];
        var prevUrl = url + (+page + 1);
        $('#gallery-prev-' + type).attr('value', url + (+page -1));
        pagination(e, this, page, prevUrl);
    });

    function pagination(e, elem, page, url) {
        var entity = '';
        if($('div#images-tab').has(elem).length){
            entity = 'image';
        }
        if($('div#videos-tab').has(elem).length){
            entity = 'video';
        }
        getAttachments(page, entity);
        $(elem).attr('value', url);
        location.hash = page;
    }

     function getAttachments(page, entity){
         $.ajax({
            url: '/ajax/' + entity + '/paginate?page=' + page
        }).done(function(data){
            var attachments = JSON.parse(data);
            var tag = '';
            var closed = '';
            var block = '';
            if (entity === 'image'){
                tag = 'img';
            }
            if (entity === 'video'){
                tag = 'video';
                closed = '</video>';
            }

            for(var item in attachments){
                if (item == 'next'){
                    continue;
                }
                item = attachments[item];
                block += '' +
                    '<div class="col-md-3 col-sm-3 col-lg-3 col-xs-4 text-center" id="' + entity + item.id + '">' +
                    '<' + tag + ' class="' + tag + '-rounded gallery-item" type="' + entity + '" id="' + item.id + '" height="80" width="80" src="' + item.route + '" alt="...">' + closed +
                    '</div>';
            }
            $('div#' + entity + 's-tab-block').html(block);
            return attachments['next'];
        });
    }

    inputElem = $('<input>').attr({
        type: 'hidden',
        name: 'timezone',
        value: Intl.DateTimeFormat().resolvedOptions().timeZone
    });

    inputElem.appendTo('form#edit-post');
    inputElem.appendTo('form#edit-post-form');
});