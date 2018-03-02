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

session_start();
$setPost['idCliente'] = $_SESSION['id'];

switch ($acao) {
    case "create":
        $create = new Create();
        $create->exeCreate("endereco", $setPost);
        break;
    case "delete":
        $delete = new Delete();
        $delete->exeDelete("endereco", "WHERE id = :id", "id={$setPost['id']}");
        $jSon['error'] = false;
        $jSon['mensagem'] = "Endereço apagado com sucesso!";
        break;
    case "update":
        $update = new Update();
        $id = $setPost['id'];
        unset($setPost['id']);
        $update->exeUpdate("endereco", $setPost, "WHERE id = :id", "id={$id}");
        $jSon['error'] = false;
        $jSon['mensagem'] = "Alterado com sucesso!";
        break;
    default :
        $jSon['error'] = true;
        $jSon['mensagem'] = "Não identificamos sua ação";
        break;
}
echo json_encode($jSon);
return;
