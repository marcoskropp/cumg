create database cumg;
use cumg;

create table produto(
	id int not null auto_increment primary key,
    nome varchar(50),
    preco decimal(10,2),
    quantidade int,
    descricao varchar(500),
    categoria varchar(50),
    marca varchar(100),
    avaliacao float
);

create table media(
	id int not null auto_increment primary key,
    tipo varchar(50),
    nome varchar(50),
    principal int,
    idProduto int
);

create table cliente(
	id int not null auto_increment primary key,
    nome varchar(100),
    cpf varchar(11),
    dataNascimento date,
    email varchar(100),
    senha varchar(100)
);

create table endereco(
	id int not null auto_increment primary key,
	idCliente int,
    destinatario varchar(100),
    cep double,
    endereco varchar(100),
    numero int,
    complemento varchar(100),
    bairro varchar(100),
    cidade varchar(50),
    estado varchar(50),
    tipo varchar(50)
    );

create table pedido(
	id int not null auto_increment primary key,
    idCliente int,
    dataPedido datetime,
    situacao varchar(50),
    idEndereco int
);

create table pedidoProduto(
    idProduto int,
    quantidade int,
    idPedido int
);

create table carrinho(
	id int not null auto_increment primary key,
    idCliente int,
    idProduto int,
    quantidade int
);

create table desejo(
	id int not null auto_increment primary key,
    idCliente int,
    idProduto int
);

create table administrador(
	id int not null auto_increment primary key,
	usuario varchar(100),
    senha varchar(100)
);

create table opiniao(
	id int not null auto_increment primary key,
	nome varchar(100),
    email varchar(100),
    texto varchar(500)
);

create table auditaProduto(
	id int not null auto_increment primary key,
    usuario varchar(50),
    acao varchar(50),
    produto varchar(50)
);

create table auditaPedido(
	id int not null auto_increment primary key,
    idCliente int,
    idProduto int,
    quantidade int,
    preco decimal(10,2)
);

INSERT INTO cliente (nome,cpf,dataNascimento,email,senha) VALUES ('John Doe','32165498721','1999-06-16','john.doe@gmail.com','d033e22ae348aeb5660fc2140aec35850c4da997');
INSERT INTO cliente (nome,cpf,dataNascimento,email,senha) VALUES ('Marcos Kropp','32165498721','1999-06-16','marcos.kropp@ghotmail.com','d033e22ae348aeb5660fc2140aec35850c4da997');

INSERT INTO administrador (usuario,senha) VALUES ('admin','d033e22ae348aeb5660fc2140aec35850c4da997');

INSERT INTO produto (id,nome,preco,quantidade,descricao,categoria,marca,avaliacao) VALUES (1,'Computador','1499.99','42','Lorem Ipsum Dolor Sit Amet','computer','Microsoft','4.5');
INSERT INTO produto (id,nome,preco,quantidade,descricao,categoria,marca,avaliacao) VALUES (2,'Smartphone','889','16','Lorem Ipsum Dolor Sit Amet','smartphone','Asus','3.5');
INSERT INTO produto (id,nome,preco,quantidade,descricao,categoria,marca,avaliacao) VALUES (3,'Televisão','3500','403','Lorem Ipsum Dolor Sit Amet','tv','LG','5');
INSERT INTO produto (id,nome,preco,quantidade,descricao,categoria,marca,avaliacao) VALUES (4,'Tablet','384.99','30','Lorem Ipsum Dolor Sit Amet','tablet','Amazon','4');
INSERT INTO produto (id,nome,preco,quantidade,descricao,categoria,marca,avaliacao) VALUES (5,'Computador 4k','2350.99','25','Lorem Ipsum Dolor Sit Amet','computer','Alienware','4');
INSERT INTO produto (id,nome,preco,quantidade,descricao,categoria,marca,avaliacao) VALUES (6,'Smartphone 4k','1250.50','13','Lorem Ipsum Dolor Sit Amet','smartphone','Xiaomi','3');
INSERT INTO produto (id,nome,preco,quantidade,descricao,categoria,marca,avaliacao) VALUES (7,'Televisão 4k','3215.99','40','Lorem Ipsum Dolor Sit Amet','tv','Philips','5.5');
INSERT INTO produto (id,nome,preco,quantidade,descricao,categoria,marca,avaliacao) VALUES (8,'Tablet 4k','999.99','37','Lorem Ipsum Dolor Sit Amet','tablet','Samsumg','4.5');
INSERT INTO produto (id,nome,preco,quantidade,descricao,categoria,marca,avaliacao) VALUES (9,'Computador 3d','1352.99','42','Lorem Ipsum Dolor Sit Amet','computer','Apple','4.5');
INSERT INTO produto (id,nome,preco,quantidade,descricao,categoria,marca,avaliacao) VALUES (10,'Smartphone 3d','450.05','16','Lorem Ipsum Dolor Sit Amet','smartphone','LG','3.5');
INSERT INTO produto (id,nome,preco,quantidade,descricao,categoria,marca,avaliacao) VALUES (11,'Televisão 3d','3050','403','Lorem Ipsum Dolor Sit Amet','tv','Philco','5');
INSERT INTO produto (id,nome,preco,quantidade,descricao,categoria,marca,avaliacao) VALUES (12,'Tablet 3d','800.99','30','Lorem Ipsum Dolor Sit Amet','tablet','LG','4');
INSERT INTO produto (id,nome,preco,quantidade,descricao,categoria,marca,avaliacao) VALUES (13,'Computador Full Hd','2000.99','25','Lorem Ipsum Dolor Sit Amet','computer','Philips','4');
INSERT INTO produto (id,nome,preco,quantidade,descricao,categoria,marca,avaliacao) VALUES (14,'Smartphone Full Hd','1050.50','13','Lorem Ipsum Dolor Sit Amet','smartphone','Apple','3');
INSERT INTO produto (id,nome,preco,quantidade,descricao,categoria,marca,avaliacao) VALUES (15,'Televisão Full Hd','2505.99','40','Lorem Ipsum Dolor Sit Amet','tv','Sony','5.5');
INSERT INTO produto (id,nome,preco,quantidade,descricao,categoria,marca,avaliacao) VALUES (16,'Tablet Full Hd','300.99','37','Lorem Ipsum Dolor Sit Amet','tablet','Philips','4.5');


INSERT INTO media (tipo,nome,principal,idProduto) VALUES ('png','computer.png',1,1);
INSERT INTO media (tipo,nome,principal,idProduto) VALUES ('png','smartphone.png',1,2);
INSERT INTO media (tipo,nome,principal,idProduto) VALUES ('png','tv.png',1,3);
INSERT INTO media (tipo,nome,principal,idProduto) VALUES ('png','tablet.png',1,4);
INSERT INTO media (tipo,nome,principal,idProduto) VALUES ('jpg','computer1.jpg',1,5);
INSERT INTO media (tipo,nome,principal,idProduto) VALUES ('jpg','smartphone1.jpg',1,6);
INSERT INTO media (tipo,nome,principal,idProduto) VALUES ('jpg','tv1.jpg',1,7);
INSERT INTO media (tipo,nome,principal,idProduto) VALUES ('jpg','tablet1.jpg',1,8);

INSERT INTO media (tipo,nome,principal,idProduto) VALUES ('jpg','computer2.jpg',1,9);
INSERT INTO media (tipo,nome,principal,idProduto) VALUES ('jpg','smartphone2.jpg',1,10);
INSERT INTO media (tipo,nome,principal,idProduto) VALUES ('jpg','tv2.jpg',1,11);
INSERT INTO media (tipo,nome,principal,idProduto) VALUES ('jpg','tablet2.jpg',1,12);
INSERT INTO media (tipo,nome,principal,idProduto) VALUES ('jpg','computer3.jpg',1,13);
INSERT INTO media (tipo,nome,principal,idProduto) VALUES ('jpg','smartphone3.jpg',1,14);
INSERT INTO media (tipo,nome,principal,idProduto) VALUES ('jpg','tv3.jpg',1,15);
INSERT INTO media (tipo,nome,principal,idProduto) VALUES ('jpg','tablet3.jpg',1,16);

ALTER TABLE media ADD FOREIGN KEY (idProduto) REFERENCES produto (id) ON UPDATE CASCADE ON DELETE CASCADE;
ALTER TABLE pedido ADD FOREIGN KEY (idCliente) REFERENCES cliente (id) ON UPDATE CASCADE ON DELETE CASCADE;
ALTER TABLE pedido ADD FOREIGN KEY (idEndereco) REFERENCES endereco (id) ON UPDATE CASCADE ON DELETE CASCADE;
ALTER TABLE pedidoProduto ADD FOREIGN KEY (idPedido) REFERENCES pedido (id) ON UPDATE CASCADE ON DELETE CASCADE;
ALTER TABLE desejo ADD FOREIGN KEY (idProduto) REFERENCES produto (id) ON UPDATE CASCADE ON DELETE CASCADE;
ALTER TABLE desejo ADD FOREIGN KEY (idCliente) REFERENCES cliente (id) ON UPDATE CASCADE ON DELETE CASCADE;
ALTER TABLE carrinho ADD FOREIGN KEY (idProduto) REFERENCES produto (id) ON UPDATE CASCADE ON DELETE CASCADE;
ALTER TABLE carrinho ADD FOREIGN KEY (idCliente) REFERENCES cliente (id) ON UPDATE CASCADE ON DELETE CASCADE;
ALTER TABLE endereco ADD FOREIGN KEY (idCliente) REFERENCES cliente (id) ON UPDATE CASCADE ON DELETE CASCADE;

DELIMITER $
CREATE TRIGGER reduzEstoque AFTER INSERT ON carrinho
FOR EACH ROW
	BEGIN
		UPDATE produto AS T1 INNER JOIN carrinho AS T2 ON T1.id = T2.idProduto
        SET T1.quantidade = T1.quantidade - T2.quantidade;
END $
DELIMITER ;

DELIMITER $
CREATE TRIGGER aumentaEstoque BEFORE DELETE ON carrinho
FOR EACH ROW
	BEGIN
		UPDATE produto AS T1 INNER JOIN carrinho AS T2 ON T1.id = T2.idProduto
        SET T1.quantidade = T1.quantidade + old.quantidade;
END $
DELIMITER ;