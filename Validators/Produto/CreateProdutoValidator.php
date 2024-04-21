<?php

require_once 'Validators/ValidatorInterface.php';

class CreateProdutoValidator implements ValidatorInterface {
    public static function validate(mixed $data): void
    {
        $requiredFields = ['nome', 'preco', 'tipoProdutoId'];
        $numericFields = ['preco', 'tipoProdutoId'];

        foreach ($requiredFields as $field) {
            if (!isset($data->$field)) {
                throw new Exception(ucfirst($field) . ' é obrigatório');
            }
        }

        foreach ($numericFields as $field) {
            if (!is_numeric($data->$field)) {
                throw new Exception(ucfirst($field) . ' deve ser numérico');
            }

            if ($data->$field < 0.01) {
                throw new Exception(ucfirst($field) . ' deve ser maior que 0.01');
            }
        }
    }
}