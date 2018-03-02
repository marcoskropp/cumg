<?php

require_once '../../../Config.inc.php';

//passando o que vem por post para uma variavel para utilizar depois
$post = filter_input_array(INPUT_POST, FILTER_DEFAULT);

//limpando possiveis tags
$setPost = array_map("strip_tags", $post);

//criando uma variavel para utilizar como jSon
$jSon = array();
$acao = $setPost['action'];
$table = $setPost['table'];
unset($setPost['action']);
unset($setPost['table']);

session_start();
$setPost['idCliente'] = $_SESSION['id'] ?? null;
if ($setPost['idCliente'] != null) {
    switch ($acao) {
        case 'create':
            $create = new Create;
            $read = new Read;
            $read->exeRead($table, "WHERE idProduto = :idProduto AND idCliente = :idCliente", "idProduto={$setPost['idProduto']}&idCliente={$setPost['idCliente']}");
            if ($read->getRowCount() > 0) {
                $jSon['error'] = true;
                $jSon['mensagem'] = "Produto NAO inserido ao carrinho";
            } else {
                $create->exeCreate($table, $setPost);
                $jSon['error'] = false;
                $jSon['mensagem'] = "Produto inserido ao carrinho";
            }
            break;
        case 'delete':
            $delete = new Delete;
            $delete->exeDelete($table, "WHERE idProduto = :idProduto AND idCliente = :idCliente", "idProduto={$setPost['idProduto']}&idCliente={$setPost['idCliente']}");
            $jSon['error'] = false;
            $jSon['mensagem'] = "Produto deletado da lista de desejos";
            break;
        default:
            $jSon['error'] = true;
            $jSon['mensagem'] = "Não identificamos sua ação";
    }
} else {
    $jSon['error'] = true;
    $jSon['mensagem'] = "Não identificamos sua ação";
}
echo json_encode($jSon);
return;
