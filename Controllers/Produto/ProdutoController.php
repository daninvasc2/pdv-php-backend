<?php

require_once 'Models/Produto/Produto.php';
require_once 'Validators/Produto/CreateProdutoValidator.php';
require_once 'Validators/Produto/DeleteProdutoValidator.php';
require_once 'Validators/Produto/ShowProdutoValidator.php';
require_once 'Validators/Produto/UpdateProdutoValidator.php';

class ProdutoController {
    public static function get(array $params = null): void {
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

    public static function create(): void {
        try {
            $data = json_decode(file_get_contents('php://input'));
            CreateProdutoValidator::validate($data);

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

    public static function update(): void {
        try {
            $data = json_decode(file_get_contents('php://input'));
            UpdateProdutoValidator::validate($data);

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

    public static function delete(int $id): void {
        try {
            DeleteProdutoValidator::validate($id);
            $produtoClass = new Produto();
            $produtoClass->delete($id);

            echo json_encode(['success' => true, 'message' => 'Produto deletado com sucesso']);
        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode(['success' => false, 'message' => $e->getMessage()]);
        }
    }

    public static function show(int $id): void {
        try {
            ShowProdutoValidator::validate($id);

            $produtoClass = new Produto();
            $produto = $produtoClass->findOrFail($id);

            echo json_encode($produto);
        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode(['success' => false, 'message' => $e->getMessage()]);
        }
    }
}