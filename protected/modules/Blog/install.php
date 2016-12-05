<?
$error = false;

if (isset($_POST['action'])) {
    switch ($_POST['action']) {
        case 'blog_set_db_connection': $_SESSION['core']['install']['blog']['db_connection'] = $_POST['db_connection']; break;
        case 'blog_set_settings': $_SESSION['core']['install']['blog']['settings'] = $_POST['settings']; break;
    }
}
?>
<!DOCTYPE html>
<html lang="en-US">
<head>
    <title>Install Blog</title>
    <meta charset="UTF-8">
    <meta name="robots" content="none">
</head>
<body>
    <h1>Install Blog</h1>
    <?
    if (!$error) {
        if (!isset($_SESSION['core']['install']['blog']['db_connection'])) {
            $error = true;
            ?>
            <h3>Select DB connection</h3>
            <form method="post">
                <input type="hidden" name="action" value="blog_set_db_connection">
                <select name="db_connection">
                    <? foreach (array_keys(APP::Module('DB')->conf['connections']) as $connection) { ?><option value="<?= $connection ?>"><?= $connection ?></option><? } ?>
                </select>
                <br><br>
                <input type="submit" value="Next">
            </form>
            <?
        }
    }
    
    if (!$error) {
        if (!isset($_SESSION['core']['install']['blog']['settings'])) {
            $error = true;
            ?>
            <h3>Settings</h3>
            <form method="post">
                <input type="hidden" name="action" value="blog_set_settings">
                
                <label for="settings[module_blog_posts_on_page]">Posts on page</label>
                <br>
                <input type="text" name="settings[module_blog_posts_on_page]" value="5">
                <br><br>
                
                <input type="submit" value="Next">
            </form>
            <?
        }
    }
    ?>
</body>
</html>
<?
$content = ob_get_contents();
ob_end_clean();

if ($error) {
    echo $content;
    exit;
}

// Install module

$data->extractTo(ROOT);

$db = APP::Module('DB')->Open($_SESSION['core']['install']['blog']['db_connection']);

$db->query("
    SET SQL_MODE = 'NO_AUTO_VALUE_ON_ZERO';
    SET time_zone = '+00:00';

    CREATE TABLE `blog_articles` (
      `id` smallint(5) UNSIGNED NOT NULL,
      `group_id` smallint(5) UNSIGNED NOT NULL,
      `uri` varchar(150) COLLATE utf8_unicode_ci NOT NULL,
      `page_title` varchar(150) COLLATE utf8_unicode_ci NOT NULL,
      `h1_title` varchar(150) COLLATE utf8_unicode_ci NOT NULL,
      `annotation` text COLLATE utf8_unicode_ci NOT NULL,
      `html_content` mediumtext COLLATE utf8_unicode_ci NOT NULL,
      `image_type` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
      `image` mediumtext COLLATE utf8_unicode_ci NOT NULL,
      `tags` text COLLATE utf8_unicode_ci NOT NULL,
      `description` varchar(250) COLLATE utf8_unicode_ci NOT NULL,
      `keywords` varchar(250) COLLATE utf8_unicode_ci NOT NULL,
      `robots` enum('all','none','index,follow','noindex,follow','index,nofollow','noindex,nofollow') COLLATE utf8_unicode_ci NOT NULL,
      `up_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

    CREATE TABLE `blog_groups` (
      `id` smallint(5) UNSIGNED NOT NULL,
      `sub_id` smallint(5) NOT NULL DEFAULT '0',
      `uri` varchar(150) COLLATE utf8_unicode_ci NOT NULL,
      `page_title` varchar(150) COLLATE utf8_unicode_ci NOT NULL,
      `h1_title` varchar(150) COLLATE utf8_unicode_ci NOT NULL,
      `annotation` text COLLATE utf8_unicode_ci NOT NULL,
      `image_type` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
      `image` mediumtext COLLATE utf8_unicode_ci NOT NULL,
      `description` varchar(250) COLLATE utf8_unicode_ci NOT NULL,
      `keywords` varchar(250) COLLATE utf8_unicode_ci NOT NULL,
      `robots` enum('all','none','index,follow','noindex,follow','index,nofollow','noindex,nofollow') COLLATE utf8_unicode_ci NOT NULL,
      `up_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

    INSERT INTO `blog_groups` (`id`, `sub_id`, `uri`, `page_title`, `h1_title`, `annotation`, `image_type`, `image`, `description`, `keywords`, `robots`, `up_date`) VALUES
    (0, 0, '', '/', '', '', '', '', '', '', '', CURRENT_TIMESTAMP);

    ALTER TABLE `blog_articles`
      ADD PRIMARY KEY (`id`),
      ADD KEY `group_id` (`group_id`),
      ADD KEY `uri` (`uri`);

    ALTER TABLE `blog_groups`
      ADD PRIMARY KEY (`id`),
      ADD KEY `sub_id` (`sub_id`),
      ADD KEY `uri` (`uri`);

    ALTER TABLE `blog_articles`
      MODIFY `id` smallint(5) UNSIGNED NOT NULL AUTO_INCREMENT;
    ALTER TABLE `blog_groups`
      MODIFY `id` smallint(5) UNSIGNED NOT NULL AUTO_INCREMENT;

    ALTER TABLE `blog_articles`
      ADD CONSTRAINT `blog_articles_ibfk_1` FOREIGN KEY (`group_id`) REFERENCES `blog_groups` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;
      
    INSERT INTO `likes_objects` (`id`, `name`, `up_date`) VALUES (NULL, 'BlogArticle', CURRENT_TIMESTAMP);
    INSERT INTO `comments_objects` (`id`, `name`, `up_date`) VALUES (NULL, 'BlogArticle', CURRENT_TIMESTAMP);
");

APP::Module('Registry')->Add('module_blog_db_connection', $_SESSION['core']['install']['blog']['db_connection']);
APP::Module('Registry')->Add('module_blog_posts_on_page', $_SESSION['core']['install']['blog']['settings']['module_blog_posts_on_page']);

APP::Module('Triggers')->Register('blog_add_article', 'Blog', 'Add article');
APP::Module('Triggers')->Register('blog_remove_article', 'Blog', 'Remove article');
APP::Module('Triggers')->Register('blog_update_article', 'Blog', 'Update article');
APP::Module('Triggers')->Register('blog_add_articles_group', 'Blog', 'Add articles group');
APP::Module('Triggers')->Register('blog_remove_articles_group', 'Blog', 'Remove articles group');
APP::Module('Triggers')->Register('blog_update_articles_group', 'Blog', 'Update articles group');
APP::Module('Triggers')->Register('blog_update_settings', 'Blog', 'Update settings');