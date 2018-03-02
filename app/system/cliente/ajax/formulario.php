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
    case "insert":
        $create = new Create();
        $read = new Read();

        $setPost['dataNascimento'] = date("Y-m-d", strtotime($setPost['dataNascimento']));
        $setPost['cpf'] = implode("", explode(".", $setPost['cpf']));
        $setPost['cpf'] = implode("", explode("-", $setPost['cpf']));

        $read->exeRead("cliente", "WHERE cpf = :cpf", "cpf={$setPost['cpf']}");

        if ($read->getRowCount() > 0) {
            $jSon['error'] = true;
            $jSon['mensagem'] = "Cliente já cadastrado!";
        } else {
            $create->exeCreate("cliente", $setPost);
            $jSon['error'] = false;
            $jSon['mensagem'] = "Cadastrado com sucesso!";
        }
        break;

    case "update":
        $update = new Update();
        $id = $setPost['id'];
        unset($setPost['id']);
        $update->exeUpdate("cliente", $setPost, "WHERE id = :id", "id={$id}");
        $jSon['error'] = false;
        $jSon['mensagem'] = "Cliente atualizado com sucesso!";
        break;

    case "delete":
        $delete = new Delete();
        $delete->exeDelete("produto", "WHERE id = :id", "id={$setPost['id']}");
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
