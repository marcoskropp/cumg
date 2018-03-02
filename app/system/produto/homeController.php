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

$sort = filter_input(INPUT_GET, 'sort', FILTER_DEFAULT);
$search = filter_input(INPUT_GET, 'search', FILTER_DEFAULT);

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

$read->exeRead("desejo", "WHERE idCliente = :idCliente", "idCliente={$idCliente}");
$arrayDesejo = $read->getResult();

View::load("./view/templates/listaHome.tpl.html");
if ($sort) {
    $read->fullRead("SELECT produto.id, produto.nome, produto.preco, produto.quantidade, produto.categoria, media.nome as 'image' FROM produto INNER JOIN media ON produto.id = media.idProduto WHERE media.principal = 1 AND categoria = :categoria", "categoria={$sort}");
} elseif ($search) {
    $read->fullRead("SELECT produto.id, produto.nome, produto.preco, produto.quantidade, produto.categoria, media.nome as 'image' FROM produto INNER JOIN media ON produto.id = media.idProduto WHERE media.principal = 1 AND produto.nome LIKE :nome", "nome=%{$search}%");
} else {
    $read->fullRead("SELECT produto.id, produto.nome, produto.preco, produto.quantidade, produto.categoria, media.nome as 'image' FROM produto INNER JOIN media ON produto.id = media.idProduto WHERE media.principal = 1");
}
$array = $read->getResult();
foreach ($array as $key => $value) {
    foreach ($arrayDesejo as $row)
        if ($value['id'] == $row['idProduto'])
            $array[$key]['icon'] = "favorite";
    if (!isset($array[$key]['icon']))
        $array[$key]['icon'] = "favorite_border";
}

$html = '';
foreach ($array as $value) {
    $html .= View::show($value);
}

View::load("../templates/footer.tpl.html");
$footer = View::show($arrayVazio);


View::load("./view/home.html");
$arr['lista'] = $html;
$arr['navbar'] = $navbar;
$arr['footer'] = $footer;

echo View::show($arr);


