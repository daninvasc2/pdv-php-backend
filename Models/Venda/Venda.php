<?php

require_once 'Models/Model.php';
require_once 'Utils/Utils.php';

class Venda extends Model {
    protected $table = 'vendas';

    public function __construct() {
        parent::__construct($this->table);
    }

    public function retornaItensVenda($id) {
        $sql = "SELECT * FROM itens_venda WHERE venda_id = :id";
        $stmt = $this->connection->prepare($sql);
        $stmt->bindValue(':id', $id);
        $stmt->execute();

        $data = $stmt->fetchAll(PDO::FETCH_ASSOC);

        
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

    public function deletarItensVenda($id) {
        $sql = "DELETE FROM itens_venda WHERE venda_id = :id";
        $stmt = $this->connection->prepare($sql);
        $stmt->bindValue(':id', $id);
        $stmt->execute();
    }
}