<?php
require_once '../config/config.php';
require_once '../model/cliente.php';

$method = $_SERVER['REQUEST_METHOD'];

switch ($method) {
    case 'GET':
        if (isset($_GET['cpf'])) {
            $cli = cliente::buscarPorCpf($_GET['cpf']);
            if (!$cli) sendError('Cliente não encontrado', 404);
            sendResponse($cli);
        }
        $busca = isset($_GET['busca']) ? $_GET['busca'] : '';
        sendResponse(cliente::buscarTodos($busca));
        break;

    case 'POST':
        $data = json_decode(file_get_contents('php://input'), true);
        if (empty($data['nome'])) sendError('Nome é obrigatório');
        $cpf = isset($data['cpf']) ? $data['cpf'] : '';
        $id = cliente::inserir($data['nome'], $cpf);
        sendResponse(array('message' => 'Cliente cadastrado', 'id' => $id), 201);
        break;

    case 'PUT':
        if (empty($_GET['id'])) sendError('ID é obrigatório');
        $data = json_decode(file_get_contents('php://input'), true);
        if (empty($data['nome'])) sendError('Nome é obrigatório');
        $cpf = isset($data['cpf']) ? $data['cpf'] : '';
        cliente::atualizar($_GET['id'], $data['nome'], $cpf);
        sendResponse(array('message' => 'Cliente atualizado'));
        break;

    case 'DELETE':
        if (empty($_GET['id'])) sendError('ID é obrigatório');
        cliente::deletar($_GET['id']);
        sendResponse(array('message' => 'Cliente removido'));
        break;

    default:
        sendError('Método não permitido', 405);
}
