<?
$followers = APP::Module('SocialNetworks')->GetFollowers();
?>
<ul class="blog-social-shares">
    <? 
    if ($followers['vk'] !== false) { 
        ?>
        <li>
            <i class="rounded-x fb fa fa-vk"></i>
            <a class="rounded-3x" href="https://vk.com/public<?= APP::Module('SocialNetworks')->settings['module_social_networks_vk_gid'] ?>" target="_blank">VK</a>
            <span class="counter"><?= $followers['vk'] ?></span>
        </li>
        <? 
    }
    
    if ($followers['twitter'] !== false) { 
        ?>
        <li>
            <i class="rounded-x tw fa fa-twitter"></i>
            <a class="rounded-3x" href="https://twitter.com/<?= APP::Module('SocialNetworks')->settings['module_social_networks_twitter_user'] ?>" target="_blank">Twitter</a>
            <span class="counter"><?= $followers['twitter'] ?></span>
        </li>
        <? 
    }
    
    if ($followers['fb'] !== false) { 
        ?>
        <li>
            <i class="rounded-x fb fa fa-facebook"></i>
            <a class="rounded-3x" href="https://www.facebook.com/<?= APP::Module('SocialNetworks')->settings['module_social_networks_fb_name'] ?>" target="_blank">Facebook</a>
            <span class="counter"><?= $followers['fb'] ?></span>
        </li>
        <? 
    }

    if ($followers['gplus'] !== false) { 
        ?>
        <li>
            <i class="rounded-x gp fa fa-google-plus"></i>
            <a class="rounded-3x" href="https://plus.google.com/<?= APP::Module('SocialNetworks')->settings['module_social_networks_gplus_user'] ?>" target="_blank">Google+</a>
            <span class="counter"><?= $followers['gplus'] ?></span>
        </li>
        <? 
    }
    ?>
</ul>