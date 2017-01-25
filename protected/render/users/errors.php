<?
// Error code
switch ($data) {
    case 'auth_vk_email':               $info = ['VK не вернул E-Mail адрес']; break;
    case 'auth_vk_user_id':             $info = ['No [userid] parameter in the response from VK']; break;
    case 'auth_vk_code':                $info = ['No [code] parameter in the response from VK']; break;
    
    case 'auth_fb_email':               $info = ['Facebook не вернул E-Mail адрес']; break;
    case 'auth_fb_id':                  $info = ['No [id] parameter in the response from Facebook']; break;
    case 'auth_fb_access_token':        $info = ['No [access_token] parameter in the response from Facebook']; break;
    case 'auth_fb_code':                $info = ['No [code] parameter in the response from Facebook']; break;
    
    case 'auth_google_email':           $info = ['Google не вернул E-Mail адрес']; break;
    case 'auth_google_id':              $info = ['No [id] parameter in the response from Google']; break;
    case 'auth_google_access_token':    $info = ['No [access_token] parameter in the response from Google']; break;
    case 'auth_google_code':            $info = ['No [code] parameter in the response from Google']; break;
    
    case 'auth_ya_email':               $info = ['Yandex не вернул E-Mail адрес']; break;
    case 'auth_ya_id':                  $info = ['No [id] parameter in the response from Yandex']; break;
    case 'auth_ya_access_token':        $info = ['No [access_token] parameter in the response from Yandex']; break;
    case 'auth_ya_code':                $info = ['No [code] parameter in the response from Yandex']; break;
    
    case 'auth_service':                $info = ['Сервис отключен администрацией']; break;
    
    default:                            $info = ['Неизвестная ошибка']; break;
}
?>
<!DOCTYPE html>
<html lang="ru-RU">
<head>
    <title>Ошибка</title>
    <meta charset="UTF-8">
    <meta name="robots" content="none">
</head>	
<body>
    <h1><?= $info[0] ?></h1>
    <?= isset($info[1]) ? $info[1] : '' ?>
</body>
</html>
<?
exit;