<?php


class QueryBuilder {
    private $pdo;

    public function __construct($pdo)
    {
        $this->pdo = $pdo;
    }

    public function create($table, array $params) {
        $fields = implode(', ', array_keys($params));
        $values = implode(', ', array_map(function ($item) {
            return ':' . $item;
        }, array_keys($params)));
        $sql = "INSERT INTO {$table} ({$fields}) VALUES ({$values})";
        $statement = $this->pdo->prepare($sql);
        return $statement->execute(array_combine(array_map(function ($item) {
            return ':' . $item;
        }, array_keys($params)), array_values($params)));
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

    public function update($table, $id, array $params) {
        $exp = implode(', ', array_map(function ($item) {
            return $item . '=:' . $item;
        }, array_keys($params)));
        $sql = "UPDATE {$table} SET {$exp} WHERE id=:id";
        $statement = $this->pdo->prepare($sql);
        $params = array_combine(array_map(function ($item) {
            return ':' . $item;
        }, array_keys($params)), array_values($params));
        $params[':id'] = $id;
        return $statement->execute($params);
    }

    public function deleteById($table, $id) {
        $sql = "DELETE FROM {$table} WHERE id=:id";
        $statement = $this->pdo->prepare($sql);
        return $statement->execute(array(':id' => $id));
    }

    public function deleteIf($table, $condition) {
        $cond = implode(', ', array_map(function ($item) {
            return $item . '=:' . $item;
        }, array_keys($condition)));
        $sql = "DELETE FROM {$table} WHERE {$cond}";
        $condition = array_combine(array_map(function ($item) {
            return ':' . $item;
        }, array_keys($condition)), array_values($condition));
        $statement = $this->pdo->prepare($sql);
        return $statement->execute($condition);
    }


    private function readAll($table) {
        $sql = "SELECT * FROM {$table}";
        $statement = $this->pdo->prepare($sql);
        $statement->execute();
        return $statement->fetchAll(PDO::FETCH_ASSOC);
    }

    private function readColumns($table, $columns) {
        $cols = implode(', ', $columns);
        $sql = "SELECT {$cols} FROM {$table}";
        $statement = $this->pdo->prepare($sql);
        $statement->execute();
        return $statement->fetchAll(PDO::FETCH_ASSOC);
    }

    private function readColsIf($table, $columns, $condition) {
        $cols = implode(', ', $columns);
        $cond = implode(', ', array_map(function ($item) {
            return $item . '=:' . $item;
        }, array_keys($condition)));
        $sql = "SELECT {$cols} FROM {$table} WHERE {$cond}";
        $condition = array_combine(array_map(function ($item) {
            return ':' . $item;
        }, array_keys($condition)), array_values($condition));
        $statement = $this->pdo->prepare($sql);
        $statement->execute($condition);
        return $statement->fetchAll(PDO::FETCH_ASSOC);
    }

    private function readIf($table, $condition) {
        $cond = implode(', ', array_map(function ($item) {
            return $item . '=:' . $item;
        }, array_keys($condition)));
        $sql = "SELECT * FROM {$table} WHERE {$cond}";
        $statement = $this->pdo->prepare($sql);
        $condition = array_combine(array_map(function ($item) {
            return ':' . $item;
        }, array_keys($condition)), array_values($condition));
        $statement->execute($condition);
        return $statement->fetchAll(PDO::FETCH_ASSOC);
    }
}