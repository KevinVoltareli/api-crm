<?php

//Cabecalhos obrigatorios
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

//Incluir a conexao
  // Create a new PDO connection
    $conn = new PDO("firebird:dbname=C:\SavWinRevo\Servidor\DataBase\BDSAVWINREVO.FDB; charset=UTF-8", "SYSDBA", "masterkey");
    // Set the PDO error mode to exception
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
$id = filter_input(INPUT_GET, 'id');


if($id != ''){

               $read = $conn->prepare("SELECT DISTINCT a.STATUS AS STATUS, a.CRIADO AS CRIADO,g.PESNOME AS CLIENTE,m.TELDDD AS DDD,m.TELNUMERO AS CELULAR, g.PESDTCADASTRO AS DTCAD, f.CLIULTIMAATUALIZACAO AS DTULTCOMP, o.PESNOME AS FILIAL, f.CLIID AS IDCLIENTE, a.CAMPANHA_ID AS CAMPANHA_ID
                           FROM TB_CAM_CLIENTE a            
                           FULL JOIN TB_CAM_CAMPANHA b ON b.ID = a.CAMPANHA_ID         
                           left JOIN TB_CLI_CLIENTE f ON f.CLIID = a.ID_CLIENTE 
                           INNER JOIN TB_PES_PESSOA g ON g.PESID = f.PESID 
                           INNER JOIN TB_INT_INTERNET l ON l.PESID = f.PESID 
                           LEFT JOIN TB_TEL_TELEFONE m ON m.PESID = g.PESID
                           INNER JOIN TB_PES_PESSOA o ON o.PESID = g.PESID  
                           WHERE m.TELTIPO = 'C'
                           AND a.CAMPANHA_ID = {$id}
                           AND  m.TELDDD > '0' ");
             } else{
              $read = $conn->prepare("SELECT DISTINCT a.STATUS AS STATUS, a.CRIADO AS CRIADO,g.PESNOME AS CLIENTE,m.TELDDD AS DDD,m.TELNUMERO AS CELULAR, g.PESDTCADASTRO AS DTCAD, f.CLIULTIMAATUALIZACAO AS DTULTCOMP, o.PESNOME AS FILIAL, f.CLIID AS IDCLIENTE, a.CAMPANHA_ID AS CAMPANHA_ID
                           FROM TB_CAM_CLIENTE a            
                           FULL JOIN TB_CAM_CAMPANHA b ON b.ID = a.CAMPANHA_ID         
                           left JOIN TB_CLI_CLIENTE f ON f.CLIID = a.ID_CLIENTE 
                           INNER JOIN TB_PES_PESSOA g ON g.PESID = f.PESID 
                           INNER JOIN TB_INT_INTERNET l ON l.PESID = f.PESID 
                           LEFT JOIN TB_TEL_TELEFONE m ON m.PESID = g.PESID
                           INNER JOIN TB_PES_PESSOA o ON o.PESID = g.PESID  
                           WHERE m.TELTIPO = 'C'
                           AND  m.TELDDD > '0' ");
             }

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
                   $CRIADO = $dados["CRIADO"];
                   $CRIADO = $dados["CAMPANHA_ID"];

         

                   extract($dados);

       

                   $CLIENTES["CLIENTES"][] = [

                     'CLIENTE' =>  $CLIENTE,                      
                     'DDD' =>  $DDD,
                     'CELULAR' => $CELULAR,
                     'DTCAD' => $DTCAD, 
                     'DTULTCOMP' => $DTULTCOMP,  
                     'IDCLIENTE' => $IDCLIENTE,  
                     'STATUS' => $STATUS,                         
                     'CRIADO' => $CRIADO,                       
                     'CAMPANHA_ID' => $CAMPANHA_ID,                                
                   ];

                     endforeach;  

                //Reposta com status 200
                http_response_code(200);

                //Retornar as informações em json
                echo json_encode($CLIENTES);  

                //echo "Total geral: {$qtd}";

              ?>