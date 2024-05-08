<?php

//Incluir a conexao
try {
    $conn = new PDO("firebird:dbname=C:\SavWinRevo\Servidor\DataBase\BDSAVWINREVO.FDB", "SYSDBA", "masterkey");
    // Set the PDO error mode to exception
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
    die();
}

$filial = filter_input(INPUT_GET, 'filial');
$dataInicial = filter_input(INPUT_GET, 'dataInicial');
$dataFinal = filter_input(INPUT_GET, 'dataFinal');



// Fetch data from the database
if($filial !== 'todas'){
$sql = ("SELECT h.STATUS AS STATUS, a.CLIID AS IDCLIENTE, b.PESNOME AS CLIENTE,COUNT(c.VENTOTALPEDIDO) AS TOTALPROD, SUM(c.VENTOTALLIQUIDO) AS TOTALVAL, SUM(c.VENTOTALLIQUIDO)/COUNT(c.VENTOTALPEDIDO) AS TM, b.PESDTCADASTRO AS DTCAD, a.CLIULTIMAATUALIZACAO AS DTULTCOMP, i.PESNOME AS FILIAL,d.TELDDD AS DDD, d.TELNUMERO AS CELULAR
                    FROM TB_CLI_CLIENTE a
                    INNER JOIN TB_PES_PESSOA b ON b.PESID = a.PESID 
                    INNER JOIN TB_VEN_VENDA c ON c.CLIID_PAGADOR = a.CLIID 
                    left JOIN TB_TEL_TELEFONE d ON d.PESID = b.PESID 
                    INNER JOIN TB_VPE_VENDAPEDIDOS e ON e.VENID_VENDA = c.VENID 
                    INNER JOIN TB_PED_PEDIDO f ON f.PEDID = e.PEDID_PEDIDO 
                    INNER JOIN TB_FIL_FILIAL g ON g.FILID = f.FILID_FILIAL 
                    LEFT JOIN TB_CAM_CLIENTE h ON h.ID_CLIENTE = a.CLIID 
                    INNER JOIN TB_PES_PESSOA i ON i.PESID = g.PESID 
                    WHERE
                    f.PEDDATAENTRADA  >= {$dataInicial} AND f.PEDDATAENTRADA  <=  {$dataFinal}
                    AND g.FILID = {$filial}
                    AND NOT d.TELTIPO = 'T'
                    GROUP BY IDCLIENTE,CLIENTE,DTCAD,DTULTCOMP,STATUS,FILIAL,DDD, CELULAR
                    ORDER BY TM DESC");
} else {
  $sql = ("SELECT h.STATUS AS STATUS, a.CLIID AS IDCLIENTE, b.PESNOME AS CLIENTE,COUNT(c.VENTOTALPEDIDO) AS TOTALPROD, SUM(c.VENTOTALLIQUIDO) AS TOTALVAL, SUM(c.VENTOTALLIQUIDO)/COUNT(c.VENTOTALPEDIDO) AS TM, b.PESDTCADASTRO AS DTCAD, a.CLIULTIMAATUALIZACAO AS DTULTCOMP, i.PESNOME AS FILIAL,d.TELDDD AS DDD, d.TELNUMERO AS CELULAR
                    FROM TB_CLI_CLIENTE a
                    INNER JOIN TB_PES_PESSOA b ON b.PESID = a.PESID 
                    INNER JOIN TB_VEN_VENDA c ON c.CLIID_PAGADOR = a.CLIID 
                    left JOIN TB_TEL_TELEFONE d ON d.PESID = b.PESID 
                    INNER JOIN TB_VPE_VENDAPEDIDOS e ON e.VENID_VENDA = c.VENID 
                    INNER JOIN TB_PED_PEDIDO f ON f.PEDID = e.PEDID_PEDIDO 
                    INNER JOIN TB_FIL_FILIAL g ON g.FILID = f.FILID_FILIAL 
                    LEFT JOIN TB_CAM_CLIENTE h ON h.ID_CLIENTE = a.CLIID 
                    INNER JOIN TB_PES_PESSOA i ON i.PESID = g.PESID 
                    WHERE
                    f.PEDDATAENTRADA  >= {$dataInicial} AND f.PEDDATAENTRADA  <=  {$dataFinal}
                    AND NOT d.TELTIPO = 'T'
                    GROUP BY IDCLIENTE,CLIENTE,DTCAD,DTULTCOMP,STATUS,FILIAL,DDD, CELULAR
                    ORDER BY TM DESC");
}


$stmt = $conn->query($sql);
$data = $stmt->fetchAll(PDO::FETCH_ASSOC);

function array_to_csv($data) {
    $csv_content = '';

    if (!empty($data)) {
        // Headers
        $headers = array_keys($data[0]);
        $csv_content .= implode(';', $headers) . "\n";

        // Rows
        foreach ($data as $row) {
            $csv_content .= implode(';', $row) . "\n";
        }
    }

    return $csv_content;
}

if (!empty($data)) {
    $csv_content = array_to_csv($data);

    // Set the appropriate headers
    header('Content-Type: text/csv');
    header('Content-Disposition: attachment; filename="report.csv"');

    // Output the CSV data
    echo $csv_content;
}

?>