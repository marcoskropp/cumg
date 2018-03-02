<?php

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

$id = filter_input(INPUT_GET, "id", FILTER_DEFAULT);

require_once '../../Config.inc.php';

$read = new Read();

$arrayVazio = [];

View::load("./view/templates/navAdmin.tpl.html");
$nav = View::show($arrayVazio);

$array = [];
$read->exeRead("produto", "WHERE id = :id", "id={$id}");
$array = $read->getResult();

switch ($array[0]['categoria']) {
    case "computer":
        $array[0]['categoria'] = "Computador";
        break;
    case "smartphone":
        $array[0]['categoria'] = "Smartphone";
        break;
    case "tablet":
        $array[0]['categoria'] = "Tablet";
        break;
    case "tv":
        $array[0]['categoria'] = "Televisão";
        break;
    default:
        $array[0]['categoria'] = "UNDEFINED";
}

$array[0]['usuario'] = $_SESSION['usuario'];
$array[0]['nav'] = $nav;

View::load("view/editarProduto.html");
echo View::show($array[0]);
