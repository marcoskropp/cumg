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

$id = filter_input(INPUT_GET, 'id', FILTER_DEFAULT);

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

$read->exeRead("media", "WHERE idProduto = :idProduto", "idProduto={$id}");
$array = $read->getResult();
View::load("./view/templates/produtoImage.tpl.html");
$html = '';
foreach ($array as $value) {
    $html .= View::show($value);
}
$read->exeRead("produto", "WHERE id = :id", "id={$id}");
$array = $read->getResult();

View::load("../templates/footer.tpl.html");
$array[0]['footer'] = View::show($arrayVazio);
$array[0]['navbar'] = $navbar;

View::load("./view/produto.html");
$array[0]['produtoImage'] = $html;
echo View::show($array[0]);



