<?php

$params = require '../config/config.php';
$pdo = Connection::make($params);
return new QueryBuilder($pdo);