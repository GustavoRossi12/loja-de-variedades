<?php

require_once __DIR__ . '/database.php';

header('Content-Type: application/json; charset=utf-8');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    setStatus(200);
    exit;
}

function setStatus($codigo) {
    $mensagens = array(
        200 => 'OK',
        201 => 'Created',
        400 => 'Bad Request',
        404 => 'Not Found',
        405 => 'Method Not Allowed'
    );
    $texto = isset($mensagens[$codigo]) ? $mensagens[$codigo] : '';
    header("HTTP/1.1 $codigo $texto");
}

function sendResponse($data, $status = 200) {
    setStatus($status);
    echo json_encode($data);
    exit;
}

function sendError($mensagem, $status = 400) {
    setStatus($status);
    echo json_encode(array('error' => $mensagem));
    exit;
}
