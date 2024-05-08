
<?php

//Cabecalhos obrigatorios
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

//Incluir a conexao
include_once '../conexao.php';
$id = filter_input(INPUT_GET, 'id');


               $read = $conn->prepare("SELECT COMENTARIO AS COMENTARIO, DATA AS DATA, b.NOME AS NOME
                                       FROM TB_CAM_COMENTARIO a
                                       left JOIN TB_CRM_USUARIOS b ON a.USER_EMAIL = b.EMAIL
                                       WHERE 
                                       ID_CLIENTE = {$id}
                                       ORDER BY DATA DESC");
           

$read->setFetchMode(PDO::FETCH_ASSOC);        
$read->execute();   
$array = $read->fetchAll();   


    foreach ($array as $dados): 
           
                   $COMENTARIO = $dados["COMENTARIO"];      
                   $DATA = $dados["DATA"];
                   $NOME = $dados["NOME"];


         

                   extract($dados);

       

                   $COMENTARIOS["COMENTARIOS"][] = [

                     'COMENTARIO' => $COMENTARIO,  
                     'NOME' => $NOME,  
                     'DATA' =>date("d/m/Y H:i",strtotime($DATA)),                                 
                   ];

                     endforeach;  

                //Reposta com status 200
                http_response_code(200);

                //Retornar as informações em json
                echo json_encode($COMENTARIOS);  

                //echo "Total geral: {$qtd}";

              ?>