<?php

require_once 'Validators/ValidatorInterface.php';

class ShowTipoProdutoValidator implements ValidatorInterface {
    public static function validate(mixed $data): void
    {
        if (!isset($data)) {
            throw new Exception('ID do tipo de produto é obrigatório');
        }

        if (!is_numeric($data)) {
            throw new Exception('ID do tipo de produto deve ser um número');
        }
    }
}