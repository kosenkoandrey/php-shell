<div class="<?= isset($data['class']['holder']) ? $data['class']['holder'] : 'card' ?>">
    <h2 class="title-v4">Write comment</h2>
    <div id="comment-form-holder">
        <?
        if (array_search(APP::Module('Users')->user['role'], $data['login']) === false) {
            switch (APP::Module('Users')->user['role']) {
                case 'default': ?><div class="alert alert-warning"><a class="alert-link" href="<?= APP::Module('Routing')->root ?>users/actions/login?return=<?= APP::Module('Crypt')->SafeB64Encode(APP::Module('Routing')->SelfUrl() . '#comment-form-holder') ?>">Log in</a> to post comments on his own behalf</div><? break;
                case 'new': ?><div class="alert alert-warning">Activate your account to post comments on his own behalf</div><? break;
            }
            ?>
            <form id="post-comment" class="form-horizontal" role="form">
                <input type="hidden" name="token" value="<?= APP::Module('Crypt')->Encode(json_encode($data)) ?>">
                <input type="hidden" name="reply" value="<?= APP::Module('Crypt')->Encode(0) ?>">
                <div class="fg-line margin-bottom-20"><textarea name="message" class="form-control" placeholder="Write you message here..."></textarea></div>
                <button type="submit" class="btn btn-default btn-lg">Post</button>
            </form>
            <?
        } else {
            ?><div class="alert alert-warning"><a class="alert-link" href="<?= APP::Module('Routing')->root ?>users/actions/login?return=<?= APP::Module('Crypt')->SafeB64Encode(APP::Module('Routing')->SelfUrl() . '#comment-form-holder') ?>">Log in</a> to post comments on his own behalf</div><?
        }
        ?>
    </div>
</div>
<? 
APP::$insert['comments_css_sweetalert'] = ['css', 'file', 'before', '</head>', APP::Module('Routing')->root . 'public/ui/vendors/bower_components/bootstrap-sweetalert/lib/sweet-alert.css'];
APP::$insert['comments_js_sweetalert'] = ['js', 'file', 'before', '</body>', APP::Module('Routing')->root . 'public/ui/vendors/bower_components/bootstrap-sweetalert/lib/sweet-alert.min.js'];
APP::$insert['comments_js_autosize'] = ['js', 'file', 'before', '</body>', APP::Module('Routing')->root . 'public/ui/vendors/bower_components/autosize/dist/autosize.min.js'];
ob_start();
?>
<script>
    $(document).ready(function() {
        autosize($('#post-comment [name="message"]'));
        
        $(document).on('click', '.reply', function() {
            var token = $(this).data('token');
            var comment = $(this).closest('.comment').clone();

            $('.reply-comment').remove();

            comment
            .addClass('reply-comment z-depth-1-bottom')
            .removeClass(token)
            .css({
                'margin': '0 0 20px 0',
                'padding': '20px'
            });

            <? if (isset(APP::$modules['Likes'])) { ?>comment.find('.btn-like').remove();<? } ?>
            comment.find('.reply').replaceWith('<a href="javascript:void(0);" id="cancel-reply" class="btn btn-warning btn-xs"><i class="fa fa-close"></i> Cancel</a>');
 
            $('#comment-form-holder').prepend(comment.get(0).outerHTML);
            $('#post-comment input[name="reply"]').val(token);
            $('#post-comment [name="message"]').attr('placeholder', 'Write you reply here...').focus();
            $('html, body').animate({scrollTop: $("#comment-form-holder").offset().top - 200}, 500);
        });
        
        $(document).on('click', '#cancel-reply', function() {
            $(this).closest('.reply-comment').remove();
            $('#post-comment input[name="reply"]').val('');
            $('#post-comment [name="message"]').attr('placeholder', 'Write you message here...');
        });

        $('#post-comment').submit(function(event) {
            event.preventDefault();

            if ($(this).find('[name="message"]').val() === '') { 
                swal({
                    title: 'Error',
                    text: 'Write you comment',
                    type: 'error',
                    timer: 1500,
                    showConfirmButton: false
                });
                            
                return false; 
            }

            $(this).find('[type="submit"]').html('Processing...').attr('disabled', true);

            $.ajax({
                type: 'post',
                url: '<?= APP::Module('Routing')->root ?>comments/api/add.json',
                data: $(this).serialize(),
                success: function(result) {
                    var token = $('#post-comment > [name="reply"]').val();
                    var offset = token ? (parseInt($('.' + token).css('margin-left'), 10) + 35) : 0;
                    var comment = [
                        '<div class="comment row blog-comments-v2 ' + result.token + '" style="margin-left: ' + offset + 'px">',
                            '<div class="commenter">',
                                <?
                                if (APP::Module('Users')->user['role'] != 'default') {
                                    ?>'<img class="rounded-x" src="<?= APP::$conf['location'][0] ?>://www.gravatar.com/avatar/<?= md5(APP::Module('Users')->user['email']) ?>?s=70&d=<?= urlencode(APP::Module('Routing')->root . APP::Module('Users')->settings['module_users_profile_picture']) ?>&t=<?= time() ?>" style="width: 70px">',<?
                                } else {
                                    ?>'<img class="rounded-x" src="<?= APP::Module('Routing')->root . APP::Module('Users')->settings['module_users_profile_picture'] ?>" style="width: 70px">',<?
                                }
                                ?>
                            '</div>',
                            '<div class="comments-itself">',
                                '<h4>',
                                    'I am',
                                    '<span><i class="fa fa-calendar"></i> Recently added</span>',
                                '</h4>',
                                '<p style="white-space: pre-wrap;">' + result.message + '</p>',
                            '</div>',
                        '</div>'
                    ].join('');
                    
                    if ($('.blank-comments-holder').length) {
                        $('.blank-comments-holder')
                        .removeClass('blank-comments-holder')
                        .addClass('<?= isset($data['class']['list']) ? $data['class']['list'] : 'card' ?>')
                        .html([
                            '<h2 class="title-v4">Comments (<span id="total-comments">0</span>)</h2>',
                            '<div id="comments-holder" class="card-body card-padding"></div>'
                        ].join(''));
                    }
                    
                    var holder = token ? $('#comments-holder .' + token).next('.children') : $('#comments-holder');
                    holder.append(comment).append('<div class="children"></div>');
  
                    $('#post-comment > [type="submit"]').html('Post').attr('disabled', false);
                    $('#cancel-reply').trigger('click');
                    $('#post-comment [name="message"]').val('');
                    $('#total-comments').html(parseInt($('#total-comments').text()) + 1);
                    
                    $('html, body').animate({
                        scrollTop: $('.comment.' + result.token).offset().top - 200
                    }, 500);
                    
                    swal({
                        title: 'Done',
                        text: 'You comment has been saved',
                        type: 'success',
                        timer: 1500,
                        showConfirmButton: false
                    });
                }
            });
          });
    });
</script>
<?
APP::$insert['comments_js_handler'] = ['js', 'code', 'before', '</body>', ob_get_contents()];
ob_end_clean();
?>