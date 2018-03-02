<?php

require_once '../../Config.inc.php';

session_start();

$usuario = $_SESSION['usuario'];

if (!(isset($usuario))) {
    session_destroy();
    header("Location: ../produto/homeController.php");
}

if ($_SESSION['nivel'] != "admin") {
    session_destroy();
    header("Location: ../produto/homeController.php");
}

$read = new Read();

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

View::load("./view/admin.html");
$arr['lista'] = $html;
$arr['usuario'] = $_SESSION['usuario'];
$arr['nav'] = $nav;
echo View::show($arr);
