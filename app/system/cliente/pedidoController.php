<?php

require_once '../../Config.inc.php';
$create = new Create;
$read = new Read;
$delete = new Delete;
session_start();
$sair = filter_input(INPUT_GET, 'sair');
if (!(isset($_SESSION['id']))) {
    session_destroy();
    header("Location: ../produto/homeController.php");
}
$idCliente = $_SESSION['id'] ?? null;
if ($sair == "sair") {
    session_destroy();
    header("Location: ../produto/homeController.php");
}

$arrayVazio = [];
$arrayCliente = [];
$arrayCarrinho = [];

View::load("../templates/navbarProduto.tpl.html");
$read->exeRead("cliente", "WHERE id = :id", "id={$idCliente}");
$array = $read->getResult();
$nomeCliente = $array[0]['nome'] ?? null;
unset($array[0]['nome']);
$array[0]['nomeCliente'] = $nomeCliente;
$array[0]['email'] = $array[0]['email'] ?? null;
$read->fullRead("SELECT produto.id, produto.nome AS 'nomeProduto', carrinho.quantidade, produto.preco, produto.categoria FROM carrinho INNER JOIN produto ON carrinho.idProduto = produto.id WHERE carrinho.idCliente = :idCliente", "idCliente={$idCliente}");
$arrayCarrinho = $read->getResult();
$html = '';
foreach ($arrayCarrinho as $value) {
    $value['nomeProduto'] = strlen($value['nomeProduto']) > 25 ? substr($value['nomeProduto'], 0, strrpos(substr($value['nomeProduto'], 0, 25), ' ')) . '...' : $value['nomeProduto'];
    $html .= View::show($value);
}
$arr['navbarProduto'] = $html;
$array[0]['navbarProduto'] = $arr['navbarProduto'];
if (isset($_SESSION['id'])) {
    $array[0]['displayAccount'] = "block";
    $array[0]['displayLogin'] = "none";
} else {
    $array[0]['displayAccount'] = "none";
    $array[0]['displayLogin'] = "block";
}
View::load("../templates/navbar.tpl.html");
$navbar = View::show($array[0]);

$read->fullRead("SELECT id FROM pedido WHERE idCliente = :idCliente ORDER BY id DESC", "idCliente={$idCliente}");
$idPedido = $read->getResult();

$pedido = '';
foreach ($idPedido as $key => $value) {
    $array = [];
    $htmlProduto = '';
    View::load("./view/templates/listaPedidoProduto.tpl.html");
    $read->fullRead("SELECT pedido.id AS 'idPedido', pedidoProduto.quantidade, produto.id, produto.nome AS 'nomeProduto', produto.preco, produto.categoria, pedido.dataPedido, pedido.situacao, media.nome AS 'image' FROM pedido INNER JOIN pedidoProduto ON pedido.id = pedidoProduto.idPedido INNER JOIN produto ON pedidoProduto.idProduto = produto.id INNER JOIN media ON produto.id = media.idProduto WHERE pedido.id = :idPedido AND media.principal = 1", "idPedido={$value['id']}");
    $array = $read->getResult();
    foreach ($array as $row)
        $htmlProduto .= View::show($row);
    $arrayListaPedido['listaProduto'] = $htmlProduto;
    $arrayListaPedido['dataPedido'] = date_format(date_create($row['dataPedido']), "d/m/Y H:i");
    $arrayListaPedido['total'] = $row['quantidade'] * $row['preco'];
    $arrayListaPedido['idPedido'] = $row['idPedido'];
    View::load("./view/templates/listaPedido.tpl.html");
    $arrayProduto[$key] = View::show($arrayListaPedido);
    $pedido .= $arrayProduto[$key];
}
$arrayPedido = [];
$arrayPedido['lista'] = $pedido;

View::load("../templates/footer.tpl.html");
$footer = View::show($arrayVazio);

$arrayPedido['navbar'] = $navbar;
$arrayPedido['footer'] = $footer;
View::load("./view/pedido.html");
echo View::show($arrayPedido);




