<?php

//Cabecalhos obrigatorios 
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

//Incluir a conexao
include_once '../conexao.php';

try {
    $query = "SELECT COUNT(b.ID_CLIENTE) AS ID_CLI, COUNT(b.CAMPANHA_ID) AS ID_CAM, A.NOME AS NOME 
              FROM TB_CAM_CAMPANHA A
              LEFT JOIN TB_CAM_CLIENTE B ON B.CAMPANHA_ID = A.ID
              GROUP BY NOME";

    $stmt = $conn->prepare($query);
    $stmt->execute();

    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $responseArray = [];

    foreach ($result as $row) {
        $ID_CLI = intval($row['ID_CLI']);
        $ID_CAM = intval($row['ID_CAM']);
        $NOME = $row['NOME'];

        $taxaResposta = 0;

        if ($NOME === 'Campanha de frete grátis') {
            $taxaResposta = 15;
        } elseif ($NOME === 'Campanha comercial 2') {
            $taxaResposta = 0;
        }

        $responseArray[] = [
            'ID_CLI' => $ID_CLI,
            'ID_CAM' => $ID_CAM,
            'NOME' => $NOME,
            'TAXA_RESPOSTA' => $taxaResposta,
        ];
    }

    http_response_code(200);
    echo json_encode($responseArray);
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['error' => 'Erro ao buscar os dados.']);
}

?>
