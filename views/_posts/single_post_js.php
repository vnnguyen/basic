// SHARE MAIL
$('.action-share-mail').on('click', function(e){
    e.preventDefault()
    var link = $(this)
    var share = $(this).hasClass('share-attachments-only') ? 'attachments' : ($(this).hasClass('stop-sharing') ? 'stop' : 'mail')
    var jqxhr = $.post( "/posts/ajax?action=share-mail&xh", {
        post_id: $(this).data('id'),
        share: share,

    })
    .done(function(data) {
        if (data.share) {
            if (data.share == 'yes') {
                link.closest('.div-mail').find('.post-shared').removeClass('d-none')
            } else {
                link.closest('.div-mail').find('.post-shared').addClass('d-none')
            }
        }
    })
    .fail(function() {
        alert( "Error sharing mail" );
    })
})

// SHARE POST
$('.action-share-post').on('click', function(e){
    e.preventDefault()
    var link = $(this)
    var share = $(this).hasClass('share-attachments-only') ? 'attachments' : ($(this).hasClass('stop-sharing') ? 'stop' : 'post')
    var jqxhr = $.post( "/posts/ajax?action=share&xh", {
        post_id: $(this).data('id'),
        share: share,
    })
    .done(function(data) {
        if (data.share) {
            if (data.share == 'yes') {
                link.closest('.div-post').find('.post-shared').removeClass('d-none')
            } else {
                link.closest('.div-post').find('.post-shared').addClass('d-none')
            }
        }
    })
    .fail(function() {
        alert( "Error sharing post" );
    })
})

// LOAD REPLY
$('.action-load-reply').on('click', function(e){
    e.preventDefault()
    if ($(this).hasClass('reply-not-loaded')) {
        $(this).toggleClass('reply-not-loaded font-weight-bold')
        $(this).closest('.mt-2').addClass('p-2 post-speech-bubble')
        var reply_id = $(this).data('id')
        var div = $('#div-post-' + reply_id)
        div.find('.post-body:eq(0)').clone(true).insertAfter($(this))
    }
})

// REPLY POST INLINE
$('.action-reply-post').on('click', function(e){
    e.preventDefault()
    if ($(this).hasClass('replying')) {
        return false;
    }
    cancelAllPosts()
    $(this).addClass('replying')

    var div = $(this).closest('.post-content').find('.you-are-replying:eq(0)')
    div.removeClass('d-none')

    post_thread_id = $(this).data('thread_id')

    $('#title').closest('.form-group').hide()

    $('#post-form').appendTo(div).removeClass('d-none')

    var editor1 = CKEDITOR.replace('editor1', CKEconfig)
    editor1.once( 'instanceReady', function() {
        this.focus()
        })

    // $('html, body').animate({
    //     scrollTop: $("#post-form").offset().top
    // }, 500);
})

// DELETE POST
$('.div-post').on('click', '.action-delete-post', function(e){
    e.preventDefault();

    var url = $(this).attr('href')
    var post_id = $(this).data('id')
    var div = $('#div-post-' + post_id)
    var hr = $('#hr-post-' + post_id)
    var rep = $('#div-reply-' + post_id)

    if (!confirm('Delete message now?' + "\n" + 'All related attachments will also be deleted.' + "\n" + 'This action is cannot be undone.')) {
        return false;
    }

    div.block({ 
        message: '<div><i class="fa fa-refresh fa-spin"></i> Deleting post...</div>', 
        css: { border: '3px solid #a00', padding:20 } 
    }); 

    $.post(url + '?xh', {
        })
    .done(function(){
        hr.remove()
        div.remove()
        rep.remove()
        })
    .fail(function(){
        alert('Error deleting post')
        })
    .always(function(){
        div.unblock();
        })
})

