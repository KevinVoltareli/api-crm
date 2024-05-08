<?php
// Configurações de CORS (Cross-Origin Resource Sharing)
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept");

try {
    // Conecte-se ao banco de dados Firebird (substitua com suas próprias configurações)

          $conn = new PDO("firebird:dbname=C:\SavWinRevo\Servidor\DataBase\BDSAVWINREVO.FDB", "SYSDBA", "masterkey");

    // Prepare e execute a consulta SQL para buscar eventos do banco de dados
    $sql = "SELECT TITULO, DATA_INICIO, DATA_TERMINO, DESCRICAO FROM TB_CALENDARIOS_CRM";
    $stmt = $conn->prepare($sql);
    $stmt->execute();

    // Recupere os resultados em um array associativo
    $eventos = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Converte os dados em JSON e envia como resposta
    header('Content-Type: application/json');
    echo json_encode($eventos);
} catch (PDOException $e) {
    // Em caso de erro na conexão ou consulta
    header('Content-Type: application/json');
    echo json_encode(['error' => 'Erro ao buscar eventos: ' . $e->getMessage()]);
}
