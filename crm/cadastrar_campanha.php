<?php

// Cabecalhos obrigatorios
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Headers: *");

// Incluir a conexao
include_once '../conexao.php';

$response_json = file_get_contents("php://input");
$dados = json_decode($response_json, true);

if ($dados) {
    // Extract email, NOME, and PLATAFORMA from the request
    $email = $dados['email']; // This is the email you're sending from the front-end
    $nome = $dados['produto']['NOME'];
    $plataforma = $dados['produto']['PLATAFORMA'];

    $query_produto = "INSERT INTO TB_CAM_CAMPANHA(DATA_CRIACAO, NOME, PLATAFORMA, CRIADO_POR) VALUES (CURRENT_TIMESTAMP, :NOME, :PLATAFORMA, :EMAIL)";
    $cad_produto = $conn->prepare($query_produto);

    $cad_produto->bindParam(':NOME', $nome, PDO::PARAM_STR);
    $cad_produto->bindParam(':PLATAFORMA', $plataforma, PDO::PARAM_STR);
    $cad_produto->bindParam(':EMAIL', $email, PDO::PARAM_STR);

    $cad_produto->execute();

    if ($cad_produto->rowCount()) {
        $response = [
            "erro" => false,
            "mensagem" => "Campanha criada com sucesso!"
        ];
    } else {
        $response = [
            "erro" => true,
            "mensagem" => "Ops! Alguma coisa deu errado =("
        ];
    }
} else {
    $response = [
        "erro" => true,
        "mensagem" => "Ops! Alguma coisa deu errado =("
    ];
}

http_response_code(200);
echo json_encode($response);
