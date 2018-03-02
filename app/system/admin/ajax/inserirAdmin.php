<?php

require_once '../../../Config.inc.php';

//passando o que vem por post para uma variavel para utilizar depois
$post = filter_input_array(INPUT_POST, FILTER_DEFAULT);

//limpando possiveis tags
$setPost = array_map("strip_tags", $post);

//criando uma variavel para utilizar como jSon
$jSon = array();

$create = new Create();
$read = new Read();

$read->exeRead("administrador", "WHERE usuario = :usuario", "usuario={$setPost['usuario']}");

if ($read->getRowCount() > 0) {
    $jSon['error'] = true;
    $jSon['mensagem'] = "O usuário {$setPost['usuario']} já está cadastrado!";
} else {
    $create->exeCreate("administrador", $setPost);
    $jSon['error'] = false;
    $jSon['mensagem'] = "Adicionado com sucesso: {$setPost['usuario']}";
}

echo json_encode($jSon);
return;
