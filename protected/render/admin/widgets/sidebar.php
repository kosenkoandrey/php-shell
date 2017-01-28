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
                        <th>ФС</th>
                        <th>Размер</th>
                        <th>Исп.</th>
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
                        <td>Всего</td>
                        <td><?= APP::Module('Utils')->SizeConvert($system[2][1][1] * 1024) ?></td>
                    </tr>
                    <tr>
                        <td>Использовано</td>
                        <td><?= APP::Module('Utils')->SizeConvert($system[2][1][2] * 1024) ?></td>
                    </tr>
                    <tr>
                        <td>Свободно</td>
                        <td><?= APP::Module('Utils')->SizeConvert($system[2][1][3] * 1024) ?></td>
                    </tr>
                    <tr>
                        <td>Расшарено</td>
                        <td><?= APP::Module('Utils')->SizeConvert($system[2][1][4] * 1024) ?></td>
                    </tr>
                    <tr>
                        <td>Буффер</td>
                        <td><?= APP::Module('Utils')->SizeConvert($system[2][1][5] * 1024) ?></td>
                    </tr>
                    <tr>
                        <td>Кэшировано</td>
                        <td><?= APP::Module('Utils')->SizeConvert($system[2][1][6] * 1024) ?></td>
                    </tr>
                    <tr>
                        <td>Буффер/кэш</td>
                        <td><?= APP::Module('Utils')->SizeConvert($system[2][2][2] * 1024) ?> / <?= APP::Module('Utils')->SizeConvert($system[2][2][3] * 1024) ?></td>
                    </tr>
                    <tr>
                        <td>Своп</td>
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
        <li class="sub-menu">
            <a href="" data-ma-action="submenu-toggle"><i class="zmdi zmdi-caret-right"></i> Система</a>
            <ul>
                <li><a href="<?= APP::Module('Routing')->root ?>admin/app">Конфигурация</a></li>
                <li><a href="<?= APP::Module('Routing')->root ?>admin/modules">Модули</a></li>
            </ul>
        </li>
        <?
        foreach ($modules as $key => $value) {
            ?>
            <li class="sub-menu">
                <a href="" data-ma-action="submenu-toggle"><i class="zmdi zmdi-caret-right"></i> <? 
                    switch ($key) {
                        case 'Analytics': echo 'Аналитика'; break;
                        case 'Billing': echo 'Биллинг'; break;
                        case 'Blog': echo 'Блог'; break;
                        case 'Cache': echo 'Кэш'; break;
                        case 'Comments': echo 'Комментарии'; break;
                        case 'Costs': echo 'Расходы'; break;
                        case 'Cron': echo 'Управление Cron'; break;
                        case 'Crypt': echo 'Шифрование'; break;
                        case 'Files': echo 'Файлы'; break;
                        case 'HotOrNot': echo 'Hot or not'; break;
                        case 'Likes': echo 'Оценки'; break;
                        case 'Logs': echo 'Журналы'; break;
                        case 'Mail': echo 'Почта'; break;
                        case 'Members': echo 'Мемберка'; break;
                        case 'Rating': echo 'Рейтинг'; break;
                        case 'Sessions': echo 'Сессии'; break;
                        case 'SocialNetworks': echo 'Социальные сети'; break;
                        case 'SSH': echo 'SSH соединения'; break;
                        case 'TaskManager': echo 'Менеджер задач'; break;
                        case 'Triggers': echo 'Триггеры'; break;
                        case 'Tunnels': echo 'Туннели'; break;
                        case 'Users': echo 'Пользователи'; break;
                        case 'Quiz': echo 'Викторина'; break;
                        case 'Groups': echo 'Группы'; break;
                        default: echo $key; break;
                    }
                ?></a>
                <ul>
                    <?= $value ?>
                </ul>
            </li>
            <?
        }
        ?>
    </ul>
</aside>