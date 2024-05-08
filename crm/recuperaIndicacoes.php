
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



               $read = $conn->prepare("SELECT DISTINCT a.ID AS IDINDICACAO ,c.PESNOME AS INDICADOR,d.TELDDD AS DDD,d.TELNUMERO AS CELULAR,a.NOME_INDICADO AS INDICADO, a.NUMERO_INDICADO AS NUMERO, a.CONVERSAO AS CONVERSAO
                    FROM TB_CAM_INDICACAO a
                    LEFT JOIN TB_CLI_CLIENTE b ON b.CLIID = a.ID_CLIENTE 
                    LEFT JOIN TB_PES_PESSOA c ON c.PESID = b.PESID 
                    LEFT JOIN TB_TEL_TELEFONE d ON d.PESID = c.PESID 
                    WHERE a.ID_CAMPANHA = {$id}");
          

$read->setFetchMode(PDO::FETCH_ASSOC);        
$read->execute();   
$array = $read->fetchAll();   


    foreach ($array as $dados): 

                    $IDINDICACAO = $dados["IDINDICACAO"]; 
           
                   $INDICADOR = $dados["INDICADOR"];                  
                   $DDD = $dados["DDD"];
                   $CELULAR = $dados["CELULAR"];

                   $INDICADO = $dados["INDICADO"];
                   $NUMERO = $dados["NUMERO"];
                   $CONVERSAO = $dados["CONVERSAO"];

         

                   extract($dados);

       

                   $CLIENTES["CLIENTES"][] = [

                     'IDINDICACAO' =>  $IDINDICACAO,
                     'INDICADOR' =>  utf8_decode($INDICADOR),        
                     'INDICADO' =>  utf8_decode($INDICADO),                
                     'DDD' =>  $DDD,
                     'CELULAR' => $CELULAR,
                     'NUMERO' => $NUMERO, 
                     'CONVERSAO' => number_format($CONVERSAO,2,",",""),                                  
                   ];

                     endforeach;  

                //Reposta com status 200
                http_response_code(200);

                //Retornar as informações em json
                echo json_encode($CLIENTES);  

                //echo "Total geral: {$qtd}";

              ?>