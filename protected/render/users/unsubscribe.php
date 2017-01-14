<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <title>Unsubscribe</title>
</head>
<body>
    <?
    if ($data['error']) {
        ?>
        <h1>Неверная ссылка для отписки</h1>
        <?
    } else {
        if ($data['active']) {
            ?>
            <h1>Вы хотите отписаться?</h1>
            <div id="content">
                <input type="radio" name="action" value="timeout" checked> Приостановить рассылку на </span>
                <select class="form-control" name="timeout">
                    <option value="+1 week">1</option>
                    <option value="+2 week">2</option>
                    <option value="+3 week">3</option>
                    <option value="+4 week">4</option>
                </select> недель. Вы не будете получать рассылки в течении этого времени.
                <br>
                <input type="radio" name="action" value="unsubscribe"> Да, отписаться
                <br><br>
                <button id="submit">Продолжить</button>
            </div>
            <?
        } else {
            ?>
            <h1>Вы успешно отписались от рассылки</h1>
            <?
        }
    }
    ?>
    <script src="https://code.jquery.com/jquery-3.1.1.min.js"></script>
    <script>
    $(document).ready(function() {
        $(document).on('click', '#submit', function() {
            switch ($('input[name="action"]:checked').val()) {
                case 'timeout':
                    $.post('<?= APP::Module('Routing')->root ?>users/api/pause.json', {
                        'mail_log': '<?= APP::Module('Routing')->get['mail_log_hash'] ?>',
                        'timeout': $('select[name="timeout"]').val()
                    }, function(){
                        $('h1').html('Вы успешно приостановили рассылку');
                        $('#content').remove();
                    });
                    break;
                case 'unsubscribe':
                    $.post('<?= APP::Module('Routing')->root ?>users/api/unsubscribe.json', {
                        'mail_log': '<?= APP::Module('Routing')->get['mail_log_hash'] ?>'
                    }, function(){
                        $('h1').html('Вы успешно отписались от рассылки');
                        $('#content').remove();
                    });
                    break;
            }
        });
    });
    </script>
</body>
</html>