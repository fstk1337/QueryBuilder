<?php

require '../src/Connection.php';
require '../src/QueryBuilder.php';

$dbtype = 'mysql';
$dbname = 'app3';
$host = 'localhost';
$username = 'root';
$password = 'root';

return [
    'dbtype' => $dbtype,
    'dbname' => $dbname,
    'host' => $host,
    'username' => $username,
    'password' => $password
];