<!DOCTYPE html>
<!--[if IE 8]> <html lang="en" class="ie8"> <![endif]-->
<!--[if IE 9]> <html lang="en" class="ie9"> <![endif]-->
<!--[if !IE]><!--> <html lang="en"> <!--<![endif]-->
<head>
    <title>Privacy and Policy</title>

    <!-- Meta -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Privacy and Policy">
    <meta name="robots" content="all">

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
            <h1 class="pull-left">Privacy and Policy</h1>
        </div>
    </div><!--/breadcrumbs-->
    <!--=== End Breadcrumbs ===-->

    <!--=== Container Part ===-->
    <div class="container content-sm">
        <div class="row">
            <div class="col-md-12 md-margin-bottom-50">
                Write you text here
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
<script src="<?= APP::Module('Routing')->root ?>public/blog/plugins/modernizr.js"></script>
<script src="<?= APP::Module('Routing')->root ?>public/blog/plugins/login-signup-modal-window/js/main.js"></script>

<!-- JS Customization -->
<script src="<?= APP::Module('Routing')->root ?>public/blog/js/custom.js"></script>

<!-- JS Page Level -->
<script src="<?= APP::Module('Routing')->root ?>public/blog/js/app.js"></script>

<script>
jQuery(document).ready(function() {
    App.init();
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
