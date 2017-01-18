<?
APP::$insert['comments_css_lightgallery'] = ['css', 'file', 'after', '<head>', APP::Module('Routing')->root . 'public/ui/vendors/bower_components/lightgallery/dist/css/lightgallery.min.css'];
APP::$insert['comments_js_lightgallery'] = ['js', 'file', 'before', '</body>', APP::Module('Routing')->root . 'public/ui/vendors/bower_components/lightgallery/dist/js/lightgallery-all.min.js'];

ob_start();
?>
<script>
$('.comment-files').lightGallery({
    thumbnail: true
});
</script>
<?
APP::$insert['comments_js_lightbox_handler'] = ['js', 'code', 'before', '</body>', ob_get_contents()];
ob_end_clean();

ob_start();
?>
<style>
    .lightbox .lightbox-item > img {
        width: initial;
        max-width: 300px;
    }
</style>
<?
APP::$insert['comments_css_lightgallery_fix'] = ['css', 'code', 'before', '</head>', ob_get_contents()];
ob_end_clean();

$comments = APP::Module('Comments')->Get($data['type'], $data['id']);

if ($comments['total']) {
    ?>
    <div class="<?= isset($data['class']['holder']) ? $data['class']['holder'] : 'card' ?>">
        <div class="card-header">
            <h2><span class="label label-warning"><i class="zmdi zmdi-comments"></i> <span id="total-comments"><?= $comments['total'] ?></span></span> комментариев</h2>
        </div>
        <div id="comments-holder" class="card-body card-padding">
            <?
            function PrintComment($comment, $offset = 0, $likes = true) {
                $comment_hash = APP::Module('Crypt')->Encode($comment['id']);

                if ($comment['username']) {
                    $username = $comment['username'];
                } else if ($comment['email']) {
                    $username = 'user' . $comment['user'];
                } else {
                    $username = 'Гость';
                }
                ?>
                <div id="comment-<?= $comment_hash ?>" class="comment media <?= $comment_hash ?>" style="margin-left: <?= $offset ?>px">
                    <div class="pull-left">
                        <?
                        if ($username == 'Гость') {
                            ?><img class="media-object avatar-img z-depth-1-bottom" src="<?= APP::Module('Routing')->root . APP::Module('Users')->settings['module_users_profile_picture'] ?>" style="width: 38px"><?
                        } else {
                            ?>
                            <a target="_blank" href="<?= APP::Module('Routing')->root ?>users/profile/<?= APP::Module('Crypt')->Encode($comment['user']) ?>">
                                <img class="media-object avatar-img z-depth-1-bottom" src="<?= APP::$conf['location'][0] ?>://www.gravatar.com/avatar/<?= md5($comment['email']) ?>?s=38&d=<?= urlencode(APP::Module('Routing')->root . APP::Module('Users')->settings['module_users_profile_picture']) ?>&t=<?= time() ?>" style="width: 38px">
                            </a>
                            <?
                        }
                        ?>
                    </div>
                    <div class="media-body">
                        <h4 class="media-heading">
                            <?
                            if ($username == 'Гость') {
                                ?><a href="javascript:void(0);" class="btn btn-default waves-effect btn-xs"><?= $username ?></a><?
                            } else {
                                ?><a target="_blank" href="<?= APP::Module('Routing')->root ?>users/profile/<?= APP::Module('Crypt')->Encode($comment['user']) ?>" class="btn btn-default waves-effect btn-xs"><?= $username ?></a><?
                            }
                            ?>
                            <p class="m-b-5 m-t-10 f-12 c-gray"><i class="zmdi zmdi-calendar"></i> <?= date('Y-m-d H:i:s', $comment['up_date']) ?></p>
                        </h4>
                        <p style="white-space: pre-wrap;" class="m-b-10"><?= $comment['message'] ?></p>
                        <div class="comments_files">
                            <?
                            $comment_files = [];
                            
                            if (APP::Module('Comments')->settings['module_comments_files']) { 
                                foreach($comment['files'] as $file){
                                    switch ($file['file_type']) {
                                        /*
                                        case 'video/mp4':
                                            ?>
                                            <p><video width="640" height="480" controls>
                                                <source src="<?= APP::Module('Routing')->root ?>comments/download/<?= APP::Module('Crypt')->Encode($file['file_id']) ?>" type="video/mp4">
                                                </video><p><a href="<?= APP::Module('Routing')->root ?>comments/download/<?= APP::Module('Crypt')->Encode($file['file_id']) ?>">Download</a></p></p>
                                            <?
                                            break;
                                        case 'application/pdf':
                                            ?><p><span class="pdf-block" ><span style="display: inline-block" class="avatar-char palette-Orange-400 bg m-r-5"><i class="zmdi zmdi-file"></i></span></span><a href="<?= APP::Module('Routing')->root ?>comments/download/<?= APP::Module('Crypt')->Encode($file['file_id']) ?>">Download</a></p><?
                                            break;
                                         */
                                        case 'image/cgm':
                                        case 'image/fits':
                                        case 'image/g3fax':
                                        case 'image/gif':
                                        case 'image/ief':
                                        case 'image/jp2':
                                        case 'image/jpeg':
                                        case 'image/jpm':
                                        case 'image/jpx':
                                        case 'image/naplps':
                                        case 'image/png':
                                        case 'image/prs.btif':
                                        case 'image/prs.pti':
                                        case 'image/t38':
                                        case 'image/tiff':
                                        case 'image/tiff-fx':
                                        case 'image/vnd.adobe.photoshop':
                                        case 'image/vnd.cns.inf2':
                                        case 'image/vnd.djvu':
                                        case 'image/vnd.dwg':
                                        case 'image/vnd.dxf':
                                        case 'image/vnd.fastbidsheet':
                                        case 'image/vnd.fpx':
                                        case 'image/vnd.fst':
                                        case 'image/vnd.fujixerox.edmics-mmr':
                                        case 'image/vnd.fujixerox.edmics-rlc':
                                        case 'image/vnd.globalgraphics.pgb':
                                        case 'image/vnd.microsoft.icon':
                                        case 'image/vnd.mix':
                                        case 'image/vnd.ms-modi':
                                        case 'image/vnd.net-fpx':
                                        case 'image/vnd.sealed.png':
                                        case 'image/vnd.sealedmedia.softseal.gif':
                                        case 'image/vnd.sealedmedia.softseal.jpg':
                                        case 'image/vnd.svf':
                                        case 'image/vnd.wap.wbmp':
                                        case 'image/vnd.xiff':
                                            $comment_files['images'][] = '<div data-src="' . APP::Module('Routing')->root . 'comments/download/' . APP::Module('Crypt')->Encode($file['file_id']) . '"><div class="lightbox-item"><img src="' . APP::Module('Routing')->root . 'comments/download/' . APP::Module('Crypt')->Encode($file['file_id']) . '" class="thumbnail"></div></div>';
                                            break;
                                        case 'audio/aac':
                                        case 'audio/wav':
                                        case 'audio/webm':
                                        case 'audio/basic':
                                        case 'auido/L24':
                                        case 'audio/mid': 
                                        case 'audio/mpeg':
                                        case 'audio/mp4':
                                        case 'audio/x-aiff':	  
                                        case 'audio/x-mpegurl':	 
                                        case 'audio/vnd.rn-realaudio':	 
                                        case 'audio/ogg':
                                        case 'audio/vorbis':
                                        case 'case audio/vnd.wav':
                                        case 'audio/mp3':
                                            $comment_files['audio'][] = '<div class="m-b-15 m-t-15" style="border: 1px solid #e3e3e3"><audio style="width: 100%;" src="' . APP::Module('Routing')->root . 'comments/download/' . APP::Module('Crypt')->Encode($file['file_id']) . '" preload="auto" controls></audio></div>';
                                            break;
                                    }
                                }
                            }
                            
                            if (isset($comment_files['images'])) {
                                ?>
                                <div class="lightbox">
                                    <?
                                    foreach ($comment_files['images'] as $value) {
                                        echo $value;
                                    }
                                    ?>
                                </div>
                                <?
                            }
                            
                            if (isset($comment_files['audio'])) {
                                ?>
                                <div class="audio">
                                    <?
                                    foreach ($comment_files['audio'] as $value) {
                                        echo $value;
                                    }
                                    ?>
                                </div>
                                <?
                            }
                            ?>
                        </div>
                        <div class="btn-group btn-group-xs m-b-10" role="group">
                            <button type="button" class="reply btn palette-Teal bg waves-effect btn-xs" data-token="<?= $comment_hash ?>"><i class="zmdi zmdi-mail-reply"></i> Ответить</button>
                            <?
                            if ((isset(APP::$modules['Likes'])) && ($likes)) {
                                APP::Render('likes/widgets/default', 'include', [
                                    'type' => APP::Module('DB')->Select(APP::Module('Likes')->settings['module_likes_db_connection'], ['fetchColumn', 0], ['id'], 'likes_objects', [['name', '=', "Comment", PDO::PARAM_STR]]),
                                    'id' => $comment['id'],
                                    'text' => 'Мне нравится',
                                    'class' => ['btn-xs', 'f-700'],
                                    'details' => false
                                ]);
                            }
                            ?>
                        </div>
                    </div>
                </div>
                <?
            }

            function ChildrenComments($comment, $children_comments, $offset = 0, $likes = true) {
                $offset = $offset + 35;
                ?>
                <div class="children m-t-15">
                    <?
                    foreach ($children_comments as $children_comment) {
                        if ($children_comment['sub_id'] == $comment['id']) {
                            PrintComment($children_comment, $offset, $likes);
                            ChildrenComments($children_comment, $children_comments, $offset, $likes);
                        }
                    }
                    ?>
                </div>
                <?
            }

            foreach ($comments['root'] as $sub_id) {
                PrintComment($sub_id, 0, $data['likes']);
                ChildrenComments($sub_id, $comments['children'], 0, $data['likes']);
            }
            ?>
        </div>
    </div>
    <?
} else {
    ?><div class="blank-comments-holder"></div><?
}
?>