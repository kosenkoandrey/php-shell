<!DOCTYPE html>
<!--[if IE 8]> <html lang="en" class="ie8"> <![endif]-->
<!--[if IE 9]> <html lang="en" class="ie9"> <![endif]-->
<!--[if !IE]><!--> <html lang="en"> <!--<![endif]-->
<head>
    <title><?= $data['article']['page_title'] ?></title>

    <!-- Meta -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="<?= $data['article']['description'] ?>">
    <meta name="keywords" content="<?= $data['article']['keywords'] ?>">
    <meta name="robots" content="<?= $data['article']['robots'] ?>">

    <!-- Favicon -->
    <?= APP::Render('favicon/widget') ?>

    <!-- Web Fonts -->
    <link rel="stylesheet" type="text/css" href="https://fonts.googleapis.com/css?family=Roboto+Slab:400,300,700">

    <!-- CSS Global Compulsory -->
    <link rel="stylesheet" href="<?= APP::Module('Routing')->root ?>public/blog/plugins/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="<?= APP::Module('Routing')->root ?>public/blog/css/blog.style.css">

    <!-- CSS Header and Footer -->
    <link rel="stylesheet" href="<?= APP::Module('Routing')->root ?>public/blog/css/headers/header-v8.css">
    <link rel="stylesheet" href="<?= APP::Module('Routing')->root ?>public/blog/css/footers/footer-v8.css">

    <!-- CSS Implementing Plugins -->
    <link rel="stylesheet" href="<?= APP::Module('Routing')->root ?>public/blog/plugins/animate.css">
    <link rel="stylesheet" href="<?= APP::Module('Routing')->root ?>public/blog/plugins/line-icons/line-icons.css">
    <link rel="stylesheet" href="<?= APP::Module('Routing')->root ?>public/blog/plugins/fancybox/source/jquery.fancybox.css">
    <link rel="stylesheet" href="<?= APP::Module('Routing')->root ?>public/blog/plugins/font-awesome/css/font-awesome.min.css">
    <link rel="stylesheet" href="<?= APP::Module('Routing')->root ?>public/blog/plugins/sky-forms-pro/skyforms/css/sky-forms.css">
    <link rel="stylesheet" href="<?= APP::Module('Routing')->root ?>public/blog/plugins/sky-forms-pro/skyforms/custom/custom-sky-forms.css">
    <link rel="stylesheet" href="<?= APP::Module('Routing')->root ?>public/blog/plugins/login-signup-modal-window/css/style.css">

    <!-- CSS Theme -->
    <link rel="stylesheet" href="<?= APP::Module('Routing')->root ?>public/blog/css/theme-colors/default.css" id="style_color">
    <link rel="stylesheet" href="<?= APP::Module('Routing')->root ?>public/blog/css/theme-skins/dark.css">

    <!-- CSS Customization -->
    <link rel="stylesheet" href="<?= APP::Module('Routing')->root ?>public/blog/css/custom.css">
</head>

<body class="header-fixed header-fixed-space-v2">

<div class="wrapper">
    <?= APP::Render('blog/widgets/header/default', 'include', $data['articles']) ?>

    <!-- Container Part -->
    <div class="container content">
        <div class="row">
            <div class="col-md-9">
                <!-- Blog Grid -->
                <div class="blog-grid margin-bottom-30">
                    <h2 class="blog-grid-title-lg"><?= $data['article']['h1_title'] ?></h2>
                    <div class="overflow-h margin-bottom-10">
                        <ul class="blog-grid-info pull-left">
                            <li><i class="fa fa-calendar"></i> <?= date('F j, Y', $data['article']['up_date']) ?></li>
                        </ul>
                    </div>
                    <div class="addthis_sharing_toolbox margin-bottom-20"></div>
                    <img class="img-responsive" src="<?= APP::Module('Routing')->root . APP::Module('Blog')->uri ?>images/articles/848x536/<?= $data['article']['uri'] ?>.<?= $data['article']['image_type'] ?>">
                </div>
                <!-- End Blog Grid -->

                <div class="margin-bottom-35">
                    <?= $data['article']['html_content'] ?>
                </div>

                <div class="margin-bottom-50">
                    <?
                    APP::Render('likes/widgets/blog', 'include', [
                        'type' => APP::Module('DB')->Select(APP::Module('Likes')->settings['module_likes_db_connection'], ['fetchColumn', 0], ['id'], 'likes_objects', [['name', '=', "BlogArticle", PDO::PARAM_STR]]),
                        'id' => $data['article']['id'],
                        'text' => 'Like',
                        'class' => ['btn-u-lg'],
                        'details' => true
                    ]);
                    ?>
                </div>
                
                <!-- Blog Grid Tagds -->
                <?
                if ($data['article']['tags']) {
                    ?>
                    <ul class="blog-grid-tags">
                        <li class="head">Tags</li>
                        <?
                        foreach (explode(',', $data['article']['tags']) as $tag) {
                            ?><li><a href="<?= APP::Module('Routing')->root . APP::Module('Blog')->uri ?>tag/<?= $tag ?>"><?= $tag ?></a></li><?
                        }
                        ?>
                    </ul>
                    <?
                }
                ?>
                <!-- End Blog Grid Tagds -->
                
                <!-- Blog Thumb v4 -->
                <div class="margin-bottom-50">
                    <h2 class="title-v4">Related posts</h2>
                    <div class="row margin-bottom-50">
                        <?
                        foreach ($data['related_articles'] as $article) {
                            ?>
                            <div class="col-sm-3 col-xs-6 sm-margin-bottom-30">
                                <!-- Blog Thumb v4 -->
                                <div class="blog-thumb-v4">
                                    <img class="img-responsive margin-bottom-10" src="<?= APP::Module('Routing')->root . APP::Module('Blog')->uri ?>images/articles/189x120/<?= $article['uri'] ?>.<?= $article['image_type'] ?>">
                                    <h3><a href="<?= APP::Module('Routing')->root . APP::Module('Blog')->uri . $article['uri'] ?>"><?= $article['page_title'] ?></a></h3>
                                </div>
                                <!-- End Blog Thumb v4 -->
                            </div>
                            <?
                        }
                        ?>
                    </div><!--/end row-->
                </div>
                <!-- End Blog Thumb v4 -->
                
                <div class="margin-bottom-50">
                    <?
                    $comment_object_type = APP::Module('DB')->Select(APP::Module('Comments')->settings['module_comments_db_connection'], ['fetchColumn', 0], ['id'], 'comments_objects', [['name', '=', "BlogArticle", PDO::PARAM_STR]]);

                    APP::Render('comments/widgets/blog/list', 'include', [
                        'type' => $comment_object_type,
                        'id' => $data['article']['id'],
                        'likes' => true,
                        'class' => [
                            'holder' => 'palette-Grey-50 bg p-l-10'
                        ]
                    ]);

                    APP::Render('comments/widgets/blog/form', 'include', [
                        'type' => $comment_object_type,
                        'id' => $data['article']['id'],
                        'login' => [],
                        'class' => [
                            'holder' => false,
                            'list' => 'palette-Grey-50 bg p-l-10'
                        ]
                    ]);
                    ?>
                </div>
            </div>

            <div class="col-md-3">
                <!-- Social Shares -->
                <div class="margin-bottom-50">
                    <h2 class="title-v4">Social networks</h2>
                    <?= APP::Render('social_networks/widgets/blog') ?>
                </div>
                <!-- End Social Shares -->
                
                <!-- Tags v3 -->
                <div class="margin-bottom-50">
                    <h2 class="title-v4">Tags</h2>
                    <ul class="list-inline tags-v3">
                        <?
                        foreach ($data['random_tags'] as $tag) {
                            ?><li><a class="rounded-3x" href="<?= APP::Module('Routing')->root . APP::Module('Blog')->uri ?>tag/<?= $tag ?>"><?= $tag ?></a></li><?
                        }
                        ?>
                    </ul>
                </div>
                <!-- End Tags v3 -->
                
                <!-- Blog Thumb v2 -->
                <div class="margin-bottom-50">
                    <h2 class="title-v4">Most commented</h2>
                    <?
                    foreach ($data['most_commented'] as $item) {
                        ?>
                        <div class="blog-thumb blog-thumb-circle margin-bottom-15">
                            <div class="blog-thumb-hover">
                                <img class="rounded-x" src="<?= APP::Module('Routing')->root . APP::Module('Blog')->uri ?>images/articles/80x50/<?= $item['uri'] ?>.<?= $item['image_type'] ?>">
                                <a class="hover-grad" href="<?= APP::Module('Routing')->root . APP::Module('Blog')->uri . $item['uri'] ?>"><i class="fa fa-link"></i></a>
                            </div>
                            <div class="blog-thumb-desc">
                                <h3><a href="<?= APP::Module('Routing')->root . APP::Module('Blog')->uri . $item['uri'] ?>"><?= $item['page_title'] ?></a></h3>
                                <ul class="blog-thumb-info">
                                    <li><?= date('d.m.Y', $item['up_date']) ?></li>
                                    <li><i class="fa fa-comments"></i> <?= $item['comments'] ?></li>
                                    <li><i class="fa fa-heart"></i> <?= $item['likes'] ?></li>
                                </ul>
                            </div>
                        </div>
                        <?
                    }
                    ?>
                </div>
                <!-- End Blog Thumb v2 -->
            </div>
        </div>
    </div>
    <!--=== End Container Part ===-->

    <!--=== Footer v8 ===-->
    <?= APP::Render('blog/widgets/footer/default') ?>
    <!--=== End Footer v8 ===-->
</div><!--/wrapper-->

<? 
if (APP::Module('Users')->user['role'] == 'default') { 
    APP::Render('blog/widgets/subscribe_newsletter');
    APP::Render('blog/widgets/user_modal');
}
?>

<!-- JS Global Compulsory -->
<script src="<?= APP::Module('Routing')->root ?>public/blog/plugins/jquery/jquery.min.js"></script>
<script src="<?= APP::Module('Routing')->root ?>public/blog/plugins/jquery/jquery-migrate.min.js"></script>
<script src="<?= APP::Module('Routing')->root ?>public/blog/plugins/bootstrap/js/bootstrap.min.js"></script>

<!-- JS Implementing Plugins -->
<script src="<?= APP::Module('Routing')->root ?>public/blog/plugins/back-to-top.js"></script>
<script src="<?= APP::Module('Routing')->root ?>public/blog/plugins/smoothScroll.js"></script>
<script src="<?= APP::Module('Routing')->root ?>public/blog/plugins/counter/waypoints.min.js"></script>
<script src="<?= APP::Module('Routing')->root ?>public/blog/plugins/counter/jquery.counterup.min.js"></script>
<script src="<?= APP::Module('Routing')->root ?>public/blog/plugins/fancybox/source/jquery.fancybox.pack.js"></script>
<script src="<?= APP::Module('Routing')->root ?>public/blog/plugins/sky-forms-pro/skyforms/js/jquery.form.min.js"></script>
<script src="<?= APP::Module('Routing')->root ?>public/blog/plugins/sky-forms-pro/skyforms/js/jquery.validate.min.js"></script>
<script src="<?= APP::Module('Routing')->root ?>public/blog/plugins/modernizr.js"></script>
<script src="<?= APP::Module('Routing')->root ?>public/blog/plugins/login-signup-modal-window/js/main.js"></script>

<!-- JS Customization -->
<script src="<?= APP::Module('Routing')->root ?>public/blog/js/custom.js"></script>

<!-- JS Page Level -->
<script src="<?= APP::Module('Routing')->root ?>public/blog/js/app.js"></script>
<script src="<?= APP::Module('Routing')->root ?>public/blog/js/plugins/fancy-box.js"></script>
<script src="<?= APP::Module('Routing')->root ?>public/blog/js/plugins/style-switcher.js"></script>

<script>
jQuery(document).ready(function() {
    App.init();
    App.initCounter();
    FancyBox.initFancybox();
});
</script>

<!-- Go to www.addthis.com/dashboard to customize your tools -->
<script src="//s7.addthis.com/js/300/addthis_widget.js#pubid=ra-4f5b24645b6bf84e" async="async"></script>

<?= APP::Render('blog/widgets/js') ?>

<!--[if lt IE 9]>
<script src="<?= APP::Module('Routing')->root ?>public/blog/plugins/respond.js"></script>
<script src="<?= APP::Module('Routing')->root ?>public/blog/plugins/html5shiv.js"></script>
<script src="<?= APP::Module('Routing')->root ?>public/blog/plugins/placeholder-IE-fixes.js"></script>
<![endif]-->
</body>
</html>
