
<?php

//Cabecalhos obrigatorios
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

//Incluir a conexao
include_once '../conexao.php';
$id = filter_input(INPUT_GET, 'id');



               $read = $conn->prepare("SELECT sum(CONVERSAO) AS VALORCONVERSAO, COUNT(NOME_INDICADO) AS INDICADOCOUNT, sum(CONVERSAO)/COUNT(NOME_INDICADO) AS TM 
                  FROM TB_CAM_INDICACAO 
                  WHERE ID_CAMPANHA = {$id}
                  ");
                                                                         

$read->setFetchMode(PDO::FETCH_ASSOC);        
$read->execute();   
$array = $read->fetchAll();   


    foreach ($array as $dados): 
                          
                   $INDICADOCOUNT = $dados["INDICADOCOUNT"];
                   $VALORCONVERSAO = $dados["VALORCONVERSAO"];
                   $TM = $dados["TM"];    


                   extract($dados);

       
                   $CRMTX["CRMTX"][] = [
                  
                     'INDICADOCOUNT' => $INDICADOCOUNT,
                     'VALORCONVERSAO' =>  number_format($VALORCONVERSAO,2,',', '.'),        
                     'TM' =>  $TM,                        
                   ];

                     endforeach;  

                //Reposta com status 200
                http_response_code(200);

                //Retornar as informações em json
                echo json_encode($CRMTX);  

                //echo "Total geral: {$qtd}";

              ?>