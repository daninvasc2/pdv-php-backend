<?php

require_once 'Config/Database.php';
require_once 'Utils/Utils.php';

class Model {
    protected $table;
    protected $connection;

    public function __construct($table) {
        $this->table = $table;
        $db = new DatabaseConfig();
        $this->connection = $db->getConnection();
    }

    public function create($data)
    {
        $keys = array_keys($data);
        $keys = array_map(function ($key) {
            return camelCaseToSnakeCase($key);
        }, $keys);

        $fields = implode(', ', $keys);
        $values = implode("', '", array_values($data));
        
        $sql = "INSERT INTO " . $this->table . " ($fields) VALUES ('$values')";
        $stmt = $this->connection->prepare($sql);
        $stmt->execute();

        return $this->connection->lastInsertId();
    }

    public function update($data)
    {
        if (!isset($data['id']) || empty($data['id'])) {
            throw new Exception('ID não informado');
        }

        $fields = '';
        foreach ($data as $key => $value) {
            $fields .= $key . " = '" . $value . "', ";
        }
        $fields = substr($fields, 0, -2);

        $sql = "UPDATE " . $this->table . " SET $fields WHERE id = " . $data['id'];
        $stmt = $this->connection->prepare($sql);
        $stmt->execute();

        return true;
    }

    public function delete($id)
    {
        if (!isset($id) || empty($id)) {
            throw new Exception('ID não informado');
        }

        $sql = "DELETE FROM " . $this->table . " WHERE id = " . $id;
        $stmt = $this->connection->prepare($sql);
        $stmt->execute();

        return true;
    }

    public function findOrFail($id)
    {
        if (!isset($id) || empty($id)) {
            throw new Exception('ID não informado');
        }

        $sql = "SELECT * FROM " . $this->table . " WHERE id = " . $id;
        $stmt = $this->connection->prepare($sql);
        $stmt->execute();

        $data = $stmt->fetch(PDO::FETCH_ASSOC);
        if (!$data) {
            echo json_encode(['success' => false, 'message' => 'Registro não encontrado']);
            return;
        }

        foreach ($data as $key => $value) {
            if (str_contains($key, '_id')) {
                $sql = "SELECT * FROM " . str_replace('_id', 's', $key) . " WHERE id = " . $value;
                    $stmt = $this->connection->prepare($sql);
                    $stmt->execute();

                    $relationKey = snakeCaseToCamelCase(str_replace('_id', '', $key));

                    $data[$relationKey] = $stmt->fetch(PDO::FETCH_ASSOC);
                    unset($data[$key]);
            }

            if (str_contains($key, '_')) {
                $camelCaseKey = snakeCaseToCamelCase($key);
                $data[$camelCaseKey] = $value;
                unset($data[$key]);
            }
        }

        return $data;
    }

    public function get($page, $term = '', $column = 'nome') {
        $sql = "SELECT * FROM " . $this->table . " WHERE 1=1 ";

        if (is_array($column) && $term != '') {
            $sql .= "AND (";
            foreach ($column as $col) {
                $sql .= "$col ILIKE '%$term%' OR ";
            }
            $sql = substr($sql, 0, -4);
            $sql .= ")";
        }

        if ($term != '' && !is_array($column) && $column != '') {
            $sql .= " AND $column ILIKE '%$term%'";
        }

        $sql .= " ORDER BY id ASC";
        $sql .= " LIMIT 10 OFFSET " . ($page - 1) * 10;

        $stmt = $this->connection->prepare($sql);
        $stmt->execute();

        $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $data = $this->handleData($data);

        return $data;
    }

    public function getBetween($dataInicial, $dataFinal, $coluna = 'data') {
        $sql = "SELECT * FROM " . $this->table . " WHERE $coluna BETWEEN '$dataInicial 00:00:00' AND '$dataFinal 23:59:59'";
        $stmt = $this->connection->prepare($sql);
        $stmt->execute();

        $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $data = $this->handleData($data);

        return $data;
    }

    public function getAll()
    {
        $sql = "SELECT * FROM " . $this->table;
        $stmt = $this->connection->prepare($sql);
        $stmt->execute();

        $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $data = $this->handleData($data);

        return $data;
    }

    private function handleData($data) {
        foreach ($data as $key => $value) {
            foreach ($value as $k => $v) {
                if (str_contains($k, '_id')) {
                    $sql = "SELECT * FROM " . str_replace('_id', 's', $k) . " WHERE id = " . $v;
                    $stmt = $this->connection->prepare($sql);
                    $stmt->execute();

                    $relationKey = snakeCaseToCamelCase(str_replace('_id', '', $k));
                    $innerData = $stmt->fetch(PDO::FETCH_ASSOC);
                    $innerData = returnCamelCaseKeys($innerData);

                    $data[$key][$relationKey] = $innerData;
                    unset($data[$key][$k]);
                } else if (str_contains($k, '_')) {
                    $camelCaseKey = snakeCaseToCamelCase($k);
                    $data[$key][$camelCaseKey] = $v;
                    unset($data[$key][$k]);
                }
            }

            if (str_contains($key, '_')) {
                $camelCaseKey = snakeCaseToCamelCase($key);
                $data[$camelCaseKey] = $value;
            }
        }

        return $data;
    }
}