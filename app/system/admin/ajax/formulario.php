<?php

require_once '../../../Config.inc.php';

//passando o que vem por post para uma variavel para utilizar depois
$post = filter_input_array(INPUT_POST, FILTER_DEFAULT);

//limpando possiveis tags
$setPost = array_map("strip_tags", $post);

//criando uma variavel para utilizar como jSon
$jSon = array();
$acao = $setPost['action'];
$usuario = $setPost['usuario'];
unset($setPost['action']);
unset($setPost['usuario']);

switch ($acao) {
    case "insert":
        $create = new Create();
        $read = new Read();

        $read->exeRead("produto", "WHERE nome = :nome", "nome={$setPost['nome']}");

        if ($read->getRowCount() > 0) {
            $jSon['error'] = true;
            $jSon['mensagem'] = "O produto {$setPost['nome']} já está cadastrado!";
        } else {
            $create->exeCreate("produto", $setPost);
            $dados = [
                'usuario' => $usuario,
                'acao' => 'insert',
                'produto' => $setPost['nome']
            ];
            $create->exeCreate("auditaProduto", $dados);
            $read->fullRead("SELECT MAX(id) AS 'id' FROM produto");
            $idProduto = $read->getResult();
            $dados = [
                'tipo' => 'jpg',
                'nome' => 'padrao.jpg',
                'principal' => 1,
                'idProduto' => $idProduto[0]['id']
            ];
            $create->exeCreate("media", $dados);
            $jSon['error'] = false;
            $jSon['mensagem'] = "Cadastrado com sucesso: {$setPost['nome']}";
        }
        break;

    case "update":
        $update = new Update();
        $create = new Create();
        $read = new Read();

        $id = $setPost['id'];
        $read->fullRead("SELECT nome FROM produto WHERE id = :id", "id={$id}");
        $nome = $read->getResult();
        unset($setPost['id']);
        $update->exeUpdate("produto", $setPost, "WHERE id = :id", "id={$id}");
        $dados = [
            'usuario' => $usuario,
            'acao' => 'update',
            'produto' => $nome[0]['nome']
        ];
        $create->exeCreate("auditaProduto", $dados);
        $jSon['error'] = false;
        $jSon['mensagem'] = "Produto atualizado com sucesso!";
        break;

    case "delete":
        $delete = new Delete();
        $create = new Create();
        $read = new Read();
        $read->fullRead("SELECT nome FROM produto WHERE id = :id", "id={$setPost['id']}");
        $delete->exeDelete("produto", "WHERE id = :id", "id={$setPost['id']}");
        $nome = $read->getResult();
        $dados = [
            'usuario' => $usuario,
            'acao' => 'delete',
            'produto' => $nome[0]['nome']
        ];
        $create->exeCreate("auditaProduto", $dados);
        $jSon['error'] = false;
        $jSon['mensagem'] = "Produto excluído com sucesso!";
        break;

    default :
        $jSon['error'] = true;
        $jSon['mensagem'] = "Não identificamos sua ação";
        break;
}

echo json_encode($jSon);
return;
