<?php

require_once 'Models/Produto/Produto.php';
require_once 'Validators/ValidatorInterface.php';

class DeleteProdutoValidator implements ValidatorInterface
{
    public static function validate(mixed $data): void
    {
        if (!isset($data)) {
            throw new Exception('ID do produto é obrigatório');
        }

        if (!is_numeric($data)) {
            throw new Exception('ID do produto deve ser um número');
        }

        $produtoClass = new Produto();
        if ($produtoClass->verificaSeProdutoFoiUtilizadoEmVenda($data)) {
            throw new Exception('Produto não pode ser deletado pois está sendo utilizado em uma venda');
        }
    }
}