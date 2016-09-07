<?
$system = APP::Module('Admin')->System();
$modules = [];
        
foreach (APP::$modules as $key => $value) {
    if (method_exists($value, 'Admin')) {
        $modules[$key] = $value->Admin();
    }
}
?>
<aside id="s-user-alerts" class="sidebar">
    <ul class="tab-nav tn-justified tn-icon m-t-10" data-tab-color="teal">
        <li><a class="system-cpu" href="#system-cpu" data-toggle="tab"><i class="zmdi zmdi-desktop-windows"></i></a></li>
        <li><a class="system-hdd" href="#system-hdd" data-toggle="tab"><i class="zmdi zmdi-dns"></i></a></li>
        <li><a class="system-memory" href="#system-memory" data-toggle="tab"><i class="zmdi zmdi-card-sd"></i></a></li>
    </ul>

    <div class="tab-content">
        <div class="tab-pane fade" id="system-cpu">
            <table class="table">
                <tbody>
                    <tr>
                        <td>LA</td>
                        <td><?= implode(' / ', $system[0]) ?></td>
                    </tr>
                </tbody>
            </table>
        </div>
        <div class="tab-pane fade" id="system-hdd">
            <table class="table">
                <thead>
                    <tr>
                        <th>Filesystem</th>
                        <th>Size</th>
                        <th>Used</th>
                    </tr>
                </thead>
                <tbody>
                    <?
                    foreach ($system[1] as $key => $value) {
                        if ($key === 0) continue;
                        ?>
                        <tr>
                            <td><?= $value[0] ?></td>
                            <td><?= $value[1] ?></td>
                            <td><?= $value[2] ?></td>
                        </tr>
                        <?
                    }
                    ?>
                </tbody>
            </table>
        </div>
        <div class="tab-pane fade" id="system-memory">
            <table class="table">
                <tbody>
                    <tr>
                        <td>Total</td>
                        <td><?= APP::Module('Utils')->SizeConvert($system[2][1][1] * 1024) ?></td>
                    </tr>
                    <tr>
                        <td>Used</td>
                        <td><?= APP::Module('Utils')->SizeConvert($system[2][1][2] * 1024) ?></td>
                    </tr>
                    <tr>
                        <td>Free</td>
                        <td><?= APP::Module('Utils')->SizeConvert($system[2][1][3] * 1024) ?></td>
                    </tr>
                    <tr>
                        <td>Shared</td>
                        <td><?= APP::Module('Utils')->SizeConvert($system[2][1][4] * 1024) ?></td>
                    </tr>
                    <tr>
                        <td>Buffers</td>
                        <td><?= APP::Module('Utils')->SizeConvert($system[2][1][5] * 1024) ?></td>
                    </tr>
                    <tr>
                        <td>Cached</td>
                        <td><?= APP::Module('Utils')->SizeConvert($system[2][1][6] * 1024) ?></td>
                    </tr>
                    <tr>
                        <td>Buffers/cache</td>
                        <td><?= APP::Module('Utils')->SizeConvert($system[2][2][2] * 1024) ?> / <?= APP::Module('Utils')->SizeConvert($system[2][2][3] * 1024) ?></td>
                    </tr>
                    <tr>
                        <td>Swap</td>
                        <td><?= APP::Module('Utils')->SizeConvert($system[2][3][1] * 1024) ?> / <?= APP::Module('Utils')->SizeConvert($system[2][3][2] * 1024) ?></td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</aside>

<aside id="s-main-menu" class="sidebar">
    <div class="smm-header">
        <i class="zmdi zmdi-long-arrow-left" data-ma-action="sidebar-close"></i>
    </div>

    <ul class="smm-alerts">
        <li data-user-alert="system-cpu" data-ma-action="sidebar-open" data-ma-target="user-alerts">
            <i class="zmdi zmdi-desktop-windows"></i>
        </li>
        <li data-user-alert="system-hdd" data-ma-action="sidebar-open" data-ma-target="user-alerts">
            <i class="zmdi zmdi-dns"></i>
        </li>
        <li data-user-alert="system-memory" data-ma-action="sidebar-open" data-ma-target="user-alerts">
            <i class="zmdi zmdi-card-sd"></i>
        </li>
    </ul>

    <ul class="main-menu">
        <?
        foreach ($modules as $key => $value) {
            ?>
            <li class="sub-menu">
                <a href="" data-ma-action="submenu-toggle"><i class="zmdi zmdi-caret-right"></i> <?= $key ?></a>
                <ul>
                    <?= $value ?>
                </ul>
            </li>
            <?
        }
        ?>
    </ul>
</aside>