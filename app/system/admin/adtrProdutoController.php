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

$arrayVazio = [];

View::load("view/templates/navAdmin.tpl.html");
$nav = View::show($arrayVazio);

$read = new Read();

$read->exeRead("auditaProduto", "ORDER BY id DESC");
$array = $read->getResult();
$html = "";

View::load("view/templates/auditaProduto.tpl.html");
foreach ($array as $value){
    $html .= View::show($value);
}

$arr = [];
$arr['lista'] = $html;
$arr['nav'] = $nav;
View::load("view/auditaProduto.html");
echo View::show($arr);