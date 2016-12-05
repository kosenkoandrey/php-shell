<?
$followers = APP::Module('SocialNetworks')->GetFollowers();
?>
<div class="list-group">
    <? if ($followers['vk'] !== false) { ?><a target="_blank" href="https://vk.com/public<?= APP::Module('SocialNetworks')->settings['module_social_networks_vk_gid'] ?>" class="list-group-item"><span class="badge palette-Teal bg"><?= $followers['vk'] ?></span> <i class="zmdi zmdi-vk zmdi-hc-fw"></i> VK</a><? } ?>
    <? if ($followers['fb'] !== false) { ?><a target="_blank" href="https://www.facebook.com/<?= APP::Module('SocialNetworks')->settings['module_social_networks_fb_name'] ?>" class="list-group-item"><span class="badge palette-Teal bg"><?= $followers['fb'] ?></span> <i class="zmdi zmdi-facebook-box zmdi-hc-fw"></i> Facebook</a><? } ?>
    <? if ($followers['gplus'] !== false) { ?><a target="_blank" href="https://plus.google.com/<?= APP::Module('SocialNetworks')->settings['module_social_networks_gplus_user'] ?>" class="list-group-item"><span class="badge palette-Teal bg"><?= $followers['gplus'] ?></span> <i class="zmdi zmdi-google-plus-box zmdi-hc-fw"></i> Google+</a><? } ?>
    <? if ($followers['twitter'] !== false) { ?><a target="_blank" href="https://twitter.com/<?= APP::Module('SocialNetworks')->settings['module_social_networks_twitter_user'] ?>" class="list-group-item"><span class="badge palette-Teal bg"><?= $followers['twitter'] ?></span> <i class="zmdi zmdi-twitter-box zmdi-hc-fw"></i> Twitter</a><? } ?>
</div>