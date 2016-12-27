<?
preg_match_all('/\<\?(.*)\?\>/ismU', $data['page']['content'], $html_php);
        
foreach ($html_php[0] as $key => $pattern) $data['page']['content'] = str_replace($pattern, '<span class=\'php\'><b>&#60?php ?&#62</b></span>', $data['page']['content']);

?>
<!DOCTYPE html>
<html lang="en-US">
<head>
    <title>Members</title>
    <meta charset="UTF-8">
    <meta name="robots" content="none">
    <style>
        .php {
            background: #fdd3d3;
        }
    </style>
</head>
<body>
    <h1 style="margin: 20px 0;"><?= $data['page']['title'] ?></h1>
    <h3>Content</h3>
    <div style="padding: 20px; margin: 20px 0; border: 3px dashed red;"><?= $data['page']['content'] ?></div>
</body>
</html>