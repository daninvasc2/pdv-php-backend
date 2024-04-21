<?php

require_once 'Models/Produto/TipoProduto.php';
require_once 'Validators/TipoProduto/CreateTipoProdutoValidator.php';
require_once 'Validators/TipoProduto/DeleteTipoProdutoValidator.php';
require_once 'Validators/TipoProduto/ShowTipoProdutoValidator.php';
require_once 'Validators/TipoProduto/UpdateTipoProdutoValidator.php';

class TipoProdutoController
{
    public static function get($params = null)
    {
        try {
            $page = 1;
            $term = $params['pesquisa'] ?? '';
            $tipoProdutoClass = new TipoProduto();

            if (!isset($params['page'])) {
                $tipoProdutos = $tipoProdutoClass->getAll();
    
                echo json_encode($tipoProdutos);
                return;
            }

            $page = $params['page'] ?? 1;
            $tiposProduto = $tipoProdutoClass->get($page, $term, 'nome');

            echo json_encode($tiposProduto);
        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode(['success' => false, 'message' => $e->getMessage()]);
        }
    }

    public static function create()
    {
        try {
            $data = json_decode(file_get_contents('php://input'));

            CreateTipoProdutoValidator::validate($data);

            $tipoProdutoClass = new TipoProduto();
            $tipoProdutoClass->create([
                'nome' => $data->nome,
                'porcentagem_imposto' => $data->porcentagemImposto
            ]);

            echo json_encode(['success' => true, 'message' => 'Tipo de produto criado com sucesso']);
        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode(['success' => false, 'message' => $e->getMessage()]);
        }
    }

    public static function update()
    {
        try {
            $data = json_decode(file_get_contents('php://input'));

            UpdateTipoProdutoValidator::validate($data);

            $tipoProdutoClass = new TipoProduto();
            $tipoProdutoClass->update([
                'id' => $data->id,
                'nome' => $data->nome,
                'porcentagemImposto' => $data->porcentagemImposto
            ]);

            echo json_encode(['success' => true, 'message' => 'Tipo de produto atualizado com sucesso']);
        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode(['success' => false, 'message' => $e->getMessage()]);
        }
    }

    public static function delete($id)
    {
        try {
            DeleteTipoProdutoValidator::validate($id);

            $tipoProdutoClass = new TipoProduto();
            $tipoProdutoClass->delete($id);

            echo json_encode(['success' => true, 'message' => 'Tipo de produto deletado com sucesso']);
        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode(['success' => false, 'message' => $e->getMessage()]);
        }
    }

    public static function show($id)
    {
        try {
            ShowTipoProdutoValidator::validate($id);

            $tipoProdutoClass = new TipoProduto();
            $tipoProduto = $tipoProdutoClass->findOrFail($id);

            echo json_encode($tipoProduto);
        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode(['success' => false, 'message' => $e->getMessage()]);
        }
    }
}