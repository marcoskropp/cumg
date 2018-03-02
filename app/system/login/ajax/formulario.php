<?php

require_once '../../../Config.inc.php';

//passando o que vem por post para uma variavel para utilizar depois
$post = filter_input_array(INPUT_POST, FILTER_DEFAULT);

//limpando possiveis tags
$setPost = array_map("strip_tags", $post);

//criando uma variavel para utilizar como jSon
$jSon = array();
$acao = $setPost['action'];
unset($setPost['action']);

switch ($acao) {
    case "admin":
        $read = new Read();
        $read->fullRead("SELECT * FROM administrador WHERE usuario = :usuario AND senha = sha1(:senha)", "usuario={$setPost['usuario']}&senha={$setPost['senha']}");

        if ($read->getRowCount() > 0) {
            session_start();
            $_SESSION['usuario'] = $setPost['usuario'];
            $_SESSION['nivel'] = "admin";
            $jSon['error'] = false;
            $jSon['mensagem'] = "Sucesso!";
            $jSon['local'] = "../admin/adminController.php";
        } else {
            $jSon['error'] = true;
            $jSon['mensagem'] = "Falha!";
        }
        break;

    case "cliente":
        $read = new Read();
        $read->fullRead("SELECT * FROM cliente WHERE email = :email AND senha = sha1(:senha)", "email={$setPost['email']}&senha={$setPost['senha']}");
        $array = $read->getResult();
        if ($read->getRowCount() > 0) {
            session_start();
            $_SESSION['usuario'] = $setPost['email'];
            $_SESSION['nivel'] = "cliente";
            $_SESSION['id'] = $array[0]['id'];
            $jSon['error'] = false;
            $jSon['mensagem'] = "Sucesso!";
            $jSon['local'] = "../produto/homeController.php";
        } else {
            $jSon['error'] = true;
            $jSon['mensagem'] = "Falha!";
        }
        break;

    default :
        $jSon['error'] = true;
        $jSon['mensagem'] = "Não identificamos sua ação";
        break;
}

echo json_encode($jSon);
return;
