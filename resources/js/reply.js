let block_click_send_comment = false;
let block_click_edit_comment = false;

$(function() {
    $('#send_comment').click(function() {
        if (block_click_send_comment) {
            alert('please waiting');
            return;
        } else {
            block_click_send_comment = true;
        }
        if ($('#submit_comment').val() == undefined || $('#submit_comment').val() == null || $('#submit_comment').val().length == 0) {
            $('#submit_comment').addClass('is-invalid');
            return ;
        }
        $.ajax({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            method: "POST",
            url: "/reply/store",
            data: { article_id: $('#section_reply').attr('data'), reply_content: $('#submit_comment').val() },
            timeout: 3000,
        }).done(function (msg) {
            alert('data : ' + msg);
            if (msg == 'success') {
                add_comment($('#submit_comment').val());
                $('#submit_comment').val('');
            }
            block_click_send_comment = false;
        });
    });

    $('#edit_comment').click(function() {
        if (block_click_edit_comment) {
            alert('please waiting');
            return;
        } else {
            block_click_edit_comment = true;
        }
        let comment_id = $(this).attr('data');
        let comment_data = $('.comment-text-edit[data="' + comment_id + '"]').val();

        if (comment_data == undefined || comment_data == null || comment_data.length == 0) {
            block_click_edit_comment = false;
            alert('please check data');
            return ;
        }

        // 진행률 노출
        $('#progress_load').removeClass('progress-off');
        let progress_obj = $('#progress_load').detach();
        $('.cont-comment-edit').before(progress_obj);

        // edit input 미노출
        $('.cont-comment-edit, .cont-text-edit').addClass('comment-off');

        $.ajax({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            method: "PUT",
            url: "/reply/edit",
            data: { article_id: $('#section_reply').attr('data'), reply_content: comment_data, comment_id : comment_id },
            timeout: 3000,
        }).done(function (msg) {
            let data;
            if (typeof msg != "undefined") {
                data = JSON.parse(msg);
            }
            $('#progress_load').addClass('progress-off');
            if (typeof data != "undefined" && data.result == 'success') {
                // 수정 성공
                $('.comment-text[data="' + comment_id + '"]').removeClass('comment-off').html(data.data);
            } else {
                // 원복해서 노출
                $('.comment-text[data="' + comment_id + '"]').removeClass('comment-off');
            }
            block_click_edit_comment = false;
        }).fail(function () {
            $('#progress_load').addClass('progress-off');
            $('.comment-text[data="' + comment_id + '"]').removeClass('comment-off');
            let fail_obj = $('#comment_fail').clone();
            $('.mt-2[data="' + comment_id + '"] .wrap-comment').prepend(fail_obj);
            $('.mt-2[data="' + comment_id + '"] .wrap-comment #comment_fail').removeClass('progress-off');
        });
    });

    $('#submit_comment').keyup(function() {
        $(this).removeClass('is-invalid');
    });

    function add_comment(content) {
        var msg_content = '<div class="mt-2"><div class="col-auto p-3"><div class="w-100"><div class="d-flex justify-content-between align-items-center"><div class="col-auto align-items-center inline-items"><img src="https://i.imgur.com/oOCvj8U.jpeg"  class="rounded-circle mr-3 img-reply"/><span class="mr-2">your reply</span></div><small>1s ago</small></div><p class="text-justify comment-text mb-0">' + content + '</p><div class="col-auto user-feed"> <span class="wish"><i class="fa fa-heartbeat mr-2"></i>0</span> <span class="ml-3"><i class="fa fa-comments-o mr-2"></i>Reply</span> </div></div></div></div>';
        $('#cont_extra_comment').append(msg_content);
    }

    $('.comment_edit_action').click(function() {
        let comment_id = $(this).attr('data');
        // editor 초기화
        $('.cont-comment-edit.comment-off .comment-text-edit.comment-off').addClass('comment-off');
        $('.comment-text').removeClass('comment-off');
        $('.comment-text-edit[data="' + comment_id + '"], .cont-comment-edit[data="' + comment_id + '"]').removeClass('comment-off');
        $('.comment-text[data="' + comment_id + '"]').addClass('comment-off');
    });



    $('.comment_del_action').click(function() {
        if ($(this).attr('data') == undefined || $(this).attr('data') == null || $(this).attr('data').length == 0) {
            alert('please refresh');
            return;
        }
        var delete_ele = $(this);
        $.ajax({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            type : "DELETE",
            url: "/reply/del",
            data : { 'comment_id' : $(this).attr('data'), 'article_id' : $('#section_reply').attr('data') },
            timeout: 3000,
            success: function(result) {
                var element = $(delete_ele).closest('div[data="' + $(delete_ele).attr('data') + '"]');
                $(element).remove();
            }
        });
    });
});

