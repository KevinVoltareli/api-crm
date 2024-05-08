<?php


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


if ($_SERVER["REQUEST_METHOD"] === "POST") {
$id = $_POST["id"];
$idCam = $_POST["idCam"];
$nome_indicado = $_POST["NOME_INDICADO"];
$contato_indicado = $_POST["CONTATO_INDICADO"];


  try {
    // Create a new PDO connection
    $conn = new PDO("firebird:dbname=C:\SavWinRevo\Servidor\DataBase\BDSAVWINREVO.FDB", "SYSDBA", "masterkey");
    // Set the PDO error mode to exception
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Prepare and execute the SQL update query using a prepared statement
  $sql = "INSERT INTO TB_CAM_INDICACAO (ID_CLIENTE,ID_CAMPANHA, NOME_INDICADO, NUMERO_INDICADO, CREATED_AT) VALUES (:id,:idCam, :nome_indicado, :contato_indicado, CURRENT_DATE)";
$stmt = $conn->prepare($sql);
$stmt->bindParam(':id', $id);
$stmt->bindParam(':idCam', $idCam);
$stmt->bindParam(':nome_indicado', $nome_indicado);
$stmt->bindParam(':contato_indicado', $contato_indicado);
    $stmt->execute();

    $response = array("message" => "Tag updated successfully");
  } catch (PDOException $e) {
    $response = array("message" => "Error updating tag: " . $e->getMessage());
  }

  // Close the connection
  $conn = null;

  echo json_encode($response);
} else {
  $response = array("mensagem" => "Invalid request method");
  echo json_encode($response);
}
?>
