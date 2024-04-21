<?php

require_once 'Models/Produto/TipoProduto.php';
require_once 'Validators/ValidatorInterface.php';

class DeleteTipoProdutoValidator implements ValidatorInterface {
    public static function validate(mixed $data): void
    {
        if (!isset($data)) {
            throw new Exception('ID do tipo de produto é obrigatório');
        }

        if (!is_numeric($data)) {
            throw new Exception('ID do tipo de produto deve ser um número');
        }

        $tipoProdutoClass = new TipoProduto();
        $produtos = $tipoProdutoClass->verificaVinculoProduto($data);

        if ($produtos) {
            throw new Exception('Existem produtos vinculados a este tipo de produto');
        }
    }
}