<?php

require_once __DIR__ . '/../config/database.php';

class categoria {
    public static function buscarTodos() {
        global $conexao;
        $sql = "SELECT c.*, (SELECT COUNT(*) FROM produto WHERE id_categoria = c.id_categoria) AS total_produtos FROM categoria c ORDER BY c.nome";
        $resultado = $conexao->query($sql);
        return fetchAll($resultado);
    }

    public static function buscarPorId($id) {
        global $conexao;
        $sql = "SELECT * FROM categoria WHERE id_categoria = $id";
        $resultado = $conexao->query($sql);
        return $resultado->fetch_assoc();
    }

    public static function inserir($nome, $descricao) {
        global $conexao;
        $sql = "INSERT INTO categoria(nome, descricao) VALUES('$nome', '$descricao')";
        $conexao->query($sql);
        return $conexao->insert_id;
    }

    public static function atualizar($id, $nome, $descricao) {
        global $conexao;
        $sql = "UPDATE categoria SET nome = '$nome', descricao = '$descricao' WHERE id_categoria = $id";
        $conexao->query($sql);
    }

    public static function deletar($id) {
        global $conexao;
        $sql = "DELETE FROM categoria WHERE id_categoria = $id";
        $conexao->query($sql);
    }
}
