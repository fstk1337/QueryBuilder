<?php

require 'functions.php';


class QueryBuilder {
    private $pdo;

    public function __construct($pdo)
    {
        $this->pdo = $pdo;
    }

    public function create($table, array $params) {
        $fields = getKeyString($params);
        $values = getKeyTagString($params);
        $sql = "INSERT INTO {$table} ({$fields}) VALUES ({$values})";
        return $this->prepareAndExecute($sql, getTagValues($params));
    }

    public function read($table, $columns=null, $condition=null) {
        if (!isset($columns)) {
            if (!isset($condition)) {
                return $this->readAll($table);
            } else {
                return $this->readIf($table, $condition);
            }
        } else if (isset($condition)) {
            return $this->readColsIf($table, $columns, $condition);
        } else {
            return $this->readColumns($table, $columns);
        }
    }

    public function updateById($table, $id, array $params) {
        $values = getKeyValuesString($params);
        $sql = "UPDATE {$table} SET {$values} WHERE id=:id";
        $params['id'] = $id;
        return $this->prepareAndExecute($sql, getTagValues($params));
    }

    public function updateIf($table, array $condition, array $params) {
        $values = getKeyValuesString($params);
        $cond = getKeyValuesString($condition);
        $sql = "UPDATE {$table} SET {$values} WHERE {$cond}";
        return $this->prepareAndExecute($sql, getTagValues($params + $condition));
    }

    public function deleteById($table, $id) {
        $sql = "DELETE FROM {$table} WHERE id=:id";
        return $this->prepareAndExecute($sql, array(':id' => $id));
    }

    public function deleteIf($table, $condition) {
        $cond = getKeyValuesString($condition);
        $sql = "DELETE FROM {$table} WHERE {$cond}";
        return $this->prepareAndExecute($sql, getTagValues($condition));
    }


    private function readAll($table) {
        $sql = "SELECT * FROM {$table}";
        return $this->prepareAndFetchAssoc($sql);
    }

    private function readColumns($table, $columns) {
        $cols = implode(', ', $columns);
        $sql = "SELECT {$cols} FROM {$table}";
        return $this->prepareAndFetchAssoc($sql);
    }

    private function readColsIf($table, $columns, $condition) {
        $cols = implode(', ', $columns);
        $cond = getKeyValuesString($condition);
        $sql = "SELECT {$cols} FROM {$table} WHERE {$cond}";
        return $this->prepareAndFetchAssoc($sql, getTagValues($condition));
    }

    private function readIf($table, $condition) {
        $cond = getKeyValuesString($condition);
        $sql = "SELECT * FROM {$table} WHERE {$cond}";
        return $this->prepareAndFetchAssoc($sql, getTagValues($condition));
    }

    private function prepareAndExecute($sql, array $params) {
        $statement = $this->pdo->prepare($sql);
        return $statement->execute($params);
    }

    private function prepareAndFetchAssoc($sql, $params = null) {
        $statement = $this->pdo->prepare($sql);
        $statement->execute($params);
        return $statement->fetchAll(PDO::FETCH_ASSOC);
    }
}