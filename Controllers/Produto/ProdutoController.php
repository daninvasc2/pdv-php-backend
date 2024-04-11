<?php

require_once 'Models/Produto/Produto.php';

class ProdutoController {
    public static function get(array $params = null) {
        try {
            $page = $params['page'] ?? 1;
            $term = $params['pesquisa'] ?? '';

            $produtoClass = new Produto();
            $produtos = $produtoClass->get($page, $term, 'nome');

            echo json_encode($produtos);
        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode(['success' => false, 'message' => $e->getMessage()]);
        }
    }

    public static function create() {
        try {
            $data = json_decode(file_get_contents('php://input'));

            if (!isset($data->nome)) {
                throw new Exception('Nome do produto é obrigatório');
            }

            if (!isset($data->preco)) {
                throw new Exception('Preço do produto é obrigatório');
            }

            if (!isset($data->tipoProdutoId)) {
                throw new Exception('Tipo do produto é obrigatório');
            }

            $produtoClass = new Produto();
            $produtoClass->create([
                'nome' => $data->nome,
                'preco' => $data->preco,
                'tipo_produto_id' => $data->tipoProdutoId
            ]);

            echo json_encode(['success' => true, 'message' => 'Produto criado com sucesso']);
        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode(['success' => false, 'message' => $e->getMessage()]);
        }
    }

    public static function update() {
        try {
            $data = json_decode(file_get_contents('php://input'));

            if (!isset($data->id)) {
                throw new Exception('ID do produto é obrigatório');
            }

            if (!isset($data->nome)) {
                throw new Exception('Nome do produto é obrigatório');
            }

            if (!isset($data->preco)) {
                throw new Exception('Preço do produto é obrigatório');
            }

            if (!isset($data->tipoProdutoId)) {
                throw new Exception('Tipo do produto é obrigatório');
            }

            $produtoClass = new Produto();
            $produtoClass->update([
                'id' => $data->id,
                'nome' => $data->nome,
                'preco' => $data->preco,
                'tipo_produto_id' => $data->tipoProdutoId
            ]);

            echo json_encode(['success' => true, 'message' => 'Produto atualizado com sucesso']);
        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode(['success' => false, 'message' => $e->getMessage()]);
        }
    }

    public static function delete($id) {
        try {
            if (!isset($id)) {
                throw new Exception('ID do produto é obrigatório');
            }

            $produtoClass = new Produto();
            if ($produtoClass->verificaSeProdutoFoiUtilizadoEmVenda($id)) {
                throw new Exception('Produto não pode ser deletado pois está sendo utilizado em uma venda');
            }

            $produtoClass->delete($id);

            echo json_encode(['success' => true, 'message' => 'Produto deletado com sucesso']);
        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode(['success' => false, 'message' => $e->getMessage()]);
        }
    }

    public static function show($id) {
        try {
            $produtoClass = new Produto();
            $produto = $produtoClass->findOrFail($id);

            echo json_encode($produto);
        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode(['success' => false, 'message' => $e->getMessage()]);
        }
    }
}