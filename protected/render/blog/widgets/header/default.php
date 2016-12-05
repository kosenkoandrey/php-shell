<!--=== Header v8 ===-->
<div class="header-v8 header-sticky">
    <!-- Topbar blog -->
    <div class="blog-topbar">
        <div class="topbar-search-block">
            <div class="container">
                <form action="">
                    <input type="text" class="form-control" placeholder="Search">
                    <div class="search-close"><i class="icon-close"></i></div>
                </form>
            </div>
        </div>
        <div class="container">
            <div class="row">
                <div class="col-sm-8 col-xs-8">
                    <div class="topbar-time"><?= strftime("%a, %e %b %Y") ?></div>
                    <div class="topbar-toggler"><span class="fa fa-angle-down"></span></div>
                    <ul class="topbar-list topbar-menu">
                        <li><a href="<?= APP::Module('Routing')->root . APP::Module('Blog')->uri ?>about">About</a></li>
                        <li><a href="<?= APP::Module('Routing')->root . APP::Module('Blog')->uri ?>contacts">Contacts</a></li>
                        <?
                        switch (APP::Module('Users')->user['role']) {
                            case 'default':
                                ?>
                                <li class="cd-log_reg hidden-sm hidden-md hidden-lg"><strong><a class="cd-signin" href="javascript:void(0);">Login</a></strong></li>
                                <li class="cd-log_reg hidden-sm hidden-md hidden-lg"><strong><a class="cd-signup" href="javascript:void(0);">Register</a></strong></li>
                                <?
                                break;
                            default:
                                ?>
                                <li class="hidden-sm hidden-md hidden-lg">
                                    <a href="javascript:void(0);"><?= APP::Module('Users')->user['email'] ?></a>
                                    <ul class="topbar-dropdown">
                                        <li><a href="<?= APP::Module('Routing')->root ?>users/profile">Profile</a></li>
                                        <li><a href="<?= APP::Module('Routing')->root ?>users/logout">Logout</a></li>
                                    </ul>
                                </li>
                                <?
                                break;
                        }
                        ?>
                    </ul>
                </div>
                <div class="col-sm-4 col-xs-4 clearfix">
                    <!-- <i class="fa fa-search search-btn pull-right"></i> -->
                    <ul class="topbar-list topbar-menu topbar-log_reg pull-right visible-sm-block visible-md-block visible-lg-block">
                        <?
                        switch (APP::Module('Users')->user['role']) {
                            case 'default':
                                ?>
                                <li class="cd-log_reg home"><a class="cd-signin" href="javascript:void(0);">Login</a></li>
                                <li class="cd-log_reg"><a class="cd-signup" href="javascript:void(0);">Register</a></li>
                                <?
                                break;
                            default:
                                ?>
                                <li class="home">
                                    <a href="javascript:void(0);"><?= APP::Module('Users')->user['email'] ?></a>
                                    <ul class="topbar-dropdown">
                                        <li><a href="<?= APP::Module('Routing')->root ?>users/profile">Profile</a></li>
                                        <li><a href="<?= APP::Module('Routing')->root ?>users/logout">Logout</a></li>
                                    </ul>
                                </li>
                                <?
                                break;
                        }
                        ?>
                    </ul>
                </div>
            </div><!--/end row-->
        </div><!--/end container-->
    </div>
    <!-- End Topbar blog -->

    <!-- Navbar -->
    <div class="navbar mega-menu" role="navigation">
        <div class="container">
            <!-- Brand and toggle get grouped for better mobile display -->
            <div class="res-container">
                <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-responsive-collapse">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>

                <div class="navbar-brand">
                    <a href="<?= APP::Module('Routing')->root . Blog::URI ?>">
                        <img src="<?= APP::Module('Routing')->root ?>public/blog/img/logo3-dark.png">
                    </a>
                </div>
            </div><!--/end responsive container-->

            <!-- Collect the nav links, forms, and other content for toggling -->
            <div class="collapse navbar-collapse navbar-responsive-collapse">
                <div class="res-container">
                    <ul class="nav navbar-nav">
                        <li class="blog-nav"><a class="blog-nav-item" href="<?= APP::Module('Routing')->root . Blog::URI ?>">Main</a></li>
                        <?
                        foreach ($data as $key => $value) {
                            ?>
                            <li class="dropdown mega-menu-fullwidth">
                                <a href="<?= APP::Module('Routing')->root . APP::Module('Blog')->uri . $value[0] ?>"><?= $value[1] ?></a>
                                <ul class="dropdown-menu">
                                    <li>
                                        <div class="mega-menu-content">
                                            <div class="container">
                                                <div class="row">
                                                    <div class="col-md-4 md-margin-bottom-30 blog-thumb">
                                                        <div class="blog-grid">
                                                            <img class="img-responsive" src="<?= APP::Module('Routing')->root . APP::Module('Blog')->uri ?>images/articles/350x221/<?= $value[3][0]['uri'] ?>.<?= $value[3][0]['image_type'] ?>">
                                                            <h3 class="blog-grid-title-sm"><a href="<?= APP::Module('Routing')->root . APP::Module('Blog')->uri . $value[3][0]['uri'] ?>"><?= $value[3][0]['page_title'] ?></a></h3>
                                                        </div>
                                                        <div class="blog-thumb-desc">
                                                            <ul class="blog-thumb-info">
                                                                <li><?= date('d.m.Y', $value[3][0]['up_date']) ?></li>
                                                                <li><i class="fa fa-comments"></i> <?= $value[3][0]['comments'] ?></li>
                                                                <li><i class="fa fa-heart"></i> <?= $value[3][0]['likes'] ?></li>
                                                            </ul>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-4 md-margin-bottom-30">
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
                                                    </div>
                                                    <div class="col-md-4">
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

                                                        <div class="blog-thumb margin-bottom-20">
                                                            <div class="blog-thumb-hover">
                                                                <img src="<?= APP::Module('Routing')->root . APP::Module('Blog')->uri ?>images/articles/120x76/<?= $value[3][6]['uri'] ?>.<?= $value[3][6]['image_type'] ?>">
                                                                <a class="hover-grad" href="<?= APP::Module('Routing')->root . APP::Module('Blog')->uri . $value[3][6]['uri'] ?>"><i class="fa fa-eye"></i></a>
                                                            </div>
                                                            <div class="blog-thumb-desc">
                                                                <h3><a href="<?= APP::Module('Routing')->root . APP::Module('Blog')->uri . $value[3][6]['uri'] ?>"><?= $value[3][6]['page_title'] ?></a></h3>
                                                                <ul class="blog-thumb-info">
                                                                    <li><?= date('d.m.Y', $value[3][6]['up_date']) ?></li>
                                                                    <li><i class="fa fa-comments"></i> <?= $value[3][6]['comments'] ?></li>
                                                                    <li><i class="fa fa-heart"></i> <?= $value[3][6]['likes'] ?></li>
                                                                </ul>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </li>
                                </ul>
                            </li>
                            <?
                        }
                        ?>
                    </ul>
                </div><!--/responsive container-->
            </div><!--/navbar-collapse-->
        </div><!--/end contaoner-->
    </div>
    <!-- End Navbar -->
</div>
<!--=== End Header v8 ===-->