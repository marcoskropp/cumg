<?php

session_start();
$sair = filter_input(INPUT_GET, "sair");

$nivel = filter_input(INPUT_GET, "nivel");

$campo = [];

switch ($nivel) {
    case "cliente":
        $campo['login'] = "email";
        $campo['tipo'] = "email";
        $campo['nivel'] = $nivel;
        $campo['label'] = "E-mail";
        break;
    case "admin":
        $campo['login'] = "usuario";
        $campo['tipo'] = "text";
        $campo['nivel'] = $nivel;
        $campo['label'] = "Usuário";
        break;
    default :
        $location = "UNDEFINED";
        break;
}


if ($sair == "sair") {
    session_destroy();
    header("../produto/homeController.php");
}

require_once '../../Config.inc.php';

$html = "";

View::load("view/templates/campos.tpl.html");
$html .= View::show($campo);

View::load("view/login.html");
$arr['campos'] = $html;
echo View::show($arr);


