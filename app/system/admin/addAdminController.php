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

require_once '../../Config.inc.php';

$arrayVazio = [];

View::load("./view/templates/navAdmin.tpl.html");
$nav = View::show($arrayVazio);

$arr['nav'] = $nav;

View::load("view/adicionarAdmin.html");
echo View::show($arr);

