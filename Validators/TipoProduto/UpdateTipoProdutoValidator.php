<?php

require_once 'Validators/ValidatorInterface.php';

class UpdateTipoProdutoValidator implements ValidatorInterface {
    public static function validate(mixed $data): void
    {
        $requiredFields = ['id', 'nome', 'porcentagemImposto'];
        $numericFields = ['id', 'porcentagemImposto'];

        foreach ($requiredFields as $field) {
            if (!isset($data->$field)) {
                throw new Exception(ucfirst($field) . ' do tipo de produto é obrigatório');
            }
        }

        foreach ($numericFields as $field) {
            if (!is_numeric($data->$field)) {
                throw new Exception(ucfirst($field) . ' do tipo de produto deve ser um número');
            }
        }
    }
}