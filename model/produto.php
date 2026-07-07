<?php

require_once __DIR__ . '/../config/database.php';

class produto {
    public static function buscarTodos($busca = '', $categoria = '') {
        global $conexao;
        $sql = "SELECT p.*, c.nome AS categoria_nome FROM produto p LEFT JOIN categoria c ON p.id_categoria = c.id_categoria WHERE 1=1";
        if ($busca) {
            $sql .= " AND p.nome LIKE '%$busca%'";
        }
        if ($categoria) {
            $sql .= " AND p.id_categoria = $categoria";
        }
        $sql .= " ORDER BY p.nome";
        $resultado = $conexao->query($sql);
        return fetchAll($resultado);
    }

    public static function buscarPorId($id) {
        global $conexao;
        $sql = "SELECT p.*, c.nome AS categoria_nome FROM produto p LEFT JOIN categoria c ON p.id_categoria = c.id_categoria WHERE p.id_produto = $id";
        $resultado = $conexao->query($sql);
        return $resultado->fetch_assoc();
    }

    public static function inserir($nome, $preco, $qtde_estoque, $id_categoria) {
        global $conexao;
        $id_categoria_val = $id_categoria ? $id_categoria : 'NULL';
        $sql = "INSERT INTO produto(nome, preco, qtde_estoque, id_categoria) VALUES('$nome', $preco, $qtde_estoque, $id_categoria_val)";
        $conexao->query($sql);
        return $conexao->insert_id;
    }

    public static function atualizar($id, $nome, $preco, $qtde_estoque, $id_categoria) {
        global $conexao;
        $id_categoria_val = $id_categoria ? $id_categoria : 'NULL';
        $sql = "UPDATE produto SET nome = '$nome', preco = $preco, qtde_estoque = $qtde_estoque, id_categoria = $id_categoria_val WHERE id_produto = $id";
        $conexao->query($sql);
    }

    public static function deletar($id) {
        global $conexao;
        $sql = "DELETE FROM produto WHERE id_produto = $id";
        $conexao->query($sql);
    }

    public static function urgentes() {
        global $conexao;
        $sql = "SELECT p.*, c.nome AS categoria_nome FROM produto p LEFT JOIN categoria c ON p.id_categoria = c.id_categoria WHERE p.qtde_estoque <= 5 ORDER BY p.qtde_estoque ASC";
        $resultado = $conexao->query($sql);
        return fetchAll($resultado);
    }
}
