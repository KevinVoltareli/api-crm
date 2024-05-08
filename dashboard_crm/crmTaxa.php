
<?php

//Cabecalhos obrigatorios
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

//Incluir a conexao
include_once '../conexao.php';
$id = filter_input(INPUT_GET, 'id');
$dataInicial = filter_input(INPUT_GET, 'dataInicial');
$dataFinal = filter_input(INPUT_GET, 'dataFinal');



               $read = $conn->prepare("SELECT STATUS AS STATUS, count(RESPOSTA) AS RESPOSTA, count(STATUS) AS STATUSCOUNT, sum(VALOR_CONVERSAO) AS VALORCONVERSAO
                                      FROM TB_CAM_CLIENTE
                                      WHERE CAMPANHA_ID = {$id}
                                      AND   CREATED_AT >= {$dataInicial} AND CREATED_AT <= {$dataFinal} 
                                      GROUP BY STATUS ");
                                                       

$read->setFetchMode(PDO::FETCH_ASSOC);        
$read->execute();   
$array = $read->fetchAll();   


    foreach ($array as $dados): 
           
                   $STATUS = $dados["STATUS"];                  
                   $RESPOSTA = $dados["RESPOSTA"];
                   $STATUSCOUNT = $dados["STATUSCOUNT"];
                   $VALORCONVERSAO = $dados["VALORCONVERSAO"];

         

         $TX = $RESPOSTA/$STATUSCOUNT;
         $TXX = $TX*100;
        
       

                   extract($dados);

       

                   $CRMTX["CRMTX"][] = [
                  
                     'STATUS' =>  $STATUS,
                     'RESPOSTA' => $RESPOSTA,
                     'STATUSCOUNT' => $STATUSCOUNT,  
                     'VALORCONVERSAO' => number_format($VALORCONVERSAO,2,',', '.'),
                     'TXX' =>  number_format($TXX, 2, ',', '.'),                            
                   ];

                     endforeach;  

                //Reposta com status 200
                http_response_code(200);

                //Retornar as informações em json
                echo json_encode($CRMTX);  

                //echo "Total geral: {$qtd}";

              ?>