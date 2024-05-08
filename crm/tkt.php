
<?php

//Cabecalhos obrigatorios
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

//Incluir a conexao
include_once '../conexao.php';

//ecommerce = 5
//agio = 4
//galao = 2

$filial = filter_input(INPUT_GET, 'filial');
$dataInicial = filter_input(INPUT_GET, 'dataInicial');
$dataFinal = filter_input(INPUT_GET, 'dataFinal');

if($filial !== 'todas'){

   $read = $conn->prepare("SELECT h.STATUS AS STATUS, a.CLIID AS IDCLIENTE, b.PESNOME AS CLIENTE,COUNT(c.VENTOTALPEDIDO) AS TOTALPROD, SUM(c.VENTOTALLIQUIDO) AS TOTALVAL, SUM(c.VENTOTALLIQUIDO)/COUNT(c.VENTOTALPEDIDO) AS TM, b.PESDTCADASTRO AS DTCAD, a.CLIULTIMAATUALIZACAO AS DTULTCOMP, i.PESNOME AS FILIAL,d.TELDDD AS DDD, d.TELNUMERO AS CELULAR
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
                    ORDER BY TM DESC ");
 } else {
  $read = $conn->prepare("SELECT h.STATUS AS STATUS, a.CLIID AS IDCLIENTE, b.PESNOME AS CLIENTE,COUNT(c.VENTOTALPEDIDO) AS TOTALPROD, SUM(c.VENTOTALLIQUIDO) AS TOTALVAL, SUM(c.VENTOTALLIQUIDO)/COUNT(c.VENTOTALPEDIDO) AS TM, b.PESDTCADASTRO AS DTCAD, a.CLIULTIMAATUALIZACAO AS DTULTCOMP, i.PESNOME AS FILIAL,d.TELDDD AS DDD, d.TELNUMERO AS CELULAR
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
                    ORDER BY TM DESC  ");
}


              

$read->setFetchMode(PDO::FETCH_ASSOC);            
$read->execute();   
$array = $read->fetchAll();   

  foreach ($array as $dados): 
           
                   $CLIENTE = $dados["CLIENTE"];  
                   $TOTALPROD = $dados["TOTALPROD"];  
                   $TOTALVAL = $dados["TOTALVAL"];
                   $DDD = $dados["DDD"];
                   $CELULAR = $dados["CELULAR"];
                   $DTCAD = $dados["DTCAD"];
                   $DTULTCOMP = $dados["DTULTCOMP"];
                   $FILIAL = $dados["FILIAL"];
                   $STATUS = $dados["STATUS"];
                   $IDCLIENTE = $dados["IDCLIENTE"];
                   $TM = $dados["TM"];



         
                   extract($dados);

       

                   $GERAL["GERAL"][] = [

                     'CLIENTE' =>  utf8_decode($CLIENTE), 
                     'TOTALPROD' => number_format($TOTALPROD,0,",",""), 
                     'TM' => number_format($TM,0,",",""),
                     'TOTALVAL' => number_format($TOTALVAL,0,",",""), 
                     'DDD' =>  $DDD,
                     'CELULAR' => $CELULAR,
                     'DTCAD' => $DTCAD, 
                     'DTULTCOMP' => $DTULTCOMP,  
                     'FILIAL' => $FILIAL,               
                     'STATUS' => $STATUS,                  
                     'IDCLIENTE' => $IDCLIENTE,      
                   ];

                     endforeach;  




                //Reposta com status 200
                http_response_code(200);

                //Retornar as informações em json
                echo json_encode($GERAL);  

                //echo "Total geral: {$qtd}";