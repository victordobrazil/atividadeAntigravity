create database loja;
use loja;

create table produtos (
id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
nome VARCHAR(30) NOT NULL,
descricao VARCHAR(60) NOT NULL,
quantidade INT NOT NULL,
preco DECIMAL(10,2) NOT NULL
);

SELECT*FROM produtos;

INSERT INTO produtos (nome, descricao, quantidade, preco) VALUES 
('Teclado USB', 'Teclado padrao USB ABNT2', 15, 120),
('Mouse Optico', 'Mouse optico 1200 DPI', 30, 45),
('Monitor 21', 'Monitor LED 21 polegadas', 8, 850),
('Pendrive 32GB', 'Pendrive USB 3.0 32GB', 50, 60),
('Cabo HDMI', 'Cabo HDMI 2 metros', 40, 35);


