<?
$like = APP::Module('Likes')->Get($data['type'], $data['id']);
$like_uniqid = uniqid('like-');
?>
<button id="<?= $like_uniqid ?>" data-token="<?= APP::Module('Crypt')->Encode(json_encode(['type' => $data['type'], 'id' => $data['id']])) ?>" class="btn-like btn palette-<?= $like['state'] ? 'Red' : 'Teal' ?> bg waves-effect <? if (isset($data['class'])) { echo implode(' ', $data['class']); } ?>"><i class="zmdi zmdi-<?= $like['state'] ? 'favorite' : 'favorite-outline' ?>"></i> <?= $data['text'] ?> <sup><?= $like['count'] ?></sup></button>
<? 
APP::$insert['likes_css_sweetalert'] = ['css', 'file', 'before', '</head>', APP::Module('Routing')->root . 'public/ui/vendors/bower_components/bootstrap-sweetalert/lib/sweet-alert.css'];
APP::$insert['likes_js_sweetalert'] = ['js', 'file', 'before', '</body>', APP::Module('Routing')->root . 'public/ui/vendors/bower_components/bootstrap-sweetalert/lib/sweet-alert.min.js'];
ob_start();
?>
<script>
    $(document).ready(function() {
        $(document).on('click', '.btn-like', function() {
            <?
            if (APP::Module('Users')->user['id']) {
                ?>
                $(this).find('sup').html(parseInt($(this).find('sup').text()) + ($(this).hasClass('palette-Teal') ? 1 : -1));
                $(this).toggleClass('palette-Red palette-Teal');
                $(this).find('i').toggleClass('zmdi-favorite zmdi-favorite-outline');

                $.ajax({
                    type: 'post',
                    url: '<?= APP::Module('Routing')->root ?>likes/api/toggle.json',
                    data: {
                        token: $(this).data('token')
                    }
                });
                <?
            } else {
                ?>
                swal({
                    title: 'Oops!',
                    text: 'You must be logged to like this',
                    type: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#DD6B55',
                    confirmButtonText: 'Login',
                    cancelButtonText: 'Cancel',
                    closeOnConfirm: false,
                    closeOnCancel: true
                }, function(isConfirm){
                    if (isConfirm) {
                        window.location.href = '<?= APP::Module('Routing')->root ?>users/actions/login?return=<?= APP::Module('Crypt')->SafeB64Encode(APP::Module('Routing')->SelfUrl()) ?>';
                    }
                });
                <?
            }
            ?>
        });
        
        $(document).on('click', '.likes-modal', function() {
            $('#likes-modal .modal-title').html($(this).data('count') + ' people like this');
            $('#likes-modal .modal-body').html('<center><div class="preloader pl-xxl"><svg class="pl-circular" viewBox="25 25 50 50"><circle class="plc-path" cx="50" cy="50" r="20" /></svg></div></center>');
            $('#likes-modal').modal('show');
            
            $.ajax({
                type: 'post',
                url: '<?= APP::Module('Routing')->root ?>likes/api/users.json',
                data: {
                    token: $(this).data('token')
                },
                success: function(result) {
                    switch(result.status) {
                        case 'success': 
                            $('#likes-modal .modal-body').empty();
                            
                            $.each(result.users, function(i, user) {
                                var username = user.username ? user.username : 'user' + user.user;
                                
                                $('#likes-modal .modal-body').append([
                                    '<div class="media" style="width: 250px; display: inline-block;">',
                                        '<div class="pull-left">',
                                            '<a href="javascript:void(0);">',
                                                '<img class="media-object avatar-img z-depth-1-bottom" src="<?= APP::$conf['location'][0] ?>://www.gravatar.com/avatar/' + user.e_token + '?s=38&d=<?= urlencode(APP::Module('Routing')->root . APP::Module('Users')->settings['module_users_profile_picture']) ?>&t=<?= time() ?>" style="width: 38px">',
                                            '</a>',
                                        '</div>',
                                        '<div class="media-body" >',
                                            '<h4 class="media-heading">',
                                                '<a target="_blank" style="margin: 8px 0 0 3px" href="<?= APP::Module('Routing')->root ?>users/profile/' + user.i_token + '" class="btn btn-default waves-effect btn-xs">' + username + '</a>',
                                            '</h4>',
                                        '</div>',
                                    '</div>'
                                ].join(''));
                            });
                            break;
                        case 'error': alert('Oops! Error :('); break;
                    }
                }
            });
        });
    });
</script>
<?
APP::$insert['likes_js_handler'] = ['js', 'code', 'before', '</body>', ob_get_contents()];
ob_end_clean();
if (($like['count']) && ($data['details'])) {
    ob_start();
    ?>
    <script>
        $(document).ready(function() {
            $('#<?= $like_uniqid ?>').popover({
                content: '<? foreach ($like['users'] as $value) { ?><a title="<?= $value['username'] ? $value['username'] : 'user' . $value['user'] ?>" href="<?= APP::Module('Routing')->root ?>users/profile/<?= APP::Module('Crypt')->Encode($value['user']) ?>" target="_blank" style="display: inline-block"><img class="media-object avatar-img z-depth-1-bottom" src="<?= APP::$conf['location'][0] ?>://www.gravatar.com/avatar/<?= md5($value['email']) ?>?s=38&d=<?= urlencode(APP::Module('Routing')->root . APP::Module('Users')->settings['module_users_profile_picture']) ?>&t=<?= time() ?>" style="width: 38px"></a><? } ?>',
                placement: 'top',
                title: '<a class="likes-modal" href="javascript:void(0)" class="c-teal" data-token="<?= APP::Module('Crypt')->Encode(json_encode(['type' => $data['type'], 'id' => $data['id']])) ?>" data-count="<?= $like['count'] ?>"><b><?= $like['count'] ?></b> people like this</a>',
                trigger: 'manual',
                animation: false,
                html: true,
                template: '<div class="popover" role="tooltip" style="width: 262px"><div class="arrow"></div><h3 class="popover-title"></h3><div class="popover-content"></div></div>'
            }).on('mouseenter', function () {
                var _this = this;
                $(this).popover("show");
                $(".popover").on("mouseleave", function () {
                    $(_this).popover('hide');
                });
            }).on('mouseleave', function () {
                var _this = this;
                setTimeout(function () {
                    if (!$('.popover:hover').length) {
                        $(_this).popover('hide');
                    }
                }, 300);
            });
        });
    </script>
    <?
    APP::$insert['likes_js_' . $like_uniqid] = ['js', 'code', 'before', '</body>', ob_get_contents()];
    ob_end_clean();
}
ob_start();
?>
<div class="modal fade" id="likes-modal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title"></h4>
            </div>
            <div class="modal-body"></div>
            <div class="modal-footer">
                <button type="button" class="btn btn-link" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
<?
APP::$insert['likes_html'] = ['html', false, 'before', '</body>', ob_get_contents()];
ob_end_clean();
?>