<pre>
<?php

$qb = require '../config/init.php';
$table = 'posts';
$items = $qb->read($table);
$keys = array_keys($items[0]);

?>
</pre>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-wEmeIV1mKuiNpC+IOBjI7aAzPcEZeedi5yW5f2yOq55WWLwNGmvvx4Um1vskeMj0" crossorigin="anonymous">
    <title><?php echo ucfirst($table);?></title>
</head>
<body>
    <div class="container-sm">
        <div class="row">
            <div class="col col-md-6">
                <h1 class="h1"><?php echo ucfirst($table);?></h1><br>
                <table class="table table-striped">
                    <thead>
                        <tr class="table-dark">
                            <?php foreach ($keys as $key):?>
                            <th><?php echo ucfirst($key);?></th>
                            <?php endforeach;?>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($items as $post):?>
                        <tr class="table-light">
                            <?php foreach ($keys as $key):?>
                            <td><?php echo $post[$key];?></td>
                            <?php endforeach;?>
                        </tr>
                        <?php endforeach;?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</body>
</html>