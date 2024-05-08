
<?php

//Cabecalhos obrigatorios
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

//Incluir a conexao
include_once '../conexao.php';

               $read = $conn->prepare("SELECT ID AS ID, DATA_CRIACAO AS DATA, NOME AS NOME,  PLATAFORMA AS PLATAFORMA
                                       FROM TB_CAM_CAMPANHA 
                                       ORDER BY DATA_CRIACAO DESC");

$read->setFetchMode(PDO::FETCH_ASSOC);        
$read->execute();   
$array = $read->fetchAll();   


    foreach ($array as $dados): 
           
                   $ID = $dados["ID"];  
                   $NOME = $dados["NOME"];  
                   $DATA = $dados["DATA"];  
                   $PLATAFORMA = $dados["PLATAFORMA"];  

            extract($dados);


                   $CAMPANHAS["CAMPANHAS"][] = [

                     'NOME' => $NOME, 
                     'DATA' => $DATA, 
                     'PLATAFORMA' => $PLATAFORMA, 
                     'ID' => number_format($ID,0,",",""),  

                   ];
      endforeach;   

                //Reposta com status 200
                http_response_code(200);

                //Retornar as informações em json
                echo json_encode($CAMPANHAS);  

                //echo "Total geral: {$qtd}";