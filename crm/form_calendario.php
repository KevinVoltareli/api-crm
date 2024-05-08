<?php
//Cabecalhos obrigatorios 
// Permitir todas as origens (não é recomendado para produção)
header("Access-Control-Allow-Origin: *");
// Permitir métodos HTTP especificados
header("Access-Control-Allow-Methods: POST, GET, OPTIONS");
// Permitir cabeçalhos personalizados
header("Access-Control-Allow-Headers: Content-Type");
// Permitir credenciais (cookies, autenticação HTTP)
header("Access-Control-Allow-Credentials: true");
// Definir a idade máxima de cache em segundos
header("Access-Control-Max-Age: 3600");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Recupere os dados do corpo da requisição JSON
    $data = json_decode(file_get_contents('php://input'), true);

    // Verifique se os campos obrigatórios estão presentes
    if (isset($data['titulo']) && isset($data['descricao']) && isset($data['dataInicio']) && isset($data['dataTermino'])) {
        // Recupere os valores dos campos
        $titulo = $data['titulo'];
        $descricao = $data['descricao'];
        $dataInicio = $data['dataInicio'];
        $dataTermino = $data['dataTermino'];


        try {

          $conn = new PDO("firebird:dbname=C:\SavWinRevo\Servidor\DataBase\BDSAVWINREVO.FDB", "SYSDBA", "masterkey");
            // Set the PDO error mode to exception
            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

           // Prepara a consulta SQL para inserir os dados
            $sql = "INSERT INTO TB_CALENDARIOS_CRM (TITULO, DESCRICAO, DATA_INICIO, DATA_TERMINO) VALUES (:titulo, :descricao, :dataInicio, :dataTermino)";
            $stmt = $conn->prepare($sql);

            // Associe os valores dos parâmetros às variáveis
            $stmt->bindParam(':titulo', $titulo);
            $stmt->bindParam(':descricao', $descricao);
            $stmt->bindParam(':dataInicio', $dataInicio);
            $stmt->bindParam(':dataTermino', $dataTermino);

            // Executa a consulta
            $stmt->execute();


            // Dados inseridos com sucesso
            $response = array(
                'status' => 'success',
                'message' => 'Dados inseridos com sucesso!',
            );
        } catch (PDOException $e) {
            // Erro na conexão ou na consulta
            $response = array(
                'status' => 'error',
                'message' => 'Erro: ' . $e->getMessage(),
            );
        }
    } else {
        // Campos obrigatórios ausentes, envie uma mensagem de erro
        $response = array(
            'status' => 'error',
            'message' => 'Campos obrigatórios ausentes',
        );
    }

    // Responda ao frontend com uma mensagem de sucesso ou erro
    echo json_encode($response);
} else {
    // Método de requisição incorreto, envie uma mensagem de erro
    $response = array(
        'status' => 'error',
        'message' => 'Método de requisição incorreto',
    );
    echo json_encode($response);
}
?>