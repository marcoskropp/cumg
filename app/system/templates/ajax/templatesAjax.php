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
$table = $setPost['table'];
unset($setPost['table']);

switch ($acao) {
    case 'create':
        $create = new Create;
        $create->exeCreate($table, $setPost);
        $jSon['error'] = false;
        $jSon['mensagem'] = "Mensagem enviada com sucesso";
        break;
    case 'delete':
        $delete = new Delete;
        $delete->exeDelete("carrinho", "WHERE idProduto = :idProduto", "idProduto={$setPost['idProduto']}");
        $jSon['error'] = false;
        $jSon['mensagem'] = "Produto deletado do carrinho";
        break;
    default:
        $jSon['error'] = true;
        $jSon['mensagem'] = "Não identificamos sua ação";
}
echo json_encode($jSon);
return;
