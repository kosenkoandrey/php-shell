<?
$comments = APP::Module('Comments')->Get($data['type'], $data['id']);

if ($comments['total']) {
    ?>
    <div class="<?= isset($data['class']['holder']) ? $data['class']['holder'] : 'card' ?>">
        <h2 class="title-v4">Comments (<span id="total-comments"><?= $comments['total'] ?></span>)</h2>
        <div id="comments-holder">
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
                <div id="comment-<?= $comment_hash ?>" class="comment row blog-comments-v2 <?= $comment_hash ?>" style="margin-left: <?= $offset ?>px">
                    <div class="commenter">
                        <?
                        if ($username == 'Guest') {
                            ?><img class="rounded-x" src="<?= APP::Module('Routing')->root . APP::Module('Users')->settings['module_users_profile_picture'] ?>" style="width: 70px"><?
                        } else {
                            ?>
                            <a target="_blank" href="<?= APP::Module('Routing')->root ?>users/profile/<?= APP::Module('Crypt')->Encode($comment['user']) ?>">
                                <img class="rounded-x" src="<?= APP::$conf['location'][0] ?>://www.gravatar.com/avatar/<?= md5($comment['email']) ?>?s=70&d=<?= urlencode(APP::Module('Routing')->root . APP::Module('Users')->settings['module_users_profile_picture']) ?>&t=<?= time() ?>" style="width: 70px">
                            </a>
                            <?
                        }
                        ?>
                    </div>
                    <div class="comments-itself">
                        <h4>
                            <?
                            if ($username == 'Guest') {
                                ?><a href="javascript:void(0);"><?= $username ?></a><?
                            } else {
                                ?><a target="_blank" href="<?= APP::Module('Routing')->root ?>users/profile/<?= APP::Module('Crypt')->Encode($comment['user']) ?>"><?= $username ?></a><?
                            }
                            ?>
                            <span><i class="fa fa-calendar"></i> <?= date('Y-m-d H:i:s', $comment['up_date']) ?></span>
                        </h4>
                        <p style="white-space: pre-wrap;"><?= $comment['message'] ?></p>
                        <div class="btn-group btn-group-xs" role="group">
                            <button type="button" class="reply btn btn-default btn-xs" data-token="<?= $comment_hash ?>"><i class="fa fa-reply"></i> Reply</button>
                            <?
                            if ((isset(APP::$modules['Likes'])) && ($likes)) {
                                APP::Render('likes/widgets/blog', 'include', [
                                    'type' => APP::Module('DB')->Select(APP::Module('Likes')->settings['module_likes_db_connection'], ['fetchColumn', 0], ['id'], 'likes_objects', [['name', '=', "Comment", PDO::PARAM_STR]]),
                                    'id' => $comment['id'],
                                    'text' => 'Like',
                                    'class' => ['btn-xs'],
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