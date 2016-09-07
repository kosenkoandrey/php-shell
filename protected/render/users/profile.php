<!DOCTYPE html>
<html lang="en-US">
<head>
    <title>Profile</title>
    <meta charset="UTF-8">
    <meta name="robots" content="none">
    <style type="text/css">
    table {
        width: 100%;
        border-collapse: collapse;
    }
    td {
        padding: 3px;
        border: 1px solid black;
        vertical-align: text-top;
    }
    </style>
    <script src="<?= APP::Module('Routing')->root ?>public/js/jquery-3.1.0.min.js"></script>
    <script>
    function Logout(link) {
        $(link).replaceWith('Processing...');
        
        $.get('<?= APP::Module('Routing')->root ?>users/api/logout.json', function() { 
            window.location.href = '<?= APP::Module('Routing')->root ?>'; 
        });
    }
    </script>
</head>
<body>
    <h1>Profile</h1>
    <table>
        <tr>
            <td>ID</td>
            <td><?= APP::Module('Users')->user['id'] ?></td>
        </tr>
        <tr>
            <td>E-Mail</td>
            <td><?= APP::Module('Users')->user['email'] ?></td>
        </tr>
        <tr>
            <td>Role</td>
            <td><?= APP::Module('Users')->user['role'] ?></td>
        </tr>
        <tr>
            <td>Registration</td>
            <td><?= APP::Module('Users')->user['reg_date'] ?></td>
        </tr>
        <tr>
            <td>Last visit</td>
            <td><?= APP::Module('Users')->user['last_visit'] ?></td>
        </tr>
    </table>
    <br>
    <a href="javascript:void(0)" onclick="return confirm('Are you sure?') ? Logout(this) : false;">Logout</a>
    
    <h2>Social networks</h2>
    <?
    if (count($data['social-profiles'])) {
        ?>
        <ul>
            <?
            foreach ($data['social-profiles'] as $profile) {
                switch ($profile['network']) {
                    case 'vk': ?><li><a href="https://vk.com/id<?= $profile['extra'] ?>" target="_blank">VK</a></li><? break;
                    case 'fb': ?><li><a href="http://facebook.com/profile.php?id=<?= $profile['extra'] ?>" target="_blank">Facebook</a></li><? break;
                    case 'google': ?><li><a href="https://plus.google.com/u/0/<?= $profile['extra'] ?>" target="_blank">Google</a></li><? break;
                    case 'ya': ?><li><a class="rounded-3x" href="http://www.yandex.ru" target="_blank">Yandex</a></li><? break;
                }
            }
            ?>
        </ul>
        <?
    } else {
        ?>
        Profiles not found
        <?
    }
    ?>
</body>
</html>