<!DOCTYPE html>
<html lang="en-US">
<head>
    <title>Files</title>
    <meta charset="UTF-8">
    <meta name="robots" content="none">
    <style>
        .php {
            background: #fdd3d3;
        }
    </style>
</head>
<body>
    <h1 style="margin: 20px 0;"><?= $data['file']['title'] ?></h1>
    <h3>Content</h3>
    <div style="padding: 20px; margin: 20px 0; border: 3px dashed red;"><?= $data['file']['id'].'.'.$data['file']['type'] ?></div>
</body>
</html>