
<?php

//Cabecalhos obrigatorios
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

//Incluir a conexao
include_once '../conexao.php';
$id = filter_input(INPUT_GET, 'id');


               $read = $conn->prepare("SELECT DISTINCT  q.LVEDESCRICAO AS LOG, q.LVEDATASISTEMA AS DATA
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
                           INNER JOIN TB_LVE_LOGVENDA q ON q.PEDID = e.PEDID 
                           WHERE 
                           f.CLIID = {$id}");
           

$read->setFetchMode(PDO::FETCH_ASSOC);        
$read->execute();   
$array = $read->fetchAll();   


    foreach ($array as $dados): 
           
                   $LOG = $dados["LOG"]; 
                   $DATA = $dados["DATA"];     
         

                   extract($dados);

       

                   $CLIENTES["CLIENTES"][] = [

                     'LOG' => $LOG,  
                     'DATA' => date("d/m/Y",strtotime($DATA)),                                     
                   ];

                     endforeach;  

                //Reposta com status 200
                http_response_code(200);

                //Retornar as informações em json
                echo json_encode($CLIENTES);  

                //echo "Total geral: {$qtd}";

              ?>