<style>
    .comment-file{
        cursor: pointer;
        vertical-align: top;
    }
    #new-files {
        margin-top: 20px;
    }
</style>
<div class="<?= isset($data['class']['holder']) ? $data['class']['holder'] : 'card' ?>">
    <div class="card-header">
        <h2>Написать комментарий</h2>
    </div>
    <div id="comment-form-holder" class="card-body card-padding">
        <?
        if (array_search(APP::Module('Users')->user['role'], $data['login']) === false) {
            switch (APP::Module('Users')->user['role']) {
                case 'default': ?><div class="alert alert-warning"><a class="alert-link" href="<?= APP::Module('Routing')->root ?>users/actions/login?return=<?= APP::Module('Crypt')->SafeB64Encode(APP::Module('Routing')->SelfUrl() . '#comment-form-holder') ?>">Войдите</a> для публикации комментариев от вашего имени</div><? break;
                case 'new': ?><div class="alert alert-warning">Активируйте вашу учетную запись для публикации комментариев от вашего имени</div><? break;
            }
            ?>
            <form enctype="multipart/form-data" id="post-comment" class="form-horizontal" role="form">
                <input type="hidden" name="token" value="<?= APP::Module('Crypt')->Encode(json_encode($data)) ?>">
                <input type="hidden" name="reply" value="<?= APP::Module('Crypt')->Encode(0) ?>">
                <? if (APP::Module('Comments')->settings['module_comments_files']) { ?>
                <div class="fg-line m-b-10"><textarea name="message" class="form-control" placeholder="Начните писать ваш комментарий..."></textarea></div>
                <div class="fg-line m-b-15">
                    <div id="new-files"></div>
                    <a href="javascript:void(0)" id="add-file" class="btn btn-default btn-sm m-b-20"><i class="zmdi zmdi-plus"></i> Добавить файл</a>
                </div>
                <? }else{ ?>
                <div class="fg-line m-b-15"><textarea name="message" class="form-control" placeholder="Начните писать ваш комментарий..."></textarea></div>
                <? } ?>
                <button type="submit" class="btn palette-Teal bg waves-effect btn-lg"><i class="zmdi zmdi-mail-send"></i> Отправить</button>
            </form>
            <?
        } else {
            ?><div class="alert alert-warning"><a class="alert-link" href="<?= APP::Module('Routing')->root ?>users/actions/login?return=<?= APP::Module('Crypt')->SafeB64Encode(APP::Module('Routing')->SelfUrl() . '#comment-form-holder') ?>">Войдите</a> для публикации комментариев от вашего имени</div><?
        }
        ?>
    </div>
</div>
<? 
APP::$insert['comments_css_sweetalert'] = ['css', 'file', 'before', '</head>', APP::Module('Routing')->root . 'public/ui/vendors/bower_components/bootstrap-sweetalert/lib/sweet-alert.css'];
APP::$insert['comments_js_sweetalert'] = ['js', 'file', 'before', '</body>', APP::Module('Routing')->root . 'public/ui/vendors/bower_components/bootstrap-sweetalert/lib/sweet-alert.min.js'];
APP::$insert['comments_js_autosize'] = ['js', 'file', 'before', '</body>', APP::Module('Routing')->root . 'public/ui/vendors/bower_components/autosize/dist/autosize.min.js'];
APP::$insert['comments_js_fileinput'] = ['js', 'file', 'before', '</body>', APP::Module('Routing')->root . 'public/ui/vendors/fileinput/fileinput.min.js'];
ob_start();
?>
<script>
    $(document).ready(function() {
        autosize($('#post-comment [name="message"]'));
        
        $('#post-comment #add-file').on('click', function() {
            $('#new-files').append('<div class="form-group"><div class="file"><div class="col-sm-12"><div class="fileinput fileinput-new" data-provides="fileinput"><span class="btn btn-default btn-sm waves-effect btn-file m-r-10"><span class="fileinput-new">Выбрать файл</span><span class="fileinput-exists">Выбрать другой файл</span><input type="file" name="file[]"></span><span class="fileinput-filename"></span><a href="#" class="close remove" data-dismiss="fileinput">&times;</a></div></div></div></div>');
        });

        $(document).on('click', '#post-comment .file .remove', function(event) {
            $(this).closest('.form-group').remove();
        });
        
        $('#file').on('change', function() {
            if (this.files && this.files[0]) {
                var reader = new FileReader();

                reader.readAsDataURL(this.files[0]);
            } else {
                swal('Sorry - you\'re browser doesn\'t support the FileReader API');
            }
        });
        
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
            comment.find('.reply').replaceWith('<a href="javascript:void(0);" id="cancel-reply" class="btn palette-Red bg waves-effect btn-xs"><i class="zmdi zmdi-close-circle"></i> Не отвечать</a>');
            comment.find('.media-heading').prepend('<span class="label label-warning"><i class="zmdi zmdi-mail-reply"></i> Ответ</span>');
 
            $('#comment-form-holder').prepend(comment.get(0).outerHTML);
            $('#post-comment input[name="reply"]').val(token);
            $('#post-comment [name="message"]').attr('placeholder', 'Начните писать ваш комментарий...').focus();
            $('html, body').animate({scrollTop: $("#comment-form-holder").offset().top - 200}, 500);
        });
        
        $(document).on('click', '#cancel-reply', function() {
            $(this).closest('.reply-comment').remove();
            $('#post-comment input[name="reply"]').val('');
            $('#post-comment [name="message"]').attr('placeholder', 'Начните писать ваш комментарий...');
        });

        $('#post-comment').submit(function(event) {
            event.preventDefault();

            if ($(this).find('[name="message"]').val() === '') { 
                swal({
                    title: 'Ошибка',
                    text: 'Во время отправки комментария произошла ошибка',
                    type: 'error',
                    timer: 1500,
                    showConfirmButton: false
                });
                            
                return false; 
            }

            $(this).find('[type="submit"]').html('Отправка...').attr('disabled', true);
            var data = new FormData($(this).get(0));
            
            $.ajax({
                type: 'post',
                url: '<?= APP::Module('Routing')->root ?>comments/api/add.json',
                data: data,
                processData: false,
                contentType: false,
                success: function(result) {
                    <? if (APP::Module('Comments')->settings['module_comments_files']) { ?>$('#new-files').html('');<? } ?>
                    var token = $('#post-comment > [name="reply"]').val();
                    var offset = token ? (parseInt($('.' + token).css('margin-left'), 10) + 35) : 0;
                    var file = [];
                    
                    if(result.file.length){
                        $.each(result.file, function(i, j){
                            switch(j.type){
                                case 'image/png':
                                case 'image/jpeg':
                                    file.push('<p><img style="height:200px;" src="'+j.url+'"><p><a href="'+j.url+'">Download</a></p></p>');
                                    break;
                                case 'video/mp4':
                                    file.push('<p><video width="640" height="480" controls><source src="'+j.url+'" type="video/mp4"></video><p><a href="'+j.url+'">Download</a></p></p>');
                                    break;
                                case 'application/pdf':
                                    file.push('<p><span class="pdf-block" ><span style="display: inline-block" class="avatar-char palette-Orange-400 bg m-r-5"><i class="zmdi zmdi-file"></i><a href="'+j.url+'">Download</a></p>');
                                    break;
                            }
                        });
                    }
                    
                    var comment = [
                        '<div class="comment media ' + result.token + '" style="margin-left: ' + offset + 'px">',
                            '<div class="pull-left">',
                                <?
                                if (APP::Module('Users')->user['role'] != 'default') {
                                    ?>'<img class="media-object avatar-img z-depth-1-bottom" src="<?= APP::$conf['location'][0] ?>://www.gravatar.com/avatar/<?= md5(APP::Module('Users')->user['email']) ?>?s=38&d=<?= urlencode(APP::Module('Routing')->root . APP::Module('Users')->settings['module_users_profile_picture']) ?>&t=<?= time() ?>" style="width: 38px">',<?
                                } else {
                                    ?>'<img class="media-object avatar-img z-depth-1-bottom" src="<?= APP::Module('Routing')->root . APP::Module('Users')->settings['module_users_profile_picture'] ?>" style="width: 38px">',<?
                                }
                                ?>
                            '</div>',
                            '<div class="media-body">',
                                '<h4 class="media-heading">',
                                    'Я',
                                    '<p class="m-b-5 m-t-10 f-12 c-gray"><i class="zmdi zmdi-calendar"></i> только что</p>',
                                '</h4>',
                                '<p style="white-space: pre-wrap; margin-bottom: 10px;">' + result.message + '</p>',
                                file.join(''),
                            '</div>',
                        '</div>'
                    ].join('');
                    
                    if ($('.blank-comments-holder').length) {
                        $('.blank-comments-holder')
                        .removeClass('blank-comments-holder')
                        .addClass('<?= isset($data['class']['list']) ? $data['class']['list'] : 'card' ?>')
                        .html([
                            '<div class="card-header">',
                                '<h2><span class="label label-warning"><i class="zmdi zmdi-comments"></i> <span id="total-comments">0</span></span> комментариев</h2>',
                            '</div>',
                            '<div id="comments-holder" class="card-body card-padding"></div>'
                        ].join(''));
                    }
                    
                    var holder = token ? $('#comments-holder .' + token).next('.children') : $('#comments-holder');
                    holder.append(comment).append('<div class="children"></div>');
  
                    $('#post-comment > [type="submit"]').html('Отправить').attr('disabled', false);
                    $('#cancel-reply').trigger('click');
                    $('#post-comment [name="message"]').val('');
                    $('#total-comments').html(parseInt($('#total-comments').text()) + 1);
                    
                    $('html, body').animate({
                        scrollTop: $('.comment.' + result.token).offset().top - 200
                    }, 500);
                    
                    swal({
                        title: 'Готово',
                        text: 'Ваш комментарий был успешно отправлен',
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
