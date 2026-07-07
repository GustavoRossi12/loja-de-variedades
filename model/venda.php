<?php

require_once __DIR__ . '/../config/database.php';

class venda {
    public static function listar($limite = 50) {
        global $conexao;
        $sql = "SELECT v.*, c.nome AS cliente_nome,
                GROUP_CONCAT(CONCAT(p.nome, ' (', iv.quantidade, 'x)') SEPARATOR ', ') AS produtos
                FROM venda v
                LEFT JOIN cliente c ON v.id_cliente = c.id_cliente
                LEFT JOIN item_venda iv ON v.id_venda = iv.id_venda
                LEFT JOIN produto p ON iv.id_produto = p.id_produto
                GROUP BY v.id_venda
                ORDER BY v.data_venda DESC
                LIMIT $limite";
        $resultado = $conexao->query($sql);
        return fetchAll($resultado);
    }

    public static function inserir($id_cliente, $itens) {
        global $conexao;

        $id_cliente_val = $id_cliente ? $id_cliente : 'NULL';
        $sql = "INSERT INTO venda(id_cliente, data_venda) VALUES($id_cliente_val, NOW())";
        $conexao->query($sql);
        $id_venda = $conexao->insert_id;

        foreach ($itens as $item) {
            $sql = "INSERT INTO item_venda(id_venda, id_produto, quantidade, preco_unitario)
                    VALUES($id_venda, {$item['id_produto']}, {$item['quantidade']}, {$item['preco']})";
            $conexao->query($sql);
        }

        $sql = "SELECT * FROM venda WHERE id_venda = $id_venda";
        $venda = $conexao->query($sql)->fetch_assoc();

        return $venda;
    }

    public static function dashboard() {
        global $conexao;
        $dados = array();

        $sql = "SELECT COUNT(*) AS total, COALESCE(SUM(valor_total), 0) AS receita FROM venda WHERE DATE(data_venda) = CURDATE()";
        $dados['hoje'] = $conexao->query($sql)->fetch_assoc();

        $sql = "SELECT COUNT(*) AS total, COALESCE(SUM(valor_total), 0) AS receita FROM venda WHERE MONTH(data_venda) = MONTH(CURDATE()) AND YEAR(data_venda) = YEAR(CURDATE())";
        $dados['mes'] = $conexao->query($sql)->fetch_assoc();

        $sql = "SELECT COUNT(*) AS total FROM produto WHERE qtde_estoque <= 5";
        $tmp = $conexao->query($sql)->fetch_assoc();
        $dados['estoque_critico'] = $tmp['total'];

        $sql = "SELECT COUNT(*) AS total FROM cliente";
        $tmp = $conexao->query($sql)->fetch_assoc();
        $dados['total_clientes'] = $tmp['total'];

        $sql = "SELECT p.nome, SUM(iv.quantidade) AS qtd FROM item_venda iv JOIN produto p ON iv.id_produto = p.id_produto GROUP BY iv.id_produto ORDER BY qtd DESC LIMIT 1";
        $dados['mais_vendido'] = $conexao->query($sql)->fetch_assoc();

        $sql = "SELECT DATE(data_venda) AS dia, COALESCE(SUM(valor_total), 0) AS total FROM venda WHERE data_venda >= DATE_SUB(CURDATE(), INTERVAL 6 DAY) GROUP BY DATE(data_venda) ORDER BY dia ASC";
        $resultado = $conexao->query($sql);
        $dados['grafico_semana'] = fetchAll($resultado);

        return $dados;
    }
}
