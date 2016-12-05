<!DOCTYPE html>
<!--[if IE 8]> <html lang="en" class="ie8"> <![endif]-->
<!--[if IE 9]> <html lang="en" class="ie9"> <![endif]-->
<!--[if !IE]><!--> <html lang="en"> <!--<![endif]-->
<head>
    <title>Ladonta.ru</title>

    <!-- Meta -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="">
    <meta name="keywords" content="">
    <meta name="author" content="">
    <meta name="robots" content="">

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
    <link rel="stylesheet" href="<?= APP::Module('Routing')->root ?>public/blog/plugins/owl-carousel/owl-carousel/owl.carousel.css">
    <link rel="stylesheet" href="<?= APP::Module('Routing')->root ?>public/blog/plugins/master-slider/masterslider/style/masterslider.css">
    <link rel="stylesheet" href="<?= APP::Module('Routing')->root ?>public/blog/plugins/master-slider/masterslider/skins/default/style.css">
    <link rel="stylesheet" href="<?= APP::Module('Routing')->root ?>public/blog/plugins/revolution-slider/revolution/css/settings.css">
    <link rel="stylesheet" href="<?= APP::Module('Routing')->root ?>public/blog/plugins/revolution-slider/revolution/css/layers.css">
    <link rel="stylesheet" href="<?= APP::Module('Routing')->root ?>public/blog/plugins/revolution-slider/revolution/css/navigation.css">

    <!-- CSS Theme -->
    <link rel="stylesheet" href="<?= APP::Module('Routing')->root ?>public/blog/css/theme-colors/default.css" id="style_color">
    <link rel="stylesheet" href="<?= APP::Module('Routing')->root ?>public/blog/css/theme-skins/dark.css">

    <!-- CSS Customization -->
    <link rel="stylesheet" href="<?= APP::Module('Routing')->root ?>public/blog/css/custom.css">
</head>

<body class="header-fixed header-fixed-space-v2">

<div class="wrapper">
    <?= APP::Render('blog/widgets/header/default', 'include', $data['articles']) ?>

    <!-- Promo -->
    <div class="promo-section">
        <div class="rev_slider_wrapper fullwidthbanner-container" data-alias="news-gallery34">
        <!-- START REVOLUTION SLIDER 5.0.7 fullwidth mode -->
          <div id="rev_slider_34_1" class="rev_slider fullwidthabanner" data-version="5.0.7">
            <ul>
                <?
                foreach ($data['articles'] as $key => $value) {
                    if ($key == 0) {
                        ?><li data-index="rs-<?= $value[0] ?>" data-transition="parallaxvertical" data-slotamount="default" data-easein="default" data-easeout="default" data-masterspeed="default" data-thumb="<?= APP::Module('Routing')->root . APP::Module('Blog')->uri ?>images/groups/100x50/<?= $value[0] ?>.<?= $value[2] ?>" data-rotate="0"  data-fstransition="fade" data-fsmasterspeed="1500" data-fsslotamount="7" data-saveperformance="off" data-title="<?= $value[1] ?>" data-description="<?= $value[3][0]['h1_title'] ?>"><?
                    } else {
                        ?><li data-index="rs-<?= $value[0] ?>" data-transition="parallaxvertical" data-slotamount="default" data-easein="default" data-easeout="default" data-masterspeed="default" data-thumb="<?= APP::Module('Routing')->root . APP::Module('Blog')->uri ?>images/groups/100x50/<?= $value[0] ?>.<?= $value[2] ?>" data-rotate="0"  data-saveperformance="off" data-title="<?= $value[1] ?>" data-description="<?= $value[3][0]['h1_title'] ?>"><?
                    }
                    ?>
                        <!-- MAIN IMAGE -->
                        <img src="<?= APP::Module('Routing')->root . APP::Module('Blog')->uri ?>images/groups/1920x1280/<?= $value[0] ?>.<?= $value[2] ?>" data-bgposition="center center" data-bgfit="cover" data-bgrepeat="no-repeat" data-bgparallax="10" class="rev-slidebg" data-no-retina>
                        <!-- LAYERS -->
                        <!-- LAYER NR. 1 -->
                        <div class="tp-caption tp-shape tp-shapewrapper   tp-resizeme rs-parallaxlevel-0"
                            id="slide-<?= $value[0] ?>-layer-3"
                            data-x="['center','center','center','center']" data-hoffset="['0','0','0','0']"
                            data-y="['middle','middle','middle','middle']" data-voffset="['0','0','0','0']"
                            data-width="full"
                            data-height="full"
                            data-whitespace="normal"
                            data-transform_idle="o:1;"
                            data-transform_in="opacity:0;s:1500;e:Power3.easeInOut;"
                            data-transform_out="opacity:0;s:1000;e:Power3.easeInOut;s:1000;e:Power3.easeInOut;"
                            data-start="1000"
                            data-basealign="slide"
                            data-responsive_offset="on"
                            style="z-index: 5;background-color:rgba(0, 0, 0, 0.35);border-color:rgba(0, 0, 0, 1.00);">
                        </div>
                        <!-- LAYER NR. 2 -->
                        <div class="tp-caption Newspaper-Title   tp-resizeme rs-parallaxlevel-0"
                            id="slide-<?= $value[0] ?>-layer-1"
                            data-x="['left','left','left','left']" data-hoffset="['50','50','50','30']"
                            data-y="['top','top','top','top']" data-voffset="['165','135','105','130']"
                            data-fontsize="['50','50','50','30']"
                            data-lineheight="['55','55','55','35']"
                            data-width="['600','600','600','420']"
                            data-height="none"
                            data-whitespace="normal"
                            data-transform_idle="o:1;"
                            data-transform_in="y:[-100%];z:0;rX:0deg;rY:0;rZ:0;sX:1;sY:1;skX:0;skY:0;s:1500;e:Power3.easeInOut;"
                            data-transform_out="auto:auto;s:1000;e:Power3.easeInOut;"
                            <?
                            if ($key == (count($data['articles']) - 1)) {
                                ?>
                                data-mask_in="x:0px;y:0px;"
                                data-mask_out="x:0;y:0;"
                                <?
                            } else {
                                ?>
                                data-mask_in="x:0px;y:0px;s:inherit;e:inherit;"
                                data-mask_out="x:0;y:0;s:inherit;e:inherit;"
                                <?
                            }
                            ?>
                            data-start="1000"
                            data-splitin="none"
                            data-splitout="none"
                            data-responsive_offset="on"
                            style="z-index: 6; min-width: 600px; max-width: 600px; white-space: normal;"><?= $value[3][0]['h1_title'] ?>
                        </div>
                        <!-- LAYER NR. 3 -->
                        <div class="tp-caption Newspaper-Subtitle tp-resizeme rs-parallaxlevel-0"
                            id="slide-<?= $value[0] ?>-layer-2"
                            data-x="['left','left','left','left']" data-hoffset="['50','50','50','30']"
                            data-y="['top','top','top','top']" data-voffset="['140','110','80','100']"
                            data-width="none"
                            data-height="none"
                            data-whitespace="nowrap"
                            data-transform_idle="o:1;"
                            data-transform_in="y:[-100%];z:0;rX:0deg;rY:0;rZ:0;sX:1;sY:1;skX:0;skY:0;s:1500;e:Power3.easeInOut;"
                            data-transform_out="auto:auto;s:1000;e:Power3.easeInOut;"
                            <?
                            if ($key == (count($data['articles']) - 1)) {
                                ?>
                                data-mask_in="x:0px;y:0px;"
                                data-mask_out="x:0;y:0;"
                                <?
                            } else {
                                ?>
                                data-mask_in="x:0px;y:0px;s:inherit;e:inherit;"
                                data-mask_out="x:0;y:0;s:inherit;e:inherit;"
                                <?
                            }
                            ?>
                            data-start="1000"
                            data-splitin="none"
                            data-splitout="none"
                            data-responsive_offset="on"
                            style="z-index: 7; white-space: nowrap;font-family: 'Roboto Slab'; font-size: 19px; background: #e74c3c; color: #FFFFFF; padding: 2px 4px;"><?= $value[1] ?>
                        </div>
                        <!-- LAYER NR. 4 -->
                        <a href="<?= APP::Module('Routing')->root . APP::Module('Blog')->uri . $value[3][0]['uri'] ?>" class="tp-caption Newspaper-Button rev-btn  rs-parallaxlevel-0"
                            id="slide-<?= $value[0] ?>-layer-5"
                            data-x="['left','left','left','left']" data-hoffset="['53','53','53','30']"
                            data-y="['top','top','top','top']" data-voffset="['361','331','301','245']"
                            data-width="none"
                            data-height="none"
                            data-whitespace="nowrap"
                            data-transform_idle="o:1;"
                            data-transform_hover="o:1;rX:0;rY:0;rZ:0;z:0;s:300;e:Power1.easeInOut;"
                            data-style_hover="c:rgba(0, 0, 0, 1.00);bg:rgba(255, 255, 255, 1.00);bc:rgba(255, 255, 255, 1.00);cursor:pointer;"
                            data-transform_in="y:[-100%];z:0;rX:0deg;rY:0;rZ:0;sX:1;sY:1;skX:0;skY:0;s:1500;e:Power3.easeInOut;"
                            data-transform_out="auto:auto;s:1000;e:Power3.easeInOut;"
                            <?
                            if ($key == (count($data['articles']) - 1)) {
                                ?>
                                data-mask_in="x:0px;y:0px;s:inherit;e:inherit;"
                                data-mask_out="x:0;y:0;s:inherit;e:inherit;"
                                <?
                            } else {
                                ?>
                                data-mask_in="x:0px;y:0px;"
                                data-mask_out="x:0;y:0;"
                                <?
                            }
                            ?>
                            data-start="1000"
                            data-splitin="none"
                            data-splitout="none"
                            data-responsive_offset="on"
                            data-responsive="off"
                            style="z-index: 8; white-space: nowrap;outline:none;box-shadow:none;box-sizing:border-box;-moz-box-sizing:border-box;-webkit-box-sizing:border-box;font-family: 'Roboto Slab'; font-size: 14px;">Read more
                        </a>
                    </li>
                    <?
                }
                ?>
            </ul>
            <div class="tp-bannertimer tp-bottom" style="height: 5px;"></div>
          </div>
        </div><!-- END REVOLUTION SLIDER -->
    </div>
    <!-- End Promo -->

    <!-- Container Part -->
    <div class="container content-sm">
        <div class="row">
            <div class="col-md-3">
                <!-- Social Shares -->
                <div class="margin-bottom-50">
                    <h2 class="title-v4">Social networks</h2>
                    <?= APP::Render('social_networks/widgets/blog') ?>
                </div>
                <!-- End Social Shares -->
            </div>
            <div class="col-md-9 md-margin-bottom-50">
                <!-- Tab v4 -->
                <div class="tab-v4 margin-bottom-40">
                    <!-- Tab Heading -->
                    <div class="tab-heading">
                            <h2>Last news</h2>
                            <ul id="last-groups" class="nav nav-tabs" role="tablist">
                                <?
                                foreach ($data['articles'] as $group) {
                                    ?>
                                    <li>
                                        <a href="#group-<?= $group[0] ?>" role="tab" data-toggle="tab"><?= $group[1] ?></a>
                                    </li>
                                    <?
                                }
                                ?>
                            </ul>
                    </div>
                    <!-- End Latest News -->

                    <!-- Tab Content -->
                    <div id="last-articles" class="tab-content">
                        <?
                        foreach ($data['articles'] as $value) {
                            ?>
                            <div class="tab-pane fade" id="group-<?= $value[0] ?>">
                                <div class="row">
                                    <div class="col-sm-7">
                                        <div class="blog-grid sm-margin-bottom-40">
                                            <img class="img-responsive" src="<?= APP::Module('Routing')->root . APP::Module('Blog')->uri ?>images/articles/482x305/<?= $value[3][0]['uri'] ?>.<?= $value[3][0]['image_type'] ?>">
                                            <h3><a href="<?= APP::Module('Routing')->root . APP::Module('Blog')->uri . $value[3][0]['uri'] ?>"><?= $value[3][0]['page_title'] ?></a></h3>
                                            <ul class="blog-grid-info">
                                                <li><?= date('F j, Y', $value[3][0]['up_date']) ?></li>
                                                <li><i class="fa fa-comments"></i> <?= $value[3][0]['comments'] ?></li>
                                                <li><i class="fa fa-heart"></i> <?= $value[3][0]['likes'] ?></li>
                                            </ul>
                                            <p><?= $value[3][0]['annotation'] ?></p>
                                            <a class="r-more" href="<?= APP::Module('Routing')->root . APP::Module('Blog')->uri . $value[3][0]['uri'] ?>">Read more</a>
                                        </div>
                                    </div>

                                    <div class="col-sm-5">
                                        <div class="blog-thumb margin-bottom-20">
                                            <div class="blog-thumb-hover">
                                                <img src="<?= APP::Module('Routing')->root . APP::Module('Blog')->uri ?>images/articles/120x76/<?= $value[3][1]['uri'] ?>.<?= $value[3][1]['image_type'] ?>">
                                                <a class="hover-grad" href="<?= APP::Module('Routing')->root . APP::Module('Blog')->uri . $value[3][1]['uri'] ?>"><i class="fa fa-eye"></i></a>
                                            </div>
                                            <div class="blog-thumb-desc">
                                                <h3><a href="<?= APP::Module('Routing')->root . APP::Module('Blog')->uri . $value[3][1]['uri'] ?>"><?= $value[3][1]['page_title'] ?></a></h3>
                                                <ul class="blog-thumb-info">
                                                    <li><?= date('d.m.Y', $value[3][1]['up_date']) ?></li>
                                                    <li><i class="fa fa-comments"></i> <?= $value[3][1]['comments'] ?></li>
                                                    <li><i class="fa fa-heart"></i> <?= $value[3][1]['likes'] ?></li>
                                                </ul>
                                            </div>
                                        </div>

                                        <div class="blog-thumb margin-bottom-20">
                                            <div class="blog-thumb-hover">
                                                <img src="<?= APP::Module('Routing')->root . APP::Module('Blog')->uri ?>images/articles/120x76/<?= $value[3][2]['uri'] ?>.<?= $value[3][2]['image_type'] ?>">
                                                <a class="hover-grad" href="<?= APP::Module('Routing')->root . APP::Module('Blog')->uri . $value[3][2]['uri'] ?>"><i class="fa fa-eye"></i></a>
                                            </div>
                                            <div class="blog-thumb-desc">
                                                <h3><a href="<?= APP::Module('Routing')->root . APP::Module('Blog')->uri . $value[3][2]['uri'] ?>"><?= $value[3][2]['page_title'] ?></a></h3>
                                                <ul class="blog-thumb-info">
                                                    <li><?= date('d.m.Y', $value[3][2]['up_date']) ?></li>
                                                    <li><i class="fa fa-comments"></i> <?= $value[3][2]['comments'] ?></li>
                                                    <li><i class="fa fa-heart"></i> <?= $value[3][2]['likes'] ?></li>
                                                </ul>
                                            </div>
                                        </div>

                                        <div class="blog-thumb margin-bottom-20">
                                            <div class="blog-thumb-hover">
                                                <img src="<?= APP::Module('Routing')->root . APP::Module('Blog')->uri ?>images/articles/120x76/<?= $value[3][3]['uri'] ?>.<?= $value[3][3]['image_type'] ?>">
                                                <a class="hover-grad" href="<?= APP::Module('Routing')->root . APP::Module('Blog')->uri . $value[3][3]['uri'] ?>"><i class="fa fa-eye"></i></a>
                                            </div>
                                            <div class="blog-thumb-desc">
                                                <h3><a href="<?= APP::Module('Routing')->root . APP::Module('Blog')->uri . $value[3][3]['uri'] ?>"><?= $value[3][3]['page_title'] ?></a></h3>
                                                <ul class="blog-thumb-info">
                                                    <li><?= date('d.m.Y', $value[3][3]['up_date']) ?></li>
                                                    <li><i class="fa fa-comments"></i> <?= $value[3][3]['comments'] ?></li>
                                                    <li><i class="fa fa-heart"></i> <?= $value[3][3]['likes'] ?></li>
                                                </ul>
                                            </div>
                                        </div>

                                        <div class="blog-thumb margin-bottom-20">
                                            <div class="blog-thumb-hover">
                                                <img src="<?= APP::Module('Routing')->root . APP::Module('Blog')->uri ?>images/articles/120x76/<?= $value[3][4]['uri'] ?>.<?= $value[3][4]['image_type'] ?>">
                                                <a class="hover-grad" href="<?= APP::Module('Routing')->root . APP::Module('Blog')->uri . $value[3][4]['uri'] ?>"><i class="fa fa-eye"></i></a>
                                            </div>
                                            <div class="blog-thumb-desc">
                                                <h3><a href="<?= APP::Module('Routing')->root . APP::Module('Blog')->uri . $value[3][4]['uri'] ?>"><?= $value[3][4]['page_title'] ?></a></h3>
                                                <ul class="blog-thumb-info">
                                                    <li><?= date('d.m.Y', $value[3][4]['up_date']) ?></li>
                                                    <li><i class="fa fa-comments"></i> <?= $value[3][4]['comments'] ?></li>
                                                    <li><i class="fa fa-heart"></i> <?= $value[3][4]['likes'] ?></li>
                                                </ul>
                                            </div>
                                        </div>

                                        <div class="blog-thumb margin-bottom-20">
                                            <div class="blog-thumb-hover">
                                                <img src="<?= APP::Module('Routing')->root . APP::Module('Blog')->uri ?>images/articles/120x76/<?= $value[3][5]['uri'] ?>.<?= $value[3][5]['image_type'] ?>">
                                                <a class="hover-grad" href="<?= APP::Module('Routing')->root . APP::Module('Blog')->uri . $value[3][5]['uri'] ?>"><i class="fa fa-eye"></i></a>
                                            </div>
                                            <div class="blog-thumb-desc">
                                                <h3><a href="<?= APP::Module('Routing')->root . APP::Module('Blog')->uri . $value[3][5]['uri'] ?>"><?= $value[3][5]['page_title'] ?></a></h3>
                                                <ul class="blog-thumb-info">
                                                    <li><?= date('d.m.Y', $value[3][5]['up_date']) ?></li>
                                                    <li><i class="fa fa-comments"></i> <?= $value[3][5]['comments'] ?></li>
                                                    <li><i class="fa fa-heart"></i> <?= $value[3][5]['likes'] ?></li>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                </div><!--/end row-->
                            </div>
                            <?
                        }
                        ?>
                    </div>
                    <!-- End Tab Content -->
                </div>
                <!-- End Tab v4 -->

                <!-- Blog Carousel Heading -->
                <div class="blog-cars-heading">
                    <h2>Most commented</h2>
                    <div class="owl-navigation">
                        <div class="customNavigation">
                            <a class="owl-btn prev-v3"><i class="fa fa-angle-left"></i></a>
                            <a class="owl-btn next-v3"><i class="fa fa-angle-right"></i></a>
                        </div>
                    </div><!--/navigation-->
                </div>
                <!-- End Blog Carousel Heading -->

                <!-- Blog Carousel -->
                <div class="blog-carousel margin-bottom-50">
                    <?
                    foreach ($data['most_commented'] as $article) {
                        ?>
                        <!-- Blog Grid -->
                        <div class="item">
                            <div class="row">
                                <div class="col-sm-5 sm-margin-bottom-20">
                                    <img class="img-responsive" src="<?= APP::Module('Routing')->root . APP::Module('Blog')->uri ?>images/articles/336x212/<?= $article['uri'] ?>.<?= $article['image_type'] ?>">
                                </div>
                                <div class="col-sm-7">
                                    <div class="blog-grid">
                                        <h3><a href="<?= APP::Module('Routing')->root . APP::Module('Blog')->uri . $article['uri'] ?>"><?= $article['page_title'] ?></a></h3>
                                        <ul class="blog-grid-info">
                                            <li><?= date('F j, Y', $article['up_date']) ?></li>
                                            <li><i class="fa fa-comments"></i> <?= $article['comments'] ?></li>
                                            <li><i class="fa fa-heart"></i> <?= $article['likes'] ?></li>
                                        </ul>
                                        <p><?= $article['annotation'] ?></p>
                                        <a class="r-more" href="<?= APP::Module('Routing')->root . APP::Module('Blog')->uri . $article['uri'] ?>">Read more</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- End Blog Grid -->
                        <?
                    }
                    ?>
                </div>
                <!-- End Blog Carousel -->
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
<script src="<?= APP::Module('Routing')->root ?>public/blog/plugins/owl-carousel/owl-carousel/owl.carousel.js"></script>
<script src="<?= APP::Module('Routing')->root ?>public/blog/plugins/master-slider/masterslider/masterslider.js"></script>
<script src="<?= APP::Module('Routing')->root ?>public/blog/plugins/master-slider/masterslider/jquery.easing.min.js"></script>
<script src="<?= APP::Module('Routing')->root ?>public/blog/plugins/modernizr.js"></script>
<script src="<?= APP::Module('Routing')->root ?>public/blog/plugins/login-signup-modal-window/js/main.js"></script>
<script src="<?= APP::Module('Routing')->root ?>public/blog/plugins/masonry/masonry.pkgd.min.js"></script>
<script src="<?= APP::Module('Routing')->root ?>public/blog/plugins/masonry/imagesloaded.pkgd.min.js"></script>
<script src="<?= APP::Module('Routing')->root ?>public/blog/plugins/revolution-slider/revolution/js/jquery.themepunch.tools.min.js"></script>
<script src="<?= APP::Module('Routing')->root ?>public/blog/plugins/revolution-slider/revolution/js/jquery.themepunch.revolution.min.js"></script>
<script src="<?= APP::Module('Routing')->root ?>public/blog/plugins/revolution-slider/revolution/js/extensions/revolution.extension.layeranimation.min.js"></script>
<script src="<?= APP::Module('Routing')->root ?>public/blog/plugins/revolution-slider/revolution/js/extensions/revolution.extension.navigation.min.js"></script>
<script src="<?= APP::Module('Routing')->root ?>public/blog/plugins/revolution-slider/revolution/js/extensions/revolution.extension.parallax.min.js"></script>
<script src="<?= APP::Module('Routing')->root ?>public/blog/plugins/revolution-slider/revolution/js/extensions/revolution.extension.slideanims.min.js"></script>

<!-- JS Customization -->
<script src="<?= APP::Module('Routing')->root ?>public/blog/js/custom.js"></script>

<!-- JS Page Level -->
<script src="<?= APP::Module('Routing')->root ?>public/blog/js/app.js"></script>
<script src="<?= APP::Module('Routing')->root ?>public/blog/js/plugins/fancy-box.js"></script>
<script src="<?= APP::Module('Routing')->root ?>public/blog/js/plugins/owl-carousel.js"></script>
<script src="<?= APP::Module('Routing')->root ?>public/blog/js/plugins/master-slider-showcase1.js"></script>
<script src="<?= APP::Module('Routing')->root ?>public/blog/js/plugins/style-switcher.js"></script>
<script src="<?= APP::Module('Routing')->root ?>public/blog/js/plugins/blog-masonry.js"></script>
<script src="<?= APP::Module('Routing')->root ?>public/blog/js/plugins/revo-slider.js"></script>

<script>
jQuery(document).ready(function() {
    App.init();
    App.initCounter();
    FancyBox.initFancybox();
    OwlCarousel.initOwlCarousel();
    OwlCarousel.initOwlCarousel2();
    StyleSwitcher.initStyleSwitcher();
    MasterSliderShowcase1.initMasterSliderShowcase1();

    $('#last-groups > li:first').addClass('home active');
    $('#last-articles > div:first').addClass('in active');
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
