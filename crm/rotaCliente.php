<?php


include_once '../conexao.php';

// Allow requests from any origin. You can replace * with specific allowed origins.
header('Access-Control-Allow-Origin: *');

// Allow the following HTTP methods for CORS requests
header('Access-Control-Allow-Methods: POST, GET, OPTIONS');

// Allow the following custom headers for CORS requests
header('Access-Control-Allow-Headers: Content-Type');

// Set the appropriate headers for CORS preflight requests (OPTIONS method)
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit;
}

$id = filter_input(INPUT_GET, 'id');


// Handle GET request to fetch all clients
if ($_SERVER['REQUEST_METHOD'] === 'GET') {

     // Create a new PDO connection
    $conn = new PDO("firebird:dbname=C:\SavWinRevo\Servidor\DataBase\BDSAVWINREVO.FDB; charset=UTF-8", "SYSDBA", "masterkey");
    // Set the PDO error mode to exception
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $stmt = $conn->prepare("SELECT DISTINCT a.STATUS AS STATUS, a.CRIADO ,
    EXTRACT(DAY FROM a.CRIADO) || '/' || EXTRACT(MONTH FROM a.CRIADO) || '/' || EXTRACT(YEAR FROM a.CRIADO) AS CRIADO, g.PESNOME AS CLIENTE,m.TELDDD AS DDD,m.TELNUMERO AS CELULAR, g.PESDTCADASTRO AS DTCAD, f.CLIULTIMAATUALIZACAO AS DTULTCOMP, o.PESNOME AS FILIAL, f.CLIID AS IDCLIENTE, a.CAMPANHA_ID AS CAMPANHA_ID, a.TAG AS TAG, a.RESPOSTA AS RESPOSTA, a.ETIQUETA AS ETIQUETA
                       FROM TB_CAM_CLIENTE a            
                       INNER JOIN TB_CAM_CAMPANHA b ON b.ID = a.CAMPANHA_ID         
                       left JOIN TB_CLI_CLIENTE f ON f.CLIID = a.ID_CLIENTE 
                       INNER JOIN TB_PES_PESSOA g ON g.PESID = f.PESID 
                       INNER JOIN TB_INT_INTERNET l ON l.PESID = f.PESID 
                       LEFT JOIN TB_TEL_TELEFONE m ON m.PESID = g.PESID
                       INNER JOIN TB_PES_PESSOA o ON o.PESID = g.PESID  
                       WHERE a.CAMPANHA_ID = :id
                       AND m.TELDDD > '0' ");
    
    $stmt->bindParam(':id', $id, PDO::PARAM_INT);
    $stmt->execute();
    $clients = $stmt->fetchAll(PDO::FETCH_ASSOC);
    echo json_encode($clients);
    exit;
}

// Handle POST request to update client status
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  if (isset($_POST['id'])) {
        $clientId = $_POST['id'];
    } else {
        die("Error: 'id' not found in POST data.");
    }

    if (isset($_POST['status'])) {
        $status = $_POST['status'];
    } else {
        die("Error: 'status' not found in POST data.");
    }

    $stmt = $conn->prepare("UPDATE TB_CAM_CLIENTE SET STATUS = ? WHERE ID_CLIENTE = ?");
    $stmt->execute([$status, $clientId]);

    echo json_encode(['mensagem' => 'Client status updated successfully']);
    exit;
}

// Handle other HTTP methods or additional API endpoints as needed

// Return an error for unsupported methods or endpoints
http_response_code(404);
echo json_encode(['error' => 'Endpoint not found']);

           
         