<?php

require_once 'Config/Database.php';
require_once 'Controllers/Produto/ProdutoController.php';
require_once 'Controllers/Venda/VendaController.php';
require_once 'Controllers/Produto/TipoProdutoController.php';
require_once 'Utils/Utils.php';

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization");

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit();
}

$method = $_SERVER['REQUEST_METHOD'];
$uri = $_SERVER['REQUEST_URI'];

$uri = explode('?', $uri)[0];
$getParams = explode('?', $_SERVER['REQUEST_URI'])[1] ?? null;
if ($getParams != null) {
    $getParams = parseGetParams($getParams);
}

switch (true) {
    case str_contains($uri, '/api/produtos'):
        switch ($method) {
            case 'GET':
                $params = str_replace('/api/produtos', '', $uri);
                $params = str_replace('/', '', $params);
                if ($params != null) {
                    ProdutoController::show($params);
                } else {
                    ProdutoController::get($getParams);
                }
                break;
            case 'POST':
                ProdutoController::create();
                break;
            case 'PUT':
                ProdutoController::update();
                break;
            case 'DELETE':
                $params = str_replace('/api/produtos', '', $uri);
                $params = str_replace('/', '', $params);
                ProdutoController::delete($params);
                break;
            default:
                http_response_code(405);
                echo json_encode(['message' => 'Método não permitido']);
                break;
        }
        
        break;
    case str_contains($uri, '/api/vendas'):
        switch ($method) {
            case 'GET':
                VendaController::get($getParams);
                break;
            case 'POST':
                VendaController::create();
                break;
            case 'DELETE':
                $params = str_replace('/api/vendas', '', $uri);
                $params = str_replace('/', '', $params);
                VendaController::delete($params);
                break;
            default:
                http_response_code(405);
                echo json_encode(['message' => 'Método não permitido']);
                break;
        }
        
        break;
    case str_contains($uri, '/api/tipos-produto'):
        switch ($method) {
            case 'GET':
                $params = str_replace('/api/tipos-produto', '', $uri);
                $params = str_replace('/', '', $params);
                if ($params != null) {
                    TipoProdutoController::show($params);
                } else {
                    TipoProdutoController::get($getParams);
                }
                break;
            case 'POST':
                TipoProdutoController::create();
                break;
            case 'PUT':
                TipoProdutoController::update();
                break;
            case 'DELETE':
                $params = str_replace('/api/tipos-produto', '', $uri);
                $params = str_replace('/', '', $params);
                TipoProdutoController::delete($params);
                break;
            default:
                http_response_code(405);
                echo json_encode(['message' => 'Método não permitido']);
                break;
        }
        
        break;
    default:
        http_response_code(404);
        echo json_encode(['message' => 'Rota não encontrada']);
        break;
}
