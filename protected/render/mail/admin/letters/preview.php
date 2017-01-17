<div id="settings" style="background-color: #f5f5f5; padding: 30px">
    <form method="get">
        Пользователь <input type="text" name="user_id" value="<?= $data['user_id'] ?>"> 
        <?
        if ($data['user_id']) {
            ?>
            Туннель 
            <select name="user_tunnel_id">
                <option value="0">Туннель не выбран</option>
                <?
                foreach ($data['user_tunnels'] as $user_tunnel) {
                    ?><option value="<?= $user_tunnel['id'] ?>" <? if ($user_tunnel['id'] == $data['user_tunnel_id']) { ?>selected<? } ?>>[<?= $data['tunnels'][$user_tunnel['tunnel_id']]['type'] ?>] [<?= $user_tunnel['state'] ?>] <?= $data['tunnels'][$user_tunnel['tunnel_id']]['name'] ?></option><?
                }
                ?>
            </select>    
            <?
        }
        ?>
            <input type="submit" value="Применить">
    </form>
</div>
<h3>HTML-version</h3>
<?= $data['letter']['html'] ?>
<hr>
<h3>Plaintext-version</h3>
<div style="padding: 20px; margin: 20px 0; border: 3px dashed red; white-space: pre-wrap"><?= $data['letter']['plaintext'] ?></div>