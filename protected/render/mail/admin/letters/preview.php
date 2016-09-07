<?
preg_match_all('/\<\?(.*)\?\>/ismU', $data['letter']['html'], $html_php);
preg_match_all('/\<\?(.*)\?\>/ismU', $data['letter']['plaintext'], $plaintext_php);
        
foreach ($html_php[0] as $key => $pattern) $data['letter']['html'] = str_replace($pattern, '<span class=\'php\'><b>&#60?php ?&#62</b></span>', $data['letter']['html']);
foreach ($plaintext_php[0] as $key => $pattern) $data['letter']['plaintext'] = str_replace($pattern, '<span class=\'php\'><b>&#60?php ?&#62</b></span>', $data['letter']['plaintext']);
?>
<!DOCTYPE html>
<html lang="en-US">
<head>
    <title>Mail</title>
    <meta charset="UTF-8">
    <meta name="robots" content="none">
    <style>
        .php {
            background: #fdd3d3;
        }
    </style>
</head>
<body>
    <h1 style="margin: 20px 0;"><?= $data['letter']['subject'] ?></h1>
    <h3>HTML-version</h3>
    <div style="padding: 20px; margin: 20px 0; border: 3px dashed red;"><?= $data['letter']['html'] ?></div>
    <h3>Plaintext-version</h3>
    <div style="padding: 20px; margin: 20px 0; border: 3px dashed red; white-space: pre-wrap"><?= $data['letter']['plaintext'] ?></div>
</body>
</html>