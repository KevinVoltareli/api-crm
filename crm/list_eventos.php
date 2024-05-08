<?php

//Cabecalhos obrigatorios 
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

include_once '../conexao.php';

try {
    $query = "SELECT ID,DATAS, DESCRICAO 
FROM TB_CALENDARIO_CRM a";

    $stmt = $conn->prepare($query);
    $stmt->execute();
    $conn = new PDO("firebird:dbname=C:\SavWinRevo\Servidor\DataBase\BDSAVWINREVO.FDB", "SYSDBA", "masterkey");
    // Set the PDO error mode to exception
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $responseArray = [];

    foreach ($result as $row) {
        $ID_CALEN = intval($row['ID']);
        $DATAS = date('Y-m-d', strtotime($row['DATAS']));
        $DESCRICAO = $row['DESCRICAO'];

        $responseArray[] = [
            'ID' => $ID_CALEN,
            'DATAS' => $DATAS,
            'DESCRICAO' => $DESCRICAO,

        ];
    }

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        
        $data = json_decode(file_get_contents('php://input'), true);
    
   
        if (isset($data['titulo']) && isset($data['descricao']) && isset($data['dataInicio']) && isset($data['dataTermino'])) {
       
            $titulo = $data['titulo'];
            $descricao = $data['descricao'];
            $dataInicio = $data['dataInicio'];
            $dataTermino = $data['dataTermino'];
    
            try {
                
                $host = 'localhost'; 
                $dbname = 'caminho/para/seu/banco.fdb'; 
                $username = 'seu_usuario';
                $password = 'sua_senha';
    
               
                $dbh = new PDO("firebird:dbname=$host:$dbname", $username, $password);
    
              
                $sql = "INSERT INTO sua_tabela (titulo, descricao, dataInicio, dataTermino) VALUES (?, ?, ?, ?)";
                $stmt = $dbh->prepare($sql);
    
               
                $stmt->execute([$titulo, $descricao, $dataInicio, $dataTermino]);
    
              
                $response = array(
                    'status' => 'success',
                    'message' => 'Dados inseridos com sucesso!',
                );
            } catch (PDOException $e) {
               
                $response = array(
                    'status' => 'error',
                    'message' => 'Erro: ' . $e->getMessage(),
                );
            }
        } else {
           
            $response = array(
                'status' => 'error',
                'message' => 'Campos obrigatórios ausentes',
            );
        }
    
        
        echo json_encode($response);
    } else {
        
        $response = array(
            'status' => 'error',
            'message' => 'Método de requisição incorreto',
        );
        echo json_encode($response);
    }

http_response_code(200);
    echo json_encode($responseArray);
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['error' => 'Erro ao buscar os dados.']);
}
?>