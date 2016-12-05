<!DOCTYPE html>
<!--[if IE 8]> <html lang="en" class="ie8"> <![endif]-->
<!--[if IE 9]> <html lang="en" class="ie9"> <![endif]-->
<!--[if !IE]><!--> <html lang="en"> <!--<![endif]-->
<head>
    <title><?= $data['group']['page_title'] ?></title>
    
    <? if ($data['page']['offset'] != 0) { ?><link rel="prev" href="<?= APP::Module('Routing')->root . APP::Module('Blog')->uri . $data['group']['uri'] . ($data['page']['offset'] == 1 ? '' : '?page=' . ($data['page']['offset'] - 1)) ?>"><? } ?>
    <? if (($data['page']['offset'] + 1) != $data['page']['max']) { ?><link rel="next" href="<?= APP::Module('Routing')->root . APP::Module('Blog')->uri . $data['group']['uri'] ?>?page=<?= $data['page']['offset'] + 1 ?>"><? } ?>

    <!-- Meta -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="<?= $data['group']['description'] ?>">
    <meta name="keywords" content="<?= $data['group']['keywords'] ?>">
    <meta name="robots" content="<?= $data['page']['offset'] ? 'noindex, follow' : $data['group']['robots'] ?>">

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

    <!--=== Breadcrumbs ===-->
    <div class="breadcrumbs breadcrumbs-light">
        <div class="container">
            <h1 class="pull-left"><?= $data['group']['h1_title'] ?></h1>
        </div>
    </div><!--/breadcrumbs-->
    <!--=== End Breadcrumbs ===-->

    <!--=== Container Part ===-->
    <div class="container content-sm">
        <div class="row">
            <div class="col-md-9 md-margin-bottom-50">
                <!-- Blog Grid -->
                <?
                if (count($data['group_articles'])) {
                    foreach ($data['group_articles'] as $item) { 
                        ?>
                        <div class="blog-grid margin-bottom-50">
                            <img class="img-responsive" src="<?= APP::Module('Routing')->root . APP::Module('Blog')->uri ?>images/articles/848x536/<?= $item['uri'] ?>.<?= $item['image_type'] ?>">
                            <div class="blog-grid-inner">
                                <h3><a href="<?= APP::Module('Routing')->root . APP::Module('Blog')->uri . $item['uri'] ?>"><?= $item['page_title'] ?></a></h3>
                                <ul class="blog-grid-info">
                                    <li><?= date('d.m.Y', $item['up_date']) ?></li>
                                    <li><i class="fa fa-comments"></i> <?= $item['comments'] ?></li>
                                    <li><i class="fa fa-heart"></i> <?= $item['likes'] ?></li>
                                </ul>
                                <p><?= $item['annotation'] ?></p>
                                <a class="r-more" href="<?= APP::Module('Routing')->root . APP::Module('Blog')->uri . $item['uri'] ?>">Read more</a>
                            </div>
                        </div>
                        <?
                    }
                    ?>
                    <ul class="pager pager-v4">
                        <? if ($data['page']['offset'] != 0) { ?><li class="previous"><a class="rounded-3x" href="<?= APP::Module('Routing')->root . APP::Module('Blog')->uri . $data['group']['uri'] . ($data['page']['offset'] == 1 ? '' : '?page=' . ($data['page']['offset'] - 1)) ?>">&larr; Older</a></li><? } ?>
                        <li class="page-amount"><?= $data['page']['offset'] + 1 ?> of <?= $data['page']['max'] ?></li>
                        <? if (($data['page']['offset'] + 1) != $data['page']['max']) { ?><li class="next"><a class="rounded-3x" href="<?= APP::Module('Routing')->root . APP::Module('Blog')->uri . $data['group']['uri'] ?>?page=<?= $data['page']['offset'] + 1 ?>">Newer &rarr;</a></li><? } ?>
                    </ul>
                    <?
                } else {
                    ?>
                    <div class="alert alert-warning">No data to display</div>
                    <?
                }
                ?>
                <!-- End Blog Grid -->
            </div>

            <div class="col-md-3">
                <!-- Social Shares -->
                <div class="margin-bottom-50">
                    <h2 class="title-v4">Social networks</h2>
                    <?= APP::Render('social_networks/widgets/blog') ?>
                </div>
                <!-- End Social Shares -->

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
        </div><!--/end row-->
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
<script src="<?= APP::Module('Routing')->root ?>public/blog/plugins/modernizr.js"></script>
<script src="<?= APP::Module('Routing')->root ?>public/blog/plugins/login-signup-modal-window/js/main.js"></script>

<!-- JS Customization -->
<script src="<?= APP::Module('Routing')->root ?>public/blog/js/custom.js"></script>

<!-- JS Page Level -->
<script src="<?= APP::Module('Routing')->root ?>public/blog/js/app.js"></script>

<script>
jQuery(document).ready(function() {
    App.init();
    App.initCounter();
});
</script>

<?= APP::Render('blog/widgets/js') ?>

<!--[if lt IE 9]>
<script src="<?= APP::Module('Routing')->root ?>public/blog/plugins/respond.js"></script>
<script src="<?= APP::Module('Routing')->root ?>public/blog/plugins/html5shiv.js"></script>
<script src="<?= APP::Module('Routing')->root ?>public/blog/plugins/placeholder-IE-fixes.js"></script>
<![endif]-->
</body>
</html>
