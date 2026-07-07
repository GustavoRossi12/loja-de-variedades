<?php

require_once __DIR__ . '/../config/database.php';

class cliente {
    public static function buscarTodos($busca = '') {
        global $conexao;
        if ($busca) {
            $sql = "SELECT * FROM cliente WHERE nome LIKE '%$busca%' OR cpf LIKE '%$busca%' ORDER BY nome";
        } else {
            $sql = "SELECT * FROM cliente ORDER BY nome";
        }
        $resultado = $conexao->query($sql);
        return fetchAll($resultado);
    }

    public static function buscarPorId($id) {
        global $conexao;
        $sql = "SELECT * FROM cliente WHERE id_cliente = $id";
        $resultado = $conexao->query($sql);
        return $resultado->fetch_assoc();
    }

    public static function buscarPorCpf($cpf) {
        global $conexao;
        $sql = "SELECT * FROM cliente WHERE cpf = '$cpf'";
        $resultado = $conexao->query($sql);
        return $resultado->fetch_assoc();
    }

    public static function inserir($nome, $cpf) {
        global $conexao;
        $cpf_val = $cpf ? "'$cpf'" : 'NULL';
        $sql = "INSERT INTO cliente(nome, cpf) VALUES('$nome', $cpf_val)";
        $conexao->query($sql);
        return $conexao->insert_id;
    }

    public static function atualizar($id, $nome, $cpf) {
        global $conexao;
        $cpf_val = $cpf ? "'$cpf'" : 'NULL';
        $sql = "UPDATE cliente SET nome = '$nome', cpf = $cpf_val WHERE id_cliente = $id";
        $conexao->query($sql);
    }

    public static function deletar($id) {
        global $conexao;
        $sql = "DELETE FROM cliente WHERE id_cliente = $id";
        $conexao->query($sql);
    }
}
