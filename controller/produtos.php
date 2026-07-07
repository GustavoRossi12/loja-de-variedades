<?php
require_once '../config/config.php';
require_once '../model/produto.php';

$method = $_SERVER['REQUEST_METHOD'];

switch ($method) {
    case 'GET':
        if (isset($_GET['action']) && $_GET['action'] === 'urgentes') {
            sendResponse(produto::urgentes());
        }
        if (isset($_GET['id'])) {
            $p = produto::buscarPorId($_GET['id']);
            if (!$p) sendError('Produto não encontrado', 404);
            sendResponse($p);
        }
        $busca = isset($_GET['busca']) ? $_GET['busca'] : '';
        $categoria = isset($_GET['categoria']) ? $_GET['categoria'] : '';
        sendResponse(produto::buscarTodos($busca, $categoria));
        break;

    case 'POST':
        $data = json_decode(file_get_contents('php://input'), true);
        if (empty($data['nome'])) sendError('Nome é obrigatório');
        $preco = isset($data['preco']) ? $data['preco'] : 0;
        $qtde_estoque = isset($data['qtde_estoque']) ? $data['qtde_estoque'] : 0;
        $id_categoria = isset($data['id_categoria']) ? $data['id_categoria'] : null;
        $id = produto::inserir($data['nome'], $preco, $qtde_estoque, $id_categoria);
        sendResponse(array('message' => 'Produto criado', 'id' => $id), 201);
        break;

    case 'PUT':
        if (empty($_GET['id'])) sendError('ID é obrigatório');
        $data = json_decode(file_get_contents('php://input'), true);
        if (empty($data['nome'])) sendError('Nome é obrigatório');
        $preco = isset($data['preco']) ? $data['preco'] : 0;
        $qtde_estoque = isset($data['qtde_estoque']) ? $data['qtde_estoque'] : 0;
        $id_categoria = isset($data['id_categoria']) ? $data['id_categoria'] : null;
        produto::atualizar($_GET['id'], $data['nome'], $preco, $qtde_estoque, $id_categoria);
        sendResponse(array('message' => 'Produto atualizado'));
        break;

    case 'DELETE':
        if (empty($_GET['id'])) sendError('ID é obrigatório');
        produto::deletar($_GET['id']);
        sendResponse(array('message' => 'Produto removido'));
        break;

    default:
        sendError('Método não permitido', 405);
}
