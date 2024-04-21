<?php

require_once 'Models/Venda/Venda.php';
require_once 'Models/Venda/ItemVenda.php';
require_once 'Config/Database.php';
require_once 'Validators/Venda/CreateVendaValidator.php';
require_once 'Validators/Venda/DeleteVendaValidator.php';
require_once 'Validators/ItemVenda/CreateItemVendaValidator.php';

class VendaController {
    public static function get(array $params = null) {
        try {
            $dataInicial = $params['dataInicial'] ?? null;
            $dataFinal = $params['dataFinal'] ?? null;

            $vendaClass = new Venda();
            if ($dataInicial && $dataFinal) {
                $vendas = $vendaClass->getBetween($dataInicial, $dataFinal);
            } else {
                $page = $params['page'] ?? 1;
                $vendas = $vendaClass->get($page);
            }

            foreach ($vendas as $key => $venda) {
                $vendas[$key]['itens'] = $vendaClass->retornaItensVenda($venda['id']);
            }

            echo json_encode($vendas);
        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode(['success' => false, 'message' => $e->getMessage()]);
        }
    }

    public static function create() {
        $dbConfig = DatabaseConfig::getInstance();
        
        try {
            $db = $dbConfig->getConnection();
            $db->beginTransaction();
            $data = json_decode(file_get_contents('php://input'));

            CreateVendaValidator::validate($data);

            $valorTotalFormatado = number_format($data->valorTotal, 2, '.', '');
            $valorTotalImpostoFormatado = number_format($data->valorTotalImposto, 2, '.', '');

            $vendaClass = new Venda();
            $vendaId = $vendaClass->create([
                'valor_total' => $valorTotalFormatado,
                'valor_total_imposto' => $valorTotalImpostoFormatado,
                'data' => date('Y-m-d H:i:s')
            ]);

            foreach ($data->itens as $item) {
                CreateItemVendaValidator::validate($item);

                $itemVendaClass = new ItemVenda();
                $itemVendaClass->create([
                    'produto_id' => $item->produtoId,
                    'quantidade' => $item->quantidade,
                    'valor_unitario' => $item->valorUnitario,
                    'valor_total' => $item->valorTotal,
                    'valor_imposto' => $item->valorImposto,
                    'venda_id' => $vendaId
                ]);
            }

            $db->commit();
            echo json_encode(['success' => true, 'message' => 'Venda criada com sucesso']);        
        } catch (Exception $e) {
            $db->rollBack();
            http_response_code(500);
            echo json_encode(['success' => false, 'message' => $e->getMessage()]);
        }
    }

    public static function delete($id) {
        $dbConfig = DatabaseConfig::getInstance();
        try {
            $db = $dbConfig->getConnection();
            $db->beginTransaction();

            DeleteVendaValidator::validate($id);

            $vendaClass = new Venda();
            $vendaClass->deletarItensVenda($id);
            $vendaClass->delete($id);

            $db->commit();
            echo json_encode(['success' => true, 'message' => 'Venda deletada com sucesso']);
        } catch (Exception $e) {
            $db->rollBack();
            http_response_code(500);
            echo json_encode(['success' => false, 'message' => $e->getMessage()]);
        }
    }
}