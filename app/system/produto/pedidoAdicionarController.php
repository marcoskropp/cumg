<?php

require_once '../../Config.inc.php';
$create = new Create;
$read = new Read;
$delete = new Delete;

session_start();

$sair = filter_input(INPUT_GET, 'sair');

if (!(isset($_SESSION['id']))) {
    session_destroy();
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
$arr['navbar'] = View::show($array[0]);

View::load("./view/templates/listaPedidoAdicionar.tpl.html");
$read->fullRead("SELECT produto.id, produto.nome AS 'nomeProduto', produto.preco, carrinho.quantidade, produto.categoria, produto.descricao, media.nome as 'image' FROM carrinho INNER JOIN produto ON carrinho.idProduto = produto.id INNER JOIN media ON carrinho.idProduto = media.idProduto WHERE carrinho.idCliente = :idCliente", "idCliente={$idCliente}");
$array = $read->getResult();
if (empty($array))
    echo "<script>window.location.href = 'homeController.php';</script>";
$html = '';
foreach ($array as $value) {
    $html .= View::show($value);
}
$arr['lista'] = $html;

View::load("./view/templates/listaPedidoAdicionarEndereco.tpl.html");
$read->exeRead("endereco", "WHERE idCliente = :idCliente", "idCliente={$idCliente}");
$array = $read->getResult();
$html = '';
foreach ($array as $value) {
    $html .= View::show($value);
}
$arr['pedidoAdicionarEndereco'] = $html;
View::load("../templates/footer.tpl.html");
$arr['footer'] = View::show($arrayVazio);

View::load("./view/pedidoAdicionar.html");
echo View::show($arr);




