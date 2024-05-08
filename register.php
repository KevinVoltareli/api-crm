<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST');
header('Access-Control-Allow-Headers: Content-Type');

include_once './conexao.php';


// Receba os dados do usuário do front-end
$data = json_decode(file_get_contents("php://input"));

$nome = $data->userData->nome;
$email = $data->email;
$funcao = $data->userData->funcao;

// Insira os dados na tabela "users" (ajuste o nome da tabela conforme necessário)
$stmt = $conn->prepare("INSERT INTO TB_CRM_USUARIOS (EMAIL, NOME, FUNCAO) VALUES (?, ?, ?)");
if ($stmt->execute([$email, $nome, $funcao])) {
    echo json_encode(["message" => "Usuário registrado com sucesso"]);
} else {
    echo json_encode(["error" => "Erro ao inserir os dados no banco de dados"]);
}

?>
