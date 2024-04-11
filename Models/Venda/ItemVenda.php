<?php

require_once 'Models/Model.php';
class ItemVenda extends Model {
    protected $table = 'itens_venda';

    public function __construct() {
        parent::__construct($this->table);
    }
}