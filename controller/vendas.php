<?php
require_once '../config/config.php';
require_once '../model/venda.php';
require_once '../model/produto.php';

$method = $_SERVER['REQUEST_METHOD'];

switch ($method) {
    case 'GET':
        if (isset($_GET['action']) && $_GET['action'] === 'dashboard') {
            sendResponse(venda::dashboard());
        }
        $limite = isset($_GET['limite']) ? $_GET['limite'] : 50;
        sendResponse(venda::listar($limite));
        break;

    case 'POST':
        $data = json_decode(file_get_contents('php://input'), true);

        if (empty($data['itens']) || !is_array($data['itens'])) {
            if (!empty($data['id_produto'])) {
                $data['itens'] = array(array(
                    'id_produto' => $data['id_produto'],
                    'quantidade' => $data['quantidade']
                ));
            } else {
                sendError('Produto é obrigatório');
            }
        }

        if (empty($data['itens'])) sendError('Nenhum item na venda');

        foreach ($data['itens'] as &$item) {
            if (empty($item['id_produto'])) sendError('Produto é obrigatório');
            if (empty($item['quantidade']) || $item['quantidade'] < 1) sendError('Quantidade inválida');

            $p = produto::buscarPorId($item['id_produto']);
            if (!$p) sendError('Produto não encontrado', 404);
            if ($p['qtde_estoque'] < $item['quantidade']) {
                sendError("Estoque insuficiente para {$p['nome']}");
            }
            $item['preco'] = $p['preco'];
        }

        $id_cliente = isset($data['id_cliente']) ? $data['id_cliente'] : null;
        $venda = venda::inserir($id_cliente, $data['itens']);

        sendResponse(array(
            'message' => 'Venda realizada',
            'id_venda' => $venda['id_venda'],
            'valor_total' => $venda['valor_total']
        ), 201);
        break;

    default:
        sendError('Método não permitido', 405);
}
