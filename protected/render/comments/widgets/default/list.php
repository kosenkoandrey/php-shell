<?
$comments = APP::Module('Comments')->Get($data['type'], $data['id']);

if ($comments['total']) {
    ?>
    <div class="<?= isset($data['class']['holder']) ? $data['class']['holder'] : 'card' ?>">
        <div class="card-header">
            <h2><span class="label label-warning"><i class="zmdi zmdi-comments"></i> <span id="total-comments"><?= $comments['total'] ?></span></span> comments </h2>
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
                    $username = 'Guest';
                }
                ?>
                <div id="comment-<?= $comment_hash ?>" class="comment media <?= $comment_hash ?>" style="margin-left: <?= $offset ?>px">
                    <div class="pull-left">
                        <?
                        if ($username == 'Guest') {
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
                            if ($username == 'Guest') {
                                ?><a href="javascript:void(0);" class="btn btn-default waves-effect btn-xs"><?= $username ?></a><?
                            } else {
                                ?><a target="_blank" href="<?= APP::Module('Routing')->root ?>users/profile/<?= APP::Module('Crypt')->Encode($comment['user']) ?>" class="btn btn-default waves-effect btn-xs"><?= $username ?></a><?
                            }
                            ?>
                            <p class="m-b-5 m-t-10 f-12 c-gray"><i class="zmdi zmdi-calendar"></i> <?= date('Y-m-d H:i:s', $comment['up_date']) ?></p>
                        </h4>
                        <p style="white-space: pre-wrap;" class="m-b-10"><?= $comment['message'] ?></p>
                        <div class="btn-group btn-group-xs m-b-10" role="group">
                            <button type="button" class="reply btn palette-Teal bg waves-effect btn-xs" data-token="<?= $comment_hash ?>"><i class="zmdi zmdi-mail-reply"></i> Reply</button>
                            <?
                            if ((isset(APP::$modules['Likes'])) && ($likes)) {
                                APP::Render('likes/widgets/default', 'include', [
                                    'type' => APP::Module('DB')->Select(APP::Module('Likes')->settings['module_likes_db_connection'], ['fetchColumn', 0], ['id'], 'likes_objects', [['name', '=', "Comment", PDO::PARAM_STR]]),
                                    'id' => $comment['id'],
                                    'text' => 'Like',
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