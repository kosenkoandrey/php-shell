<?
$db = APP::Module('Blog')->settings['module_blog_db_connection'];

APP::Module('DB')->Open($db)->query('DROP TABLE blog_articles');
APP::Module('DB')->Open($db)->query('DROP TABLE blog_groups');

APP::Module('Registry')->Delete([['item', 'IN', [
    'module_blog_db_connection',
    'module_blog_posts_on_page'
]]]);

APP::Module('Triggers')->Unregister([
    'blog_add_article',
    'blog_remove_article',
    'blog_update_article',
    'blog_add_articles_group',
    'blog_remove_articles_group',
    'blog_update_articles_group',
    'blog_update_settings'
]);