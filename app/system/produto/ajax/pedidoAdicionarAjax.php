<?php

require_once '../../../Config.inc.php';

//passando o que vem por post para uma variavel para utilizar depois
$setPost = filter_input_array(INPUT_POST, FILTER_DEFAULT);


//criando uma variavel para utilizar como jSon
$jSon = array();
$acao = $setPost['action'];
$idProduto = $setPost['idProduto'];
$quantidade = $setPost['quantidade'];
$preco = $setPost['preco'];
unset($setPost['action']);
unset($setPost['idProduto']);
unset($setPost['quantidade']);
unset($setPost['preco']);

session_start();

$idCliente = $setPost['idCliente'] = $_SESSION['id'] ?? null;

if (isset($setPost['idCliente']) && isset($setPost['idEndereco'])) {
    switch ($acao) {
        case 'create':
            $create = new Create;
            $read = new Read;
            $delete = new Delete;
            $arrayAuditoria = [];
            $read->exeRead("cliente", "WHERE id = :id", "id={$idCliente}");
            $cliente = $read->getResult();
            $setPost['dataPedido'] = date("Y-m-d h:i:s");
            $setPost['situacao'] = "executando";
            $create->exeCreate("pedido", $setPost);
            $read->fullRead("SELECT MAX(id) AS 'id' FROM pedido");
            $idPedido = $read->getResult();
            unset($setPost['dataPedido']);
            unset($setPost['situacao']);
            unset($setPost['idCliente']);
            unset($setPost['idEndereco']);
            foreach ($idProduto as $key => $value) {
                $arrayAuditoria = [
                    "idCliente" => $idCliente,
                    "idProduto" => $value,
                    "quantidade" => $quantidade[$key],
                    "preco" => $preco[$key]
                ];
                $create->exeCreate("auditaPedido", $arrayAuditoria);
                $setPost['idPedido'] = $idPedido[0]['id'];
                $setPost['idProduto'] = $value;
                $setPost['quantidade'] = $quantidade[$key];
                $create->exeCreate("pedidoProduto", $setPost);
                $delete->exeDelete("carrinho", "WHERE idCliente = :idCliente", "idCliente={$idCliente}");
            }
            $mensagem = "Saudeçoes {$cliente[0]['nome']}, \r\n \r\n ";
            $jSon['error'] = false;
            $jSon['mensagem'] = "Venda efetuada com sucesso";
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
