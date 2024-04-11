<?php

require_once 'Models/Model.php';
class Produto extends Model {
    protected $table = 'produtos';

    public function __construct() {
        parent::__construct($this->table);
    }

    public function verificaSeProdutoFoiUtilizadoEmVenda(int $produtoId) {
        $sql = "SELECT COUNT(*) as total FROM itens_venda WHERE produto_id = :produtoId";
        $stmt = $this->connection->prepare($sql);
        $stmt->bindParam(':produtoId', $produtoId);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        return $result['total'] > 0;
    }
}