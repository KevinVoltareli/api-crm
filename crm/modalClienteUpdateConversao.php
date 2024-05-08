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
  $tag = $_POST["tag"];
  $email = $_POST["email"];


  try {
    // Create a new PDO connection
    $conn = new PDO("firebird:dbname=C:\SavWinRevo\Servidor\DataBase\BDSAVWINREVO.FDB", "SYSDBA", "masterkey");
    // Set the PDO error mode to exception
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Prepare and execute the SQL update query using a prepared statement
    $sql = "UPDATE TB_CAM_CLIENTE SET VALOR_CONVERSAO = :tag, USER_EMAIL = :email, CREATED_AT = CURRENT_DATE WHERE ID_CLIENTE = :id";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':tag', $tag);
    $stmt->bindParam(':id', $id);
    $stmt->bindParam(':email', $email);
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
