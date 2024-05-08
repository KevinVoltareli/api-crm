<?php

// Permitir solicitações de qualquer origem. Você pode substituir * por origens específicas permitidas.
header('Access-Control-Allow-Origin: *');

// Permitir os seguintes métodos HTTP para solicitações CORS
header('Access-Control-Allow-Methods: POST, GET, OPTIONS');

// Permitir os seguintes cabeçalhos personalizados para solicitações CORS
header('Access-Control-Allow-Headers: Content-Type');

// Defina os cabeçalhos apropriados para solicitações de pré-voo CORS (método OPTIONS)
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit;
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $id = $_POST["id"];
    $tag = $_POST["tag"];
    $email = $_POST["email"];

    try {
        // Crie uma nova conexão PDO
        $conn = new PDO("firebird:dbname=C:\SavWinRevo\Servidor\DataBase\BDSAVWINREVO.FDB", "SYSDBA", "masterkey");
        // Defina o modo de erro PDO para exceção
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // Prepare e execute a consulta SQL de atualização usando uma instrução preparada
        $sql = "UPDATE TB_CAM_CLIENTE SET TAG = :tag, USER_EMAIL = :email, CREATED_AT = CURRENT_DATE WHERE ID_CLIENTE = :id";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':tag', $tag);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':id', $id);
        $stmt->execute();

        $response = array("mensagem" => "Tag e email atualizados com sucesso");
    } catch (PDOException $e) {
        $response = array("mensagem" => "Erro ao atualizar a tag e o email: " . $e->getMessage());
    }

    // Feche a conexão
    $conn = null;

    echo json_encode($response);
} else {
    $response = array("mensagem" => "Método de solicitação inválido");
    echo json_encode($response);
}
?>
