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
        if (isset($columns) && isset($condition)) {
            $cols = implode(', ', $columns);
            $cond = getKeyValuesString($condition);
            $sql = "SELECT {$cols} FROM {$table} WHERE {$cond}";
            return $this->prepareAndFetchAssoc($sql, getTagValues($condition));
        } else {
            $sql = "SELECT * FROM {$table}";
            return $this->prepareAndFetchAssoc($sql);
        }
    }

    public function update($table, $id, array $params) {
        $values = getKeyValuesString($params);
        $sql = "UPDATE {$table} SET {$values} WHERE id=:id";
        $params['id'] = $id;
        return $this->prepareAndExecute($sql, getTagValues($params));
    }

    public function delete($table, $id) {
        $sql = "DELETE FROM {$table} WHERE id=:id";
        return $this->prepareAndExecute($sql, array(':id' => $id));
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