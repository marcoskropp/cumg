<?php

$sair = filter_input(INPUT_GET, "sair");

require_once '../../Config.inc.php';
session_start();


$read = new Read();

$usuario = $_SESSION['usuario'];

if (!(isset($usuario))) {
    session_destroy();
    header("Location: ../produto/homeController.php");
}

if ($_SESSION['nivel'] != "admin") {
    session_destroy();
    header("Location: ../produto/homeController.php");
}
$sort = filter_input_array(INPUT_GET, FILTER_DEFAULT);

$read->fullRead("SELECT produto.id, produto.nome, produto.preco, produto.marca, produto.quantidade, media.nome AS imagem FROM produto INNER JOIN media ON produto.id = media.idProduto ORDER BY produto.id DESC");
$array = [];
$array = $read->getResult();
$html = "";
$arrayVazio = [];

View::load("./view/templates/navAdmin.tpl.html");
$nav = View::show($arrayVazio);

View::load("./view/templates/listaAdmin.tpl.html");
foreach ($array as $value) {
    $html .= View::show($value);
}

View::load("./view/templates/listaImprimirRelatorio.tpl.html");
$array = [];
$html = '';

if ($sort['sort'] == "valor") {
    $read->fullRead("SELECT pedido.id AS 'idPedido', cliente.cpf, produto.nome AS 'nomeProduto', produto.preco, pedidoProduto.quantidade, pedido.dataPedido, pedido.situacao, (produto.preco * pedidoProduto.quantidade) AS 'total' FROM pedido INNER JOIN cliente ON pedido.idCliente = cliente.id INNER JOIN pedidoProduto ON pedido.id = pedidoProduto.idPedido INNER JOIN produto ON pedidoProduto.idProduto = produto.id ORDER BY total DESC");
} elseif ($sort['sort'] == "data" && $sort['dataInicial'] != null && $sort['dataFinal'] != null) {
    $read->fullRead("SELECT pedido.id AS 'idPedido', cliente.cpf, produto.nome AS 'nomeProduto', produto.preco, pedidoProduto.quantidade, pedido.dataPedido, pedido.situacao, (produto.preco * pedidoProduto.quantidade) AS 'total' FROM pedido INNER JOIN cliente ON pedido.idCliente = cliente.id INNER JOIN pedidoProduto ON pedido.id = pedidoProduto.idPedido INNER JOIN produto ON pedidoProduto.idProduto = produto.id WHERE pedido.dataPedido BETWEEN :dataInicial AND :dataFinal", "dataInicial={$sort['dataInicial']}&dataFinal={$sort['dataFinal']}");
} else {
    $read->fullRead("SELECT pedido.id AS 'idPedido', cliente.cpf, produto.nome AS 'nomeProduto', produto.preco, pedidoProduto.quantidade, pedido.dataPedido, pedido.situacao, (produto.preco * pedidoProduto.quantidade) AS 'total' FROM pedido INNER JOIN cliente ON pedido.idCliente = cliente.id INNER JOIN pedidoProduto ON pedido.id = pedidoProduto.idPedido INNER JOIN produto ON pedidoProduto.idProduto = produto.id");
}
$array = $read->getResult();

foreach ($array as $row) {
    $html .= View::show($row);
}

View::load("./view/imprimirRelatorio.html");
$array = [];
$array['listaImprimirRelatorio'] = $html;
$array['nav'] = $nav;
echo View::show($array);
