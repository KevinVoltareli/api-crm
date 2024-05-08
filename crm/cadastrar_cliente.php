<?php

//Cabecalhos obrigatorios
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Headers: *");
//header("Access-Control-Allow-Methods: GET,PUT,POST,DELETE");

//Incluir a conexao
include_once '../conexao.php';

$response_json = file_get_contents("php://input");
$dados = json_decode($response_json, true);


$id = filter_input(INPUT_GET, 'id');
if($dados){

    $query_produto = "INSERT INTO TB_CAM_CLIENTE(ID_CLIENTE, CAMPANHA_ID, STATUS, CRIADO) VALUES (:ID_CLIENTE,{$id}, :STATUS,Current_timestamp )";
    $cad_produto = $conn->prepare($query_produto);

    $cad_produto->bindParam(':ID_CLIENTE', $dados['produto']['ID_CLIENTE'], PDO::PARAM_STR);
    $cad_produto->bindParam(':STATUS', $dados['produto']['STATUS'], PDO::PARAM_STR);


    $cad_produto->execute();

    if($cad_produto->rowCount()){
        $response = [
            "erro" => false,
            "mensagem" => "Campanha criada com sucesso!"
        ];
    }else{
        $response = [
            "erro" => true,
            "mensagem" => "Ops! Alguma coisa deu errado =("
        ];
    }
    
    
}else{
    $response = [
        "erro" => true,
        "mensagem" => "Ops! Alguma coisa deu errado =("
    ];
}

http_response_code(200);
echo json_encode($response);