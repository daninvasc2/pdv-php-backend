<?php

require_once 'Models/Model.php';

class TipoProduto extends Model {

    private $id;
    private $nome;
    private $porcentagem_imposto;

    protected string $table = 'tipo_produtos';

    public function __construct() {
        parent::__construct($this->table);
    }

    public function verificaVinculoProduto($id) {
        $sql = "SELECT * FROM produtos WHERE tipo_produto_id = " . $id;
        $stmt = $this->connection->prepare($sql);
        $stmt->execute();

        return $stmt->fetch();
    }
}