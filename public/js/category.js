jQuery(function ($) {
    var posts,
        cursor = 0,
        next;

    $(document).ready(function () {
        var postUrl = $('#category-route').val();
        next = parseInt($('#post-per-page-select').val());
        posts = getPosts(postUrl);
        console.log(next);
    });

    $('#show-more-posts').click(function () {
        appendPosts(posts, parseInt($('#post-per-page-select').val()));
    });

    function declarePostModal() {
        $('.post').click(function () {
            var theModal = $(this).data("target"),
                post = JSON.parse($(this).attr("data-post").split("'").join('"')),
                target = $(this).attr('target');
            renderSchedulePostContainer(target, post);
        });
    }

    function renderSchedulePostContainer(target, data) {
        $('div#' + target).hide();
        $('#text').val(data.message);
        console.log(data.attachments);

        var block = $('div#block' + target);
        block.html($('#post-form-container').show());
        // saveAttachmentsOnLinks(data.attachments);
        sendAttachmentToStorage('images');
    }

    // function sendAttachmentToStorage(entity){
    //     var data = new FormData();
    //     var attachmentStoreUrl = $('#store-url').val();
    //     var csrf = $('#token').val();
    //     data.append('_token', csrf);
    //
    //     $.each($('#' + entity)[0].files, function(i, file) {
    //         data.append(entity + '[]', file);
    //     });
    //
    //     $.ajax({
    //         url: attachmentStoreUrl,
    //         data: data,
    //         cache: false,
    //         contentType: false,
    //         processData: false,
    //         method: 'POST',
    //         type: 'POST', // For jQuery < 1.9
    //         success: function(data){
    //             $('#' + entity).val('');
    //             appendAttachments(JSON.parse(data));
    //             // console.log(JSON.parse(data))
    //         }
    //     });
    // }

    // function appendAttachments(attachments){
    //     var form = $('#edit-post-form');
    //     attachments.forEach(function (item, i, arr) {
    //         if (item.type == 'video') {
    //             var video = '<div class="col-md-3 col-sm-3 col-lg-3 col-xs-4 text-center ' + item.type + item.id + '" id="' + item.type + item.id + '">' +
    //                 '<video height="70" width="70" class="attachment-item"  src="' + item.route + '"></video>' +
    //                 '</div>';
    //             $('.videos-block').append(video);
    //
    //             $('<input>').attr({
    //                 type: 'hidden',
    //                 id: item.type + item.id,
    //                 name: 'videos[]',
    //                 value: item.id
    //             }).appendTo(form);
    //
    //             $('.videos-block-shows').show();
    //         }
    //         if (item.type == 'image') {
    //             var image = '<div class="col-md-3 col-sm-3 col-lg-3 col-xs-4 ' + item.type + item.id + '" id="' + item.type + item.id + '">' +
    //                 '<img class="img-rounded attachment-item" height="80" width="80" src="' + item.route + '" alt="...">' +
    //                 '</div>';
    //             $('.images-block').append(image);
    //
    //             $('<input>').attr({
    //                 type: 'hidden',
    //                 id: item.type + item.id,
    //                 name: 'images[]',
    //                 value: item.id
    //             }).appendTo(form);
    //
    //             $('.images-block-shows').show();
    //         }
    //         $('.form-attachments-block').show();
    //
    //     });
    //
    //     $('.attachment-item').click(function () {
    //         $('input#' + $(this).parent().attr('id')).remove();
    //         $('.' + $(this).parent().attr('id')).remove();
    //         $('#' + $(this).parent().attr('id')).show();
    //         if ($('.images-block').is(':empty')){
    //             $('.images-block-shows').hide();
    //         }
    //         if ($('.videos-block').is(':empty')){
    //             $('.videos-block-shows').hide();
    //         }
    //         if ($('.images-block').is(':empty') && $('.videos-block').is(':empty')){
    //             $('.form-attachments-block').hide();
    //         }
    //
    //     });
    // }










    function declareIFrameEvent() {
        $('.video').click(function () {
            var theModal = $(this).data("target"),
                videoSRC = $(this).attr("data-video");
            $(theModal + ' iframe').attr('src', videoSRC);
            $(theModal + ' button.close').click(function () {
                $(theModal + ' iframe').attr('src', '');
            });
        });
    }

    function getPosts(url) {
        $.get(url, function (data) {
            posts = JSON.parse(data);
            // console.log(posts);
        })
            .done(function () {
                appendPosts(posts, next);
            })
            .fail(function () {
                $('#posts-block').append('<div><h3>Sorry, Something went wrong!</h3></div>')
            })
    }

    function saveAttachmentsOnLinks(attachments) {
        var data = new FormData();
        var csrf = $('#token').val();
        data.append('_token', csrf);
        data.append('attachments', JSON.stringify(attachments));

        $.ajax({
            url: '/store/attachments/links',
            data: data,
            cache: false,
            contentType: false,
            processData: false,
            // method: 'POST',
            type: 'POST', // For jQuery < 1.9
            success: function(data){
                console.log(JSON.parse(data));
                // $('#' + entity).val('');
                // appendAttachments(JSON.parse(data));
                // // console.log(JSON.parse(data))
            }
        });
    }

    function appendPosts(posts, nextStep) {
        var fin = cursor + nextStep;
        while (cursor < fin) {
            try {
                var post = posts.posts[cursor];
                declareIFrameEvent();
                // declarePostModal();
                $('#posts-block')

                    .append('<div class="container" id="block' + cursor + '">\n' +
                        '<div class="container" id="' + cursor + '">\n' +
                        '    <div class="row">\n' +
                        '        <div class="col-md-8 col-md-offset-2">\n' +
                        '            <div class="panel panel-default">\n' +
                        '                <div class="panel-body" id="panel-body">\n' +
                        '                   <input type="hidden" value="' + JSON.stringify(post).split('"').join("'") + '">' +
                        '                   <div class="col-md-8 text-justify">' +
                        '                       <label for="">Text</label>' +
                        '                       <div class="row">' + post.message + '</div>' +
                        '                       <div class="col-md-12 text-justify" id="post-attachments">' +
                                            appendImages(post.attachments) +
                        '                   </div>' +
                        '                   </div>' +
                        '                   <div class="col-md-3 col-md-offset-1">' +
                        '                       <div class="row">' +
                        '                        </div>' +
                        '                           <div class="row">' +
                        '                               <a href="' + post.link + '">post on facebook</a>' +
                        '                               <button class="btn btn-default btn-md post" target="' + cursor + '" data-post="' + JSON.stringify(post).split('"').join("'") + '" data-toggle="modal" data-target="#postModal">Post to my group</button>' +
                        '                           </div>' +
                        '                   </div>' +
                        '                </div>\n' +
                        '                   <div class="row">' +
                        '                </div>\n' +
                        '            </div>\n' +
                        '        </div>\n' +
                        '    </div>\n' +
                        '</div>\n' +
                        '</div>' +
                        '</div>' +
                        '');
                cursor++;
            } catch (e) {
                $('#posts-block')
                    .append('<p><h3>That`s all</h3></p>');
                $('#show-more-posts').hide();
                break;
            }
        }
        declarePostModal();

        function appendImages(attachments) {
            var str = '';

            attachments.images.forEach(function (item, i, arr) {
                str +=
                    '<div class="col-md-2 col-sm-3">' +
                    '<img class="img-rounded" height="70" width="70" src="' + item + '" alt="...">' +
                    '</div>';
                if (attachments.videos) {
                    var www = attachments.videos;
                    str = '<button class="btn btn-success btn-lg video" data-video="' + www + '" data-toggle="modal" data-target="#videoModal">' + str + '</button>\n';
                }
            });
            return str;
        }
    }

});