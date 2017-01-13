<h1><?= $data['code'] ?></h1>
<hr>
<h3>HTML</h3>
<div style="border: 3px dashed #ffb2b2;"><?= $data['content'] ?></div>
<h3>PLAINTEXT</h3>
<div style="border: 3px dashed #ffb2b2; white-space: pre-wrap"><?= htmlspecialchars($data['content']) ?></div>