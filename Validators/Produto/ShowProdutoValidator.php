<?php

require_once 'Validators/ValidatorInterface.php';

class ShowProdutoValidator implements ValidatorInterface
{
    public static function validate(mixed $data): void
    {
        if (!isset($data)) {
            throw new Exception('ID do produto é obrigatório');
        }

        if (!is_numeric($data)) {
            throw new Exception('ID do produto deve ser um número');
        }
    }
}