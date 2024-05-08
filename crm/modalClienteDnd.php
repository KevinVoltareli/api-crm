
<?php

//Cabecalhos obrigatorios
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

//Incluir a conexao
include_once '../conexao.php';
$id = filter_input(INPUT_GET, 'id');


               $read = $conn->prepare("SELECT DISTINCT g.PESNOME AS CLIENTE,m.TELDDD AS DDD,m.TELNUMERO AS CELULAR, g.PESDTCADASTRO AS DTCAD, f.CLIULTIMAATUALIZACAO AS DTULTCOMP,
o.PESNOME AS FILIAL, f.CLIID AS IDCLIENTE, p.STATUS AS STATUS
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
                           WHERE 
                           f.CLIID = {$id}
                           GROUP BY CLIENTE,CELULAR,DTCAD, DTULTCOMP,DDD, FILIAL,IDCLIENTE,STATUS");
           

$read->setFetchMode(PDO::FETCH_ASSOC);        
$read->execute();   
$array = $read->fetchAll();   


    foreach ($array as $dados): 
           
                   $CLIENTE = $dados["CLIENTE"];                  
                   $DDD = $dados["DDD"];
                   $CELULAR = $dados["CELULAR"];
                   $DTCAD = $dados["DTCAD"];
                   $DTULTCOMP = $dados["DTULTCOMP"];
                   $IDCLIENTE = $dados["IDCLIENTE"];
                   $STATUS = $dados["STATUS"];
         

                   extract($dados);

       

                   $CLIENTES["CLIENTES"][] = [

                     'CLIENTE' =>  utf8_decode($CLIENTE),                      
                     'DDD' =>  $DDD,
                     'CELULAR' => $CELULAR,
                     'DTCAD' => date("d/m/Y",strtotime($DTCAD)),
                     'DTULTCOMP' => date("d/m/Y",strtotime($DTULTCOMP)),
                     'IDCLIENTE' => $IDCLIENTE,          
                     'STATUS' => $STATUS,                              
                   ];

                     endforeach;  

                //Reposta com status 200
                http_response_code(200);

                //Retornar as informações em json
                echo json_encode($CLIENTES);  

                //echo "Total geral: {$qtd}";

              ?>