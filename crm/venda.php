
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

   $read = $conn->prepare("SELECT DISTINCT p.STATUS AS STATUS,g.PESNOME AS CLIENTE,sum(a.LCTVALOR) AS TOTALVAL,m.TELDDD AS DDD,m.TELNUMERO AS CELULAR, g.PESDTCADASTRO AS DTCAD, f.CLIULTIMAATUALIZACAO AS DTULTCOMP, o.PESNOME AS FILIAL, f.CLIID AS IDCLIENTE
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
                           ORDER BY TOTALVAL desc ");
 } else {
  $read = $conn->prepare("SELECT DISTINCT p.STATUS AS STATUS,g.PESNOME AS CLIENTE,sum(a.LCTVALOR) AS TOTALVAL,m.TELDDD AS DDD,m.TELNUMERO AS CELULAR, g.PESDTCADASTRO AS DTCAD, f.CLIULTIMAATUALIZACAO AS DTULTCOMP, o.PESNOME AS FILIAL,f.CLIID AS IDCLIENTE
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
                           ORDER BY TOTALVAL desc  ");
}

              

$read->setFetchMode(PDO::FETCH_ASSOC);            
$read->execute();   
$array = $read->fetchAll();   

    foreach ($array as $dados): 
           
                   $CLIENTE = $dados["CLIENTE"];   
                   $TOTALVAL = $dados["TOTALVAL"];
                   $DDD = $dados["DDD"];
                   $CELULAR = $dados["CELULAR"];
                   $DTCAD = $dados["DTCAD"];
                   $DTULTCOMP = $dados["DTULTCOMP"];
                   $FILIAL = $dados["FILIAL"];
                   $STATUS = $dados["STATUS"];
                   $IDCLIENTE = $dados["IDCLIENTE"];



         
                   extract($dados);

       

                   $GERAL["GERAL"][] = [

                     'CLIENTE' =>  utf8_decode($CLIENTE), 
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