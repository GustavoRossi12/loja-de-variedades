<?php
require_once '../config/config.php';
require_once '../model/categoria.php';

$method = $_SERVER['REQUEST_METHOD'];

switch ($method) {
    case 'GET':
        if (isset($_GET['id'])) {
            $cat = categoria::buscarPorId($_GET['id']);
            if (!$cat) sendError('Categoria não encontrada', 404);
            sendResponse($cat);
        }
        sendResponse(categoria::buscarTodos());
        break;

    case 'POST':
        $data = json_decode(file_get_contents('php://input'), true);
        if (empty($data['nome'])) sendError('Nome é obrigatório');
        $descricao = isset($data['descricao']) ? $data['descricao'] : '';
        $id = categoria::inserir($data['nome'], $descricao);
        sendResponse(array('message' => 'Categoria criada', 'id' => $id), 201);
        break;

    case 'PUT':
        if (empty($_GET['id'])) sendError('ID é obrigatório');
        $data = json_decode(file_get_contents('php://input'), true);
        if (empty($data['nome'])) sendError('Nome é obrigatório');
        $descricao = isset($data['descricao']) ? $data['descricao'] : '';
        categoria::atualizar($_GET['id'], $data['nome'], $descricao);
        sendResponse(array('message' => 'Categoria atualizada'));
        break;

    case 'DELETE':
        if (empty($_GET['id'])) sendError('ID é obrigatório');
        categoria::deletar($_GET['id']);
        sendResponse(array('message' => 'Categoria removida'));
        break;

    default:
        sendError('Método não permitido', 405);
}
