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
unset($setPost['repeteSenha']);
session_start();
$idCliente = $_SESSION['id'];

switch ($acao) {
    case "update":
        $read = new Read;
        $update = new Update;
        $read->exeRead("cliente", "WHERE id = :id", "id={$idCliente}");
        $array = $read->getResult();

        if ($array[0]['senha'] === sha1($setPost['senhaAntiga'])) {
            $setPost['senha'] = sha1($setPost['senhaNova']);
            unset($setPost['senhaNova']);
            unset($setPost['senhaAntiga']);
            $update->exeUpdate("cliente", $setPost, "WHERE id = :id", "id={$idCliente}");
            $jSon['error'] = false;
            $jSon['mensagem'] = "Cliente atualizado com sucesso!";
        } else {
            $jSon['error'] = true;
            $jSon['mensagem'] = "Senhas nao conferem";
        }
        break;
    default :
        $jSon['error'] = true;
        $jSon['mensagem'] = "Não identificamos sua ação";
        break;
}

echo json_encode($jSon);
return;
