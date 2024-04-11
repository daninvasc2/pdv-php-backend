<?php

require_once 'Models/Produto/TipoProduto.php';

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
            
            if (!isset($data->nome)) {
                throw new Exception('Nome do tipo de produto é obrigatório');
            }

            if (!isset($data->porcentagemImposto)) {
                throw new Exception('Porcentagem de imposto do tipo de produto é obrigatório');
            }

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

            if (!isset($data->id)) {
                throw new Exception('ID do tipo de produto é obrigatório');
            }

            if (!isset($data->nome)) {
                throw new Exception('Nome do tipo de produto é obrigatório');
            }

            if (!isset($data->porcentagemImposto)) {
                throw new Exception('Porcentagem de imposto do tipo de produto é obrigatório');
            }

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

            if (!isset($id)) {
                throw new Exception('ID do tipo de produto é obrigatório');
            }

            $tipoProdutoClass = new TipoProduto();
            $produtos = $tipoProdutoClass->verificaVinculoProduto($id);

            if ($produtos) {
                throw new Exception('Existem produtos vinculados a este tipo de produto');
            }

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
            if (!isset($id)) {
                throw new Exception('ID do tipo de produto é obrigatório');
            }

            $tipoProdutoClass = new TipoProduto();
            $tipoProduto = $tipoProdutoClass->findOrFail($id);

            echo json_encode($tipoProduto);
        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode(['success' => false, 'message' => $e->getMessage()]);
        }
    }
}