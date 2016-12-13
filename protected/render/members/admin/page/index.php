<?
$nav = [];
$nav_cnt = 0;

foreach ($data['path'] as $key => $value) {
    ++ $nav_cnt;
    $title = $key ? $value : 'Pages';
    
    if (count($data['path']) !== $nav_cnt) {
        $nav[$title] = 'admin/members/pages/' . APP::Module('Crypt')->Encode($key);
    } else {
        $nav[$title] = mb_substr(APP::Module('Routing')->RequestURI(), 1);
    }
}
?>
<!DOCTYPE html>
<!--[if IE 9 ]><html class="ie9"><![endif]-->
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>PHP-shell - Pages</title>

        <!-- Vendor CSS -->
        <link href="<?= APP::Module('Routing')->root ?>public/ui/vendors/bower_components/animate.css/animate.min.css" rel="stylesheet">
        <link href="<?= APP::Module('Routing')->root ?>public/ui/vendors/bower_components/material-design-iconic-font/dist/css/material-design-iconic-font.min.css" rel="stylesheet">
        <link href="<?= APP::Module('Routing')->root ?>public/ui/vendors/bower_components/malihu-custom-scrollbar-plugin/jquery.mCustomScrollbar.min.css" rel="stylesheet">        
        <link href="<?= APP::Module('Routing')->root ?>public/ui/vendors/bower_components/google-material-color/dist/palette.css" rel="stylesheet">
        <link href="<?= APP::Module('Routing')->root ?>public/ui/vendors/bower_components/bootstrap-sweetalert/lib/sweet-alert.css" rel="stylesheet">
        <link href="<?= APP::Module('Routing')->root ?>public/ui/vendors/bootgrid/jquery.bootgrid.min.css" rel="stylesheet">

        <? APP::Render('core/widgets/css') ?>
    </head>
    <body data-ma-header="teal">
        <? APP::Render('admin/widgets/header', 'include', $nav) ?>

        <section id="main">
            <? APP::Render('admin/widgets/sidebar') ?>

            <section id="content">
                <div class="container">
                    <div class="card">
                        <div class="card-header">
                            <h2>Manage pages</h2>
                            <ul class="actions">
                                <li class="dropdown">
                                    <a href="javascript:void(0)" data-toggle="dropdown">
                                        <i class="zmdi zmdi-more-vert"></i>
                                    </a>
                                    <ul class="dropdown-menu dropdown-menu-right">
                                        <li><a href="<?= APP::Module('Routing')->root ?>admin/members/pages/<?= $data['group_sub_id'] ? APP::Module('Crypt')->Encode($data['group_sub_id']) : 0 ?>/add">Add page</a></li>
                                        <li><a href="<?= APP::Module('Routing')->root ?>admin/members/pages/<?= $data['group_sub_id'] ? APP::Module('Crypt')->Encode($data['group_sub_id']) : 0 ?>/groups/add">Add group</a></li>
                                    </ul>
                                </li>
                            </ul>
                        </div>
                        <div class="card-body">
                            <?
                            if (count($data['list'])) {
                                ?>
                                <table class="table table-hover table-vmiddle">
                                    <tbody>
                                        <?
                                        foreach ($data['list'] as $item) {
                                            switch ($item[0]) {
                                                case 'group':
                                                    ?>
                                                    <tr>
                                                        <td style="font-size: 16px"><span style="display: inline-block" class="avatar-char palette-Teal bg m-r-5"><i class="zmdi zmdi-folder"></i></span> <a style="color: #4C4C4C" href="<?= APP::Module('Routing')->root ?>admin/members/pages/<?= APP::Module('Crypt')->Encode($item[1]) ?>"><?= $item[2] ?></a></td>
                                                        <td>
                                                            <a href="<?= APP::Module('Routing')->root ?>admin/members/pages/<?= $data['group_sub_id'] ? APP::Module('Crypt')->Encode($data['group_sub_id']) : 0 ?>/groups/<?= APP::Module('Crypt')->Encode($item[1]) ?>/edit" class="btn btn-sm btn-default btn-icon waves-effect waves-circle"><span class="zmdi zmdi-edit"></span></a>
                                                            <a href="javascript:void(0)" data-page-group-id="<?= $item[1] ?>" class="btn btn-sm btn-default btn-icon waves-effect waves-circle remove-page-group"><span class="zmdi zmdi-delete"></span></a>
                                                        </td>
                                                    </tr>
                                                    <?
                                                    break;
                                                case 'page':
                                                    ?>
                                                    <tr>
                                                        <td style="font-size: 16px;"><span style="display: inline-block" class="avatar-char palette-Orange-400 bg m-r-5"><i class="zmdi zmdi-file"></i></span> <a style="color: #4C4C4C" href="<?= APP::Module('Routing')->root ?>admin/members/pages/<?= $data['group_sub_id'] ? APP::Module('Crypt')->Encode($data['group_sub_id']) : 0 ?>/preview/<?= APP::Module('Crypt')->Encode($item[1]) ?>" target="_blank"><?= $item[2] ?></a></td>
                                                        <td>
                                                            <a href="<?= APP::Module('Routing')->root ?>admin/members/pages/<?= $data['group_sub_id'] ? APP::Module('Crypt')->Encode($data['group_sub_id']) : 0 ?>/edit/<?= APP::Module('Crypt')->Encode($item[1]) ?>" class="btn btn-sm btn-default btn-icon waves-effect waves-circle"><span class="zmdi zmdi-edit"></span></a>
                                                            <a href="javascript:void(0)" data-page-id="<?= $item[1] ?>" class="btn btn-sm btn-default btn-icon waves-effect waves-circle remove-page"><span class="zmdi zmdi-delete"></span></a>
                                                        </td>
                                                    </tr>
                                                    <?
                                                    break;
                                            }
                                        }
                                        ?>
                                    </tbody>
                                </table>
                                <?
                            } else {
                                ?>
                                <div class="card-body card-padding">
                                    <div class="alert alert-warning" role="alert"><i class="zmdi zmdi-close-circle"></i> Pages not found</div>
                                </div>
                                <?
                            }
                            ?>
                        </div>
                    </div>
                </div>
            </section>

            <? APP::Render('admin/widgets/footer') ?>
        </section>

        <? APP::Render('core/widgets/page_loader') ?>
        <? APP::Render('core/widgets/ie_warning') ?>

        <!-- Javascript Libraries -->
        <script src="<?= APP::Module('Routing')->root ?>public/ui/vendors/bower_components/jquery/dist/jquery.min.js"></script>
        <script src="<?= APP::Module('Routing')->root ?>public/ui/vendors/bower_components/bootstrap/dist/js/bootstrap.min.js"></script>
        <script src="<?= APP::Module('Routing')->root ?>public/ui/vendors/bower_components/malihu-custom-scrollbar-plugin/jquery.mCustomScrollbar.concat.min.js"></script>
        <script src="<?= APP::Module('Routing')->root ?>public/ui/vendors/bower_components/Waves/dist/waves.min.js"></script>
        <script src="<?= APP::Module('Routing')->root ?>public/ui/vendors/bower_components/bootstrap-sweetalert/lib/sweet-alert.min.js"></script>
        <script src="<?= APP::Module('Routing')->root ?>public/ui/vendors/bootgrid/jquery.bootgrid.updated.min.js"></script>

        <? APP::Render('core/widgets/js') ?>
        
        <script>
        $('body').on('click', '.remove-page', function() {
            var page_id = $(this).data('page-id');

            swal({
                title: 'Are you sure?',
                text: 'You will not be able to recover this page',
                type: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#DD6B55',
                confirmButtonText: 'Yes',
                cancelButtonText: 'No',
                closeOnConfirm: false,
                closeOnCancel: true
            }, function(isConfirm){
                if (isConfirm) {
                    $.post('<?= APP::Module('Routing')->root ?>admin/members/api/pages/remove.json', {
                        id: page_id
                    }, function() { 
                        swal({
                            title: 'Done!',
                            text: 'Page #' + page_id + ' has been removed',
                            type: 'success',
                            showCancelButton: false,
                            confirmButtonText: 'Ok',
                            closeOnConfirm: false
                        }, function(){
                            window.location.href = '<?= APP::Module('Routing')->root ?>admin/members/pages/<?= $data['group_sub_id'] ? APP::Module('Crypt')->Encode($data['group_sub_id']) : 0 ?>';
                        });
                    });
                }
            });
        });
        
        $('body').on('click', '.remove-page-group', function() {
            var page_group_id = $(this).data('page-group-id');

            swal({
                title: 'Are you sure?',
                text: 'You will not be able to recover this group',
                type: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#DD6B55',
                confirmButtonText: 'Yes',
                cancelButtonText: 'No',
                closeOnConfirm: false,
                closeOnCancel: true
            }, function(isConfirm){
                if (isConfirm) {
                    $.post('<?= APP::Module('Routing')->root ?>admin/members/api/pages/groups/remove.json', {
                        id: page_group_id
                    }, function() { 
                        swal({
                            title: 'Done!',
                            text: 'Group #' + page_group_id + ' has been removed',
                            type: 'success',
                            showCancelButton: false,
                            confirmButtonText: 'Ok',
                            closeOnConfirm: false
                        }, function(){
                            window.location.href = '<?= APP::Module('Routing')->root ?>admin/members/pages/<?= $data['group_sub_id'] ? APP::Module('Crypt')->Encode($data['group_sub_id']) : 0 ?>'; 
                        });
                    });
                }
            });
        });
        </script>
    </body>
</html>