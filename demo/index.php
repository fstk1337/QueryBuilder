<h1>Test</h1>
<pre>
<?php

$qb = require '../config/init.php';

$posts = $qb->read('posts');
var_dump($posts);

?>
</pre>