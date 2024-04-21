<?php

require_once 'Config/Database.php';
require_once 'Utils/Utils.php';

/**
 * Base model class. It contains the basic CRUD operations.
 */
class Model {
    protected string $table;
    protected PDO $connection;

    public function __construct(string $table) {
        $this->table = $table;
        $db = DatabaseConfig::getInstance();
        $this->connection = $db->getConnection();
    }

    /**
     * Create a new record
     * 
     * @param array $data
     * @return int
     */
    public function create(array $data): int
    {
        $keys = array_keys($data);
        $keys = array_map(function ($key) {
            return camelCaseToSnakeCase($key);
        }, $keys);

        $fields = implode(', ', $keys);
        $fieldCount = count($keys);
        $values = str_repeat("?, ", $fieldCount);
        $values = substr($values, 0, -2);

        $sql = "INSERT INTO " . $this->table . " ($fields) VALUES ($values)";
        $stmt = $this->connection->prepare($sql);
        $stmt->execute(array_values($data));

        return $this->connection->lastInsertId();
    }

    /**
     * Update a record
     * 
     * @param array $data
     * @return bool
     */
    public function update(array $data): bool
    {
        $fields = '';
        foreach ($data as $key => $unused) {
            $fields .= $key . " = '?', ";
        }
        $fields = substr($fields, 0, -2);

        $sql = "UPDATE " . $this->table . " SET $fields WHERE id = :id";
        $stmt = $this->connection->prepare($sql);
        $stmt->bindValue(':id', $data['id']);
        $stmt->execute(array_values($data));

        return true;
    }

    /**
     * Delete a record
     * 
     * @param int $id
     * @return bool
     */
    public function delete(int $id): bool
    {
        $sql = "DELETE FROM " . $this->table . " WHERE id = :id";
        $stmt = $this->connection->prepare($sql);
        $stmt->bindValue(':id', $id);
        $stmt->execute();

        return true;
    }

    /**
     * Find a record by ID or throw an exception
     * 
     * @param int $id
     * 
     * @throws Exception
     * 
     * @return array
     */
    public function findOrFail(int $id): array
    {
        $sql = "SELECT * FROM " . $this->table . " WHERE id = :id";
        $stmt = $this->connection->prepare($sql);
        $stmt->bindValue(':id', $id);
        $stmt->execute();

        $data = $stmt->fetch(PDO::FETCH_ASSOC);
        if (!$data) {
            throw new Exception('Registro não encontrado');
        }

        foreach ($data as $key => $value) {
            if (str_contains($key, '_id')) {
                $sql = "SELECT * FROM " . str_replace('_id', 's', $key) . " WHERE id = :id";
                    $stmt = $this->connection->prepare($sql);
                    $stmt->bindValue(':id', $value);
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

    /**
     * Get records paginated. If a term is provided, it will search for it in the specified column
     * 
     * @param int $page
     * @param string $term
     * @param string $column
     * 
     * @return array
     */
    public function get(int $page, string $term = '', string $column = 'nome'): array {
        $sql = "SELECT * FROM " . $this->table . " WHERE 1=1 ";

        if (is_array($column) && $term != '') {
            $sql .= "AND (";

            foreach ($column as $col) {
                $sql .= "$col ILIKE '%:term%' OR ";
            }

            $sql = substr($sql, 0, -4);
            $sql .= ")";
        }

        if ($term != '' && !is_array($column) && $column != '') {
            $sql .= " AND $column ILIKE '%:term%'";
        }

        $sql .= " ORDER BY id ASC";
        $sql .= " LIMIT 10 OFFSET " . ($page - 1) * 10;

        $stmt = $this->connection->prepare($sql);

        if ($term != '' && !is_array($column) && $column != '') {
            $stmt->bindValue(':term', $term);
        }

        $stmt->execute();

        $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $data = $this->handleData($data);

        return $data;
    }

    /**
     * Get paginated records between two dates
     * 
     * @param string $firstDate
     * @param string $lastDate
     * @param string $columnName
     * 
     * @return array
     */
    public function getBetween(string $firstDate, string $lastDate, string $columnName = 'data'): array {
        $sql = "SELECT * FROM " . $this->table . " WHERE $columnName BETWEEN ':firstDate 00:00:00' AND ':lastDate 23:59:59'";
        $stmt = $this->connection->prepare($sql);
        $stmt->bindValue(':firstDate', $firstDate);
        $stmt->bindValue(':lastDate', $lastDate);
        $stmt->execute();

        $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $data = $this->handleData($data);

        return $data;
    }

    /**
     * Get all records
     * 
     * @return array
     */
    public function getAll(): array
    {
        $sql = "SELECT * FROM " . $this->table;
        $stmt = $this->connection->prepare($sql);
        $stmt->execute();

        $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $data = $this->handleData($data);

        return $data;
    }

    /**
     * Handle data to return camelCase keys and inner relations
     * 
     * @param array $data
     * 
     * @return array
     */
    private function handleData(array $data): array {
        foreach ($data as $key => $value) {
            foreach ($value as $k => $v) {
                if (str_contains($k, '_id')) {
                    $sql = "SELECT * FROM " . str_replace('_id', 's', $k) . " WHERE id = :id";
                    $stmt = $this->connection->prepare($sql);
                    $stmt->bindValue(':id', $v);
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