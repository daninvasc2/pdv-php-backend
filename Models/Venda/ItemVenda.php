<?php

require_once 'Models/Model.php';
class ItemVenda extends Model {

    private $id;
    private $quantidade;
    private $valor_unitario;
    private $valor_total;
    private $valor_imposto;
    private $venda_id;
    private $produto_id;
    protected string $table = 'itens_venda';

    public function __construct() {
        parent::__construct($this->table);
    }
}