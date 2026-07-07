CREATE DATABASE IF NOT EXISTS loja_variedades CHARACTER SET latin1;
USE loja_variedades;

CREATE TABLE IF NOT EXISTS categoria (
    id_categoria INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(100) NOT NULL,
    descricao TEXT
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

CREATE TABLE IF NOT EXISTS produto (
    id_produto INT AUTO_INCREMENT PRIMARY KEY,
    id_categoria INT DEFAULT NULL,
    nome VARCHAR(150) NOT NULL,
    preco DECIMAL(10,2) NOT NULL,
    qtde_estoque INT DEFAULT 0,
    KEY id_categoria (id_categoria),
    FOREIGN KEY (id_categoria) REFERENCES categoria(id_categoria)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

CREATE TABLE IF NOT EXISTS cliente (
    id_cliente INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(150) NOT NULL,
    cpf VARCHAR(14) DEFAULT NULL,
    pontos_fidelidade INT DEFAULT 0,
    UNIQUE KEY cpf (cpf)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

CREATE TABLE IF NOT EXISTS venda (
    id_venda INT AUTO_INCREMENT PRIMARY KEY,
    id_cliente INT DEFAULT NULL,
    data_venda DATETIME DEFAULT NULL,
    valor_total DECIMAL(10,2) DEFAULT 0.00,
    KEY id_cliente (id_cliente),
    FOREIGN KEY (id_cliente) REFERENCES cliente(id_cliente)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

CREATE TABLE IF NOT EXISTS item_venda (
    id_item INT AUTO_INCREMENT PRIMARY KEY,
    id_venda INT NOT NULL,
    id_produto INT NOT NULL,
    quantidade INT NOT NULL,
    preco_unitario DECIMAL(10,2) NOT NULL,
    subtotal DECIMAL(10,2) DEFAULT 0.00,
    KEY id_venda (id_venda),
    KEY id_produto (id_produto),
    FOREIGN KEY (id_venda) REFERENCES venda(id_venda),
    FOREIGN KEY (id_produto) REFERENCES produto(id_produto)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

DELIMITER ;;
CREATE TRIGGER tg_insere_item_venda
BEFORE INSERT ON item_venda
FOR EACH ROW
SET NEW.subtotal = NEW.quantidade * NEW.preco_unitario;;

CREATE TRIGGER tg_apos_insere_item
AFTER INSERT ON item_venda
FOR EACH ROW
BEGIN
    UPDATE produto SET qtde_estoque = qtde_estoque - NEW.quantidade
    WHERE id_produto = NEW.id_produto;

    UPDATE venda SET valor_total = valor_total + NEW.subtotal
    WHERE id_venda = NEW.id_venda;
END;;
DELIMITER ;
