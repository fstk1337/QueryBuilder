<?php


class Connection {

    public static function make(array $params) {
        $dbtype = $params['dbtype'];
        $dbname = $params['dbname'];
        $host = $params['host'];
        $username = $params['username'];
        $password = $params['password'];
        $dsn = "{$dbtype}:dbname={$dbname};host={$host}";
        return new PDO($dsn, $username, $password);
    }
}