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
$sql = ("SELECT DISTINCT p.STATUS AS STATUS,g.PESNOME AS CLIENTE, count(a.LCTVALOR) AS TOTALPROD,sum(a.LCTVALOR) AS TOTALVAL,m.TELDDD AS DDD,m.TELNUMERO AS CELULAR, g.PESDTCADASTRO AS DTCAD, f.CLIULTIMAATUALIZACAO AS DTULTCOMP, o.PESNOME AS FILIAL, f.CLIID AS IDCLIENTE
                           FROM TB_LCT_LANCAMENTOS a
                           INNER JOIN TB_LTV_LANCAMENTOVENDA b ON b.LCTID = a.LCTID 
                           INNER JOIN TB_VEN_VENDA c ON c.VENID = a.VENID  
                           INNER JOIN TB_VPE_VENDAPEDIDOS d ON d.VENID_VENDA = c.VENID 
                           INNER JOIN TB_PED_PEDIDO e ON e.PEDID = d.PEDID_PEDIDO 
                           RIGHT JOIN TB_CLI_CLIENTE f ON f.CLIID = c.CLIID_PAGADOR 
                           INNER JOIN TB_PES_PESSOA g ON g.PESID = f.PESID 
                           INNER JOIN TB_USU_USUARIO h ON h.USUID = a.USUID
                           INNER JOIN TB_INT_INTERNET l ON l.PESID = f.PESID 
                           LEFT JOIN TB_TEL_TELEFONE m ON m.PESID = g.PESID
                           INNER JOIN TB_FIL_FILIAL n ON n.FILID = e.FILID_FILIAL 
                           INNER JOIN TB_PES_PESSOA o ON o.PESID = n.PESID  
                           LEFT JOIN TB_CAM_CLIENTE p ON p.ID_CLIENTE = f.CLIID 
                           WHERE f.CLIULTIMAATUALIZACAO >= {$dataInicial} AND f.CLIULTIMAATUALIZACAO <= {$dataFinal}  
                           AND a.LCTDATALANCAMENTO >= {$dataInicial}
                           AND e.MCVID IS NULL
                           AND m.TELNUMERO LIKE '9%'
                           AND NOT m.TELDDD IS NULL
                           AND n.FILID = {$filial}
                           GROUP BY CLIENTE,CELULAR,DTCAD, DTULTCOMP,DDD, FILIAL,STATUS,IDCLIENTE
                           ORDER BY TOTALPROD desc");
} else {
  $sql = ("SELECT DISTINCT p.STATUS AS STATUS,g.PESNOME AS CLIENTE, count(a.LCTVALOR) AS TOTALPROD,sum(a.LCTVALOR) AS TOTALVAL,m.TELDDD AS DDD,m.TELNUMERO AS CELULAR, g.PESDTCADASTRO AS DTCAD, f.CLIULTIMAATUALIZACAO AS DTULTCOMP, o.PESNOME AS FILIAL, f.CLIID AS IDCLIENTE
                           FROM TB_LCT_LANCAMENTOS a
                           INNER JOIN TB_LTV_LANCAMENTOVENDA b ON b.LCTID = a.LCTID 
                           INNER JOIN TB_VEN_VENDA c ON c.VENID = a.VENID  
                           INNER JOIN TB_VPE_VENDAPEDIDOS d ON d.VENID_VENDA = c.VENID 
                           INNER JOIN TB_PED_PEDIDO e ON e.PEDID = d.PEDID_PEDIDO 
                           RIGHT JOIN TB_CLI_CLIENTE f ON f.CLIID = c.CLIID_PAGADOR 
                           INNER JOIN TB_PES_PESSOA g ON g.PESID = f.PESID 
                           INNER JOIN TB_USU_USUARIO h ON h.USUID = a.USUID
                           INNER JOIN TB_INT_INTERNET l ON l.PESID = f.PESID 
                           LEFT JOIN TB_TEL_TELEFONE m ON m.PESID = g.PESID
                           INNER JOIN TB_FIL_FILIAL n ON n.FILID = e.FILID_FILIAL 
                           INNER JOIN TB_PES_PESSOA o ON o.PESID = n.PESID  
                           LEFT JOIN TB_CAM_CLIENTE p ON p.ID_CLIENTE = f.CLIID 
                           WHERE f.CLIULTIMAATUALIZACAO >= {$dataInicial} AND f.CLIULTIMAATUALIZACAO <= {$dataFinal}  
                           AND a.LCTDATALANCAMENTO >= {$dataInicial}
                           AND e.MCVID IS NULL
                           AND m.TELNUMERO LIKE '9%'
                           AND NOT m.TELDDD IS NULL
                           GROUP BY CLIENTE,CELULAR,DTCAD, DTULTCOMP,DDD, FILIAL,STATUS,IDCLIENTE
                           ORDER BY TOTALPROD desc");
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